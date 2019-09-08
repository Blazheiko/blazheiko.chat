<template>
    <div class="contacts-list">
        <a>Список Контактов </a>
    <ul>
        <li v-for="contact in contacts" :key="contact.contact.id" @click="selectContact(contact.contact)" :class="{ 'selected': contact.contact == selected }">
            <div class="avatar">
                <img :src="'/uploads/avatars/'+ contact.contact.avatar" :alt="contact.name" >
            </div>
            <div class="contact">
                <p class="name">{{ contact.contact.name }}</p>
                <p class="email">{{ contact.contact.email }}</p>
            </div>
<!--            -->
            <span class="unread" v-if="contact.counter - contact.count_read" >{{ contact.counter - contact.count_read}}</span>
            <a class="counter" v-if="contact.counter" >{{ contact.counter }}</a>.
        </li>
    </ul>
    </div>
</template>

<script>
    export default {
        props: {
            contacts: {
                type: Array,
                 default: []
            },
        },
        data() {
            return {
                selected:null
            };
        },
        mounted: function () {
            // this.$nextTick(function () {
            //     // Код, который будет запущен только после
            //     // отображения всех представлений
            //     // console.log('в mounted');
            //     // if (this.contacts.length){
            //     //     this.selected = this.contacts[0].contact
            //     //         };
            // })
        },

        updated(){
            if (this.selected == null && this.contacts.length){
                this.selectContact(this.contacts[0].contact)
            }
        },

        methods: {

            selectContact(contact) {
                this.selected = contact;
                this.$emit('selected', contact);
            }
        },
        computed: {
            // sortedContacts() {
            //     return _.sortBy(this.contacts, [(contact) => {
            //         if (contact == this.selected) {
            //             return Infinity;
            //         }
            //         return contact.unread;
            //     }]).reverse();
            // }
        }
    }
</script>

<style lang="scss" scoped>

    .contacts-list {
        flex: 2;
        /*max-height: 600px;*/
        overflow: scroll;
        border-left: 1px solid #a6a6a6;
        float: left;
        max-height: 700px;
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
                    background: #d5b69fa6;
                }

                span.unread {
                    background: #82e0a8;
                    color: #fff;
                    position: absolute;
                    right: 11px;
                    top: 18px;
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
                a.counter {
                    /*background: #82e0a8;*/
                    /*color: #fff;*/
                    position: absolute;
                    right: 11px;
                    bottom: 5px;
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
