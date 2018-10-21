import axios from 'axios';
// Initialize Firebase
var config = {
  apiKey: 'AIzaSyC8LXZezgLrPL3Az2bmUhQNjRYGf0GoNpc',
  authDomain: 'emergency-notifier-18e77.firebaseapp.com',
  databaseURL: 'https://emergency-notifier-18e77.firebaseio.com',
  projectId: 'emergency-notifier-18e77',
  storageBucket: 'emergency-notifier-18e77.appspot.com',
  messagingSenderId: '1034182934094',
};
firebase.initializeApp(config);
const messaging = firebase.messaging();

messaging.usePublicVapidKey('BKd3vbaxjXg-KkgyT8VFiSBBMW1cEEZQ8NYPKZJFE3hqbMGXvSmvthQk9kgnQ-SUXX8J_rGO5IybhAYrz2nJkMY');
document.addEventListener('DOMContentLoaded', (e) => {
  if (window.Notification !== undefined) {
    Notification.requestPermission().then(() => {
        messaging.getToken().then((token) => {
          console.log('Token: '+ token);
          axios.post('/ajax/roadrip-notify-token', {token: token});
        });
    }, () => {
      window.alert('Вы отказались от получения уведомлений!');
    });
  } else {
    window.alert('Ваш браузер не поддерживает уведомления. Уведомление о путевых листах отключено');
  }
});
