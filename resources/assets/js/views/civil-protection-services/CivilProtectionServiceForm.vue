<template>
    <div>
        <v-datepicker-search
                v-model="record_.date"
                :date="record_.date"
                @dateChanged="record_.date = $event"
                class="control"
                :disabled="!record_.is_active"
                label="Дата">
        </v-datepicker-search>
        <br>
        <div class="level" v-if="record_.id">
            <div class="level-left">
                <div class="level-item">
                </div>

            </div>
            <div class="level-right">
                <div class="level-item">
                    <a :href="`/civil-protection-services/export/${record.id}`" class="button is-basic">
                        <i class="fas fa-file-word"></i>&nbsp;Скачать
                    </a>
                </div>
            </div>
        </div>

        <div class="section" v-for="block in blocks" :key="`block_${block.id}`">
            <p class="title">{{ block.name }}</p>

            <table class="table is-small is-fullwidth is-bordered is-striped is-narrow is-hoverable">
                <thead>
                <tr>
                    <th>Должность</th>
                    <th>Ф.И.О</th>
                    <th>Телефон</th>
                    <th>Мешкотара</th>
                    <th>Песок и др.</th>
                    <th>Шанцевые инструменты</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                    <tr v-for="blockItem in record_.items" v-if="blockItem.cp_service_block_id === block.id">
                        <td>
                            <textarea
                                    placeholder=""
                                    :disabled="!record_.is_active"
                                    v-model="blockItem.position"
                                    class="textarea"></textarea>
                        </td>
                        <td>
                            <textarea
                                    placeholder=""
                                    :disabled="!record_.is_active"
                                    v-model="blockItem.name"
                                    class="textarea"></textarea>
                        </td>
                        <td>
                            <textarea
                                    placeholder=""
                                    :disabled="!record_.is_active"
                                    v-model="blockItem.contacts"
                                    class="textarea"></textarea>
                        </td>
                        <td>
                            <textarea
                                    placeholder=""
                                    :disabled="!record_.is_active"
                                    v-model="blockItem.inventory1"
                                    class="textarea"></textarea>
                        </td>
                        <td>
                            <textarea
                                    placeholder=""
                                    :disabled="!record_.is_active"
                                    v-model="blockItem.inventory2"
                                    class="textarea"></textarea>
                        </td>
                        <td>
                            <textarea
                                    placeholder=""
                                    :disabled="!record_.is_active"
                                    v-model="blockItem.inventory3"
                                    class="textarea"></textarea>
                        </td>
                        <td>
                            <a v-if="record_.is_active" @click.prevent="deleteRow(blockItem.id)" class="button is-danger">Удалить строку</a>
                        </td>
                    </tr>
                </tbody>
            </table>
            <a class="button is-success" v-if="record_.is_active" @click.prevent="addNewRow(block.id)">Добавить строку в таблицу</a>
        </div>
        <div class="section">
            <textarea
                    placeholder="Доп.информация"
                    v-model="record_.note"
                    :disabled="!record_.is_active"
                    class="textarea"></textarea>
        </div>
        <div class="panel" style="padding: 30px 10px 20px; margin-top:20px;border-top: 1px solid #dbdbdb; background-color: #f7f7f7">
            <div class="level">
                <p class="level-left">
                </p>
                <p class="level-right">
                    <button type="submit" v-if="record_.is_active" @click.prevent="store" class="button is-success"><i class="fas fa-check"></i>&nbsp; Сохранить</button>
                </p>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "CivilProtectionServiceForm",
        props: {
            record: {
                type: Object,
                default: null
            },
            blocks: {
                type: Array,
                default: () => []
            },
        },
        data: function () {
            return {
                record_: this.record ? this.record : {
                    id: null,
                    date: new Date(),
                    note: '',
                    is_active: true,
                    items: [],
                    blocks: this.blocks,
                }
            }
        },
        methods: {
            addNewRow(id) {
                this.record_.items.push({
                    id: window.moment().valueOf(),
                    cp_service_block_id: id,
                    position: null,
                    name: null,
                    contacts: null,
                    inventory1: null,
                    inventory2: null,
                    inventory3: null,
                });
            },
            store() {
                let record = window._.clone(this.record_);
                record.date = window.moment(record.date).format('YYYY-MM-DD');
                window.axios.post(this.storeUrl, record).then((resp) => {
                    window.location.href = '/civil-protection-services';
                });
            },
            deleteRow(id) {
                this.record_.items = this.record_.items.filter((item) => item.id !== id);
            }
        },
        computed: {
            storeUrl() {
                return `/civil-protection-services/store`;
                return this.record_.id ? `/civil-protection-services/update/${this.record_.id}` : `/civil-protection-services/store`;
            }
        },
        created() {
            if (this.record) {
                this.record.date = window.moment(this.record.date).toDate();
            }
        }
    }
</script>

<style scoped>

</style>