document.addEventListener('DOMContentLoaded', () => {
  const list = document.getElementById('notificationsList');
  const markAllBtn = document.getElementById('notificationsMarkAll');

  const fetchJson = async (url, options = {}) => {
    const response = await fetch(url, { credentials: 'same-origin', ...options });
    const data = await response.json().catch(() => null);
    if (!response.ok) {
      const message = data?.message || 'Request failed';
      throw new Error(message);
    }
    return data;
  };

  const render = (notifications) => {
    list.innerHTML = '';
    if (notifications.length === 0) {
      list.innerHTML = '<li class="list-group-item text-center text-muted py-5">No notifications</li>';
      markAllBtn.classList.add('d-none');
      return;
    }

    if (notifications.some((n) => !n.is_read && !n.isRead)) {
      markAllBtn.classList.remove('d-none');
    } else {
      markAllBtn.classList.add('d-none');
    }

    notifications.forEach((notif) => {
      const isRead = notif.isRead ?? notif.is_read;
      const item = document.createElement('li');
      item.className = `list-group-item ${!isRead ? 'bg-light' : ''}`;
      item.innerHTML = `
        <div class="d-flex align-items-start">
          <div class="me-3 fs-4">${getNotificationIcon(notif.type)}</div>
          <div class="flex-grow-1">
            <div class="${!isRead ? 'fw-bold' : ''}">${notif.message}</div>
            <div class="text-muted small">${getTimeAgo(notif.created_at || notif.createdAt)}</div>
          </div>
          ${!isRead ? `<button class="btn btn-link btn-sm" data-action="read" data-id="${notif.id || notif.notificationId}">Mark as read</button>` : ''}
        </div>
      `;
      list.appendChild(item);
    });
  };

  const load = async () => {
    list.innerHTML = '<li class="list-group-item text-center py-5"><div class="spinner-border text-primary"></div></li>';
    try {
      const notifications = await fetchJson('/api/notifications');
      render(Array.isArray(notifications) ? notifications : []);
    } catch (error) {
      list.innerHTML = '<li class="list-group-item text-center text-muted py-5">Failed to load notifications</li>';
    }
  };

  list.addEventListener('click', async (event) => {
    const target = event.target;
    if (!(target instanceof HTMLElement)) return;
    if (target.dataset.action !== 'read') return;
    const id = target.dataset.id;
    try {
      await fetchJson(`/api/notifications/${id}/read`, {
        method: 'PUT',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').content,
        },
      });
      load();
      window.dispatchEvent(new CustomEvent('notificationRead'));
    } catch (error) {
      return;
    }
  });

  markAllBtn.addEventListener('click', async () => {
    try {
      await fetchJson('/api/notifications/read-all', {
        method: 'PUT',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').content,
        },
      });
      load();
      window.dispatchEvent(new CustomEvent('notificationRead'));
    } catch (error) {
      return;
    }
  });

  load();
});
