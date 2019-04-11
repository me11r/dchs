import axios from 'axios';

export const ReportType = {
    ANALYTICS_SPIASR: 'ANALYTICS_SPIASR'
};

export class QueuedReportService {
    static RegisterToQueue(dateStart, dateEnd, reportType, reportData) {
        return axios.post('/auth-api/queued-reports', {
            dateStart,
            dateEnd,
            reportType,
            reportData
        });
    }

    static UserHasNotFinishedReport() {
        return new Promise((resolve) => {
            axios.get('/auth-api/queued-reports/user-has-not-finished-report')
                .then((response) => {
                    resolve(response.data.result);
                });
        });
    }
}
