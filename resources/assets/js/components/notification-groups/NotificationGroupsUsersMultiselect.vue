<template>
    <div>
        <multiselect
            :options='users'
            v-model="localSelectedUsers"
            :multiple="true"
            :close-on-select="false"
            :clear-on-select="false"
            :hide-selected="true"
            :preserve-search="true"
            label="full_username"
            track-by="id"
            name="users"
            placeholder=""
            required
        />
        <input
            :key="'hidden_user_input_' + user.id"
            name="users[]"
            type="hidden"
            v-for="user in localSelectedUsers"
            :value="user.id">
    </div>
</template>

<script>
import Multiselect from 'vue-multiselect';

export default {
    props: {
        'selectedUsers': {
            type: Array,
            required: true,
            default: () => []
        },
        'users': {
            type: Array,
            required: true,
            default: () => {
            }
        }
    },
    components: {
        Multiselect
    },
    data() {
        return {
            localSelectedUsers: []
        };
    },
    name: 'NotificationGroupsUsersMultiselect',
    beforeMount() {
        this.localSelectedUsers = this.selectedUsers;
    }
};
</script>

<style scoped>

</style>
