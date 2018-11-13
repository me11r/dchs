<template>
    <div
        class="user-list__row"
        :class="selectedClass"
        @click="select"
    ><div class="user-list__row__section">
        <i
            class="status-icon fas fa-circle"
            :class="userOnlineClass"></i>&nbsp;{{ user.name }}
    </div>
        <div
            class="user-list__row__aside"
            v-if="hasUnread">{{ user.unread_count }}</div>
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
                return {
                    id: 0,
                    name: '',
                    fullname: '',
                    unread_count: 0,
                    last_connect_at: null
                };
            }
        }
    },
    data: function() {
        return {
            selected: false
        };
    },
    computed: {
        hasUnread: function() {
            return this.user.unread_count > 0;
        },
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
        display: flex;
        color: $primary;
        padding: 2px 5px;
        border-bottom: 1px solid cadetblue;
        background-color: $white-ter;
        cursor: pointer;
        border-left: 1px solid cadetblue;
        &__section {
            flex-grow: 1;
        }
        &__aside {
            flex-grow: 0;
            color: $white;
            background-color: darken($red, 20%);
            padding: 0 5px;
            border-radius: 300px;
        }

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
