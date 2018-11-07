<template>
    <div class="field">
        <div class="add_button">
            <button
                class="button is-small is-basic"
                type="button"
                @click.prevent="addEmptyItem()">
                <i class="fa fa-plus"></i>&nbsp;Добавить телефон
            </button>
        </div>

        <div
            class="panels"
            v-for="item in records_"
            :key="item.id">

            <div class="field is-grouped">
                <div class="control column is-four-fifths">
                    <label :for="getName('phone_id')">Телефон</label><br>
                    <input
                        required
                        type="text"
                        class="input"
                        :name="getName('phone_id', item.id)"
                        :id="getName('phone_id', item.id)"
                        v-model="item.phone"
                    >

                </div>

                <div class="control column">
                    <label>Удалить</label>

                    <button
                        class="button is-small is-outlined is-danger square-button-36"
                        @click.prevent="removeItem(item.id)"
                        type="button"
                        title="Удалить">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'Phone',
    props: {
        block_type: {
            type: String,
            default: ''
        },
        records: {
            type: Array,
            default: () => []
        }
    },
    data() {
        return {
            records_: this.records,
            block_type_: this.block_type
        };
    },
    methods: {
        getName(control) {
            return control + `[]`;
        },
        addEmptyItem() {
            this.addItem({});
        },
        addItem(item) {
            this.records_.push(item);
        },
        removeItem(id) {
            if (confirm('Вы действительно хотите удалить эту запись?')) {
                this.records_ = this.records_.filter(function (item) {
                    return item.id !== id;
                });
            }
        }
    }
};
</script>

<style scoped>
    .small-time-picker {
        max-width: 6rem;
    }

    .select, .select > select {
        width: 100%
    }

    .is-narrow {
        width: 20%;
    }

    .square-button-36 {
        display: block;
        height: 36px;
        width: 36px;
    }

    .add_button {
        padding: 0 0 20px 0;
    }
</style>
