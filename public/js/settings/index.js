document.addEventListener('DOMContentLoaded', () => {
  const alertBox = document.getElementById('settingsAlert');
  const oauthAlert = document.getElementById('settingsOAuthAlert');
  const universitySelect = document.getElementById('settingsUniversitySelect');
  const universityCurrent = document.getElementById('settingsUniversityCurrent');
  const universityForm = document.getElementById('settingsUniversityForm');
  const universitySubmit = document.getElementById('settingsUniversitySubmit');
  const profileForm = document.getElementById('settingsProfileForm');
  const nameInput = document.getElementById('settingsName');
  const profileSubmit = document.getElementById('settingsProfileSubmit');
  const notificationsToggle = document.getElementById('settingsNotificationsToggle');
  const passwordForm = document.getElementById('settingsPasswordForm');
  const passwordTitle = document.getElementById('settingsPasswordTitle');
  const passwordInfo = document.getElementById('settingsPasswordInfo');
  const oldPasswordGroup = document.getElementById('settingsOldPasswordGroup');
  const oldPasswordInput = document.getElementById('settingsOldPassword');
  const newPasswordInput = document.getElementById('settingsNewPassword');
  const confirmPasswordInput = document.getElementById('settingsConfirmPassword');
  const passwordSubmit = document.getElementById('settingsPasswordSubmit');

  const STORAGE_KEY = 'unihub_notifications_enabled';

  const fetchJson = async (url, options = {}) => {
    const response = await fetch(url, { credentials: 'same-origin', ...options });
    const data = await response.json().catch(() => null);
    if (!response.ok) {
      const message = data?.message || data?.error || 'Request failed';
      throw new Error(message);
    }
    return data;
  };

  const setAlert = (message, type = 'success') => {
    alertBox.innerHTML = `
      <div class="alert alert-${type} alert-dismissible" role="alert">
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    `;
  };

  const clearAlert = () => {
    alertBox.innerHTML = '';
  };

  const loadUniversities = async () => {
    try {
      const universities = await fetchJson('/api/admin/universities');
      const filtered = Array.isArray(universities)
        ? universities.filter((uni) => uni.name !== 'Example University')
        : [];
      universitySelect.innerHTML = '<option value="">Choose a university...</option>';
      filtered.forEach((uni) => {
        const option = document.createElement('option');
        option.value = uni.universityId || uni.id || '';
        option.textContent = uni.name;
        universitySelect.appendChild(option);
      });
    } catch (error) {
      universitySelect.innerHTML = '<option value="">Choose a university...</option>';
    }
  };

  const init = async () => {
    try {
      const user = await fetchJson('/api/auth/session');

      nameInput.value = user.name || '';
      if (user.universityName) {
        universityCurrent.textContent = `Current: ${user.universityName}`;
      }

      const isOAuth2User = !user.hasPassword;
      if (isOAuth2User && !user.universityId) {
        oauthAlert.classList.remove('d-none');
      }

      if (isOAuth2User) {
        passwordTitle.textContent = 'Set Password';
        passwordInfo.classList.remove('d-none');
        oldPasswordGroup.classList.add('d-none');
      } else {
        passwordTitle.textContent = 'Change Password';
        passwordInfo.classList.add('d-none');
        oldPasswordGroup.classList.remove('d-none');
      }

      const notificationsEnabled = localStorage.getItem(STORAGE_KEY) !== 'false';
      notificationsToggle.checked = notificationsEnabled;

      await loadUniversities();
      if (user.universityId) {
        universitySelect.value = user.universityId;
      }

      universityForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        clearAlert();
        universitySubmit.disabled = true;
        try {
          await fetchJson('/api/users/university', {
            method: 'PUT',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ universityId: universitySelect.value || null }),
          });
          setAlert('University updated successfully');
          window.location.reload();
        } catch (error) {
          setAlert(error.message || 'Failed to update university', 'danger');
        } finally {
          universitySubmit.disabled = false;
        }
      });

      profileForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        clearAlert();
        profileSubmit.disabled = true;
        try {
          await fetchJson('/api/users/me', {
            method: 'PUT',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ name: nameInput.value }),
          });
          setAlert('Name updated successfully');
        } catch (error) {
          setAlert(error.message || 'Failed to update name', 'danger');
        } finally {
          profileSubmit.disabled = false;
        }
      });

      notificationsToggle.addEventListener('change', (event) => {
        const enabled = event.target.checked;
        localStorage.setItem(STORAGE_KEY, enabled.toString());
        setAlert('Notification preferences updated');
      });

      passwordForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        clearAlert();
        if (newPasswordInput.value !== confirmPasswordInput.value) {
          setAlert('New passwords do not match', 'danger');
          return;
        }

        passwordSubmit.disabled = true;
        try {
          if (isOAuth2User) {
            await fetchJson('/api/users/set-password', {
              method: 'PUT',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
              },
              body: JSON.stringify({ newPassword: newPasswordInput.value }),
            });
            setAlert('Password set successfully! You can now login with email and password.');
          } else {
            await fetchJson('/api/users/change-password', {
              method: 'PUT',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
              },
              body: JSON.stringify({
                oldPassword: oldPasswordInput.value,
                newPassword: newPasswordInput.value,
              }),
            });
            setAlert('Password changed successfully');
          }

          oldPasswordInput.value = '';
          newPasswordInput.value = '';
          confirmPasswordInput.value = '';
          window.location.reload();
        } catch (error) {
          setAlert(error.message || 'Failed to change password', 'danger');
        } finally {
          passwordSubmit.disabled = false;
        }
      });
    } catch (error) {
      setAlert('Please log in to access settings', 'danger');
    }
  };

  init();
});