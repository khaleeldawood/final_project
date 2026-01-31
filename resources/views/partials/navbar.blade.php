<nav class="navbar navbar-expand-lg fixed-top navbar-theme">
  <div class="container">
    <a class="navbar-brand" href="/" style="text-decoration: none; color: inherit;">
      <i class="bi bi-mortarboard-fill"></i> UniHub
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain"
      aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarMain">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item" data-auth="guest"><a class="nav-link" href="/">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="/events">Events</a></li>
        <li class="nav-item"><a class="nav-link" href="/blogs">Blogs</a></li>
        <li class="nav-item"><a class="nav-link" href="/leaderboard">Leaderboard</a></li>
        <li class="nav-item" data-auth="user"><a class="nav-link" href="/badges">Badges</a></li>
      </ul>
      <ul class="navbar-nav ms-auto align-items-lg-center">

        <li class="nav-item">
          <button class="btn btn-link nav-link theme-toggle" type="button" data-theme-toggle title="Toggle theme"><i
              class="bi bi-moon-fill"></i></button>
        </li>
        <li class="nav-item" data-auth="guest">
          <a class="btn btn-outline-primary me-2" href="/login">Login</a>
        </li>
        <li class="nav-item" data-auth="guest">
          <a class="btn btn-primary" href="/register">Register</a>
        </li>
        <li class="nav-item dropdown" data-auth="user">
          <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown"
            aria-expanded="false">
            <i class="bi bi-person-circle"></i> <span data-user-name>User</span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
            <li><a class="dropdown-item" href="/dashboard">Dashboard</a></li>
            <li><a class="dropdown-item" data-profile-link href="/profile/">Profile</a></li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item" href="/my-events">My Events</a></li>
            <li><a class="dropdown-item" href="/my-blogs">My Blogs</a></li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item" href="/participation-requests">My Participation Requests</a></li>
            <li><a class="dropdown-item" href="/my-event-requests">Event Participation Requests</a></li>
            <li>
              <hr class="dropdown-divider" data-role-any="SUPERVISOR,ADMIN">
            </li>
            <li data-role-any="SUPERVISOR,ADMIN"><a class="dropdown-item" href="/events/approvals">Event Approvals</a>
            </li>
            <li data-role-any="SUPERVISOR,ADMIN"><a class="dropdown-item" href="/blogs/approvals">Blog Approvals</a>
            </li>
            <li data-role-any="SUPERVISOR,ADMIN"><a class="dropdown-item" href="/reports">Reports</a></li>
            <li>
              <hr class="dropdown-divider" data-role="ADMIN">
            </li>
            <li data-role="ADMIN"><a class="dropdown-item" href="/admin/users">Manage Users</a></li>
            <li data-role="ADMIN"><a class="dropdown-item" href="/admin/universities">Manage Universities</a></li>
            <li data-role="ADMIN"><a class="dropdown-item" href="/admin/analytics">Analytics</a></li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item" href="/settings">Settings</a></li>
            <li><a class="dropdown-item" href="/login" data-logout>Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>