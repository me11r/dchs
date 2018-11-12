import {globalBus} from '../global-bus';
import Vue from '../../VueInstance';

export default function bindTimepickers() {
    const pickers = document.querySelectorAll('[data-component="timepicker"]');
    pickers.forEach((element, index) => {
        const cdate = element.getAttribute('data-value');
        let dt = new Date('01-01-1970 00:00');
        if (cdate !== '') {
            const tm = cdate.split(':');
            if (tm.length > 1) {
                dt.setHours(tm[0]);
                dt.setMinutes(tm[1]);
            }
        }
        return new Vue({
            el: element,
            data: function () {
                return {
                    value: dt
                };
            },
            methods: {
                close: function () {
                    if (this.$children[0]) {
                        this.$children[0].close();
                    }
                },
                closeAll() {
                    globalBus.$emit('closeAll', this);
                }
            },
            mounted: function (item) {
                globalBus.$on('closeAll', (element) => {
                    if (this.$el !== element) {
                        this.close();
                    }
                });
            }
        });
    });
}
