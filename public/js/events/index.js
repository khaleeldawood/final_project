document.addEventListener('DOMContentLoaded', () => {
  const list = document.getElementById('eventsList');
  const loading = document.getElementById('eventsLoading');
  const empty = document.getElementById('eventsEmpty');
  const statusWrap = document.getElementById('eventsStatusFilterWrap');
  const statusSelect = document.getElementById('eventsStatusFilter');
  const universitySelect = document.getElementById('eventsUniversityFilter');
  const timeSelect = document.getElementById('eventsTimeFilter');
  const typeSelect = document.getElementById('eventsTypeFilter');
  const searchInput = document.getElementById('eventsSearchFilter');

  const storedUser = (() => {
    try {
      return JSON.parse(localStorage.getItem('user'));
    } catch (error) {
      return null;
    }
  })();

  if (storedUser && (storedUser.role === 'ADMIN' || storedUser.role === 'SUPERVISOR')) {
    statusWrap.hidden = false;
  } else {
    statusWrap.hidden = true;
    statusSelect.value = 'APPROVED';
  }

  const setLoading = (isLoading) => {
    loading.classList.toggle('d-none', !isLoading);
  };

  const loadUniversities = async () => {
    try {
      const response = await fetch('/api/admin/universities', { credentials: 'same-origin' });
      const data = await response.json();
      const universities = Array.isArray(data) ? data.filter((uni) => uni.name !== 'Example University') : [];
      universitySelect.innerHTML = '<option value="">All Universities</option>';
      universities.forEach((uni) => {
        const option = document.createElement('option');
        option.value = uni.universityId || uni.id || '';
        option.textContent = uni.name;
        universitySelect.appendChild(option);
      });
    } catch (error) {
      universitySelect.innerHTML = '<option value="">All Universities</option>';
    }
  };

  const buildCard = (event) => {
    const col = document.createElement('div');
    col.className = 'col-12 col-sm-6 col-lg-4';

    const reportBadge = event.reportCount > 0
      ? `<div style="position:absolute;bottom:5px;right:5px;width:28px;height:28px;background-color:#dc3545;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:0.875rem;font-weight:700;z-index:10;box-shadow:0 2px 4px rgba(0,0,0,0.2)">${event.reportCount}</div>`
      : '';

    col.innerHTML = `
      <div class="card event h-100" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border: 1px solid var(--border-color); position: relative; overflow: hidden;">
        ${reportBadge}
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start mb-2 flex-wrap gap-1">
            <span class="badge bg-${getStatusVariant(event.status)}" style="font-size:0.85rem;font-weight:600;">${event.status}</span>
            <span class="badge bg-secondary">${event.type || ''}</span>
            ${new Date(event.endDate) < new Date() ? '<span class="badge bg-dark" style="font-size:0.85rem;font-weight:600;">Event Completed</span>' : ''}
          </div>
          <div class="card-title" style="font-size:1.25rem;font-weight:600;margin-bottom:0.75rem;">${event.title || ''}</div>
          <div class="card-text" style="color:var(--text-secondary);line-height:1.6;">${truncateText(event.description || '', 100)}</div>
          <div class="mb-3 mt-3">
            <div class="text-muted small">ñ??® <strong>${event.university?.name || 'N/A'}</strong></div>
            <div class="text-muted small">ñ??? ${event.location || ''}</div>
            <div class="text-muted small">ñ??? ${formatDate(event.startDate)}</div>
            <div class="text-muted small">ñ??” By: <strong>${event.creator?.name || ''}</strong></div>
          </div>
          <div class="d-flex gap-2 flex-wrap">
            <a href="/events/${event.eventId}" class="btn btn-outline-primary btn-sm" style="font-weight:600;width:fit-content;">View Details</a>
          </div>
        </div>
      </div>
    `;

    return col;
  };

  const loadEvents = async () => {
    setLoading(true);
    list.innerHTML = '';
    empty.classList.add('d-none');

    const params = new URLSearchParams();
    const statusValue = statusSelect.value || '';
    const universityValue = universitySelect.value || '';
    if (statusValue) params.append('status', statusValue);
    if (universityValue) params.append('universityId', universityValue);

    try {
      const response = await fetch(`/api/events?${params.toString()}`, { credentials: 'same-origin' });
      const data = await response.json();
      let events = Array.isArray(data) ? data : [];

      events = events.map((event) => ({
        ...event,
        universityId: event.university?.universityId || event.universityId,
      }));

      if (typeSelect.value) {
        events = events.filter((event) => event.type === typeSelect.value);
      }

      if (storedUser && storedUser.role === 'STUDENT') {
        events = events.filter((event) => event.status === 'APPROVED');
      }

      const now = new Date();
      if (timeSelect.value === 'ACTIVE') {
        events = events.filter((event) => new Date(event.startDate) <= now && new Date(event.endDate) >= now);
      } else if (timeSelect.value === 'FUTURE') {
        events = events.filter((event) => new Date(event.startDate) > now);
      } else if (timeSelect.value === 'COMPLETED') {
        events = events.filter((event) => new Date(event.endDate) < now);
      }

      const searchValue = searchInput.value.trim().toLowerCase();
      if (searchValue) {
        events = events.filter((event) =>
          (event.title || '').toLowerCase().includes(searchValue) ||
          (event.description || '').toLowerCase().includes(searchValue)
        );
      }

      events = events.sort((a, b) => {
        const aEnded = new Date(a.endDate) < now;
        const bEnded = new Date(b.endDate) < now;
        if (aEnded && !bEnded) return 1;
        if (!aEnded && bEnded) return -1;
        const aTime = new Date(a.createdAt || a.startDate || 0).getTime();
        const bTime = new Date(b.createdAt || b.startDate || 0).getTime();
        return bTime - aTime;
      });

      if (events.length === 0) {
        empty.classList.remove('d-none');
      } else {
        events.forEach((event) => {
          list.appendChild(buildCard(event));
        });
      }
    } catch (error) {
      empty.classList.remove('d-none');
    } finally {
      setLoading(false);
    }
  };

  [statusSelect, universitySelect, timeSelect, typeSelect].forEach((el) => {
    el.addEventListener('change', loadEvents);
  });

  searchInput.addEventListener('input', () => {
    loadEvents();
  });

  loadUniversities().then(loadEvents);
});
