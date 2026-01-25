document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('registerForm');
  const successPanel = document.getElementById('registerSuccessPanel');
  const formPanel = document.getElementById('registerFormPanel');
  const errorAlert = document.getElementById('registerError');
  const successEmail = document.getElementById('registerSuccessEmail');
  const submitButton = document.getElementById('registerSubmit');

  const nameInput = document.getElementById('registerName');
  const emailInput = document.getElementById('registerEmail');
  const passwordInput = document.getElementById('registerPassword');
  const confirmInput = document.getElementById('registerConfirmPassword');
  const universitySelect = document.getElementById('registerUniversity');

  const emailError = document.getElementById('registerEmailError');
  const passwordRequirementList = document.querySelectorAll('[data-password-rule]');

  const setLoading = (isLoading) => {
    submitButton.disabled = isLoading;
    submitButton.textContent = isLoading ? 'Creating account...' : 'Register';
  };

  const showError = (message) => {
    errorAlert.textContent = message;
    errorAlert.classList.remove('d-none');
  };

  const clearError = () => {
    errorAlert.classList.add('d-none');
    errorAlert.textContent = '';
  };

  const validateEmail = (email) => /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(email);

  const validatePassword = (password) => {
    const errors = [];
    if (password.length < 8) errors.push('At least 8 characters');
    if (!/[A-Z]/.test(password)) errors.push('At least one uppercase letter');
    if (!/[a-z]/.test(password)) errors.push('At least one lowercase letter');
    if (!/[0-9]/.test(password)) errors.push('At least one number');
    if (!/[!@#$%^&*(),.?":{}|<>]/.test(password)) errors.push('At least one special character (!@#$%^&*...)');
    return errors;
  };

  const updatePasswordRules = () => {
    const password = passwordInput.value;
    passwordRequirementList.forEach((item) => {
      const rule = item.getAttribute('data-password-rule');
      const isValid = (() => {
        if (rule === 'length') return password.length >= 8;
        if (rule === 'uppercase') return /[A-Z]/.test(password);
        if (rule === 'lowercase') return /[a-z]/.test(password);
        if (rule === 'number') return /[0-9]/.test(password);
        if (rule === 'special') return /[!@#$%^&*(),.?":{}|<>]/.test(password);
        return false;
      })();
      item.classList.toggle('text-success', isValid);
    });
  };

  const loadUniversities = async () => {
    try {
      const response = await fetch('/api/admin/universities', { credentials: 'same-origin' });
      const data = await response.json();
      const universities = Array.isArray(data) ? data : [];
      cachedUniversities = universities;

      universitySelect.innerHTML = '<option value="">Select University</option>';
      universities.forEach((uni) => {
        const option = document.createElement('option');
        option.value = uni.universityId || uni.id || '';
        const domain = uni.emailDomain || uni.email_domain || '';
        option.textContent = `${uni.name}${domain ? ` (@${domain})` : ''}`;
        universitySelect.appendChild(option);
      });

      if (universities.length > 0) {
        const firstId = universities[0].universityId || universities[0].id || '';
        universitySelect.value = firstId;
        updateUniversityHint(universities);
      }
    } catch (error) {
      universitySelect.innerHTML = '<option value="">Select University</option>';
    }
  };

  const updateUniversityHint = (universities) => {
    const selectedId = parseInt(universitySelect.value, 10);
    const selected = universities.find((uni) => (uni.universityId || uni.id) === selectedId);
    const hint = document.getElementById('registerUniversityHint');
    if (selected && (selected.emailDomain || selected.email_domain)) {
      const domain = selected.emailDomain || selected.email_domain;
      hint.textContent = `Use your @${domain} email`;
      hint.classList.remove('d-none');
    } else {
      hint.textContent = '';
      hint.classList.add('d-none');
    }
  };

  let cachedUniversities = [];
  loadUniversities();

  emailInput.addEventListener('blur', () => {
    if (emailInput.value && !validateEmail(emailInput.value)) {
      emailError.textContent = 'Please enter a valid email address (e.g., user@university.edu)';
      emailError.classList.remove('d-none');
    } else {
      emailError.textContent = '';
      emailError.classList.add('d-none');
    }
  });

  passwordInput.addEventListener('blur', updatePasswordRules);
  passwordInput.addEventListener('input', updatePasswordRules);
  universitySelect.addEventListener('change', () => updateUniversityHint(cachedUniversities));

  form.addEventListener('submit', async (event) => {
    event.preventDefault();
    clearError();

    if (!validateEmail(emailInput.value.trim())) {
      showError('Please enter a valid email address');
      return;
    }

    const passwordErrors = validatePassword(passwordInput.value);
    if (passwordErrors.length > 0) {
      showError(`Password does not meet requirements: ${passwordErrors.join(', ')}`);
      return;
    }

    if (passwordInput.value !== confirmInput.value) {
      showError('Passwords do not match');
      return;
    }

    setLoading(true);

    try {
      const response = await fetch('/api/auth/register', {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({
          name: nameInput.value.trim(),
          email: emailInput.value.trim(),
          password: passwordInput.value,
          confirmPassword: confirmInput.value,
          role: 'STUDENT',
          universityId: universitySelect.value,
        }),
      });

      const data = await response.json().catch(() => ({}));
      if (!response.ok) {
        showError(data.message || 'Registration failed. Please try again.');
        return;
      }

      setLoading(false);
      window.location.href = '/dashboard';
    } catch (error) {
      showError('Registration failed. Please try again.');
    } finally {
      setLoading(false);
    }
  });


});
