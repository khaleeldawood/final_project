document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('createBlogForm');
  const errorAlert = document.getElementById('createBlogError');
  const submitButton = document.getElementById('createBlogSubmit');

  const showError = (message) => {
    errorAlert.textContent = message;
    errorAlert.classList.remove('d-none');
  };

  const clearError = () => {
    errorAlert.classList.add('d-none');
    errorAlert.textContent = '';
  };

  form.addEventListener('submit', async (event) => {
    event.preventDefault();
    clearError();

    const data = Object.fromEntries(new FormData(form).entries());
    const isGlobal = document.getElementById('createBlogGlobal').checked;

    if ((data.title || '').length < 3) {
      showError('Title must be at least 3 characters long');
      return;
    }

    if (!data.content || !data.content.trim()) {
      showError('Content cannot be empty');
      return;
    }

    submitButton.disabled = true;
    submitButton.textContent = 'Creating...';

    try {
      const response = await fetch('/api/blogs', {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').content,
        },
        body: JSON.stringify({
          title: data.title,
          content: data.content,
          category: data.category,
          isGlobal,
        }),
      });

      const responseData = await response.json().catch(() => ({}));
      if (!response.ok) {
        showError(responseData.message || 'Failed to create blog. Please try again.');
        return;
      }

      window.location.href = '/my-blogs';
    } catch (error) {
      showError('Failed to create blog. Please try again.');
    } finally {
      submitButton.disabled = false;
      submitButton.textContent = 'Create Post';
    }
  });
});
