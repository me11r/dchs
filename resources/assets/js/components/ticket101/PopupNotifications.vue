<template>
    <div>
        <form @submit.prevent="sendMessage()">
            <div class="columns">
                <div class="column">
                    <div class="field">
                        <label for="notification_groups">Группы личного состава</label>
                        <multiselect
                            v-model="ticket101['notification_groups']"
                            :options="notificationGroups"
                            :multiple="true"
                            :close-on-select="false"
                            :clear-on-select="false"
                            :hide-selected="true"
                            :preserve-search="true"
                            :disabled="!!ticket101['notifications_sent']"
                            placeholder=""
                            label="name"
                            id="notification_groups"
                            track-by="id"
                            required
                        />
                    </div>
                </div>

                <div class="column">
                    <div class="field">
                        <label for="notification_message">Сообщение</label>
                        <textarea
                            :title="ticket101['notifications_sent'] ? 'Уведомление уже было отправлено' : ''"
                            :disabled="!!ticket101['notifications_sent']"
                            required
                            minlength="10"
                            maxlength="1000"
                            v-model="ticket101.notification_message"
                            class="textarea"
                            name="notification_message"
                            id="notification_message"
                            cols="30"
                            rows="2"></textarea>
                    </div>
                    <p class="level-right">
                        <button
                            :disabled="!!ticket101['notifications_sent']"
                            type="submit"
                            class="button is-success">
                            <i class="fas fa-check"></i>&nbsp; Отправить
                        </button>
                    </p>
                </div>
            </div>

            <div class="panel table-panel">
                <p class="level-left">
                    <button
                        style="margin-bottom: 25px;"
                        :disabled="!ticket101['notifications_sent']"
                        @click="getTicket101Data()"
                        type="button"
                        class="button is-basic">
                        <i class="fas fa-check"></i>&nbsp; Обновить
                    </button>
                </p>

                <table class="table is-expanded is-striped is-narrow is-fullwidth">
                    <thead>
                        <tr>
                            <th>№</th>
                            <th>Получатель</th>
                            <th>Группа</th>
                            <th>Время отправки</th>
                            <th>Время получения</th>
                            <th>Статус</th>
                            <th>Сообщение</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="item in ticket101['popup_notifications']"
                            :key="'popup_notification_' + item.id">
                            <td>{{ item['id'] }}</td>
                            <td>{{ item['user']['full_username'] }}</td>
                            <td>{{ item['group']['name'] }}</td>
                            <td>{{ item['send_date'] }}</td>
                            <td>{{ item['receive_date'] }}</td>
                            <td>{{ item['status']['name'] }}</td>
                            <td>{{ item['body'] }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</template>

<script>
import Multiselect from 'vue-multiselect';
import Ticket101Api from '../../services/api/ticket101-api';
import axios from 'axios';

export default {
    name: 'PopupNotifications',
    components: {
        Multiselect
    },
    data() {
        return {
            ticket101: {},
            notificationGroups: [],
            ticket101Api: new Ticket101Api(axios)
        };
    },
    methods: {
        sendMessage() {
            this.ticket101Api
                .sendNotifications(
                    this.ticket101['id'],
                    this.ticket101['notification_message'],
                    this.ticket101['notification_groups'].map((item) => {
                        return item.id;
                    }))
                .then(() => {
                    this.getTicket101Data();
                });
        },
        getTicket101Data() {
            this.ticket101Api
                .getTicket(this.ticket101.id)
                .then((data) => {
                    this.ticket101 = data;
                });
        }
    },
    beforeMount() {
        this.ticket101 = {...window.ticket101add.ticket101};
        this.notificationGroups = window.ticket101add.notificationGroups;
    }
};
</script>

<style scoped>
    .table-panel {
        padding: 30px 10px 20px;
        margin-top: 20px;
        border-top: 1px solid #dbdbdb;
        background-color: #f7f7f7;
    }
</style>
