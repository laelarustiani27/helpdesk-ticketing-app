{{-- 
    Notification Component
    File: resources/views/components/notification.blade.php
    
    Usage: @include('components.notification')
--}}

<div class="notification-wrapper">
    <button class="notification-btn" onclick="toggleNotifications()">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
        </svg>
        <span class="notification-badge hidden" id="notificationBadge">0</span>
    </button>

    <div class="notification-dropdown" id="notificationDropdown">
        <div class="notification-header">
            <h3>Notifikasi</h3>
            <button onclick="markAllAsRead()" class="mark-all-read">Tandai semua dibaca</button>
        </div>
        
        <div class="notification-list" id="notificationList">
            <div class="notification-loading">
                <p>Memuat notifikasi...</p>
            </div>
        </div>
    </div>
</div>

<style>
/* Notification Styles */
.notification-wrapper {
    position: relative;
    display: inline-block;
}

.notification-btn {
    position: relative;
    background: transparent;
    border: none;
    color: #e2e8f0;
    cursor: pointer;
    padding: 8px;
    border-radius: 8px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.notification-btn:hover {
    background: rgba(148, 163, 184, 0.1);
    color: #667eea;
}

.notification-btn svg {
    width: 24px;
    height: 24px;
}

.notification-badge {
    position: absolute;
    top: 4px;
    right: 4px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 10px;
    font-size: 11px;
    font-weight: 600;
    min-width: 18px;
    height: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 4px;
    border: 2px solid #1e293b;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.4);
}

.notification-badge.hidden {
    display: none;
}

.notification-dropdown {
    position: absolute;
    top: calc(100% + 10px);
    right: 0;
    background: #1e293b;
    border: 1px solid rgba(148, 163, 184, 0.2);
    border-radius: 12px;
    width: 380px;
    max-height: 500px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
    display: none;
    z-index: 1000;
    overflow: hidden;
}

.notification-dropdown.show {
    display: block;
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.notification-header {
    padding: 16px 20px;
    border-bottom: 1px solid rgba(148, 163, 184, 0.2);
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: rgba(30, 41, 59, 0.8);
}

.notification-header h3 {
    color: #e2e8f0;
    font-size: 16px;
    font-weight: 600;
    margin: 0;
}

.mark-all-read {
    background: transparent;
    border: none;
    color: #667eea;
    font-size: 12px;
    cursor: pointer;
    padding: 4px 8px;
    border-radius: 4px;
    transition: all 0.2s;
}

.mark-all-read:hover {
    background: rgba(102, 126, 234, 0.1);
    color: #5568d3;
}

.notification-list {
    max-height: 400px;
    overflow-y: auto;
}

.notification-list::-webkit-scrollbar {
    width: 6px;
}

.notification-list::-webkit-scrollbar-track {
    background: rgba(30, 41, 59, 0.5);
}

.notification-list::-webkit-scrollbar-thumb {
    background: rgba(148, 163, 184, 0.3);
    border-radius: 3px;
}

.notification-list::-webkit-scrollbar-thumb:hover {
    background: rgba(148, 163, 184, 0.5);
}

.notification-item {
    padding: 16px 20px;
    border-bottom: 1px solid rgba(148, 163, 184, 0.1);
    cursor: pointer;
    transition: background 0.2s;
    position: relative;
}

.notification-item:last-child {
    border-bottom: none;
}

.notification-item:hover {
    background: rgba(51, 65, 85, 0.3);
}

.notification-item.unread {
    background: rgba(102, 126, 234, 0.05);
}

.notification-item.unread::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 4px;
    height: 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 0 4px 4px 0;
}

.notification-title {
    color: #e2e8f0;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 4px;
}

.notification-message {
    color: #94a3b8;
    font-size: 13px;
    margin-bottom: 6px;
    line-height: 1.4;
}

.notification-time {
    color: #64748b;
    font-size: 11px;
}

.notification-loading,
.notification-empty {
    padding: 40px 20px;
    text-align: center;
    color: #64748b;
    font-size: 14px;
}

.notification-loading p,
.notification-empty p {
    margin: 0;
}

.notification-loading::before {
    content: '';
    display: block;
    width: 30px;
    height: 30px;
    margin: 0 auto 16px;
    border: 3px solid rgba(102, 126, 234, 0.1);
    border-top-color: #667eea;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.notification-empty::before {
    content: '🔔';
    display: block;
    font-size: 48px;
    margin-bottom: 12px;
    opacity: 0.5;
}

@media (max-width: 480px) {
    .notification-dropdown {
        width: 320px;
        right: -50px;
    }
}

@media (max-width: 360px) {
    .notification-dropdown {
        width: 280px;
        right: -80px;
    }
}
</style>

<script>
// Toggle notification dropdown
function toggleNotifications() {
    const dropdown = document.getElementById('notificationDropdown');
    dropdown.classList.toggle('show');
    
    if (dropdown.classList.contains('show')) {
        loadNotifications();
    }
}

// Load notifications from server
function loadNotifications() {
    const listContainer = document.getElementById('notificationList');
    
    listContainer.innerHTML = `
        <div class="notification-loading">
            <p>Memuat notifikasi...</p>
        </div>
    `;
    
    fetch('/notifications/list')
        .then(response => response.json())
        .then(data => {
            if (data.notifications && data.notifications.length > 0) {
                displayNotifications(data.notifications);
            } else {
                listContainer.innerHTML = `
                    <div class="notification-empty">
                        <p>Tidak ada notifikasi baru</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading notifications:', error);
            listContainer.innerHTML = `
                <div class="notification-empty">
                    <p>Gagal memuat notifikasi</p>
                </div>
            `;
        });
}

// Display notifications in the list
function displayNotifications(notifications) {
    const listContainer = document.getElementById('notificationList');
    
    const html = notifications.map(notification => {
        const unreadClass = notification.read_at === null ? 'unread' : '';
        const timeAgo = formatTimeAgo(notification.created_at);
        
        return `
            <div class="notification-item ${unreadClass}" 
                onclick="handleNotificationClick('${notification.id}')">
                <div class="notification-title">${escapeHtml(notification.title)}</div>
                <div class="notification-message">${escapeHtml(notification.message)}</div>
                <div class="notification-time">${timeAgo}</div>
            </div>
        `;
    }).join('');
    
    listContainer.innerHTML = html;
}

// Handle notification click
function handleNotificationClick(id) {
    markAsRead(id);
}

// Update notification count badge
function updateNotificationCount() {
    fetch('/notifications/unread-count')
        .then(response => response.json())
        .then(data => {
            const badge = document.getElementById('notificationBadge');
            if (data.count > 0) {
                badge.textContent = data.count > 99 ? '99+' : data.count;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        })
        .catch(error => {
            console.error('Error updating notification count:', error);
        });
}

// Mark all notifications as read
function markAllAsRead() {
    fetch('/notifications/read-all', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': getCsrfToken(),
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateNotificationCount();
            loadNotifications();
        }
    })
    .catch(error => {
        console.error('Error marking all as read:', error);
    });
}

// Mark single notification as read
function markAsRead(id) {
    fetch(`/notifications/${id}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': getCsrfToken(),
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateNotificationCount();
        }
    })
    .catch(error => {
        console.error('Error marking as read:', error);
    });
}

// Get CSRF token
function getCsrfToken() {
    const token = document.querySelector('meta[name="csrf-token"]');
    return token ? token.getAttribute('content') : '';
}

// Format time ago in Indonesian
function formatTimeAgo(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const seconds = Math.floor((now - date) / 1000);
    
    const intervals = {
        tahun: 31536000,
        bulan: 2592000,
        minggu: 604800,
        hari: 86400,
        jam: 3600,
        menit: 60,
        detik: 1
    };
    
    for (const [name, value] of Object.entries(intervals)) {
        const interval = Math.floor(seconds / value);
        if (interval >= 1) {
            return `${interval} ${name} yang lalu`;
        }
    }
    
    return 'Baru saja';
}

// Escape HTML to prevent XSS
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const wrapper = event.target.closest('.notification-wrapper');
    const dropdown = document.getElementById('notificationDropdown');
    
    if (!wrapper && dropdown) {
        dropdown.classList.remove('show');
    }
});

// Prevent dropdown from closing when clicking inside
document.addEventListener('DOMContentLoaded', function() {
    const dropdown = document.getElementById('notificationDropdown');
    if (dropdown) {
        dropdown.addEventListener('click', function(event) {
            event.stopPropagation();
        });
    }
    
    updateNotificationCount();
    setInterval(updateNotificationCount, 30000);
});

// Handle escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const dropdown = document.getElementById('notificationDropdown');
        if (dropdown && dropdown.classList.contains('show')) {
            dropdown.classList.remove('show');
        }
    }
});
</script>