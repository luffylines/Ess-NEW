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
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:1000'],
            'gender' => ['nullable', 'in:male,female,other'],
            'profile_photo' => ['nullable', 'file', 'image', 'max:2048'],
        ]);

        $user = $request->user();

        // ✅ Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo safely if it exists
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            $file = $request->file('profile_photo');
            $filename = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();

            // Store the new file in storage/app/public/profile_photos
            $path = $file->storeAs('profile_photos', $filename, 'public');

            // Save relative path in DB (e.g. "profile_photos/filename.jpg")
            $validated['profile_photo'] = $path;
        }

        // Update user info
        $user->fill([
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
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

        // Delete profile photo if exists
        if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/')->with('status', 'Your account has been deleted.');
    }
}
