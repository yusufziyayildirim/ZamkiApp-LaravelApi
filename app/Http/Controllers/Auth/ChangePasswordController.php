<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Controllers\Response;
use Illuminate\Support\Facades\Hash;


class ChangePasswordController extends Controller
{
    public function change_password(ChangePasswordRequest $request){
        $loggeduser = auth()->user();
        if(Hash::check($request->password, $loggeduser->password)){
            $loggeduser->password = Hash::make($request->new_password);
            $loggeduser->save();
            return Response::withoutData(true, 'Password Changed Successfully', 200);
        }
        return Response::withoutData(false, 'Old password is incorrect', 400);
    }
}
