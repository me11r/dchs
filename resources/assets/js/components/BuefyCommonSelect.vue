<template>
    <b-autocomplete
        v-model="text"
        :data="filteredDataArray"
        :placeholder="placeholder"
        field="text"
        @select="select">
        <template slot="empty">{{ emptyText }}</template>
    </b-autocomplete>
</template>

<script>
import Buefy from 'buefy';
import {_} from 'vue-underscore';

export default {
    props: {
        options: {
            type: Array,
            default() {
                return [
                    {
                        'text': 'Список пуст',
                        'id': 0
                    }
                ];
            }
        },
        value: {
            type: Number,
            default: 0
        },
        placeholder: {
            type: String,
            default: ''
        },
        minLength: {
            type: Number,
            default: 3
        }
    },
    data() {
        return {
            text: '',
            selected: null
        };
    },
    computed: {
        filteredDataArray() {
            return this.text.length >= this.minLength ? this.options.filter((option) => {
                let text = option.text.toString().toLowerCase();
                let value = this.text.toLowerCase();
                return text.indexOf(value) >= 0;
            }) : [];
        },
        emptyText() {
            return this.text.length >= this.minLength ? 'Ничего не найдено' : 'Пожалуйста, введите не менее ' + this.minLength + ' символов';
        }
    },
    name: 'BuefyCommonSelect',
    components: {
        'b-autocomplete': Buefy['Autocomplete']
    },
    methods: {
        getOptionById(id) {
            return _.where(this.options, {id: id})[0];
        },
        select(option) {
            this.selected = option;
            const input = option && option.id ? option.id : null;
            this.$emit('input', input);
        },
        changeInitialOption() {
            const initialOption = this.getOptionById(this.value);
            if (initialOption) {
                this.selected = initialOption;
                this.text = this.selected.text;
            }
        }
    },
    watch: {
        value() {
            this.changeInitialOption();
        }
    },
    beforeMount() {
        this.changeInitialOption();
    }
};
</script>
