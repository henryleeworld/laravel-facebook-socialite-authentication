<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Exception;
use Socialite;
  
class FacebookController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }
      
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function handleFacebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->stateless()->user();
            $user = User::updateOrCreate([
                'facebook_id'              => $facebookUser->id,
            ], [
                'name'                     => $facebookUser->name,
                'email'                    => $facebookUser->email,
                'password'                 => encrypt('123456dummy'),
                'facebook_avatar'          => $facebookUser->avatar,
                'facebook_avatar_original' => $facebookUser->avatar_original,
                'facebook_profile_url'     => $facebookUser->profileUrl,
            ]);
            Auth::login($user);
            return redirect('/dashboard');
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}

