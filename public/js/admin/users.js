const usersTableBody = document.getElementById('usersTableBody');
const successAlert = document.getElementById('successAlert');
const errorAlert = document.getElementById('errorAlert');
const successAlertMessage = document.getElementById('successAlertMessage');
const errorAlertMessage = document.getElementById('errorAlertMessage');
const addSupervisorButton = document.getElementById('addSupervisorButton');
const addSupervisorForm = document.getElementById('addSupervisorForm');
const supervisorUniversity = document.getElementById('supervisorUniversity');
const editUserForm = document.getElementById('editUserForm');
const editUserRole = document.getElementById('editUserRole');
const editUserPoints = document.getElementById('editUserPoints');
const confirmDeleteButton = document.getElementById('confirmDeleteButton');

const addSupervisorModal = new bootstrap.Modal(document.getElementById('addSupervisorModal'));
const editUserModal = new bootstrap.Modal(document.getElementById('editUserModal'));
const deleteUserModal = new bootstrap.Modal(document.getElementById('deleteUserModal'));

const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

let universitiesMap = {};
let activeUserId = null;
let deleteUserId = null;

function showAlert(type, message) {
    if (type === 'success') {
        successAlertMessage.textContent = message;
        successAlert.classList.remove('d-none');
        errorAlert.classList.add('d-none');
    } else {
        errorAlertMessage.textContent = message;
        errorAlert.classList.remove('d-none');
        successAlert.classList.add('d-none');
    }
}

async function loadUniversities() {
    const response = await fetch('/admin/universities', {
        headers: {
            'Accept': 'application/json'
        }
    });

    if (!response.ok) {
        showAlert('error', 'Failed to load universities');
        return;
    }

    const universities = await response.json();
    universitiesMap = {};
    supervisorUniversity.innerHTML = '<option value="">No University</option>';

    universities.forEach((university) => {
        universitiesMap[university.id] = university.name;
        const option = document.createElement('option');
        option.value = university.id;
        option.textContent = university.name;
        supervisorUniversity.appendChild(option);
    });
}

function roleBadge(role) {
    if (role === 'ADMIN') {
        return '<span class="badge text-bg-danger">ADMIN</span>';
    }
    if (role === 'SUPERVISOR') {
        return '<span class="badge text-bg-warning">SUPERVISOR</span>';
    }
    return '<span class="badge text-bg-primary">STUDENT</span>';
}

async function loadUsers() {
    const response = await fetch('/admin/users', {
        headers: {
            'Accept': 'application/json'
        }
    });

    if (!response.ok) {
        showAlert('error', 'Failed to load users');
        return;
    }

    const users = await response.json();
    usersTableBody.innerHTML = '';

    users.forEach((user) => {
        const tr = document.createElement('tr');
        const universityName = user.university_id ? (universitiesMap[user.university_id] || 'No University') : 'No University';

        tr.innerHTML = `
            <td>${user.name ?? ''}</td>
            <td>${user.email ?? ''}</td>
            <td>${universityName}</td>
            <td>${roleBadge(user.role)}</td>
            <td>${user.points ?? 0}</td>
            <td>
                <button class="btn btn-outline-primary btn-sm me-2" data-action="edit">Edit</button>
                <button class="btn btn-outline-danger btn-sm" data-action="delete">Delete</button>
            </td>
        `;

        tr.querySelector('[data-action="edit"]').addEventListener('click', () => {
            activeUserId = user.id;
            editUserRole.value = user.role || 'STUDENT';
            editUserPoints.value = user.points ?? 0;
            editUserModal.show();
        });

        tr.querySelector('[data-action="delete"]').addEventListener('click', () => {
            deleteUserId = user.id;
            deleteUserModal.show();
        });

        usersTableBody.appendChild(tr);
    });
}

addSupervisorButton.addEventListener('click', () => {
    addSupervisorForm.reset();
    supervisorUniversity.value = '';
    addSupervisorModal.show();
});

addSupervisorForm.addEventListener('submit', async (event) => {
    event.preventDefault();

    const payload = {
        name: document.getElementById('supervisorName').value,
        email: document.getElementById('supervisorEmail').value,
        password: document.getElementById('supervisorPassword').value,
        role: 'SUPERVISOR',
        universityId: supervisorUniversity.value || null
    };

    const response = await fetch('/admin/users', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify(payload)
    });

    if (!response.ok) {
        showAlert('error', 'Failed to create user');
        return;
    }

    addSupervisorModal.hide();
    showAlert('success', 'User created successfully');
    await loadUsers();
});

editUserForm.addEventListener('submit', async (event) => {
    event.preventDefault();

    if (!activeUserId) {
        return;
    }

    const payload = {
        role: editUserRole.value,
        points: editUserPoints.value
    };

    const response = await fetch(`/admin/users/${activeUserId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify(payload)
    });

    if (!response.ok) {
        showAlert('error', 'Failed to update user');
        return;
    }

    editUserModal.hide();
    showAlert('success', 'User updated successfully');
    await loadUsers();
});

confirmDeleteButton.addEventListener('click', async () => {
    if (!deleteUserId) {
        return;
    }

    const response = await fetch(`/admin/users/${deleteUserId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    });

    if (!response.ok) {
        showAlert('error', 'Failed to delete user');
        return;
    }

    deleteUserModal.hide();
    showAlert('success', 'User deleted successfully');
    await loadUsers();
});

document.addEventListener('DOMContentLoaded', async () => {
    await loadUniversities();
    await loadUsers();
});
