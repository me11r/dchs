<template>
    <div
        class="container"
        style="padding: 20px 0 20px 0;">
        <h3 class="title">Очередь отчетов</h3>
        <button
            @click="loadItems()"
            class="button is-success"
            type="button"
            style="margin-bottom: 20px">Обновить список</button>
        <table
            class="table is-narrow is-hoverable is-fullwidth is-striped is-small formation-record-table"
            style="margin-bottom: 20px">
            <thead>
                <tr>
                    <th></th>
                    <th>Дата добавления</th>
                    <th>Отчет</th>
                    <th>От</th>
                    <th>До</th>
                    <th>Статус</th>
                    <th>Количество попыток</th>
                    <th>Текст ошибки</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="item in items"
                    :key="'queue_item_key' + item.id">
                    <td>{{ item.id }}</td>
                    <td>{{ item.created_at }}</td>
                    <td>{{ item.report_type.name }}</td>
                    <td>{{ item.date_start }}</td>
                    <td>{{ item.date_end }}</td>
                    <td>
                        <span
                            class="tag"
                            :class="classMap[item.status.slug]">
                            {{ item.status.name }}
                        </span>
                    </td>
                    <td>{{ item.attempts }}</td>
                    <td>{{ item.error_text }}</td>
                    <td>
                        <button
                            v-if="item.status.slug !== 'ENDED' && item.status.slug !== 'ERROR'"
                            @click="updateItemStatus(item.id)"
                            type="button"
                            class="button is-small">Обновить статус</button>
                        <button
                            v-if="item.status.slug === 'ERROR'"
                            @click="sendToQueue(item.id)"
                            type="button"
                            class="button is-info is-small">Повторить попытку</button>
                        <a
                            v-if="item.status.slug === 'ENDED'"
                            :href="'/api/upload/queued-report/file/download/' + item.id"
                            :download="item.file_name"
                            type="button"
                            class="button is-success is-small">Скачать</a>
                    </td>
                </tr>
            </tbody>
        </table>
        <v-pagination
            @change="changePage"
            :total="totalPages"
            :current="currentPage"
            :order="order"
            :per-page="perPage"/>
    </div>
</template>

<script>
import {CommonListMixin} from '../../mixins/common-list-mixin';
import _ from 'lodash';
import axios from 'axios';
import VPagination from '../../components/VPagination';

export default {
    name: 'QueuedReports',
    mixins: [CommonListMixin],
    components: {
        VPagination
    },
    data() {
        return {
            items: [],
            classMap: {
                'CREATED': '',
                'QUEUED': 'is-info',
                'IN_PROGRESS': 'is-warning',
                'ENDED': 'is-success',
                'ERROR': 'is-danger'
            }
        };
    },
    methods: {
        loadItems: _.debounce(function() {
            axios
                .get('/api/queued-reports', {
                    params: {
                        page: this.currentPage,
                        per_page: this.perPage
                    }
                })
                .then(response => {
                    const data = response['data'];
                    this.items = data['data'];
                    this.totalPages = parseInt(data['total']);
                    this.$snackbar.open({
                        message: 'Список обновлен',
                        type: 'is-success',
                        duration: 3000
                    });
                })
                .catch(() => {
                    this.$snackbar.open({
                        message: 'При получении списка произошла ошибка',
                        type: 'is-danger',
                        duration: 3000
                    });
                });
        }, 500),
        sendToQueue(id) {
            axios
                .post('/api/queued-reports/send-to-queue', {id})
                .then(response => {
                    this.$snackbar.open({
                        message: 'Отчет отправлен в очередь обработки',
                        type: 'is-success',
                        duration: 3000
                    });
                    this.updateItemStatus(id);
                })
                .catch(() => {
                    this.$snackbar.open({
                        message: 'При отправке в очередь произошла ошибка',
                        type: 'is-danger',
                        duration: 3000
                    });
                });
        },
        updateItemStatus(id) {
            axios
                .get('/api/queued-reports/' + id)
                .then((response) => {
                    this.setUpdatedItem(response.data);
                    this.$snackbar.open({
                        message: 'Статус обновлен',
                        type: 'is-success',
                        duration: 3000
                    });
                })
                .catch(() => {
                    this.$snackbar.open({
                        message: 'При обновлении статуса произошла ошибка',
                        type: 'is-danger',
                        duration: 3000
                    });
                });
        },
        setUpdatedItem(updatedItem) {
            this.items = this.items.map((item) => {
                return item.id === updatedItem.id ? updatedItem : item;
            });
        }
    },
    mounted() {
        this.loadItems();
    }
};
</script>

<style scoped>

</style>
