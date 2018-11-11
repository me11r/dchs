<template>
    <div
        class="message"
        :class="messageClass"><div class="message-text">{{ message.message }}</div>
        <div class="timer"><i class="far fa-clock fa-fw"></i>&nbsp;{{ message.created_at|dateFilter('HH:mm') }}</div>
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
                    reciever_id: 0
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
            return (this.message.reciever_id === this.user.id) ? 'is-contacts-message' : 'is-mine-message';
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
            margin-right: 2rem;
            background-color: $white-ter;
        }
        &.is-contacts-message {
            margin-left: 2rem;
            background-color: lighten($green, 90%);
        }
        .message-text {
            flex-grow:1;
        }
        .message-timer {
            flex-grow: 0;
        }

    }
</style>
