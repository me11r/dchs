<template>
    <div class="field">
        <p class="control">
            <label for="dept_id_selector">№ ОДС</label>
        </p>
        <div class="select">
            <select class="select"
                    name="ods_id"
                    id="dept_id_selector"
                    v-model="shiftId_">
                <option v-for="item in ods_" :value="item.id">
                &nbsp;{{ item.name }}&nbsp;
                </option>
            </select>
        </div>
    </div>
</template>

<script>
    export default {
        name: "PageSelector",
        props: {
            ods: {
                type: Array,
                default: () => {}
            },
            shiftId: {
                type: Number,
                default: 1
            },
        },
        data: function () {
            return {
                shiftId_: this.shiftId,
                ods_: this.ods,
            }
        },
        watch: {
            'shiftId_'(newValue, oldValue) {
                if (oldValue !== '' && oldValue !== newValue) {
                    let location = window.location.pathname.split('/');
                    location.pop();
                    location.push(newValue);
                    window.location = location.join('/');
                }
            }
        },
        methods: {
        }
    }
</script>

<style scoped>

</style>