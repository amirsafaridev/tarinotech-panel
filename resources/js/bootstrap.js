import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

let laravelEcho = new Echo({
    broadcaster: 'pusher',
    key: "asdfkjlj2459123128",
    wsHost: "ws.tarinotech.com",
    wsPort: 80,
    wssPort: 80,
    forceTLS: false,
    encrypted: false,
    disableStats: true,
    enabledTransports: ['ws', 'wss'],
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    authEndpoint:'/broadcasting/auth/web'
});

window.Echo = laravelEcho;