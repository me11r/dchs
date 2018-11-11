<template>
    <div class="messages-list">
        <div
            class="messages-loading"
            v-if="loading">
            <v-preloader/>
        </div>
    </div>
</template>

<script>
import SvgPreloader from './SvgPreloader';
import EventBus from './MessengerEventBus';
import axios from 'axios';
const evbus = EventBus();
const api = axios.create({
    baseURL: '/api/messenger/'
});
export default {
    name: 'MessagesListPane',
    data: function() {
        return {
            user: {
                id: 0,
                name: ''
            },
            loading: false,
            messages: []
        };
    },
    components: {
        'v-preloader': SvgPreloader
    },
    computed: {},
    methods: {
        addMessage: function(message) {
            this.messages.push({type: 'text', message: message});
        },
        fetchMessages: function(user) {
            this.loading = true;
            return api.get('/messages/list/' + user.id)
                .then(response => {
                    this.messages = response.data.messages;
                    this.loading = false;
                });
        }

    },
    mounted: function() {
        evbus.$on('messenger-selected-user', (user) => {
            this.user = user;
            this.fetchMessages(user);
        });
        evbus.$on('messenger-message-sent', (message, user) => {
            if (user.id === this.user.id) {
                this.addMessage(message);
            }
        });
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
