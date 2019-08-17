<!-- resources/views/chat.blade.php -->

@extends('layouts.app')

@section('content')
    {{--<span>В блоке чат блейд</span>--}}
    <div class="container" xmlns:v-bind="http://www.w3.org/1999/xhtml">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    {{--<example></example>--}}
                    <div class="panel-heading">Chats</div>

                    <div  ref='messageDisplay' class="panel-bodychat">

                        <chat-messages :messages="messages"></chat-messages>
                    </div>
                    <div  class="panel-footer" >
                        <chat-form
                            v-on:messagesent="addMessage"
                            :user=" {{Auth::user()}} "
                        ></chat-form>

                    </div>

                    <span>Select New Photo</span>
                    <input ref="photo" type="file" class="form-control" name="photo" @change="update">





                </div>
            </div>
            <div class="list-group">
                <chat-list></chat-list>
            </div>
        </div>
    </div>
@endsection
