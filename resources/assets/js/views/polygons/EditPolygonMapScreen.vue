<template>
    <div>
        <section class="hero is-primary is-fullheight">
            <div
                class="hero-body"
                style="align-items: unset;background-color:#fafafa;">
                <div class="container">
                    <div class="columns is-5-tablet is-4-desktop is-3-widescreen">
                        <div class="column">
                            <form
                                class="is-overlay"
                                @submit.prevent="onSubmit()">
                                <div class="field">
                                    <label class="label">Выберите микроучасток</label>
                                    <div class="control">
                                        <div class="select">
                                            <select
                                                v-model="currentItem"
                                                @input="onItemSelect">
                                                <option
                                                    :key="'item_' + item.id"
                                                    :value="item"
                                                    v-for="item in items">{{ item.title }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <template v-if="currentItem.id">
                                    <div class="field">
                                        <label class="label">Название</label>
                                        <div class="control">
                                            <input
                                                v-model="currentItem.title"
                                                required
                                                class="input"
                                                type="text">
                                        </div>
                                    </div>

                                    <div class="field">
                                        <label class="label">Цвет линии</label>
                                        <div class="control">
                                            <input
                                                v-model="currentItem.line_color"
                                                required
                                                class="input"
                                                type="text">
                                        </div>
                                    </div>

                                    <div class="field">
                                        <label class="label">Цвет заливки</label>
                                        <div class="control">
                                            <input
                                                v-model="currentItem.fill_color"
                                                required
                                                class="input"
                                                type="text">
                                        </div>
                                    </div>

                                    <div class="field">
                                        <label class="label">Прозрачность</label>
                                        <div class="control">
                                            <input
                                                v-model="currentItem.opacity"
                                                required
                                                class="input"
                                                type="text">
                                        </div>
                                    </div>

                                    <div class="field">
                                        <label class="label">Точки на карте</label>
                                        <div class="control">
                                            <textarea
                                                class="textarea"
                                                rows="10"
                                                required
                                                @keyup="checkRawPoints()"
                                                v-model="rawPointsText"></textarea>
                                        </div>
                                        <p
                                            class="help is-danger"
                                            v-if="rawPointsIncorrect">
                                            Некорректные данные. Правильный формат: [ [1,2] , [3, 4] ]
                                        </p>
                                    </div>

                                    <div class="field is-grouped">
                                        <div class="control">
                                            <button class="button is-link">Сохранить</button>
                                        </div>
                                    </div>
                                </template>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </section>
        <ymaps-polygon-editor
            @pointsChanged="onPointsChanged"
            :polygon-model="currentItem"/>
    </div>
</template>

<script>

import axios from 'axios';
import YmapsPolygonEditor from './YmapsPolygonEditor';

export default {
    name: 'EditPolygonMapScreen',
    components: {YmapsPolygonEditor},
    data: function() {
        return {
            items: [],
            emptyItem: {
                id: 0,
                title: '',
                line_color: '',
                fill_color: '',
                opacity: 0.5,
                points: []
            },
            currentItem: {},
            rawPointsText: '',
            rawPointsIncorrect: false
        };
    },
    methods: {
        checkRawPoints() {
            let points = null;
            let result = true;
            try {
                points = JSON.parse(this.rawPointsText);
                if (Array.isArray(points)) {
                    result = true;
                }
            } catch (e) {
                result = false;
            }

            if (result) {
                this.currentItem.points = points;
            }

            this.rawPointsIncorrect = !result;
        },
        onSubmit() {
            axios
                .post('/api/polygon/' + this.currentItem.id, {...this.currentItem, '_method': 'PATCH'})
                .then(() => {
                    this.fillList();
                    this.$snackbar.open({
                        message: 'Сохранено успешно',
                        position: 'is-top',
                        type: 'is-success',
                        duration: 3000
                    });
                });
        },
        onItemSelect() {
            this.$nextTick(() => {
                this.rawPointsText = JSON.stringify(this.currentItem.points);
            });
        },
        setEmptyItem() {
            this.currentItem = {...this.emptyItem};
        },
        fillList() {
            axios.get('/api/polygon').then((response) => {
                this.items = response['data'];
            });
        },
        onPointsChanged(points) {
            this.currentItem.points = points;
            this.rawPointsText = JSON.stringify(this.currentItem.points);
        }
    },
    mounted() {
        this.fillList();
    }
};
</script>
