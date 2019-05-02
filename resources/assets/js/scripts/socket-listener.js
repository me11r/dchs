import Echo from 'laravel-echo';

window.io = require('socket.io-client');

class SocketListener {
    getEcho() {
        return new Promise((resolve) => {
            if (!window.Echo) {
                const token = document.head.querySelector('meta[name="csrf-token"]');

                window.Echo = new Echo({
                    broadcaster: 'socket.io',
                    host: window.location.hostname + ':6001',
                    auth: {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': token
                        }
                    }
                });
                resolve(window.Echo);
            } else {
                resolve(window.Echo);
            }
        });
    }

    defineDefaultListeners() {
        this.getEcho().then((LaravelEcho) => {
            LaravelEcho.channel('Reports').listen('ReportUpdated', (e) => {
                console.log('report updated on server');
            });
        });
    }

    privateUserChannel() {
        return new Promise((resolve) => {
            this.getEcho().then((LaravelEcho) => {
                resolve(LaravelEcho.private(`App.User.${window.user_id}`));
            });
        });
    }
}

export default new SocketListener();
