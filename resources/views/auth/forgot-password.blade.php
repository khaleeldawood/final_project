@extends('layouts.app')

@section('content')
  <div id="forgotPasswordContainer" class="container d-flex align-items-center justify-content-center"
    style="min-height: 100vh; margin-top: 150px; margin-bottom: 100px;">
    <div class="card" style="max-width: 500px; width: 100%;">
      <div class="card-body p-5">
        <div id="forgotPasswordFormPanel">
          <div class="text-center mb-4">
            <h2 style="font-size: 2rem; font-weight: 700;"><i class="bi bi-key"></i> Forgot Password</h2>
            <p class="text-muted">Enter your email to receive a reset link</p>
          </div>

          <div id="forgotPasswordError" class="alert alert-danger d-none"></div>

          <form id="forgotPasswordForm">
            <div class="mb-4">
              <label class="form-label">Email Address</label>
              <input id="forgotPasswordEmail" type="email" class="form-control" placeholder="your.email@university.edu"
                required />
            </div>

            <button id="forgotPasswordSubmit" type="submit" class="btn btn-primary w-100 mb-3"
              style="padding: 0.875rem; font-size: 1.125rem; font-weight: 600;">
              Send Reset Link
            </button>
          </form>

          <div class="text-center">
            <a href="/login" class="text-decoration-none"><i class="bi bi-arrow-left"></i> Back to Login</a>
          </div>
        </div>

        <div id="forgotPasswordSuccessPanel" class="text-center d-none">
          <div style="font-size: 4rem; margin-bottom: 1rem;"><i class="bi bi-envelope-check"></i></div>
          <h2 class="mb-3">Check Your Email</h2>
          <p class="text-muted mb-4">
            If an account exists with <strong id="forgotPasswordSuccessEmail"></strong>, you will receive a password reset
            link shortly.
          </p>
          <p class="text-muted mb-4">The link will expire in 15 minutes.</p>
          <a href="/login" class="btn btn-primary">Back to Login</a>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script src="/js/auth/forgot-password.js"></script>
@endpush