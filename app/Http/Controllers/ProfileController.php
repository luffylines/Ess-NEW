<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Format phone number to ensure it has +63 prefix
     */
    private function formatPhoneNumber($phone)
    {
        if (empty($phone)) {
            return null;
        }

        // Remove all non-digit characters except +
        $phone = preg_replace('/[^\d+]/', '', $phone);

        // If it starts with +63, keep it as is
        if (str_starts_with($phone, '+63')) {
            return $phone;
        }

        // If it starts with 63, add +
        if (str_starts_with($phone, '63')) {
            return '+' . $phone;
        }

        // If it starts with 09, replace with +639
        if (str_starts_with($phone, '09')) {
            return '+63' . substr($phone, 1);
        }

        // If it starts with 9 and is 10 digits, add +63
        if (str_starts_with($phone, '9') && strlen($phone) == 10) {
            return '+63' . $phone;
        }

        // Otherwise, assume it's a local number and add +639
        return '+639' . ltrim($phone, '0');
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information (name, phone, address, gender, profile_photo).
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
           'name' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'in:male,female,other'],
            'phone' => ['required', 'regex:/^\+63[0-9]{10}$/'],
            'address' => ['required', 'string', 'max:500'],
            'profile_photo' => ['nullable', 'file', 'image', 'max:2048'],
        ]);

        $user = $request->user();

        // ✅ Handle profile photo upload to Cloudinary
        if ($request->hasFile('profile_photo')) {
            // Delete old photo from Cloudinary if it exists (optional, requires storing public_id)
            // For now, just overwrite

            $uploadedFileUrl = \CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary::upload($request->file('profile_photo')->getRealPath(), [
                'folder' => 'profile_photos',
                'public_id' => 'user_' . $user->id . '_' . time(),
            ])->getSecurePath();

            // Save Cloudinary URL in DB
            $validated['profile_photo'] = $uploadedFileUrl;
        }

        // Format phone number to include +63 prefix
        $formattedPhone = $this->formatPhoneNumber($validated['phone'] ?? null);

        // Update user info
        $user->fill([
            'name' => $validated['name'],
            'phone' => $formattedPhone,
            'address' => $validated['address'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'profile_photo' => $validated['profile_photo'] ?? $user->profile_photo,
        ])->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update the user's email address.
     */
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

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $user = $request->user();
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return Redirect::route('profile.edit')->with('status', 'password-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Optionally: Delete profile photo from Cloudinary if needed (not implemented)

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/')->with('status', 'Your account has been deleted.');
    }
}
