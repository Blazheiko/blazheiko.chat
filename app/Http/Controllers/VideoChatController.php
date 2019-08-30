<?php

namespace App\Http\Controllers;

use App\Conversation;
use App\Events\MessageSent;
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
    public function offer(Request $request,$id)
    {
        $user = Auth::user();
        $messagesNew = $user->messages()->create([
            'conversation_id'=>$id,'message'=>'Видеочат', 'is_video'=>true
        ]);
//        Conversation::where('id',$request->conversation_id)->increment('counter');
        $conversation = Conversation::find($id);

        $conversation->offerVideoChat()->create(['offer'=>$request->all()]);

        $conversation->counter ++;
        if ($user->id == $conversation->user_id ){
            $conversation->count_read = $conversation->counter;
        }else {
            $conversation->count_read_to = $conversation->counter;
        }
        $conversation->save();

        broadcast(new MessageSent($user, $messagesNew))->toOthers();

        return response($request);

//        return response(['message'=>$messagesNew]);
    }
}
