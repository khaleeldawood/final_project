@extends('layouts.app')

@section('content')
<div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
  <div class="card" style="max-width: 500px; width: 100%;">
    <div class="card-body text-center p-5">
      <div class="spinner-border text-primary mb-3" role="status" id="verifyEmailSpinner">
        <span class="visually-hidden">Loading...</span>
      </div>
      <div id="verifyEmailStatusIcon" class="mb-3"></div>
      <h4 id="verifyEmailStatusText">Verifying your email...</h4>
      <div id="verifyEmailAlert" class="alert d-none mt-3"></div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="/js/auth/verify-email.js"></script>
@endpush
