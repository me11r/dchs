<template>
    <div>
        <a @click.prevent="dispatchDept()" v-if="is_dispatched_== 0" class="button is-info" href=""><i class="far fa-bell"></i>&nbsp;Назначить: отделение -  {{ dep_.departments }}</a>
        <a @click.prevent="markDeptReturn()" v-if="is_dispatched_== 1 && dep.ret_time == null" class="button is-success is-outlined" href=""><i class="fas fa-retweet"></i>&nbsp;Отметить возвращение: отделение -  {{ dep_.departments }}</a>
        <a @click.prevent="" v-if="is_returned_" class="button is-disabled" href=""><i class="fas fa-retweet"></i>&nbsp;Отделение вернулось: {{ dep_.departments }}</a>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        name: "RoadtripDeptBtn",
        props: {
            dep: {
                default: function () {
                    return {};
                },
                type: Object,
            },
            trip: {
                default: function () {
                    return {};
                },
                type: Object,
            },
        },
        data: function () {
            return {
                dep_: this.dep,
                is_dispatched_: this.dep.dispatched,
                is_returned_: this.dep.ret_time == null ? false : true,
                roadtrip_id_: this.trip.ret_time,
            }
        },
        methods: {
            dispatchDept() {
                let self = this;
                axios.post('/roadtrip/dispatch', {
                    dept_id: self.dep_.id,
                    // trip_id: self.roadtrip_id_,
                }).then((resp) => {
                    self.is_dispatched_ = 1;
                });
            },
            markDeptReturn() {
                let self = this;
                axios.post('/roadtrip/return', {
                    dept_id: self.dep_.id,
                }).then((resp) => {
                    self.is_returned_ = true;
                    self.dep.ret_time = 1;
                });
            }
        },
    }
</script>

<style scoped>

</style>