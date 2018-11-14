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
                <div class="header__closer has-text-right"><a
                    class="closer"
                    @click.prevent="openUp"><i class="fas fa-times"></i></a>
                </div>
            </div>
            <div class="messenger-body">
                <v-message-pane/>
                <v-user-list/>
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
import EventBus from './MessengerEventBus';
const evbus = EventBus();
const api = axios.create({ baseURL: '/api/messenger'});
export default {
    name: 'Messenger',
    data: function() {
        return {
            selectedUser: {
                id: 0,
                name: ''
            },
            unread: 0,
            isOpened: false
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
                evbus.$emit('messenger-selected-user', {id: 0, name: ''});
            }
            this.isOpened = !this.isOpened;
        },
        checkUnreadAny: function() {
            return api.get('/messages/unread')
                .then(response => {
                    this.unread = response.data.unread;
                });
        }
    },
    mounted: function() {
        evbus.$on('messenger-selected-user', (user) => {
            this.selectedUser = user;
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
                    width: 250px;
                    .closer {
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
