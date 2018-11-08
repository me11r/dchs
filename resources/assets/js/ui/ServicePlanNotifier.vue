<template>

</template>

<script>
import axios from 'axios';
import AlarmSound from './GetAlarmSoundContext';

export default {
    name: 'ServicePlanNotifier',
    data: function () {
        return {
            needChecking: false,
            checking: false,
            alarming: false,
            shown: false,
            service_id: 0,
            plans: []
        };
    },
    components: {},
    methods: {
        notify: function () {
            if (!this.shown) {
                this.shown = true;
                this.$snackbar.open({
                    message: 'Новый путевой лист! (' + this.plans.length + ')',
                    indefinite: true,
                    type: 'is-danger',
                    position: 'is-top',
                    onAction: () => {
                        this.shown = false;
                        window.location.href = `/service-plans/${this.service_id}/${this.plans[0].id}/show`;
                    }
                });
                const notify = window.Notification;
                if (notify !== undefined) {
                    if (notify.permission === 'granted') {
                        this.showSystemToast();
                    }
                    if (notify.permission !== 'denied') {
                        notify.requestPermission().then((permission) => {
                            if (permission === 'granted') {
                                this.showSystemToast();
                            }
                        });
                    }
                }
            }
        },
        showSystemToast: () => {
            // eslint-disable-next-line no-new
            new Notification('Новый путевой лист!!', {
                image: '/android-chrome-96x96.png'
            });
        },
        check: function () {
            this.checking = true;
            axios.get('/ajax/service-plans').then(response => {
                this.plans = response.data.plans;
                this.service_id = response.data.service_id;
                if (this.plans.length > 0) {
                    this.notify();
                    this.alertSound(true);
                } else {
                    this.alertSound(false);
                }
                this.checking = false;
            }).catch(reason => {
                this.$snackbar.open({
                    message: 'Произошла ошибка получения данных : ' + reason,
                    type: 'is-danger',
                    duration: 3000
                });
                this.checking = false;
            });
        },
        alertSound: function (play) {
            if (play && !this.alarming) {
                this.alarming = true;
                this.audio.start();
            }
            if (!play && this.alarming) {
                this.audio.stop();
            }
        },
        loadSound: function () {
            return AlarmSound().getContext().then((audio) => {
                this.audio = audio;
            });
        },
        configureTimer: function () {
            this.timer = setInterval(() => {
                if (!this.checking) {
                    this.check();
                }
            }, 5300);
        },
        startChecks: function() {
            if (this.needChecking) {
                this.loadSound().then(() => {
                    this.configureTimer();
                });
            }
        },
        delayLoad: function () {
            setTimeout(() => { this.startChecks(); }, 5500);
        }
    },
    mounted: function () {
        this.needChecking = window._global_ajax_timers.check_service_plans;
        this.delayLoad();
    }
};
</script>

<style>
    #service_plans_notifier {
        display: none;
    }
</style>
