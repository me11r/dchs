<template>
    <div class="panel">
        <div class="box">
            <form :action="`/service-plans/additional/${record.id}`"
                  method="post"
            >
                <input type="hidden" :value="csrf" name="_token">
                <div class="field field-quarter">
                    <v-datepicker-search
                            v-model="model.date_time"
                            :date-string="model.date_time"
                            name="date_time"
                            :include-time="true"
                            class="control"
                            @dateChanged="model.date_time = $event"
                            label="Дата и время">
                    </v-datepicker-search>
                </div>

                <div class="field inline-field field-half">
                    <label for="location">Место ЧС</label>
                    <input type="text"
                           class="input"
                           name="location"
                           id="location"
                           v-model="model.location"/>
                </div>
                <div class="field is-full">
                    <label for="description">Информация о событии</label>
                    <textarea name="description" v-model="model.description" class="textarea" id="description" cols="30" rows="10"></textarea>
                </div>

                <div class="columns">

                    <div class="column">
                        <label for="wounded">Пострадавших людей/детей</label>
                        <input type="number"
                               class="input"
                               name="wounded"
                               id="wounded"
                               v-model="model.wounded"/>
                    </div>

                    <div class="column">
                        <label for="died">Погибло людей/детей</label>
                        <input type="number"
                               class="input"
                               name="died"
                               id="died"
                               v-model="model.died"/>
                    </div>

                    <div class="column">
                        <label for="evacuated">Эвакуированных людей/детей</label>
                        <input type="number"
                               class="input"
                               name="evacuated"
                               id="evacuated"
                               v-model="model.evacuated"/>
                    </div>

                    <div class="column">
                        <label for="hospitalized">Госпитализированных людей/детей</label>
                        <input type="number"
                               class="input"
                               name="hospitalized"
                               id="hospitalized"
                               v-model="model.hospitalized"/>
                    </div>

                </div>
                <div class="columns">

                    <div class="column">
                        <label for="injured">Травмированных людей/детей</label>
                        <input type="number"
                               class="input"
                               name="injured"
                               id="injured"
                               v-model="model.injured"/>
                    </div>


                    <div class="column">
                        <label for="poisoned">Отравление людей/детей</label>
                        <input type="number"
                               class="input"
                               name="poisoned"
                               id="poisoned"
                               v-model="model.poisoned"/>
                    </div>

                    <div class="column">
                        <label for="saved">Спасено людей/детей</label>
                        <input type="number"
                               class="input"
                               name="saved"
                               id="saved"
                               v-model="model.saved"/>
                    </div>

                    <div class="column">
                        <label for="saved_animals">Спасено животных</label>
                        <input type="number"
                               class="input"
                               name="saved_animals"
                               id="saved_animals"
                               v-model="model.saved_animals"/>
                    </div>

                </div>

                <div class="columns" v-if="record.ticket">
                    <div class="column">
                        <b-checkbox v-model="model.notification_101">
                            Отправить уведомление диспетчеру 101
                        </b-checkbox>
                    </div>
                </div>
                <div class="columns" v-if="record.ticket112">
                    <div class="column">
                        <b-checkbox v-model="model.notification_112">
                            Отправить уведомление диспетчеру 112
                        </b-checkbox>
                    </div>
                </div>

                <div class="buttons has-text-right is-grouped is-right" style="">
                    <button @click.prevent="saveModel" type="submit" class="button is-success"><i class="fas fa-check"></i>&nbsp;Сохранить
                    </button>
                    <a href="/service-plans" class="button is-light">Отменить</a>
                </div>

            </form>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ServicePlanAdditional",
        props: {
            record: {
                type: Object,
                default: null,
            }
        },
        data: function () {
            return {
                model: this.record.service_plan_additional ? this.record.service_plan_additional : {
                    location: this.record.ticket ? this.record.ticket.location : this.record.ticket112.location,
                    description: null,
                    wounded: null,
                    died: null,
                    evacuated: null,
                    hospitalized: null,
                    injured: null,
                    poisoned: null,
                    saved: null,
                    saved_animals: null,
                    notification_101: 0,
                    notification_112: 0,
                    date_time: window.moment(this.record.dispatched_time).toDate(),
                },
                csrf: window.token,
            }
        },
        methods: {
            saveModel() {
                let clonedModel = window._.clone(this.model);
                clonedModel.date_time = window.moment(clonedModel.date_time).format('YYYY-MM-DD HH:mm');
                clonedModel.notification_101 = clonedModel.notification_101 ? 1 : 0;
                clonedModel.notification_112 = clonedModel.notification_112 ? 1 : 0;

                window.axios.post(`/service-plans/additional/${this.record.id}`, clonedModel)
                    .then((r) => {
                        window.location.href = `/service-plans/additional/${this.record.id}`;
                    })
            }
        },
        mounted() {
            if (this.record.service_plan_additional) {
                this.record.service_plan_additional.notification_112 = this.record.service_plan_additional.notification_112 === 0 ? false : true;
                this.record.service_plan_additional.notification_101 = this.record.service_plan_additional.notification_101 === 0 ? false : true;
            }
        }
    }
</script>

<style scoped>
    .inline-field {
        display: inline-block;
    }

    .field-half, .field-quarter {
        padding: 0 10px 0 10px;
        margin: 0 0 0 -5px;
    }

    .field-full {
        width: 100%;
    }

    .field-half {
        width: 50%;
    }

    .field-quarter {
        width: 25%;
    }

    .field-third-part {
        width: 33%;
    }

    .field-group {
        display: block;
    }

    .select.full-width-select,
    .select.full-width-select select {
        width: 100%;
    }
</style>