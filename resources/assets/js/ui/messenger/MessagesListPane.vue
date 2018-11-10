<template>
    <div class="messages-list">
        eqeqe
        qweqeqqe
        qeqeqe
    </div>
</template>

<script>
import EventBus from './MessengerEventBus';

const evbus = EventBus();
export default {
    name: 'MessagesListPane',
    data: function() {
        return {
            user: {
                id: 0,
                name: ''
            },
            messages: []
        };
    },
    computed: {},
    methods: {
        addMessage: function(message) {
            this.messages.push({type: 'text', message: message});
        }
    },
    mounted: function() {
        evbus.$on('messenger-selected-user', (user) => {
            this.user = user;
        });
        evbus.$on('messenger-message-sent', (message, user) => {
            console.log(message, user);
        });
    }
};
</script>

<style lang="scss" scoped>
    @import "../../../sass/variables";

    .messages-list {
        max-height: 400px;
        min-height: 400px;
        border-bottom: 1px solid cadetblue;
        background-color: $blueish;
        overflow-y: scroll;
    }
</style>
