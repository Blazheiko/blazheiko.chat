
<template>
    <ul class="chat">
        <a>Messages</a>
        <li class="left clearfix" v-for="message in messages">
            <div   class="chat-body clearfix">
                <div class="header">

                    <strong class="primary-font">
                        <img v-bind:src="'/uploads/avatars/'+ message.user.avatar" style="width:32px; height:32px; border-radius:50%">
                        {{ message.user.name }}
                    </strong>
                </div>
                <p>
                    {{ message.message }}
                </p>

                <span v-if="message.is_photo">
                        <div class="modal-header">

                              <div class="col-md-10 col-md-offset-1">
                                 <img v-bind:src="'/uploads/photos/'+ message.photo_url"  style="width:150px; height:150px; float:left; margin-right:25px;">

                               </div>

                         </div>

                </span>



            </div>
        </li>
    </ul>
</template>

<script>
    export default {
        props: ['messages'],
    };
</script>
