<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes" />
  <meta name="description" content="UniHub - University Event Management and Collaboration Platform" />
  <meta name="keywords" content="university, events, blogs, student collaboration, campus activities" />
  <meta name="author" content="UniHub Team" />
  <meta name="robots" content="index, follow" />
  <meta name="referrer" content="strict-origin-when-cross-origin" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <meta property="og:title" content="UniHub - University Hub" />
  <meta property="og:description" content="Connect, collaborate, and engage with your university community" />
  <meta property="og:type" content="website" />
  <meta property="og:url" content="https://unihub.example.com" />
  <meta property="og:image" content="/og-image.png" />
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="UniHub - University Hub" />
  <meta name="twitter:description" content="Connect, collaborate, and engage with your university community" />
  <meta name="twitter:image" content="/twitter-image.png" />
  <meta name="theme-color" content="#4f46e5" />
  <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
  <link rel="apple-touch-icon" href="/apple-touch-icon.png" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
    rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css" rel="stylesheet">
  <link href="/css/index.css" rel="stylesheet">
  <link href="/css/App.css" rel="stylesheet">
  <link href="/css/components/common/Footer.css" rel="stylesheet">
  <link href="/css/components/common/BackToTop.css" rel="stylesheet">
  @stack('styles')
  <title>@yield('title', 'UniHub - University Hub')</title>
</head>

<body>
  <div class="spirit-container">
    <div class="spirit"></div>
    <div class="spirit"></div>
    <div class="spirit"></div>
    <div class="spirit"></div>
  </div>

  @include('partials.navbar')

  <main class="flex-grow-1" style="min-height: calc(100vh - 200px)">
    @yield('content')
  </main>

  @include('partials.footer')
  @include('partials.back-to-top')

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="/js/services/ThemeService.js"></script>
  <script>
    (function () {
      const applyUserState = (user) => {
        document.querySelectorAll('[data-auth="user"]').forEach((el) => {
          el.hidden = !user;
        });
        document.querySelectorAll('[data-auth="guest"]').forEach((el) => {
          el.hidden = !!user;
        });

        if (user) {
          const nameTarget = document.querySelector('[data-user-name]');
          if (nameTarget) {
            nameTarget.textContent = user.name || 'User';
          }
          const profileLink = document.querySelector('[data-profile-link]');
          if (profileLink) {
            profileLink.setAttribute('href', `/profile/${user.userId || ''}`);
          }
          const roleBlocks = document.querySelectorAll('[data-role]');
          roleBlocks.forEach((el) => {
            const allowed = (user.role || '').toUpperCase();
            el.hidden = el.getAttribute('data-role') !== allowed;
          });
          const roleGroups = document.querySelectorAll('[data-role-any]');
          roleGroups.forEach((el) => {
            const allowedRoles = el.getAttribute('data-role-any').split(',').map((r) => r.trim().toUpperCase());
            el.hidden = !allowedRoles.includes((user.role || '').toUpperCase());
          });
        } else {
          document.querySelectorAll('[data-role],[data-role-any]').forEach((el) => {
            el.hidden = true;
          });
        }
      };

      const storedUser = localStorage.getItem('user');
      if (storedUser) {
        try {
          applyUserState(JSON.parse(storedUser));
        } catch (error) {
          localStorage.removeItem('user');
        }
      }

      fetch('/api/auth/session', { credentials: 'same-origin' })
        .then((response) => (response.ok ? response.json() : null))
        .then((data) => {
          if (data) {
            localStorage.setItem('user', JSON.stringify(data));
            applyUserState(data);
          } else {
            localStorage.removeItem('user');
            applyUserState(null);
          }
        })
        .catch(() => {
          applyUserState(null);
        });

      const logoutLink = document.querySelector('[data-logout]');
      if (logoutLink) {
        logoutLink.addEventListener('click', (event) => {
          event.preventDefault();
          fetch('/api/auth/logout', { method: 'POST', credentials: 'same-origin' })
            .finally(() => {
              localStorage.removeItem('user');
              localStorage.removeItem('token');
              window.location.href = '/login';
            });
        });
      }

      const navbar = document.querySelector('.navbar-theme');
      if (navbar) {
        const handleScroll = () => {
          navbar.classList.toggle('scrolled', window.scrollY > 50);
        };
        window.addEventListener('scroll', handleScroll);
        handleScroll();
      }
    })();
  </script>
  @stack('scripts')
</body>

</html>