<template>
    <div class="reply-pane">
        <div class="attach-file">
            <a
                class="upload"
                @click="triggerFileSelection"><i
                    class="fas fa-fw fa-2x"
                    :class="uploadingClass"></i></a>
            <input
                type="file"
                name="file"
                ref="messenger-file-upload"
                id="messenger-file-upload"
                @change="doUpload"
            >
        </div>
        <div class="text">
            <textarea
                :readonly="sending"
                v-model="message"
                name="message-text"
                class="reply-textarea"
                @keypress.enter.exact="sendMessage"
        ></textarea></div>
        <div class="send-message"><a
            @click.prevent="sendMessage"
            class="send">
            <i
                class="fas fa-fw fa-2x"
                :class="sendingClass"></i>
        </a></div>
    </div>
</template>

<script>
import axios from 'axios';
import EventBus, {EVENT_NAMES} from './MessengerEventBus';

const evbus = EventBus();
const api = axios.create({
    baseURL: '/api/messenger/'
});
const fileUploadApi = axios.create({
    baseURL: '/api/upload/'
});
export default {
    name: 'MessageReplyPane',
    props: {
        checkedUsers: {
            type: Array,
            default: () => { return []; }
        },
        multiselect: {
            type: Boolean,
            default: false
        }
    },
    data: function() {
        return {
            message: '',
            user: {
                id: 0,
                name: ''
            },
            uploading: false,
            sending: false
        };
    },
    computed: {
        sendingClass: function() {
            return this.sending ? 'fa-spin fa-circle-notch' : 'fa-comment-alt';
        },
        uploadingClass: function() {
            return this.uploading ? 'fa-spin fa-circle-notch' : 'fa-upload';
        }
    },
    methods: {
        triggerFileSelection: function() {
            if (!this.uploading) {
                const refname = 'messenger-file-upload';
                const upload = this.$refs[refname];
                upload.click();
            }
        },
        sendMessage: function() {
            if (!this.sending && (this.message !== '') && (this.user)) {
                this.send();
            }
        },
        doUpload: function(event) {
            this.uploading = true;
            evbus.$emit(EVENT_NAMES.messageSending);
            const fileinput = event.srcElement;
            const file = fileinput.files[0];
            let formData = new FormData();
            formData.append('file', file);
            fileUploadApi.post('file', formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }
            )
                .then(response => {
                    const fileId = response.data.id;
                    this.sendFile(fileId)
                        .then(response => {
                            this.uploading = false;
                        });
                }).catch(error => {
                    this.uploading = false;
                    throw error;
                });
        },
        send: function() {
            this.sending = true;
            const to = this.multiselect ? this.checkedUsers : [this.user.id];
            evbus.$emit(EVENT_NAMES.messageSending);
            return api.post('message/send', {message: this.message, to: to}).then(() => {
                evbus.$emit(EVENT_NAMES.messageSent, this.message, this.user);
                this.sending = false;
                this.message = '';
            });
        },
        sendFile: function(fileId) {
            const to = this.multiselect ? this.checkedUsers : [this.user.id];
            this.sending = true;
            return api.post('message/send', {message: '', type: 'file', file_id: fileId, to: to}).then(() => {
                evbus.$emit(EVENT_NAMES.messageSent, this.message, this.user);
                this.sending = false;
            });
        }
    },
    mounted: function() {
        evbus.$on(EVENT_NAMES.messengerSelectedUser, (user) => {
            this.user = user;
        });
    }
};
</script>

<style lang="scss">
    @import "../../../sass/variables";

    #messenger-file-upload {
        display: none;
    }
    .reply-pane {
        display: flex;
        min-height: 100px;
        height: 100px;
        box-sizing: border-box;

        .attach-file {
            flex-grow: 0;
            min-width: 100px;
            border-right: 1px solid cadetblue;
            box-sizing: border-box;
            display: flex;

            .upload {
                display: block;
                width: 100%;
                text-align: center;
                vertical-align: center;
                height: 100%;
                cursor: pointer;
                color: $grey-dark;

                &:hover {
                    background: linear-gradient(to bottom, #eeeeee 0%, #cccccc 100%);
                    color: $primary;
                }

                &:active {
                    background: linear-gradient(to bottom, #fff 0%, #ddd 100%);
                }

                .fas {
                    margin-top: 20%;
                }
            }
        }

        .text {
            flex-grow: 1;
            box-sizing: border-box;

            .reply-textarea {
                outline: none;
                max-width: 100%;
                min-width: 100%;
                font-size: 16px;
                padding: 3px 5px;
                min-height: 100%;
                line-height: 1.1;
                border: none;
                box-sizing: border-box;
                resize: none;
            }
        }

        .send-message {
            box-sizing: border-box;
            flex-grow: 0;
            max-width: 100px;
            width: 100%;
            border-left: 1px solid cadetblue;
            display: flex;

            .send {
                display: block;
                width: 100%;
                text-align: center;
                vertical-align: center;
                height: 100%;
                cursor: pointer;
                color: $grey-dark;

                &:hover {
                    background: linear-gradient(to bottom, #eeeeee 0%, #cccccc 100%);
                    color: $primary;
                }

                &:active {
                    background: linear-gradient(to bottom, #fff 0%, #ddd 100%);
                }

                .fas {
                    margin-top: 20%;
                }
            }

        }
    }
</style>
