@extends('layouts.app')

@section('content')
<div class="container py-4">
  <h2 class="mb-4">ñ??? System Analytics</h2>

  <div id="adminAnalyticsLoading" class="text-center py-5 d-none">
    <div class="spinner-border text-primary"></div>
  </div>

  <div id="adminAnalyticsContent" class="d-none">
    <div class="row g-4 mb-4">
      <div class="col-md-3">
        <div class="card text-center">
          <div class="card-body">
            <h3 id="analyticsUsers">0</h3>
            <p class="text-muted mb-0">Total Users</p>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card text-center">
          <div class="card-body">
            <h3 id="analyticsEvents">0</h3>
            <p class="text-muted mb-0">Total Events</p>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card text-center">
          <div class="card-body">
            <h3 id="analyticsBlogs">0</h3>
            <p class="text-muted mb-0">Total Blogs</p>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card text-center">
          <div class="card-body">
            <h3 id="analyticsUniversities">0</h3>
            <p class="text-muted mb-0">Universities</p>
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header"><h5 class="mb-0">Users by Role</h5></div>
      <div class="card-body">
        <div id="analyticsChart" style="display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem;align-items:end;height:260px;">
          <div class="text-center">
            <div class="analytics-bar bg-primary" data-role="students" style="height:20px;border-radius:0.5rem;"></div>
            <div class="mt-2">Students</div>
          </div>
          <div class="text-center">
            <div class="analytics-bar bg-success" data-role="supervisors" style="height:20px;border-radius:0.5rem;"></div>
            <div class="mt-2">Supervisors</div>
          </div>
          <div class="text-center">
            <div class="analytics-bar bg-danger" data-role="admins" style="height:20px;border-radius:0.5rem;"></div>
            <div class="mt-2">Admins</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="/js/admin/analytics.js"></script>
@endpush
