@extends('layouts.app')

@section('content')
<div class="container py-5">
  <div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-body p-5 text-center">
      <div style="font-size: 4rem;">ñ???</div>
      <h1 class="mb-4">Contact Us</h1>
      <p class="lead text-muted mb-4">Have questions or need support? We're here to help!</p>

      <div class="mb-4">
        <h5>Email</h5>
        <a href="mailto:unihubplat@gmail.com" style="font-size: 1.2rem; color: var(--primary-color);">
          unihubplat@gmail.com
        </a>
      </div>

      <a class="btn btn-primary btn-lg" href="mailto:unihubplat@gmail.com">Send Email</a>

      <div class="mt-5 text-muted">
        <p class="mb-1">We typically respond within 24-48 hours</p>
        <p>Monday - Friday, 9:00 AM - 5:00 PM</p>
      </div>
    </div>
  </div>
</div>
@endsection
