<template>
    <div>
        <h3 class="title">Дополнительные адреса (ОК/ОП)</h3>
        <div class="section" v-for="record in records_" :key="record.id">

            <b-field label="Наименование объекта">
                <input type="text" :key="`object_name_${record.id}`" :value="selectedObject[record.id]" disabled class="input">
            </b-field>

            <b-field label="Адрес">
                <input type="text" :key="`location_${record.id}`" :value="selectedLocation[record.id]" disabled class="input">
            </b-field>

            <input type="hidden" name="additional_special_plans[]" :value="record.special_plan ? record.special_plan.id : null">
            <input type="hidden" name="additional_operational_cards[]" :value="record.operational_card ? record.operational_card.id : null">

            <div class="columns">

                <div class="column">
                    <b-field label="ОП" v-if="record.special_plan">
                        <b-autocomplete
                                :data="operational_plans[record.id]"
                                placeholder="введите номер плана"
                                field="operational_plan.name"
                                :keep-first="true"
                                v-model="record.special_plan.operational_plan.name"
                                @input="getSpecialPlans($event, record.id)"
                                @select="selectOp($event, record)">
                            <template slot-scope="props">
                                <div class="media">
                                    <div class="media-content">
                                        {{ props.option.operational_plan.name }}
                                        <br>
                                        <small><b>{{ props.option.object_name }}</b></small>
                                        <br>
                                        <small><i>{{ props.option.location }}</i></small>
                                    </div>
                                </div>
                            </template>
                        </b-autocomplete>
                    </b-field>
                </div>

                <div class="column">
                    <b-field label="ОК" v-if="record.operational_card">
                        <b-autocomplete
                                :data="operational_cards[record.id]"
                                placeholder="введите адрес или номер карточки"
                                field="oc_number"
                                v-model="record.operational_card.oc_number"
                                @input="getOperationalCards($event, record.id)"
                                @select="selectOc($event, record)">
                            <template slot-scope="props">
                                <div class="media">
                                    <div class="media-content">
                                        {{ props.option.oc_number }}
                                        <br>
                                        <small><b>{{ props.option.object_name }}</b></small>
                                        <br>
                                        <small><i>{{ props.option.location }}</i></small>
                                    </div>
                                </div>
                            </template>
                        </b-autocomplete>
                    </b-field>
                </div>
            </div>

            <div class="section">
                <a @click.prevent="deleteRecord(record)" class="button is-danger">-</a>
            </div>
        </div>
        <div class="section">
            <b-field label="Добавить ОК/ОП">
                <a @click.prevent="addRecord()" class="button is-success">+</a>
            </b-field>
        </div>
    </div>

</template>

<script>
    export default {
        name: "AdditionalOC",
        props: {
            records: {
                type: Array,
                default: () => []
            },
            selectedOC_id: {
                type: Number,
                default: null,
            },
            selectedOP_id: {
                type: Number,
                default: null,
            },
        },
        data: function() {
            return {
                objectName: [],
                objectAddress: [],
                selectedOC: [],
                selectedOP: [],
                selectedObject: [],
                selectedLocation: [],
                records_: this.records,
                isFetching: false,
                operational_cards: [], /*оперкарты*/
                operational_plans: [], /*оперпланы*/
            };
        },
        methods: {
            addRecord() {
                this.records_.push({
                    id: window.moment().valueOf(),
                    special_plan_id: null,
                    special_plan: {
                        id: null,
                        location: '',
                        object_name: '',
                        operational_plan: {
                            id: null,
                            name: '',
                        },
                    },
                    operational_card_id: null,
                    operational_card: {
                        id: null,
                        location: '',
                        oc_number: '',
                        object_name: '',
                    },
                });
            },
            deleteRecord(record) {
                this.records_ = this.records_.filter(item => item.id !== record.id);
            },
            selectOp(value, record) {
                if (value) {
                    record.special_plan = value;
                    record.operational_card.id = null;
                    this.selectedOP[record.id] = value;
                    this.selectedLocation[record.id] = value.location;
                    this.selectedObject[record.id] = value.object_name;
                }
            },
            selectOc(value, record) {
                if (value) {
                    record.operational_card = value;
                    record.special_plan.id = null;
                    this.selectedOC[record.id] = value;
                    this.selectedLocation[record.id] = value.location;
                    this.selectedObject[record.id] = value.object_name;
                }
            },
            getSelected(id, key) {
                if (this.selectedOP[id]) {
                    return this.selectedOP[id][key];
                }
                else if (this.selectedOC[id]) {
                    return this.selectedOC[id][key];
                }
                return null;
            },
            getSpecialPlans: window._.debounce(function (name, recordId) {

                if (!name.length) {
                    this.operational_plans[recordId] = [];
                    return;
                }

                this.selectedOC[recordId] = null;
                this.selectedOP[recordId] = null;
                this.isFetching = true;

                window.axios.post(`/api/card101/special-plans`, {name: name})
                    .then(({ data }) => {
                        this.operational_plans[recordId] = [];
                        data.special_plans.forEach((item) => this.operational_plans[recordId].push(item));
                    })
                    .catch((error) => {
                        this.operational_plans[recordId] = [];

                        throw error;
                    })
                    .finally(() => {
                        this.isFetching = false;
                    })
            }, 500),
            getOperationalCards: window._.debounce(function (name, recordId) {

                if (!name.length) {
                    this.operational_cards[recordId] = [];
                    return;
                }

                this.isFetching = true;
                this.selectedOP[recordId] = null;
                this.selectedOC[recordId] = null;

                window.axios.post(`/api/card101/operational-cards`, {name: name})
                    .then(({ data }) => {
                        this.operational_cards[recordId] = [];
                        data.operational_cards.forEach((item) => this.operational_cards[recordId].push(item));
                    })
                    .catch((error) => {
                        this.operational_cards[recordId] = [];

                        throw error;
                    })
                    .finally(() => {
                        this.isFetching = false;
                    });
            }, 500)
        },
        computed: {
        },
        created() {
            this.records_.forEach((record) => {
               this.selectedObject[record.id] = record.special_plan ? record.special_plan.object_name : record.operational_card.object_name;
               this.selectedLocation[record.id] = record.special_plan ? record.special_plan.location : record.operational_card.location;
            });
            console.dir(this.selectedLocation);
        }
    }
</script>

<style scoped>

</style>