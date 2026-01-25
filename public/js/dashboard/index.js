document.addEventListener('DOMContentLoaded', () => {
  const loadingEl = document.getElementById('dashboardLoading');
  const contentEl = document.getElementById('dashboardContent');
  const welcomeEl = document.getElementById('dashboardWelcome');
  const universityEl = document.getElementById('dashboardUniversity');
  const roleBadgeEl = document.getElementById('dashboardRoleBadge');
  const pointsEl = document.getElementById('dashboardPoints');
  const badgeEl = document.getElementById('dashboardBadge');
  const eventsCountEl = document.getElementById('dashboardEventsCount');
  const blogsCountEl = document.getElementById('dashboardBlogsCount');
  const eventsCountLinkEl = document.getElementById('dashboardEventsCountLink');
  const blogsCountLinkEl = document.getElementById('dashboardBlogsCountLink');
  const eventsListEl = document.getElementById('dashboardEventsList');
  const blogsListEl = document.getElementById('dashboardBlogsList');
  const topMembersEl = document.getElementById('dashboardTopMembers');
  const notificationsEl = document.getElementById('dashboardNotifications');
  const approvalsWrap = document.getElementById('dashboardApprovals');
  const approvalsTitle = document.getElementById('dashboardApprovalsTitle');
  const pendingEventsEl = document.getElementById('dashboardPendingEvents');
  const pendingBlogsEl = document.getElementById('dashboardPendingBlogs');

  const fetchJson = async (url, options = {}) => {
    const response = await fetch(url, { credentials: 'same-origin', ...options });
    const data = await response.json().catch(() => null);
    if (!response.ok) {
      const message = data?.message || 'Request failed';
      throw new Error(message);
    }
    return data;
  };

  const setRoleBadge = (role) => {
    if (role === 'ADMIN') {
      roleBadgeEl.className = 'badge bg-danger';
      roleBadgeEl.textContent = 'Admin';
    } else if (role === 'SUPERVISOR') {
      roleBadgeEl.className = 'badge bg-warning text-dark';
      roleBadgeEl.textContent = 'Supervisor';
    } else {
      roleBadgeEl.className = 'badge bg-primary';
      roleBadgeEl.textContent = 'Student';
    }
  };

  const renderEvents = (events) => {
    eventsListEl.innerHTML = '';
    if (!events.length) {
      eventsListEl.innerHTML = `
        <div class="text-center py-4">
          <div style="font-size: 3rem; margin-bottom: 0.5rem; opacity: 0.3;">&#128197;</div>
          <p class="text-muted mb-3">No events created yet</p>
          <a href="/events/new" class="btn btn-primary btn-sm">Create Your First Event</a>
        </div>
      `;
      return;
    }

    const list = document.createElement('div');
    list.className = 'list-group list-group-flush';
    events.forEach((event) => {
      const item = document.createElement('a');
      item.href = `/events/${event.eventId}`;
      item.className = 'list-group-item list-group-item-action hover-card';
      item.style.textDecoration = 'none';
      item.innerHTML = `
        <div class="d-flex justify-content-between align-items-start">
          <div style="flex: 1;">
            <strong style="font-size: 1.05rem;">${event.title || ''}</strong>
            <div class="text-muted small mt-1">&#128205; ${event.location || ''} &#8226; ${formatDate(event.startDate)}</div>
          </div>
          <span class="badge bg-${getStatusVariant(event.status)}" style="font-size: 0.85rem; font-weight: 600; padding: 0.5rem 0.75rem;">${event.status}</span>
        </div>
      `;
      list.appendChild(item);
    });
    eventsListEl.appendChild(list);
  };

  const renderBlogs = (blogs) => {
    blogsListEl.innerHTML = '';
    if (!blogs.length) {
      blogsListEl.innerHTML = `
        <div class="text-center py-4">
          <div style="font-size: 3rem; margin-bottom: 0.5rem; opacity: 0.3;">&#128221;</div>
          <p class="text-muted mb-3">No blogs created yet</p>
          <a href="/blogs/new" class="btn btn-success btn-sm">Create Your First Blog</a>
        </div>
      `;
      return;
    }

    const list = document.createElement('div');
    list.className = 'list-group list-group-flush';
    blogs.forEach((blog) => {
      const item = document.createElement('a');
      item.href = `/blogs/${blog.blogId}`;
      item.className = 'list-group-item list-group-item-action hover-card';
      item.style.textDecoration = 'none';
      item.innerHTML = `
        <div class="d-flex justify-content-between align-items-start">
          <div style="flex: 1;">
            <strong style="font-size: 1.05rem;">${blog.title || ''}</strong>
            <div class="text-muted small mt-1">
              <span class="badge bg-info me-2">${blog.category || ''}</span>
              ${blog.isGlobal ? '<span class="badge bg-secondary">Global</span>' : ''}
            </div>
          </div>
          <span class="badge bg-${getStatusVariant(blog.status)}" style="font-size: 0.85rem; font-weight: 600; padding: 0.5rem 0.75rem;">${blog.status}</span>
        </div>
      `;
      list.appendChild(item);
    });
    blogsListEl.appendChild(list);
  };

  const renderTopMembers = (members) => {
    topMembersEl.innerHTML = '';
    if (!members.length) {
      topMembersEl.innerHTML = '<p class="text-center text-muted">No data available</p>';
      return;
    }

    members.forEach((member, index) => {
      const row = document.createElement('div');
      row.className = 'd-flex align-items-center mb-3';
      row.style.padding = '1rem';
      row.style.borderRadius = 'var(--radius-md)';
      row.style.border = '1px solid var(--border-color)';
      row.style.backgroundColor = 'var(--bg-primary)';
      row.innerHTML = `
        <div class="me-3">
          <h4 class="text-muted mb-0">#${index + 1}</h4>
        </div>
        <div class="flex-grow-1">
          <strong>${member.name}</strong>
          ${member.currentBadge ? `<div class="small"><span class="badge bg-${getBadgeColor(member.currentBadge.name)}">${member.currentBadge.name}</span></div>` : ''}
        </div>
        <div>
          <span class="badge bg-primary pill">${member.points} pts</span>
        </div>
      `;
      topMembersEl.appendChild(row);
    });
  };

  const renderNotifications = (notifications) => {
    notificationsEl.innerHTML = '';
    if (!notifications.length) {
      notificationsEl.innerHTML = '<p class="text-muted text-center">No notifications</p>';
      return;
    }

    const list = document.createElement('div');
    list.className = 'list-group list-group-flush';
    notifications.forEach((notif) => {
      const item = document.createElement('div');
      item.className = 'list-group-item';
      item.innerHTML = `
        <div class="d-flex align-items-start">
          <div class="me-2">${notif.type === 'BADGE_EARNED' ? '&#127941;' : '&#128276;'}</div>
          <div class="flex-grow-1">
            <div class="${notif.isRead || notif.is_read ? '' : 'fw-bold'}">${notif.message || ''}</div>
            <div class="text-muted small">${getTimeAgo(notif.createdAt || notif.created_at)}</div>
          </div>
        </div>
      `;
      list.appendChild(item);
    });
    notificationsEl.appendChild(list);
  };

  const loadDashboard = async () => {
    try {
      const user = await fetchJson('/api/auth/session');

      welcomeEl.textContent = `Welcome back, ${user.name}!`;
      universityEl.textContent = user.universityName || '';
      setRoleBadge(user.role);
      pointsEl.textContent = formatPoints(user.points || 0);
      badgeEl.textContent = user.currentBadgeName || 'Newcomer';
      badgeEl.className = `badge bg-${getBadgeColor(user.currentBadgeName || 'Newcomer')}`;

      const [events, blogs, topMembers, notifications] = await Promise.all([
        fetchJson('/api/events/my-events').catch(() => []),
        fetchJson('/api/blogs/my-blogs').catch(() => []),
        fetchJson('/api/gamification/top-members?scope=GLOBAL&limit=3').catch(() => []),
        fetchJson('/api/notifications').catch(() => []),
      ]);

      const sortedEvents = Array.isArray(events)
        ? events.sort((a, b) => new Date(b.createdAt) - new Date(a.createdAt)).slice(0, 3)
        : [];
      const sortedBlogs = Array.isArray(blogs)
        ? blogs.sort((a, b) => new Date(b.createdAt) - new Date(a.createdAt)).slice(0, 3)
        : [];
      const sortedNotifications = Array.isArray(notifications)
        ? notifications.sort((a, b) => new Date(b.createdAt || b.created_at) - new Date(a.createdAt || a.created_at)).slice(0, 3)
        : [];

      eventsCountEl.textContent = Array.isArray(events) ? events.length : 0;
      blogsCountEl.textContent = Array.isArray(blogs) ? blogs.length : 0;
      eventsCountLinkEl.textContent = Array.isArray(events) ? events.length : 0;
      blogsCountLinkEl.textContent = Array.isArray(blogs) ? blogs.length : 0;

      renderEvents(sortedEvents);
      renderBlogs(sortedBlogs);
      renderTopMembers(Array.isArray(topMembers) ? topMembers : []);
      renderNotifications(sortedNotifications);

      if (user.role === 'SUPERVISOR' || user.role === 'ADMIN') {
        approvalsWrap.classList.remove('d-none');
        approvalsTitle.textContent = `${user.role === 'ADMIN' ? 'Admin' : 'Supervisor'} Dashboard`;
        const [pendingEvents, pendingBlogs] = await Promise.all([
          fetchJson('/api/events?status=PENDING').catch(() => []),
          fetchJson('/api/blogs/pending').catch(() => []),
        ]);
        pendingEventsEl.textContent = Array.isArray(pendingEvents) ? pendingEvents.length : 0;
        pendingBlogsEl.textContent = Array.isArray(pendingBlogs) ? pendingBlogs.length : 0;
      }

      contentEl.classList.remove('d-none');
      loadingEl.classList.add('d-none');
    } catch (error) {
      loadingEl.innerHTML = '<div class="text-muted">Failed to load dashboard</div>';
    }
  };

  loadDashboard();
});