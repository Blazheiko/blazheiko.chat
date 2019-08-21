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


        $userId = auth()->id();
        $conversationStart = Conversation::where(function($q) use ($id,$userId) {
            $q->where('user_id', $userId);
            $q->where('user_to_id', $id);
        })->orWhere(function($q) use ($id,$userId) {
            $q->where('user_id', $id);
            $q->where('user_to_id', $userId);
        })
            ->get();
//        return response(count($conversationStart));
        if (count($conversationStart)==0){
            $messagesStart['user_id'] = $userId ;
            $messagesStart['message'] = 'Начало чата с пользователем';
            $messagesStart['photo_url'] = '';
            $messagesStart['is_photo'] = false;

            $date = new DateTime();
            $messagesStart['datatime'] = $date->getTimestamp();


            $conversationStart=new Conversation([
                'user_id' => $userId,'user_to_id' =>(int)$id,'messages' => $messagesStart]);
            $conversationStart->save();
        }

        $userTo=User::find($id);
        $user = User::find($userId);
//        dd($messagesStart);

        return response(['conversation'=>$conversationStart,'user'=>$user,'user_to'=>$userTo]);
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
