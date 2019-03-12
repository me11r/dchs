<template>
    <div>
        <a
            @click.prevent="dispatchDept()"
            v-if="!is_dispatched_"
            class="button is-info"
            href=""><i class="far fa-bell"></i>&nbsp;Назначить: отделение -  {{ dep_.tech.department ? dep_.tech.department : dep_.promoted_department }}</a>
        <a
                v-else
                class="button is-warning"
                :href="`/roadtrip/additional/${trip.id}`"><i class="fas fa-retweet"></i>&nbsp;Дополнительно </a>

        <!--<a-->
            <!--@click.prevent="markDeptArrived()"-->
            <!--v-else-if="is_dispatched_== 1 && is_arrived_ === false"-->
            <!--class="button is-warning is-outlined"-->
            <!--href=""><i class="fas fa-retweet"></i>&nbsp;Отметить прибытие: отделение -  {{ dep_.tech.department ? dep_.tech.department : dep_.promoted_department }}</a>-->
        <!--<a-->
            <!--@click.prevent="markDeptReturn()"-->
            <!--v-else-if="is_arrived_ && dep.ret_time == null"-->
            <!--class="button is-success is-outlined"-->
            <!--href=""><i class="fas fa-retweet"></i>&nbsp;Отметить возвращение: отделение -  {{ dep_.tech.department ? dep_.tech.department : dep_.promoted_department }}</a>-->
        <!--<a-->
            <!--@click.prevent=""-->
            <!--v-else-if="is_returned_"-->
            <!--class="button is-disabled"-->
            <!--href=""><i class="fas fa-retweet"></i>&nbsp;Отделение вернулось: {{ dep_.tech.department ? dep_.tech.department : dep_.promoted_department }}</a>-->
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'RoadtripDeptBtn',
    props: {
        dep: {
            default: function () {
                return {};
            },
            type: Object
        },
        trip: {
            default: function () {
                return {};
            },
            type: Object
        }
    },
    data: function () {
        return {
            dep_: this.dep,
            is_dispatched_: this.dep.out_time != null,
            is_returned_: this.dep.ret_time != null,
            is_arrived_: (this.dep.arrive_time !== null),
            roadtrip_id_: this.trip.ret_time
        };
    },
    methods: {
        dispatchDept() {
            let self = this;
            axios.post('/roadtrip/dispatch', {
                dept_id: self.dep_.id
            }).then((resp) => {
                self.is_dispatched_ = 1;
            });
        },
        markDeptArrived() {
            let self = this;
            axios.post('/roadtrip/arrived', {
                dept_id: self.dep_.id
            }).then((resp) => {
                self.is_arrived_ = true;
            });
        },
        markDeptReturn() {
            let self = this;
            axios.post('/roadtrip/return', {
                dept_id: self.dep_.id
            }).then((resp) => {
                self.is_returned_ = true;
                self.dep.ret_time = 1;
            });
        }
    },
    created() {
        window.addEventListener('storage', (event) => {
            if (event.key === 'DEPARTMENT_WAS_RETREATED') {
                let data = event.newValue;
                if (data === this.dep_.id) {
                    this.is_arrived_ = true;
                    this.dep.ret_time = new Date;
                }
            }
        });
    }
};
</script>

<style scoped>

</style>
