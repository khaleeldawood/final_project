document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('loginForm');
  const emailInput = document.getElementById('loginEmail');
  const passwordInput = document.getElementById('loginPassword');
  const errorAlert = document.getElementById('loginError');
  const errorMessage = document.getElementById('loginErrorMessage');
  const successAlert = document.getElementById('loginSuccess');

  const submitButton = document.getElementById('loginSubmit');

  const params = new URLSearchParams(window.location.search);
  if (params.get('error') === 'oauth2_failed') {
    showError('OAuth2 authentication failed. Please try again.');
  }

  const setLoading = (isLoading) => {
    submitButton.disabled = isLoading;
    submitButton.textContent = isLoading ? 'Logging in...' : 'Login';
  };

  const clearAlerts = () => {
    errorAlert.classList.add('d-none');
    successAlert.classList.add('d-none');
  };

  const showError = (message) => {
    errorMessage.textContent = message;
    errorAlert.classList.remove('d-none');
  };

  const showSuccess = (message) => {
    successAlert.textContent = message;
    successAlert.classList.remove('d-none');
  };

  form.addEventListener('submit', async (event) => {
    event.preventDefault();
    clearAlerts();
    setLoading(true);

    try {
      const response = await fetch('/api/auth/login', {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({
          email: emailInput.value.trim(),
          password: passwordInput.value,
        }),
      });

      const data = await response.json().catch(() => ({}));

      if (!response.ok) {
        const message = data.message || 'Invalid email or password';
        showError(message);

        return;
      }

      if (data.token) {
        localStorage.setItem('token', data.token);
      }
      localStorage.setItem('user', JSON.stringify(data));
      window.location.href = '/dashboard';
    } catch (error) {
      showError('Invalid email or password');
    } finally {
      setLoading(false);
    }
  });


});
