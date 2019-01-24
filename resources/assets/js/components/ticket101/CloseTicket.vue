<template>
    <div>
        <button v-show="ticket_.id" @click.prevent="closeTicket" class="button is-danger">{{ closedText }}</button>
    </div>
</template>

<script>
    import axios from 'axios';
    export default {
        name: "CloseTicket",
        props: {
            ticket: {
                type: Object,
                default() {
                    return {};
                }
            },
        },
        data() {
            return {
                ticket_: this.ticket,
            }
        },
        computed: {
            closedText(){
                return this.ticket_.closed ? 'Открыть карточку' : 'Закрыть карточку';
            },
        },
        methods: {
            closeTicket() {
                axios.post(`/card/add101/${this.ticket_.id}/switch-state`).then((resp) => {
                    this.ticket_.closed = !this.ticket_.closed;
                });
            }
        }
    }
</script>

<style scoped>

</style>