<template>
    <div>

        <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
            <thead>
                <tr>
                    <th>Роль</th>
                    <th>Пользователи</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="role in roles_">
                    <td>{{ role.title }}</td>
                    <td>
                        <b-collapse
                            v-for="user in role.users"
                            :open="false"
                            :key="`user_block`+user.id"
                        >

                            <a class="button is-primary" slot="trigger">{{ user.name }}</a>
                            <div class="notification">
                                <div class="content">
                                    <!--todo отключил, т.к. "вешает" страницу-->
                                    <!--<p>-->
                                        <!--<b-checkbox-->
                                            <!--@input="switchAll(user.id)"-->
                                            <!--v-model="countSelected[user.id]"-->
                                        <!--&gt;-->
                                            <!--<b>Отметить всех</b>-->
                                        <!--</b-checkbox>-->
                                    <!--</p>-->
                                    <!--<p v-for="u in getUsers(user.id)">-->
                                    <p v-for="u in userPermissions[user.id]">
                                        <b-checkbox
                                                true-value="1"
                                                false-value="0"
                                                :name="`permissions[${role.name}][${user.id}][${u.user.id}]`"
                                                v-model="u.can_send_id"
                                                :key="`user_checkbox`+u.id"
                                        >{{ u.user.name }} ({{ u.user.role.title }})
                                        </b-checkbox>
                                    </p>
                                </div>
                            </div>
                        </b-collapse>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>
</template>

<script>
    import _ from 'lodash';
    export default {
        name: "MessengerPermissions",
        props: {
            roles: {
                type: Array,
                default: () => { return []; }
            },
            users: {
                type: Array,
                default: () => { return []; }
            },

        },
        data: function () {
            return {
                roles_: this.roles,
                users_: this.users,
                userPermissions: []
            };
        },
        methods: {
            switchAll(userId) {
                let total = this.userPermissions[userId].length;
                let selectedFalse = _.filter(this.userPermissions[userId], {can_send_id: 0}).length;
                let selectedTrue = _.filter(this.userPermissions[userId], {can_send_id: 1}).length;

                if (total === selectedFalse) {
                    this.userPermissions[userId].forEach((user) => {
                        user.can_send_id = 1;
                    });
                } else if (total === selectedTrue) {
                    this.userPermissions[userId].forEach((user) => {
                        user.can_send_id = 0;
                    });
                } else {
                    this.userPermissions[userId].forEach((user) => {
                        user.can_send_id = 1;
                    });
                }
            },
            getUsers(userId) {
                let result = [];
                this.users_.forEach((u) => {
                    if (u.id !== userId) {
                        let hasRight = _.find(u.messenger_rights_reverse, {user_id: userId, can_send_id: u.id});

                        if (hasRight !== null && hasRight !== undefined) {
                            result.push({
                                user_id: hasRight.user_id,
                                can_send_id: 1,
                                user: u,
                            });
                        } else {
                            result.push({
                                user_id: u.id,
                                can_send_id: 0,
                                user: u,
                            });
                        }
                    }
                });

                return result;
            },
        },
        computed: {
            total () {
                let result = [];
                this.users_.forEach((user) => {
                    result[user.id] = this.userPermissions[user.id].length;
                });
                return result;
            },
            selected () {
                let result = [];
                this.users_.forEach((user) => {
                    result[user.id] = _.filter(this.userPermissions[user.id], {can_send_id: 1}).length;
                });
                return result;
            },
            userPermissionsComp() {
                let result = [];
                this.users_.forEach((user) => {
                    result[user.id] = this.getUsers(user.id);
                });
                return result;
            },
            countSelected() {
                let result = [];
                this.users_.forEach((user) => {
                    let total = this.total[user.id];
                    let selectedTrue = this.selected[user.id];

                    result[user.id] = selectedTrue === total;
                });
                console.dir(result)
                return result;
            }
        },
        created() {
            this.userPermissions = this.userPermissionsComp;
        }
    }
</script>

<style scoped>

</style>