document.addEventListener('DOMContentLoaded', () => {
  const grid = document.getElementById('badgesGrid');
  const loading = document.getElementById('badgesLoading');
  const subtitle = document.getElementById('badgesSubtitle');
  const progress = document.getElementById('badgesProgress');
  const pointsLabel = document.getElementById('badgesPoints');
  const callout = document.getElementById('badgesCallout');

  const storedUser = (() => {
    try {
      return JSON.parse(localStorage.getItem('user'));
    } catch (error) {
      return null;
    }
  })();

  const setLoading = (isLoading) => {
    loading.classList.toggle('d-none', !isLoading);
  };

  const fetchJson = async (url) => {
    const response = await fetch(url, { credentials: 'same-origin' });
    const data = await response.json().catch(() => null);
    if (!response.ok) {
      const message = data?.message || 'Request failed';
      throw new Error(message);
    }
    return data;
  };

  const renderBadge = (badge, currentPoints) => {
    const earned = storedUser && currentPoints >= badge.pointsThreshold;
    const progressValue = calculateBadgeProgress(currentPoints, badge.pointsThreshold);
    const icon = earned ? '\uD83C\uDFC6' : '\uD83D\uDD12';

    const col = document.createElement('div');
    col.className = 'col-md-6 col-lg-4';
    col.innerHTML = `
      <div class="card h-100 ${earned ? 'border-success' : 'border-secondary'}">
        <div class="card-body">
          <div class="text-center mb-3">
            <div class="badge bg-${getBadgeColor(badge.name)} fs-1 p-4 rounded-circle ${earned ? '' : 'opacity-50'}" style="width:100px;height:100px;display:inline-flex;align-items:center;justify-content:center;">
              ${icon}
            </div>
          </div>
          <h5 class="text-center mb-2">${badge.name}</h5>
          <p class="text-center text-muted small mb-3">${badge.description || ''}</p>
          <div class="mb-3">
            <div class="d-flex justify-content-between small mb-1">
              <span>Points Required:</span>
              <strong>${formatPoints(badge.pointsThreshold)}</strong>
            </div>
            ${storedUser ? `<div class="progress"><div class="progress-bar bg-${earned ? 'success' : 'primary'}" role="progressbar" style="width:${progressValue}%;">${progressValue}%</div></div>` : ''}
          </div>
          <div class="text-center">
            ${earned ? '<span class="badge bg-success px-3 py-2">Earned</span>' : '<span class="badge bg-secondary px-3 py-2">Locked</span>'}
          </div>
        </div>
      </div>
    `;
    return col;
  };

  const load = async () => {
    setLoading(true);
    grid.innerHTML = '';
    callout.classList.add('d-none');

    try {
      if (storedUser) {
        const data = await fetchJson('/api/gamification/my-badges');
        const badges = Array.isArray(data.allBadges) ? data.allBadges : [];
        const currentPoints = data.currentPoints || 0;

        subtitle.textContent = 'Earn points to unlock achievement badges';
        progress.classList.remove('d-none');
        pointsLabel.textContent = `${formatPoints(currentPoints)} points earned`;

        badges.forEach((badge) => {
          grid.appendChild(renderBadge(badge, currentPoints));
        });
      } else {
        const badges = await fetchJson('/api/gamification/badges');
        subtitle.textContent = 'Register to start earning badges!';
        progress.classList.add('d-none');
        callout.classList.remove('d-none');
        (Array.isArray(badges) ? badges : []).forEach((badge) => {
          grid.appendChild(renderBadge(badge, 0));
        });
      }
    } catch (error) {
      grid.innerHTML = '<div class="col-12 text-center text-muted py-5">Failed to load badges</div>';
    } finally {
      setLoading(false);
    }
  };

  load();
});
