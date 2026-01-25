@extends('layouts.app')

@section('content')
<style>
  @keyframes gradientMove {
    0%, 100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
  }

  @keyframes float {
    0%, 100% { transform: translateY(0) translateX(0); }
    25% { transform: translateY(-20px) translateX(10px); }
    50% { transform: translateY(-10px) translateX(-10px); }
    75% { transform: translateY(-30px) translateX(5px); }
  }

  .animated-bg {
    background: linear-gradient(-45deg, rgba(99, 102, 241, 0.1), rgba(139, 92, 246, 0.1), rgba(236, 72, 153, 0.1), rgba(6, 182, 212, 0.1));
    background-size: 400% 400%;
    animation: gradientMove 15s ease infinite;
    min-height: 100vh;
    position: relative;
    overflow: hidden;
  }

  .spirit {
    position: absolute;
    border-radius: 50%;
    filter: blur(40px);
    opacity: 0.6;
    animation: float 20s ease-in-out infinite;
  }

  .spirit:nth-child(1) { width: 200px; height: 200px; background: radial-gradient(circle, rgba(99, 102, 241, 0.4), transparent); top: 10%; left: 10%; animation-duration: 18s; }
  .spirit:nth-child(2) { width: 150px; height: 150px; background: radial-gradient(circle, rgba(236, 72, 153, 0.4), transparent); top: 60%; right: 15%; animation-duration: 22s; animation-delay: -5s; }
  .spirit:nth-child(3) { width: 180px; height: 180px; background: radial-gradient(circle, rgba(139, 92, 246, 0.4), transparent); bottom: 20%; left: 20%; animation-duration: 25s; animation-delay: -10s; }
  .spirit:nth-child(4) { width: 120px; height: 120px; background: radial-gradient(circle, rgba(6, 182, 212, 0.4), transparent); top: 30%; right: 30%; animation-duration: 20s; animation-delay: -15s; }

  .fade-in-up { opacity: 0; transform: translateY(30px); transition: all 0.8s ease; }
  .fade-in-up.visible { opacity: 1; transform: translateY(0); }
  .feature-card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
  .feature-card:hover { transform: translateY(-10px); box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
</style>

<div class="animated-bg">
  <div class="spirit"></div>
  <div class="spirit"></div>
  <div class="spirit"></div>
  <div class="spirit"></div>

  <div class="container py-5" style="position: relative; z-index: 1;">
    <div class="text-center mb-5 fade-in-up" data-animate>
      <h1 class="mb-3">ƒ?? Features</h1>
      <p class="lead text-muted">Discover what makes UniHub powerful</p>
    </div>

    <div class="row">
      <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100 feature-card fade-in-up" data-animate>
          <div class="card-body text-center p-4">
            <div style="font-size: 3rem;">ñ???</div>
            <h4 class="mt-3">Event Management</h4>
            <p class="text-muted">Create and manage university events with role-based participation</p>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100 feature-card fade-in-up" data-animate>
          <div class="card-body text-center p-4">
            <div style="font-size: 3rem;">ñ???</div>
            <h4 class="mt-3">Blog System</h4>
            <p class="text-muted">Share knowledge through blogs with approval workflow</p>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100 feature-card fade-in-up" data-animate>
          <div class="card-body text-center p-4">
            <div style="font-size: 3rem;">ñ???</div>
            <h4 class="mt-3">Gamification</h4>
            <p class="text-muted">Earn points, badges, and climb leaderboards</p>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100 feature-card fade-in-up" data-animate>
          <div class="card-body text-center p-4">
            <div style="font-size: 3rem;">ñ???</div>
            <h4 class="mt-3">Real-time Notifications</h4>
            <p class="text-muted">Stay updated with WebSocket notifications</p>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100 feature-card fade-in-up" data-animate>
          <div class="card-body text-center p-4">
            <div style="font-size: 3rem;">ñ???</div>
            <h4 class="mt-3">Role-based Access</h4>
            <p class="text-muted">Student, Faculty, and Admin roles with permissions</p>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100 feature-card fade-in-up" data-animate>
          <div class="card-body text-center p-4">
            <div style="font-size: 3rem;">ñ???</div>
            <h4 class="mt-3">Analytics Dashboard</h4>
            <p class="text-muted">Track engagement and system statistics</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  document.querySelectorAll('[data-animate]').forEach((element) => {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
        }
      });
    }, { threshold: 0.1 });
    observer.observe(element);
  });
</script>
@endpush
