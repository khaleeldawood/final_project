@extends('layouts.app')

@section('content')
<div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
  <div class="text-center">
    <div class="spinner-border mb-3" role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
    <p>Processing authentication...</p>
  </div>
</div>
@endsection

@push('scripts')
<script src="/js/auth/oauth2-redirect.js"></script>
@endpush
