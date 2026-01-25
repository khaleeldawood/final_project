@extends('layouts.app')

@section('content')
    <div class="container d-flex align-items-center justify-content-center"
        style="min-height: 100vh; margin-top: 150px; margin-bottom: 100px;">
        <div class="card" style="max-width: 500px; width: 100%;">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <h2 style="font-size: 2rem; font-weight: 700;">?? UniHub</h2>
                    <p class="text-muted">Create your account</p>
                </div>



                <div id="registerFormPanel">
                    <div id="registerError" class="alert alert-danger d-none"></div>

                    <form id="registerForm">
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input id="registerName" type="text" name="name" class="form-control custom-input"
                                placeholder="John Doe" required minlength="2" />
                        </div>

                        <div class="mb-3">
                            <label class="form-label">University Email</label>
                            <input id="registerEmail" type="email" name="email" class="form-control custom-input"
                                placeholder="your.email@university.edu" required />
                            <div id="registerEmailError" class="text-danger d-block mt-1 d-none"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">University</label>
                            <select id="registerUniversity" name="universityId" class="form-select custom-input">
                                <option value="">Select University</option>
                            </select>
                            <div id="registerUniversityHint" class="text-muted d-block mt-1 d-none"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input id="registerPassword" type="password" name="password" class="form-control custom-input"
                                placeholder="Create a strong password" required />
                            <div class="text-muted d-block mt-2" style="font-size: 0.875rem;">
                                <strong>Password must contain:</strong>
                                <ul class="mb-0 mt-1" style="padding-left: 1.25rem;">
                                    <li data-password-rule="length">? At least 8 characters</li>
                                    <li data-password-rule="uppercase">? One uppercase letter (A-Z)</li>
                                    <li data-password-rule="lowercase">? One lowercase letter (a-z)</li>
                                    <li data-password-rule="number">? One number (0-9)</li>
                                    <li data-password-rule="special">? One special character (!@#$%...)</li>
                                </ul>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Confirm Password</label>
                            <input id="registerConfirmPassword" type="password" name="confirmPassword"
                                class="form-control custom-input" placeholder="Re-enter password" required />
                        </div>

                        <button id="registerSubmit" type="submit" class="btn btn-primary w-100 mb-3"
                            style="padding: 0.875rem; font-size: 1.125rem; font-weight: 600;">
                            Register
                        </button>
                    </form>

                    <div class="text-center">
                        <a href="/login" class="text-decoration-none">Already have an account? Login here</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="/js/auth/register.js"></script>
@endpush