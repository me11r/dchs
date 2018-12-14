<template>
    <b-timepicker
        :name="name_"
        @change="changeTime"
        v-model="c_date"
        icon="clock"
        icon-pack="far"
        type="text"
        editable
        :readonly="readOnly">
        <div class="field is-grouped" style="justify-content: space-between">
            <p class="control">
                <a class="button is-primary is-small"
                   @click="()=> {c_date = new Date();}">
                    <b-icon pack="far" icon="clock"></b-icon>&nbsp;
                    <span>Сейчас</span>
                </a>
            </p>
        </div>
    </b-timepicker>
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
            type: String | Date,
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
    watch: {
        'c_date'(){
            this.$emit('timeChanged', this.c_date);
        }
    },
    methods: {
        changeTime() {
            this.$emit('timeChanged', this.c_date);
            console.dir(this.c_date);
        }
    }
};
</script>

<style scoped>

</style>
