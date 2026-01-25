document.addEventListener('DOMContentLoaded', () => {
  const loadingEl = document.getElementById('profileLoading');
  const contentEl = document.getElementById('profileContent');
  const notFoundEl = document.getElementById('profileNotFound');

  const nameEl = document.getElementById('profileName');
  const emailEl = document.getElementById('profileEmail');
  const roleEl = document.getElementById('profileRole');
  const pointsEl = document.getElementById('profilePoints');
  const rankEl = document.getElementById('profileRank');
  const badgeEl = document.getElementById('profileBadge');
  const universityEl = document.getElementById('profileUniversity');

  const badgesSection = document.getElementById('profileBadgesSection');
  const badgesList = document.getElementById('profileBadgesList');
  const statusSection = document.getElementById('profileStatusSection');
  const eventsCountEl = document.getElementById('profileEventsCount');
  const blogsCountEl = document.getElementById('profileBlogsCount');
  const badgesCountEl = document.getElementById('profileBadgesCount');
  const recentEventsSection = document.getElementById('profileRecentEventsSection');
  const recentBlogsSection = document.getElementById('profileRecentBlogsSection');
  const recentEventsList = document.getElementById('profileRecentEvents');
  const recentBlogsList = document.getElementById('profileRecentBlogs');

  const userId = window.location.pathname.split('/').pop();

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
      roleEl.className = 'badge bg-danger mb-2';
    } else if (role === 'SUPERVISOR') {
      roleEl.className = 'badge bg-warning text-dark mb-2';
    } else {
      roleEl.className = 'badge bg-primary mb-2';
    }
    roleEl.textContent = role;
  };

  const renderBadges = (badges) => {
    badgesList.innerHTML = '';
    if (!badges.length) {
      badgesList.innerHTML = '<p class="text-muted text-center py-3 w-100">No badges earned yet</p>';
      return;
    }

    badges.forEach((userBadge) => {
      const badge = userBadge.badge || userBadge;
      const span = document.createElement('span');
      span.className = `badge bg-${getBadgeColor(badge.name)} p-2`;
      span.style.fontSize = '0.9rem';
      span.textContent = badge.name;
      badgesList.appendChild(span);
    });
  };

  const renderRecentList = (listEl, items, type) => {
    listEl.innerHTML = '';
    items.forEach((item) => {
      const li = document.createElement('a');
      li.className = 'list-group-item d-flex justify-content-between align-items-center';
      li.style.textDecoration = 'none';
      li.style.color = 'inherit';
      li.href = `/${type}/${type === 'events' ? item.eventId : item.blogId}`;
      li.innerHTML = `
        <span>${item.title}</span>
        <span class="badge bg-${getStatusVariant(item.status)}">${item.status}</span>
      `;
      listEl.appendChild(li);
    });
  };

  const init = async () => {
    try {
      const currentUser = await fetchJson('/api/auth/session').catch(() => null);
      const profile = await fetchJson(`/api/users/${userId}`);

      nameEl.textContent = profile.name || '';
      emailEl.textContent = profile.email || '';
      setRoleBadge(profile.role || 'STUDENT');
      pointsEl.textContent = formatPoints(profile.points || 0);
      if (profile.currentBadge) {
        badgeEl.classList.remove('d-none');
        badgeEl.className = `badge fs-6 bg-${getBadgeColor(profile.currentBadge.name)}`;
        badgeEl.textContent = `Badge: ${profile.currentBadge.name}`;
      }
      if (profile.university && profile.university.name) {
        universityEl.textContent = profile.university.name;
      }

      try {
        const rankData = await fetchJson(`/api/gamification/rank/${userId}?scope=GLOBAL`);
        if (rankData?.rank) {
          rankEl.classList.remove('d-none');
          rankEl.textContent = `Rank #${rankData.rank}`;
        }
      } catch (error) {
        // ignore
      }

      const isOwnProfile = currentUser && currentUser.userId && currentUser.userId.toString() === userId.toString();

      if (!isOwnProfile) {
        badgesSection.classList.add('d-none');
        statusSection.classList.add('d-none');
        recentEventsSection.classList.add('d-none');
        recentBlogsSection.classList.add('d-none');
      } else {
        const [badgesData, eventsData, blogsData] = await Promise.all([
          fetchJson('/api/gamification/my-badges').catch(() => ({})),
          fetchJson('/api/events/my-events').catch(() => []),
          fetchJson('/api/blogs/my-blogs').catch(() => []),
        ]);

        const earnedBadges = badgesData.earnedBadges || [];
        renderBadges(earnedBadges);
        badgesCountEl.textContent = earnedBadges.length;

        const events = Array.isArray(eventsData) ? eventsData.slice(0, 5) : [];
        const blogs = Array.isArray(blogsData) ? blogsData.slice(0, 5) : [];
        eventsCountEl.textContent = Array.isArray(eventsData) ? eventsData.length : 0;
        blogsCountEl.textContent = Array.isArray(blogsData) ? blogsData.length : 0;

        if (events.length > 0) {
          renderRecentList(recentEventsList, events, 'events');
        } else {
          recentEventsSection.classList.add('d-none');
        }

        if (blogs.length > 0) {
          renderRecentList(recentBlogsList, blogs, 'blogs');
        } else {
          recentBlogsSection.classList.add('d-none');
        }
      }

      contentEl.classList.remove('d-none');
      loadingEl.classList.add('d-none');
    } catch (error) {
      loadingEl.classList.add('d-none');
      notFoundEl.classList.remove('d-none');
    }
  };

  init();
});
