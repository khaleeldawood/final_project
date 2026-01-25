@extends('layouts.app')

@section('content')
<div class="container py-5">
  <div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-body p-5 text-center">
      <div style="font-size: 4rem;">ñ???</div>
      <h1 class="mb-4">Support</h1>
      <p class="lead text-muted mb-4">Need technical assistance or have an issue?</p>

      <div class="mb-4">
        <h5>Support Email</h5>
        <a href="mailto:unihubplat@gmail.com" style="font-size: 1.2rem; color: var(--primary-color);">
          unihubplat@gmail.com
        </a>
      </div>

      <a class="btn btn-primary btn-lg" href="mailto:unihubplat@gmail.com?subject=Support Request">Request Support</a>

      <div class="mt-5">
        <h5>Before contacting support:</h5>
        <ul class="text-start" style="max-width: 400px; margin: 1rem auto; color: var(--text-primary);">
          <li>Check the FAQ page</li>
          <li>Review the Guidelines</li>
          <li>Try refreshing the page</li>
          <li>Clear your browser cache</li>
        </ul>
      </div>
    </div>
  </div>
</div>
@endsection
