<template>
    <div>
        <table class="table is-expanded is-striped is-narrow is-fullwidth">
            <thead>
            <tr>
                <th>Службы</th>
                <th>Время сообщения</th>
                <th>Фамилия<br/>принявшего сообщение</th>
                <th>Время прибытия</th>
                <th>Путевой лист отправлен</th>
                <th>Уведомление отправлено</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="notification in ticket_.service_plans"
                v-if="notification.service_type && notification.service_type.id">
                <td>
                    <label>
                        <input type="checkbox"
                               :name="`notification_services[${notification.id}][checked]`"
                               value="1"
                               @change="sendOneCheckService($event, ticket.id, notification.service_type.id, notification.id)"
                               class="checkbox">
                        {{ notification.service_type.name }}
                    </label>

                </td>
                <td>
                    <b-timepicker
                            class="small-time-picker"
                            icon="clock"
                            icon-pack="far"
                            id="call_time"
                            :name="`notification_services[${notification.id}][message_time]`"
                            :ref="'dispatched_time'+notification.id"
                            type="text"
                            v-model="notification.dispatched_time">
                        <div
                                class="field is-grouped"
                                style="justify-content: space-between">
                            <p class="control">
                                <a
                                        class="button is-primary is-small"
                                        @click="setCurrentTimeNow('dispatched_time'+notification.id, notification.id,'dispatched_time')">
                                    <b-icon
                                        pack="far"
                                        icon="clock"/>
                                    <span>Сейчас</span>
                                </a>
                            </p>
                            <p class="control">
                                <a
                                        class="button is-basic is-small"
                                        @click="closeTimePicker('dispatched_time'+notification.id)">
                                    <i class="fas fa-check"></i><span>Принять</span>
                                </a>
                            </p>
                        </div>
                    </b-timepicker>

                </td>
                <td>
                    <input type="text"
                           class="input"
                           v-model="notification.name_accepted"
                           :id="`${notification.id}_name`"
                           :name="`notification_services[${notification.id}][name]`"/>
                </td>
                <td>
                    <b-timepicker
                            class="small-time-picker"
                            icon="clock"
                            icon-pack="far"
                            id="arrive_time"
                            :name="`notification_services[${notification.id}][arrive_time]`"
                            :ref="'arrive_time'+notification.id"
                            type="text"
                            v-model="notification.arrive_time">
                        <div
                                class="field is-grouped"
                                style="justify-content: space-between">
                            <p class="control">
                                <a
                                        class="button is-primary is-small"
                                        @click="setCurrentTimeNow('arrive_time'+notification.id, notification.id,'arrive_time')">
                                    <b-icon
                                            pack="far"
                                            icon="clock"/>
                                    <span>Сейчас</span>
                                </a>
                            </p>
                            <p class="control">
                                <a
                                        class="button is-basic is-small"
                                        @click="closeTimePicker('arrive_time'+notification.id)">
                                    <i class="fas fa-check"></i><span>Принять</span>
                                </a>
                            </p>
                        </div>
                    </b-timepicker>
                </td>
                <td>
                    <p :id="`${notification.id}_dispatched_time`">
                        {{ notification.dispatched_time != null ? dateFormat(notification.dispatched_time) : '' }}
                    </p>
                </td>
                <td>
                    <p v-for="item in ticket_.notifications"
                       v-if="item.notification_service_id === notification.service_type.id"
                       :id="`${ item.id }_notification_dispatched_time`">
                        {{ item.checked ? item.created_at : '' }}
                    </p>
                </td>
            </tr>
            </tbody>
        </table>


    </div>
</template>

<script>
    import axios from 'axios';
    import _ from 'lodash';
    import moment from 'moment';
    import {globalBus} from '../../scripts/global-bus';
    export default {
        name: "NotificationServices",
        components: {
        },
        props: {
            ticket: {
                type: Object,
                default: () => { return {}; },
            },
            ticketType: {
                type: Number,
                default: 101,
            },
        },
        data: function () {
            return {
                ticket_: this.ticket,
            }
        },
        methods: {
            sendOneCheckService(event, cardId, service, notificationId) {
                if (event.target.checked) {
                    axios
                        .post('/service-plans/send', {
                            card_id: cardId,
                            service_id: service,
                            cardType: this.ticketType
                        })
                        .then((response) => {
                            document.getElementById(notificationId + '_dispatched_time').innerHTML = moment().format('DD.MM.Y HH:SS');
                        })
                        .catch(() => {
                        });

                    axios
                        .post('/api/notification/ticket101send', {
                            notification_id: notificationId,
                            cardType: this.ticketType,
                        })
                        .then((response) => {
                            let data = response.data;
                            if (data['success']) {
                                document.querySelector(`[id="${notificationId + '_message_time'}"]`).value = data['time'];
                                document.querySelector(`[id="${notificationId + '_name'}"]`).value = data['name'];
                                document.querySelector(`[id="${notificationId + '_notification_dispatched_time'}"]`).value = data['time'];
                            } else {
                                this.$snackbar.open({
                                    message: data['message'],
                                    type: 'is-danger',
                                    duration: 3000
                                });
                            }
                        })
                        .catch(() => {
                            this.$snackbar.open({
                                message: 'Произошла ошибка во время отправки уведомления для "' + service + '"',
                                type: 'is-danger',
                                duration: 3000
                            });
                        });
                }
            },
            computedDate(date) {
                let dt = new Date('01-01-1970 00:00');
                if (date !== '' && date !== null) {
                    let tm = date.split(':');
                    if (tm.length > 1) {
                        dt.setHours(tm[0]);
                        dt.setMinutes(tm[1]);
                    }
                }
                return dt;
            },
            setCurrentTimeNow(ref, id, field) {
                _.find(this.ticket_.service_plans, {id: id})[field] = moment().toDate();
                this.closeTimePicker(ref);
            },
            closeTimePicker(ref) {
                if (this.$refs[ref].close) {
                    this.$refs[ref].close();
                } else {
                    this.$refs[ref][0].close();
                }
            },
            dateFormat(date) {
                return moment(date).format('DD.MM.YYYY HH:mm');
            }
        },

        beforeMount() {
            if(this.ticket_.service_plans) {
                this.ticket_.service_plans.forEach((item) => {
                    item.dispatched_time = item.dispatched_time !== null ? moment(item.dispatched_time).toDate() : null;
                    item.arrive_time = moment(item.arrive_time).toDate();
                });
            }

        },
        mounted() {
            globalBus.$on('checkedRoadtrips', (roadtrips) => {

            });

            globalBus.$on('checkedServicePlans', (plans) => {
                plans.forEach((plan) => {
                    this.ticket_.service_plans.forEach((ticketPlan) => {
                        if(ticketPlan.id === plan.id) {
                            if (ticketPlan.dispatched_time == null && plan.dispatched_time !== null) {
                                ticketPlan.dispatched_time = moment(plan.dispatched_time).toDate();
                            }

                            if (ticketPlan.arrive_time == null && plan.arrive_time !== null) {
                                ticketPlan.arrive_time = moment(plan.arrive_time).toDate();
                            }

                            if (ticketPlan.name_accepted == null && plan.name_accepted !== null) {
                                ticketPlan.name_accepted = plan.name_accepted;
                            }
                        }
                    })
                })
            });
        }
    }
</script>

<style scoped>

</style>