<template>
    <div
        class="users-list">
        <div
            class="preloader"
            v-if="!isLoadedList">
            <SvgPreloader/>
        </div>
        <v-user-list-row
            v-if="isLoadedList"
            v-for="user in allowedUsersToSend"
            :user="user"
            :key="user.id"
            :multiselect.sync="multiselect"
        />
    </div>
</template>

<script>
import rights from '../../scripts/rights';
import axios from 'axios';
import SvgPreloader from './SvgPreloader';
import VUserListRow from './VUserListRow';
import EventBus, {EVENT_NAMES} from './MessengerEventBus';

import SocketListener from '../../scripts/socket-listener';
import {MessengerSocketEvents} from './MessengerSocketEvents';
import _ from 'lodash';
import moment from 'moment';

const evbus = EventBus();
const api = axios.create({
    baseURL: '/api/messenger/'
});

export default {
    name: 'UsersList',
    props: {
        multiselect: {
            type: Boolean,
            default: false
        }
    },
    components: {VUserListRow, SvgPreloader},
    data: function() {
        return {
            isLoadedList: false,
            users: [],
            lastCheckTime: null,
            selectedUser: {}
        };
    },
    computed: {
        allowedUsersToSend() {
            return this.users.filter((user) => {
                return rights.canSendMessage(user.id) || user.email === 'notifications@localhost.net';
            });
        },
        totalUnreadCount() {
            let count = 0;
            this.users.map((item) => {
                if (item && item.unread_count) {
                    count += item.unread_count;
                }
            });
            return count;
        }
    },
    methods: {
        updateUsers: function() {
            return api.get('users/list')
                .then(response => {
                    this.users = response.data.users;
                    this.isLoadedList = true;
                    this.lastCheckTime = Date.now();
                });
        },
        defineIncomingMessageListener() {
            SocketListener
                .privateUserChannel()
                .then((channel) => {
                    channel
                        .listen(MessengerSocketEvents.MessageCreated, (event) => {
                            const senderId = event.message.sender_id;

                            if (parseInt(senderId) === parseInt(this.selectedUser.id)) {
                                this.markMessagesAsRead(this.selectedUser);
                            } else {
                                let sender = _.find(this.users, {id: senderId});
                                if (sender) {
                                    sender.unread_count++;
                                    sender.last_connect_at = moment().format('YYYY-MM-DD hh:mm');
                                }
                            }
                        });
                });
        },
        markMessagesAsRead(user) {
            let sender = _.find(this.users, {id: user.id});
            if (sender) {
                sender.unread_count = 0;
            }
        }
    },
    watch: {
        totalUnreadCount(newValue) {
            evbus.$emit(EVENT_NAMES.totalUnreadCountChanged, newValue);
        }
    },
    mounted: function() {
        if (!this.isLoadedList) {
            this.updateUsers();
        }

        evbus.$on(EVENT_NAMES.messengerSelectedUser, (user) => {
            this.selectedUser = user;
            this.markMessagesAsRead(user);
        });

        this.defineIncomingMessageListener();
    }
};
</script>

<style scoped lang="scss">
    @import "../../../sass/variables";
    .preloader {
        min-height: 100%;
        min-width: 100%;
        display: flex;
        justify-content: center;
        justify-items: center;
    }
    .users-list {
        height: 100%;
        min-height: 100%;
        font-size: 14px;
        min-width: 250px;
        overflow-y: scroll;
    }
</style>
