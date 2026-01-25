@extends('layouts.app')

@section('content')
<style>
  @keyframes gradientMove {
    0%, 100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
  }
  .animated-bg {
    background: linear-gradient(-45deg, rgba(99, 102, 241, 0.1), rgba(139, 92, 246, 0.1), rgba(236, 72, 153, 0.1), rgba(6, 182, 212, 0.1));
    background-size: 400% 400%;
    animation: gradientMove 15s ease infinite;
    min-height: 100vh;
  }
</style>

<div class="animated-bg">
  <div class="container py-5">
    <div class="text-center mb-5">
      <h1 class="mb-3">ƒ?? Frequently Asked Questions</h1>
      <p class="lead text-muted">Find answers to common questions</p>
    </div>

    <div class="accordion" id="faqAccordion" style="max-width: 800px; margin: 0 auto;">
      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faqOne">
            What is UniHub?
          </button>
        </h2>
        <div id="faqOne" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            UniHub is a comprehensive university portal platform that connects students, faculty, and administrators through events, blogs, and gamification features.
          </div>
        </div>
      </div>

      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqTwo">
            How do I earn points?
          </button>
        </h2>
        <div id="faqTwo" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            You earn points by participating in events as an organizer, volunteer, or attendee. Each role has different point values.
          </div>
        </div>
      </div>

      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqThree">
            What are badges?
          </button>
        </h2>
        <div id="faqThree" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            Badges are achievements you unlock by reaching certain milestones, such as attending events or writing blogs.
          </div>
        </div>
      </div>

      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqFour">
            How do I create an event?
          </button>
        </h2>
        <div id="faqFour" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            Navigate to Events page and click "Create Event". Fill in the details and submit for approval.
          </div>
        </div>
      </div>

      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqFive">
            Who can approve events?
          </button>
        </h2>
        <div id="faqFive" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            Events are approved by supervisors or administrators.
          </div>
        </div>
      </div>

      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqSix">
            Can I edit my blog after publishing?
          </button>
        </h2>
        <div id="faqSix" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            Yes, you can edit pending or approved blogs, but editing an approved blog will reset it to pending status.
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
