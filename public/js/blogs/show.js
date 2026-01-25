document.addEventListener('DOMContentLoaded', () => {
  const root = document.getElementById('blogDetailsRoot');
  const blogId = parseInt(window.location.pathname.split('/').pop(), 10);

  const storedUser = (() => {
    try {
      return JSON.parse(localStorage.getItem('user'));
    } catch (error) {
      return null;
    }
  })();

  const fetchJson = async (url, options = {}) => {
    const response = await fetch(url, { credentials: 'same-origin', ...options });
    const data = await response.json().catch(() => null);
    if (!response.ok) {
      const message = data?.message || 'Request failed';
      throw new Error(message);
    }
    return data;
  };

  const render = (blog) => {
    const reportBadge = blog.reportCount > 0
      ? `<div style="position:absolute;bottom:5px;right:5px;width:28px;height:28px;background-color:#dc3545;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:0.875rem;font-weight:700;z-index:10;box-shadow:0 2px 4px rgba(0,0,0,0.2)">${blog.reportCount}</div>`
      : '';

    const canReport = storedUser && blog.author?.userId !== storedUser.userId && (blog.status === 'PENDING' || blog.status === 'APPROVED');

    root.innerHTML = `
      <div class="mb-3">
        <a href="/blogs" class="btn btn-outline-secondary btn-sm">ƒ?? Back to Blogs</a>
      </div>
      <div class="card mb-4" style="position:relative;overflow:hidden;">
        ${reportBadge}
        <div class="card-header">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <h2 class="mb-2">${blog.title || ''}</h2>
              <div class="d-flex gap-2 flex-wrap">
                <span class="badge bg-info">${blog.category}</span>
                ${blog.isGlobal ? '<span class="badge bg-warning">ñ??? Global</span>' : ''}
                <span class="badge bg-${getStatusVariant(blog.status)}">${blog.status}</span>
              </div>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="mb-3">
            <strong>Author:</strong> <span style="color:var(--text-primary)">${blog.author?.name || ''}</span>
            ${blog.author?.university ? `<span class="text-muted"> ƒ?? ${blog.author.university.name}</span>` : ''}
          </div>
          <div class="mb-3">
            <strong>Published:</strong> <span style="color:var(--text-primary)">${formatDate(blog.createdAt)}</span>
          </div>
          ${blog.lastModifiedAt ? `<div class="mb-3"><strong>Last Updated:</strong> <span style="color:var(--text-primary)">${formatDate(blog.lastModifiedAt)}</span></div>` : ''}
          <hr style="border-color:var(--border-color);opacity:1;" />
          <div style="white-space:pre-wrap;line-height:1.8;color:var(--text-primary);">${blog.content || ''}</div>
        </div>
      </div>
      ${canReport ? `
        <div class="card mb-3">
          <div class="card-body">
            <h5>Report This Blog</h5>
            <p class="text-muted small">If you find this blog inappropriate or violates community guidelines</p>
            <button id="blogReportButton" class="btn btn-warning btn-sm">Report Blog</button>
          </div>
        </div>
      ` : ''}
      ${(storedUser && storedUser.role === 'ADMIN') ? `
        <div class="card">
          <div class="card-body">
            <h5>Admin Actions</h5>
            <button id="blogDeleteButton" class="btn btn-danger btn-sm">Delete Blog</button>
          </div>
        </div>
      ` : ''}

      <div class="modal fade" id="blogReportModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content" style="background-color:var(--bg-secondary);color:var(--text-primary);">
            <div class="modal-header" style="border-color:var(--border-color);">
              <h5 class="modal-title">Report Blog</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <p class="text-muted">You are reporting: <strong style="color:var(--text-primary)">${blog.title}</strong></p>
              <div class="mb-3">
                <label class="form-label">Reason for reporting</label>
                <textarea id="blogReportReason" class="form-control" rows="4" placeholder="Please explain why you are reporting this blog..."></textarea>
              </div>
            </div>
            <div class="modal-footer" style="border-color:var(--border-color);">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn-danger" id="blogReportSubmit">Submit Report</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" id="blogDeleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content" style="background-color:var(--bg-secondary);color:var(--text-primary);">
            <div class="modal-header" style="border-color:var(--border-color);">
              <h5 class="modal-title">Delete Blog</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <p>Are you sure you want to delete this blog? This action cannot be undone.</p>
            </div>
            <div class="modal-footer" style="border-color:var(--border-color);">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn-danger" id="blogDeleteSubmit">Delete Blog</button>
            </div>
          </div>
        </div>
      </div>
    `;

    const reportButton = document.getElementById('blogReportButton');
    const reportModalElement = document.getElementById('blogReportModal');
    const reportModal = reportModalElement ? new bootstrap.Modal(reportModalElement) : null;
    if (reportButton && reportModal) {
      reportButton.addEventListener('click', () => reportModal.show());
    }

    const deleteButton = document.getElementById('blogDeleteButton');
    const deleteModalElement = document.getElementById('blogDeleteModal');
    const deleteModal = deleteModalElement ? new bootstrap.Modal(deleteModalElement) : null;
    if (deleteButton && deleteModal) {
      deleteButton.addEventListener('click', () => deleteModal.show());
    }

    document.getElementById('blogReportSubmit')?.addEventListener('click', async () => {
      const reason = document.getElementById('blogReportReason').value.trim();
      if (!reason) {
        alert('Please provide a reason for reporting');
        return;
      }
      try {
        await fetchJson(`/api/reports/blogs/${blogId}`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').content,
          },
          body: JSON.stringify({ reason }),
        });
        reportModal.hide();
        window.location.reload();
      } catch (error) {
        alert(error.message);
      }
    });

    document.getElementById('blogDeleteSubmit')?.addEventListener('click', async () => {
      try {
        await fetchJson(`/api/blogs/${blogId}`, {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').content,
          },
        });
        deleteModal.hide();
        window.location.href = '/blogs';
      } catch (error) {
        alert(error.message);
      }
    });
  };

  const load = async () => {
    root.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>';
    try {
      const blog = await fetchJson(`/api/blogs/${blogId}`);
      render(blog);
    } catch (error) {
      root.innerHTML = '<div class="alert alert-danger">Failed to load blog details</div>';
    }
  };

  load();
});
