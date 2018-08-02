<template>
    <div
        class="popup">
        <div class="shadowed-background">
            <div class="popup-window">
                <h5 class="subtitle">{{ model.id ? 'Редактирование' : 'Добавление' }}</h5>

                <hr>

                <form @submit.prevent="onSubmit()">
                    <div class="field">
                        <label for="address">Адрес</label>
                        <input
                            type="text"
                            class="input"
                            name="address"
                            id="address"
                            required
                            v-model="model['address']">
                    </div>
                    <div class="field">
                        <label for="specification">Спецификация</label>
                        <textarea
                            name="specification"
                            id="specification"
                            class="textarea"
                            cols="30"
                            rows="3"
                            required
                            v-model="model.specification"></textarea>
                    </div>
                    <div class="control is-expanded">
                        <p class="control">
                            <label>ПЧ</label>
                        </p>
                        <buefy-common-select
                            id="fire_department_id"
                            name="fire_department_id"
                            :options="fireDepartmentsOptions"
                            :min-length="0"
                            required
                            v-model="model['fire_department_id']"/>
                    </div>

                    <div class="field">
                        <label for="lat">Широта</label>
                        <input
                            type="number"
                            class="input"
                            name="lat"
                            id="lat"
                            disabled
                            required
                            v-model="model['lat']">
                    </div>
                    <div class="field">
                        <label for="long">Долгота</label>
                        <input
                            type="number"
                            class="input"
                            name="long"
                            id="long"
                            disabled
                            v-model="model['long']">
                    </div>

                    <hr>

                    <button
                        type="submit"
                        style="width: 100%"
                        class="button is-success">
                        <i class="fas fa-check"></i>Сохранить
                    </button>
                    <button
                        type="button"
                        style="width: 100%; margin: 5px 0 0 0;"
                        @click="$emit('onClose')"
                        class="button is-info">
                        Отмена
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import {BuefyCommonSelect} from '../../components';

export default {
    name: 'PopupForm',
    props: {
        item: {
            type: Object,
            default: () => {}
        }
    },
    data() {
        return {
            model: {},
            fireDepartments: []
        };
    },
    components: {
        BuefyCommonSelect
    },
    computed: {
        fireDepartmentsOptions() {
            return this.fireDepartments.map((item) => {
                return {
                    'id': item.id,
                    'text': item.title
                };
            });
        }
    },
    methods: {
        onSubmit() {
            this.$emit('onSave', this.model);
        }
    },
    watch: {
        'item'() {
            this.model = this.item;
        }
    },
    beforeMount() {
        this.model = this.item;
        if (window.hydrantListData) {
            this.fireDepartments = window.hydrantListData.fireDepartments;
        }
    }
};
</script>

<style scoped>
    .popup {
        display: block;
    }

    .shadowed-background {
        background: rgba(102, 102, 102, 0.5);
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
    }

    .popup-window {
        width: 300px;
        height: 635px;
        text-align: left;
        padding: 15px;
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        margin: 100px auto;
        background: #fff;
        border: 1px solid;
        border-top: 3px solid #456eba;
    }
</style>
