@extends('layouts.app')

@section('content')
  <div class="container d-flex align-items-center justify-content-center"
    style="min-height: 100vh; margin-top: 150px; margin-bottom: 100px;">
    <div class="card" style="max-width: 500px; width: 100%;">
      <div class="card-body p-5">
        <div class="text-center mb-4">
          <h2 style="font-size: 2rem; font-weight: 700;">?? UniHub</h2>
          <p class="text-muted">Login to your account</p>
        </div>

        <div id="loginSuccess" class="alert alert-success d-none"></div>
        <div id="loginError" class="alert alert-danger d-none">
          <div id="loginErrorMessage"></div>

        </div>

        <form id="loginForm">
          <div class="mb-4">
            <label class="form-label">Email</label>
            <input id="loginEmail" type="email" name="email" class="form-control" placeholder="your.email@university.edu"
              required />
          </div>

          <div class="mb-4">
            <label class="form-label">Password</label>
            <input id="loginPassword" type="password" name="password" class="form-control"
              placeholder="Enter your password" required />
          </div>

          <button id="loginSubmit" type="submit" class="btn btn-primary w-100 mb-3"
            style="padding: 0.875rem; font-size: 1.125rem; font-weight: 600;">
            Login
          </button>
        </form>



        <div class="text-center">
          <a href="/forgot-password" class="text-decoration-none">?? Forgot password?</a>
          <div class="mt-3">
            <a href="/register" class="text-decoration-none">Don't have an account? Register here</a>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script src="/js/auth/login.js"></script>
@endpush