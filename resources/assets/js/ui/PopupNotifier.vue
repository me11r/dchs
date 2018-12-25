<template>
    
</template>

<script>
    import axios from 'axios';
    export default {
        name: "PopupNotifier",
        data: function () {
            return {
            }
        },
        methods: {
            checkNotifications() {
                axios.get('/check-popup-notifications').then((r) => {
                    let notifications = r.data.notifications;
                    if(notifications.length){
                        for (let i = 0; i < notifications.length; i++){
                            this.$snackbar.open({
                                message: notifications[i].message,
                                position: notifications[i].popup_position ? notifications[i].popup_position : 'is-bottom-left',
                                type: notifications[i].popup_type ? notifications[i].popup_type : 'is-info',
                                duration: 10000,
                            });
                        }
                    }

                }).catch((e) => {
                    console.dir(e.message);
                })

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