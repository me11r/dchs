<template>
    <div class="messages-list">
        <div
            class="messages-loading"
            v-if="loading && !loaded">
            <v-preloader/>
        </div>
        <div
            class="messages"
            v-if="!loading || loaded"
            v-for="message in messages"
            :key="message.id"
        ><v-message
            :message="message"
            :me="me"
            :user="user"
        /></div>
    </div>
</template>

<script>
import moment from 'moment';
import VMessage from './SingleMessage';
import SvgPreloader from './SvgPreloader';
import EventBus, {EVENT_NAMES} from './MessengerEventBus';
import axios from 'axios';
import SocketListener from '../../scripts/socket-listener';
import _ from 'lodash';
import {MessengerSocketEvents} from './MessengerSocketEvents';

const evbus = EventBus();
const api = axios.create({
    baseURL: '/api/messenger/'
});
export default {
    name: 'MessagesListPane',
    props: {
        me: {
            type: Object,
            default: function() {
                return {
                    id: 0,
                    name: ''
                };
            }
        }
    },
    data: function() {
        return {
            user: {
                id: 0,
                name: ''
            },
            loading: false,
            loaded: false,
            messages: []
        };
    },
    components: {
        'v-preloader': SvgPreloader,
        'v-message': VMessage
    },
    computed: {},
    methods: {
        addMessage: function(message) {
            this.messages.push(message);
            this.scrollDown();
        },
        fetchMessages: function() {
            this.loading = true;
            if (this.user.id !== 0) {
                return api.get('/messages/list/' + this.user.id).then(response => {
                    this.messages = response.data.messages;
                    this.messages = _.sortBy(this.messages, 'id', ['asc']);
                    this.loading = false;
                    this.loaded = true;

                    this.markChatAsRead();
                }).then(() => {
                    this.scrollDown();
                });
            }
        },
        defineIncomingMessageListener() {
            SocketListener
                .privateUserChannel()
                .then((channel) => {
                    channel
                        .listen(MessengerSocketEvents.MessageCreated, (event) => {
                            if (event.message.sender_id === this.user.id) {
                                this.messages.push(event.message);
                                this.scrollDown();

                                this.markChatAsRead();
                            }
                        });
                });
        },
        markChatAsRead: _.debounce(function() {
            if (_.find(this.messages, {is_viewed: 0, reciever_id: window.user_id})) {
                api.get('/messages/chat_was_read/' + this.user.id).then(() => {
                    this.messages = this.messages.map((item) => {
                        if (item.reciever_id === window.user_id) {
                            item.is_viewed = true;
                        }
                        return item;
                    });
                    this.scrollDown();
                });
            }
        }, 2000),
        scrollDown: _.debounce(function() {
            this.$el.scrollTop = this.$el.scrollHeight;
        }, 300)

    },
    mounted: function() {
        evbus.$on(EVENT_NAMES.messengerSelectedUser, (user) => {
            this.user = user;
            this.loaded = false;
            this.fetchMessages();
        });
        evbus.$on(EVENT_NAMES.messageSent, (message, userId) => {
            if (userId === this.user.id) {
                this.addMessage(message);
            }
        });
        this.fetchMessages();
        this.defineIncomingMessageListener();
    }
};
</script>

<style lang="scss" scoped>
    @import "../../../sass/variables";

    .messages-list {
        max-height: 400px;
        min-height: 400px;
        height: 400px;
        border-bottom: 1px solid cadetblue;
        background-color: $blueish;
        overflow-y: scroll;
        .messages-loading {
            height: 100%;
            min-width: 100%;
            text-align: center;
            display: flex;
            justify-content: center;
            justify-items: center;
        ;
        }
    }
</style>
