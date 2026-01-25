@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
      <h3>ƒ??‹?? Edit Event</h3>
    </div>
    <div class="card-body">
      <div id="editEventError" class="alert alert-danger d-none"></div>
      <div id="editEventWarning" class="alert alert-warning d-none">
        ƒ?ÿ‹?? <strong>Note:</strong> Editing an approved event will reset its status to PENDING and require re-approval by a supervisor.
      </div>
      <form id="editEventForm" class="d-none">
        <div class="mb-3">
          <label class="form-label">Event Title *</label>
          <input type="text" name="title" class="form-control" required minlength="3" />
        </div>
        <div class="mb-3">
          <label class="form-label">Description *</label>
          <textarea name="description" class="form-control" rows="4" required></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">University *</label>
          <select name="universityId" id="editEventUniversity" class="form-select" required>
            <option value="">Select a university...</option>
          </select>
          <div class="form-text text-muted">Select the university where this event will take place</div>
        </div>
        <div class="mb-3">
          <label class="form-label">Location (Venue) *</label>
          <input type="text" name="location" class="form-control" required placeholder="e.g., Main Auditorium, Building A" />
          <div class="form-text text-muted">Specific venue or building at the selected university</div>
        </div>
        <div class="mb-3">
          <label class="form-label">Event Type *</label>
          <select name="type" class="form-select" required>
            <option value="Workshop">Workshop</option>
            <option value="Seminar">Seminar</option>
            <option value="Conference">Conference</option>
            <option value="Meetup">Meetup</option>
            <option value="Competition">Competition</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Start Date & Time *</label>
          <input type="datetime-local" name="startDate" class="form-control" required style="color-scheme: light dark;" />
        </div>
        <div class="mb-3">
          <label class="form-label">End Date & Time *</label>
          <input type="datetime-local" name="endDate" class="form-control" required style="color-scheme: light dark;" />
        </div>
        <hr class="my-4" />
        <h5 class="mb-3">ñ??? Capacity Limits</h5>
        <div class="row mb-3">
          <div class="col-md-4">
            <label class="form-label">Max Organizers</label>
            <input type="number" name="maxOrganizers" class="form-control" min="0" placeholder="Unlimited" />
          </div>
          <div class="col-md-4">
            <label class="form-label">Max Volunteers</label>
            <input type="number" name="maxVolunteers" class="form-control" min="0" placeholder="Unlimited" />
          </div>
          <div class="col-md-4">
            <label class="form-label">Max Attendees</label>
            <input type="number" name="maxAttendees" class="form-control" min="0" placeholder="Unlimited" />
          </div>
        </div>
        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-primary" id="editEventSubmit">Save Changes</button>
          <a href="/my-events" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="/js/events/edit.js"></script>
@endpush
