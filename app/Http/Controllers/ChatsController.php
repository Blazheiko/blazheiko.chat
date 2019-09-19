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


    //отправляем список контактов
    public function fetchContacts()
    {
        $user = Auth::user();
        $conversations =Conversation::where('user_id', $user->id)
            ->orWhere('user_to_id', $user->id)
            ->get();
        $contacts = User::where('id', '!=', auth()->id())->get();
        $listContact=[];
//        return response($conversations);
        //делаем список контактов для клиента используя его контакты и список всех пользователей
        foreach ($contacts as $contact){
            $contactTemp['contact'] = $contact;
            $contactTemp['counter'] = 0;
            $contactTemp['count_read'] = 0;
            $contactTemp['last_message_date']=null;
            $contactTemp['conversation_id']= null;

            for ($i=0; $i<count($conversations);$i++){
                if ($contact->id == $conversations[$i]->user_id || $contact->id == $conversations[$i]->user_to_id ){
                    $contactTemp['conversation_id']= $conversations[$i]->id;
                    $contactTemp['counter'] = $conversations[$i]->counter;
                    $contactTemp['last_message_date']=$conversations[$i]->last_message_date;

                    if ($contact->id == $conversations[$i]->user_id ){
                        $contactTemp['count_read'] = $conversations[$i]->count_read_to;}
                    else{
                        $contactTemp['count_read'] = $conversations[$i]->count_read;
                    }
                    $i=count($conversations);
                }
            }
            $listContact[]=$contactTemp ;
        }

        return response($listContact);
    }

    /**
     * Fetch all messages (выбираем все сообщения с заданным пользователем)
     *
     * @return Message
     */

    public function fetchMessages($id)
    {
        $user = Auth::user();
        $userId =$user->id;
        $userTo=User::find($id);
//        return response($userTo);
        //ищем необходимый диалог
        $conversation = Conversation::where(function($q) use ($id,$userId) {
            $q->where('user_id', $userId);
            $q->where('user_to_id', $id);
        })->orWhere(function($q) use ($id,$userId) {
            $q->where('user_id', $id);
            $q->where('user_to_id', $userId);
        })
            ->first();
        //если диалога нету то создаем новый
        if (!$conversation){
            $conversation = $user->conversations()->create([
                'user_to_id'=>$id
            ]);
            $messages = [];
            return response(['conversation'=>$conversation,'messages'=>$messages,'user'=>$user,'userto'=>$userTo]);
        }
        //если диалог есть
        $messages = $conversation -> messages;
//        return response($user->id = $conversation->user_id)
        if ($user->id == $conversation->user_id ){
            $conversation->count_read = $conversation->counter ;
        }else $conversation->count_read_to = $conversation->counter;

        $conversation->save();

        return response(['conversation'=>$conversation,'messages'=>$messages,'user'=>$user,'userto'=>$userTo]);
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

//        Conversation::where('id',$request->conversation_id)->increment('counter');
        $conversation = Conversation::find($request->conversation_id);
        $conversation->counter ++;
        if ($user->id == $conversation->user_id ){
            $conversation->count_read = $conversation->counter;
            $messagesNew = $user->messages()->create([
                'conversation_id'=>$request->conversation_id,'message'=>$request->text,'user_to'=>$conversation->user_to_id
            ]);
        }else {
            $conversation->count_read_to = $conversation->counter;
            $messagesNew = $user->messages()->create([
                'conversation_id'=>$request->conversation_id,'message'=>$request->text,'user_to'=>$conversation->user_id
            ]);
        }

        $conversation->save();

        broadcast(new MessageSent($user, $messagesNew))->toOthers();

        return response(['message'=>$messagesNew]);
    }

}
