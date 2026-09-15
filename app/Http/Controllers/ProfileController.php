<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Format a Philippine mobile number to +63 format when one is provided.
     */
    private function formatPhoneNumber(?string $phone): ?string
    {
        if (blank($phone)) {
            return null;
        }

        $phone = preg_replace('/[^\d+]/', '', $phone);

        if (str_starts_with($phone, '+63')) {
            return $phone;
        }

        if (str_starts_with($phone, '63')) {
            return '+' . $phone;
        }

        if (str_starts_with($phone, '09')) {
            return '+63' . substr($phone, 1);
        }

        if (str_starts_with($phone, '9') && strlen($phone) === 10) {
            return '+63' . $phone;
        }

        return '+639' . ltrim($phone, '0');
    }

    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update profile details and profile photo.
     *
     * Cloudinary is preferred in production. When Cloudinary credentials are
     * not configured, the app falls back to Laravel's public disk so uploads
     * still work instead of failing with a 500 error.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'gender' => ['nullable', 'in:male,female,other'],
            'phone' => ['nullable', 'regex:/^\+63[0-9]{10}$/'],
            'address' => ['nullable', 'string', 'max:500'],
            'profile_photo' => ['nullable', 'file', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
        ], [
            'profile_photo.max' => 'Please choose an image smaller than 5 MB.',
            'profile_photo.image' => 'The selected profile photo must be a valid image.',
            'phone.regex' => 'Use the format +639XXXXXXXXX for the phone number.',
        ]);

        $user = $request->user();
        $photoStatus = null;

        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $oldPhoto = $user->profile_photo;
            $cloudinaryConfigured = filled(config('cloudinary.cloud_url'))
                || (filled(config('cloudinary.cloud.cloud_name'))
                    && filled(config('cloudinary.cloud.api_key'))
                    && filled(config('cloudinary.cloud.api_secret')));

            try {
                if ($cloudinaryConfigured) {
                    $uploadedFileUrl = \CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary::upload(
                        $file->getRealPath(),
                        [
                            'folder' => 'profile_photos',
                            'public_id' => 'user_' . $user->id . '_' . time(),
                            'overwrite' => true,
                            'resource_type' => 'image',
                            'transformation' => [
                                'width' => 900,
                                'height' => 900,
                                'crop' => 'limit',
                                'quality' => 'auto:good',
                                'fetch_format' => 'auto',
                            ],
                        ]
                    )->getSecurePath();

                    $validated['profile_photo'] = $uploadedFileUrl;
                    $photoStatus = 'Profile photo uploaded successfully.';
                } else {
                    $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
                    $filename = 'user_' . $user->id . '_' . time() . '.' . $extension;
                    $validated['profile_photo'] = $file->storeAs('profile_photos', $filename, 'public');
                    $photoStatus = 'Profile photo uploaded successfully.';
                }

                // Remove a previous local photo after the new upload succeeds.
                if ($oldPhoto && !str_starts_with($oldPhoto, 'http://') && !str_starts_with($oldPhoto, 'https://')) {
                    $oldPath = ltrim(str_replace('storage/', '', $oldPhoto), '/');
                    if (Storage::disk('public')->exists($oldPath)) {
                        Storage::disk('public')->delete($oldPath);
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('Cloud profile upload failed; attempting local fallback.', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                ]);

                try {
                    $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
                    $filename = 'user_' . $user->id . '_' . time() . '.' . $extension;
                    $validated['profile_photo'] = $file->storeAs('profile_photos', $filename, 'public');
                    $photoStatus = 'Profile photo uploaded successfully.';
                } catch (\Throwable $fallbackError) {
                    Log::error('Profile photo fallback upload failed.', [
                        'user_id' => $user->id,
                        'error' => $fallbackError->getMessage(),
                    ]);

                    return Redirect::route('profile.edit')
                        ->withInput($request->except('profile_photo'))
                        ->withErrors(['profile_photo' => 'The image could not be saved. Please try another JPG, PNG, or WebP image.']);
                }
            }
        }

        $user->fill([
            'name' => $validated['name'],
            'phone' => array_key_exists('phone', $validated)
                ? $this->formatPhoneNumber($validated['phone'])
                : $user->phone,
            'address' => $validated['address'] ?? $user->address,
            'gender' => $validated['gender'] ?? $user->gender,
            'profile_photo' => $validated['profile_photo'] ?? $user->profile_photo,
        ])->save();

        return Redirect::route('profile.edit')->with([
            'status' => 'profile-updated',
            'profile_message' => $photoStatus ?: 'Profile updated successfully.',
        ]);
    }

    public function updateEmail(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $request->user()->id],
        ]);

        $user = $request->user();

        if ($validated['email'] !== $user->email) {
            $user->forceFill([
                'email' => $validated['email'],
                'email_verified_at' => null,
            ])->save();

            if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail) {
                $user->sendEmailVerificationNotification();
                return Redirect::route('profile.edit')->with('status', 'verification-link-sent');
            }
        }

        return Redirect::route('profile.edit')->with('status', 'email-updated');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return Redirect::route('profile.edit')->with('status', 'password-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/')->with('status', 'Your account has been deleted.');
    }
}
