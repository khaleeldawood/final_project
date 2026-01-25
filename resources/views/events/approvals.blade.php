@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div class="alert alert-warning" style="font-weight: 500; border: 2px solid #ffc107; box-shadow: 0 2px 8px rgba(255, 193, 7, 0.2);">
    <strong>ñ???ƒ??ñ??® Admin/Supervisor View:</strong> Review and approve/reject pending events from your university.
  </div>
  <h2 class="mb-4" style="font-size: 2rem; font-weight: 700; color: var(--text-primary);">Event Approvals</h2>
  <div class="card" style="box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); border: none; border-radius: 1rem;">
    <div class="card-body">
      <div id="eventApprovalsLoading" class="text-center py-5 d-none">
        <div class="spinner-border text-primary"></div>
      </div>
      <div class="table-responsive d-none" id="eventApprovalsTableWrap">
        <table class="table table-hover">
          <thead style="background-color: var(--bg-tertiary);">
            <tr>
              <th style="font-weight: 600; padding: 1rem;">Title</th>
              <th style="font-weight: 600; padding: 1rem;">Creator</th>
              <th style="font-weight: 600; padding: 1rem;">Type</th>
              <th style="font-weight: 600; padding: 1rem;">Date</th>
              <th style="font-weight: 600; padding: 1rem;">Actions</th>
            </tr>
          </thead>
          <tbody id="eventApprovalsBody"></tbody>
        </table>
      </div>
      <div id="eventApprovalsEmpty" class="text-center py-5 d-none">
        <div style="font-size: 4rem; margin-bottom: 1rem; opacity: 0.3;">ƒ??</div>
        <h4 style="color: #6c757d; font-weight: 600;">All Clear!</h4>
        <p class="text-muted">No pending event approvals at the moment.</p>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="/js/utils/helpers.js"></script>
<script src="/js/events/approvals.js"></script>
@endpush
