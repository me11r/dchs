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
            shownRetreat: false,
            retreatNotify: null,
            roadTrip: null,
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
                        let url = (this.plans[0].ticket !== null ? '/roadtrip/view/' : '/roadtrip/view-other/') + this.plans[0].id;
                        window.location.href = url;
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

                this.plans = response.data.plans;
                this.retreatNotify = response.data.retreatNotify;
                this.roadTrip = response.data.roadTrip;

                if (this.plans.length > 0) {
                    this.notify();
                    this.alertSound(true);
                } else {
                    this.alertSound(false);
                }

                if (this.retreatNotify !== null) {
                    this.notifyRetreat();
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
        notifyRetreat: function () {
            if (!this.shownRetreat) {
                this.shownRetreat = true;
                this.$snackbar.open({
                    message: `Отбой произведен, ${this.retreatNotify.department.title}: ${this.retreatNotify.tech.department}`,
                    indefinite: true,
                    type: 'is-warning',
                    position: 'is-top',
                    onAction: () => {
                        this.shownRetreat = false;
                        axios.post('/ajax/roadtrips-submit-notify-retreat101', {id: this.retreatNotify.id})
                            .then((r) => {
                                window.localStorage.setItem('DEPARTMENT_WAS_RETREATED', this.retreatNotify.id);
                                this.shown = false;
                                if (this.roadTrip) {
                                    let url = `/roadtrip/additional/` + this.roadTrip.id;
                                    window.location.href = url;
                                }
                            });
                    }
                });
            }
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
