<template>
    <a v-if="canDelete_" href="#" @click.prevent="deleteEntity" class="button is-danger is-small"><i class="fas fa-eraser"></i>&nbsp; Удалить</a>
</template>

<script>
    import axios from 'axios';
    export default {
        name: "DeleteButton",
        props: {
            id: {
                type: Number,
                default: null
            },
            deleteUrl: {
                type: String,
                default: ''
            },
            redirectUrl: {
                type: String,
                default: ''
            },
        },
        data: function() {
            return {

            };
        },
        methods: {
            deleteEntity() {
                if (confirm('Удалить запись?')){
                    axios.post(this.deleteUrl, {id: this.id}).then((resp) => {
                        if (this.redirectUrl !== '') {
                            window.location.href = this.redirectUrl;
                        }
                    })
                }
            }
        }
    }
</script>

<style scoped>

</style>