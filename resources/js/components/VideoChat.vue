<template v-if="video_is">

    <div >
        <video id="yourVideo" ref="yourVideo"  autoplay muted></video>
        <video id="friendsVideo" ref="friendsVideo" autoplay></video>
        <br />
        <button @click="showMyFace()" type="button" class="btn btn-danger btn-lg"><span class="glyphicon glyphicon-facetime-video" aria-hidden="true"></span> Call</button>
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
                this.showMyFace();
                this.yourId = this.user.id;
                this.senderId = this.contact.id;
                // this.yourVideo = document.getElementById("yourVideo");
                this.yourVideo = this.$refs.yourVideo;
                // this.friendsVideo = document.getElementById("friendsVideo");
                this.yourVideo = this.$refs.friendsVideo;
                let servers = {'iceServers': [ {'urls': 'stun:stun.l.google.com:19302'},{'urls': 'stun:stun.services.mozilla.com'}, {'urls': 'turn:numb.viagenie.ca','credential': 'webrtc','username': 'websitebeaver@mail.com'}]};
                console.log(servers);
                this.pc = new RTCPeerConnection(servers);
                console.log(this.pc);
                this.pc.onicecandidate = (event => {
                    if (event.candidate) {
                        this.sendMessage(JSON.stringify({'ice': event.candidate}));
                        console.log(JSON.stringify({'ice': event.candidate}));
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
                    this.yourVideo = this.$refs.yourVideo;// for test
                    this.yourVideo.srcObject = null;

                    // let getUserMedia =
                    //     navigator.getUserMedia ||
                    //     navigator.webkitGetUserMedia ||
                    //     navigator.mozGetUserMedia ||
                    //     navigator.msGetUserMedia ||
                    //     navigator.oGetUserMedia;

                    navigator.mediaDevices.getUserMedia({
                        audio: true,
                        video: {
                            width: {
                                exact: 320
                            },
                            height: {
                                exact: 240
                            }
                        }
                    }).then(function (stream) {
                            this.yourVideo.srcObject = stream;
                    }).catch(function(e) {
                            alert('getUserMedia() error: ' + e.name);
                        });

                //     navigator.getUserMedia(
                //     // Настройки
                //     {video: true},
                //     // Колбэк для успешной операции
                //     function(stream){
                //         // Создаём объект для видео потока и
                //         // запускаем его в HTLM элементе video.
                //         this.yourVideo.src = window.URL.createObjectURL(stream);
                //         // Воспроизводим видео.
                //         this.yourVideo.play();
                //     },
                //     // Колбэк для не успешной операции
                //     function(err){
                //         // Наиболее частые ошибки — PermissionDenied и DevicesNotFound.
                //         console.error(err);
                //     }
                // );
                    //|| navigator.mozGetUserMedia

                    //  navigator.getWebcam = (navigator.getUserMedia || navigator.webKitGetUserMedia || navigator.moxGetUserMedia || navigator.msGetUserMedia || navigator.mediaDevices);
                    // if (navigator.getWebcam.getUserMedia) {
                    //     navigator.getWebcam.getUserMedia({  audio: true, video: true })
                    //         .then(function (stream) {
                    //             //Display the video stream in the video object
                    //             this.yourVideo.srcObject = window.URL.createObjectURL(stream);
                    //             this.yourVideo.play();
                    //             console.log('в условии внутри ');
                    //
                    //         })
                    //         .catch(function (e) { console.log('в условии  '+ e.name + ': ' + e.message +navigator.mediaDevices.getUserMedia); });
                    // }
                    // else {
                    //     navigator.getWebcam({ audio: true, video: true },
                    //         function (stream) {
                    //             //Display the video stream in the video object
                    //             this.yourVideo.srcObject = stream
                    //         },
                    //          console.log("Web cam is not accessible.") )
                    // }


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
