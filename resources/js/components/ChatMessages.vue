
<template>

        <ul class="chat">

            <li class="left clearfix" v-for="message in messages">
                <div   class="chat-body clearfix">
                    <div class="header">
                        <div v-if="message.user_id === user.id">
                            <strong class="primary-font">
                                <img v-bind:src="'/uploads/avatars/'+ user.avatar" style="width:32px; height:32px; border-radius:50%">
                                {{ user.name }}
                            </strong>
                        </div>

                        <div v-else>
                            <strong class="primary-font">
                                <img v-bind:src="'/uploads/avatars/'+ userto.avatar" style="width:32px; height:32px; border-radius:50%">
                                {{ userto.name }}
                            </strong>
                        </div>
                    </div>
                    <p>
                        {{ message.message }}
                    </p>
                    <p>
                        {{ message.datatime }}
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
        props: ['messages','user','userto'],
    };
</script>
