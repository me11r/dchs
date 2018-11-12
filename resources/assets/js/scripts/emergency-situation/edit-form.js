import moment from 'moment';
import Vue from '../../VueInstance';

if (document.getElementById('emergency-situation-form')) {
    new Vue({
        el: '#emergency-situation-form',
        data() {
            return {
                time: new Date(),
                date: new Date()
            };
        },
        computed: {
            timeToSave() {
                return moment(this.time).format('YYYY-MM-DD HH:mm:ss')
            },
            dateToSave() {
                return moment(this.date).format('YYYY-MM-DD HH:mm:ss')
            }
        },
        methods: {
            closeTimePicker () {
                if (this.$refs['timepicker']) {
                    this.$refs['timepicker'].close();
                }
            }
        },
        beforeMount() {
            this.time = moment(document.getElementById('emergency-situation-timepicker').dataset.value).toDate();
            this.date = moment(document.getElementById('emergency-situation-datepicker').dataset.value).toDate();
        }
    });
}
