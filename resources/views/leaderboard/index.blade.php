@extends('layouts.app')

@section('content')
<div class="container py-4" style="margin-top: 100px;">
  <div class="row mb-4 align-items-center">
    <div class="col">
      <h2>ñ??? Leaderboard</h2>
      <p class="text-muted">See top contributors and events</p>
    </div>
    <div class="col-md-auto">
      <div id="leaderboardRankCard" class="card text-white bg-primary d-none">
        <div class="card-body py-2 px-4 text-center">
          <small>Your Rank</small>
          <h3 class="mb-0" id="leaderboardRankValue">#</h3>
        </div>
      </div>
    </div>
  </div>

  <div class="row mb-4">
    <div class="col-md-6">
      <div class="card">
        <div class="card-body">
          <label class="form-label">Leaderboard Type</label>
          <div class="btn-group w-100" role="group">
            <button class="btn btn-primary" id="leaderboardTypeMembers">ñ??? Members</button>
            <button class="btn btn-outline-primary" id="leaderboardTypeEvents">ñ??? Events</button>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card">
        <div class="card-body">
          <label class="form-label">Scope</label>
          <div class="btn-group w-100" role="group">
            <button class="btn btn-outline-primary" id="leaderboardScopeUniversity">ñ??® My University</button>
            <button class="btn btn-primary" id="leaderboardScopeGlobal">ñ??? Global</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <h5 class="mb-0" id="leaderboardTitle"></h5>
    </div>
    <div class="card-body">
      <div id="leaderboardLoading" class="text-center py-5 d-none">
        <div class="spinner-border text-primary"></div>
      </div>
      <div class="table-responsive">
        <table class="table table-hover" id="leaderboardTable">
          <thead>
            <tr id="leaderboardHeadRow"></tr>
          </thead>
          <tbody id="leaderboardBody"></tbody>
        </table>
      </div>
      <div id="leaderboardEmpty" class="text-center text-muted py-5 d-none">No data available</div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="/js/utils/helpers.js"></script>
<script src="/js/leaderboard/index.js"></script>
@endpush
