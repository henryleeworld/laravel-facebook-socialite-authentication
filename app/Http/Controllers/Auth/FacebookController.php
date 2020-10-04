<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Exception;
use App\Http\Controllers\Controller;
use App\Models\User;
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
            $user = Socialite::driver('facebook')->stateless()->user();
            $finduser = User::where('facebook_id', $user->id)->first();
            if($finduser) {
                Auth::login($finduser);
                return redirect('/dashboard');
            }else{
                $newUser = User::create([
                    'name'                     => $user->name,
                    'email'                    => $user->email,
                    'facebook_id'              => $user->id,
                    'facebook_avatar'          => $user->avatar,
                    'facebook_avatar_original' => $user->avatar_original,
                    'facebook_profile_url'     => $user->profileUrl,
                    'password'                 => encrypt('123456dummy')
                ]);
                Auth::login($newUser);
     
                return redirect('/dashboard');
            }
    
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}

