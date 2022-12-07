<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\NativeIn;
use App\Models\AlsoSpeaking;
use App\Models\Learning;
use App\Http\Controllers\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function getAllUser(){
        $user = User::where('id', '!=', auth()->id())
        ->where('setup', '=', 1)
        ->with('NativeIn','AlsoSpeaking','Learning')
        ->verified()
        ->orderBy('created_at', 'desc')
        ->paginate(25);

        return Response::withData(true, 'Get all user', $user, 200);
    }

    public function getUser(Request $request){
        $user = User::where('id', '!=', auth()->id())
        ->where('name', 'like', '%'.$request->name.'%')
        ->where('setup', '=', 1)
        ->with('NativeIn','AlsoSpeaking','Learning')
        ->verified()
        ->orderBy('created_at', 'desc')
        ->get();
        return Response::withData(true, 'Get user', $user, 200);
    }

    public function setUserLanguage(Request $request){
        $user = auth()->user();

        foreach($request->input('nativeIn') as $lang)
        {
            NativeIn::create([
                'user_id'=>$user->id,
                'lang'=>$lang
            ]);
        }
        foreach($request->input('alsoSpeaking') as $lang)
        {
            AlsoSpeaking::create([
                'user_id'=>$user->id,
                'lang'=>$lang
            ]);
        }
        foreach($request->input('learning') as $lang)
        {
            Learning::create([
                'user_id'=>$user->id,
                'lang'=>$lang,
                'level'=>1
            ]);
        }
        $user->setup = 1;
        $user->save();

        $newUserData = User::where('id',  auth()->id())
        ->with('NativeIn','AlsoSpeaking','Learning')
        ->get();

        return Response::withData(true, 'Success', $newUserData[0], 200);
    }

    public function updateNativeInLanguage(Request $request){
        $validated = $request->validate([
            'nativeIn' => 'required'
        ]);
        $user = auth()-> user();

        foreach($request->input('nativeIn') as $lang)
        {
            NativeIn::updateOrCreate(
                ['user_id' => $user->id, 'lang' => $lang],
                []
            );

        }
        NativeIn::where('user_id', auth()->id())->whereNotIn('lang', $request->input('nativeIn'))->delete();

        $newUserData = User::where('id',  auth()->id())
        ->with('NativeIn','AlsoSpeaking','Learning')
        ->get();

        return Response::withData(true, 'Success', $newUserData[0], 200);
    }

    public function updateAlsoSpeakingLanguage(Request $request){

        $user = auth()->user();

        if($request->input('alsoSpeaking')){
            foreach($request->input('alsoSpeaking') as $lang)
            {
                AlsoSpeaking::updateOrCreate(
                    ['user_id' => $user->id, 'lang' => $lang],
                    []
                );

            }
            AlsoSpeaking::where('user_id',  auth()->id())->whereNotIn('lang', $request->input('alsoSpeaking'))->delete();
        }else{
            AlsoSpeaking::where('user_id',  auth()->id())->delete();
        }

        $newUserData = User::where('id',  auth()->id())
        ->with('NativeIn','AlsoSpeaking','Learning')
        ->get();

        return Response::withData(true, 'Success', $newUserData[0], 200);

    }

    public function updateLearningLanguage(Request $request){
        $validated = $request->validate([
            'learning' => 'required'
        ]);
        $user = auth()-> user();

        foreach($request->input('learning') as $lang)
        {
            Learning::updateOrCreate(
                ['user_id' => $user->id, 'lang' => $lang],
                ['level' => 1]
            );
        }
        Learning::where('user_id',  auth()->id())->whereNotIn('lang', $request->input('learning'))->delete();

        $newUserData = User::where('id',  auth()->id())
        ->with('NativeIn','AlsoSpeaking','Learning')
        ->get();

        return Response::withData(true, 'Success', $newUserData[0], 200);

    }

    public function updateProfile(Request $request){
        $validated = $request->validate([
            'name' => 'required|min:3',
            'desc' => 'required|max:255',
        ]);
        $user = auth()->user();
        if ($request->hasFile('img')) {
            if (!empty($user->img)){
                Storage::disk('public')->delete($user->img);
            }
            $image_path = $request->file('img')->store('users', 'public');
            $user->img = $image_path;
        }
        $user->name = $request->name;
        $user->desc = $request->desc;
        $user->save();
        $newUserData = User::where('id',  auth()->id())
        ->with('NativeIn','AlsoSpeaking','Learning')
        ->get();
        return Response::withData(true, 'Success', $newUserData[0], 200);
    }
}
