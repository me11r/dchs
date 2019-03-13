<template>
    <div>
        <!--добавлено в ARM-513-->
        <b-notification v-for="item, key in notificationsParsed"
                        :key="`notification_${key}`"
                        :duration="10000"
                        auto-close
                        type="is-success"
                        :ref="`notification_${key}`"
                        :active.sync="item.is_viewed">
            <p>{{ item.message }}</p>

            <a @click.prevent="close(`notification_${key}`)" class="button is-info">Ok</a>
            <a :href="item.url" class="button is-info">Перейти</a>
        </b-notification>
    </div>

</template>

<script>
    import axios from 'axios';
    import _ from 'lodash';
    export default {
        name: "PopupNotifier",
        data: function () {
            return {
                open: true,
                notifications: []
            }
        },
        methods: {
            checkNotifications() {
                axios.get('/check-popup-notifications').then((r) => {
                    this.notifications = r.data.notifications;
                    //todo старый вариант попапа (изменено в ARM-513)
                    // let notifications = r.data.notifications;
                    // if(notifications.length){
                    //     for (let i = 0; i < notifications.length; i++){
                    //
                    //         this.$snackbar.open({
                    //             message: notifications[i].message,
                    //             position: notifications[i].popup_position ? notifications[i].popup_position : 'is-bottom-left',
                    //             type: notifications[i].popup_type ? notifications[i].popup_type : 'is-info',
                    //             duration: 10000,
                    //             actionText: 'Перейти',
                    //             onAction: () => {
                    //                 window.location.href = notifications[i].url;
                    //             }
                    //         });
                    //     }
                    // }

                }).catch((e) => {
                    console.dir(e.message);
                })

            },
            close(id) {
                if (this.$refs[id].close) {
                    this.$refs[id].close();
                } else {
                    this.$refs[id][0].close();
                }
            }
        },
        computed: {
            notificationsParsed() {
                return _.each(this.notifications, (item) => {
                    item.is_viewed = true;
                });
            }
        },
        mounted(){
            if(window._global_ajax_timers.popup_notifications === true){
                setInterval(() => {
                    this.checkNotifications();
                }, 30000);
            }
        }
    }
</script>

<style scoped>

</style>