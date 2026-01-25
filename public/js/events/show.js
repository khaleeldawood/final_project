document.addEventListener('DOMContentLoaded', () => {
  const root = document.getElementById('eventDetailsRoot');
  const eventId = parseInt(window.location.pathname.split('/').pop(), 10);

  const storedUser = (() => {
    try {
      return JSON.parse(localStorage.getItem('user'));
    } catch (error) {
      return null;
    }
  })();

  const fetchJson = async (url, options = {}) => {
    const response = await fetch(url, { credentials: 'same-origin', ...options });
    const data = await response.json().catch(() => null);
    if (!response.ok) {
      const message = data?.message || 'Request failed';
      throw new Error(message);
    }
    return data;
  };

  const render = (event, participants, userRequest) => {
    const isCompleted = new Date(event.endDate) < new Date();
    const userParticipation = storedUser
      ? participants.find((p) => p.user.userId === storedUser.userId)
      : null;

    const reportBadge = event.reportCount > 0
      ? `<div style="position:absolute;bottom:5px;right:5px;width:28px;height:28px;background-color:#dc3545;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:0.875rem;font-weight:700;z-index:10;box-shadow:0 2px 4px rgba(0,0,0,0.2)">${event.reportCount}</div>`
      : '';

    root.innerHTML = `
      <div class="row">
        <div class="col-md-8">
          <div class="card mb-4" style="position:relative;overflow:hidden;">
            ${reportBadge}
            <div class="card-header">
              <div class="d-flex justify-content-between align-items-start">
                <div>
                  <h3 class="mb-0">${event.title || ''}</h3>
                  ${isCompleted ? '<span class="badge bg-dark mt-2">Event Completed</span>' : ''}
                </div>
                <span class="badge bg-${getStatusVariant(event.status)}">${event.status}</span>
              </div>
            </div>
            <div class="card-body">
              <div class="mb-3">
                <span class="badge bg-secondary">${event.type || ''}</span>
              </div>
              <p class="lead">${event.description || ''}</p>
              <hr style="border-color:var(--border-color);opacity:1;" />
              <div class="mb-3">
                <strong>University:</strong><br />
                <span style="color:var(--text-primary)">${event.university?.name || 'N/A'}</span>
              </div>
              <div class="mb-3">
                <strong>Location:</strong><br />
                <span style="color:var(--text-primary)">${event.location || ''}</span>
              </div>
              <div class="mb-3">
                <strong>Organizer:</strong><br />
                <span style="color:var(--text-primary)">${event.creator?.name || ''}</span>
              </div>
              <div class="mb-3">
                <strong>Event Timeline:</strong>
                <div class="mt-3" style="position:relative;padding:10px 30px 70px 30px;max-width:90%;margin:0 auto;">
                  <div style="position:absolute;top:10px;left:30px;right:30px;height:8px;background-color:var(--border-color);border-radius:4px;">
                    <div id="eventProgressBar" style="position:absolute;left:0;top:0;height:100%;width:0;background-color:transparent;border-radius:4px;transition:width 0.3s ease;"></div>
                  </div>
                  <div style="position:absolute;left:30px;top:10px;transform:translateY(-50%);">
                    <div style="width:16px;height:16px;background-color:#0d6efd;border-radius:50%;border:3px solid var(--bg-primary);box-shadow:0 2px 4px rgba(0,0,0,0.2)"></div>
                    <div style="position:absolute;top:20px;left:50%;transform:translateX(-50%);font-size:0.8rem;color:var(--text-primary);text-align:center;white-space:nowrap;font-weight:600;">
                      <strong>Start</strong><br />
                      <span style="font-size:0.7rem;color:var(--text-secondary);font-weight:400;">${formatDate(event.startDate)}</span>
                    </div>
                  </div>
                  <div id="eventNowMarker"></div>
                  <div style="position:absolute;right:30px;top:10px;transform:translateY(-50%);">
                    <div style="width:16px;height:16px;background-color:#dc3545;border-radius:50%;border:3px solid var(--bg-primary);box-shadow:0 2px 4px rgba(0,0,0,0.2)"></div>
                    <div style="position:absolute;top:20px;left:50%;transform:translateX(-50%);font-size:0.8rem;color:var(--text-primary);text-align:center;white-space:nowrap;font-weight:600;">
                      <strong>End</strong><br />
                      <span style="font-size:0.7rem;color:var(--text-secondary);font-weight:400;">${formatDate(event.endDate)}</span>
                    </div>
                  </div>
                </div>
              </div>
              ${(event.maxOrganizers || event.maxVolunteers || event.maxAttendees) ? `
                <hr style="border-color:var(--border-color);opacity:1;" />
                <h5 class="mb-3">Event Capacity</h5>
                <div class="row">
                  ${event.maxOrganizers ? `<div class="col-md-4"><strong>Organizers:</strong><br /><span style="color:var(--text-primary)">${participants.filter((p) => p.role === 'ORGANIZER').length} / ${event.maxOrganizers}</span><div class="small text-muted">(${event.organizerPoints} pts)</div></div>` : ''}
                  ${event.maxVolunteers ? `<div class="col-md-4"><strong>Volunteers:</strong><br /><span style="color:var(--text-primary)">${participants.filter((p) => p.role === 'VOLUNTEER').length} / ${event.maxVolunteers}</span><div class="small text-muted">(${event.volunteerPoints} pts)</div></div>` : ''}
                  ${event.maxAttendees ? `<div class="col-md-4"><strong>Attendees:</strong><br /><span style="color:var(--text-primary)">${participants.filter((p) => p.role === 'ATTENDEE').length} / ${event.maxAttendees}</span><div class="small text-muted">(${event.attendeePoints} pts)</div></div>` : ''}
                </div>
              ` : ''}
            </div>
          </div>
          ${storedUser && event.status === 'APPROVED' ? `
            ${userParticipation ? `
              <div class="card mb-3 border-success">
                <div class="card-body">
                  <h5>Г?? You are participating in this event</h5>
                  <p>Role: <span class="badge bg-${getRoleVariant(userParticipation.role)}">${userParticipation.role}</span></p>
                  <button id="eventLeaveButton" class="btn btn-danger btn-sm">Leave Event</button>
                </div>
              </div>
            ` : userRequest ? `
              <div class="card mb-3 border-warning">
                <div class="card-body">
                  <h5>Г?? Request Pending</h5>
                  <p>You have requested to join as: <span class="badge bg-warning">${userRequest.requestedRole}</span></p>
                  <p class="text-muted small mb-0">The event organizer will review your request.</p>
                </div>
              </div>
            ` : `
              <div class="card mb-3">
                <div class="card-body">
                  <h5>Request to Join This Event</h5>
                  <p>Submit a request to participate and the organizer will approve it!</p>
                  <div class="d-flex gap-2 flex-wrap">
                    ${(!event.maxOrganizers || participants.filter((p) => p.role === 'ORGANIZER').length < event.maxOrganizers) ? `<button class="btn btn-danger" data-role="ORGANIZER">Organizer (${event.organizerPoints} pts)</button>` : ''}
                    ${(!event.maxVolunteers || participants.filter((p) => p.role === 'VOLUNTEER').length < event.maxVolunteers) ? `<button class="btn btn-success" data-role="VOLUNTEER">Volunteer (${event.volunteerPoints} pts)</button>` : ''}
                    ${(!event.maxAttendees || participants.filter((p) => p.role === 'ATTENDEE').length < event.maxAttendees) ? `<button class="btn btn-info" data-role="ATTENDEE">Attendee (${event.attendeePoints} pts)</button>` : ''}
                  </div>
                </div>
              </div>
            `}
          ` : ''}
          ${(storedUser && (event.status === 'PENDING' || event.status === 'APPROVED') && !isCompleted) ? `
            <div class="card mb-3">
              <div class="card-body">
                <h5>Report This Event</h5>
                <p class="text-muted small">If you find this event inappropriate or violates community guidelines</p>
                <button id="eventReportButton" class="btn btn-warning btn-sm">Report Event</button>
              </div>
            </div>
          ` : ''}
          ${(storedUser && storedUser.role === 'ADMIN' && event.status !== 'CANCELLED') ? `
            <div class="card">
              <div class="card-body">
                <h5>Admin Actions</h5>
                <div class="d-flex gap-2 flex-wrap">
                  ${event.status === 'APPROVED' ? '<button id="eventCancelButton" class="btn btn-warning btn-sm">Cancel Event</button>' : ''}
                  ${(event.status === 'PENDING' || event.status === 'REJECTED') ? '<button id="eventDeleteButton" class="btn btn-danger btn-sm">Delete Event</button>' : ''}
                </div>
              </div>
            </div>
          ` : ''}
        </div>
        <div class="col-md-4">
          <div class="card">
            <div class="card-header">
              <h5 class="mb-0">Participants (${participants.length})</h5>
            </div>
            <div class="card-body" style="max-height:400px;overflow-y:auto;">
              ${participants.map((p) => `
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span style="color:var(--text-primary)">${p.user?.name || ''}</span>
                  <span class="badge bg-${getRoleVariant(p.role)}">${p.role}</span>
                </div>
              `).join('')}
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" id="eventJoinModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content" style="background-color:var(--bg-secondary);color:var(--text-primary);">
            <div class="modal-header" style="border-color:var(--border-color);">
              <h5 class="modal-title">Request to Join Event</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <p>You are requesting to join as: <span id="eventJoinRoleBadge" class="badge bg-info">ATTENDEE</span></p>
              <p>You will earn <strong id="eventJoinPoints">0</strong> points once approved!</p>
              <div class="alert alert-info mb-0" style="background-color:rgba(6,182,212,0.15);color:var(--text-primary);border-color:rgba(6,182,212,0.3);">
                The event organizer will review your request before you can participate.
              </div>
            </div>
            <div class="modal-footer" style="border-color:var(--border-color);">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn-primary" id="eventJoinSubmit">Submit Request</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" id="eventLeaveModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content" style="background-color:var(--bg-secondary);color:var(--text-primary);">
            <div class="modal-header" style="border-color:var(--border-color);">
              <h5 class="modal-title">Г? Л?? Leave Event</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="alert alert-warning" style="background-color:rgba(255,193,7,0.15);color:var(--text-primary);border-color:rgba(255,193,7,0.3);">
                <strong>Warning:</strong> You will be penalized 2x the points you earned from this event!
              </div>
              <p>Are you sure you want to leave this event?</p>
            </div>
            <div class="modal-footer" style="border-color:var(--border-color);">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn-danger" id="eventLeaveSubmit">Leave Event</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" id="eventReportModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content" style="background-color:var(--bg-secondary);color:var(--text-primary);">
            <div class="modal-header" style="border-color:var(--border-color);">
              <h5 class="modal-title">Report Event</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label class="form-label">Reason for reporting</label>
                <textarea id="eventReportReason" class="form-control" rows="4" placeholder="Please explain why you are reporting this event..."></textarea>
              </div>
            </div>
            <div class="modal-footer" style="border-color:var(--border-color);">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn-danger" id="eventReportSubmit">Submit Report</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" id="eventCancelModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content" style="background-color:var(--bg-secondary);color:var(--text-primary);">
            <div class="modal-header" style="border-color:var(--border-color);">
              <h5 class="modal-title">Cancel Event</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <p>Are you sure you want to cancel this event?</p>
            </div>
            <div class="modal-footer" style="border-color:var(--border-color);">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn-warning" id="eventCancelSubmit">Cancel Event</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" id="eventDeleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content" style="background-color:var(--bg-secondary);color:var(--text-primary);">
            <div class="modal-header" style="border-color:var(--border-color);">
              <h5 class="modal-title">Delete Event</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <p>Are you sure you want to delete this event? This action cannot be undone.</p>
            </div>
            <div class="modal-footer" style="border-color:var(--border-color);">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn-danger" id="eventDeleteSubmit">Delete Event</button>
            </div>
          </div>
        </div>
      </div>
      <style>
        @keyframes pulse {
          0%, 100% { box-shadow: 0 2px 8px rgba(40, 167, 69, 0.5); }
          50% { box-shadow: 0 2px 16px rgba(40, 167, 69, 0.8); }
        }
      </style>
    `;

    const updateTimeline = () => {
      const start = new Date(event.startDate);
      const end = new Date(event.endDate);
      const now = new Date();
      const total = end - start;
      const elapsed = now - start;
      const progress = Math.min(Math.max((elapsed / total) * 100, 0), 100);
      const bar = document.getElementById('eventProgressBar');
      if (bar) {
        bar.style.width = `${progress}%`;
        bar.style.backgroundColor = progress >= 100 ? '#6c757d' : progress > 0 ? '#28a745' : 'transparent';
      }
      const marker = document.getElementById('eventNowMarker');
      if (marker && progress > 0 && progress < 100) {
        marker.innerHTML = `
          <div style="position:absolute;left:calc(30px + ${progress}% * (100% - 60px) / 100%);top:10px;transform:translate(-50%, -50%);">
            <div style="width:20px;height:20px;background-color:#28a745;border-radius:50%;border:3px solid var(--bg-primary);box-shadow:0 2px 8px rgba(40,167,69,0.5);animation:pulse 2s infinite;"></div>
            <div style="position:absolute;bottom:20px;left:50%;transform:translateX(-50%);white-space:nowrap;font-size:0.8rem;font-weight:700;color:#28a745;">Now</div>
          </div>
        `;
      }
    };

    updateTimeline();

    const joinButtons = root.querySelectorAll('[data-role]');
    const joinModal = new bootstrap.Modal(document.getElementById('eventJoinModal'));
    const joinRoleBadge = document.getElementById('eventJoinRoleBadge');
    const joinPoints = document.getElementById('eventJoinPoints');
    let selectedRole = 'ATTENDEE';

    joinButtons.forEach((button) => {
      button.addEventListener('click', () => {
        selectedRole = button.getAttribute('data-role');
        joinRoleBadge.textContent = selectedRole;
        joinRoleBadge.className = `badge bg-${getRoleVariant(selectedRole)}`;
        const pointsKey = `${selectedRole.toLowerCase()}Points`;
        joinPoints.textContent = event[pointsKey] || 0;
        joinModal.show();
      });
    });

    const leaveButton = document.getElementById('eventLeaveButton');
    const leaveModalElement = document.getElementById('eventLeaveModal');
    const leaveModal = leaveModalElement ? new bootstrap.Modal(leaveModalElement) : null;
    if (leaveButton && leaveModal) {
      leaveButton.addEventListener('click', () => leaveModal.show());
    }

    const reportButton = document.getElementById('eventReportButton');
    const reportModalElement = document.getElementById('eventReportModal');
    const reportModal = reportModalElement ? new bootstrap.Modal(reportModalElement) : null;
    if (reportButton && reportModal) {
      reportButton.addEventListener('click', () => reportModal.show());
    }

    const cancelButton = document.getElementById('eventCancelButton');
    const cancelModalElement = document.getElementById('eventCancelModal');
    const cancelModal = cancelModalElement ? new bootstrap.Modal(cancelModalElement) : null;
    if (cancelButton && cancelModal) {
      cancelButton.addEventListener('click', () => cancelModal.show());
    }

    const deleteButton = document.getElementById('eventDeleteButton');
    const deleteModalElement = document.getElementById('eventDeleteModal');
    const deleteModal = deleteModalElement ? new bootstrap.Modal(deleteModalElement) : null;
    if (deleteButton && deleteModal) {
      deleteButton.addEventListener('click', () => deleteModal.show());
    }

    document.getElementById('eventJoinSubmit')?.addEventListener('click', async () => {
      try {
        await fetchJson(`/api/event-participation-requests/events/${eventId}`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').content,
          },
          body: JSON.stringify({ requestedRole: selectedRole }),
        });
        joinModal.hide();
        window.location.reload();
      } catch (error) {
        alert(error.message);
      }
    });

    document.getElementById('eventLeaveSubmit')?.addEventListener('click', async () => {
      try {
        await fetchJson(`/api/events/${eventId}/leave`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').content,
          },
        });
        leaveModal.hide();
        window.location.reload();
      } catch (error) {
        alert(error.message);
      }
    });

    document.getElementById('eventReportSubmit')?.addEventListener('click', async () => {
      const reason = document.getElementById('eventReportReason').value.trim();
      if (!reason) {
        alert('Please provide a reason for reporting');
        return;
      }
      try {
        await fetchJson(`/api/reports/events/${eventId}`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').content,
          },
          body: JSON.stringify({ reason }),
        });
        reportModal.hide();
        window.location.reload();
      } catch (error) {
        alert(error.message);
      }
    });

    document.getElementById('eventCancelSubmit')?.addEventListener('click', async () => {
      try {
        await fetchJson(`/api/events/${eventId}/cancel`, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').content,
          },
          body: JSON.stringify({ reason: '' }),
        });
        cancelModal.hide();
        window.location.reload();
      } catch (error) {
        alert(error.message);
      }
    });

    document.getElementById('eventDeleteSubmit')?.addEventListener('click', async () => {
      try {
        await fetchJson(`/api/events/${eventId}`, {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').content,
          },
        });
        deleteModal.hide();
        window.location.href = '/events';
      } catch (error) {
        alert(error.message);
      }
    });
  };

  const load = async () => {
    root.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>';
    try {
      const event = await fetchJson(`/api/events/${eventId}`);
      let participants = [];
      try {
        participants = await fetchJson(`/api/events/${eventId}/participants`);
      } catch (error) {
        participants = [];
      }

      let userRequest = null;
      if (storedUser) {
        try {
          const requests = await fetchJson('/api/event-participation-requests/my-requests');
          userRequest = requests.find((req) => req.eventId === eventId && req.status === 'PENDING') || null;
        } catch (error) {
          userRequest = null;
        }
      }

      render(event, participants, userRequest);
    } catch (error) {
      root.innerHTML = '<div class="alert alert-danger">Failed to load event details</div>';
    }
  };

  load();
});
