<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>

        .row{
            /*width: 900px;*/
            white-space:nowrap;
        }
        /*.row div{*/
            /*display:inline-block;*/
            /*border:1px solid black;*/
        /*}*/
        .chat-list {
            float:right;
        }
        .chat {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .chat li {
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px dotted #B3A9A9;
        }

        .chat li .chat-body p {
            margin: 0;
            color: #777777;
        }

        .panel-body {
            float:left;
            overflow-y: scroll;
            /*height: 400px;*/
            width: 1150px;
        }
        
        .panel-bodychat {
            float:left;
            overflow-y: scroll;
            height: 600px;
            width: 800px;
        }

        ::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
            background-color: #F5F5F5;
        }

        ::-webkit-scrollbar {
            width: 12px;
            background-color: #F5F5F5;
        }

        ::-webkit-scrollbar-thumb {
            -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
            background-color: #555;
        }
        /*@import url('https://fonts.googleapis.com/css?family=PT+Sans+Caption');*/
        /*.top-menu {*/
            /*margin: 0 60px;*/
            /*position: relative;*/
            /*background: #C8C9CE;*/
            /*box-shadow:*/
                /*inset 1px 0 0 rgba(255,255,255,.1),*/
                /*inset -1px 0 0 rgba(255,255,255,.1),*/
                /*inset 150px 0 150px -150px rgba(255,255,255,.12),*/
                /*inset -150px 0 150px -150px rgba(255,255,255,.12);*/
        /*}*/
        /*.top-menu:before,*/
        /*.top-menu:after {*/
            /*content: "";*/
            /*position: absolute;*/
            /*z-index: 2;*/
            /*left: 0;*/
            /*width: 100%;*/
            /*height: 3px;*/
        /*}*/
        /*.top-menu:before {*/
            /*top: 0;*/
            /*border-bottom: 1px dashed rgba(255,255,255,.2);*/
        /*}*/
        /*.top-menu:after {*/
            /*bottom: 0;*/
            /*border-top: 1px dashed rgba(255,255,255,.2);*/
        /*}*/
        .menu-main {
            list-style: none;
            padding: 0;
            margin: 0;
            text-align: center;
        }
        .menu-main:before,
        .menu-main:after {
            content: "";
            position: absolute;
            width: 50px;
            height: 0;
            top: 8px;
            border-top: 18px solid #C8C9CE;
            border-bottom: 18px solid #C8C9CE;
            transform: rotate(360deg);
            z-index: -1;
        }
        .menu-main:before {
            left: -30px;
            border-left: 12px solid rgba(255, 255, 255, 0);
        }
        .menu-main:after {
            right: -30px;
            border-right: 12px solid rgba(255, 255, 255, 0);
        }
        .menu-main li {
            display: inline-block;
            margin-right: -4px;
        }
        .menu-main a {
            text-decoration: none;
            display: inline-block;
            padding: 15px 30px;
            font-family: 'PT Sans Caption', sans-serif;
            color: white;
            transition: .3s linear;
        }
        .menu-main a.current,
        .menu-main a:hover {background: rgba(0,0,0,.2);}
        @media (max-width: 680px) {
            .top-menu {margin: 0;}
            .menu-main li {
                display: block;
                margin-right: 0;
            }
            .menu-main:before,
            .menu-main:after {content: none;}
            .menu-main a {display: block;}
        }
        .bottom_block {
            margin:0px;
            text-align:left;
            font-size:14px;
            font-family:Arial, Helvetica, sans-serif;
            background-color:#F9FDFF;
            position:absolute;
            width: 200px;
            left: 15px;
            bottom: 0px;}
        .col-md-4 portfolio-item{
            position:relative;
            width:200px;
            height:200px;

        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                {{--<a href="{{ url('chat/') }}"> Chat </a>--}}
                {{--<a href="/blogs">Blogs</a>--}}
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span href="/blogs" class="navbar-toggler-icon">БЛОГИ</span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    {{--<ul class="navbar-nav mr-auto">--}}
                        {{--<li><a class="navbar-brand" href="">Главная страница </a></li>--}}
                        {{--<li><a class="navbar-brand" href="/blogs">Блоги </a></li>--}}
                        {{--<li><a class="navbar-brand" href="">Месседжер </a></li>--}}

                    {{--</ul>--}}

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" style="position:relative; padding-left:50px;">
                                    <img src="/uploads/avatars/{{ Auth::user()->avatar}}" style="width:32px; height:32px; position:absolute; top:-5px; left:0px; border-radius:50%">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>


                                <ul class="dropdown-menu" role="menu">

                                    <li>
                                        <a href="{{ route('profile') }}"
                                           onclick="event.preventDefault();
                                                     document.getElementById('profile-form').submit();">
                                            Profile
                                        </a>


                                        <form id="profile-form" action="{{ route('profile') }}" method="GET" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>

                                    <li>
                                        <a href="{{ route('blog') }}"
                                           onclick="event.preventDefault();
                                                     document.getElementById('blog-form').submit();">
                                            Blog
                                        </a>


                                        <form id="blog-form" action="{{ route('blog') }}" method="GET" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>

                                    <li>
                                        <a href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>


                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>

                                </ul>
                            </li>

                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <nav class="top-menu">
            <ul class="menu-main">
                <li><a href="/home" class="current">Главная</a></li>
                <li><a href="/blogs">Блоги</a></li>
                <li><a href="{{ url('chat/') }}">Чат</a></li>
            </ul>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
