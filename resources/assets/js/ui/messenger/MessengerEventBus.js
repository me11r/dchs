import Vue from 'vue';
let instance;
export default function() {
    if (!instance) {
        instance = new Vue();
    }
    return instance;
}

export const EVENT_NAMES = {
    messengerSelectedUser: 'messenger-selected-user',
    messengerMultiselectUser: 'messenger-multi-user-check',
    messengerClearMultiselect: 'messenger-multi-clear-all',

    messageSending: 'messenger-sending-message',
    messageSent: 'messenger-message-sent'
};
