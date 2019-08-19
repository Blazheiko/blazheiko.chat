<?php

namespace App\Http\Controllers;

use App\Conversation;
use App\Events\MessageSent;
use App\Message;
use App\User;
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
    public function fetchMessages()
    {
//        dd(Message::with('user')->get());

        return Message::with('user')->get();
    }


    public function fetchConversations()
    {
       // dd(Conversation::with('user')->get());

        return Conversation::with('user')->get();
    }

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
//        return ['status' => 'в контроллере'];
//        dd($request);
        $user = Auth::user();
//        return ['status' => $request->message];

        $message = $user->messages()->create([
            'message' => $request->input('message'),'photo_url'=> '',
        ]);
        $user->save();


        broadcast(new MessageSent($user, $message))->toOthers();

        return ['status' => 'Message Sent!'];
    }
}
