<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    /*public function updateAvatar(Request $request): Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {

        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = auth()->user();


        if (Storage::exists('avatars' . basename($user->avatar))) {
            Storage::delete('avatars' . basename($user->avatar));
        }

        // store the new avatar
        $avatarName = $user->id . '_avatar' . time() . '.' . request()->avatar->getClientOriginalExtension();
        $request->avatar->storeAs('public/avatars', $avatarName);

        $avatarUrl = Storage::url('avatars/' . $avatarName);

        $user->avatar_url = $avatarUrl;
        $user->save();
        return response([
            'user' => $user,
        ]);
    }


    public function updateName(Request $request)
    {


        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user = $request->user();
        $user->name = $validated['name'];
        $user->save();

        return response()->json([
            'user' => $user
        ], 200);
    }*/
}
