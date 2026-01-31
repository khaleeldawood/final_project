@extends('layouts.app')

@section('content')
  <div class="container py-4" style="margin-top: 100px;">
    <div class="row mb-4">
      <div class="col">
        <h2><i class="bi bi-award-fill"></i> Badges</h2>
        <p class="text-muted" id="badgesSubtitle">Register to start earning badges!</p>
        <div id="badgesProgress" class="alert alert-info d-none"
          style="background-color: rgba(6, 182, 212, 0.15); border-color: rgba(6, 182, 212, 0.3);">
          <strong style="color: var(--text-primary);">Your Progress:</strong> <span style="color: var(--text-primary);"
            id="badgesPoints">0 points earned</span>
        </div>
      </div>
    </div>

    <div id="badgesLoading" class="text-center py-5 d-none">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <div id="badgesGrid" class="row g-4"></div>

    <div id="badgesCallout" class="row mt-4 d-none">
      <div class="col">
        <div class="card bg-light">
          <div class="card-body text-center">
            <h5>Want to earn badges?</h5>
            <p class="text-muted mb-3">Register now to start participating and unlocking achievements!</p>
            <a class="btn btn-primary" href="/register">Register Now</a>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script src="/js/utils/helpers.js"></script>
  <script src="/js/badges/index.js"></script>
@endpush