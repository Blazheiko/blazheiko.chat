<?php

namespace App\Http\Controllers;

use App\Profile;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{

    public function profile(){
        $user = Auth::user();
        $profile= $user -> profile;
        //dd($profile);
        return view('profile', ['user' => $user, 'profile' => $profile] );
    }
//    public function edit($id)
//    {
//        $profile = Profile::find($id);
//
//        return view('edit')->with('post', $post);
//    }

    public function show($id)
    {
        $user = User::find($id);
        $profile = $user->profile;
        return view('profile', ['user' => $user, 'profile' => $profile] );
    }

    public function update_avatar(Request $request){
        //dd($request);

        // Handle the user upload of avatar
        if($request->hasFile('avatar')){
            //echo ' in function update_avatar';

            $avatar = $request->file('avatar');
            $filename = time() . '.' . $avatar->getClientOriginalExtension();

            Image::make($avatar)->resize(300, 300)->save( public_path('/uploads/avatars/' . $filename ) );
            $user = Auth::user();
            $user->avatar = $filename;
            $user->save();
            $profile= $user -> profile;
            return view('profile', ['user' => $user, 'profile' => $profile] );
        }
        return redirect()->route('profile');

    }

    public function blog(){
        $user = Auth::user();
        $posts= $user -> posts;
         //dd($posts);
        return view('blogs', ['user' => $user, 'posts' => $posts] );
    }
}
