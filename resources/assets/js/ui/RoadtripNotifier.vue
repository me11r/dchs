<template>

</template>

<script>
import axios from 'axios';

export default {
    name: 'RoadtripNotifier',
    data: function () {
        return {
            checking: false,
            alarming: false,
            shown: false,
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
                    type: 'is-success',
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
        },
        alertSound: function (play) {
            if (play && !this.alarming) {
                this.alarming = true;
                this.audio.start();
            }
            if (!play && this.alarming) {
                this.audio.stop();
            }
        }
    },
    mounted: function () {
        this.audioctx = new AudioContext();
        axios.get(
            '/assets/alarm.wav',
            {responseType: 'arraybuffer'}
        ).then((response) => {
            this.audioctx.decodeAudioData(response.data, (buffer) => {
                this.audio = this.audioctx.createBufferSource();
                this.audio.buffer = buffer;
                this.audio.loop = true;
                this.audio.connect(this.audioctx.destination);
            });
        });
        this.timer = setInterval(() => {
            if (!this.checking) {
                this.check();
            }
        }, 5000);
    }
};
</script>

<style>
    #roadtrip-notifier {
        display: none;
    }
</style>
