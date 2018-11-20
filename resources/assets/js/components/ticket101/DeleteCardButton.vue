<template>
    <a v-if="canDelete_" href="#" @click.prevent="deleteTicket" class="button is-danger is-small"><i class="fas fa-eraser"></i>&nbsp; Удалить</a>
</template>

<script>
    import axios from 'axios';
    export default {
        name: "DeleteCardButton",
        props: {
            ticket: {
                type: Object,
                default: () => {
                    return {};
                }
            },
            canDelete: {
                type: Number,
                default: 0
            }
        },
        data: function() {
            return {
                ticket_: this.ticket,
                canDelete_: this.canDelete,
            };
        },
        methods: {
            deleteTicket() {
                if(confirm('Удалить карточку?')){
                    axios.post('/card/101/delete', {id: this.ticket_.id}).then((resp) => {
                        window.location.href = "/card/101";
                    })
                }
            }
        }
    }
</script>

<style scoped>

</style>