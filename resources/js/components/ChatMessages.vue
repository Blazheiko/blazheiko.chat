
<template>

        <ul class="chat" v-if="messages">
            <li class="left clearfix" v-for="message in messages">
                <div   class="chat-body clearfix">
                    <div class="header">
                        <div class="received" v-if="message.user_id === user.id">
                            <strong class="primary-font">
                                <img v-bind:src="'/uploads/avatars/'+ user.avatar" style="width:32px; height:32px; border-radius:50%">
                                {{ user.name }}
                            </strong>
                            <div class="text">
                                {{ message.message }}
                            </div>
                            <div class="date">
                                {{ message.datatime }}
                            </div>


                            <span v-if="message.is_photo">
                                <div class="modal-header">
                                      <div class="col-md-10 col-md-offset-1">
                                         <img v-bind:src="'/uploads/photos/'+ message.photo_url"  style="width:150px; height:150px; float:left; margin-right:25px;">
                                       </div>
                                 </div>
                            </span>
                        </div>

                        <div class="sent" v-else>
                                <strong class="primary-font">
                                    <img v-bind:src="'/uploads/avatars/'+ userto.avatar" style="width:32px; height:32px; border-radius:50%">
                                    {{ userto.name }}
                                </strong>
                            <div class="text">
                                {{ message.message }}
                            </div>
                            <div class="date">
                                {{ message.datatime }}
                            </div>
                            <span v-if="message.is_photo">
                                <div class="modal-header">
                                      <div class="col-md-10 col-md-offset-1">
                                         <img v-bind:src="'/uploads/photos/'+ message.photo_url"  style="width:150px; height:150px; float:left; margin-right:25px;">
                                       </div>
                                 </div>
                            </span>



                        </div>
                    </div>


                </div>
            </li>
        </ul>
</template>

<script>
    export default {
        props: ['messages','user','userto'],
    };
</script>

<style lang="scss" scoped>

        .received {
            width: max-content;
            padding: 10px;
            text-align: right;
            float: right;
            .text {
                /*background: #b2b2b2;*/
            }
        }
        .sent {
            width: max-content;
            padding: 20px;
            text-align: left;
            background: #d5b69fa6;
            .text {
                /*background: #81c4f9;*/
            }
        }



</style>
