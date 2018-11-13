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
            sendOne(i, part) {
                return new Promise((resolve) => {
                    if (part === '') {
                        // part = document.querySelector('[id="ph_' + i + '_ot"]').value;
                        part = document.querySelector('[id="ph_' + i + '_ot"]').value;
                    }

                    if (part !== '') {
                        axios.get('/roadtrip/send/' + i + '/' + window.ticket101add.ticketId, {
                            params: {
                                part: document.querySelector('[id="ph_' + i + '_ot"]').value
                            }
                        }).then((response) => {
                            // resolve(i);
                            window.location.href = '/card/add101/' + window.ticket101add.ticketId;
                        }).catch(() => {
                        });
                    } else {
                        resolve(i);
                    }
                });
            },
            sendOneCheck(event, dept_id, dept_number, res_id) {
                /* проставляем галочки в чекбосах */
                let object = document.getElementById(`dept_${res_id}`);
                let is_checked = object.checked;
                object.checked = !is_checked;

                axios.get('/roadtrip/send/' + dept_id + '/' + window.ticket101add.ticketId + '/' + dept_number).then((response) => {
                    alert(`Подразделение отправлено`);
                    event.target.disabled = true;
                    event.target.classList.add('is-danger');
                }).catch((e) => {
                    console.dir(e);
                });
            },

            selectToSend(event, id) {
                let recommended = event.target.checked;

                axios.post('/roadtrip/recommend', {
                    id: id, recommended: recommended
                }).then((response) => {

                });
            }

        }
    });
}
