@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div id="myEventRequestsAlert"></div>
  <h2 class="mb-4">Event Participation Requests (<span id="myEventRequestsCount">0</span>)</h2>
  <div id="myEventRequestsLoading" class="text-center py-5 d-none">
    <div class="spinner-border text-primary"></div>
  </div>
  <div id="myEventRequestsEmpty" class="card d-none">
    <div class="card-body text-center py-5">
      <p class="text-muted mb-0">No pending participation requests</p>
    </div>
  </div>
  <div id="myEventRequestsWrap"></div>
</div>
@endsection

@push('scripts')
<script src="/js/utils/helpers.js"></script>
<script src="/js/events/my-event-requests.js"></script>
@endpush
