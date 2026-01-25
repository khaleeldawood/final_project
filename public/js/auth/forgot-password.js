document.addEventListener('DOMContentLoaded', () => {
  const container = document.getElementById('forgotPasswordContainer');
  const formPanel = document.getElementById('forgotPasswordFormPanel');
  const successPanel = document.getElementById('forgotPasswordSuccessPanel');
  const form = document.getElementById('forgotPasswordForm');
  const emailInput = document.getElementById('forgotPasswordEmail');
  const errorAlert = document.getElementById('forgotPasswordError');
  const submitButton = document.getElementById('forgotPasswordSubmit');
  const successEmail = document.getElementById('forgotPasswordSuccessEmail');

  const setLoading = (loading) => {
    submitButton.disabled = loading;
    submitButton.textContent = loading ? 'Sending...' : 'Send Reset Link';
  };

  const showError = (message) => {
    errorAlert.textContent = message;
    errorAlert.classList.remove('d-none');
  };

  const clearError = () => {
    errorAlert.textContent = '';
    errorAlert.classList.add('d-none');
  };

  form.addEventListener('submit', async (event) => {
    event.preventDefault();
    clearError();
    setLoading(true);

    try {
      const response = await fetch('/api/auth/forgot-password', {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ email: emailInput.value.trim() }),
      });

      if (!response.ok) {
        throw new Error('failed');
      }

      successEmail.textContent = emailInput.value.trim();
      formPanel.classList.add('d-none');
      successPanel.classList.remove('d-none');
      container.style.marginTop = '0';
      container.style.marginBottom = '0';
    } catch (error) {
      showError('An error occurred. Please try again.');
    } finally {
      setLoading(false);
    }
  });
});
