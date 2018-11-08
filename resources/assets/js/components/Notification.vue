<template>
    
</template>

<script>
    export default {
        name: "Notification",
        props: {
            position: {
                type: String,
                default: 'is-top'
            },
            message: {
                type: String,
                default: ''
            },
            type: {
                type: String,
                default: 'is-info'
            },
            duration: {
                type: Number,
                default: 10000
            },
        },
        data() {
            return {
                position_: this.position,
                message_: this.message,
                type_: this.type,
                duration_: this.duration,
            }
        },
        methods: {
            makeAlert(){
                setTimeout(10)
                this.$snackbar.open({
                    message: this.message_,
                    position: this.position_,
                    type: this.type_,
                    duration: this.duration_,
                });
            }
        },
        mounted(){
            setTimeout(this.makeAlert, 15000);
            // this.makeAlert();
        }
    }
</script>

<style scoped>

</style>