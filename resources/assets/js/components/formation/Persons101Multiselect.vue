<template>
    <div>
        <span
            :class="titleClass"
            class="name-span"
            @click="activateTrigger()">{{ name }}:</span>
        <template v-if="activated">
            <multiselect
                v-model="$parent.selectedStaff[rank]"
                :options="options"
                :multiple="true"
                :close-on-select="false"
                :clear-on-select="false"
                :hide-selected="true"
                :preserve-search="true"
                placeholder=""
                label="name"
                track-by="unique"
                required
            />
        </template>
        <template v-else>{{ staffToShow }}</template>
    </div>
</template>

<script>

import Multiselect from 'vue-multiselect';
import _ from 'lodash';
import axios from 'axios';

export default {
    name: 'Persons101Multiselect',
    props: {
        name: {
            'type': String,
            'default': ''
        },
        rank: {
            'type': String,
            'default': ''
        },
        titleClass: {
            'type': String | Object | Array,
            'default': ''
        }
    },
    computed: {
        staffToShow() {
            const data = [];
            _.each(this.$parent.selectedStaff[this.rank], (item) => {
                data.push(item['name']);
            });

            return data.join(', ');
        },
        options() {
            return this.$parent.odStaff && this.$parent.odStaff[this.rank] ? this.$parent.odStaff[this.rank] : [];
        }
    },
    data() {
        return {
            activated: false
        };
    },
    methods: {
        activateTrigger() {
            if (this.activated) {
                this.activated = false;
                this.save();
            } else {
                this.activated = true;
            }
        },
        save() {
            axios.post('/api/101/sync-formation-od-persons', {
                formId: this.$parent.formId,
                type: this.rank,
                selectedStaff: this.$parent.selectedStaff[this.rank]
            });
        }
    },
    components: {
        Multiselect
    }
};
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style scoped>
    .name-span {
        cursor: pointer;
        padding: 0 20px 0 0;
    }
    .name-span:hover {
        color:#15A4FA;
    }
</style>
