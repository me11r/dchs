export default class Ticket101Api {
    constructor(axios) {
        this.axios = axios;
    }

    sendNotifications(ticket101Id, notificationMessage, notificationGroups) {
        return new Promise((resolve, reject) => {
            const requestBody = {
                ticket101Id,
                notificationMessage,
                notificationGroups
            };

            this.axios
                .post('/api/101card/send_notifications', requestBody)
                .then((response) => {
                    resolve(response);
                })
                .catch((error) => {
                    reject(error);
                });
        });
    }

    getTicket(id) {
        return new Promise((resolve, reject) => {
            this.axios
                .get('/api/101card/get_ticket101', {params: {id}})
                .then((response) => {
                    resolve(response['data']['ticket101']);
                })
                .catch((error) => {
                    reject(error);
                });
        });
    }
}
