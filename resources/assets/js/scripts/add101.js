import Vue from 'vue';
import Buefy from 'buefy';
import axios from 'axios';
import {_} from 'vue-underscore';

export default class Add101Functions {
    constructor() {
        this.globalBus = new Vue({});
        this.activeTab = 0;
    }

    setTab(i) {
        let tabs = document.querySelectorAll('#cardadd101 .tabs li');
        let panels = document.querySelectorAll('#cardadd101 .panels > div');
        tabs[this.activeTab].classList.remove('is-active');
        panels[this.activeTab].classList.add('is-hidden');
        tabs[i].classList.add('is-active');
        panels[i].classList.remove('is-hidden');
        this.activeTab = i;
    }

    nextTab(e) {
        e.preventDefault();
        let nextTab = this.activeTab + 1;
        if (nextTab > 4) {
            nextTab = 0;
        }
        this.setTab(nextTab);
    }

    bindTimepickers() {
        const _this = this;
        const pickers = document.querySelectorAll('[data-component="timepicker"]');
        pickers.forEach((element, index) => {
            const cdate = element.getAttribute('data-value');
            let dt = new Date('01-01-1970 00:00');
            if (cdate !== '') {
                const tm = cdate.split(':');
                if (tm.length > 1) {
                    dt.setHours(tm[0]);
                    dt.setMinutes(tm[1]);
                }
            }
            return new Vue({
                el: element,
                data: function () {
                    return {
                        value: dt
                    };
                },
                components: {
                    'b-icon': Buefy['Icon'],
                    'b-timepicker': Buefy['Timepicker']
                },
                methods: {
                    close: function () {
                        if (this.$children[0]) {
                            this.$children[0].close();
                        }
                    },
                    closeAll() {
                        _this.globalBus.$emit('closeAll', this);
                    }
                },
                mounted: function (item) {
                    _this.globalBus.$on('closeAll', (element) => {
                        if (this.$el !== element) {
                            this.close();
                        }
                    });
                }
            });
        });
    }

    bindAutocomplete() {
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
                            }
                            )
                            .catch(() => {
                                this.isFetching = false;
                            });
                    }, 300)
                }
            });
        });
    }
}
