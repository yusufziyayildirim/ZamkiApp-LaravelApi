<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Http\Controllers\Response;
use Illuminate\Http\Request;


class UserController extends Controller
{
    public function getAllUser(){
        $user = User::where('id', '!=', auth()->id())
        ->verified()
        ->orderBy('created_at', 'desc')
        ->get();
        return Response::withData(true, 'Get all user', $user, 200);
    }
}
