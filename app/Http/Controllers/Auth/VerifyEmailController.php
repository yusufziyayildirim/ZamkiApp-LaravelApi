<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Response;
use App\Models\User;
use Illuminate\Auth\Events\Verified;

class VerifyEmailController extends Controller
{
    public function verify($id, $hash)
    {
        $user = User::find($id);
        abort_if(!$user, 403);
        abort_if(!hash_equals($hash, sha1($user->getEmailForVerification())), 403);

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new Verified($user));
        }
        return view('auth.verified-account');
    }

    public function resendNotification(Request $request) {
        $request->validate([
            'email' => 'required|email',
        ]);
        $email = $request->email;
        $user = User::where('email', $email)->first();
        $user->sendEmailVerificationNotification();
        return Response::withoutData(true, 'Verification link sent', 200);
    }
}
