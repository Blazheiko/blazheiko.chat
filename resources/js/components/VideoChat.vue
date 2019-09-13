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
                start:false,
                setRemote:false,
                listICE:[]
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
                this.yourVideo = this.$refs.yourVideo;
                this.yourVideo.srcObject = null;
                this.friendsVideo = this.$refs.friendsVideo;
                this.friendsVideo.srcObject = null;
                const servers = {'iceServers': [ {'urls': 'stun:stun.l.google.com:19302'},{'urls': 'stun:stun.services.mozilla.com'}, {'urls': 'turn:numb.viagenie.ca','credential': 'webrtc','username': 'websitebeaver@mail.com'}]};
                // console.log(servers);
                this.pc = new RTCPeerConnection(servers);
                 console.log('перед pc.onicecandidate');
                this.pc.onicecandidate = (event => {
                    if (event.candidate) {
                        this.sendMessage({'ice_send': event.candidate});
                        console.log('отправляем ICE кандидатов');
                    } else {
                        console.log("Sent All Ice " + event.candidate)
                    }
                });

                console.log('перед pc.ontrack ');
                // this.pc.ontrack = (event =>
                //     this.friendsVideo.srcObject = event.stream);
                this.pc.ontrack = (event) => {
                    // don't set srcObject again if it is already set.
                    if (this.friendsVideo.srcObject) return;
                    this.friendsVideo.srcObject = event.streams[0];
                    console.log('добавлено friendsVideo.srcObject')
                };


            },

            offerVideoChat(){
                console.log('выполняем offerVideoChat()');
                this.startVideoChat();
                axios.get('/offerVideoChat/'+this.conversation.id);

            },


            sendMessage( data) {
                console.log(data);
                axios.post('/videoChat/'+ this.conversation.id, {
                    data: JSON.stringify(data)
                })
            },

            async readSdp() {
                console.log('выполняем readSdp()  ' + this.sdp);
                if (!this.start) this.startVideoChat();
                let msg = JSON.parse(this.sdp);
                console.log('выполняем readSdp(obj)  ' + msg);
                // var sender = data.val().sender;
                if (msg.ice_send != undefined ){
                    console.log('внутри  msg.ice_send != undefined  ');
                    if (this.setRemote){
                        this.listICE.forEach((item)=>this.pc.addIceCandidate(item));
                        this.listICE=[];
                         this.pc.addIceCandidate(msg.ice_send)
                             .then(
                                console.log("Кандидат добавлен")
                            );
                    } else {
                        this.listICE.push(msg.ice_send)
                    }
                }
                else {
                    if (msg.sdp_send.type == "offer") {
                        console.log('внутри условия msg.sdp_send.type == "offer"');
                        this.offer = true;
                         // this.pc.setRemoteDescription(msg.sdp_send);
                        // const stream = await navigator.mediaDevices.getUserMedia({audio:true, video:true});
                        // stream.getTracks().forEach((track) => this.pc.addTrack(track, stream));
                        //  this.pc.setLocalDescription( this.pc.createAnswer());
                        // this.sendMessage({'sdp_send': this.pc.localDescription});
                         this.pc.setRemoteDescription( new RTCSessionDescription(msg.sdp_send))
                            .then(() => this. showMyFace())
                            .then(() => this.pc.createAnswer())
                            .then(answer => this.pc.setLocalDescription(answer))
                            .then(() => this.sendMessage({'sdp_send': this.pc.localDescription}))
                             .then(()=>this.setRemote = true);
                    } else if (msg.sdp_send.type == "answer") {
                        console.log('внутри условия msg.sdp_send.type == "answer"');
                        await this.pc.setRemoteDescription(msg.sdp_send)
                            .then(()=>this.setRemote = true)
                            .then(() =>console.log('выполнили this.pc.setRemoteDescription(msg.sdp_send)'));
                        // this.pc.setRemoteDescription(new RTCSessionDescription(msg.sdp_send));
                    }
                }

            },
            async readIce() {
                console.log('выполняем readIce()  ' + this.ice);
                let msg = JSON.parse(this.ice);
                if (!this.start) this.startVideoChat();
                if (msg.ice_send != undefined)
                    await this.pc.addIceCandidate(msg.ice_send);
                // this.pc.addIceCandidate(new RTCIceCandidate(msg.ice_send));
            },


            async showMyFace() {
                    console.log('in showMyFace');
                try {
                    // get a local stream, show it in a self-view and add it to be sent
                    const stream = await navigator.mediaDevices.getUserMedia({audio:true, video:true});
                    stream.getTracks().forEach((track) => this.pc.addTrack(track, stream));
                    this.yourVideo.srcObject = stream;
                } catch (err) {
                    console.error('ошибка вебкамера....'+err);
                }
                    // navigator.mediaDevices.getUserMedia({audio:true, video:true})
                    //      .then(stream => this.yourVideo.srcObject = stream)
                    //     .then(stream =>stream.getTracks().forEach((track) => pc.addTrack(track, stream)));
                    //      // .then(stream => this.pc.addStream(stream));
            },

             showFriendsFace() {
                    this.startVideoChat();
                    console.log('выполняем showFriendsFace');
                 this.showMyFace();
                this.pc.onnegotiationneeded = async () => {
                    try {
                        this.pc.setLocalDescription(await this.pc.createOffer());
                        this.sendMessage({'sdp_send': this.pc.localDescription});
                        // send the offer to the other peer
                        // signaling.send({desc: pc.localDescription});
                    } catch (err) {
                        console.log('исключение в showFriendsFace()');
                        console.error(err);
                    }
                };

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
