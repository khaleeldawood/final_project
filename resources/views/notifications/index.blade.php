@extends('layouts.app')

@section('content')
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2><i class="bi bi-bell-fill"></i> Notifications</h2>
      <button id="notificationsMarkAll" class="btn btn-outline-primary btn-sm d-none">Mark All as Read</button>
    </div>
    <div class="card">
      <ul class="list-group list-group-flush" id="notificationsList"></ul>
    </div>
  </div>
@endsection

@push('scripts')
  <script src="/js/utils/helpers.js"></script>
  <script src="/js/notifications/index.js"></script>
@endpush