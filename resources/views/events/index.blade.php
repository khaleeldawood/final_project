@extends('layouts.app')

@section('content')
  <div class="container py-4" style="margin-top: 100px;">
    <div class="row mb-4">
      <div class="col">
        <div class="d-flex justify-content-between align-items-center">
          <h2><i class="bi bi-calendar-event"></i> Events</h2>
          <a href="/events/new" class="btn btn-primary" data-auth="user">Create Event</a>
        </div>
      </div>
    </div>

    <div class="card events-filters mb-4" style="box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
      <div class="card-body">
        <h5 class="mb-3"><i class="bi bi-funnel"></i> Filters</h5>
        <div class="row g-3">
          <div class="col-md-3" id="eventsStatusFilterWrap" hidden>
            <label class="form-label" style="font-weight: 600; font-size: 0.95rem;">Status</label>
            <select id="eventsStatusFilter" class="form-select" style="border: 2px solid #dee2e6; border-radius: 0.5rem;">
              <option value="">All Statuses</option>
              <option value="APPROVED">✓ Approved</option>
              <option value="PENDING">⏰ Pending</option>
              <option value="REJECTED">✗ Rejected</option>
              <option value="CANCELLED">⊘ Cancelled</option>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label" style="font-weight: 600; font-size: 0.95rem;">University</label>
            <select id="eventsUniversityFilter" class="form-select"
              style="border: 2px solid #dee2e6; border-radius: 0.5rem;">
              <option value="">All Universities</option>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label" style="font-weight: 600; font-size: 0.95rem;">Time</label>
            <select id="eventsTimeFilter" class="form-select" style="border: 2px solid #dee2e6; border-radius: 0.5rem;">
              <option value="ALL">All Events</option>
              <option value="ACTIVE">📡 Active Now</option>
              <option value="FUTURE">📅 Future Events</option>
              <option value="COMPLETED">✔ Completed</option>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label" style="font-weight: 600; font-size: 0.95rem;">Type</label>
            <select id="eventsTypeFilter" class="form-select" style="border: 2px solid #dee2e6; border-radius: 0.5rem;">
              <option value="">All Types</option>
              <option value="Workshop">🔧 Workshop</option>
              <option value="Seminar">📢 Seminar</option>
              <option value="Conference">👥 Conference</option>
              <option value="Meetup">💬 Meetup</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label" style="font-weight: 600; font-size: 0.95rem;">Search</label>
            <input id="eventsSearchFilter" type="text" class="form-control"
              placeholder="🔍 Search events by title or description..."
              style="border: 2px solid #dee2e6; border-radius: 0.5rem;">
          </div>
        </div>
      </div>
    </div>

    <div id="eventsLoading" class="text-center py-5 d-none">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <div id="eventsList" class="row g-3"></div>

    <div id="eventsEmpty" class="card d-none">
      <div class="card-body text-center py-5">
        <p class="text-muted mb-0">No events found</p>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script src="/js/utils/helpers.js"></script>
  <script src="/js/events/index.js"></script>
@endpush