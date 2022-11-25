<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Http\Controllers\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
    public function register(RegisterRequest $request){
        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password)
        ]);
        event(new Registered($user));
        return Response::withoutData(true, 'Registration Success.. Check your email for verification', 200);
    }

    public function login(LoginRequest $request){
        $user = User::where('email', $request->email)->first();
        if($user && Hash::check($request->password, $user->password)){
            if($user->email_verified_at){
                $token = $user->createToken($request->email)->plainTextToken;
                return Response::withData(true, 'Login Success', $token, 200);
            }
            return Response::withoutData(false, 'Verify e-mail address', 400);
        }
        return Response::withoutData(false, 'Email or password is incorrect', 400);
    }

    public function logout(){
        auth()->user()->currentAccessToken()->delete();
        return Response::withData(true, 'Logout Success', auth()->user(), 200);
    }

    public function logged_user(){
        $loggeduser = User::where('id',  auth()->id())
        ->with('NativeIn','AlsoSpeaking','Learning')
        ->get();
        return Response::withData(true, 'Logged User Data', $loggeduser, 200);
    }
}
