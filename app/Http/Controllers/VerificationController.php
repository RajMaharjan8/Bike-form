<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    //
    public function verifyEmail($token)
    {
        $user = User::where('verification_token', $token)->first();

        if ($user) {
        
            $user->update(['verification_token' => null, 'email_verified_at' => now()]);

            return redirect('/')->with('success', 'Email verified successfully.');
        }

        return redirect('/')->with('error', 'Invalid verification token.');
    }
}
