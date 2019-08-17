<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\User;

class SocialAuthGoogleController extends Controller
{

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */

    public function handleProviderCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect('/login');
        }

        // check if they're an existing user
        $existingUser = User::where('email', $user->email)->first();
        if($existingUser){
            // log them in
            auth()->login($existingUser, true);
        }
//        } else {
//            // create a new user
//            $newUser            = new User;
//            $newUser->name      = $user->name;
//            $newUser->email     = $user->email;
//            $newUser->password  = md5(rand(1,10000));
////            $newUser->avatar  = $user->avatar;
//            $newUser->save();
//            auth()->login($newUser, true);
//        }

//        return redirect()->to('/home');
        return view('auth.social_register.blade')->with(['user'=>$user]);

        //dd($user) ;
    }
}
