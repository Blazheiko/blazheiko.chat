
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


const app = new Vue({
    el: '#app',

    data: {
        messages: []
    },

    created() {
        this.fetchMessages();

        Echo.private('chat')
            .listen('MessageSent', (e) => {
                this.messages.push({
                    message: e.message.message,
                    photo_url: e.message.photo_url,
                    is_photo:e.message.is_photo,
                    user: e.user
                });
            });


    },
    updated(){
        var container =app.$refs.messageDisplay;
        // var container = document.getElementById('#chatcontainer');
        container.scrollTop = container.scrollHeight;
    },

    methods: {
        fetchMessages() {
            axios.get('/messages').then(response => {
                this.messages = response.data;
            });
        },

        addMessage(message) {
            this.messages.push(message);

            axios.post('/messages', message).then(response => {
                console.log(response.data);
            });
        },
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
