<template>
    <div
        class="message-pane"
        :class="activeClass">
        <v-messages-list
            :user="user"
            v-show="selected && !multiselect"/>
        <div
            v-if="multiselect"
            class="multiselect--message-pane">
            <transition
                name="slide-fade"
                mode="in-out">
                <v-svg-fly-plane v-if="sending"/>
            </transition>
        </div>
        <v-reply-pane
            :multiselect="multiselect"
            :checked-users="checkedUsers"/>
    </div>
</template>

<script>
import VMessagesList from './MessagesListPane';
import VReplyPane from './MessageReplyPane';
import SvgFlyPlane from './SvgFlyPlane';
import EventBus, {EVENT_NAMES} from './MessengerEventBus';
const evbus = EventBus();
export default {
    name: 'MessagePane',
    props: {
        multiselect: {
            type: Boolean,
            default: false
        },
        checkedUsers: {
            type: Array,
            default: () => []
        }
    },
    data: function() {
        return {
            me: {},
            user: {},
            selected: false,
            sending: false
        };
    },
    components: {
        'v-messages-list': VMessagesList,
        'v-reply-pane': VReplyPane,
        'v-svg-fly-plane': SvgFlyPlane
    },
    computed: {
        visible: function() {
            return (this.selected || this.multiselect);
        },
        activeClass: function() {
            return this.visible ? 'is-active' : '';
        }
    },
    mounted: function() {
        evbus.$on(EVENT_NAMES.messengerSelectedUser, (user) => {
            this.user = user;
            this.selected = (user.id !== 0);
        });
        evbus.$on(EVENT_NAMES.messageSending, () => { this.sending = true; });
        evbus.$on(EVENT_NAMES.messageSent, () => { this.sending = false; });
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
            //border-right: 1px solid $primary;
        }
        .multiselect--message-pane {
            max-height: 400px;
            min-height: 400px;
            height: 400px;
            border-bottom: 1px solid cadetblue;
            background-color: $blueish;
            text-align: center;
            justify-content: center;
            display: flex;
            align-items: center;
        }
    }
</style>
