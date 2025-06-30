import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import Echo from 'laravel-echo';
window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY || process.env.VITE_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER || process.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true
});

// Real-time notification badge update
document.addEventListener('DOMContentLoaded', function () {
    const notificationBadge = document.getElementById('notification-badge');
    const notificationIcon = document.getElementById('notification-icon');

    if (!notificationBadge || !notificationIcon) return;

    function updateBadge(count) {
        if (count > 0) {
            notificationBadge.classList.remove('hidden');
            notificationBadge.textContent = count > 9 ? '9+' : count;
        } else {
            notificationBadge.classList.add('hidden');
            notificationBadge.textContent = '';
        }
    }

    // Initial fetch of unread notifications count
    axios.get('/api/notifications/unread-count')
        .then(response => {
            updateBadge(response.data.count);
        })
        .catch(() => {
            updateBadge(0);
        });

    // Listen for new notifications via Echo
    window.Echo.private(`App.Models.User.${Laravel.userId}`)
        .notification((notification) => {
            // Increment badge count on new notification
            let currentCount = parseInt(notificationBadge.textContent) || 0;
            updateBadge(currentCount + 1);
        });

    // Optionally, clear badge on clicking notification icon
    notificationIcon.addEventListener('click', () => {
        updateBadge(0);
        // Optionally, mark notifications as read via API call
        axios.post('/api/notifications/mark-read').catch(() => {});
    });
});
