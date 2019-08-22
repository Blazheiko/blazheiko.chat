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
        $conversation = Conversation::where(function($q) use ($id,$userId) {
            $q->where('user_id', $userId);
            $q->where('user_to_id', $id);
        })->orWhere(function($q) use ($id,$userId) {
            $q->where('user_id', $id);
            $q->where('user_to_id', $userId);
        })
            ->get();
//        return response(count($conversationStart));
        if (count($conversation)==0){
            $messages =[];
            $messagesStart['user_id'] = $userId ;
            $messagesStart['message'] = 'Начало чата с пользователем';
            $messagesStart['photo_url'] = '';
            $messagesStart['is_photo'] = false;
            $date = new DateTime();
            $messagesStart['datatime'] = $date->getTimestamp();
            $messages[]=$messagesStart;

            $conversationStart=new Conversation([
                'user_id' => $userId,'user_to_id' =>(int)$id,'messages' => $messages]);
            $conversationStart->save();
            $conversation[]=$conversationStart;
        }
        $userTo=User::find($id);
        $user = User::find($userId);
//        dd($messagesStart);

        return response(['conversation'=>$conversation,'user'=>$user,'userto'=>$userTo]);
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
//        return response($request);
//        return ['status' => 'в контроллере'];
        $conversationId=$request->conversation_id;

        $user = Auth::user();
        $conversation = Conversation::find($conversationId);

        $messages = $conversation->messages;
        $messagesNew['user_id'] = $user->id;
        $messagesNew['message'] = $request->text;
        $messagesNew['photo_url'] = '';
        $messagesNew['is_photo'] = false;
        $messagesNew['conversationId'] = $conversationId;
        $date = new DateTime();
        $messagesNew['datatime'] = $date->getTimestamp();
        $messages[]=$messagesNew;
        $conversation->messages =$messages;
        $conversation->save();

//        return response($messagesNew);
        broadcast(new MessageSent($user, $messagesNew))->toOthers();

        return response($messagesNew);
    }
}
