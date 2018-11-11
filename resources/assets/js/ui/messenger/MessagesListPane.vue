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
import EventBus from './MessengerEventBus';
import axios from 'axios';
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
            this.messages.push({type: 'text', message: message, created_at: moment().format('YYYY-MM-DD hh:mm')});
        },
        fetchMessages: function() {
            this.loading = true;
            return api.get('/messages/list/' + this.user.id)
                .then(response => {
                    this.messages = response.data.messages;
                    this.loading = false;
                    this.loaded = true;
                })
                .then(() => {
                    this.scrollDown();
                });
        },
        scrollDown: function() {
            this.$el.scrollTop(this.$el.scrollHeight);
        }

    },
    mounted: function() {
        evbus.$on('messenger-selected-user', (user) => {
            this.user = user;
            this.loaded = false;
            this.fetchMessages();
        });
        evbus.$on('messenger-message-sent', (message, user) => {
            if (user.id === this.user.id) {
                this.addMessage(message);
            }
        });
        setInterval(this.fetchMessages, 1300);
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
