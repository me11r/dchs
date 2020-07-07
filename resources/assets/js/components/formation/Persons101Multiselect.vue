<template>
    <div>
        <span
            :class="titleClass"
            class="name-span"
            @click="activateTrigger()">{{ name }}:</span>
        <template v-if="activated">
            <multiselect
                v-model="$parent.selectedStaff[rank]"
                :options="del"
                :multiple="true"
                :close-on-select="false"
                :clear-on-select="false"
                :hide-selected="true"
                :preserve-search="true"
                placeholder=""
                label="name"
                track-by="unique"
                required
            />
        </template>
        <template v-else>
            <span v-for="(item, index) in staffToShow" :key="rank+item.id+'_block'">
                <a v-if="machineBlock" @click.prevent="staffEdit(item.id)">{{ item.name }}</a>
                <span v-if="!machineBlock">{{ item.name }}</span>

                <input style="width: inherit"
                       v-model="item.gsm_count"
                       class="input is-small"
                       type="text"
                       :key="rank+item.id+'_input'"
                       v-if="checkEdit(item.id)">

                <span v-if="!checkEdit(item.id)"> {{ item.gsm_count }}</span>
                <span v-if="((index + 1) !== staffToShow.length)">, </span>
            </span>
        </template>
    </div>
</template>

<script>

import Multiselect from 'vue-multiselect';
import _ from 'lodash';
import axios from 'axios';

export default {
    name: 'Persons101Multiselect',
    props: {
        name: {
            'type': String,
            'default': ''
        },
        machineBlock: {
            'type': Boolean,
            'default': false
        },
        rank: {
            'type': String,
            'default': ''
        },
        inactiveType: {
            'type': String,
            'default': ''
        },
        titleClass: {
            'type': String | Object | Array,
            'default': ''
        }
    },
    computed: {
        staffToShow() {
            const data = [];
            _.each(this.$parent.selectedStaff[this.rank], (item) => {
                data.push(item);
            });

            return data;
        },

        tableName() {
            const tulparTypes = [
                'tulpar1',
                'tulpar2',
                'tulpar3',
                'tulpar4',
                'tulpar5',
                'tulpar6',
                'tulpar7',
                'tulpar8',
                'tulpar9',
                'tulpar10'
            ];

            const zhalynTypes = [
                'ipl_zhalyn'
            ];

            const cppsTypes = [
                'cpps_sick',
                'cpps_vacation',
            ];

            const dsptTypes = [
                'dspt_vacation',
                'dspt_business_trip',
                'dspt_sick'
            ];

            const iplTypes = [
                'ipl_vacation',
                'ipl_other',
                'ipl_study',
                'ipl_maternity',
                'ipl_business_trip',
                'ipl_sick'
            ];

            const kshmTypes = [
                'kshm_vacation',
                'kshm_other',
                'kshm_study',
                'kshm_maternity',
                'kshm_business_trip',
                'kshm_sick'
            ];

            if (tulparTypes.indexOf(this.rank) !== -1) {
                return 'duty_vehicle';
            } else if (zhalynTypes.indexOf(this.rank) !== -1) {
                return 'zhalin';
            } else if (dsptTypes.indexOf(this.rank) !== -1) {
                return 'dspt';
            } else if (iplTypes.indexOf(this.rank) !== -1) {
                return 'ipl';
            } else if (kshmTypes.indexOf(this.rank) !== -1) {
                return 'kshm';
            } else if (cppsTypes.indexOf(this.rank) !== -1) {
                return 'cpps';
            } else {
                return this.rank;
            }
        },
        options() {
            return this.$parent.odStaff && this.$parent.odStaff[this.tableName] ? this.$parent.odStaff[this.tableName] : [];
        },
        del(){
            const data = [];
            _.each(this.$parent.odStaff[this.tableName], (item) => {
              if (item['deleted_at'] == null){
                    data.push(item);
                }
            });        
          return data ? data : [];
        }
    },
    data() {
        return {
            activated: false,
            activatedInput: [],
            inactiveType_: this.inactiveType
        };
    },
    methods: {
        activateTrigger() {
            if (window.canEditOd === false || window.approved === true) {
                return;
            }

            if (this.activated) {
                this.activated = false;
                this.save();
            } else {
                this.activated = true;
            }
        },
        checkEdit(id) {
            let record = _.find(this.activatedInput, {id: id});
            if (record) {
                return !record.edit;
            }
            return false;
        },
        staffEdit(id) {
            let record = _.find(this.activatedInput, {id: id});
            if (record) {
                record.edit = !record.edit;
                if (record.edit) {
                    this.save();
                }
                return record.edit;
            } else {
                this.activatedInput.push({id: id, edit: true});
            }
            return true;
        },
        save() {
            axios.post('/api/101/sync-formation-od-persons', {
                formId: this.$parent.formId,
                inactiveType: this.inactiveType_,
                type: this.rank,
                gsm_count: this.rank,
                selectedStaff: this.$parent.selectedStaff[this.rank],
                tableName: this.tableName
            });
        }
    },
    watch: {
        /*'this.$parent.selectedStaff' (newValue, oldValue) {
            _.each(newValue, (value, key) => {
                if (oldValue[key]) {
                    this.activatedInput = _.reject(this.activatedInput, function(staffId){ return staffId.id === oldValue.id; });
                    this.activatedInput.push(newValue);
                }
            });
            _.each(oldValue, (value, key) => {
                if (!newValue[key]) {
                    this.activatedInput = _.reject(this.activatedInput, function(staffId){ return staffId.id === oldValue.id; });
                }
            });
        }*/
    },
    created () {
        _.each(this.$parent.selectedStaff[this.rank], (item) => {
            this.activatedInput.push({id: item.id, edit: true});
        });
    },
    components: {
        Multiselect
    }
};
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style scoped>
    .name-span {
        cursor: pointer;
        padding: 0 20px 0 0;
    }

    .name-span:hover {
        color: #15A4FA;
    }
</style>
