<template>
    <b-timepicker v-model="computedDate"></b-timepicker>
</template>

<script>
    import Buefy from 'buefy';
    export default {
        name: "Timepicker",
        data() {
            return {
                time: this.inputdate,
                // time: new Date(),
                c_date: this.computedDate
            }
        },
        props: {
            inputdate: {
                type: String,
                default: '00:00'
            }
        },
        components: {
            'b-icon': Buefy['Icon'],
            'b-timepicker': Buefy['Timepicker']
        },
        computed: {
            computedDate() {
                const cdate = this.inputdate;
                let dt = new Date('01-01-1970 00:00');
                if (cdate !== '') {
                    const tm = cdate.split(':');
                    if (tm.length > 1) {
                        dt.setHours(tm[0]);
                        dt.setMinutes(tm[1]);
                    }

                }
                return dt;
            }
        }
    }
</script>

<style scoped>

</style>