@extends('layouts.app')

@section('content')

    @if (Auth::guest())
        <li><a href="{{ route('login') }}">Login</a></li>
        <li><a href="{{ route('register') }}">Register</a></li>
    @else
        @if (count($errors)>0)
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>
                            {{$error}}
                        </li>
                    @endforeach
                </ul>
            </div>

        @endif

        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h1>Новая статья</h1>
                           <div class="panel-body">

                            @if (session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}

                                </div>
                            @else
                                {{ Form::open (['route'=> 'post.store']) }}
                                @include('_form')
                                {{ Form::close() }}

                            @endif

                           </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    @endif


@stop
