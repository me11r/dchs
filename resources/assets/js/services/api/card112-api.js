export default class Card112Api {
    constructor(axios) {
        this.axios = axios;
    }

    sendNotifications(card112Id, notificationMessage, notificationGroups) {
        return new Promise((resolve, reject) => {
            const requestBody = {
                card112Id,
                notificationMessage,
                notificationGroups
            };

            this.axios
                .post('/api/card112/send_notifications', requestBody)
                .then((response) => {
                    resolve(response);
                })
                .catch((error) => {
                    reject(error);
                });
        });
    }

    getCard(id) {
        return new Promise((resolve, reject) => {
            this.axios
                .get('/api/card112/get_card112', {params: {id}})
                .then((response) => {
                    resolve(response['data']['card112']);
                })
                .catch((error) => {
                    reject(error);
                });
        });
    }
}
