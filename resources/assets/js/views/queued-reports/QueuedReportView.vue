<template>
    <div
        class="container"
        style="padding: 20px 0 20px 0;">

        <template v-if="componentName !== null">
            <h3 class="title">Просмотр отчета "{{ this.item.report_type.name }}"</h3>
            <component
                :report-data="reportData"
                :is="componentName"/>
        </template>

        <template v-else>
            <i class="fas fa-spinner fa-pulse"></i> &nbsp; Загрузка отчета...
        </template>

    </div>
</template>

<script>
import axios from 'axios';
import {ReportType} from '../../services/queued-report-service';
import {VAnalyticsSpiasr} from './report-views';

export default {
    name: 'QueuedReportView',
    components: {
        VAnalyticsSpiasr
    },
    data() {
        return {
            reportId: null,
            item: {}
        };
    },
    computed: {
        reportData() {
            return this.item.id ? this.item.data : {};
        },
        componentsMap() {
            let result = {};

            result[ReportType.ANALYTICS_SPIASR] = 'VAnalyticsSpiasr';

            return result;
        },
        componentName() {
            return this.item.id ? this.componentsMap[this.item.report_type.slug] : null;
        }
    },
    methods: {
        loadItem() {
            axios.get('/auth-api/queued-reports/' + this.reportId)
                .then((response) => {
                    this.item = response.data;
                });
        }
    },
    beforeMount() {
        const urlParams = new URLSearchParams(window.location.search);
        this.reportId = urlParams.get('report_id');
        this.loadItem();
    }

};
</script>

<style scoped>

</style>
