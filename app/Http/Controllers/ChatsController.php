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

        return response(['conversation'=>$conversation,'messages'=>$messages,'user'=>$user,'userto'=>$userTo]);
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
            $contactTemp['counter']= 0;
            $contactTemp['unread']= 0;
            $contactTemp['last_message_date']=null;
//            $contactTemp['conversation']= null; необходимо будет добавть когда в списке будут только те кто имеют диалоги

            for ($i=0; $i<count($conversations);$i++){
                if ($contact->id == $conversations[$i]->user_id || $contact->id == $conversations[$i]->user_to_id ){
//                    $contactTemp['conversation']= $conversations[$i]->id;необходимо будет добавть когда в списке будут только те кто имеют диалоги
                    $contactTemp['counter'] = $conversations[$i]->counter;
                    $contactTemp['last_message_date']=$conversations[$i]->last_message_date;

                    if ($contact->id == $conversations[$i]->user_id ){
                        $contactTemp['unread'] = $conversations[$i]->unread;}
                    else{
                        $contactTemp['unread'] = $conversations[$i]->unread_to;}
                    $i=count($conversations);
                }
            }
            $listContact[]=$contactTemp ;
        }

        return response($listContact);
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
        Conversation::where('id',$request->conversation_id)->increment('counter');

        broadcast(new MessageSent($user, $messagesNew))->toOthers();

        return response(['message'=>$messagesNew]);
    }
}
