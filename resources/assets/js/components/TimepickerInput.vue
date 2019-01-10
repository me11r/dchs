<template>
    <input
        type="text"
        v-model="value_"
        class="input"
        @change="changeInput()"
        @click="setEmpty"
        @keypress="isNumber($event)">
</template>

<script>
import {globalBus} from '../scripts/global-bus';
export default {
    name: 'TimepickerInput',
    data: function () {
        return {
            value_: this.valueData,
            unique_: this.unique,
        };
    },
    props: {
        valueData: {
            type: String,
            default: ''
        },

        valueTime: {
            type: String,
            default: ''
        },

        unique: {
            type: String,
            default: ''
        }

    },
    methods: {
        changeInput(event) {
            if (this.value_.length >= 4) {
                this.value_ = this.value_.replace(/\D/g, '');
                this.value_ = this.value_.slice(0, 4);

                let minutes = this.value_.slice(0, 2);
                let seconds = this.value_.slice(-2);
                this.value_ = minutes + ':' + seconds;

                this.$emit('timeChanged', this.value_);
                this.$emit('timeChangedUnique', {value: this.value_, unique: this.unique_});
                globalBus.$emit('timeChangedUnique', {value: this.value_, unique: this.unique_});
            }
        },
        setEmpty() {
            if (this.value_ === '00:00:00') {
                this.value_ = '';
            }
        },
        isNumber: function(evt) {
            // evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                evt.preventDefault();
            } else {
                return true;
            }
        }
    },
    computed: {
    }
};
</script>

<style scoped>

</style>
