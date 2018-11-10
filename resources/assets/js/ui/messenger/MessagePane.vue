<template>
    <div
        class="message-pane"
        :class="activeClass">
        <v-messages-list :user="user"/>
        <v-reply-pane/>
    </div>
</template>

<script>
import VMessagesList from './MessagesListPane';
import VReplyPane from './MessageReplyPane';
import EventBus from './MessengerEventBus';
const evbus = EventBus();
export default {
    name: 'MessagePane',
    data: function() {
        return {
            me: {},
            user: {},
            selected: false
        };
    },
    components: {
        'v-messages-list': VMessagesList,
        'v-reply-pane': VReplyPane
    },
    computed: {
        activeClass: function() {
            return this.selected ? 'is-active' : '';
        }
    },
    mounted: function() {
        evbus.$on('messenger-selected-user', (user) => {
            this.user = user;
            this.selected = true;
        });
    }
};
</script>

<style lang="scss" scoped>
    @import "../../../sass/variables";
    .message-pane {
        display: none;
        height: 100%;
        &.is-active {
            display: block;
            min-width: 600px;
            border-right: 1px solid $primary;
        }
    }
</style>
