@extends('layouts.app')

@section('content')
<style>
  .fade-in-up {
    opacity: 0;
    transform: translateY(30px);
    transition: opacity 0.8s ease, transform 0.8s ease;
  }

  .fade-in-up.visible {
    opacity: 1;
    transform: translateY(0);
  }

  .team-card {
    position: relative;
    transition: transform 0.3s ease;
  }

  .team-card:hover {
    transform: translateY(-10px);
  }

  .particle {
    position: absolute;
    width: 4px;
    height: 4px;
    background: var(--primary-color);
    border-radius: 50%;
    pointer-events: none;
    animation: particleFloat 1s ease-out forwards;
  }

  @keyframes particleFloat {
    0% {
      opacity: 1;
      transform: translate(0, 0) scale(1);
    }
    100% {
      opacity: 0;
      transform: translate(var(--tx), var(--ty)) scale(0);
    }
  }
</style>

<div class="container py-5">
  <div class="text-center mb-5 fade-in-up" data-animate>
    <h1 class="text-gradient mb-3">About UniHub</h1>
    <p class="lead" style="color: var(--text-secondary);">
      A comprehensive university portal platform connecting students, faculty, and administrators
    </p>
  </div>

  <div class="row justify-content-center mb-5">
    <div class="col-md-8">
      <div class="card mb-4 fade-in-up" data-animate>
        <div class="card-body p-4">
          <h3 class="mb-3" style="color: var(--text-secondary);">Our Mission</h3>
          <p style="color: var(--text-secondary);">
            UniHub is designed to create a centralized platform for university communities to connect,
            collaborate, and engage through events, blogs, and gamification features. We aim to enhance
            the university experience by making it easier for students and faculty to participate in
            campus activities and share knowledge.
          </p>
        </div>
      </div>
    </div>
  </div>

  <div class="text-center mb-4 fade-in-up" data-animate>
    <h2 class="mb-4">Meet Our Team</h2>
    <p style="color: var(--text-secondary);">
      Developed by a dedicated team of four developers
    </p>
  </div>

  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-3 mb-4">
      <div class="card h-100 text-center team-card fade-in-up" data-animate>
        <div class="card-body d-flex flex-column align-items-center justify-content-center p-4">
          <img
            src="/images/team/Ahmed.jpg"
            alt="Ahmed Rawashdeh"
            class="mb-3"
            style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid var(--primary-color);"
          />
          <h5 class="mb-2">Ahmed Rawashdeh</h5>
          <p class="text-muted small mb-3">SDE at Amazon</p>
          <div class="d-flex flex-column gap-2">
            <a href="https://www.linkedin.com/in/ahmed-rawashdeh-893295292" target="_blank" rel="noopener noreferrer" class="btn btn-outline-primary btn-sm">
              <i class="bi bi-linkedin"></i> LinkedIn
            </a>
            <a href="https://github.com/ahmedyraw/" target="_blank" rel="noopener noreferrer" class="btn btn-outline-dark btn-sm">
              <i class="bi bi-github"></i> GitHub
            </a>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-6 col-lg-3 mb-4">
      <div class="card h-100 text-center team-card fade-in-up" data-animate>
        <div class="card-body d-flex flex-column align-items-center justify-content-center p-4">
          <img
            src="/images/team/Hussam.jpg"
            alt="Hussam Nafi"
            class="mb-3"
            style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid var(--primary-color);"
          />
          <h5 class="mb-2">Hussam Nafi</h5>
          <p class="text-muted small mb-3">FullStack Engineer at Estarta</p>
          <div class="d-flex flex-column gap-2">
            <a href="https://www.linkedin.com/in/hussam-nafi-6b8498379/" target="_blank" rel="noopener noreferrer" class="btn btn-outline-primary btn-sm">
              <i class="bi bi-linkedin"></i> LinkedIn
            </a>
            <a href="https://github.com/Hussam-Nafi" target="_blank" rel="noopener noreferrer" class="btn btn-outline-dark btn-sm">
              <i class="bi bi-github"></i> GitHub
            </a>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-6 col-lg-3 mb-4">
      <div class="card h-100 text-center team-card fade-in-up" data-animate>
        <div class="card-body d-flex flex-column align-items-center justify-content-center p-4">
          <img
            src="/images/team/Khaleel.jpg"
            alt="Khaleel Ayash"
            class="mb-3"
            style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid var(--primary-color);"
          />
          <h5 class="mb-2">Khaleel Ayash</h5>
          <p class="text-muted small mb-3">Backend Engineer at Orange</p>
          <div class="d-flex flex-column gap-2">
            <a href="https://www.linkedin.com/in/khaleel-ayash/" target="_blank" rel="noopener noreferrer" class="btn btn-outline-primary btn-sm">
              <i class="bi bi-linkedin"></i> LinkedIn
            </a>
            <a href="https://github.com/khaleeldawood" target="_blank" rel="noopener noreferrer" class="btn btn-outline-dark btn-sm">
              <i class="bi bi-github"></i> GitHub
            </a>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-6 col-lg-3 mb-4">
      <div class="card h-100 text-center team-card fade-in-up" data-animate>
        <div class="card-body d-flex flex-column align-items-center justify-content-center p-4">
          <img
            src="/images/team/Mohammad.jpg"
            alt="Mohammad Abu Hammad"
            class="mb-3"
            style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid var(--primary-color);"
          />
          <h5 class="mb-2">Mohammad Abu Hammad</h5>
          <p class="text-muted small mb-3">Backend Engineer</p>
          <div class="d-flex flex-column gap-2">
            <a href="https://www.linkedin.com/in/mohammad-abu-hammad04/" target="_blank" rel="noopener noreferrer" class="btn btn-outline-primary btn-sm">
              <i class="bi bi-linkedin"></i> LinkedIn
            </a>
            <a href="https://github.com/MohammadAbdalhaleem" target="_blank" rel="noopener noreferrer" class="btn btn-outline-dark btn-sm">
              <i class="bi bi-github"></i> GitHub
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row justify-content-center mt-5">
    <div class="col-md-8">
      <div class="card fade-in-up" data-animate>
        <div class="card-body p-4 text-center">
          <h4 class="mb-3">Technologies Used</h4>
          <div class="d-flex flex-wrap justify-content-center gap-2">
            <span class="badge bg-primary">Spring Boot</span>
            <span class="badge bg-primary">React</span>
            <span class="badge bg-primary">PostgreSQL</span>
            <span class="badge bg-primary">JWT</span>
            <span class="badge bg-primary">WebSocket</span>
            <span class="badge bg-primary">Bootstrap</span>
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

  document.querySelectorAll('.team-card').forEach((card) => {
    card.addEventListener('mousemove', (event) => {
      const rect = card.getBoundingClientRect();
      const x = event.clientX - rect.left;
      const y = event.clientY - rect.top;

      for (let i = 0; i < 3; i += 1) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        particle.style.left = `${x}px`;
        particle.style.top = `${y}px`;
        particle.style.setProperty('--tx', `${(Math.random() - 0.5) * 100}px`);
        particle.style.setProperty('--ty', `${(Math.random() - 0.5) * 100}px`);
        card.appendChild(particle);
        setTimeout(() => particle.remove(), 1000);
      }
    });
  });
</script>
@endpush
