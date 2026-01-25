@extends('layouts.app')

@section('content')
<div class="container py-4 dashboard-page" style="margin-top: 100px;">
  <div id="dashboardLoading" class="text-center py-5">
    <div class="spinner-border text-primary" role="status"></div>
  </div>

  <div id="dashboardContent" class="d-none">
    <div class="row mb-4">
      <div class="col">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h2 id="dashboardWelcome" style="font-size: 2.5rem; font-weight: 700;"></h2>
            <div class="d-flex align-items-center gap-2">
              <p id="dashboardUniversity" class="text-muted mb-0" style="font-size: 1.125rem;"></p>
              <span id="dashboardRoleBadge" class="badge" style="font-size: 0.9rem; font-weight: 600; padding: 0.5rem 1rem;"></span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row g-3 mb-4">
      <div class="col-6 col-md-3">
        <div class="card h-100 text-center">
          <div class="card-body">
            <div class="display-6 mb-2">&#11088;</div>
            <h3 class="mb-0" id="dashboardPoints">0</h3>
            <p class="text-muted mb-0">Total Points</p>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="card h-100 text-center">
          <div class="card-body">
            <div class="display-6 mb-2">&#127941;</div>
            <h3 class="mb-0"><span id="dashboardBadge" class="badge"></span></h3>
            <p class="text-muted mb-0">Current Badge</p>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="card h-100 text-center">
          <div class="card-body">
            <div class="display-6 mb-2">&#128197;</div>
            <h3 class="mb-0" id="dashboardEventsCount">0</h3>
            <p class="text-muted mb-0">My Events</p>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="card h-100 text-center">
          <div class="card-body">
            <div class="display-6 mb-2">&#128221;</div>
            <h3 class="mb-0" id="dashboardBlogsCount">0</h3>
            <p class="text-muted mb-0">My Blogs</p>
          </div>
        </div>
      </div>
    </div>

    <div id="dashboardApprovals" class="mb-4 d-none">
      <div class="mb-3">
        <h4 id="dashboardApprovalsTitle" style="font-weight: 700; color: var(--warning-color);"></h4>
      </div>
      <div class="row g-4">
        <div class="col-md-6">
          <div class="card border-warning" style="border-width: 3px; box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3); background: linear-gradient(135deg, #ffffff 0%, #fff9e6 100%);">
            <div class="card-body">
              <h5 style="font-weight: 700; margin-bottom: 1rem;">&#9888; Pending Event Approvals</h5>
              <h2 id="dashboardPendingEvents" style="font-size: 3rem; font-weight: 700; color: var(--warning-color); margin-bottom: 1rem;">0</h2>
              <a href="/events/approvals" class="btn btn-warning" style="font-weight: 600; padding: 0.625rem 1.25rem;">Review Events</a>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card border-warning" style="border-width: 3px; box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3); background: linear-gradient(135deg, #ffffff 0%, #fff9e6 100%);">
            <div class="card-body">
              <h5 style="font-weight: 700; margin-bottom: 1rem;">&#9888; Pending Blog Approvals</h5>
              <h2 id="dashboardPendingBlogs" style="font-size: 3rem; font-weight: 700; color: var(--warning-color); margin-bottom: 1rem;">0</h2>
              <a href="/blogs/approvals" class="btn btn-warning" style="font-weight: 600; padding: 0.625rem 1.25rem;">Review Blogs</a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row g-3">
      <div class="col-12 col-lg-6">
        <div class="card" style="box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); border: none; border-radius: 1rem; min-height: 400px; display: flex; flex-direction: column;">
          <div class="card-header" style="background-color: var(--bg-tertiary); border-bottom: 2px solid var(--border-color); padding: 1rem;">
            <div class="d-flex justify-content-between align-items-center">
              <h5 class="mb-0" style="font-weight: 700;">&#128197; My Recent Events</h5>
              <a href="/my-events" class="btn btn-link btn-sm" style="font-weight: 600;">View All (<span id="dashboardEventsCountLink">0</span>)</a>
            </div>
          </div>
          <div class="card-body" id="dashboardEventsList"></div>
        </div>
      </div>

      <div class="col-12 col-lg-6">
        <div class="card" style="box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); border: none; border-radius: 1rem; min-height: 400px; display: flex; flex-direction: column;">
          <div class="card-header" style="background-color: var(--bg-tertiary); border-bottom: 2px solid var(--border-color); padding: 1rem;">
            <div class="d-flex justify-content-between align-items-center">
              <h5 class="mb-0" style="font-weight: 700;">&#128221; My Recent Blogs</h5>
              <a href="/my-blogs" class="btn btn-link btn-sm" style="font-weight: 600;">View All (<span id="dashboardBlogsCountLink">0</span>)</a>
            </div>
          </div>
          <div class="card-body" id="dashboardBlogsList"></div>
        </div>
      </div>

      <div class="col-12 col-lg-6">
        <div class="card" style="box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); border: none; border-radius: 1rem; min-height: 400px; display: flex; flex-direction: column;">
          <div class="card-header" style="background-color: var(--bg-tertiary); border-bottom: 2px solid var(--border-color); padding: 1rem;">
            <div class="d-flex justify-content-between align-items-center">
              <h5 class="mb-0" style="font-weight: 700;">&#127942; Top Contributors</h5>
              <a href="/leaderboard" class="btn btn-link btn-sm" style="font-weight: 600;">View Full Leaderboard</a>
            </div>
          </div>
          <div class="card-body" id="dashboardTopMembers"></div>
        </div>
      </div>

      <div class="col-12 col-lg-6">
        <div class="card" style="box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); border: none; border-radius: 1rem; min-height: 400px; display: flex; flex-direction: column;">
          <div class="card-header" style="background-color: var(--bg-tertiary); border-bottom: 2px solid var(--border-color); padding: 1rem;">
            <div class="d-flex justify-content-between align-items-center">
              <h5 class="mb-0" style="font-weight: 700;">&#128276; Recent Notifications</h5>
              <a href="/notifications" class="btn btn-link btn-sm" style="font-weight: 600;">View All</a>
            </div>
          </div>
          <div class="card-body" id="dashboardNotifications"></div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="/js/utils/helpers.js"></script>
<script src="/js/dashboard/index.js"></script>
@endpush