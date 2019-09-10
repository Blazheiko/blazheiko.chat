<template v-if="video_is">

    <div >
        <video id="yourVideo" ref="yourVideo"  autoplay muted></video>
        <video id="friendsVideo" ref="friendsVideo" autoplay></video>
        <br />
        <button @click="offerVideoChat()" type="button" class="btn btn-danger btn-lg"><span class="glyphicon glyphicon-facetime-video" aria-hidden="true"></span> Call</button>
    </div>


</template>

<script>
    export default {
        name: "VideoChat",
        props: ['user','contact','conversation','sdp','ice','is_video'],
        data() {
            return {
                selected: null,
                yourVideo:null,
                friendsVideo:null,
                pc:null,
                yourId:null,
                senderId:null,
                start:false
            };
        },

        mounted() {


        },

        watch: {
            sdp: function () {
                console.log('event sdp ');
                this.readSdp() ;
            },
            ice: function () {
                console.log('event ice ');
                this.readIce() ;
            },
            is_video: function () {
                console.log('event is_video ');
                this.showFriendsFace();
            },
        },

        methods: {
            startVideoChat(){
                console.log('выполняем startVideoChat');
                if(this.start){
                    return
                };
                this.start = true;
                this.yourId = this.user.id;
                this.senderId = this.contact.id;
                this.yourVideo = this.$refs.yourVideo;// for test
                this.yourVideo.srcObject = null;
                this.friendsVideo = this.$refs.friendsVideo;// for test
                this.friendsVideo.srcObject = null;
                let servers = {'iceServers': [ {'urls': 'stun:stun.l.google.com:19302'},{'urls': 'stun:stun.services.mozilla.com'}, {'urls': 'turn:numb.viagenie.ca','credential': 'webrtc','username': 'websitebeaver@mail.com'}]};
                // console.log(servers);
                this.pc = new RTCPeerConnection(servers);
                this.showMyFace();
                // console.log(this.pc);

                // this.pc.onicecandidate = (event =>
                //     event.candidate?this.sendMessage( JSON.stringify({'ice': event.candidate})):console.log("Sent All Ice") );
                this.pc.ontrack = (event =>
                    this.friendsVideo.srcObject = event.stream);

            },

            offerVideoChat(){
                console.log('выполняем offerVideoChat()');
                axios.get('/offerVideoChat/'+this.conversation.id);
                    // .then((response) => {
                    //     console.log(response.data);
                    // });
                this.startVideoChat();
            },


            sendMessage( data) {
                // var msg = database.push({ sender: senderId, message: data });
                // msg.remove();
                console.log(data);
                axios.post('/videoChat/'+ this.conversation.id, {
                    data: JSON.stringify(data)
                }).then((response) => {
                            console.log('response - '+ JSON.stringify(response.data));
                        });

                 },

            readSdp() {
                    console.log('выполняем readSdp()  '+this.sdp);
                    if (!this.start)this.startVideoChat();
                     let msg = JSON.parse(this.sdp);
                    console.log('выполняем readSdp(obj)  '+ msg);
                    // var sender = data.val().sender;
                     if (msg.sdp_send.type == "offer"){
                         this.pc.setRemoteDescription(new RTCSessionDescription(msg.sdp_send))
                             .then(() => this.pc.createAnswer())
                             .then(answer => this.pc.setLocalDescription(answer))
                             .then(() => this.sendMessage({'sdp_send': this.pc.localDescription}));
                         this.pc.onicecandidate = (event => {
                             if (event.candidate) {
                                 this.sendMessage({'ice_send': event.candidate});
                                 console.log('отправляем ICE кандидатов');
                             } else {
                                 console.log("Sent All Ice " + event.candidate)
                             }
                         });
                         } else if (msg.sdp_send.type == "answer"){
                         this.pc.setRemoteDescription(new RTCSessionDescription(msg.sdp_send));
                         this.pc.onicecandidate = (event => {
                             if (event.candidate) {
                                 this.sendMessage({'ice_send': event.candidate});
                                 console.log('отправляем ICE кандидатов');
                             } else {
                                 console.log("Sent All Ice " + event.candidate)
                             }
                         });
                     }
            },
            readIce() {
                console.log('выполняем readIce()  '+ this.ice);
                let msg = JSON.parse(this.ice);
                if (!this.start)this.startVideoChat();
                if (msg.ice_send != undefined)
                        this.pc.addIceCandidate(new RTCIceCandidate(msg.ice_send));
            },

                    // database.on('child_added', readMessage);

            showMyFace() {
                    console.log('in showMyFace');
                    navigator.mediaDevices.getUserMedia({audio:true, video:true})
                         .then(stream => this.yourVideo.srcObject = stream)
                         .then(stream => this.pc.addStream(stream));

            },

            showFriendsFace() {
                    this.startVideoChat();
                    console.log('выполняем showFriendsFace');
                    this.pc.createOffer()
                        .then(offer => this.pc.setLocalDescription(offer) )
                        .then(() => this.sendMessage( {'sdp_send': this.pc.localDescription}) );
            }

        }
    }


</script>

<style scoped>
    video {
        background-color: #faf2cc;
        border-radius: 15px;
        margin: 10px 0px 0px 10px;
        width: 400px;
        height: 260px;
    }
    button {
        margin: 5px 0px 0px 10px !important;
        width: 810px;
    }

</style>
