<button class="back-to-top" data-back-to-top aria-label="Back to top"><i class="bi bi-arrow-up"></i></button>

@push('scripts')
  <script>
    (function () {
      const button = document.querySelector('[data-back-to-top]');
      if (!button) {
        return;
      }

      const toggleVisible = () => {
        button.classList.toggle('visible', window.scrollY > 300);
      };

      window.addEventListener('scroll', toggleVisible);
      toggleVisible();

      button.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
      });
    })();
  </script>
@endpush