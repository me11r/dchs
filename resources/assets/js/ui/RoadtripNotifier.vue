<template>

</template>

<script>
import axios from 'axios';
import AlarmSound from './GetAlarmSoundContext';

export default {
    name: 'RoadtripNotifier',
    data: function () {
        return {
            needChecking: true,
            checking: false,
            alarming: false,
            shown: false,
            shown103: false,
            shown102: false,
            plans: [],
            plans103: [],
            plans102: [],
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
                        window.location.href = '/roadtrip/view/' + this.plans[0].id;
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
        notify103: function () {
            if (!this.shown103) {
                this.shown103 = true;
                this.$snackbar.open({
                    message: 'Новый путевой лист 103!',
                    indefinite: true,
                    type: 'is-danger',
                    position: 'is-top',
                    onAction: () => {
                        this.shown103 = false;
                        window.location.href = '/roadtrip-103/' + this.plans103[0].id;
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
        notify102: function () {
            if (!this.shown102) {
                this.shown102 = true;
                this.$snackbar.open({
                    message: 'Новый путевой лист 102!',
                    indefinite: true,
                    type: 'is-danger',
                    position: 'is-top',
                    onAction: () => {
                        this.shown102 = false;
                        window.location.href = '/roadtrip-102/' + this.plans102[0].id;
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
            axios.get('/ajax/roadtrips').then(response => {
                this.plans = response.data;
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

            axios.get('/ajax/roadtrips-103').then(response => {
                this.plans103 = response.data;
                if (this.plans103.length > 0) {
                    this.notify103();
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

            axios.get('/ajax/roadtrips-102').then(response => {
                this.plans102 = response.data;
                if (this.plans102.length > 0) {
                    this.notify102();
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
            }).catch(error => {
                console.log(error, 'errored');
            });
        },
        configureTimer: function () {
            this.timer = setInterval(() => {
                if (!this.checking) {
                    this.check();
                }
            }, 5200);
        },
        startChecks: function() {
            if (this.needChecking) {
                this.loadSound().then(() => {
                    this.configureTimer();
                });
            }
        },
        delayLoad: function () {
            setTimeout(() => { this.startChecks(); }, 5000);
        }

    },
    mounted: function () {
        this.needChecking = window._global_ajax_timers.check_roadtrips;
        this.delayLoad();
    }
};
</script>

<style>
    #roadtrip-notifier {
        display: none;
    }
</style>
