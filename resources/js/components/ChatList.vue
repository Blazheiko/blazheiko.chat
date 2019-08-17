<template>
        <ul class="contacts-list">

        <a>Messages</a>
        <li class="left clearfix" v-for="contact in contacts">
            <div   class="chat-body clearfix">
                <div class="header">

                    <strong class="primary-font">
                        <img v-bind:src="'/uploads/avatars/'+ contact.avatar" style="width:32px; height:32px; border-radius:50%">
                        {{ contact.name }}
                    </strong>
                </div>



            </div>
        </li>
        </ul>
</template>

<script>
    export default {
        props: [contacts],
    }

</script>

<style lang="scss" scoped>
    .contacts-list {
        flex: 2;
        /*max-height: 600px;*/
        overflow: scroll;
        border-left: 1px solid #a6a6a6;
        float:left;
    }
</style>
