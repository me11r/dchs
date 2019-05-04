<template>
    <div>
        <!--добавлено в ARM-513-->
        <b-notification v-for="item, key in notificationsParsed"
                        :key="`notification_${key}`"
                        :duration="10000"
                        :auto-close="item.auto_close"
                        type="is-success"
                        :ref="`notification_${key}`"
                        :active.sync="item.is_viewed">
            <p>{{ item.message }}</p>

            <div class="level">
                <div class="level-left">
                    <div class="level-item">
                        <a v-if="item.auto_close" @click.prevent="close(`notification_${key}`)" class="button is-info">Ok</a>
                    </div>
                    <div class="level-item">
                        <a :href="item.url" class="button is-info">Перейти</a>
                    </div>
                </div>
                <div v-if="!item.auto_close" class="level-right">
                    <a @click.prevent="close(`notification_${key}`, item.id)" class="button is-danger">Удалить уведомление (в случае ошибки)</a>
                </div>
            </div>

        </b-notification>
    </div>

</template>

<script>
    import axios from 'axios';
    import _ from 'lodash';
    import NotifySound from './GetNotifySoundContext';
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
                axios.get('/popup-notifications/check').then((r) => {

                    let notifications = r.data.notifications;

                    if (notifications.length > 0) {

                        _.each(notifications, (item) => {
                            let exists = _.find(this.notifications, {id: item.id});

                            if (!exists) {
                                this.notifications.push(item);
                                this.loadAndPlaySound();
                            }
                        });
                    }

                }).catch((e) => {
                    console.dir(e.message);
                })

            },
            close(id, notificationId) {

                if (notificationId !== undefined) {
                    axios.post('/popup-notifications/mark-viewed', {id: notificationId});
                }

                if (this.$refs[id].close) {
                    this.$refs[id].close();
                } else {
                    this.$refs[id][0].close();
                }

            },
            loadAndPlaySound: function () {
                return NotifySound().getContext().then((audio) => {
                    audio.start();
                }).catch(error => {
                    console.log(error, 'errored');
                });
            },
        },
        computed: {
            notificationsParsed() {
                return _.each(this.notifications, (item) => {
                    item.is_viewed = true;
                    item.auto_close = item.is_permanent === 1 ? false : true;
                });
            }
        },
        mounted(){
            if(window._global_ajax_timers.popup_notifications === true){
                setInterval(() => {
                    this.checkNotifications();
                }, 3000);
            }
        }
    }
</script>

<style scoped>

</style>