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
import * as _ from 'lodash';
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
            }).sort((a, b) => {
                if (parseInt(a.text) - parseInt(b.text) === 0) { // compare between the same digits
                    a = a.text.substring(a.text.indexOf('/') + 1);
                    b = b.text.substring(b.text.indexOf('/') + 1);
                    return parseInt(a) - parseInt(b);
                }
                return parseInt(a.text) - parseInt(b.text);
            }) : [];
        },
        emptyText() {
            return this.text.length >= this.minLength ? 'Ничего не найдено' : 'Пожалуйста, введите не менее ' + this.minLength + ' символов';
        }
    },
    name: 'BuefyCommonSelect',
    methods: {
        getOptionById(id) {
            return _.find(this.options, {id: id});
        },
        select(option) {
            this.selected = option;
            const input = option && option.id ? option.id : null;
            this.$emit('input', input);
            this.$emit('update:value', input);
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
