<template>
    <div id="emergency_messenger">
        <div
            class="messenger"
            :class="openedClass"
            v-if="isOpened">
            <div class="header has-text-right"><a
                class="closer"
                @click.prevent="openUp"><i class="fas fa-times"></i></a>
            </div>
            <div class="messenger-body">
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
export default {
    name: 'Messenger',
    data: function() {
        return {
            isOpened: false
        };
    },
    components: {
        'v-user-list': VueMessengerUserList
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
    }
};
</script>

<style lang="scss">
    @import "../../../sass/variables";
    #emergency_messenger {
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
            transition: max-height 2s ease-in-out;
            max-height: 0;
            &.is-open {
                height: 500px;
                max-height: 500px;
            }
            .header {
                background-color: $primary;
                width: 250px;
                .closer {
                    padding: 3px 5px;
                    color: $primary-invert;
                    &:hover {
                        color: $white;
                    }
                }
            }
            .messenger-body {
                overflow-y: scroll;
                max-height: 100%;
                height: 100%;
            }
        }
    }
</style>
