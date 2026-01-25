@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div id="adminUniversitiesAlerts"></div>
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2>ñ??® University Management</h2>
    <button class="btn btn-outline-primary" id="adminUniversitiesAdd">Add University</button>
  </div>

  <div class="card">
    <div class="card-body">
      <div id="adminUniversitiesLoading" class="text-center py-5 d-none">
        <div class="spinner-border text-primary"></div>
      </div>
      <div class="table-responsive">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>Name</th>
              <th>Description</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="adminUniversitiesBody"></tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="modal fade" id="adminUniversityModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content" style="background-color:var(--bg-secondary);color:var(--text-primary);">
        <div class="modal-header" style="border-color:var(--border-color);">
          <h5 class="modal-title" id="adminUniversityModalTitle">Edit University</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">University Name</label>
            <input type="text" class="form-control" id="adminUniversityName" required />
          </div>
          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea class="form-control" rows="3" id="adminUniversityDescription"></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Logo URL</label>
            <input type="text" class="form-control" id="adminUniversityLogoUrl" placeholder="https://example.com/logo.png" />
            <div class="form-text text-muted">Optional: URL to university logo image</div>
          </div>
        </div>
        <div class="modal-footer" style="border-color:var(--border-color);">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary" id="adminUniversitySave">Save Changes</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="adminUniversityDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content" style="background-color:var(--bg-secondary);color:var(--text-primary);">
        <div class="modal-header" style="border-color:var(--border-color);">
          <h5 class="modal-title">Delete University</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to delete this university? This action cannot be undone.</p>
        </div>
        <div class="modal-footer" style="border-color:var(--border-color);">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-danger" id="adminUniversityDeleteConfirm">Delete</button>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="/js/admin/universities.js"></script>
@endpush
