import axios from 'axios';
import {_} from 'vue-underscore';
import Vue from '../../VueInstance';

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
            methods: {
                onSelect: function (selected) {
                    this.hid = selected.id;
                    this.name = selected.name;
                },
                getData: _.debounce(function () {
                    const area = document.getElementById('city_area');
                    let areaId = 0;
                    if (area[area.selectedIndex] !== undefined) {
                        areaId = area[area.selectedIndex].value;
                    }
                    this.data = [];
                    this.isFetching = true;
                    axios.get('/ajax/street/' + areaId + '/?q=' + this.name)
                        .then((response) => {
                            const data = response.data;

                            data.streets.forEach((item) => {
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
