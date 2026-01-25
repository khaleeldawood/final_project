function formatDate(value) {
  if (!value) return '';
  const date = new Date(value);
  if (Number.isNaN(date.getTime())) return '';
  return date.toLocaleString(undefined, {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  });
}

function truncateText(text, length) {
  if (!text) return '';
  if (text.length <= length) return text;
  return `${text.slice(0, length)}...`;
}

function getStatusVariant(status) {
  switch (status) {
    case 'APPROVED':
      return 'success';
    case 'PENDING':
      return 'warning';
    case 'REJECTED':
      return 'secondary';
    case 'CANCELLED':
      return 'danger';
    default:
      return 'secondary';
  }
}

function getRoleVariant(role) {
  switch (role) {
    case 'ORGANIZER':
      return 'danger';
    case 'VOLUNTEER':
      return 'success';
    case 'ATTENDEE':
      return 'info';
    default:
      return 'secondary';
  }
}

function getTimeAgo(value) {
  if (!value) return '';
  const date = new Date(value);
  const now = new Date();
  const seconds = Math.floor((now - date) / 1000);

  const intervals = {
    year: 31536000,
    month: 2592000,
    week: 604800,
    day: 86400,
    hour: 3600,
    minute: 60,
  };

  for (const [unit, secondsInUnit] of Object.entries(intervals)) {
    const interval = Math.floor(seconds / secondsInUnit);
    if (interval >= 1) {
      return `${interval} ${unit}${interval > 1 ? 's' : ''} ago`;
    }
  }

  return 'Just now';
}

function getNotificationIcon(type) {
  const icons = {
    BADGE_EARNED: '\uD83C\uDFC6',
    LEVEL_UP: '\uD83C\uDF1F',
    EVENT_UPDATE: '\uD83D\uDCC5',
    BLOG_APPROVAL: '\u270D\uFE0F',
    SYSTEM_ALERT: '\u26A0\uFE0F',
  };
  return icons[type] || '\uD83D\uDD14';
}

function formatPoints(points) {
  if (points === null || points === undefined) return '0';
  return points.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

function getBadgeColor(name) {
  const colors = {
    Newcomer: 'secondary',
    Explorer: 'info',
    Contributor: 'primary',
    Leader: 'success',
    Champion: 'warning',
    Legend: 'danger',
  };
  return colors[name] || 'secondary';
}

function calculateBadgeProgress(currentPoints, badgeThreshold) {
  if (badgeThreshold === 0) return 100;
  return Math.min(Math.round((currentPoints / badgeThreshold) * 100), 100);
}
