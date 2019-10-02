<!-- resources/views/chat.blade.php -->

@extends('layouts.app')

@section('content')
    {{--<span>В блоке чат блейд</span>--}}
    <div class="container" xmlns:v-bind="http://www.w3.org/1999/xhtml">
        <div class="row">
            <div class="col-md-12 col-md-offset-2">
                <div class="panel panel-default">
                    <div>
                        <strong> {{$language_default.' - '.$language}}
                            <form method="POST" action="{{ route('chat.lang') }}">
                                @csrf
                                <select name="lang">
                                    <option>select translator language</option>
                                    @foreach ($list_lang as $item)
                                        <option  value={{$item['code']}}>{{$item['name'].' - '.$item['code']}}</option>
                                    @endforeach
                                </select>
                                <input type="submit" value="Select">
                            </form>
{{--                            <form action="{{ url('chat/') }}" method="post">--}}
{{--                                @csrf--}}
{{--                              <select name="lang">--}}
{{--                                <option>Выберите язык чата</option>--}}
{{--                                @foreach ($list_lang as $item)--}}
{{--                                <option  value={{$item['code']}}>{{$item['name']}}</option>--}}
{{--                                @endforeach--}}
{{--                              </select>--}}
{{--                                <p><input type="submit" value="Select"></p>--}}
{{--                            </form>--}}
                        </strong>
                    </div>

                    {{--<example></example>--}}
                    <chat-header :contact="contact">
                    </chat-header>

                    <div  ref='messageDisplay' class="panel-bodychat">

                        <chat-messages
                            :messages="messages"
                            :user="user"
                            :userto="userto"
                        ></chat-messages>
                    </div>
                    <div  class="panel-footer" >
                        <chat-form
                            @send="sendMessage"
                            :user="user"
                        ></chat-form>

                    </div>
                    <span>Отправить фото</span>
                    <input ref="photo" type="file" class="" name="photo" @change="update">

                </div>
                <div class="list-group">
                    <chat-list
                        v-on:selected="selectedContact"
                        :contacts="contacts"
                        :selected_video="selected_video">

                    </chat-list>
                </div>
            </div>
            <video-chat
                :conversation="conversation"
                :contact="contact"
                :user="user"
                :sdp="sdp"
                :is_video="is_video">
            </video-chat>

        </div>
    </div>
@endsection
