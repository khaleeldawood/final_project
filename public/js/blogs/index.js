document.addEventListener('DOMContentLoaded', () => {
  const list = document.getElementById('blogsList');
  const loading = document.getElementById('blogsLoading');
  const statusWrap = document.getElementById('blogsStatusWrap');
  const statusSelect = document.getElementById('blogsStatusFilter');
  const categorySelect = document.getElementById('blogsCategoryFilter');
  const searchInput = document.getElementById('blogsSearchFilter');
  const alertWrap = document.getElementById('blogsAlert');

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

  const showAlert = (type, message) => {
    if (!message) {
      alertWrap.innerHTML = '';
      return;
    }
    alertWrap.innerHTML = `<div class="alert alert-${type}" role="alert">${message}</div>`;
  };

  const buildCard = (blog) => {
    const col = document.createElement('div');
    col.className = 'col-12 col-sm-6 col-lg-4';
    const reportBadge = blog.reportCount > 0
      ? `<div style="position:absolute;bottom:5px;right:5px;width:28px;height:28px;background-color:#dc3545;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:0.875rem;font-weight:700;z-index:10;box-shadow:0 2px 4px rgba(0,0,0,0.2)">${blog.reportCount}</div>`
      : '';

    col.innerHTML = `
      <div class="card blogs h-100" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border: 1px solid var(--border-color); min-height: 320px; position: relative; overflow: hidden;">
        ${reportBadge}
        <div class="card-body">
          <div class="mb-2 d-flex flex-wrap gap-1">
            <span class="badge bg-info">${blog.category}</span>
            ${blog.isGlobal ? '<span class="badge bg-warning">ñ??? Global</span>' : ''}
            <span class="badge bg-${getStatusVariant(blog.status)}">${blog.status}</span>
          </div>
          <div class="card-title" style="font-size:1.25rem;font-weight:600;margin-bottom:0.75rem;">${blog.title}</div>
          <div class="card-text" style="color:var(--text-secondary);line-height:1.6;">${truncateText(blog.content || '', 150)}</div>
          <div class="text-muted small mb-3">ƒ??‹?? By: <strong>${blog.author?.name || ''}</strong></div>
          <div class="d-flex gap-2 flex-wrap">
            <a href="/blogs/${blog.blogId}" class="btn btn-outline-primary btn-sm" style="font-weight:600;width:fit-content;">Read More</a>
          </div>
        </div>
      </div>
    `;

    return col;
  };

  const loadBlogs = async () => {
    setLoading(true);
    list.innerHTML = '';
    showAlert('', '');

    const params = new URLSearchParams();
    const statusValue = statusSelect.value || '';
    const categoryValue = categorySelect.value || '';
    if (statusValue) params.append('status', statusValue);
    if (categoryValue) params.append('category', categoryValue);

    try {
      const response = await fetch(`/api/blogs?${params.toString()}`, { credentials: 'same-origin' });
      let data = await response.json();
      let blogs = Array.isArray(data) ? data : [];

      if (storedUser && storedUser.role === 'STUDENT') {
        blogs = blogs.filter((blog) => blog.status === 'APPROVED' || blog.author?.userId === storedUser.userId);
      }

      const searchValue = searchInput.value.trim().toLowerCase();
      if (searchValue) {
        blogs = blogs.filter((blog) =>
          (blog.title || '').toLowerCase().includes(searchValue) ||
          (blog.content || '').toLowerCase().includes(searchValue)
        );
      }

      if (blogs.length === 0) {
        list.innerHTML = '<div class="col-12"><div class="card"><div class="card-body text-center py-5"><p class="text-muted mb-0">No blogs found</p></div></div></div>';
      } else {
        blogs.forEach((blog) => list.appendChild(buildCard(blog)));
      }
    } catch (error) {
      showAlert('danger', 'Failed to load blogs.');
    } finally {
      setLoading(false);
    }
  };

  [statusSelect, categorySelect].forEach((el) => {
    el.addEventListener('change', loadBlogs);
  });

  searchInput.addEventListener('input', loadBlogs);

  loadBlogs();
});
