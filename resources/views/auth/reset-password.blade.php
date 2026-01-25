@extends('layouts.app')

@section('content')
<div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh; padding-top: 2rem; padding-bottom: 2rem;">
  <div class="card" style="max-width: 550px; width: 100%;">
    <div class="card-body p-5">
      <div id="resetPasswordValidating" class="text-center">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-3">Validating reset link...</p>
      </div>

      <div id="resetPasswordInvalid" class="text-center d-none">
        <div style="font-size: 4rem; margin-bottom: 1rem;">??</div>
        <h2 class="mb-3">Invalid or Expired Link</h2>
        <p class="text-muted mb-4">
          This password reset link is invalid or has expired. Reset links are only valid for 15 minutes.
        </p>
        <a href="/forgot-password" class="btn btn-primary me-2">Request New Link</a>
        <a href="/login" class="btn btn-outline-secondary">Back to Login</a>
      </div>

      <div id="resetPasswordSuccess" class="text-center d-none">
        <div style="font-size: 4rem; margin-bottom: 1rem;">?</div>
        <h2 class="mb-3">Password Reset Successful</h2>
        <p class="text-muted mb-4">Your password has been reset successfully. Redirecting to login...</p>
      </div>

      <div id="resetPasswordFormPanel" class="d-none">
        <div class="text-center mb-4">
          <h2 style="font-size: 2rem; font-weight: 700;">?? Reset Password</h2>
          <p class="text-muted">Enter your new password</p>
        </div>

        <div id="resetPasswordError" class="alert alert-danger d-none"></div>

        <form id="resetPasswordForm">
          <div class="mb-3">
            <label class="form-label">New Password</label>
            <input
              id="resetPassword"
              type="password"
              class="form-control"
              placeholder="Enter new password"
              required
              style="padding: 0.75rem; font-size: 1rem;"
            />
            <div class="mt-2">
              <div class="d-flex justify-content-between mb-1">
                <small>Password Strength:</small>
                <small id="resetPasswordStrengthLabel" class="text-secondary">none</small>
              </div>
              <div class="progress" style="height: 8px;">
                <div id="resetPasswordStrengthBar" class="progress-bar bg-secondary" style="width: 0%;"></div>
              </div>
            </div>
            <div class="text-muted d-block mt-2">
              <strong>Password must contain:</strong>
              <ul class="mb-0 mt-1" style="padding-left: 1.25rem; font-size: 0.875rem;">
                <li data-reset-rule="length">? At least 8 characters</li>
                <li data-reset-rule="uppercase">? One uppercase letter</li>
                <li data-reset-rule="lowercase">? One lowercase letter</li>
                <li data-reset-rule="number">? One number</li>
                <li data-reset-rule="special">? One special character</li>
              </ul>
            </div>
          </div>

          <div class="mb-4">
            <label class="form-label">Confirm Password</label>
            <input
              id="resetPasswordConfirm"
              type="password"
              class="form-control"
              placeholder="Confirm new password"
              required
              style="padding: 0.75rem; font-size: 1rem;"
            />
          </div>

          <button
            id="resetPasswordSubmit"
            type="submit"
            class="btn btn-primary w-100"
            style="padding: 0.875rem; font-size: 1.125rem; font-weight: 600;"
          >
            Reset Password
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="/js/auth/passwordValidator.js"></script>
<script src="/js/auth/reset-password.js"></script>
@endpush
