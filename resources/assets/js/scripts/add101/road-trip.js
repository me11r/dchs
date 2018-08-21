import axios from 'axios';
import Vue from 'vue';

export default function bindRoadTrip() {
    const element = document.querySelector('[data-component="road-trip"]');
    return new Vue({
        el: element,
        data: {},
        methods: {
            sendAllTripPlans() {
                let count = 0;
                for (let i = 1; i < 18; i++) {
                    this.gerQuery(i, document.querySelector('[id="ph_' + i + '_ot"]').value).then((numb) => {
                        count += 1;
                        if (count === 17) {
                            window.location.href = '/card/add101/' + window.ticket101add.ticketId;
                        }
                    });
                }

            },
            gerQuery(i, part) {
                return new Promise((resolve) => {
                    if (part !== '') {
                        axios.get('/roadtrip/send/' + i + '/' + window.ticket101add.ticketId, {
                            params: {
                                part: document.querySelector('[id="ph_' + i + '_ot"]').value
                            }
                        }).then((response) => {
                            resolve(i);
                        }).catch(() => {
                        });
                    } else {
                        resolve(i);
                    }
                });
            }
        }
    });
}
