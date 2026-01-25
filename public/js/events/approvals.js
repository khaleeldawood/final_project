document.addEventListener('DOMContentLoaded', () => {
  const loading = document.getElementById('eventApprovalsLoading');
  const tableWrap = document.getElementById('eventApprovalsTableWrap');
  const tableBody = document.getElementById('eventApprovalsBody');
  const empty = document.getElementById('eventApprovalsEmpty');

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

  const load = async () => {
    setLoading(true);
    tableWrap.classList.add('d-none');
    empty.classList.add('d-none');
    tableBody.innerHTML = '';

    try {
      const events = await fetchJson('/api/events?status=PENDING');
      if (!Array.isArray(events) || events.length === 0) {
        empty.classList.remove('d-none');
        return;
      }

      events.forEach((event) => {
        const row = document.createElement('tr');
        row.style.borderLeft = '4px solid #ffc107';
        row.innerHTML = `
          <td style="font-weight:500;padding:1rem;">${event.title}</td>
          <td style="padding:1rem;">
            <div><strong>${event.creator?.name || ''}</strong></div>
            <div class="text-muted small">${event.creator?.email || ''}</div>
          </td>
          <td style="padding:1rem;"><span class="badge bg-secondary">${event.type}</span></td>
          <td style="padding:1rem;">${formatDate(event.startDate)}</td>
          <td style="padding:1rem;">
            <div class="d-flex gap-2">
              <button class="btn btn-success btn-sm" data-action="approve" data-id="${event.eventId}">Approve</button>
              <button class="btn btn-secondary btn-sm" data-action="reject" data-id="${event.eventId}">Reject</button>
            </div>
          </td>
        `;
        tableBody.appendChild(row);
      });

      tableWrap.classList.remove('d-none');
    } catch (error) {
      empty.classList.remove('d-none');
    } finally {
      setLoading(false);
    }
  };

  tableBody.addEventListener('click', async (event) => {
    const target = event.target;
    if (!(target instanceof HTMLElement)) return;
    const action = target.dataset.action;
    const eventId = target.dataset.id;
    if (!action || !eventId) return;

    if (action === 'approve') {
      try {
        await fetchJson(`/api/events/${eventId}/approve`, {
          method: 'PUT',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').content,
          },
        });
        load();
      } catch (error) {
        alert('Failed to approve event. Please try again.');
      }
    }

    if (action === 'reject') {
      const reason = window.prompt('Rejection reason:');
      if (!reason) return;
      try {
        await fetchJson(`/api/events/${eventId}/reject`, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').content,
          },
          body: JSON.stringify({ reason }),
        });
        load();
      } catch (error) {
        alert('Failed to reject event. Please try again.');
      }
    }
  });

  load();
});
