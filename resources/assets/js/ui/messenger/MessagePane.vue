<template>
    <div
        class="message-pane"
        :class="activeClass">
        {{ user.id }}
        fsdgsdgsgsdgsgsgs
        {{ user.name }}
    </div>
</template>

<script>
import EventBus from './MessengerEventBus';
const evbus = EventBus();
export default {
    name: 'MessagePane',
    data: function() {
        return {
            me: {},
            user: {},
            messages: [],
            selected: false
        };
    },
    computed: {
        activeClass: function() {
            return this.selected ? 'is-active' : '';
        }
    },
    mounted: function() {
        evbus.$on('messenger-selected-user', (user) => {
            this.user = user;
            this.selected = true;
        });
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
            border-right: 1px solid $primary;
        }
    }
</style>
