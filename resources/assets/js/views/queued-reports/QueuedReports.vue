<template>
    <div
        class="container"
        style="padding: 20px 0 20px 0;">
        <h3 class="title">{{ '/reports/queued-reports.title'|trans }}</h3><!--Очередь отчетов-->
        <button
            @click="loadItems()"
            class="button is-success"
            type="button"
            style="margin-bottom: 20px">{{ 'refresh'|trans }}</button><!--Обновить список-->
        <table
            class="table is-narrow is-hoverable is-fullwidth is-striped is-small formation-record-table"
            style="margin-bottom: 20px">
            <thead>
                <tr>
                    <th></th>
                    <th>{{ '/reports/queued-reports.date'|trans }}</th><!--Дата добавления-->
                    <th>{{ '/reports/queued-reports.report'|trans }}</th><!--Отчет-->
                    <th>{{ '/reports/queued-reports.date_from'|trans }}</th><!--От-->
                    <th>{{ '/reports/queued-reports.date_to'|trans }}</th><!--До-->
                    <th>{{ 'status'|trans }}</th><!--Статус-->
                    <th>{{ '/reports/queued-reports.tries'|trans }}</th><!--Количество попыток-->
                    <th>{{ '/reports/queued-reports.error_text'|trans }}</th><!--Текст ошибки-->
                    <th>{{ '/reports/queued-reports.actions'|trans }}</th><!--Действия-->
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
                            class="button is-small">{{ 'refresh'|trans }}</button><!--Обновить статус-->
                        <button
                            v-if="item.status.slug === 'ERROR'"
                            @click="sendToQueue(item.id)"
                            type="button"
                            class="button is-info is-small">{{ '/reports/queued-reports.retry'|trans }}</button><!--Повторить попытку-->
                        <a
                            v-if="item.status.slug === 'ENDED' && item.file_path"
                            :href="'/api/upload/queued-report/file/download/' + item.id"
                            :download="item.file_name"
                            type="button"
                            class="button is-success is-small">{{ 'download'|trans }}</a><!--скачать-->
                        <a
                            v-if="item.status.slug === 'ENDED'"
                            :href="'/reports/queued-reports/view?report_id=' + item.id"
                            type="button"
                            class="button is-small">{{ 'view'|trans }}</a><!--просмотр-->
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
                .get('/auth-api/queued-reports', {
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
                        message: window.trans.get('/reports/queued-reports.list_changed'),/*'Список обновлен'*/
                        type: 'is-success',
                        duration: 3000
                    });
                })
                .catch(() => {
                    this.$snackbar.open({
                        message: window.trans.get('/reports/queued-reports.error_while_list_fetching'),/*'При получении списка произошла ошибка'*/
                        type: 'is-danger',
                        duration: 3000
                    });
                });
        }, 500),
        sendToQueue(id) {
            axios
                .post('/auth-api/queued-reports/send-to-queue', {id})
                .then(response => {
                    this.$snackbar.open({
                        message: window.trans.get('/reports/queued-reports.sending_in_queue'),/*'Отчет отправлен в очередь обработки'*/
                        type: 'is-success',
                        duration: 3000
                    });
                    this.updateItemStatus(id);
                })
                .catch(() => {
                    this.$snackbar.open({
                        message: window.trans.get('/reports/queued-reports.error_while_sending_in_queue'),/*'При отправке в очередь произошла ошибка'*/
                        type: 'is-danger',
                        duration: 3000
                    });
                });
        },
        updateItemStatus(id) {
            axios
                .get('/auth-api/queued-reports/' + id)
                .then((response) => {
                    this.setUpdatedItem(response.data);
                    this.$snackbar.open({
                        message: window.trans.get('/reports/queued-reports.status_changed'),/*'Статус обновлен'*/
                        type: 'is-success',
                        duration: 3000
                    });
                })
                .catch(() => {
                    this.$snackbar.open({
                        message: window.trans.get('/reports/queued-reports.error_while_status_changed'),/*'При обновлении статуса произошла ошибка'*/
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
