@extends('layouts.app')

@section('content')
<div class="container">
    {{--<div class="row justify-content-center">--}}
        {{--<div class="col-md-8">--}}
            {{--<div class="card">--}}

                {{--<div class="card-header">Profile</div>--}}
                {{--<div class="card-body">--}}
                    {{--@if (session('status'))--}}
                        {{--<div class="alert alert-success" role="alert">--}}
                            {{--{{ session('status') }}--}}
                        {{--</div>--}}
                    {{--@endif--}}

                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
    @foreach($users->chunk(4) as $userLine)
        <div class="row">
            @foreach($userLine as $user)
                <div class="col-md-3 col-sm-6" >
                    <a href="/profile/{{$user->id}}/show">
                       <span><img class="img-responsive" src="/uploads/avatars/{{ $user->avatar }}" style="width:200px; height:200px; float:left; margin-right:25px;" alt=""></span>
                    </a>
                    {{--<h3>--}}
                        {{--<a href="/{{ $user->id }}">{{ $user->name }}</a>--}}
                    {{--</h3>--}}
                    <div class="bottom_block">
                        <p>{{ $user->name }}</p>
                    </div>

                    {{--<p>{{ str_limit($item->product_description, 121) }}</p>--}}
                </div>
            @endforeach
        </div>
    @endforeach
</div>
@endsection
