<template>
    <b-timepicker
        @keyup="test"
        :name="name_"
        v-model="computedDate"
        :readonly="readOnly"></b-timepicker>
</template>

<script>
export default {
    name: 'Timepicker',
    data() {
        return {
            time: this.inputdate,
            name_: this.name,
            c_date: this.computedDate,
            readOnly: this.isReadOnly
        };
    },
    props: {
        inputdate: {
            type: String,
            default: '00:00'
        },
        name: {
            type: String,
            default: '00:00'
        },
        isReadOnly: {
            type: Boolean,
            default: true
        }
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
    },
    methods: {
        test() {
            console.dir(123);
        }
    }
};
</script>

<style scoped>

</style>
