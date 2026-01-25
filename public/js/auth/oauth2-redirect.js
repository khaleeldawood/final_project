document.addEventListener('DOMContentLoaded', () => {
  const params = new URLSearchParams(window.location.search);
  const token = params.get('token');
  const error = params.get('error');

  if (error) {
    window.location.href = '/login?error=oauth2_failed';
    return;
  }

  if (!token) {
    window.location.href = '/login';
    return;
  }

  localStorage.setItem('token', token);

  fetch('/api/auth/session', { credentials: 'same-origin' })
    .then(async (response) => {
      if (!response.ok) {
        throw new Error('session');
      }
      const data = await response.json();
      localStorage.setItem('user', JSON.stringify(data));
      window.location.href = '/dashboard';
    })
    .catch(() => {
      localStorage.removeItem('token');
      window.location.href = '/login?error=oauth2_failed';
    });
});
