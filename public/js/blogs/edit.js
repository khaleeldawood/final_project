document.addEventListener('DOMContentLoaded', () => {
  const blogId = parseInt(window.location.pathname.split('/').slice(-2)[0], 10);
  const form = document.getElementById('editBlogForm');
  const errorAlert = document.getElementById('editBlogError');
  const warningAlert = document.getElementById('editBlogWarning');
  const submitButton = document.getElementById('editBlogSubmit');
  const globalCheckbox = document.getElementById('editBlogGlobal');

  const showError = (message) => {
    errorAlert.textContent = message;
    errorAlert.classList.remove('d-none');
  };

  const clearError = () => {
    errorAlert.classList.add('d-none');
    errorAlert.textContent = '';
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

  const loadBlog = async () => {
    try {
      const blog = await fetchJson(`/api/blogs/${blogId}`);
      const currentUser = JSON.parse(localStorage.getItem('user') || '{}');
      if (blog.author?.userId !== currentUser.userId) {
        showError('You do not have permission to edit this blog');
        return;
      }
      if (blog.status !== 'PENDING' && blog.status !== 'APPROVED') {
        showError('Only pending or approved blogs can be edited');
        return;
      }

      warningAlert.classList.toggle('d-none', blog.status !== 'APPROVED');
      form.title.value = blog.title || '';
      form.content.value = blog.content || '';
      form.category.value = blog.category || 'ARTICLE';
      globalCheckbox.checked = !!blog.isGlobal;
      form.classList.remove('d-none');
    } catch (error) {
      showError('Failed to load blog details');
    }
  };

  form.addEventListener('submit', async (event) => {
    event.preventDefault();
    clearError();
    submitButton.disabled = true;
    submitButton.textContent = 'Saving...';

    try {
      await fetchJson(`/api/blogs/${blogId}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').content,
        },
        body: JSON.stringify({
          title: form.title.value,
          content: form.content.value,
          category: form.category.value,
          isGlobal: globalCheckbox.checked,
        }),
      });
      window.location.href = '/my-blogs';
    } catch (error) {
      showError(error.message || 'Failed to update blog. Please try again.');
    } finally {
      submitButton.disabled = false;
      submitButton.textContent = 'Save Changes';
    }
  });

  loadBlog();
});
