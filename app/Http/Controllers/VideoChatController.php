<?php

namespace App\Http\Controllers;

use App\Conversation;
use App\Events\MessageSent;
use App\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show chats
     *
     * @return \Illuminate\Http\Response
     */
    public function message(Request $request,$id)
    {

        $user = Auth::user();
        $messagesNew = new Message();
        $messagesNew->conversation_id = $id;
        $messagesNew->user_id = $user->id;
        $messagesNew->message = 'webRTC';
        $messagesNew->is_photo = false;
        $messagesNew->is_video = true;
        if ($request->has('ice'))
        {
//            $messagesNew->ice = $request->except('_url');
            $messagesNew->ice = $request->get('data');
        }else{
            $messagesNew->sdp = $request->get('data');
        }

//        $messagesNew->video_chat = $request->all();
        $messagesNew -> save();

        broadcast(new MessageSent($user, $messagesNew))->toOthers();

        return response($messagesNew);
    }

    public function offerVideoChat($id)
    {
        $user = Auth::user();
        $messagesNew = $user->messages()->create([
            'conversation_id'=>$id,'message'=>'Видеочат','photo_url'=>'editor_icon_124382.png','is_photo'=>true, 'is_video'=>true
        ]);

        broadcast(new MessageSent($user, $messagesNew))->toOthers();

        return response('ответ сервера start videoChat');

    }

}
