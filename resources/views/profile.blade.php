@extends('layouts.app')

@section('content')

    @if (Auth::guest())
        <li><a href="{{ route('login') }}">Login</a></li>
        <li><a href="{{ route('register') }}">Register</a></li>
    @else

        <div class="container">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <img src="/uploads/avatars/{{ $user->avatar }}" style="width:150px; height:150px; float:left; margin-right:25px;">
                    <h2>{{ $user-> name }}</h2>

                    @if( $user->id == Auth::user()->id)

                        <form enctype="multipart/form-data" action="/profile" method="POST">
                            <label>Обновить свое фото</label>
                            <input type="file" name="avatar">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="submit" class="pull-right btn btn-sm btn-primary">
                        </form>

                    @endif



                </div>

                <hr>
                {{--<iframe width="900" height="510" src="https://www.youtube.com/embed/T1n9tQKp6N8" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>--}}
                <hr>


            </div>
            <div class="row">
                @if($profile)
                <div class="col-md-10 col-md-offset-1">

                    <p>
                        {{'Возраст - '. $profile->age}}
                    </p>

                </div>
                <div class="col-md-10 col-md-offset-1">

                    <p>
                        {{'Увлечения - '. $profile->hobbi}}
                    </p>

                </div>
                    @if( $user->id == Auth::user()->id)

                        <div style="float: left; margin-right: 3px">
                            <form action="/profile/{{ $profile->id}}/edit"><button>Редактировать</button> </form>
                        </div>

                    @endif

                @else
                    @if( $user->id == Auth::user()->id)

                        {{ Form::open (['route'=> 'profile.store']) }}
                        {{Form::token()}}
                        <div class="form-group">
                            {{ Form::label ('Сколько вам лет') }}
                            {{ Form::number ('age',null,['class' => 'form-control' ]) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label ('Ваши увлечения') }}
                            {{ Form::textarea ('hobbi',null,['class' => 'form-control' ]) }}
                        </div>
                        <div class="form-group">
                            {{ Form::submit ('Сохранить',['class' => 'btn btn-primary' ]) }}
                        </div>
                        {{ Form::close() }}

                    @endif



                @endif

            </div>

        </div>
    @endif



@endsection
