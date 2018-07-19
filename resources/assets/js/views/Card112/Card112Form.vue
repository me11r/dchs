<template>
    <section class="container">
        <h4
            class="title"
            style="padding: 3px 15px">Добавление: Карточка 112</h4>
        <form
            action=""
            method="post">
            <input
                type="hidden"
                name="_token"
                :value="csrf">
            <div class="tabs is-boxed">
                <ul>
                    <li :class="{'is-active': currentTabIndex === 0}">
                        <a @click="setTab(0)"><i class="fas fa-phone"/>&nbsp;Звонок</a>
                    </li>
                    <li :class="{'is-active': currentTabIndex === 1}">
                        <a
                            @click="setTab(1)">
                        <i class="fas fa-envelope"/>&nbsp;Уведомления</a>
                    </li>
                    <li :class="{'is-active': currentTabIndex === 2}">
                        <a
                            @click="setTab(2)">
                        <i class="fas fa-truck-moving"/>&nbsp; Подразделения</a>
                    </li>
                    <li :class="{'is-active': currentTabIndex === 3}">
                        <a
                            @click="setTab(3)">
                        <i class="fas fa-info-circle"/>&nbsp; Прочее</a>
                    </li>
                    <li :class="{'is-active': currentTabIndex === 4}">
                        <a
                            @click="setTab(4)">
                        <i class="fas fa-check-double"/>&nbsp;Итоги выезда</a>
                    </li>
                </ul>
            </div>
            <div class="panels">
                <div class="panels">
                    <div
                        id="tab_call"
                        :class="{'is-hidden': currentTabIndex !== 0}">
                        <h5 class="subtitle">Первоначальная информация заявителя:</h5>
                        <div class="field">
                            <label for="street_id">Адрес</label>
                            <select
                                id="street_id"
                                name="street_id"
                                v-model="model.street_id"
                                required>
                                <option
                                    v-for="street in streets"
                                    :key="street.id"
                                    :value="street.id">{{ street.name }}</option>
                            </select>
                        </div>

                        <!--<div class="field is-grouped">-->
                            <!--<div-->
                                <!--class="control is-expanded"-->
                                <!--data-component="autocomplete"-->
                                <!--:data-name="model.crossroad_1? model.crossroad_1.name.name : ''"-->
                                <!--:data-hid="model.crossroad_1_id">-->
                                <!--<label for="crossroad_1_id">Пересечение улицы</label>-->
                                <!--<input-->
                                    <!--type="hidden"-->
                                    <!--name="crossroad_1_id"-->
                                    <!--id="crossroad_1_hid"-->
                                    <!--:value="model.crossroad_1_id">-->
                                <!--<b-autocomplete-->
                                    <!--field="name"-->
                                    <!--ref="crossroad_1_hid"-->
                                    <!--id="crossroad_1_id"-->
                                    <!--v-model="name"-->
                                    <!--:data="streets"-->
                                    <!--@select="onSelect">-->
                                    <!--<template slot-scope="props">-->
                                        <!--<div class="row"><span>{{ props.option.name }}</span><small v-if="props.option.area">, {{ props.option.area.name }} район</small></div>-->
                                    <!--</template>-->
                                    <!--<template slot="empty">Подходящих улиц не найдено</template>-->
                                <!--</b-autocomplete>-->
                            <!--</div>-->
                            <!--<div-->
                                <!--class="control is-expanded"-->
                                <!--data-component="autocomplete"-->
                                <!--:data-name="model.crossroad_2? model.crossroad_2.name.name : ''"-->
                                <!--:data-hid="model.crossroad_2_id">-->
                                <!--<label for="crossroad_2_id">и улицы</label>-->
                                <!--<input-->
                                    <!--type="hidden"-->
                                    <!--name="crossroad_2_id"-->
                                    <!--id="crossroad_2_hid"-->
                                    <!--:value="model.crossroad_1_id">-->
                                <!--<b-autocomplete-->
                                    <!--type="text"-->
                                    <!--id="crossroad_2_id"-->
                                    <!--field="name"-->
                                    <!--ref="crossroad_2_hid"-->
                                    <!--v-model="name"-->
                                    <!--:data="streets"-->
                                    <!--@select="onSelect">-->
                                    <!--<template slot-scope="props">-->
                                        <!--<div class="row"><span>{{ props.option.name }}</span><small v-if="props.option.area">, {{ props.option.area.name }} район</small></div>-->
                                    <!--</template>-->
                                    <!--<template slot="empty">Подходящих улиц не найдено</template>-->
                                <!--</b-autocomplete>-->
                            <!--</div>-->
                        <!--</div>-->
                    </div>
                </div>
        </div></form>
    </section>
</template>

<script>
import {bAutocomplete} from 'buefy';

export default {
    name: 'Card112Form',
    data() {
        return {
            streets: [],
            incidentTypes: [],
            serviceTypes: [],
            model: [],
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            currentTabIndex: 0
        };
    },
    components: {
        bAutocomplete
    },
    methods: {
        setTab(tabIndex) {
            this.currentTabIndex = tabIndex;
        },
        onSelect(data) {
            console.log(data);
        }
    },
    beforeMount() {
        if (window.card112FormData) {
            this.streets = window.card112FormData.streets;
            this.incidentTypes = window.card112FormData.incidentTypes;
            this.serviceTypes = window.card112FormData.serviceTypes;
            this.model = window.card112FormData.model;
        }
    }
};
</script>
