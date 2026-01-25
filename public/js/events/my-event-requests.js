document.addEventListener('DOMContentLoaded', () => {
  const wrap = document.getElementById('myEventRequestsWrap');
  const loading = document.getElementById('myEventRequestsLoading');
  const empty = document.getElementById('myEventRequestsEmpty');
  const count = document.getElementById('myEventRequestsCount');
  const alertWrap = document.getElementById('myEventRequestsAlert');

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
    wrap.innerHTML = '';
    empty.classList.add('d-none');
    alertWrap.innerHTML = '';
    count.textContent = '0';

    try {
      const events = await fetchJson('/api/events/my-events');
      const requestsMap = {};
      let totalRequests = 0;

      for (const event of events) {
        try {
          const eventRequests = await fetchJson(`/api/event-participation-requests/events/${event.eventId}`);
          if (eventRequests.length > 0) {
            requestsMap[event.eventId] = eventRequests;
            totalRequests += eventRequests.length;
          }
        } catch (error) {
          continue;
        }
      }

      count.textContent = totalRequests;

      if (totalRequests === 0) {
        empty.classList.remove('d-none');
        return;
      }

      Object.entries(requestsMap).forEach(([eventId, eventRequests]) => {
        const event = events.find((item) => item.eventId === parseInt(eventId, 10));
        const card = document.createElement('div');
        card.className = 'card mb-4';
        card.innerHTML = `
          <div class="card-header">
            <h5 class="mb-0">
              <a href="/events/${eventId}">${event?.title || 'Event'}</a>
              <span class="badge bg-warning ms-2">${eventRequests.length} Pending</span>
            </h5>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover">
                <thead style="background-color: var(--bg-tertiary);">
                  <tr>
                    <th style="font-weight:600;padding:1rem;">User</th>
                    <th style="font-weight:600;padding:1rem;">Role</th>
                    <th style="font-weight:600;padding:1rem;">Requested</th>
                    <th style="font-weight:600;padding:1rem;">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  ${eventRequests.map((request) => `
                    <tr data-request-id="${request.requestId}">
                      <td style="font-weight:500;padding:1rem;">
                        ${request.userName}
                        <div class="text-muted small">${request.userEmail}</div>
                      </td>
                      <td style="padding:1rem;"><span class="badge bg-info">${request.requestedRole}</span></td>
                      <td style="padding:1rem;">${formatDate(request.requestedAt)}</td>
                      <td style="padding:1rem;">
                        <div class="d-flex gap-2">
                          <button class="btn btn-success btn-sm" data-action="approve">Approve</button>
                          <button class="btn btn-secondary btn-sm" data-action="reject">Reject</button>
                        </div>
                      </td>
                    </tr>
                  `).join('')}
                </tbody>
              </table>
            </div>
          </div>
        `;
        wrap.appendChild(card);
      });
    } catch (error) {
      alertWrap.innerHTML = `<div class="alert alert-danger">${error.message}</div>`;
    } finally {
      setLoading(false);
    }
  };

  wrap.addEventListener('click', async (event) => {
    const target = event.target;
    if (!(target instanceof HTMLElement)) return;
    const action = target.dataset.action;
    if (!action) return;
    const row = target.closest('tr');
    const requestId = row?.dataset.requestId;
    if (!requestId) return;

    try {
      if (action === 'approve') {
        await fetchJson(`/api/event-participation-requests/${requestId}/approve`, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').content,
          },
        });
      }
      if (action === 'reject') {
        await fetchJson(`/api/event-participation-requests/${requestId}/reject`, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').content,
          },
        });
      }
      load();
    } catch (error) {
      alertWrap.innerHTML = `<div class="alert alert-danger">${error.message}</div>`;
    }
  });

  load();
});
