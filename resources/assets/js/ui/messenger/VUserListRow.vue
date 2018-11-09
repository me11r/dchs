<template>
    <div
        class="user-list__row">
        <i
            class="status-icon fas fa-circle"
            :class="userOnlineClass"></i>&nbsp;{{ user.name }}
    </div>
</template>
<script>
import moment from 'moment';
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
        }
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
    &:hover {
         background-color: $white-bis;
     }

    .status-icon {
        color: $grey-light;
    &.online {
         color: $green;
     }
    }
    }
</style>
