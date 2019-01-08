import axios from 'axios';
export default {
    rights: [],
    rightsList: function() {
        let rights = window.localStorage.getItem('preloaded_rights');
        if (rights !== undefined) {
            rights = JSON.parse(rights);
        } else {
            rights = [];
        }
        return rights;
    },
    hasRight: function(id) {
        let rights = this.rightsList();
        return rights.indexOf(id) !== -1;
    },
    hasAnyRight: function(ids) {
        // const ids = Array.from(arguments);
        // console.dir(arguments)

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
    }
}
