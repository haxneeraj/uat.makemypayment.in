<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
    
    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect('/login')->withErrors(['email' => 'Google login failed. Please try again.']);
        }
        
        // Check if the user exists
        $existingUser = User::where('email', $user->email)->first();
        
        if($existingUser) {
            // User exists, login
            Auth::login($existingUser, true);
        } else {
            // Create a new user
            $newUser = User::create([
                'name' => $user->name,
                'email' => $user->email,
                'password' => bcrypt(str_random(16)), // Generate a random password
                'google_id' => $user->id,
            ]);
            
            Auth::login($newUser, true);
        }
        
        return redirect()->intended('/dashboard');
    }
}
