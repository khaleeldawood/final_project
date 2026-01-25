document.addEventListener('DOMContentLoaded', () => {
  const eventId = parseInt(window.location.pathname.split('/').slice(-2)[0], 10);
  const form = document.getElementById('editEventForm');
  const errorAlert = document.getElementById('editEventError');
  const warningAlert = document.getElementById('editEventWarning');
  const submitButton = document.getElementById('editEventSubmit');
  const universitySelect = document.getElementById('editEventUniversity');

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

  const loadUniversities = async () => {
    try {
      const data = await fetchJson('/api/admin/universities');
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

  const formatDateForInput = (value) => {
    if (!value) return '';
    const date = new Date(value);
    if (Number.isNaN(date.getTime())) return '';
    return date.toISOString().slice(0, 16);
  };

  const loadEvent = async () => {
    try {
      const event = await fetchJson(`/api/events/${eventId}`);

      if (event.creator?.userId !== (JSON.parse(localStorage.getItem('user') || '{}').userId || null)) {
        showError('You do not have permission to edit this event');
        return;
      }

      if (event.status !== 'PENDING' && event.status !== 'APPROVED') {
        showError('Only pending or approved events can be edited');
        return;
      }

      warningAlert.classList.toggle('d-none', event.status !== 'APPROVED');

      form.title.value = event.title || '';
      form.description.value = event.description || '';
      form.location.value = event.location || '';
      form.startDate.value = formatDateForInput(event.startDate);
      form.endDate.value = formatDateForInput(event.endDate);
      form.type.value = event.type || 'Workshop';
      form.universityId.value = event.university?.universityId || '';
      form.maxOrganizers.value = event.maxOrganizers || '';
      form.maxVolunteers.value = event.maxVolunteers || '';
      form.maxAttendees.value = event.maxAttendees || '';

      form.classList.remove('d-none');
    } catch (error) {
      showError('Failed to load event details');
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
    submitButton.textContent = 'Saving...';

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
      };

      await fetchJson(`/api/events/${eventId}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').content,
        },
        body: JSON.stringify(payload),
      });

      window.location.href = '/my-events';
    } catch (error) {
      showError(error.message || 'Failed to update event. Please try again.');
    } finally {
      submitButton.disabled = false;
      submitButton.textContent = 'Save Changes';
    }
  });

  loadUniversities().then(loadEvent);
});
