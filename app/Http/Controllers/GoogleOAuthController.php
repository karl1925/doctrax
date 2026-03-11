<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Notifications\AccountCreatedNotification;
use Illuminate\Http\Request;


class GoogleOAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->scopes([
                'profile',
            ])
            ->with(['access_type' => 'offline', 'response_type' => 'code'])
            ->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
    try {
        // Use stateless if you don’t rely on sessions
        $googleUser = Socialite::driver('google')->stateless()->user();
    } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
        return redirect()->route('login')->with('error', 'Google login failed. Please try again.');
    }

    // Check by Google ID first
    $existingUser = User::withoutTrashed()->where('google_id', $googleUser->id)->first();

    if ($existingUser) {
        // Update token info
        $existingUser->update([
            'google_token' => $googleUser->token,
            'google_refresh_token' => $googleUser->refreshToken,
            'google_token_expires' => now()->addSeconds($googleUser->expiresIn),
            'profile_photo_path' => $existingUser->profile_photo_path ?: $googleUser->avatar,
        ]);

        auth()->login($existingUser);
        $msg = 'Logged in successfully.';

    } else {
        // Check by email if Google ID not found
        $existingEmail = User::withoutTrashed()->where('email', $googleUser->email)->first();

        if ($existingEmail) {
            $existingEmail->update([
                'google_id' => $googleUser->id,
                'google_token' => $googleUser->token,
                'google_refresh_token' => $googleUser->refreshToken,
                'google_token_expires' => now()->addSeconds($googleUser->expiresIn),
                'profile_photo_path' => $existingEmail->profile_photo_path ?: $googleUser->avatar,
            ]);

            auth()->login($existingEmail);
            $msg = 'Logged in successfully.';

        } else {
            // Automatic account creation controlled by config
            if (config('modules.allow_new_govmail')) {
                $plainPassword = Str::random(12);

                $newUser = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'google_token' => $googleUser->token,
                    'google_refresh_token' => $googleUser->refreshToken,
                    'google_token_expires' => now()->addSeconds($googleUser->expiresIn),
                    'password' => Hash::make($plainPassword),
                    'profile_photo_path' => $googleUser->avatar,
                ]);

                $newUser->notify(new AccountCreatedNotification($plainPassword));
                auth()->login($newUser);

                $msg = "Your DocTrax account has been successfully created. We’ve sent your login credentials to your email. You can change your password anytime from your profile page.";

            } else {
                return redirect()->route('login')
                    ->with('error', 'Automatic account creation for GovMail addresses is currently disabled. For access, please reach out to miss.region2@dict.gov.ph.');
            }
        }
    }

    return redirect()->intended('/dashboard')->with('success', $msg);
    }
}