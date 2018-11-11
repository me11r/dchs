<template>
    <div
        class="user-list__row"
        :class="selectedClass"
        @click="select"
    >
        <i
            class="status-icon fas fa-circle"
            :class="userOnlineClass"></i>&nbsp;{{ user.name }}
    </div>
</template>
<script>
import moment from 'moment';
import EventBus from './MessengerEventBus';

const evbus = EventBus();

export default {
    name: 'VUserListRow',
    props: {
        user: {
            type: Object,
            default: function() {
                return {};
            }
        }
    },
    data: function() {
        return {
            selected: false
        };
    },
    computed: {
        isUserOnline: function() {
            if (!this.user.last_connect_at) {
                return false;
            }
            const visitTime = moment(this.user.last_connect_at);
            return moment().diff(visitTime, 'minutes') < 5;
        },
        userOnlineClass: function() {
            return this.isUserOnline ? 'online' : '';
        },
        selectedClass: function() {
            return this.selected ? 'is-active' : '';
        }
    },
    methods: {
        select: function() {
            this.selected = true;
            evbus.$emit('messenger-selected-user', this.user);
        }
    },
    mounted: function() {
        evbus.$on('messenger-selected-user', (user) => {
            if (user.id !== this.user.id) {
                this.selected = false;
            }
        });
    }
};
</script>
<style lang="scss">
    @import "../../../sass/variables";

    .user-list__row {
        color: $primary;
        padding: 2px 5px;
        border-bottom: 1px solid cadetblue;
        background-color: $white-ter;
        cursor: pointer;
        border-left: 1px solid cadetblue;

        &.is-active {
            background-color: $blueish;
            font-weight: bold;
            border-left: 1px solid transparent;
        }

        &:hover {
            background-color: $white;
            color: $primary;
        }

        .status-icon {
            color: $grey-light;

            &.online {
                color: $green;
            }
        }
    }
</style>
