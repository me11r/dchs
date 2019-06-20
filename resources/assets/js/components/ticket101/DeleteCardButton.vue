<template>
    <a v-if="canDelete_" href="#" @click.prevent="deleteTicket" class="button is-danger is-small"><i class="fas fa-eraser"></i>&nbsp; Удалить</a>
</template>

<script>
    import axios from 'axios';
    export default {
        name: "DeleteCardButton",
        props: {
            ticketId: {
                type: String | Number,
                default: 0
            },
            canDelete: {
                type: Number | Boolean,
                default: 0
            }
        },
        data: function() {
            return {
                ticketId_: this.ticketId,
                canDelete_: this.canDelete,
            };
        },
        methods: {
            deleteTicket() {
                if(confirm('Удалить карточку?')){
                    axios.post('/card/101/delete', {id: this.ticketId_}).then((resp) => {
                        window.location.href = "/card/101";
                    })
                }
            }
        }
    }
</script>

<style scoped>

</style>