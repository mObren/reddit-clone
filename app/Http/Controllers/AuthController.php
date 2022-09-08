<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('reddit')->redirect();
    }

    public function callback()
    {
        $redditUser = Socialite::driver('reddit')->user();
        $user = User::updateOrCreate(
        [
            'reddit_id' => $redditUser->id,
        ],

        [
            'username' => $redditUser->nickname,
        ]
    );

        Auth::login($user);

        
        return redirect('/');
    }

    public function logout(Request $request)
    {
        Auth::guard()->logout();
        $request->session()->flush();
        return redirect('/');
    }
}
