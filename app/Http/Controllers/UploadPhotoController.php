<?php


namespace App\Http\Controllers;

use App\Conversation;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class UploadPhotoController extends Controller
{

    public function __invoke (Request $request,$id){
//        return response($id);
        // Handle the user upload of avatar
        if($request->hasFile('photo')){

            $photo = $request->file('photo');
            $filename = time() . '.' . $photo->getClientOriginalExtension();
            Image::make($photo)->resize(300, 300)->save( public_path('/uploads/photos/' . $filename  ) );

            $user = Auth::user();
            $messagesNew = $user->messages()->create([
                'conversation_id'=>$id,'photo_url'=>$filename,'is_photo'=>true
            ]);

            broadcast(new MessageSent($user, $messagesNew))->toOthers();

          return ['message'=> $messagesNew];
        }
        return ['status' => 'Photo not!!!!'];

    }

}

