importScripts('https://www.gstatic.com/firebasejs/5.5.5/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/5.5.5/firebase-app.js');
const config = {
  apiKey: "AIzaSyC8LXZezgLrPL3Az2bmUhQNjRYGf0GoNpc",
  authDomain: "emergency-notifier-18e77.firebaseapp.com",
  databaseURL: "https://emergency-notifier-18e77.firebaseio.com",
  projectId: "emergency-notifier-18e77",
  storageBucket: "emergency-notifier-18e77.appspot.com",
  messagingSenderId: "1034182934094"
};
firebase.initializeApp(config);

const messaging = firebase.messaging();
