@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h1>Блог пользователя</h1>
                        <a href="{{ url('post/create') }}">Создать новую статью</a></div>


                    <div class="page-item">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}

                            </div>
                        @else
                            @foreach($posts as $post)
                                <article>

                                    <h2>
                                        <a href="{{ url('post/'.$post->id.'/show') }}">{{$post->title}}</a>
                                        {{--{{$post->title}}--}}
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

                                {{--<div style="float: left; margin-right: 3px">--}}
                                    {{--<form action="/post/{{ $post->id}}/edit"><button>Редактировать</button> </form>--}}

                                {{--</div>--}}

                                {{--<form action="/post/{{ $post->id}}/delete"><button>Удалить</button></form>--}}

                            @endforeach
                        @endif



                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
