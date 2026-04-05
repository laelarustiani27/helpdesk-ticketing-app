<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ env('VITE_APP_NAME', 'Laravel Realtime') }}</title>
    @vite('resources/js/app.js')
</head>
<body>
    <h1>Laravel + Pusher Realtime Test</h1>
    <div id="messages"></div>
    <div id="notifications" style="margin-top:20px; color:red;"></div>

    <script>
        const messagesDiv = document.getElementById('messages');
        const notificationsDiv = document.getElementById('notifications');

        // Setup Echo
        window.Echo.channel('test-channel')
            .listen('.TestEvent', (e) => {
                const p = document.createElement('p');
                p.textContent = "Message: " + e.message;
                messagesDiv.appendChild(p);
            })
            .subscribed(() => {
                console.log('Subscribed to channel');
            })
            .error((err) => {
                const p = document.createElement('p');
                p.textContent = "⚠ Network or connection error!";
                notificationsDiv.appendChild(p);
            });

        // Detect Pusher connection state changes
        if (window.Echo.connector.pusher) {
            window.Echo.connector.pusher.connection.bind('state_change', function(states) {
                if (states.current === 'disconnected') {
                    const p = document.createElement('p');
                    p.textContent = "⚠ Pusher disconnected! Check your network.";
                    notificationsDiv.appendChild(p);
                }
            });
        }
    </script>
</body>
</html>
