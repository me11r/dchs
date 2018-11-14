<template>
    <div
        class="message"
        :class="messageClass"><div class="message-text">{{ message.message }}</div>
        <div class="timer">
            <div class="top">
                <i class="far fa-clock fa-fw"></i>&nbsp;{{ message.created_at|dateFilter('DD.MM, HH:mm') }}
            </div>
            <div class="bottom">
                <div
                    class="viewed"
                    v-if="message.is_viewed">
                    <i class="far fa-check-circle fa-fw"></i>&nbsp;{{ message.updated_at|dateFilter('DD.MM, HH:mm') }}</div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'SingleMessage',
    props: {
        message: {
            type: Object,
            default: () => {
                return {
                    message: '',
                    sender_id: 0,
                    reciever_id: 0,
                    is_viewed: false
                };
            }
        },
        me: {
            type: Object,
            default: () => {
                return {
                    id: 0,
                    name: ''
                };
            }
        },
        user: {
            type: Object,
            default: () => {
                return {
                    id: 0,
                    name: ''
                };
            }
        }

    },
    data: function() {
        return {};
    },
    computed: {
        messageClass: function() {
            return (this.message.sender_id === this.user.id) ? 'is-contacts-message' : 'is-mine-message';
        }
    },
    methods: {},
    mounted: function() {

    }
};
</script>

<style lang="scss">
    @import "../../../sass/variables";
    .message {
        margin: 3px 5px;
        padding: 2px 5px;
        border: 1px solid #9BA2AB;
        border-radius: 12px;
        box-shadow: 2px 2px 2px rgba(0,0,0,.1);
        display: flex;
        &.is-mine-message {
            margin-right: 1rem;
            background-color: $white-ter;
        }
        &.is-contacts-message {
            margin-left:  1rem;
            background-color: lighten($green, 60%);
        }
        .message-text {
            flex-grow:1;
        }
        .timer {
            align-self: flex-end;
            flex-grow: 0;
            font-size: 11px;
            font-style: italic;
            color: transparentize($primary, .3);
            .viewed {
                color: transparentize($cyan, .3);
            }
        }

    }
</style>
