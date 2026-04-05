import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true
});

window.Echo.channel('test-channel')
    .listen('.TestEvent', (e) => {
        console.log('Event diterima:', e);

        alert(e.message);

        if (Notification.permission === "granted") {
            new Notification("Notifikasi Jaringan", {
                body: e.message
            });
        }
    });

if ("Notification" in window) {
    Notification.requestPermission();
}
