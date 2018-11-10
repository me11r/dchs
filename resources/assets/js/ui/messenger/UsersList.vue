<template>
    <div
        class="users-list">
        <div
            class="preloader"
            v-if="!isLoadedList">
            <SvgPreloader/>
        </div>
        <v-user-list-row
            v-if="isLoadedList"
            v-for="user in users"
            :user="user"
            :key="user.id"
        />
    </div>
</template>

<script>
import axios from 'axios';
import SvgPreloader from './SvgPreloader';
import VUserListRow from './VUserListRow';

const api = axios.create({
    baseURL: '/api/messenger/'
});
export default {
    name: 'UsersList',
    components: {VUserListRow, SvgPreloader},
    data: function() {
        return {
            isLoadedList: false,
            users: [],
            lastCheckTime: null
        };
    },
    methods: {
        updateUsers: function() {
            return api.get('users/list')
                .then(response => {
                    this.users = response.data.users;
                    this.isLoadedList = true;
                    this.lastCheckTime = Date.now();
                });
        }
    },
    mounted: function() {
        if (!this.isLoadedList) {
            this.updateUsers();
        }
    }
};
</script>

<style scoped lang="scss">
    @import "../../../sass/variables";
    .preloader {
        min-height: 100%;
        min-width: 100%;
        display: flex;
        justify-content: center;
        justify-items: center;
    }
    .users-list {
        height: 100%;
        min-height: 100%;
        font-size: 14px;
        min-width: 250px;
        overflow-y: scroll;
    }
</style>
