document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('createEventForm');
  const errorAlert = document.getElementById('createEventError');
  const submitButton = document.getElementById('createEventSubmit');
  const universitySelect = document.getElementById('createEventUniversity');
  const participatesToggle = document.getElementById('createEventParticipates');
  const roleWrap = document.getElementById('createEventRoleWrap');
  const roleSelect = document.getElementById('createEventRole');

  const showError = (message) => {
    errorAlert.textContent = message;
    errorAlert.classList.remove('d-none');
  };

  const clearError = () => {
    errorAlert.classList.add('d-none');
    errorAlert.textContent = '';
  };

  participatesToggle.addEventListener('change', () => {
    roleWrap.classList.toggle('d-none', !participatesToggle.checked);
  });

  const loadUniversities = async () => {
    try {
      const response = await fetch('/api/admin/universities', { credentials: 'same-origin' });
      const data = await response.json();
      const universities = Array.isArray(data) ? data : [];
      universitySelect.innerHTML = '<option value="">Select a university...</option>';
      universities.forEach((uni) => {
        const option = document.createElement('option');
        option.value = uni.universityId || uni.id || '';
        option.textContent = uni.name;
        universitySelect.appendChild(option);
      });
    } catch (error) {
      universitySelect.innerHTML = '<option value="">Select a university...</option>';
    }
  };

  form.addEventListener('submit', async (event) => {
    event.preventDefault();
    clearError();

    const data = Object.fromEntries(new FormData(form).entries());
    if (new Date(data.startDate) >= new Date(data.endDate)) {
      showError('End date must be after start date');
      return;
    }
    if (new Date(data.startDate) <= new Date()) {
      showError('Start date must be in the future');
      return;
    }

    submitButton.disabled = true;
    submitButton.textContent = 'Creating...';

    try {
      const payload = {
        title: data.title,
        description: data.description,
        location: data.location,
        startDate: data.startDate,
        endDate: data.endDate,
        type: data.type,
        universityId: data.universityId ? parseInt(data.universityId, 10) : null,
        maxOrganizers: data.maxOrganizers ? parseInt(data.maxOrganizers, 10) : null,
        maxVolunteers: data.maxVolunteers ? parseInt(data.maxVolunteers, 10) : null,
        maxAttendees: data.maxAttendees ? parseInt(data.maxAttendees, 10) : null,
        organizerPoints: 50,
        volunteerPoints: 20,
        attendeePoints: 10,
        creatorParticipates: participatesToggle.checked,
        creatorRole: participatesToggle.checked ? roleSelect.value : null,
      };

      const response = await fetch('/api/events', {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').content,
        },
        body: JSON.stringify(payload),
      });

      const responseData = await response.json().catch(() => ({}));
      if (!response.ok) {
        const message = responseData.message || 'Failed to create event. Please check all fields and try again.';
        showError(message);
        return;
      }

      window.location.href = '/my-events';
    } catch (error) {
      showError('Failed to create event. Please check all fields and try again.');
    } finally {
      submitButton.disabled = false;
      submitButton.textContent = 'Create Event';
    }
  });

  loadUniversities();
});
