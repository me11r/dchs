<template>
    <div>
        <div class="columns">
            <div class="column">
                <span
                    class="name-span"
                    @click="activateTrigger()">{{ name }}:</span>
            </div>
            <div class="column">
                <template v-if="activated">
                    <div class="columns">
                        <button
                                class="button is-small is-basic"
                                type="button"
                                @click.prevent="addEmptyItem">
                            <i class="fa fa-plus"></i>&nbsp;Добавить
                        </button>
                    </div>
                    <div class="columns">
                        <div class="column">
                            <table class="table is-stripped">
                                <thead>
                                    <tr>
                                        <th>Ф.И.О.</th>
<!--                                        <th>Номер караула</th>-->
                                        <th>С</th>
                                        <th>По</th>
                                        <th>Комментарий</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(selectedPerson, selectedPersonKey) in selectedPersons"
                                        :key="`selected_person_${selectedPersonKey}`"
                                    >
                                        <td>
                                            <div class="select">
                                                <select v-model="selectedPerson.staff_id">
                                                    <option v-for="(person, personKey) in staff"
                                                            :key="`person_${personKey}`"
                                                            :value="person.staff_id"
                                                    >{{ person.person_name }}</option>
                                                </select>
                                            </div>
                                        </td>
<!--                                        <td>-->
<!--                                            <div class="select">-->
<!--                                                <select v-model="selectedPerson.person_guard_number_id">-->
<!--                                                    <option v-for="(guardNumber, guardKey) in guardNumbers"-->
<!--                                                            :key="`guard_num_${guardKey}`"-->
<!--                                                            :value="guardNumber"-->
<!--                                                    >{{ guardNumber }}</option>-->
<!--                                                </select>-->
<!--                                            </div>-->
<!--                                        </td>-->
                                        <td>
                                            <div class="field is-grouped">
                                                <input
                                                    v-model="selectedPerson.date_from"
                                                    class="control"
                                                    type="date"
                                                >
                                            </div>
                                        </td>
                                        <td>
                                            <div class="field is-grouped">
                                                <input
                                                    v-model="selectedPerson.date_to"
                                                    class="control"
                                                    type="date"
                                                >
                                            </div>
                                        </td>
                                        <td>
                                            <textarea v-model="selectedPerson.comment" class="textarea"></textarea>
                                        </td>
                                        <td>
                                            <button
                                                    class="button is-small is-danger square-button-36"
                                                    @click.prevent="removeItem(selectedPerson.id)"
                                                    type="button"
                                                    title="Удалить">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </template>
                <template v-else>
                    {{ selectedStaffString }}
                </template>
            </div>
        </div>
    </div>
</template>

<script>
    import moment from 'moment';

    export default {
        name: "PersonsOdSelect",
        props: {
            name: {
                type: String,
                default: '',
            },
            tableName: {
                type: String,
                required: true,
            },
            status: {
                type: String,
                default: '',
            },
            staff: {
                type: Array,
                default: () => [],
            },
        },
        computed: {
            selectedStaffString() {
                return this.selectedPersons.map(item => {
                    let result = item.person_name;
                    if (!result) {
                        let name = this.staff.find(names => names.staff_id === item.staff_id);
                        result = name ? name.person_name : '';
                    }
                    if (item.date_from) {
                        result += ` с ${item.date_from}`;
                    }
                    if (item.date_to) {
                        result += ` по ${item.date_to}`;
                    }
                    if (item.comment) {
                        result += ` ${item.comment}`;
                    }
                    return result;
                }).join(', ');
            },
            formattedStaffToSave() {
                let staff = JSON.parse(JSON.stringify(this.selectedPersons));
                staff = staff.map(item => {
                    item.id = item.staff_id;
                    return item;
                });
                return staff;
            }
        },
        data: function () {
            return {
                selectedPersons: [],
                guardNumbers: [1,2,3,4],
                activated: false
            };
        },
        methods: {
            removeItem(id) {
                this.selectedPersons = this.selectedPersons.filter(item => item.id !== id);
                this.saveItems();
            },
            addEmptyItem() {
                this.selectedPersons.push({
                    id: moment().valueOf(),
                    name: '',
                    comment: '',
                    date_from: '',
                    date_to: '',
                    formation_report_id: window.formId,
                    person_guard_number_id: '',
                    person_name: '',
                    staff_id: '',
                    status: this.status,
                    table_name: this.tableName,
                });
            },
            activateTrigger() {
                if (window.canEditOd === false || window.approved === true) {
                    return;
                }
                this.activated = !this.activated;
            },
            saveItems() {
                axios.post('/api/101/sync-formation-od-persons', {
                    formId: window.formId,
                    type: this.status,
                    selectedStaff: this.formattedStaffToSave,
                    tableName: this.tableName
                });
            }
        },
        watch: {
            'activated'(value) {
                if (!value) {
                    this.saveItems();
                }
            }
        },
        created() {
            this.selectedPersons = this.staff.filter(item => item.status === this.status);
        }
    }
</script>

<style scoped>
    .name-span {
        cursor: pointer;
        padding: 0 20px 0 0;
    }

    .name-span:hover {
        color: #15A4FA;
    }
</style>