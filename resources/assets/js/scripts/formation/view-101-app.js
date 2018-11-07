import Vue from 'vue';
import Persons101Multiselect from '../../components/formation/Persons101Multiselect';

export default class View101App {
    createApp(element, people, odStaff, formId) {
        this.app = new Vue({
            el: element,
            data: {
                people: people,
                odStaff: odStaff,
                selectedStaff: {},
                formId: formId
            },
            components: {
                Persons101Multiselect
            },
            methods: {
                setSelectedStaff() {
                    let selectedStaff = {};
                    if (this.people && this.people[19] && this.people[19]['formation_person_items_od']) {
                        this.people[19]['formation_person_items_od'].map((item) => {
                            if (!selectedStaff[item['rank']]) {
                                selectedStaff[item['rank']] = [];
                            }
                            if (item['staff']) {
                                selectedStaff[item['rank']].push(item['staff']);
                            }
                        });
                    }
                    this.selectedStaff = selectedStaff;
                }
            },
            beforeMount() {
                this.setSelectedStaff();
            }
        });
    }
}
