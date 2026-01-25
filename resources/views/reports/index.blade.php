@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div id="reportsAlerts"></div>
  <div class="row mb-4">
    <div class="col">
      <h2>Content Reports</h2>
      <p class="text-muted">Manage reported events and blogs</p>
    </div>
  </div>

  <div class="card mb-4">
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label" style="font-weight:600;">Report Type</label>
          <select id="reportsTypeFilter" class="form-select">
            <option value="ALL">All Reports</option>
            <option value="EVENT">Event Reports</option>
            <option value="BLOG">Blog Reports</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label" style="font-weight:600;">Status</label>
          <select id="reportsStatusFilter" class="form-select">
            <option value="ALL">All Statuses</option>
            <option value="PENDING">Pending</option>
            <option value="REVIEWED">Resolved</option>
            <option value="DISMISSED">Dismissed</option>
          </select>
        </div>
      </div>
    </div>
  </div>

  <div id="reportsLoading" class="text-center py-5 d-none">
    <div class="spinner-border text-primary"></div>
  </div>

  <div id="eventReportsCard" class="card mb-4 d-none">
    <div class="card-header">
      <h5 class="mb-0">Event Reports (<span id="eventReportsCount">0</span>)</h5>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover mb-0 text-center">
          <thead>
            <tr>
              <th>Event</th>
              <th>Reported By</th>
              <th>Reason</th>
              <th>Date</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="eventReportsBody"></tbody>
        </table>
      </div>
    </div>
  </div>

  <div id="blogReportsCard" class="card mb-4 d-none">
    <div class="card-header">
      <h5 class="mb-0">Blog Reports (<span id="blogReportsCount">0</span>)</h5>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover mb-0 text-center">
          <thead>
            <tr>
              <th>Blog</th>
              <th>Reported By</th>
              <th>Reason</th>
              <th>Date</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="blogReportsBody"></tbody>
        </table>
      </div>
    </div>
  </div>

  <div id="reportsEmpty" class="card d-none">
    <div class="card-body text-center py-5">
      <p class="text-muted mb-0">No reports found</p>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="/js/utils/helpers.js"></script>
<script src="/js/reports/index.js"></script>
@endpush
