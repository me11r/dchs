<template>
    <div>
        <div v-for="tripResult in tripResults_" class="">
            <div v-for="item in record_.items" class="">
                <div v-if="item.trip_result_id === tripResult.id && item.ticket101" class="field">
                    <h4 class="header"><b>{{ tripResult.name }}</b></h4>
                    <div>
                        <vue-editor name="text" v-model="item.text"></vue-editor>
                        <input type="text" :name="`text[${item.id}]`" v-model="item.text" hidden>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { VueEditor } from "vue2-editor";

    export default {
        name: "EditAnalytics",
        data: function () {
            return {
                tripResults_: this.tripResults,
                record_: this.record,
            }
        },
        props: {
            tripResults: {
                type: Array,
                default: () => [],
            },
            record: {
                type: Object,
                default: () => {},
            },
        },
        components: {
            'vue-editor': VueEditor,
        },
        methods: {

        }
    }
</script>

<style scoped>

</style>