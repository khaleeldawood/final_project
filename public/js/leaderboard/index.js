document.addEventListener('DOMContentLoaded', () => {
  const body = document.getElementById('leaderboardBody');
  const headRow = document.getElementById('leaderboardHeadRow');
  const title = document.getElementById('leaderboardTitle');
  const loading = document.getElementById('leaderboardLoading');
  const empty = document.getElementById('leaderboardEmpty');
  const rankCard = document.getElementById('leaderboardRankCard');
  const rankValue = document.getElementById('leaderboardRankValue');

  const typeMembersBtn = document.getElementById('leaderboardTypeMembers');
  const typeEventsBtn = document.getElementById('leaderboardTypeEvents');
  const scopeUniversityBtn = document.getElementById('leaderboardScopeUniversity');
  const scopeGlobalBtn = document.getElementById('leaderboardScopeGlobal');

  let scope = 'GLOBAL';
  let type = 'MEMBERS';

  const storedUser = (() => {
    try {
      return JSON.parse(localStorage.getItem('user'));
    } catch (error) {
      return null;
    }
  })();

  if (!storedUser) {
    scopeUniversityBtn.disabled = true;
  }

  const setLoading = (isLoading) => {
    loading.classList.toggle('d-none', !isLoading);
  };

  const setActive = (button, active) => {
    button.classList.toggle('btn-primary', active);
    button.classList.toggle('btn-outline-primary', !active);
  };

  const updateButtons = () => {
    setActive(typeMembersBtn, type === 'MEMBERS');
    setActive(typeEventsBtn, type === 'EVENTS');
    setActive(scopeUniversityBtn, scope === 'UNIVERSITY');
    setActive(scopeGlobalBtn, scope === 'GLOBAL');
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

  const renderMembers = (rankings) => {
    headRow.innerHTML = `
      <th style="width:80px">Rank</th>
      <th>Name</th>
      <th>University</th>
      <th>Badge</th>
      <th class="text-end">Points</th>
    `;
    body.innerHTML = '';

    rankings.forEach((item, index) => {
      const row = document.createElement('tr');
      row.style.cursor = 'pointer';
      row.innerHTML = `
        <td><h4 class="mb-0 text-muted">#${index + 1}</h4></td>
        <td><strong>${item.name}</strong>${storedUser && item.userId === storedUser.userId ? ' <span class="badge bg-info ms-2">You</span>' : ''}</td>
        <td>${item.university?.name || ''}</td>
        <td>${item.currentBadge ? `<span class="badge bg-${getBadgeColor(item.currentBadge.name)}">${item.currentBadge.name}</span>` : ''}</td>
        <td class="text-end"><span class="badge bg-primary">${formatPoints(item.points)} pts</span></td>
      `;
      row.addEventListener('click', () => {
        window.location.href = `/profile/${item.userId}`;
      });
      body.appendChild(row);
    });
  };

  const renderEvents = (rankings) => {
    headRow.innerHTML = `
      <th style="width:80px">Rank</th>
      <th>Event Title</th>
      <th>Organizer</th>
      <th>Type</th>
      <th class="text-end">Participants</th>
    `;
    body.innerHTML = '';

    rankings.forEach((item, index) => {
      const row = document.createElement('tr');
      row.innerHTML = `
        <td><h4 class="mb-0 text-muted">#${index + 1}</h4></td>
        <td><strong>${item.title}</strong></td>
        <td>${item.creator?.name || ''}</td>
        <td><span class="badge bg-secondary">${item.type || ''}</span></td>
        <td class="text-end"><span class="badge bg-success">${item.participants?.length || 0}</span></td>
      `;
      body.appendChild(row);
    });
  };

  const loadRank = async () => {
    if (!storedUser || type !== 'MEMBERS') {
      rankCard.classList.add('d-none');
      return;
    }
    try {
      const data = await fetchJson(`/api/gamification/my-rank?scope=${scope}`);
      rankValue.textContent = `#${data.rank}`;
      rankCard.classList.remove('d-none');
    } catch (error) {
      rankCard.classList.add('d-none');
    }
  };

  const load = async () => {
    setLoading(true);
    empty.classList.add('d-none');
    body.innerHTML = '';
    updateButtons();

    const params = new URLSearchParams();
    params.set('scope', scope);
    params.set('type', type);
    if (scope === 'UNIVERSITY' && storedUser?.universityId) {
      params.set('universityId', storedUser.universityId);
    }

    try {
      const data = await fetchJson(`/api/gamification/leaderboard?${params.toString()}`);
      const rankings = Array.isArray(data.rankings) ? data.rankings : [];
      title.textContent = `${type === 'MEMBERS' ? 'Top Members' : 'Top Events'} - ${scope === 'UNIVERSITY' ? 'University' : 'Global'}`;
      if (rankings.length === 0) {
        empty.classList.remove('d-none');
      } else if (type === 'MEMBERS') {
        renderMembers(rankings);
      } else {
        renderEvents(rankings);
      }
      loadRank();
    } catch (error) {
      empty.classList.remove('d-none');
    } finally {
      setLoading(false);
    }
  };

  typeMembersBtn.addEventListener('click', () => {
    type = 'MEMBERS';
    load();
  });
  typeEventsBtn.addEventListener('click', () => {
    type = 'EVENTS';
    load();
  });
  scopeUniversityBtn.addEventListener('click', () => {
    if (!storedUser) return;
    scope = 'UNIVERSITY';
    load();
  });
  scopeGlobalBtn.addEventListener('click', () => {
    scope = 'GLOBAL';
    load();
  });

  load();
});
