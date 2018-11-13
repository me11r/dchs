<template>
    <div id="emergency_messenger"
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
            <a
                @click.prevent="openUp">
                <i class="fas fa-comments fa-2x fa-fw"></i>
            </a>
        </div>
    </div>
</template>

<script>
import VueMessengerUserList from './UsersList';
import VueMessagePane from './MessagePane';
import EventBus from './MessengerEventBus';
const evbus = EventBus();
export default {
    name: 'Messenger',
    data: function() {
        return {
            selectedUser: {
                id: 0,
                name: ''
            },
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
            this.isOpened = !this.isOpened;
        }
    },
    mounted: function() {
        evbus.$on('messenger-selected-user', (user) => {
            console.log(user);
            this.selectedUser = user;
        });
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
