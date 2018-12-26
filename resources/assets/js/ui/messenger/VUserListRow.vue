<template>
    <div
        class="user-list__row"
        :class="selectedClass"
        @click="select"
    >
        <label :for="'user-check-id-' + user.id">
            <input
                v-if="multiselect"
                :id="'user-check-id-' + user.id"
                type="checkbox"
                :checked="is_checked">&nbsp;
        </label>
        <div class="user-list__row__section">
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
import EventBus, {EVENT_NAMES} from './MessengerEventBus';

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
        },
        multiselect: {
            type: Boolean,
            default: false
        },
        checked: {
            type: Boolean,
            default: false
        }
    },
    data: function() {
        return {
            selected: false,
            is_checked: this.checked
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
            if (this.multiselect) {
                this.selectInMultimode();
            } else {
                this.selected = true;
                evbus.$emit(EVENT_NAMES.messengerSelectedUser, this.user);
            }
        },
        selectInMultimode: function() {
            this.is_checked = !this.is_checked;
            evbus.$emit(EVENT_NAMES.messengerMultiselectUser, this.user, this.is_checked);
        }
    },
    mounted: function() {
        evbus.$on(EVENT_NAMES.messengerSelectedUser, (user) => {
            if (user.id !== this.user.id) {
                this.selected = false;
            }
        });
        evbus.$on(EVENT_NAMES.messengerClearMultiselect, () => {
            this.is_checked = false;
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
            border-left: 3px solid cadetblue;
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
