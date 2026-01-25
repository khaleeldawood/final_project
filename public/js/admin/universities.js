document.addEventListener('DOMContentLoaded', () => {
  const body = document.getElementById('adminUniversitiesBody');
  const alerts = document.getElementById('adminUniversitiesAlerts');
  const loading = document.getElementById('adminUniversitiesLoading');
  const addButton = document.getElementById('adminUniversitiesAdd');

  const modalElement = document.getElementById('adminUniversityModal');
  const deleteModalElement = document.getElementById('adminUniversityDeleteModal');
  const modal = new bootstrap.Modal(modalElement);
  const deleteModal = new bootstrap.Modal(deleteModalElement);
  const modalTitle = document.getElementById('adminUniversityModalTitle');
  const nameInput = document.getElementById('adminUniversityName');
  const descriptionInput = document.getElementById('adminUniversityDescription');
  const logoInput = document.getElementById('adminUniversityLogoUrl');
  const saveButton = document.getElementById('adminUniversitySave');
  const deleteConfirmButton = document.getElementById('adminUniversityDeleteConfirm');

  let currentUniversity = null;

  const setLoading = (isLoading) => {
    loading.classList.toggle('d-none', !isLoading);
  };

  const showAlert = (type, message) => {
    if (!message) {
      alerts.innerHTML = '';
      return;
    }
    alerts.innerHTML = `<div class="alert alert-${type}" role="alert">${message}</div>`;
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

  const load = async () => {
    setLoading(true);
    body.innerHTML = '';
    showAlert('', '');
    try {
      const universities = await fetchJson('/api/admin/universities');
      (Array.isArray(universities) ? universities : []).forEach((uni) => {
        const row = document.createElement('tr');
        row.innerHTML = `
          <td><strong>${uni.name}</strong></td>
          <td>${uni.description || ''}</td>
          <td>
            <div class="d-flex gap-2">
              <button class="btn btn-outline-primary btn-sm" data-action="edit" data-id="${uni.universityId || uni.id}">Edit</button>
              <button class="btn btn-outline-danger btn-sm" data-action="delete" data-id="${uni.universityId || uni.id}">Delete</button>
            </div>
          </td>
        `;
        row.dataset.payload = JSON.stringify(uni);
        body.appendChild(row);
      });
    } catch (error) {
      showAlert('danger', error.message);
    } finally {
      setLoading(false);
    }
  };

  addButton.addEventListener('click', () => {
    currentUniversity = null;
    modalTitle.textContent = 'Add New University';
    nameInput.value = '';
    descriptionInput.value = '';
    logoInput.value = '';
    saveButton.textContent = 'Add University';
    modal.show();
  });

  body.addEventListener('click', (event) => {
    const target = event.target;
    if (!(target instanceof HTMLElement)) return;
    const action = target.dataset.action;
    if (!action) return;
    const row = target.closest('tr');
    const payload = row ? JSON.parse(row.dataset.payload) : null;
    if (!payload) return;

    if (action === 'edit') {
      currentUniversity = payload;
      modalTitle.textContent = 'Edit University';
      nameInput.value = payload.name || '';
      descriptionInput.value = payload.description || '';
      logoInput.value = payload.logoUrl || payload.logo_url || '';
      saveButton.textContent = 'Save Changes';
      modal.show();
    }

    if (action === 'delete') {
      currentUniversity = payload;
      deleteModal.show();
    }
  });

  saveButton.addEventListener('click', async () => {
    showAlert('', '');
    const payload = {
      name: nameInput.value,
      description: descriptionInput.value,
      logoUrl: logoInput.value,
    };
    try {
      if (currentUniversity) {
        await fetchJson(`/api/admin/universities/${currentUniversity.universityId || currentUniversity.id}`, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').content,
          },
          body: JSON.stringify(payload),
        });
        showAlert('success', 'University updated successfully');
      } else {
        await fetchJson('/api/admin/universities', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').content,
          },
          body: JSON.stringify(payload),
        });
        showAlert('success', 'University added successfully');
      }
      modal.hide();
      load();
    } catch (error) {
      showAlert('danger', error.message);
    }
  });

  deleteConfirmButton.addEventListener('click', async () => {
    if (!currentUniversity) return;
    try {
      await fetchJson(`/api/admin/universities/${currentUniversity.universityId || currentUniversity.id}`, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').content,
        },
      });
      showAlert('success', 'University deleted successfully');
      deleteModal.hide();
      load();
    } catch (error) {
      showAlert('danger', error.message);
      deleteModal.hide();
    }
  });

  load();
});
