@extends('layouts.app')

@push('styles')
<link href="/css/pages/Home.css" rel="stylesheet">
<link href="/css/components/home/HeroSection.css" rel="stylesheet">
<link href="/css/components/home/FeatureCard.css" rel="stylesheet">
<link href="/css/components/home/EventCard.css" rel="stylesheet">
<link href="/css/components/home/OpportunityCard.css" rel="stylesheet">
<link href="/css/components/home/LeaderboardPreview.css" rel="stylesheet">
@endpush

@section('content')
<div class="home-page">
  <div class="hero-section">
    <div class="container">
      <div class="row align-items-center min-vh-75">
        <div class="col-lg-6 hero-content">
          <div class="hero-label">
            <span class="label-text">University Engagement Platform</span>
          </div>
          <h1 class="hero-title">
            Connect. Participate. <span class="highlight">Get Rewarded.</span>
          </h1>
          <p class="hero-description">
            Join your university community, participate in events, share knowledge through blogs,
            and earn points to climb the leaderboard. Your engagement matters.
          </p>
          <div class="hero-buttons">
            <a href="/register" class="btn btn-primary btn-lg btn-get-started">Get Started</a>
            <a href="/events" class="btn btn-outline-primary btn-lg btn-explore">Explore Events</a>
          </div>
        </div>
        <div class="col-lg-6 hero-image-container">
          <div class="hero-image-wrapper">
            <div class="floating-card card-1">
              <div class="card-icon"><i class="bi bi-calendar-event"></i></div>
              <div class="card-text">200+ Events</div>
            </div>
            <div class="floating-card card-2">
              <div class="card-icon"><i class="bi bi-trophy"></i></div>
              <div class="card-text">Top Contributors</div>
            </div>
            <div class="floating-card card-3">
              <div class="card-icon"><i class="bi bi-lightbulb"></i></div>
              <div class="card-text">Share Ideas</div>
            </div>
            <div class="hero-illustration">
              <div class="illustration-circle circle-1"></div>
              <div class="illustration-circle circle-2"></div>
              <div class="illustration-circle circle-3"></div>
              <div class="illustration-center">
                <span class="center-emoji"><i class="bi bi-mortarboard-fill"></i></span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <section class="section-empowering">
    <div class="container">
      <div class="section-header text-center">
        <h2 class="section-title">Empowering University Communities</h2>
        <p class="section-subtitle">
          Connect with peers, discover opportunities, and grow together in your academic journey
        </p>
      </div>
    </div>
  </section>

  <section class="section-features">
    <div class="container">
      <div class="row g-4">
        <div class="col-md-6 col-lg-3">
          <div class="card feature-card" style="animation-delay: 0ms;">
            <div class="card-body text-center">
              <div class="feature-icon"><i class="bi bi-calendar-check"></i></div>
              <div class="feature-title">Events & Activities</div>
              <div class="feature-description">
                Create and participate in university events. Earn points as organizer, volunteer, or attendee.
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="card feature-card" style="animation-delay: 100ms;">
            <div class="card-body text-center">
              <div class="feature-icon"><i class="bi bi-journal-text"></i></div>
              <div class="feature-title">Blogs & Opportunities</div>
              <div class="feature-description">
                Share articles, internships, and job opportunities with your peers and community.
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="card feature-card" style="animation-delay: 200ms;">
            <div class="card-body text-center">
              <div class="feature-icon"><i class="bi bi-award"></i></div>
              <div class="feature-title">Points & Badges</div>
              <div class="feature-description">
                Earn points for contributions and unlock achievement badges as you level up.
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="card feature-card" style="animation-delay: 300ms;">
            <div class="card-body text-center">
              <div class="feature-icon"><i class="bi bi-bar-chart"></i></div>
              <div class="feature-title">University Leaderboards</div>
              <div class="feature-description">
                Compete with peers and see top contributors across different universities.
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="section-powerful-features">
    <div class="container">
      <div class="section-header text-center">
        <h2 class="section-title">Powerful Features</h2>
        <p class="section-subtitle">
          Everything you need to engage, collaborate, and succeed
        </p>
      </div>
      <div class="row g-4">
        <div class="col-md-6">
          <div class="feature-box" style="animation-delay: 0ms;">
            <div class="feature-box-icon"><i class="bi bi-calendar4-event"></i></div>
            <h3 class="feature-box-title">Event Management</h3>
            <p class="feature-box-description">
              Create, manage, and participate in university events. Track attendance,
              manage volunteers, and earn points for every contribution.
            </p>
          </div>
        </div>
        <div class="col-md-6">
          <div class="feature-box" style="animation-delay: 100ms;">
            <div class="feature-box-icon"><i class="bi bi-newspaper"></i></div>
            <h3 class="feature-box-title">Blog & Opportunity Publishing</h3>
            <p class="feature-box-description">
              Share knowledge, post internships, and publish job opportunities.
              Help your peers discover valuable resources and grow together.
            </p>
          </div>
        </div>
        <div class="col-md-6">
          <div class="feature-box" style="animation-delay: 200ms;">
            <div class="feature-box-icon"><i class="bi bi-gem"></i></div>
            <h3 class="feature-box-title">Gamification System</h3>
            <p class="feature-box-description">
              Engage with a comprehensive points and badges system. Level up,
              unlock achievements, and showcase your contributions.
            </p>
          </div>
        </div>
        <div class="col-md-6">
          <div class="feature-box" style="animation-delay: 300ms;">
            <div class="feature-box-icon"><i class="bi bi-speedometer2"></i></div>
            <h3 class="feature-box-title">Role-based Dashboards</h3>
            <p class="feature-box-description">
              Tailored dashboards for students, supervisors, and admins.
              Each role gets the tools they need to succeed.
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="section-events">
    <div class="container">
      <div class="section-header-with-action">
        <h2 class="section-title">Upcoming Events</h2>
        <a href="/events" class="btn btn-outline-primary view-all-btn">View All</a>
      </div>
      <div class="row g-4 mt-1">
        <div class="col-md-6 col-lg-4">
          <div class="card event-card" style="animation-delay: 0ms;">
            <div class="card-body">
              <span class="badge bg-primary event-category mb-3">Workshop</span>
              <div class="event-title">Web Development Bootcamp</div>
              <div class="event-details">
                <div class="event-detail-item">
                  <i class="bi bi-calendar-event detail-icon"></i>
                  <span>Jan 15, 2026</span>
                </div>
                <div class="event-detail-item">
                  <i class="bi bi-clock detail-icon"></i>
                  <span>10:00 AM</span>
                </div>
                <div class="event-detail-item">
                  <i class="bi bi-geo-alt detail-icon"></i>
                  <span>Engineering Hall</span>
                </div>
                <div class="event-detail-item">
                  <i class="bi bi-people detail-icon"></i>
                  <span>45 attending</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-4">
          <div class="card event-card" style="animation-delay: 100ms;">
            <div class="card-body">
              <span class="badge bg-primary event-category mb-3">Seminar</span>
              <div class="event-title">AI & Machine Learning Trends</div>
              <div class="event-details">
                <div class="event-detail-item">
                  <i class="bi bi-calendar-event detail-icon"></i>
                  <span>Jan 18, 2026</span>
                </div>
                <div class="event-detail-item">
                  <i class="bi bi-clock detail-icon"></i>
                  <span>2:00 PM</span>
                </div>
                <div class="event-detail-item">
                  <i class="bi bi-geo-alt detail-icon"></i>
                  <span>Science Building</span>
                </div>
                <div class="event-detail-item">
                  <i class="bi bi-people detail-icon"></i>
                  <span>78 attending</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-4">
          <div class="card event-card" style="animation-delay: 200ms;">
            <div class="card-body">
              <span class="badge bg-primary event-category mb-3">Competition</span>
              <div class="event-title">Hackathon 2026</div>
              <div class="event-details">
                <div class="event-detail-item">
                  <i class="bi bi-calendar-event detail-icon"></i>
                  <span>Jan 20, 2026</span>
                </div>
                <div class="event-detail-item">
                  <i class="bi bi-clock detail-icon"></i>
                  <span>9:00 AM</span>
                </div>
                <div class="event-detail-item">
                  <i class="bi bi-geo-alt detail-icon"></i>
                  <span>Tech Hub</span>
                </div>
                <div class="event-detail-item">
                  <i class="bi bi-people detail-icon"></i>
                  <span>120 attending</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="section-opportunities">
    <div class="container">
      <div class="section-header-with-action">
        <h2 class="section-title">Latest Opportunities</h2>
        <a href="/blogs" class="btn btn-outline-primary view-all-btn">View All</a>
      </div>
      <div class="row g-4 mt-1">
        <div class="col-md-6 col-lg-4">
          <div class="card opportunity-card" style="animation-delay: 0ms;">
            <div class="card-body">
              <span class="badge bg-info opportunity-type mb-3">Internship</span>
              <div class="opportunity-title">Software Engineering Intern</div>
              <div class="opportunity-details">
                <div class="opportunity-detail-item">
                  <i class="bi bi-building detail-icon"></i>
                  <span>Tech Innovations Inc.</span>
                </div>
                <div class="opportunity-detail-item">
                  <i class="bi bi-geo-alt detail-icon"></i>
                  <span>Remote</span>
                </div>
                <div class="opportunity-detail-item">
                  <i class="bi bi-clock detail-icon"></i>
                  <span class="text-muted">2 hours ago</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-4">
          <div class="card opportunity-card" style="animation-delay: 100ms;">
            <div class="card-body">
              <span class="badge bg-success opportunity-type mb-3">Part-time</span>
              <div class="opportunity-title">Campus Ambassador</div>
              <div class="opportunity-details">
                <div class="opportunity-detail-item">
                  <i class="bi bi-building detail-icon"></i>
                  <span>EdTech Startup</span>
                </div>
                <div class="opportunity-detail-item">
                  <i class="bi bi-geo-alt detail-icon"></i>
                  <span>On-site</span>
                </div>
                <div class="opportunity-detail-item">
                  <i class="bi bi-clock detail-icon"></i>
                  <span class="text-muted">5 hours ago</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-4">
          <div class="card opportunity-card" style="animation-delay: 200ms;">
            <div class="card-body">
              <span class="badge bg-warning opportunity-type mb-3">Research</span>
              <div class="opportunity-title">AI Research Assistant</div>
              <div class="opportunity-details">
                <div class="opportunity-detail-item">
                  <i class="bi bi-building detail-icon"></i>
                  <span>University Lab</span>
                </div>
                <div class="opportunity-detail-item">
                  <i class="bi bi-geo-alt detail-icon"></i>
                  <span>Hybrid</span>
                </div>
                <div class="opportunity-detail-item">
                  <i class="bi bi-clock detail-icon"></i>
                  <span class="text-muted">1 day ago</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="section-leaderboard">
    <div class="container">
      <div class="section-header text-center">
        <h2 class="section-title">Top Contributors</h2>
        <p class="section-subtitle">
          Celebrating our most active community members
        </p>
      </div>
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="leaderboard-preview">
            <div class="card leaderboard-item rank-1" style="animation-delay: 0ms;">
              <div class="card-body d-flex align-items-center">
                <div class="rank-number" style="color: #FFD700;">#1</div>
                <div class="contributor-avatar" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                  A
                </div>
                <div class="contributor-info flex-grow-1">
                  <div class="contributor-name">Ahmed Hassan</div>
                  <span class="badge bg-warning badge-level">
                    <span class="badge-icon"><i class="bi bi-trophy"></i></span>
                    Gold
                  </span>
                </div>
                <div class="contributor-points">
                  <div class="points-value">2450</div>
                  <div class="points-label">points</div>
                </div>
              </div>
            </div>
            <div class="card leaderboard-item rank-2" style="animation-delay: 100ms;">
              <div class="card-body d-flex align-items-center">
                <div class="rank-number" style="color: #C0C0C0;">#2</div>
                <div class="contributor-avatar" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                  S
                </div>
                <div class="contributor-info flex-grow-1">
                  <div class="contributor-name">Sarah Johnson</div>
                  <span class="badge bg-secondary badge-level">
                    <span class="badge-icon"><i class="bi bi-award"></i></span>
                    Silver
                  </span>
                </div>
                <div class="contributor-points">
                  <div class="points-value">2180</div>
                  <div class="points-label">points</div>
                </div>
              </div>
            </div>
            <div class="card leaderboard-item rank-3" style="animation-delay: 200ms;">
              <div class="card-body d-flex align-items-center">
                <div class="rank-number" style="color: #CD7F32;">#3</div>
                <div class="contributor-avatar" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                  M
                </div>
                <div class="contributor-info flex-grow-1">
                  <div class="contributor-name">Mohamed Ali</div>
                  <span class="badge bg-danger badge-level">
                    <span class="badge-icon"><i class="bi bi-award"></i></span>
                    Bronze
                  </span>
                </div>
                <div class="contributor-points">
                  <div class="points-value">1990</div>
                  <div class="points-label">points</div>
                </div>
              </div>
            </div>
          </div>
          <div class="text-center mt-4">
            <a href="/leaderboard" class="btn btn-primary btn-lg view-leaderboard-btn">View Full Leaderboard</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="section-cta">
    <div class="container">
      <div class="cta-content text-center">
        <h2 class="cta-title">Join Your University Community Today</h2>
        <p class="cta-description">
          Start your journey towards academic excellence and community engagement
        </p>
        <div class="cta-buttons">
          <a href="/register" class="btn btn-light btn-lg cta-btn-primary">Register Now</a>
          <a href="/login" class="btn btn-outline-light btn-lg cta-btn-secondary">Login</a>
        </div>
      </div>
    </div>
  </section>
</div>
@endsection
