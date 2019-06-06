<template>
    <section
        class="container">
        <h4
            class="title"
            style="padding: 3px 15px">{{ model_.id ? 'Редактирование' : 'Добавление' }}: {{ gaugingStation.name }}</h4>
        <form
            :action="formRoute"
            method="POST">
            <input
                type="hidden"
                name="_token"
                :value="csrf">
            <input
                    type="hidden"
                    name="gauging_station_id"
                    :value="gaugingStation.id">
            <div class="panels">
                <div class="panels">
                    <div class="field">
                        <v-datepicker-search
                                v-model="model_.date"
                                :date-string="model_.date"
                                name="date"
                                :include-time="true"
                                @dateChanged="model_.date = $event"
                                class="control"
                                label="Дата">
                        </v-datepicker-search>
                    </div>
                    <!--Информация-->
                    <div class="field">
                        <label for="information">{{ 'info'|trans }}</label><!--Информация-->
                        <textarea
                            name="information"
                            id="information"
                            class="textarea"
                            cols="30"
                            rows="3"
                            v-model="model_.information"></textarea>
                    </div>
                    <!--Расход воды-->
                    <div class="field">
                        <label for="water_flow_rate">{{ '/mudflow-protection.water_flow_rate'|trans }}</label><!--Расход воды-->
                        <input
                            type="number"
                            step="0.01"
                            class="input"
                            name="water_flow_rate"
                            id="water_flow_rate"
                            v-model="model_.water_flow_rate">
                    </div>
                    <!--Критический расход воды-->
                    <div class="field">
                        <label for="critical_water_flow_rate">{{ '/mudflow-protection.critical_water_flow_rate'|trans }}</label><!--Критический расход воды-->
                        <input
                            type="number"
                            step="0.01"
                            class="input"
                            name="critical_water_flow_rate"
                            id="critical_water_flow_rate"
                            v-model="model_.critical_water_flow_rate">
                    </div>
                    <!--Мутность воды-->
                    <div class="field">
                        <label for="turbidity_of_water">{{ '/mudflow-protection.turbidity_of_water'|trans }}</label><!--Мутность воды-->
                        <input
                            type="number"
                            step="0.01"
                            class="input"
                            name="turbidity_of_water"
                            id="turbidity_of_water"
                            v-model="model_.turbidity_of_water">
                    </div>
                    <!--Максимальная мутность воды-->
                    <div class="field">
                        <label for="max_turbidity_of_water">{{ '/mudflow-protection.max_turbidity_of_water'|trans }}</label><!--Максимальная мутность воды-->
                        <input
                            type="number"
                            step="0.01"
                            class="input"
                            name="max_turbidity_of_water"
                            id="max_turbidity_of_water"
                            v-model="model_.max_turbidity_of_water">
                    </div>
                    <!--Температура воздуха-->
                    <div class="field">
                        <label for="air_temperature">{{ '/mudflow-protection.air_temperature'|trans }}</label><!--Температура воздуха-->
                        <input
                            type="number"
                            step="0.01"
                            class="input"
                            name="air_temperature"
                            id="air_temperature"
                            v-model="model_.air_temperature">
                    </div>
                    <!--Температура воды-->
                    <div class="field">
                        <label for="water_temperature">{{ '/mudflow-protection.water_temperature'|trans }}</label><!--Температура воды-->
                        <input
                            type="number"
                            step="0.01"
                            class="input"
                            name="water_temperature"
                            id="water_temperature"
                            v-model="model_.water_temperature">
                    </div>
                    <!--Осадки-->
                    <div class="field">
                        <label for="precipitation">{{ '/mudflow-protection.precipitation'|trans }}</label><!--Осадки-->
                        <input
                            type="number"
                            step="0.01"
                            class="input"
                            name="precipitation"
                            id="precipitation"
                            v-model="model_.precipitation">
                    </div>
                    <!--Высота снега-->
                    <div class="field">
                        <label for="height_of_snow">{{ '/mudflow-protection.height_of_snow'|trans }}</label><!--Высота снега-->
                        <input
                            type="number"
                            step="0.01"
                            class="input"
                            name="height_of_snow"
                            id="height_of_snow"
                            v-model="model_.height_of_snow">
                    </div>
                    <!--Погода-->
                    <div class="field">
                        <label for="weather">{{ '/mudflow-protection.weather'|trans }}</label><!--Погода-->
                        <textarea
                            name="weather"
                            id="weather"
                            class="textarea"
                            cols="30"
                            rows="3"
                            v-model="model_.weather"></textarea>
                    </div>
                    <!--Комментарий-->
                    <div class="field">
                        <label for="comment">{{ '/mudflow-protection.comment'|trans }}</label><!--Комментарий-->
                        <textarea
                            name="comment"
                            id="comment"
                            class="textarea"
                            cols="30"
                            rows="3"
                            v-model="model_.comment"></textarea>
                    </div>
                </div>
            </div>
            <div
                class="panel bottom_panel">
                <div class="level">
                    <p class="level-right">
                        <a
                            href="/mudflow-protection/2019-02-05/"
                            class="button is-danger"><i class="fas fa-times"></i>&nbsp;{{ 'cancel'|trans }}<!--Отменить-->
                        </a>
                    </p>
                    <p class="level-right">
                        <button
                            type="submit"
                            class="button is-success"><i class="fas fa-check"></i>{{ 'save'|trans }}<!--Сохранить-->
                        </button>
                    </p>
                </div>
            </div>
        </form>
    </section>
</template>

<script>
export default {
    name: 'MudflowProtectionForm',
    props: {
        model: {
            type: Object,
            default: () => {},
        },
        gaugingStation: {
            type: Object,
            default: () => {},
        },
        formRoute: {
            type: String,
            default: '',
        },
    },
    data() {
        return {
            csrf: window.csrf_token,
            model_: this.model ? this.model : {
                information: '',
                water_flow_rate: '',
                critical_water_flow_rate: '',
                turbidity_of_water: '',
                max_turbidity_of_water: '',
                air_temperature: '',
                water_temperature: '',
                precipitation: '',
                height_of_snow: '',
                weather: '',
                comment: '',
                date: '',
            },
            method: 'POST',
            date: new Date(),
            formRoute_: this.formRoute
        };
    },
    computed: {
        formDataExists() {
            return !!window.mudflowProtectionFormData;
        }
    },
};
</script>

<style scoped>
    .bottom_panel {
        padding: 30px 10px 20px;
        margin-top: 20px;
        border-top: 1px solid #dbdbdb;
        background-color: #f7f7f7
    }

</style>
