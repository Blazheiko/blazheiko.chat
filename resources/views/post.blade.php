@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <a href="{{ url('post/create') }}">Создать новую статью</a>
                    </div>


                    <div class="page-item">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}

                            </div>
                        @else
                            <hr>
                          <div class="container">
                              <div class="row">
                              <article>
                                  <p>
                                      <img src="/uploads/avatars/{{ $post->user->avatar}}" style="width:32px; height:32px;  top:-5px; left:0px; border-radius:50%">
                                      {{$post->user->name}}

                                  </p>

                                  <h2>
                                      {{$post->title}}
                                  </h2>
                                  <p>
                                      {{$post->post}}
                                  </p>
                                  <p>
                                      {{'Создан' }}
                                      {{$post->created_at}}
                                  </p>
                                  <p>
                                      {{'Обновлен'}}
                                      {{$post->updated_at}}
                                  </p>

                              </article>
                              </div>
                          </div>



                            @if( $post->user == Auth::user())

                            <div style="float: left; margin-right: 3px">
                                <form action="/post/{{ $post->id}}/edit"><button>Редактировать</button> </form>

                            </div>

                            <form action="/post/{{ $post->id}}/delete"><button>Удалить</button></form>

                            @endif

                            <hr>

                            {{ Form::open (['route'=> ['comment.store',$post]]) }}
                            <div class="form-group">
                                {{ Form::label ('Коментарий:') }}
                                {{ Form::text ('comment',null,['class' => 'form-control' ]) }}
                            </div>
                            <div class="form-group">
                                {{ Form::submit ('Сохранить',['class' => 'btn btn-primary' ]) }}
                            </div>
                            {{ Form::close() }}


                             <div class="comment">
                                 <ul class="list-group">
                                     @foreach($comments as $comment)

                                         <li class="list-group-item">

                                             <h2>
                                                 {{--<a href="{{ url('post/'.$post->id.'/show') }}">{{$post->title}}</a>--}}
                                                 {{--{{$post->title}}--}}
                                             </h2>
                                             <p>
                                                 {{--{{User::find($comment->user_id)-> name}}--}}
                                                 {{$comment->user->name }}
                                             </p>
                                             <p>
                                                 {{$comment->comment}}
                                             </p>
                                             <p>
                                                 {{$post->created_at}}
                                             </p>


                                         </li>

                                     @endforeach
                                 </ul>

                             </div>



                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
    {

@endsection
