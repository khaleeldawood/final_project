document.addEventListener('DOMContentLoaded', () => {
  const loading = document.getElementById('participationLoading');
  const body = document.getElementById('participationBody');
  const empty = document.getElementById('participationEmpty');
  const tableWrap = document.getElementById('participationTableWrap');
  const alertWrap = document.getElementById('participationAlert');

  const setLoading = (isLoading) => {
    loading.classList.toggle('d-none', !isLoading);
  };

  const load = async () => {
    setLoading(true);
    body.innerHTML = '';
    empty.classList.add('d-none');
    tableWrap.classList.remove('d-none');
    alertWrap.innerHTML = '';

    try {
      const response = await fetch('/api/event-participation-requests/my-requests', { credentials: 'same-origin' });
      const data = await response.json();
      const requests = Array.isArray(data) ? data : [];

      if (requests.length === 0) {
        tableWrap.classList.add('d-none');
        empty.classList.remove('d-none');
        return;
      }

      requests.forEach((request) => {
        const row = document.createElement('tr');
        row.innerHTML = `
          <td style="font-weight:500;padding:1rem;"><a href="/events/${request.eventId}">${request.eventTitle}</a></td>
          <td style="padding:1rem;"><span class="badge bg-info">${request.requestedRole}</span></td>
          <td style="padding:1rem;"><span class="badge bg-${getStatusVariant(request.status)}">${request.status}</span></td>
          <td style="padding:1rem;">${formatDate(request.requestedAt)}</td>
          <td style="padding:1rem;"><a class="btn btn-outline-primary btn-sm" href="/events/${request.eventId}">View Event</a></td>
        `;
        body.appendChild(row);
      });
    } catch (error) {
      alertWrap.innerHTML = '<div class="alert alert-danger">Failed to load participation requests</div>';
    } finally {
      setLoading(false);
    }
  };

  load();
});
