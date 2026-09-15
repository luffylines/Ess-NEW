<?php

namespace App\Http\Controllers;

use App\Models\UserProfilePhoto;
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

    /**
     * Resize and compress a profile photo before saving it in the database.
     * This keeps the persistent DB fallback small and fast to serve.
     *
     * @return array{0:string,1:string}
     */
    private function optimizeProfilePhoto($file): array
    {
        $raw = file_get_contents($file->getRealPath());

        if ($raw === false) {
            throw new \RuntimeException('Unable to read the uploaded image.');
        }

        if (!function_exists('imagecreatefromstring')) {
            return [$raw, $file->getMimeType() ?: 'image/jpeg'];
        }

        $source = @imagecreatefromstring($raw);

        if (!$source) {
            return [$raw, $file->getMimeType() ?: 'image/jpeg'];
        }

        // Respect EXIF orientation for phone-camera JPEGs when available.
        $extension = strtolower((string) $file->getClientOriginalExtension());
        if (function_exists('exif_read_data') && in_array($extension, ['jpg', 'jpeg'], true)) {
            $exif = @exif_read_data($file->getRealPath());
            $orientation = (int) ($exif['Orientation'] ?? 1);

            if ($orientation === 3) {
                $source = imagerotate($source, 180, 0) ?: $source;
            } elseif ($orientation === 6) {
                $source = imagerotate($source, -90, 0) ?: $source;
            } elseif ($orientation === 8) {
                $source = imagerotate($source, 90, 0) ?: $source;
            }
        }

        $width = imagesx($source);
        $height = imagesy($source);
        $maxDimension = 512;
        $scale = min(1, $maxDimension / max($width, $height));
        $targetWidth = max(1, (int) round($width * $scale));
        $targetHeight = max(1, (int) round($height * $scale));

        $canvas = imagecreatetruecolor($targetWidth, $targetHeight);
        $white = imagecolorallocate($canvas, 255, 255, 255);
        imagefill($canvas, 0, 0, $white);
        imagecopyresampled(
            $canvas,
            $source,
            0,
            0,
            0,
            0,
            $targetWidth,
            $targetHeight,
            $width,
            $height
        );

        ob_start();
        imagejpeg($canvas, null, 84);
        $optimized = ob_get_clean();

        imagedestroy($canvas);
        imagedestroy($source);

        if (!is_string($optimized) || $optimized === '') {
            return [$raw, $file->getMimeType() ?: 'image/jpeg'];
        }

        return [$optimized, 'image/jpeg'];
    }

    /**
     * Store the photo in the database as a durable fallback for hosts with
     * ephemeral filesystems such as Render free web services.
     */
    private function storePhotoInDatabase($user, $file): void
    {
        [$binary, $mimeType] = $this->optimizeProfilePhoto($file);

        UserProfilePhoto::updateOrCreate(
            ['user_id' => $user->id],
            [
                'mime_type' => $mimeType,
                'image_data' => base64_encode($binary),
                'size_bytes' => strlen($binary),
            ]
        );
    }

    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Serve a database-backed profile photo to authenticated ESS users.
     */
    public function photo(Request $request, int $userId)
    {
        $photo = UserProfilePhoto::where('user_id', $userId)->firstOrFail();
        $binary = base64_decode($photo->image_data, true);

        abort_if($binary === false, 404);

        return response($binary, 200, [
            'Content-Type' => $photo->mime_type ?: 'image/jpeg',
            'Content-Length' => (string) strlen($binary),
            'Cache-Control' => 'private, max-age=3600',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    /**
     * Update profile details and profile photo.
     *
     * Cloudinary remains preferred when configured. Otherwise, photos are
     * stored in the database so they survive Render restarts and deploys.
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

                    UserProfilePhoto::where('user_id', $user->id)->delete();
                    $validated['profile_photo'] = $uploadedFileUrl;
                    $photoStatus = 'Profile photo saved permanently.';
                } else {
                    $this->storePhotoInDatabase($user, $file);
                    $validated['profile_photo'] = 'database';
                    $photoStatus = 'Profile photo saved permanently.';
                }

                // Clean up a previous local fallback file if it still exists.
                if ($oldPhoto
                    && $oldPhoto !== 'database'
                    && !str_starts_with($oldPhoto, 'http://')
                    && !str_starts_with($oldPhoto, 'https://')) {
                    $oldPath = ltrim(str_replace('storage/', '', $oldPhoto), '/');
                    if (Storage::disk('public')->exists($oldPath)) {
                        Storage::disk('public')->delete($oldPath);
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('Cloud profile upload failed; using durable database fallback.', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                ]);

                try {
                    $this->storePhotoInDatabase($user, $file);
                    $validated['profile_photo'] = 'database';
                    $photoStatus = 'Profile photo saved permanently.';
                } catch (\Throwable $fallbackError) {
                    Log::error('Persistent profile photo fallback failed.', [
                        'user_id' => $user->id,
                        'error' => $fallbackError->getMessage(),
                    ]);

                    return Redirect::route('profile.edit')
                        ->withInput($request->except('profile_photo'))
                        ->withErrors(['profile_photo' => 'The image could not be saved permanently. Please try another JPG, PNG, or WebP image.']);
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
