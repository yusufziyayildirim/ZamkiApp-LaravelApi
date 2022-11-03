<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Mail;
use Illuminate\Mail\Message;
use App\Http\Requests\Auth\PasswordResetRequest;
use App\Http\Controllers\Response;

class PasswordResetController extends Controller
{
    public function send_reset_password_email(Request $request){
        $request->validate([
            'email' => 'required|email',
        ]);
        $email = $request->email;

        // Check User's Email Exists or Not
        $user = User::where('email', $email)->first();
        if(!$user){
            return Response::withoutData(false, 'Email doesnt exists', 404);
        }

        // Generate Token
        $token = Str::random(60);
        // Saving Data to Password Reset Table
        PasswordReset::create([
            'email'=>$email,
            'token'=>$token
        ]);

        // Sending EMail with Password Reset View
        Mail::send('auth.password-reset-mail', ['token'=>$token], function(Message $message) use ($email){
            $message->subject('Reset Your Password');
            $message->to($email);
        });

        return Response::withoutData(true, 'Password Reset Email Sent... Check Your Email', 200);
    }

    public function reset_form($token){
        $control = PasswordReset::where('token', '=', $token)->delete();

        if($control){
            return view('auth.password-reset-form', compact('token'));
        }

        return "Page not found";
    }

    public function reset(PasswordResetRequest $request, $token){
        // Delete Token older than 2 minute
        $formatted = Carbon::now()->subMinutes(2)->toDateTimeString();
        PasswordReset::where('created_at', '<=', $formatted)->delete();

        $passwordreset = PasswordReset::where('token', $token)->first();

        if(!$passwordreset){
            return Response::withoutData(false, 'Token is Invalid or Expired', 404);
        }

        $user = User::where('email', $passwordreset->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete the token after resetting password
        PasswordReset::where('email', $user->email)->delete();

        return Response::withoutData(true, 'Password Reset Success', 200);

        return response([
            'message'=>'Password Reset Success',
            'status'=>'success'
        ], 200);

    }
}
