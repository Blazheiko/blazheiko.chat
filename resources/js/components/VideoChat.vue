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
        props: ['user','contact','conversation','msgvideochat','is_video'],
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
            msgvideochat: function () {
                this.readMessage() ;
            },
            is_video: function () {
                this.startVideoChat();
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
                this.pc.onicecandidate = (event => {
                    if (event.candidate) {
                        this.sendMessage(JSON.stringify({'ice': event.candidate}));
                        console.log(JSON.stringify('отправляем ICE кандидатов'));
                    } else {
                        console.log("Sent All Ice " + event.candidate)
                    }
                });
                // this.pc.onicecandidate = (event =>
                //     event.candidate?this.sendMessage( JSON.stringify({'ice': event.candidate})):console.log("Sent All Ice") );
                this.pc.ontrack = (event =>
                    this.friendsVideo.srcObject = event.stream);
                // this.showMyFace();
                this.showFriendsFace();

            },

            offerVideoChat(){
                console.log('выполняем offerVideoChat()');
                axios.get('/offerVideoChat/'+this.conversation.id)
                    .then((response) => {
                        console.log(response.data);
                    });
                this.startVideoChat();
            },


            sendMessage( data) {
                // var msg = database.push({ sender: senderId, message: data });
                // msg.remove();
                    axios.post('/videoChat/'+this.conversation.id,data)
                        .then((response) => {
                            console.log('сообщение отправлено');
                        })
                    return;
                 },

                readMessage() {
                    console.log('выполняем readMessage()');
                    let msg = JSON.parse(this.msgVideo);
                    // var sender = data.val().sender;
                    if (this.is_video) {
                        if (msg.ice != undefined)
                            this.pc.addIceCandidate(new RTCIceCandidate(msg.ice));
                        else if (msg.sdp.type == "offer")
                            this.pc.setRemoteDescription(new RTCSessionDescription(msg.sdp))
                                .then(() => this.pc.createAnswer())
                                .then(answer => this.pc.setLocalDescription(answer))
                                .then(() => sendMessage(JSON.stringify({'sdp': this.pc.localDescription})));
                        else if (msg.sdp.type == "answer")
                            this.pc.setRemoteDescription(new RTCSessionDescription(msg.sdp));
                    }
                },

                    // database.on('child_added', readMessage);

                showMyFace() {
                    console.log('in showMyFace');

                    navigator.mediaDevices.getUserMedia({audio:true, video:true})
                         .then(stream => this.yourVideo.srcObject = stream)
                         .then(stream => this.pc.addStream(stream));
                    return;

                },

                showFriendsFace() {
                    console.log('in showFriendsFace');
                    this.pc.createOffer()
                        .then(offer => this.pc.setLocalDescription(offer) )
                        .then(() => this.sendMessage( JSON.stringify({'sdp': this.pc.localDescription})) );
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
