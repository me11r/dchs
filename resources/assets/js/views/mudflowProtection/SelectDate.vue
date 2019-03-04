<template>
    <div>
        <v-datepicker-search
                v-model="date_"
                :date-string="date_"
                name="date"
                @dateChanged="date_ = $event"
                class="control"
                label="Дата">
        </v-datepicker-search>
    </div>
</template>

<script>
    import moment from 'moment';
    export default {
        name: "SelectDate",
        props: {
            date: {
                type: String,
                default: '',
            },
        },
        data: function () {
            return {
                date_:this.date,
            };
        },
        watch: {
            'date_'() {
                window.location.href = `/mudflow-protection/${this.dateFormatted}`;
            }
        },
        computed: {
            dateFormatted() {
                return moment(this.date_).format('YYYY-MM-DD');
            }
        }
    }
</script>

<style scoped>

</style>