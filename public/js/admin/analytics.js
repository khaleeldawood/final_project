document.addEventListener('DOMContentLoaded', () => {
  const loading = document.getElementById('adminAnalyticsLoading');
  const content = document.getElementById('adminAnalyticsContent');
  const usersEl = document.getElementById('analyticsUsers');
  const eventsEl = document.getElementById('analyticsEvents');
  const blogsEl = document.getElementById('analyticsBlogs');
  const universitiesEl = document.getElementById('analyticsUniversities');

  const setLoading = (isLoading) => {
    loading.classList.toggle('d-none', !isLoading);
  };

  const load = async () => {
    setLoading(true);
    content.classList.add('d-none');
    try {
      const response = await fetch('/api/admin/analytics', { credentials: 'same-origin' });
      const analytics = await response.json();
      usersEl.textContent = analytics?.totalUsers || 0;
      eventsEl.textContent = analytics?.totalEvents || 0;
      blogsEl.textContent = analytics?.totalBlogs || 0;
      universitiesEl.textContent = analytics?.totalUniversities || 0;

      const roles = analytics?.usersByRole || {};
      const resolvedRoles = {
        students: roles.students ?? roles.STUDENT ?? 0,
        supervisors: roles.supervisors ?? roles.SUPERVISOR ?? 0,
        admins: roles.admins ?? roles.ADMIN ?? 0,
      };
      const max = Math.max(resolvedRoles.students, resolvedRoles.supervisors, resolvedRoles.admins, 1);
      document.querySelectorAll('.analytics-bar').forEach((bar) => {
        const key = bar.dataset.role;
        const value = resolvedRoles[key] || 0;
        const height = Math.max(Math.round((value / max) * 220), 20);
        bar.style.height = `${height}px`;
        bar.title = `${value}`;
      });

      content.classList.remove('d-none');
    } catch (error) {
      content.classList.remove('d-none');
    } finally {
      setLoading(false);
    }
  };

  load();
});
