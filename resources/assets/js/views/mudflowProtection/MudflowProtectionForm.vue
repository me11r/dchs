<template>
    <section
        class="container"
        v-if="formDataExists">
        <h4
            class="title"
            style="padding: 3px 15px">{{ model.id ? 'Редактирование' : 'Добавление' }}: {{ model.gaugingStation.name }}</h4>
        <form
            :action="this.formRoute"
            method="POST">
            <input
                type="hidden"
                name="_method"
                :value="method">
            <input
                type="hidden"
                name="_token"
                :value="csrf">
            <input
                    type="hidden"
                    name="gauging_station_id"
                    :value="model.gauging_station_id">
            <div class="panels">
                <div class="panels">
                    <!--Информация-->
                    <div class="field">
                        <label for="information">Информация</label>
                        <textarea
                            name="information"
                            id="information"
                            class="textarea"
                            cols="30"
                            rows="3"
                            v-model="model.information"></textarea>
                    </div>
                    <!--Расход воды-->
                    <div class="field">
                        <label for="water_flow_rate">Расход воды</label>
                        <input
                            type="number"
                            step="0.01"
                            class="input"
                            name="water_flow_rate"
                            id="water_flow_rate"
                            v-model="model.water_flow_rate">
                    </div>
                    <!--Критический расход воды-->
                    <div class="field">
                        <label for="critical_water_flow_rate">Критический расход воды</label>
                        <input
                            type="number"
                            step="0.01"
                            class="input"
                            name="critical_water_flow_rate"
                            id="critical_water_flow_rate"
                            v-model="model.critical_water_flow_rate">
                    </div>
                    <!--Мутность воды-->
                    <div class="field">
                        <label for="turbidity_of_water">Мутность воды</label>
                        <input
                            type="number"
                            step="0.01"
                            class="input"
                            name="turbidity_of_water"
                            id="turbidity_of_water"
                            v-model="model.turbidity_of_water">
                    </div>
                    <!--Максимальная мутность воды-->
                    <div class="field">
                        <label for="max_turbidity_of_water">Максимальная мутность воды</label>
                        <input
                            type="number"
                            step="0.01"
                            class="input"
                            name="max_turbidity_of_water"
                            id="max_turbidity_of_water"
                            v-model="model.max_turbidity_of_water">
                    </div>
                    <!--Температура воздуха-->
                    <div class="field">
                        <label for="air_temperature">Температура воздуха</label>
                        <input
                            type="number"
                            step="0.01"
                            class="input"
                            name="air_temperature"
                            id="air_temperature"
                            v-model="model.air_temperature">
                    </div>
                    <!--Температура воды-->
                    <div class="field">
                        <label for="water_temperature">Температура воды</label>
                        <input
                            type="number"
                            step="0.01"
                            class="input"
                            name="water_temperature"
                            id="water_temperature"
                            v-model="model.water_temperature">
                    </div>
                    <!--Осадки-->
                    <div class="field">
                        <label for="precipitation">Осадки</label>
                        <input
                            type="number"
                            step="0.01"
                            class="input"
                            name="precipitation"
                            id="precipitation"
                            v-model="model.precipitation">
                    </div>
                    <!--Высота снега-->
                    <div class="field">
                        <label for="height_of_snow">Высота снега</label>
                        <input
                            type="number"
                            step="0.01"
                            class="input"
                            name="height_of_snow"
                            id="height_of_snow"
                            v-model="model.height_of_snow">
                    </div>
                    <!--Погода-->
                    <div class="field">
                        <label for="water_flow_rate">Погода</label>
                        <textarea
                            name="weather"
                            id="weather"
                            class="textarea"
                            cols="30"
                            rows="3"
                            v-model="model.weather"></textarea>
                    </div>
                    <!--Комментарий-->
                    <div class="field">
                        <label for="water_flow_rate">Комментарий</label>
                        <textarea
                            name="comment"
                            id="comment"
                            class="textarea"
                            cols="30"
                            rows="3"
                            v-model="model.comment"></textarea>
                    </div>
                </div>
            </div>
            <div
                class="panel bottom_panel">
                <div class="level">
                    <p class="level-right">
                        <button
                            type="submit"
                            class="button is-success"><i class="fas fa-check"></i>Сохранить
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
    data() {
        return {
            csrf: window.csrf_token,
            model: {},
            method: 'POST',
            formRoute: ''
        };
    },
    computed: {
        formDataExists() {
            return !!window.mudflowProtectionFormData;
        }
    },
    beforeMount() {
        if (window.mudflowProtectionFormData) {
            this.method = window.mudflowProtectionFormData.method;
            this.formRoute = window.mudflowProtectionFormData.formRoute;
            this.model = window.mudflowProtectionFormData.model;
        }
    }
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
