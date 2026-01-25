@extends('layouts.app')

@section('content')
  <div class="container py-4" style="margin-top: 100px;">
    <h2 class="mb-4">&#9881; Settings</h2>

    <div id="settingsAlert"></div>



    <div class="card mb-4">
      <div class="card-header">
        <h5 class="mb-0">University</h5>
      </div>
      <div class="card-body">
        <form id="settingsUniversityForm">
          <div class="mb-3">
            <label class="form-label" for="settingsUniversitySelect">Select Your University</label>
            <select id="settingsUniversitySelect" class="form-select" required>
              <option value="">Choose a university...</option>
            </select>
            <div class="form-text text-muted" id="settingsUniversityCurrent"></div>
          </div>
          <button type="submit" class="btn btn-primary" id="settingsUniversitySubmit">Update University</button>
        </form>
      </div>
    </div>

    <div class="card mb-4">
      <div class="card-header">
        <h5 class="mb-0">Profile Information</h5>
      </div>
      <div class="card-body">
        <form id="settingsProfileForm">
          <div class="mb-3">
            <label class="form-label" for="settingsName">Full Name</label>
            <input type="text" id="settingsName" class="form-control" required minlength="2" />
          </div>
          <button type="submit" class="btn btn-primary" id="settingsProfileSubmit">Update Name</button>
        </form>
      </div>
    </div>

    <div class="card mb-4">
      <div class="card-header">
        <h5 class="mb-0">Notification Preferences</h5>
      </div>
      <div class="card-body">
        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" id="settingsNotificationsToggle" />
          <label class="form-check-label" for="settingsNotificationsToggle">Enable badge promotion pop-ups</label>
        </div>
        <small class="text-muted">When enabled, you'll see a pop-up modal when you earn a new badge</small>
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <h5 class="mb-0" id="settingsPasswordTitle">Change Password</h5>
      </div>
      <div class="card-body">

        <form id="settingsPasswordForm">
          <div class="mb-3" id="settingsOldPasswordGroup">
            <label class="form-label" for="settingsOldPassword">Current Password</label>
            <input type="password" id="settingsOldPassword" class="form-control" />
          </div>
          <div class="mb-3">
            <label class="form-label" for="settingsNewPassword">New Password</label>
            <input type="password" id="settingsNewPassword" class="form-control" required />
          </div>
          <div class="mb-3">
            <label class="form-label" for="settingsConfirmPassword">Confirm New Password</label>
            <input type="password" id="settingsConfirmPassword" class="form-control" required />
          </div>
          <button type="submit" class="btn btn-primary" id="settingsPasswordSubmit">Update Password</button>
        </form>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script src="/js/settings/index.js"></script>
@endpush