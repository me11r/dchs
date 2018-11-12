<template>
    <div class="reply-pane">
        <div class="attach-file"><a class="upload"><i class="fas fa-upload fa-fw fa-2x"></i></a></div>
        <div class="text">
            <textarea
                :readonly="sending"
                v-model="message"
                name="message-text"
                class="reply-textarea"
                @keypress.enter.exact="sendMessage"
        ></textarea></div>
        <div class="send-message"><a
            @click.prevent="sendMessage"
            class="send">
            <i
                class="fas fa-fw fa-2x"
                :class="sendingClass"></i>
        </a></div>
    </div>
</template>

<script>
import axios from 'axios';
import EventBus from './MessengerEventBus';

const evbus = EventBus();
const api = axios.create({
    baseURL: '/api/messenger/'
});
export default {
    name: 'MessageReplyPane',
    data: function() {
        return {
            message: '',
            user: {
                id: 0,
                name: ''
            },
            sending: false
        };
    },
    computed: {
        sendingClass: function() {
            return this.sending ? 'fa-spin fa-circle-notch' : 'fa-comment-alt';
        }
    },
    methods: {
        sendMessage: function() {
            if (!this.sending && (this.message !== '') && (this.user)) {
                this.send();
            }
        },
        send: function() {
            this.sending = true;
            return api.post('message/send', {message: this.message, to: this.user.id}).then(() => {
                evbus.$emit('messenger-message-sent', this.message, this.user);
                this.sending = false;
                this.message = '';
            });
        }
    },
    mounted: function() {
        evbus.$on('messenger-selected-user', (user) => {
            this.user = user;
        });
    }
};
</script>

<style lang="scss" scoped>
    @import "../../../sass/variables";

    .reply-pane {
        display: flex;
        min-height: 100px;
        height: 100px;
        box-sizing: border-box;

        .attach-file {
            flex-grow: 0;
            min-width: 100px;
            border-right: 1px solid cadetblue;
            box-sizing: border-box;
            display: flex;

            .upload {
                display: block;
                width: 100%;
                text-align: center;
                vertical-align: center;
                height: 100%;
                cursor: pointer;
                color: $grey-dark;

                &:hover {
                    background: linear-gradient(to bottom, #eeeeee 0%, #cccccc 100%);
                    color: $primary;
                }

                &:active {
                    background: linear-gradient(to bottom, #fff 0%, #ddd 100%);
                }

                .fas {
                    margin-top: 20%;
                }
            }
        }

        .text {
            flex-grow: 1;
            box-sizing: border-box;

            .reply-textarea {
                outline: none;
                width: 100%;
                font-size: 16px;
                padding: 3px 5px;
                min-height: 100%;
                line-height: 1.1;
                border: none;
                box-sizing: border-box;
            }
        }

        .send-message {
            box-sizing: border-box;
            flex-grow: 0;
            max-width: 100px;
            width: 100%;
            border-left: 1px solid cadetblue;
            display: flex;

            .send {
                display: block;
                width: 100%;
                text-align: center;
                vertical-align: center;
                height: 100%;
                cursor: pointer;
                color: $grey-dark;

                &:hover {
                    background: linear-gradient(to bottom, #eeeeee 0%, #cccccc 100%);
                    color: $primary;
                }

                &:active {
                    background: linear-gradient(to bottom, #fff 0%, #ddd 100%);
                }

                .fas {
                    margin-top: 20%;
                }
            }

        }
    }
</style>
