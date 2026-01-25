document.addEventListener('DOMContentLoaded', () => {
  const alerts = document.getElementById('reportsAlerts');
  const typeFilter = document.getElementById('reportsTypeFilter');
  const statusFilter = document.getElementById('reportsStatusFilter');
  const loading = document.getElementById('reportsLoading');
  const eventCard = document.getElementById('eventReportsCard');
  const blogCard = document.getElementById('blogReportsCard');
  const eventBody = document.getElementById('eventReportsBody');
  const blogBody = document.getElementById('blogReportsBody');
  const eventCount = document.getElementById('eventReportsCount');
  const blogCount = document.getElementById('blogReportsCount');
  const empty = document.getElementById('reportsEmpty');

  const setLoading = (isLoading) => {
    loading.classList.toggle('d-none', !isLoading);
  };

  const fetchJson = async (url, options = {}) => {
    const response = await fetch(url, { credentials: 'same-origin', ...options });
    const data = await response.json().catch(() => null);
    if (!response.ok) {
      const message = data?.message || 'Request failed';
      throw new Error(message);
    }
    return data;
  };

  const resolveReporterName = (report) =>
    report?.reportedBy?.name ||
    report?.reportedByName ||
    report?.reported_by?.name ||
    report?.reportedBy?.email ||
    'Unknown';

  const resolveReportDate = (report) =>
    report?.createdAt || report?.created_at || report?.reportedAt || report?.reported_at;

  const buildStatusBadge = (status) => {
    const variant = status === 'PENDING' ? 'warning' : status === 'REVIEWED' ? 'success' : 'secondary';
    return `<span class="badge bg-${variant}">${status === 'REVIEWED' ? 'RESOLVED' : status}</span>`;
  };

  const load = async () => {
    setLoading(true);
    alerts.innerHTML = '';
    eventBody.innerHTML = '';
    blogBody.innerHTML = '';
    eventCard.classList.add('d-none');
    blogCard.classList.add('d-none');
    empty.classList.add('d-none');

    const statusValue = statusFilter.value === 'ALL' ? '' : statusFilter.value;
    const params = statusValue ? `?status=${statusValue}` : '';

    try {
      const [eventReports, blogReports] = await Promise.all([
        fetchJson(`/api/reports/events${params}`),
        fetchJson(`/api/reports/blogs${params}`),
      ]);

      const filteredEventReports = typeFilter.value === 'BLOG' ? [] : (Array.isArray(eventReports) ? eventReports : []);
      const filteredBlogReports = typeFilter.value === 'EVENT' ? [] : (Array.isArray(blogReports) ? blogReports : []);

      if (filteredEventReports.length === 0 && filteredBlogReports.length === 0) {
        empty.classList.remove('d-none');
        return;
      }

      if (filteredEventReports.length > 0) {
        eventCount.textContent = filteredEventReports.length;
        filteredEventReports.forEach((report) => {
          const eventId = report.eventId;
          const eventTitle = report.eventTitle || 'Event';
          const eventCreator = report.eventCreatorName || 'Unknown';
          const reportedByName = resolveReporterName(report);
          const createdAt = resolveReportDate(report);
          const row = document.createElement('tr');
          row.innerHTML = `
            <td>
              <a href="/events/${eventId}">${eventTitle}</a>
              <div class="text-muted small">by ${eventCreator}</div>
            </td>
            <td>${reportedByName}</td>
            <td><div style="max-width:300px;">${report.reason}</div></td>
            <td><small>${createdAt ? getTimeAgo(createdAt) : 'N/A'}</small></td>
            <td>${buildStatusBadge(report.status)}</td>
            <td>
              ${report.status === 'PENDING' ? `
                <div class="d-flex gap-2">
                  <button class="btn btn-success btn-sm" data-action="resolve-event" data-id="${report.reportId}">Resolve</button>
                  <button class="btn btn-secondary btn-sm" data-action="dismiss-event" data-id="${report.reportId}">Dismiss</button>
                </div>
              ` : ''}
            </td>
          `;
          eventBody.appendChild(row);
        });
        eventCard.classList.remove('d-none');
      }

      if (filteredBlogReports.length > 0) {
        blogCount.textContent = filteredBlogReports.length;
        filteredBlogReports.forEach((report) => {
          const blogId = report.blogId;
          const blogTitle = report.blogTitle || 'Blog';
          const blogAuthor = report.blogAuthorName || 'Unknown';
          const reportedByName = resolveReporterName(report);
          const createdAt = resolveReportDate(report);
          const row = document.createElement('tr');
          row.innerHTML = `
            <td>
              <a href="/blogs/${blogId}">${blogTitle}</a>
              <div class="text-muted small">by ${blogAuthor}</div>
            </td>
            <td>${reportedByName}</td>
            <td><div style="max-width:300px;">${report.reason}</div></td>
            <td><small>${createdAt ? getTimeAgo(createdAt) : 'N/A'}</small></td>
            <td>${buildStatusBadge(report.status)}</td>
            <td>
              ${report.status === 'PENDING' ? `
                <div class="d-flex gap-2">
                  <button class="btn btn-success btn-sm" data-action="resolve-blog" data-id="${report.reportId}">Resolve</button>
                  <button class="btn btn-secondary btn-sm" data-action="dismiss-blog" data-id="${report.reportId}">Dismiss</button>
                </div>
              ` : ''}
            </td>
          `;
          blogBody.appendChild(row);
        });
        blogCard.classList.remove('d-none');
      }
    } catch (error) {
      alerts.innerHTML = `<div class="alert alert-danger">${error.message}</div>`;
    } finally {
      setLoading(false);
    }
  };

  const handleAction = async (action, id) => {
    if (action === 'resolve-event') {
      await fetchJson(`/api/reports/events/${id}/review`, { method: 'PUT' });
    }
    if (action === 'dismiss-event') {
      await fetchJson(`/api/reports/events/${id}/dismiss`, { method: 'PUT' });
    }
    if (action === 'resolve-blog') {
      await fetchJson(`/api/reports/blogs/${id}/review`, { method: 'PUT' });
    }
    if (action === 'dismiss-blog') {
      await fetchJson(`/api/reports/blogs/${id}/dismiss`, { method: 'PUT' });
    }
  };

  const tableClickHandler = async (event) => {
    const target = event.target;
    if (!(target instanceof HTMLElement)) return;
    const action = target.dataset.action;
    const id = target.dataset.id;
    if (!action || !id) return;
    try {
      await handleAction(action, id);
      alerts.innerHTML = '<div class="alert alert-success">Report updated successfully</div>';
      load();
    } catch (error) {
      alerts.innerHTML = `<div class="alert alert-danger">${error.message}</div>`;
    }
  };

  eventBody.addEventListener('click', tableClickHandler);
  blogBody.addEventListener('click', tableClickHandler);

  typeFilter.addEventListener('change', load);
  statusFilter.addEventListener('change', load);

  load();
});
