<template>
    <div>
        Ну привет
    </div>
</template>

<script>
    export default {
        props: {
            streets: {
                default: [],
                type: Array
            }
        },
        name: "card112-form",
        beforeMount() {
            alert('1234');
        }
    }
</script>