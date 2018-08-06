import axios from 'axios';
import Vue from 'vue';
import Buefy from 'buefy';
import {_} from 'vue-underscore';

export default function bindAutocomplete() {
    const autoc = document.querySelectorAll('[data-component="autocomplete"]');
    autoc.forEach((element) => {
        const sname = element.getAttribute('data-name');
        const shid = element.getAttribute('data-hid');
        return new Vue({
            el: element,
            data: {
                data: [],
                isFetching: false,
                selected: null,
                name: sname,
                hid: shid
            },
            components: {
                'b-icon': Buefy['Icon'],
                'b-timepicker': Buefy['Timepicker'],
                'b-autocomplete': Buefy['Autocomplete']
            },
            methods: {
                onSelect: function (selected) {
                    this.hid = selected.id;
                    this.name = selected.name;
                },
                getData: _.debounce(function () {
                    const area = document.getElementById('city_area');
                    const areaId = area[area.selectedIndex].value;
                    this.data = [];
                    this.isFetching = true;
                    axios.get('/ajax/street/' + areaId + '/?q=' + this.name)
                        .then((response) => {
                            const data = response.data;
                            data.forEach((item) => {
                                this.data.push(item);
                            });
                            this.isFetching = false;
                        })
                        .catch(() => {
                            this.isFetching = false;
                        });
                }, 300)
            },
            mounted() {
                this.name = element.dataset.name;
            }
        });
    });
}
