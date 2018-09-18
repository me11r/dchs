<template>

</template>

<script>
import axios from 'axios';

export default {
    name: 'AutoPrint',
    data: function() {
        return {
            id: 0
        };
    },

    methods: {
        send: function(data) {
            axios.post('http://localhost:13800/print/', data).then(response => {
                console.log('Repsonse', response);
            });
        }
    },
    mounted: function() {
        const url = window.location.pathname.split('/');
        this.id = parseInt(url.pop());
        axios.get('/roadtrip/print/' + this.id, {responseType: 'arraybuffer'})
            .then(response => {
                this.send(response.data);
            });
    }
};
</script>

<style scoped>

</style>
