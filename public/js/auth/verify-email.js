document.addEventListener('DOMContentLoaded', () => {
  const params = new URLSearchParams(window.location.search);
  const token = params.get('token');

  const statusText = document.getElementById('verifyEmailStatusText');
  const statusIcon = document.getElementById('verifyEmailStatusIcon');
  const alertBox = document.getElementById('verifyEmailAlert');
  const spinner = document.getElementById('verifyEmailSpinner');

  const setStatus = (status, message) => {
    spinner.classList.add('d-none');
    statusText.textContent = '';
    statusText.classList.add('d-none');
    alertBox.textContent = message;
    alertBox.className = `alert ${status === 'success' ? 'alert-success' : 'alert-danger'}`;
    alertBox.classList.remove('d-none');

    if (status === 'success') {
      statusIcon.textContent = '?';
      statusIcon.className = 'text-success mb-3';
    } else {
      statusIcon.textContent = '?';
      statusIcon.className = 'text-danger mb-3';
    }
  };

  if (!token) {
    setStatus('error', 'Invalid verification link');
    return;
  }

  fetch(`/api/auth/verify-email?token=${encodeURIComponent(token)}`, { credentials: 'same-origin' })
    .then(async (response) => {
      const data = await response.json().catch(() => ({}));
      if (!response.ok) {
        throw new Error(data.error || data.message || 'Verification failed');
      }
      localStorage.setItem('user', JSON.stringify(data));
      if (data.token) {
        localStorage.setItem('token', data.token);
      }
      setStatus('success', 'Email verified successfully! Redirecting...');
      setTimeout(() => {
        window.location.href = '/dashboard';
      }, 2000);
    })
    .catch((error) => {
      setStatus('error', error.message || 'Verification failed');
    });
});
