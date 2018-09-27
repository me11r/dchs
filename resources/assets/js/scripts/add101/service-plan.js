import axios from 'axios';
import Vue from 'vue';

export default function bindServicePlan() {
    const autoc = document.querySelectorAll('[data-component="service-plan"]');
    autoc.forEach((element) => {
        return new Vue({
            el: element,
            data: {
            },
            methods: {
                sendOneCheckService(event, cardId, service) {
                    axios.post('/service-plans/send', {
                        card_id: cardId,
                        service: service,
                    }).then((response) => {
                        // resolve(i);
                        // window.location.href = '/card/add101/' + window.ticket101add.ticketId;
                    }).catch(() => {
                    });
                }
            },
        });
    });
}
