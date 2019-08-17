<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Post;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::with('user')->get();
        return view('blogs_ general')->with(['posts'=>$posts]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('create_post');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Post $post, Request $request)
    {
        //валидация данных
        $this->validate($request, [
            'title' => 'required|unique:posts|max:255',
            'post' => 'required',
        ]);
        $post = new Post($request->all());

        $user = User::find(Auth::user()->id);
        $user->posts()->save($post);

         // Event::fire(new onAddTaskEvent($post,$user));

//      отправка почты вариант №1
//        Mail::send (['text' => 'mails.mail'],['name'=> 'list'],function ($message){
//            $message -> to('alexmix75@gmail.com','test1')->subject('test mail');
//            $message -> from('alexmix75@gmail.com','list');
//        });
        //      отправка почты вариант №2
//        Mail::to($userEmail)->send(new MailClass($userName,$userEmail));


        return redirect()->route('blog');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);
        $comments =Comment::with('user') ->where('post_id', $id )
            ->get()->reverse();
        return view('post')->with(['post'=>$post,'comments'=>$comments]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);

        return view('edit')->with('post', $post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
//        $this->validate($request, [
//            'title' => 'required|unique:todolists|max:255',
//            'content' => 'required',
//        ]);
        $user = User::find(Auth::user()->id);
        $user ->posts()->where('id',$id)->
                    update(['title'=>$request->title, 'post'=>$request->get('post')]);

        return redirect()->route('blog');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
//        $user = User::find(Auth::user()->id);
//
//        $post = $user ->posts()->where('id',$id);//->delete();
//        $post ->comments()->get()->delete();
//        $post ->delete();
        $post =Post::find($id);
        $post ->comments()->delete();
        $post ->delete();
        return redirect()->route('blog');
    }
}
