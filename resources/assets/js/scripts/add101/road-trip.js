import axios from 'axios';
import Vue from '../../VueInstance';

export default function bindRoadTrip() {
    const element = document.querySelector('[data-component="road-trip"]');
    return new Vue({
        el: element,
        data: {
            plans_sent: false
        },
        mounted() {
            let token = document.head.querySelector('meta[name="csrf-token"]');
            axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
            axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content || '';
        },
        methods: {
            sendAllTripPlans() {
                axios.get('/roadtrip/send-all/' + window.ticket101add.ticketId).then((response) => {
                    alert('Силы отправлены');
                }).catch(() => {
                });
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
            },
        }
    });
}
