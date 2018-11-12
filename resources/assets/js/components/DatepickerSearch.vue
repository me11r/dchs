<template>
    <div>
        <form
            id="date-form"
            action="formAction_">
            <b-field label="Выберите дату">
                <b-datepicker
                    placeholder="Click to select..."
                    icon="calendar-today"/>
            </b-field>
        </form>
    </div>

</template>

<script>
export default {
    name: 'DatepickerSearch',
    props: {
        formAction: {
            type: String,
            default: ''
        }
    },
    data: function () {
        return {
            formAction_: this.formAction
        };
    },
    methods: {
        changeDate() {
            alert();
        }
    }
};
</script>

<style scoped>

</style>
