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
            $conversation = Conversation::find($id);
            $messages = $conversation->messages;
            $messagesNew['user_id'] = $user->id;
            $messagesNew['message'] = '';
            $messagesNew['photo_url'] = $filename;
            $messagesNew['is_photo'] = true;
            $messagesNew['conversationId'] = $id;
            $messagesNew['datatime'] =''.date("m.d.y").'  '.date("H:i:s");
            $messages[]=$messagesNew;
            $conversation->messages =$messages;
            $conversation->save();
//            return response($request);

            broadcast(new MessageSent($user, $messagesNew))->toOthers();

          return ['user'=>$user,'message'=> $messagesNew];
        }
        return ['status' => 'Photo not!!!!'];

    }

}

