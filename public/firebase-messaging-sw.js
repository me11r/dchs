importScripts('https://www.gstatic.com/firebasejs/5.5.5/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/5.5.5/firebase-messaging.js');
const config = {
    apiKey: 'AIzaSyC8LXZezgLrPL3Az2bmUhQNjRYGf0GoNpc',
    authDomain: 'emergency-notifier-18e77.firebaseapp.com',
    databaseURL: 'https://emergency-notifier-18e77.firebaseio.com',
    projectId: 'emergency-notifier-18e77',
    storageBucket: 'emergency-notifier-18e77.appspot.com',
    messagingSenderId: '1034182934094'
};
firebase.initializeApp(config);

const messaging = firebase.messaging();

messaging.setBackgroundMessageHandler(function(payload) {
    console.log('Handling background message', payload);

    // Copy data object to get parameters in the click handler
    payload.data.data = JSON.parse(JSON.stringify(payload.data));
    payload.data.requireInteraction = true;

    return self.registration.showNotification(payload.data.title, payload.data);
});

self.addEventListener('notificationclick', function(event) {
    const target = event.notification.data.click_action || '/';
    event.notification.close();

    // This looks to see if the current is already open and focuses if it is
    event.waitUntil(clients.matchAll({
        type: 'window',
        includeUncontrolled: true
    }).then(function(clientList) {
        // clientList always is empty?!
        for (var i = 0; i < clientList.length; i++) {
            var client = clientList[i];
            if (client.url === target && 'focus' in client) {
                return client.focus();
            }
        }

        return clients.openWindow(target);
    }));
});
