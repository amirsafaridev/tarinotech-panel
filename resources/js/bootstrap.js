import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

let laravelEcho = new Echo({
    broadcaster: 'pusher',
    key: '12345678',
    wsHost: 'localhost',
    wsPort: 6001,
    forceTLS: false,
    encrypted: false,
    disableStats: true,
    //enabledTransports: ['ws'],
    cluster: 'mt1',
    authEndpoint:'/broadcasting/auth/web'
});

window.Echo = laravelEcho;