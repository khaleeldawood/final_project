document.addEventListener('DOMContentLoaded', () => {
  const params = new URLSearchParams(window.location.search);
  const token = params.get('token');

  const validatingPanel = document.getElementById('resetPasswordValidating');
  const invalidPanel = document.getElementById('resetPasswordInvalid');
  const successPanel = document.getElementById('resetPasswordSuccess');
  const formPanel = document.getElementById('resetPasswordFormPanel');
  const form = document.getElementById('resetPasswordForm');
  const passwordInput = document.getElementById('resetPassword');
  const confirmInput = document.getElementById('resetPasswordConfirm');
  const errorAlert = document.getElementById('resetPasswordError');
  const strengthLabel = document.getElementById('resetPasswordStrengthLabel');
  const strengthBar = document.getElementById('resetPasswordStrengthBar');
  const submitButton = document.getElementById('resetPasswordSubmit');
  const passwordRules = document.querySelectorAll('[data-reset-rule]');

  const showPanel = (panel) => {
    [validatingPanel, invalidPanel, successPanel, formPanel].forEach((el) => el.classList.add('d-none'));
    panel.classList.remove('d-none');
  };

  const showError = (message) => {
    errorAlert.textContent = message;
    errorAlert.classList.remove('d-none');
  };

  const clearError = () => {
    errorAlert.textContent = '';
    errorAlert.classList.add('d-none');
  };

  const updateStrength = () => {
    const strength = passwordValidator.getPasswordStrength(passwordInput.value);
    strengthLabel.textContent = strength.strength;
    strengthLabel.className = `text-${strength.color}`;
    strengthBar.style.width = `${strength.percentage}%`;
    strengthBar.className = `progress-bar bg-${strength.color}`;

    passwordRules.forEach((item) => {
      const rule = item.getAttribute('data-reset-rule');
      const isValid = (() => {
        if (rule === 'length') return passwordInput.value.length >= 8;
        if (rule === 'uppercase') return /[A-Z]/.test(passwordInput.value);
        if (rule === 'lowercase') return /[a-z]/.test(passwordInput.value);
        if (rule === 'number') return /[0-9]/.test(passwordInput.value);
        if (rule === 'special') return /[!@#$%^&*(),.?":{}|<>]/.test(passwordInput.value);
        return false;
      })();
      item.classList.toggle('text-success', isValid);
    });
  };

  const validateToken = async () => {
    if (!token) {
      showPanel(invalidPanel);
      return;
    }

    try {
      const response = await fetch(`/api/auth/validate-reset-token?token=${encodeURIComponent(token)}`, {
        credentials: 'same-origin',
      });
      const data = await response.json();
      if (data.valid) {
        showPanel(formPanel);
      } else {
        showPanel(invalidPanel);
      }
    } catch (error) {
      showPanel(invalidPanel);
    }
  };

  passwordInput.addEventListener('input', updateStrength);

  form.addEventListener('submit', async (event) => {
    event.preventDefault();
    clearError();

    const errors = passwordValidator.validatePassword(passwordInput.value);
    if (errors.length > 0) {
      showError(`Password does not meet requirements: ${errors.join(', ')}`);
      return;
    }

    if (passwordInput.value !== confirmInput.value) {
      showError('Passwords do not match');
      return;
    }

    submitButton.disabled = true;
    submitButton.textContent = 'Resetting...';

    try {
      const response = await fetch('/api/auth/reset-password', {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ token, newPassword: passwordInput.value }),
      });
      const data = await response.json().catch(() => ({}));
      if (!response.ok) {
        showError(data.error || 'Failed to reset password. Please try again.');
        return;
      }
      showPanel(successPanel);
      setTimeout(() => {
        window.location.href = '/login?reset=success';
      }, 2000);
    } catch (error) {
      showError('Failed to reset password. Please try again.');
    } finally {
      submitButton.disabled = false;
      submitButton.textContent = 'Reset Password';
    }
  });

  showPanel(validatingPanel);
  validateToken();
});
