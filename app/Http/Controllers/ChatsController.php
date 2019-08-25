<?php

namespace App\Http\Controllers;

use App\Conversation;
use App\Events\MessageSent;
use App\Message;
use App\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatsController extends Controller
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
    public function index()
    {
       // dd(Message::with('user')->get());
        return view('chat');
    }

    /**
     * Fetch all messages
     *
     * @return Message
     */
    public function fetchMessages($id)
    {

        $user = Auth::user();
        $userId =$user->id;
        $userTo=User::find($id);
//        return response($userTo);

        $conversation = Conversation::where(function($q) use ($id,$userId) {
            $q->where('user_id', $userId);
            $q->where('user_to_id', $id);
        })->orWhere(function($q) use ($id,$userId) {
            $q->where('user_id', $id);
            $q->where('user_to_id', $userId);
        })
            ->first();

        if (!$conversation){
//            return response('в условии');
            $conversation = $user->conversations()->create([
                'user_to_id'=>$id
            ]);
            $messages = [];
            return response(['conversation'=>$conversation,'messages'=>$messages,'user'=>$user,'userto'=>$userTo]);
        }

        $messages = $conversation -> messages;

        return response(['conversation'=>$conversation,'messages'=>$messages,'user'=>$user,'userto'=>$userTo]);
    }


//    public function fetchConversations()
//    {
//       // dd(Conversation::with('user')->get());
//
//        return Conversation::with('user')->get();
//    }

    public function fetchContacts()
    {
        // dd(Conversation::with('user')->get());

        return User::where('id', '!=', auth()->id())->get();
    }


    /**
     * Persist message to database
     *
     * @param  Request $request
     * @return Response
     */
    public function sendMessage(Request $request)
    {
        $user = Auth::user();
        $messagesNew = $user->messages()->create([
            'conversation_id'=>$request->conversation_id,'message'=>$request->text
        ]);

        broadcast(new MessageSent($user, $messagesNew))->toOthers();

        return response(['message'=>$messagesNew]);
    }
}
