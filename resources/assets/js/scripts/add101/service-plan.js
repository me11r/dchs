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
                    // let date = new Date();
                    // let dateFormatted = date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate() + ' ' + date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds();
                    // console.dir(dateFormatted);

                    axios.post('/service-plans/send', {
                        card_id: cardId,
                        service: service
                    }).then((response) => {
                        let data = response.data;
                        let id = service + '_created_at';

                        document.querySelector(`[id="${id}"]`).value = data.created_at;

                        }).catch(() => {
                    });
                }
            },
        });
    });
}
