<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
            'role' => ['required', 'in:employee,hr,admin'],
            'g-recaptcha-response' => ['required'],
        ];
    }

    /**
     * Custom validation messages
     */
    public function messages(): array
    {
        return [
            'login.required' => 'Please enter your Employee ID or Email.',
            'g-recaptcha-response.required' => 'Please complete the reCAPTCHA verification.',
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // Validate reCAPTCHA
        $this->validateRecaptcha();

        // Determine if login is email or employee_id
        $loginField = filter_var($this->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'employee_id';
        
        $credentials = [
            $loginField => $this->login,
            'password' => $this->password,
            'role' => $this->role,
        ];

        if (! Auth::attempt($credentials, $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'login' => 'The provided credentials are incorrect or you are not authorized for this role.',
            ]);
        }

        // Log successful login activity
        $user = Auth::user();
        $user->logLogin($loginField); // Log with the login method used (email or employee_id)

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Validate reCAPTCHA response
     */
    protected function validateRecaptcha(): void
    {
        $recaptchaResponse = $this->input('g-recaptcha-response');
        
        if (empty($recaptchaResponse)) {
            throw ValidationException::withMessages([
                'g-recaptcha-response' => 'Please complete the reCAPTCHA verification.',
            ]);
        }
        
        $secretKey = '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe'; // Google's test secret key

        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secretKey}&response={$recaptchaResponse}");
        $responseData = json_decode($response, true);

        if (!$responseData['success']) {
            $errorMessage = 'reCAPTCHA verification failed.';
            
            // Add specific error details for debugging
            if (isset($responseData['error-codes'])) {
                $errorCodes = $responseData['error-codes'];
                if (in_array('invalid-input-secret', $errorCodes)) {
                    $errorMessage = 'reCAPTCHA configuration error. Please contact administrator.';
                } elseif (in_array('invalid-input-response', $errorCodes)) {
                    $errorMessage = 'reCAPTCHA response is invalid. Please try again.';
                } elseif (in_array('bad-request', $errorCodes)) {
                    $errorMessage = 'reCAPTCHA request is malformed. Please try again.';
                }
            }
            
            throw ValidationException::withMessages([
                'g-recaptcha-response' => $errorMessage,
            ]);
        }
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'login' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('login')).'|'.$this->ip());
    }
}
