
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
        messages:[],
        contacts:[],
        contact :null,
        conversation:null,
        user:null,
        userto:null,
        conversationid:0
    },

    created() {
        this.fetchContacts();

        Echo.private('chat')
            .listen('MessageSent', (e) => {
                // console.log(e.message.conversation_id);
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
            axios.get('/messages/'+this.contact.id).then(response => {
                console.log(response.data);
                this.messages=[];
                this.messages = response.data.messages;
                this.user = response.data.user;
                this.userto = response.data.userto;
                this.conversation=response.data.conversation;
                this.resetUnread();
            });
        },

        //обрабатываем сообщение от пушера
        handleIncoming(message,userFrom) {
            if (this.contact){

                if (message.conversation_id == this.conversation.id) {
                    this.messages.push(message);
                    this.incrementCounter(userFrom.id);
                    return;
                }
                else {
                    for (i=0;i < this.contacts.length;i++) {
                        if (this.contacts[i].contact.id == userFrom.id) {
                            this.contacts[i].unread++;
                            this.contacts[i].counter++;
                            this.sendUnread(this.contacts[i].unread,message.conversation_id);
                            return
                        }
                    }
                }
            }

        },
        incrementCounter(idUserFrom){
            for (i=0;i < this.contacts.length;i++){
                if (this.contacts[i].contact.id == idUserFrom){
                    this.contacts[i].counter ++;
                    return
                }
            }
        },
        resetUnread(){
            for (i=0;i < this.contacts.length;i++){
                if (this.contacts[i].contact.id == this.contact.id){
                    this.contacts[i].unread =0;
                    return
                }
            }
        },
        sendUnread(unread,conversation_id){
            axios.get('/saveUnread/'+unread+'/'+conversation_id).then(response => {
                console.log(response.data);
            });
        },

        // запрашиваем у сервера список контактов
        fetchContacts() {
            axios.get('/contacts').then(response => {
                console.log(response.data);
                this.contacts = response.data;
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
                console.log(response.data);
                this.messages.push(response.data.message);
                this.incrementCounter(this.contact.id);
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
                    this.incrementCounter(this.contact.id);
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
