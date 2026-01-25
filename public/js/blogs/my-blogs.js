document.addEventListener('DOMContentLoaded', () => {
  const tableBody = document.querySelector('#myBlogsTable tbody');
  const loading = document.getElementById('myBlogsLoading');
  const empty = document.getElementById('myBlogsEmpty');

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
      const response = await fetch('/api/blogs/my-blogs', { credentials: 'same-origin' });
      const data = await response.json();
      const blogs = Array.isArray(data) ? data : [];

      if (blogs.length === 0) {
        empty.classList.remove('d-none');
        return;
      }

      blogs.forEach((blog) => {
        const row = document.createElement('tr');
        row.style.cursor = 'pointer';
        row.innerHTML = `
          <td style="font-weight:500;">${blog.title}</td>
          <td><span class="badge bg-info" style="font-size:0.85rem;font-weight:600;">${blog.category}</span></td>
          <td><span class="badge bg-${getStatusVariant(blog.status)}" style="font-size:0.85rem;font-weight:600;">${blog.status}</span></td>
          <td>
            <div class="d-flex gap-2">
              <a class="btn btn-outline-primary btn-sm" href="/blogs/${blog.blogId}">View</a>
              ${(blog.status === 'PENDING' || blog.status === 'APPROVED') ? `<a class="btn btn-outline-info btn-sm" href="/blogs/${blog.blogId}/edit">Edit</a>` : ''}
              ${(blog.status === 'PENDING' || blog.status === 'REJECTED' || (storedUser && storedUser.role === 'ADMIN' && blog.status === 'APPROVED')) ? `<button class="btn btn-danger btn-sm" data-action="delete" data-id="${blog.blogId}" data-title="${blog.title}">Delete</button>` : ''}
            </div>
          </td>
        `;
        row.addEventListener('click', () => {
          window.location.href = `/blogs/${blog.blogId}`;
        });
        row.querySelectorAll('button, a').forEach((el) => {
          el.addEventListener('click', (evt) => evt.stopPropagation());
        });
        tableBody.appendChild(row);
      });
    } catch (error) {
      empty.textContent = 'Failed to load blogs';
      empty.classList.remove('d-none');
    } finally {
      setLoading(false);
    }
  };

  tableBody.addEventListener('click', async (event) => {
    const target = event.target;
    if (!(target instanceof HTMLElement)) return;
    if (target.dataset.action !== 'delete') return;
    const blogId = target.dataset.id;
    const title = target.dataset.title || 'blog';
    if (!window.confirm(`Are you sure you want to delete "${title}"?`)) {
      return;
    }
    await fetch(`/api/blogs/${blogId}`, {
      method: 'DELETE',
      credentials: 'same-origin',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').content,
      },
    });
    load();
  });

  load();
});
