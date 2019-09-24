<template>

    <div class="input-group">
        <div class="composer">
            <textarea v-model="message" @keydown.enter="send" placeholder="Message..."></textarea>

        </div>
        <a>
            <button @click="send" type="button" class="btn btn-warning">Отправить оригинал </button>
        </a>


        <div >
            <button @click="translate()" type="button" class="btn btn-success "> Перевести на английский </button>
        </div>
        <div class="composer">

            <textarea v-model="translateMessage" @keydown.enter="sendTranslate" placeholder="Translation..."></textarea>


        </div>
        <a>
            <button @click="sendTranslate" type="button" class="btn btn-warning"> Отправить перевод </button>
        </a>
    </div>

</template>

<script>
    export default {
        data() {
            return {
                message: '',
                translateMessage: ''
            };
        },
        methods: {
            send(e) {
                e.preventDefault();

                if (this.message == '') {
                    return;
                } else if (this.isKyr(this.message)) {
                    this.$emit('send', this.message);
                    this.message = '';
                    this.translateMessage = '';
                } else alert('В сообщении присутствуют символы кирилицы');

            },
            sendTranslate(e) {
                e.preventDefault();

                if (this.translateMessage == '') {
                    return;
                } else if (this.isKyr(this.translateMessage)) {
                    this.$emit('send', this.translateMessage);
                    this.message = '';
                    this.translateMessage = '';
                } else alert('В сообщении присутствуют символы кирилицы');

            },
            translate() {
                axios.post('/translate/en', {
                    text: this.message
                }).then((response) => {
                    console.log(response.data);
                    console.log(response.data.translate);
                    this.translateMessage = response.data.translate;
                })
            },
            isKyr(str) {
                for ( let i = 0 ; i < str.length ;i++ ){
                    if(str.charCodeAt(i) > 122) {
                        return false;
                    }
                }
                return true;
            }
        }
    }
</script>

<style lang="scss" scoped>
    .composer textarea {
        width: 550px;
        margin: 10px;
        resize: none;
        border-radius: 3px;
        border: 1px solid lightgray;
        padding: 6px;
    }
    button {
        margin: 5px 0px 0px 10px !important;
        width: 250px;
        height: 30px;
        border-radius: 5px;
    }
</style>
