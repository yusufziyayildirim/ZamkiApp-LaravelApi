<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\NativeIn;
use App\Models\AlsoSpeaking;
use App\Models\Learning;
use App\Http\Controllers\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function getAllUser(){
        $user = User::where('id', '!=', auth()->id())
        ->with('NativeIn','AlsoSpeaking','Learning')
        ->verified()
        ->orderBy('created_at', 'desc')
        ->get();
        return Response::withData(true, 'Get all user', $user, 200);
    }

    public function getUser(Request $request){
        $user = User::where('email', Str::lower($request->email))
        ->with('NativeIn','AlsoSpeaking','Learning')
        ->verified()
        ->orderBy('created_at', 'desc')
        ->first();
        return Response::withData(true, 'Get user', $user, 200);
    }

    public function setUserLanguage(Request $request){
        $user = auth()->user();
        NativeIn::create([
            'user_id'=>$user->id,
            'lang'=>$request->nativeIn
        ]);
        AlsoSpeaking::create([
            'user_id'=>$user->id,
            'lang'=>$request->alsoSpeaking
        ]);
        Learning::create([
            'user_id'=>$user->id,
            'lang'=>$request->learning,
            'level'=>1
        ]);
        return Response::withoutData(true, 'Success', 200);
    }

    public function updateNativeInLanguage(Request $request){
        $user = auth()->user();
        $native = NativeIn::where('user_id', $user->id)->first();
        $native->lang = $request->nativeIn;
        $native->save();
        return Response::withoutData(true, 'Success', 200);
    }

    public function updateAlsoSpeakingLanguage(Request $request){
        $user = auth()->user();
        $native = AlsoSpeaking::where('user_id', $user->id)->first();
        $native->lang = $request->alsoSpeaking;
        $native->save();
        return Response::withoutData(true, 'Success', 200);
    }

    public function updateLearningLanguage(Request $request){
        $user = auth()->user();
        $native = Learning::where('user_id', $user->id)->first();
        $native->lang = $request->learning;
        $native->save();
        return Response::withoutData(true, 'Success', 200);
    }
}
