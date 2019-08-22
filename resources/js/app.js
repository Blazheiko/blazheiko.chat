
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

const app = new Vue({
    el: '#app',

    data: {
        messages: [],
        contacts:[],
        contact:null,
        conversation:null,
        user:null,
        userto:null,
        conversationid:0
    },

    created() {
        this.fetchContacts();

        Echo.private('chat')
            .listen('MessageSent', (e) => {
                this.hanleIncoming(e.message);
                console.log(e);
                // this.messages.push({
                //     message: e.message.message,
                //     photo_url: e.message.photo_url,
                //     is_photo:e.message.is_photo,
                //     userid: e.user.id
                // });
            });


    },
    updated(){
        var container =app.$refs.messageDisplay;
        // var container = document.getElementById('#chatcontainer');
        container.scrollTop = container.scrollHeight;
    },

    methods: {

        fetchMessages(contact) {
            axios.get('/messages/'+this.contact.id).then(response => {
                console.log(response.data);
                this.messages=[];
                this.messages = response.data.conversation[0].messages;
                // console.log(this.messages);
                this.user = response.data.user;
                this.userto = response.data.userto;
                this.conversation=response.data.conversation[0];

            });
        },
        hanleIncoming(message) {
            if (this.contact && message.conversationId == this.conversation.id) {
                this.messages.push(message);
                return;
            }
        },

        fetchConversations() {
            axios.get('/conversations').then(response => {
                // console.log(response.data);
                this.conversations = response.data;
            });
        },
        fetchContacts() {
            axios.get('/contacts').then(response => {
                this.contacts = response.data;
                // console.log(this.contacts);

            });
        },

        selectedContact(contact){
            this.contact=contact;
            this.fetchMessages();
        },

        // отправляем сообщение на сервер
        sendMessage(text) {
            if (!this.contact) {
                return;
            }
            axios.post('/messages', {
                conversation_id: this.conversation.id,
                text: text
            }).then((response) => {
                this.messages.push(response.data);
                // console.log(response.data);
                // this.$emit('new', response.data);
            })
        },
        //отправляем файлы на сервер
        update(e) {
            e.preventDefault();

            let photoname = this.gatherFormData();

            axios.post('photo', photoname )
                .then(response => this.messages.push({
                    message: response.data.message.message,
                    photo_url: response.data.message.photo_url,
                    is_photo:response.data.message.is_photo,
                    user: response.data.user
                }));
        },

        gatherFormData() {
            const data = new FormData();

            data.append('photo', this.$refs.photo.files[0]);

            return data;
        },
    }
});
