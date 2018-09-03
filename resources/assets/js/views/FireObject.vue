<template>
    <div class="field">
        <p class="control">
            <label for="fire_object_id">Что горит</label>
        </p>
        <div class="select" style="width:50%; padding-right: 6px;">
            <select
                    v-model="fire_object_id"
                    @building_found="onBuildingFound"
                    name="fire_object_id"
                    id="fire_object_id"
                    style="width: 100%">
                <option value="">-</option>
                <option
                        v-for="fire_object in fire_objects_"
                        :key="'fire_object_' + fire_object.id"
                        :value="fire_object.id">{{ fire_object.name }}
                </option>
                <!--{% for object in fire_object %}-->
                <!--<option value="{{ object.id }}"-->
                        <!--{% if object.id == ticket.fire_object_id %}selected{% endif %}>{{ object.name }}</option>-->
                <!--{% endfor %}-->
            </select>
        </div>
    </div>
</template>

<script>
    export default {
        name: "FireObject",
        props: [
            'fire_objects',
            'ticket',
        ],
        data: function () {
            return {
                fire_objects_: this.fire_objects,
                ticket_: this.ticket,
                fire_object_id: this.ticket.fire_object_id,
            }
        },
        methods: {
            onBuildingFound: function (fireObjectId) {
                this.fire_object_id = fireObjectId;
            }
        }
    }
</script>

<style scoped>

</style>