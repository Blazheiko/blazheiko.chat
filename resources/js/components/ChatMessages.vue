
<template>

        <ul class="chat" v-if="messages">
            <li class="left clearfix" v-for="(message, index) in messages"  @click="translate(message.message,index)">
                <div   class="chat-body clearfix" v-if="message.message != '' || message.is_photo">
                        <div class="received" v-if="message.user_id === user.id">
                            <strong class="primary-font">
                                <img v-bind:src="'/uploads/avatars/'+ user.avatar" style="width:32px; height:32px; border-radius:50%">
                                {{ user.name }}
                            </strong>
                            <div class="text">
                                {{ message.message }}
                            </div>
                            <div class="date">
                                {{ message.created_at }}
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
                                {{ message.created_at }}
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



            </li>
        </ul>
</template>

<script>
    export default {
        props: ['messages','user','userto'],
        methods:{
            translate(message,index){
                if (!this.messages[index].translate)
                axios.post('/translate/'+this.user.language+'/en', {
                    text: message
                }).then((response) => {
                    // console.log(response.data.translate);
                    this.messages[index].message = message + '\n --||-- \n' + response.data.translate;
                    this.messages[index].translate = true ;
                })
            },
        }
    };

</script>

<style lang="scss" scoped>

        .received {
            width: 250px;
            height:auto;
            padding: 10px;
            text-align: right;
            float: right;
            margin-left: auto;
            margin-right: 1em;
            ;
            .text {
                padding: 10px;
                width: 250px;
                height: auto;
                /*display: flex;*/
                text-align: right;
                white-space: normal;
            }

        }

        .sent {
            width: 250px;
            height:auto;
            padding: 20px;
            text-align: left;
            background: #d5b69fa6;
            margin-left: 0em;
            margin-right: auto;
            .text {
                width: 250px;
                height:auto;
                display: flex;
                text-align: left;
                white-space: normal;
                /*overflow: auto;*/
                /*background: #b2b2b2;*/
            }
        }

        .left clearfix{
            height: auto;
        }





</style>
