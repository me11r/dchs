<template>
    <div
        id="emergency_messenger"
        class="is-hidden-touch">
        <div
            class="messenger"
            :class="openedClass"
            v-if="isOpened">
            <div class="header">
                <div class="header__title has-text-centered">{{ selectedUser.name }}</div>
                <div class="header__closer">
                    <span
                        @click="checked"
                        class="multimode-label"><i
                            class="fas"
                            :class="multiselect?'fa-check':'fa-circle'"></i>&nbsp;<span>Массовая рассылка</span></span>
                    <a
                        class="closer"
                        @click.prevent="openUp"><i class="fas fa-times"></i></a>
                </div>
            </div>
            <div class="messenger-body">
                <v-message-pane
                    :multiselect="multiselect"
                    :checked-users="checkedUsers"/>
                <v-user-list :multiselect="multiselect"/>
            </div>
        </div>
        <div
            class="opener"
            v-if="!isOpened">
            <div
                class="unread"
                v-if="unread > 0">{{ unread }}</div>
            <a
                @click.prevent="openUp">
                <i class="fas fa-comments fa-2x fa-fw"></i>
            </a>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import VueMessengerUserList from './UsersList';
import VueMessagePane from './MessagePane';
import EventBus, {EVENT_NAMES} from './MessengerEventBus';
const evbus = EventBus();
const api = axios.create({baseURL: '/api/messenger'});
export default {
    name: 'Messenger',
    data: function() {
        return {
            selectedUser: {
                id: 0,
                name: ''
            },
            unread: 0,
            isOpened: false,
            multiselect: false,
            checkedUsers: []
        };
    },
    components: {
        'v-user-list': VueMessengerUserList,
        'v-message-pane': VueMessagePane
    },
    computed: {
        openedClass: function() {
            return this.isOpened ? 'is-open' : '';
        }
    },
    methods: {
        openUp: function() {
            if (this.isOpened) {
                evbus.$emit(EVENT_NAMES.messengerSelectedUser, {id: 0, name: ''});
            }
            this.isOpened = !this.isOpened;
        },
        checkUnreadAny: function() {
            return api.get('/messages/unread')
                .then(response => {
                    this.unread = response.data.unread;
                });
        },
        checked: function() {
            this.multiselect = !this.multiselect;
            if (!this.multiselect) {
                evbus.$emit(EVENT_NAMES.messengerClearMultiselect);
            } else {
                evbus.$emit(EVENT_NAMES.messengerSelectedUser, {id: 0, name: ''});
            }
        },
        updateChecked: function(user, checked) {
            if (checked) {
                this.checkedUsers.push(user.id);
            } else {
                this.checkedUsers.splice(this.checkedUsers.indexOf(user.id), 1);
            }
        }

    },
    mounted: function() {
        evbus.$on(EVENT_NAMES.messengerSelectedUser, (user) => {
            this.selectedUser = user;
        });

        evbus.$on(EVENT_NAMES.messengerMultiselectUser, (user, checked) => {
            this.updateChecked(user, checked);
        });
        this.checkUnreadAny();
        setInterval(() => {
            this.checkUnreadAny();
        }, 3400);
    }
};
</script>

<style lang="scss">
    @import "../../../sass/variables";
    #emergency_messenger {
        text-shadow: 0 1px 0 #fff;
        z-index: 9999;
        position: fixed;
        bottom: 0;
        right: 20px;
        background-color: $white-ter;
        border: 1px solid $primary;
        border-bottom: none;
        box-shadow: 0px -2px 8px rgba(0,0,0,.3);
        border-top-left-radius: 4px;
        border-top-right-radius: 4px;
        .opener {
            .unread {
                background-color: $red;
                border-radius: 500px;
                width: 20px;
                height: 20px;
                line-height: 20px;
                text-shadow: 0 1px 0 rgba(0,0,0,.4);
                text-align: center;
                border: 1px solid $primary;
                box-shadow: 1px -1px 2px rgba(0,0,0,.4);
                position: absolute;
                margin-top: -10px;
                margin-right: -10px;
                color: $white;
            }
        }
        .opener > a {
            display: inline-block;
            color: $primary;
            margin: 5px;
            &:hover {
                color: darken($primary, 10%);
            }
        }
        .messenger {
            max-height: 0;
            &.is-open {
                height: 500px;
                max-height: 500px;
            }
            .header {
                background-color: $primary;
                display: flex;
                .header__closer {
                    display: flex;
                    width: 250px;
                    .multimode-label {
                        cursor: pointer;
                        flex-grow: 1;
                        text-shadow: none;
                        margin: 0 5px;
                        line-height: 1.8;
                        font-size: 14px;
                        span {
                            text-decoration: underline;
                            color: $white;
                        }
                        i.fa-check {
                            color: $green;
                        }
                        i.fa-circle-notch {
                            color: $red;
                        }
                    }
                    .closer {
                        flex-grow: 0;
                        padding: 3px 5px;
                        color: $primary-invert;

                        &:hover {
                            color: $white;
                        }
                    }
                }
                .header__title {
                    flex-grow: 1;
                    font-weight: bold;
                    color: $primary-invert;
                }
            }
            .messenger-body {
                display: flex;
                max-height: 100%;
                height: 100%;
            }
        }
    }
</style>
