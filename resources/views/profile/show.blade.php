@extends('layouts.app')

@section('content')
<div class="container py-4" style="margin-top: 100px;">
  <div id="profileLoading" class="text-center py-5">
    <div class="spinner-border text-primary" role="status"></div>
  </div>

  <div id="profileContent" class="d-none">
    <div class="card mb-4">
      <div class="card-body text-center">
        <div class="display-1 mb-3">&#128100;</div>
        <h2 id="profileName"></h2>
        <p id="profileEmail" class="text-muted"></p>
        <span id="profileRole" class="badge mb-2"></span>
        <div class="mt-3">
          <h4 class="mb-3"><span id="profilePoints">0</span> Points</h4>
          <div class="d-flex justify-content-center gap-2 mb-3">
            <span id="profileRank" class="badge bg-info fs-6 d-none"></span>
            <span id="profileBadge" class="badge fs-6 d-none"></span>
          </div>
        </div>
        <p id="profileUniversity" class="text-muted mt-2"></p>
      </div>
    </div>

    <div class="row g-4" id="profileExtras">
      <div class="col-md-6" id="profileBadgesSection">
        <div class="card">
          <div class="card-header">
            <h5 class="mb-0">&#127941; Earned Badges</h5>
          </div>
          <div class="card-body">
            <div id="profileBadgesList" class="d-flex flex-wrap gap-2"></div>
            <div class="text-center mt-3">
              <a href="/badges" class="btn btn-outline-primary btn-sm">View All Badges</a>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6" id="profileStatusSection">
        <div class="card">
          <div class="card-header">
            <h5 class="mb-0">Status</h5>
          </div>
          <div class="card-body">
            <div class="d-flex justify-content-around text-center">
              <div>
                <h3 id="profileEventsCount">0</h3>
                <p class="text-muted mb-0 small">Events</p>
              </div>
              <div>
                <h3 id="profileBlogsCount">0</h3>
                <p class="text-muted mb-0 small">Blogs</p>
              </div>
              <div>
                <h3 id="profileBadgesCount">0</h3>
                <p class="text-muted mb-0 small">Badges</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6" id="profileRecentEventsSection">
        <div class="card">
          <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
              <h5 class="mb-0">&#128197; Recent Events</h5>
              <a href="/my-events" class="btn btn-link btn-sm">View All</a>
            </div>
          </div>
          <div class="card-body">
            <ul id="profileRecentEvents" class="list-group list-group-flush"></ul>
          </div>
        </div>
      </div>

      <div class="col-md-6" id="profileRecentBlogsSection">
        <div class="card">
          <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
              <h5 class="mb-0">&#128221; Recent Blogs</h5>
              <a href="/my-blogs" class="btn btn-link btn-sm">View All</a>
            </div>
          </div>
          <div class="card-body">
            <ul id="profileRecentBlogs" class="list-group list-group-flush"></ul>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="profileNotFound" class="d-none">
    <div class="card">
      <div class="card-body text-center py-5">
        <p class="text-muted">User not found</p>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="/js/utils/helpers.js"></script>
<script src="/js/profile/show.js"></script>
@endpush
