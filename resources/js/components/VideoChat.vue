<template v-if="video_is">

    <div >
        <video id="yourVideo" ref="yourVideo"  autoplay muted></video>
        <video id="friendsVideo" ref="friendsVideo" autoplay></video>
        <br />
        <div v-if="start">
            <button @click="endVideoChat()" type="button" class="btn btn-danger btn-lg"><span class="glyphicon glyphicon-facetime-video" aria-hidden="true" ></span> Закончить видеочат </button>
        </div>
        <div v-else >
        <button @click="offerVideoChat()" type="button" class="btn btn-success btn-lg"><span class="glyphicon glyphicon-facetime-video" aria-hidden="true" ></span> Видеочат </button>
        </div>

        </div>


</template>

<script>
    export default {
        name: "VideoChat",
        props: ['user','contact','conversation','sdp','is_video'],
        data() {
            return {
                // selected: null,
                yourVideo:null,
                friendsVideo:null,
                pc:null,
                yourId:null,
                senderId:null,
                start:false,
                // setRemote:false,
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
                }
                if (!confirm('начать видеочат с - ' + this.contact.name)){
                    return;
                }
                this.start = true;
                this.yourId = this.user.id;
                this.senderId = this.contact.id;
                this.yourVideo = this.$refs.yourVideo;
                this.yourVideo.srcObject = null;
                this.friendsVideo = this.$refs.friendsVideo;
                this.friendsVideo.srcObject = null;
                const servers = {'iceServers': [
                        {'urls': 'stun:stun.l.google.com:19302'},
                        {url: 'turn:turn.anyfirewall.com:443?transport=tcp',
                        credential: 'webrtc',
                        username: 'webrtc'}
                    ]};
                // console.log(servers);
                this.pc = new RTCPeerConnection(servers);
                 console.log('перед pc.onicecandidate');
                this.pc.onicecandidate = (event => {
                    if (event.candidate) {
                        this.sendMessage({'ice_send': event.candidate});
                        console.log('отправляем ICE кандидатов');
                    } else {
                        console.log("Sent All Ice " + event.candidate);
                        this.listICE.forEach((item)=> this.pc.addIceCandidate(item));
                        this.listICE = [];
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
                try {
                    if (msg.sdp_send) {
                        // if we get an offer, we need to reply with an answer
                        if (msg.sdp_send.type == 'offer') {
                            await this.pc.setRemoteDescription(msg.sdp_send);
                            const stream = await navigator.mediaDevices.getUserMedia({audio:true, video:true});
                            stream.getTracks().forEach((track) => this.pc.addTrack(track, stream));
                            this.yourVideo.srcObject = stream;
                            await this.pc.setLocalDescription(await this.pc.createAnswer());
                            this.sendMessage({'sdp_send': this.pc.localDescription});
                        } else if (msg.sdp_send.type == 'answer') {
                            await this.pc.setRemoteDescription(msg.sdp_send);
                        } else {
                            console.log('Unsupported SDP type. Your code may differ here.');
                        }
                    } else if (msg.ice_send) {
                        await this.pc.addIceCandidate(msg.ice_send);
                    }
                } catch (err) {
                    console.error('что то пошло не так...'+err);
                    console.error(msg.ice_send);
                    this.listICE.push(msg.ice_send);

                }

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

            },
            endVideoChat(){
                this.pc.close();

                this.start = false;
                this.yourVideo.srcObject.getTracks().forEach(track => track.stop());
                alert('Видеочат с - ' + this.contact.name + '  завершен.');
                this.pc = null;
                this.yourVideo.srcObject = null;
                this.friendsVideo.srcObject = null;

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
