@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div id="participationAlert"></div>
  <h2 class="mb-4">My Participation Requests</h2>
  <div class="card">
    <div class="card-body">
      <div id="participationLoading" class="text-center py-5 d-none">
        <div class="spinner-border text-primary"></div>
      </div>
      <div class="table-responsive" id="participationTableWrap">
        <table class="table table-hover">
          <thead style="background-color: var(--bg-tertiary);">
            <tr>
              <th style="font-weight: 600; padding: 1rem;">Event</th>
              <th style="font-weight: 600; padding: 1rem;">Role</th>
              <th style="font-weight: 600; padding: 1rem;">Status</th>
              <th style="font-weight: 600; padding: 1rem;">Requested</th>
              <th style="font-weight: 600; padding: 1rem;">Actions</th>
            </tr>
          </thead>
          <tbody id="participationBody"></tbody>
        </table>
      </div>
      <div id="participationEmpty" class="text-center text-muted py-5 d-none">No participation requests yet</div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="/js/utils/helpers.js"></script>
<script src="/js/events/participation-requests.js"></script>
@endpush
