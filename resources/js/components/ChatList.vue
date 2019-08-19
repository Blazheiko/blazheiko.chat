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
        props: ['contacts'],
    }

</script>

<style lang="scss" scoped>

    .contacts-list {
        flex: 2;
        /*max-height: 600px;*/
        overflow: scroll;
        border-left: 1px solid #a6a6a6;
        float: left;
        max-height: 600px;
        width: min-content;
        overflow: scroll;

        ul {
            list-style-type: none;
            padding-left: 0;

            li {
                display: flex;
                padding: 2px;
                border-bottom: 1px solid #aaaaaa;
                height: 80px;
                position: relative;
                cursor: pointer;

                &.selected {
                    background: #dfdfdf;
                }

                span.unread {
                    background: #82e0a8;
                    color: #fff;
                    position: absolute;
                    right: 11px;
                    top: 20px;
                    display: flex;
                    font-weight: 700;
                    min-width: 20px;
                    justify-content: center;
                    align-items: center;
                    line-height: 20px;
                    font-size: 12px;
                    padding: 0 4px;
                    border-radius: 3px;
                }

                .avatar {
                    flex: 1;
                    display: flex;
                    align-items: center;

                    img {
                        width: 35px;
                        border-radius: 50%;
                        margin: 0 auto;
                    }
                }

                .contact {
                    flex: 3;
                    font-size: 10px;
                    overflow: hidden;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;

                    p {
                        margin: 0;

                        &.name {
                            font-weight: bold;
                        }
                    }
                }
            }
        }
    }

</style>
