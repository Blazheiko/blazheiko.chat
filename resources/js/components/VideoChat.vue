<template v-if="video_is">

    <div >
        <video id="yourVideo" ref="yourVideo" autoplay muted></video>
        <video id="friendsVideo" ref="friendsVideo" autoplay></video>
        <br />
        <button @click="startVideoChat()" type="button" class="btn btn-danger btn-lg"><span class="glyphicon glyphicon-facetime-video" aria-hidden="true"></span> Call</button>
    </div>


</template>

<script>
    export default {
        name: "VideoChat",
        props: ['user','contact','conversation'],
        data() {
            return {
                selected: null,
                yourVideo:null,
                friendsVideo:null,
                pc:null,
                yourId:null,
                senderId:null,
            };
        },

        mounted() {


        },
        methods: {
            startVideoChat(){
                this.yourId = this.user.id;
                this.senderId = this.contact.id;
                // this.yourVideo = document.getElementById("yourVideo");
                this.yourVideo = this.$refs.yourVideo;
                // this.friendsVideo = document.getElementById("friendsVideo");
                this.yourVideo = this.$refs.friendsVideo;
                const servers = {'iceServers': [{'urls': 'stun:stun.services.mozilla.com'}, {'urls': 'stun:stun.l.google.com:19302'}, {'urls': 'turn:numb.viagenie.ca','credential': 'webrtc','username': 'websitebeaver@mail.com'}]};
                this.pc = new RTCPeerConnection(servers);
                this.pc.onicecandidate = (event =>
                    event.candidate?this.sendMessage( JSON.stringify({'ice': event.candidate})):console.log("Sent All Ice") );
                this.pc.onaddstream = (event =>
                    this.friendsVideo.srcObject = event.stream);
                this.showMyFace();
                this.showFriendsFace();

            },
                sendMessage( data) {
                // var msg = database.push({ sender: senderId, message: data });
                // msg.remove();
                    axios.post('/videoChat/'+this.conversation.id,data)
                        .then((response) => {
                            // this.messages.push(response.data.message);
                            // this.incrementCounter(this.contact.id);
                            console.log(response.data);
                        })
                    return;
                 },

                readMessage(data) {
                    var msg = JSON.parse(data.val().message);
                    var sender = data.val().sender;
                    if (sender != this.yourId) {
                        if (msg.ice != undefined)
                            this.pc.addIceCandidate(new RTCIceCandidate(msg.ice));
                        else if (msg.sdp.type == "offer")
                            this.pc.setRemoteDescription(new RTCSessionDescription(msg.sdp))
                                .then(() => this.pc.createAnswer())
                                .then(answer => this.pc.setLocalDescription(answer))
                                .then(() => sendMessage(yourId, JSON.stringify({'sdp': this.pc.localDescription})));
                        else if (msg.sdp.type == "answer")
                            this.pc.setRemoteDescription(new RTCSessionDescription(msg.sdp));
                    }
                },

                    // database.on('child_added', readMessage);

                showMyFace() {
                    console.log('in showMyFace');
                    navigator.getWebcam = (navigator.getUserMedia || navigator.webKitGetUserMedia || navigator.moxGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia);
                    if (navigator.mediaDevices.getUserMedia) {
                        navigator.mediaDevices.getUserMedia({  audio: true, video: true })
                            .then(function (stream) {
                                //Display the video stream in the video object
                            })
                            .catch(function (e) { logError(e.name + ": " + e.message); });
                    }
                    else {
                        navigator.getWebcam({ audio: true, video: true },
                            function (stream) {
                                //Display the video stream in the video object
                            },
                            function () { logError("Web cam is not accessible."); });
                    }
                    // navigator.mediaDevices.getUserMedia({audio:true, video:true})
                    //     .then(stream => this.yourVideo.srcObject = stream)
                    //     .then(stream => this.pc.addStream(stream));
                },

                showFriendsFace() {
                    this.pc.createOffer()
                        .then(offer => this.pc.setLocalDescription(offer) )
                        .then(() => this.sendMessage(this.yourId, JSON.stringify({'sdp': this.pc.localDescription})) );
                }

        }
    }


</script>

<style scoped>
    video {
        background-color: #faf2cc;
        border-radius: 7px;
        margin: 10px 0px 0px 10px;
        width: 400px;
        height: 293px;
    }
    button {
        margin: 5px 0px 0px 10px !important;
        width: 810px;
    }

</style>
