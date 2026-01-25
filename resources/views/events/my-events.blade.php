@extends('layouts.app')

@section('content')
<div class="container py-4">
  <h2 class="mb-4">ñ??? My Events</h2>
  <div class="card">
    <div class="card-body">
      <div id="myEventsLoading" class="text-center py-5 d-none">
        <div class="spinner-border text-primary"></div>
      </div>
      <div id="myEventsEmpty" class="text-center text-muted py-5 d-none">No events created yet</div>
      <div class="table-responsive">
        <table class="table table-hover" id="myEventsTable">
          <thead>
            <tr>
              <th>Title</th>
              <th>Type</th>
              <th>Start Date</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="/js/utils/helpers.js"></script>
<script src="/js/events/my-events.js"></script>
@endpush
