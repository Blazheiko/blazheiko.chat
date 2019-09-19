
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
window.Vue = require('vue');
//
// Vue.component('example', require('./components/ExampleComponent.vue').default);
Vue.component('chat-messages', require('./components/ChatMessages.vue').default);
Vue.component('chat-form', require('./components/ChatForm.vue').default);
Vue.component('chat-list', require('./components/ChatList.vue').default);
Vue.component('chat-header', require('./components/Header.vue').default);
Vue.component('video-chat', require('./components/VideoChat.vue').default);

const app = new Vue({
    el: '#app',

    data: {
        messages:[],
        contacts:[],
        contact :null,
        conversation:null,
        user:null,
        userto:null,
        selected_video:null,
        index:0,
        // conversationid:0,
        unread:0,
        sdp:null,
        is_video:false
    },

    created() {
        this.fetchContacts();

        Echo.private('chat')
            .listen('MessageSent', (e) => {
                // console.log('Echo.private(chat)'+e.user);
                this.handleIncoming(e.message,e.user);
            });
    },
    updated(){
        var container =app.$refs.messageDisplay;
        // var container = document.getElementById('#chatcontainer');
        container.scrollTop = container.scrollHeight;
    },

    methods: {
        //Запрашиваем у сервера все сообщения по данному контакту
        fetchMessages(contact) {
            axios.get('/messages/'+contact.id).then(response => {
                console.log(response.data);
                this.messages=[];
                this.messages = response.data.messages;
                this.user = response.data.user;
                this.userto = response.data.userto;
                this.conversation=response.data.conversation;
                this.resetUnread( );
            });
        },

        //обрабатываем сообщение от пушера
        handleIncoming(message,userFrom) {
            // console.log(userFrom);
            if (this.user.id == message.user_to){
                console.log('в условии '+ message);
                // console.log('в условии с '+ userFrom);
                this.incrementCounter(userFrom);
                if (message.is_video &&
                    message.is_photo ){
                        console.log('пришло is_video');
                        this.startVideoChat(userFrom);
                }
                if (message.conversation_id == this.conversation.id) {
                    if (message.is_video){
                        this.sdp = message.video_descr;
                        console.log('пришло video_descr'+ message.video_descr);
                    }else {
                        this.messages.push(message);
                        this.contacts[this.index].count_read ++;
                    }
                }

            }

        },
        isOnContacts(conversationId){
            let flag = false;
            for (i=0;i < this.contacts.length;i++){
                if (this.contacts[i].conversation_id == conversationId){
                    flag = true;
                    break;
                }
            }
            return flag;
        },
        incrementCounter(userFrom){
            console.log('в incrementCounter(userFrom)');
            for (let i=0;i < this.contacts.length; i++){
                if (this.contacts[i].contact.id == userFrom.id){
                    this.contacts[i].counter ++;
                    console.log('this.contacts[i].counter ++');
                    // this.contacts[i].count_read ++;
                    break
                }
            }
        },
        // выравниваем колличество прочитанных и непрочитанных
        resetUnread(){
            this.contacts[this.index].count_read = this.conversation.counter;
            this.contacts[this.index].counter = this.conversation.counter;
        },
        startVideoChat(contact){
            // this.selectedContact(contact);
            this.selected_video = contact;
            this.is_video=true;

        },
        // запрашиваем у сервера список контактов
        fetchContacts() {
            axios.get('/contacts').then(response => {
                console.log(response.data);
                this.contacts = response.data;
            });
        },

        selectedContact(contact){
            this.contact= contact;
            for (let i=0; i<this.contacts.length ; i++){
                if (this.contacts[i].contact.id == contact.id) {
                    this.index = i ;
                    break}
            }
            this.fetchMessages(contact);
        },

        // отправляем сообщение на сервер
        sendMessage: function (text) {
            if (!this.contact) {
                return;
            }
            axios.post('/messages', {
                conversation_id: this.conversation.id,
                text: text
            }).then((response) => {
                console.log(response.data);
                this.messages.push(response.data.message);
                this.contacts[this.index].count_read++;
                this.contacts[this.index].counter++;
                // this.incrementCounter(this.conversation.id);
                // console.log(response.data);
            })
        },
        //отправляем файлы на сервер
        update(e) {
            if (!this.contact) {
                return;
            }
            e.preventDefault();
            let photoname = this.gatherFormData();

            axios.post('/photo/'+this.conversation.id,photoname)
                .then((response) => {
                    this.messages.push(response.data.message);
                    this.contacts[this.index].count_read++;
                    this.contacts[this.index].counter++;
                    // this.incrementCounter(this.conversation.id);
                    console.log(response.data);
                })
        },

        gatherFormData() {
            const data = new FormData();

            data.append('photo', this.$refs.photo.files[0]);

            return data;
        },
    }
});
