import axios from 'axios';
import Vue from 'vue';

export default function bindServicePlan() {
    const autoc = document.querySelectorAll('[data-component="service-plan"]');
    autoc.forEach((element) => {
        return new Vue({
            el: element,
            data: {},
            methods: {
                sendOneCheckService(event, cardId, service, notificationId) {
                    if (event.target.checked) {
                        axios
                            .post('/service-plans/send', {
                                card_id: cardId,
                                service_id: service,
                                cardType: 101
                            })
                            .then((response) => {
                            })
                            .catch(() => {
                            });

                        axios
                            .post('/api/notification/ticket101send', {
                                notification_id: notificationId,
                                cardType: 101,
                            })
                            .then((response) => {
                                let data = response.data;
                                if (data['success']) {
                                    document.querySelector(`[id="${notificationId + '_message_time'}"]`).value = data['time'];
                                    document.querySelector(`[id="${notificationId + '_name'}"]`).value = data['name'];
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
                }
            }
        });
    });
}
