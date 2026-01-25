document.addEventListener('DOMContentLoaded', () => {
  const tableBody = document.querySelector('#myEventsTable tbody');
  const loading = document.getElementById('myEventsLoading');
  const empty = document.getElementById('myEventsEmpty');

  const storedUser = (() => {
    try {
      return JSON.parse(localStorage.getItem('user'));
    } catch (error) {
      return null;
    }
  })();

  const setLoading = (isLoading) => {
    loading.classList.toggle('d-none', !isLoading);
  };

  const load = async () => {
    setLoading(true);
    empty.classList.add('d-none');
    tableBody.innerHTML = '';
    try {
      const response = await fetch('/api/events/my-events', { credentials: 'same-origin' });
      const data = await response.json();
      const events = Array.isArray(data) ? data : [];

      if (events.length === 0) {
        empty.classList.remove('d-none');
        return;
      }

      events.forEach((event) => {
        const row = document.createElement('tr');
        row.style.cursor = 'pointer';
        row.innerHTML = `
          <td style="font-weight:500;">${event.title}</td>
          <td>${event.type}</td>
          <td>${formatDate(event.startDate)}</td>
          <td><span class="badge bg-${getStatusVariant(event.status)}" style="font-size:0.85rem;font-weight:600;">${event.status}</span></td>
          <td>
            <div class="d-flex gap-2">
              <a class="btn btn-outline-primary btn-sm" href="/events/${event.eventId}">View</a>
              ${(event.status === 'PENDING' || event.status === 'APPROVED') ? `<a class="btn btn-outline-info btn-sm" href="/events/${event.eventId}/edit">Edit</a>` : ''}
              ${(event.status === 'PENDING' || event.status === 'REJECTED') ? `<button class="btn btn-danger btn-sm" data-action="delete" data-id="${event.eventId}" data-title="${event.title}">Delete</button>` : ''}
              ${(event.status === 'APPROVED' && storedUser && (storedUser.role === 'ADMIN' || storedUser.role === 'SUPERVISOR')) ? `<button class="btn btn-warning btn-sm" data-action="cancel" data-id="${event.eventId}" data-title="${event.title}">Cancel</button>` : ''}
            </div>
          </td>
        `;
        row.addEventListener('click', () => {
          window.location.href = `/events/${event.eventId}`;
        });
        row.querySelectorAll('button, a').forEach((el) => {
          el.addEventListener('click', (evt) => evt.stopPropagation());
        });
        tableBody.appendChild(row);
      });
    } catch (error) {
      empty.textContent = 'Failed to load events';
      empty.classList.remove('d-none');
    } finally {
      setLoading(false);
    }
  };

  tableBody.addEventListener('click', async (event) => {
    const target = event.target;
    if (!(target instanceof HTMLElement)) return;
    if (!target.dataset.action) return;

    const eventId = target.dataset.id;
    const title = target.dataset.title || 'event';
    if (target.dataset.action === 'delete') {
      if (!window.confirm(`Are you sure you want to delete "${title}"?`)) {
        return;
      }
      await fetch(`/api/events/${eventId}`, {
        method: 'DELETE',
        credentials: 'same-origin',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').content,
        },
      });
      load();
    }

    if (target.dataset.action === 'cancel') {
      if (!window.confirm(`Are you sure you want to cancel "${title}"?`)) {
        return;
      }
      await fetch(`/api/events/${eventId}/cancel`, {
        method: 'PUT',
        credentials: 'same-origin',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').content,
        },
        body: JSON.stringify({ reason: '' }),
      });
      load();
    }
  });

  load();
});
