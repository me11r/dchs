import _ from 'lodash';

export const CommonListMixin = {
    data() {
        return {
            items: [],
            perPage: 20,
            currentPage: 1,
            totalPages: 1,
            order: '',
            requestParams: {}
        };
    },
    methods: {
        loadItems: _.debounce(function() {
            // @TODO implement in the place
        }, 500),
        changePage(newValue) {
            this.currentPage = newValue;
            this.loadItems();
        }
    },
    mounted() {
        this.loadItems();
    }
};
