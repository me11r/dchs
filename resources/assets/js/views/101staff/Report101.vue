<template>
    <div class="container">
        <div class="panel">
            <div class="box" style="margin-top: 20px">
                <h4 class="title">Отчет по строевой записке ЛС</h4>
                <form>
                    <div class="field">
                        <label for="staff">Ф.И.О.</label>
                        <select @change="selectName" class="select" name="" id="staff">
                            <option
                                    v-model="person.id"
                                    v-for="person in staff_"
                                    :key="person.id">{{ person.name }}
                            </option>
                        </select>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                    <!--<div class="buttons has-text-right is-grouped is-right" style="">-->
                        <!--<button type="submit" class="button is-success"><i class="fas fa-check"></i>&nbsp;Сохранить</button>-->
                    <!--</div>-->
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Report101",
        props: {
            staff: {
                type: Array,
                default: [],
            },
        },
        data: function () {
            return {
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                staff_: this.staff,
                person_id: '',
            }
        },
        methods: {
            selectName(event) {
                console.dir(event);
            }
        },
    }
</script>

<style scoped>

</style>