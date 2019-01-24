import axios from 'axios';
import _ from 'lodash';
export default {
    rights: [],
    messengerPermissions: [],
    rightsList: function() {
        let rights = window.localStorage.getItem('preloaded_rights');
        if (rights !== undefined) {
            rights = JSON.parse(rights);
        } else {
            rights = [];
        }
        return rights;
    },
    permissionsList: function() {
        let rights = window.localStorage.getItem('messengerPermissions');
        if (rights !== undefined) {
            rights = JSON.parse(rights);
        } else {
            rights = [];
        }
        return rights;
    },
    canSendMessage: function (receiverId) {
        if (_.find(this.permissionsList(), {'can_send_id': receiverId})) {
            return true;
        }

        return false;
    },
    hasRight: function(id) {
        let rights = this.rightsList();
        return rights.indexOf(id) !== -1;
    },
    hasAnyRight: function(ids) {

        let hasRight = false;

        ids.forEach((value, index) => {
            if (this.hasRight(value)) {
                hasRight = true;
            }
        });

        return hasRight;
    },
    getRights() {
        return new Promise((resolve) => {
            axios.get('/ajax/rights/list').then((response) => {
                this.setRights(response.data);
                resolve(response.data);
            });
        });
    },
    setRights(rights) {
        this.rights = rights;
        window.localStorage.setItem('preloaded_rights', JSON.stringify(rights));
    },
    setMessengerPermissions(permissions) {
        this.messengerPermissions = permissions;
        window.localStorage.setItem('messengerPermissions', JSON.stringify(permissions));
    },
    getMessengerPermissions() {
        return new Promise((resolve) => {
            axios.get('/ajax/messenger-rights').then((response) => {
                this.setMessengerPermissions(response.data);
                resolve(response.data);
            });
        });
    }
};
