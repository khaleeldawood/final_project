document.addEventListener('DOMContentLoaded', () => {
  const loading = document.getElementById('blogApprovalsLoading');
  const tableWrap = document.getElementById('blogApprovalsTableWrap');
  const tableBody = document.getElementById('blogApprovalsBody');
  const empty = document.getElementById('blogApprovalsEmpty');

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
      const blogs = await fetchJson('/api/blogs/pending');
      if (!Array.isArray(blogs) || blogs.length === 0) {
        empty.classList.remove('d-none');
        return;
      }

      blogs.forEach((blog) => {
        const row = document.createElement('tr');
        row.style.borderLeft = '4px solid #ffc107';
        row.innerHTML = `
          <td style="font-weight:500;padding:1rem;">${blog.title}</td>
          <td style="padding:1rem;">
            <div><strong>${blog.author?.name || ''}</strong></div>
            <div class="text-muted small">${blog.author?.email || ''}</div>
          </td>
          <td style="padding:1rem;"><span class="badge bg-info" style="font-size:0.85rem;font-weight:600;">${blog.category}</span></td>
          <td style="padding:1rem;">
            <div class="d-flex gap-2">
              <button class="btn btn-success btn-sm" data-action="approve" data-id="${blog.blogId}">Approve</button>
              <button class="btn btn-secondary btn-sm" data-action="reject" data-id="${blog.blogId}">Reject</button>
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
    const blogId = target.dataset.id;
    if (!action || !blogId) return;

    if (action === 'approve') {
      try {
        await fetchJson(`/api/blogs/${blogId}/approve`, {
          method: 'PUT',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').content,
          },
        });
        load();
      } catch (error) {
        alert('Failed to approve blog. Please try again.');
      }
    }

    if (action === 'reject') {
      const reason = window.prompt('Rejection reason:');
      if (!reason) return;
      try {
        await fetchJson(`/api/blogs/${blogId}/reject`, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').content,
          },
          body: JSON.stringify({ reason }),
        });
        load();
      } catch (error) {
        alert('Failed to reject blog. Please try again.');
      }
    }
  });

  load();
});
