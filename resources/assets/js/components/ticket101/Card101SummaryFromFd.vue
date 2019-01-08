<template>
    <form
        action=""
        method="post">
        <div class="field">
            <label for="detailed_address">Уточненный адрес</label>
            <textarea
                v-model="card.detailed_address"
                id="detailed_address"
                cols="30"
                rows="3"
                class="textarea">
                {{ card.detailed_address || '' }}
            </textarea>
        </div>
        <div class="field">
            <div class="control">
                <label for="burn_object_id">Объект горения</label>
            </div>
            <div class="select">
                <select
                    v-model="card.burn_object_id"
                    id="burn_object_id">
                    <option value=""> -</option>
                    <option
                        v-for="burn in burn_object"
                        :key="`burn__${burn.id}`"
                        :value="burn.id">
                        {{ burn.name }}
                    </option>
                </select>
            </div>
        </div>
        <div class="field">
            <div class="control">
                <label for="living_sector_type_id">Тип жилого сектора</label>
            </div>
            <div class="select">
                <select
                    v-model="card.living_sector_type_id"
                    id="living_sector_type_id">
                    <option value=""> -</option>
                    <option
                        v-for="item in living_sector_types"
                        :key="`living__${item.id}`"
                        :value="item.id">
                        {{ item.name }}
                    </option>
                </select>
            </div>
        </div>
        <div class="field">
            <div class="control">
                <label for="trip_result_id">Результат выезда</label>
            </div>
            <div class="select">
                <select
                    v-model="card.trip_result_id"
                    id="trip_result_id">
                    <option value=""> -</option>
                    <option
                        v-for="trip_res in trip_result"
                        :key="`trip_result__${trip_res.id}`"
                        :value="trip_res.id">
                        {{ trip_res.name }}
                    </option>
                </select>
            </div>
        </div>

        <div class="field is-grouped">
            <div class="field control">
                <div class="control">
                    <label for="liquidation_method_id">Ликвидация достигнута</label>
                </div>
                <div class="select control">
                    <select
                        v-model="card.liquidation_method_id"
                        id="liquidation_method_id">
                        <option value="">-</option>
                        <option
                            v-for="liquidation_method in liquidation_methods"
                            :key="`liquidation_method__${liquidation_method.id}`"
                            :value="liquidation_method.id">
                            {{ liquidation_method.name }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="control">
                <div id="card101_other_records_results"></div>
            </div>
        </div>
        <br>
        <br>

        <div class="field is-grouped">
            <div class="control is-three-fifths">
                <p>
                    <label for="result_fire_level_id">Уточненный ранг пожара</label>
                </p><div class="select">
                    <select
                        v-model="card.result_fire_level_id"
                        id="result_fire_level_id">
                        <option value="">-</option>
                        <option
                            v-for="fire_level in fire_levels"
                            :key="`fire_level__${fire_level.id}`"
                            :value="fire_level.id">
                            {{ fire_level.name }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="control">
                <label for="square_max">Площадь пожара</label>
                <input
                    min=0
                    oninput="validity.valid||(value='');"
                    class="input"
                    type="number"
                    step=".01"
                    id="square_max"
                    v-model.number="card.max_square">
            </div>
            <div class="control">
                <span class="field">м<sup><small>2</small></sup></span>
            </div>
        </div>
        <div class="field is-grouped">
            <div class="control">
                <div class="checkbox">
                    <input
                        type="hidden"
                        v-model="card.vu_found"
                        value="0">
                    <input
                        type="checkbox"
                        v-model="card.vu_found"
                        id="vu_found"
                        value="1">
                    <label for="vu_found">Обнаружено ВУ</label>
                </div>
            </div>
            <div class="control">
                <div class="checkbox">
                    <input
                        type="hidden"
                        v-model="card.animal_death"
                        value="0">
                    <input
                        type="checkbox"
                        v-model="card.animal_death"
                        id="animal_death"
                        value="1">
                    <label for="animal_death">Гибель животных</label>
                </div>
            </div>
            <div class="control">
                <div class="checkbox">
                    <input
                        type="hidden"
                        v-model="card.car_crash"
                        value="0">
                    <input
                        type="checkbox"
                        v-model="card.car_crash"
                        id="car_crash"
                        value="1">
                    <label for="car_crash">Авария А/М при следовании на пожар</label>
                </div>
            </div>

        </div>
        <div
            class="field is-grouped"
            style="margin-right: 40px">
            <div
                class="control"
                style="width: 25%">
                <label for="rescued_count">Спасено людей
                </label>
                <input
                    min=0
                    oninput="validity.valid||(value='');"
                    type="number"
                    class="input"
                    v-model.number="card.rescued_count"
                    id="rescued_count">
            </div>
            <div
                class="control"
                style="width: 25%">
                <label for="evac_count">Эвакуировано людей
                </label>
                <input
                    min=0
                    oninput="validity.valid||(value='');"
                    type="number"
                    class="input"
                    v-model.number="card.evac_count"
                    id="evac_count">
            </div>

            <div
                class="control"
                style="width: 25%">
                <label for="co2_poisoned_count">Получили отравления угарным газом
                </label>
                <input
                    min=0
                    oninput="validity.valid||(value='');"
                    type="number"
                    class="input"
                    v-model.number="card.co2_poisoned_count"
                    id="co2_poisoned_count">
            </div>
            <div
                class="control"
                style="width: 25%">
                <label for="ch4_poisoned_count">Получили отравления природным газом
                </label>
                <input
                    min=0
                    oninput="validity.valid||(value='');"
                    type="number"
                    class="input"
                    v-model.number="card.ch4_poisoned_count"
                    id="ch4_poisoned_count">
            </div>

        </div>
        <div
            class="field is-grouped"
            style="margin-right: 40px">
            <div
                class="control"
                style="width: 25%">
                <label for="gpt_burns_count">Получили ожоги
                </label>
                <input
                    min=0
                    oninput="validity.valid||(value='');"
                    type="number"
                    class="input"
                    v-model.number="card.gpt_burns_count"
                    id="gpt_burns_count">
            </div>

            <div
                class="control"
                style="width: 25%">
                <label for="people_death_count">Гибель людей
                </label>
                <input
                    min=0
                    oninput="validity.valid||(value='');"
                    type="number"
                    class="input"
                    v-model.number="card.people_death_count"
                    id="people_death_count">
            </div>
            <div
                class="control"
                style="width: 25%">
                <label for="children_death_count">Гибель детей
                </label>
                <input
                    min=0
                    oninput="validity.valid||(value='');"
                    type="number"
                    class="input"
                    v-model.number="card.children_death_count"
                    id="children_death_count">
            </div>
            <div
                class="control"
                style="width: 25%">
                <label for="hospitalized_count">Госпитализировано
                </label>
                <input
                    min=0
                    oninput="validity.valid||(value='');"
                    type="number"
                    class="input"
                    v-model.number="card.hospitalized_count"
                    id="hospitalized_count">
            </div>
        </div>

        <div class="field">
            <div class="control">
                <label for="card_result">Итог выезда</label>
                <textarea
                    class="textarea"
                    cols="30"
                    v-model="card.ticket_result"
                    id="card_result"
                    rows="2">{{ card.ticket_result || '' }}</textarea>
            </div>
        </div>

        <div class="field">
            <div class="control">
                <label for="special_tech">Спец. тех. средства</label>
                <textarea
                    class="textarea"
                    cols="30"
                    v-model="card.special_tech"
                    id="special_tech"
                    rows="2">{{ card.special_tech || '' }}</textarea>
            </div>
        </div>

        <div class="field">
            <label for="more_info">Дополнительная информация</label>
            <textarea
                v-model="card.more_info"
                id="more_info"
                cols="30"
                rows="2"
                class="textarea">{{ card.more_info || '' }}</textarea>
        </div>
        <div class="field">
            <div class="control">
                <label for="water_supply_source_id">Источник противопожарного водоснабжения</label>
            </div>
            <div class="select">
                <select
                    v-model="card.water_supply_source_id"
                    id="water_supply_source_id">
                    <option
                        v-for="object in water_sources"
                        :key="`water__${object.id}`"
                        :value="object.id">
                        {{ object.name }}
                    </option>
                </select>
            </div>

        </div>

        <div class="field">
            <div class="control">
                <label for="distance">Расстояние до места
                </label>
                <input
                    min=0
                    oninput="validity.valid||(value='');"
                    type="number"
                    class="input"
                    step=".01"
                    v-model="card.distance"
                    id="distance">
            </div>
        </div>
        <div class="field">
            <label for="owner">Владелец(дома,кв.,а/м)</label>
            <input
                type="text"
                id="owner"
                v-model="card.owner"
                class="input">
        </div>
        <div
            class="panel"
            style="padding: 30px 10px 20px; margin-top:20px;border-top: 1px solid #dbdbdb; background-color: #f7f7f7">
            <div class="level">
                <div class="level-left">
                    <button
                        type="submit"
                        @click.prevent="saveInfo"
                        class="button is-basic is-main"><i class="fas fa-check"></i>&nbsp;
                        Сохранить
                    </button>
                </div>
            </div>
        </div>
    </form>
</template>

<script>
import axios from 'axios';

export default {
    props: {
        ticket: {
            type: Object,
            default: () => {}
        },
        burn_object: {
            type: Array,
            default: () => []
        },
        living_sector_types: {
            type: Array,
            default: () => []
        },
        trip_result: {
            type: Array,
            default: () => []
        },
        liquidation_methods: {
            type: Array,
            default: () => []
        },
        fire_levels: {
            type: Array,
            default: () => []
        },
        water_sources: {
            type: Array,
            default: () => []
        },
        fire_department_id: {
            type: Number,
            default: null
        }
    },
    data() {
        return {
            card: {
                id: this.ticket.id,
                detailed_address: this.ticket.detailed_address || null,
                burn_object_id: this.ticket.burn_object_id || null,
                living_sector_type_id: this.ticket.living_sector_type_id || null,
                trip_result_id: this.ticket.trip_result_id || null,
                liquidation_method_id: this.ticket.liquidation_method_id || null,
                result_fire_level_id: this.ticket.result_fire_level_id || null,
                max_square: this.ticket.max_square || null,
                vu_found: this.ticket.vu_found || false,
                animal_death: this.ticket.animal_death || false,
                car_crash: this.ticket.car_crash || false,
                rescued_count: this.ticket.rescued_count || null,
                evac_count: this.ticket.evac_count || null,
                co2_poisoned_count: this.ticket.co2_poisoned_count || null,
                ch4_poisoned_count: this.ticket.ch4_poisoned_count || null,
                gpt_burns_count: this.ticket.gpt_burns_count || null,
                people_death_count: this.ticket.people_death_count || null,
                children_death_count: this.ticket.children_death_count || null,
                hospitalized_count: this.ticket.hospitalized_count || null,
                ticket_result: this.ticket.ticket_result || null,
                special_tech: this.ticket.special_tech || null,
                more_info: this.ticket.more_info || null,
                water_supply_source_id: this.ticket.water_supply_source_id || null,
                distance: this.ticket.distance || null,
                owner: this.ticket.owner || null,
                ticket_id: this.ticket.ticket_id,
                fire_department_id: this.ticket.fire_department_id
            }
        };
    },

    mounted() {
        let token = document.head.querySelector('meta[name="csrf-token"]');
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content || '';
    },
    methods: {
        saveInfo() {
            axios.post('/api/101card/save-info-from-fd', {
                card: this.card
            }).then((resp) => {
                this.$snackbar.open({
                    message: 'Информация успешно обновлена!',
                    position: 'is-bottom-left',
                    type: 'is-success',
                    duration: 10000
                });
            }).catch((e) => {
                this.$snackbar.open({
                    message: 'Ошибка! Что-то пошло не так',
                    position: 'is-bottom-left',
                    type: 'is-warning',
                    duration: 10000
                });
            });
        }
    }
};
</script>
