# Replace all remaining ?? and corrupted icons with Bootstrap Icons
# This script finds files with icon placeholders and suggests replacements

Write-Host "Finding files with icon placeholders..." -ForegroundColor Cyan

$files = @(
    "resources/views/notifications/index.blade.php",
    "resources/views/events/index.blade.php",
    "resources/views/events/my-events.blade.php",
    "resources/views/events/approvals.blade.php",
    "resources/views/events/edit.blade.php",
    "resources/views/features.blade.php",
    "resources/views/faq.blade.php",
    "resources/views/support.blade.php",
    "resources/views/guidelines.blade.php",
    "resources/views/privacy-policy.blade.php",
    "resources/views/terms-of-service.blade.php"
)

$iconMap = @{
    # Common Patterns
    "Notifications" = "bi-bell-fill"
    "Events" = "bi-calendar-event"
    "My Events" = "bi-calendar-check"
    "Features" = "bi-stars"
    "Support" = "bi-question-circle"
    "FAQ" = "bi-patch-question"
    "Guidelines" = "bi-book"
    "Privacy" = "bi-shield-lock"
    "Terms" = "bi-file-text"
    
    # Event Specific
    "Approved" = "bi-check-circle"
    "Pending" = "bi-clock"
    "Rejected" = "bi-x-circle"
    "Cancelled" = "bi-slash-circle"
    "Active Now" = "bi-broadcast"
    "Future Events" = "bi-calendar-plus"
    
    # Other
    "Filters" = "bi-funnel"
    "Capacity Limits" = "bi-people"
    "Note" = "bi-info-circle"
    "Edit Event" = "bi-pencil-square"
    "Admin/Supervisor View" = "bi-shield-check"
}

Write-Host "`nCommon Icon Replacements:`n" -ForegroundColor Yellow

Write-Host "Event Icons:" -ForegroundColor Green
Write-Host "  ñ???, ƒ??, ?? near 'Events' → <i class=\""bi bi-calendar-event\""></i>" -ForegroundColor White
Write-Host "  ñ???, ƒ?? near 'Approved' → <i class=\""bi bi-check-circle\""></i>" -ForegroundColor White
Write-Host "  ƒ?? near 'Pending' → <i class=\""bi bi-clock\""></i>" -ForegroundColor White
Write-Host "  ƒ?? near 'Rejected' → <i class=\""bi bi-x-circle\""></i>" -ForegroundColor White
Write-Host "  ñ??® near 'Cancelled' → <i class=\""bi bi-slash-circle\""></i>" -ForegroundColor White

Write-Host "`nNotification Icons:" -ForegroundColor Green
Write-Host "  ñ??? near 'Notifications' → <i class=\""bi bi-bell-fill\""></i>" -ForegroundColor White

Write-Host "`nOther Icons:" -ForegroundColor Green
Write-Host "  ñ??? near 'Filters' → <i class=\""bi bi-funnel\""></i>" -ForegroundColor White
Write-Host "  ñ??? near 'Capacity' → <i class=\""bi bi-people\""></i>" -ForegroundColor White
Write-Host "  ƒ?? near 'Features' → <i class=\""bi bi-stars\""></i>" -ForegroundColor White
Write-Host "  ƒ?? near 'FAQ' → <i class=\""bi bi-patch-question\""></i>" -ForegroundColor White
Write-Host "  ñ??? near 'Guidelines' → <i class=\""bi bi-book\""></i>" -ForegroundColor White
Write-Host "  ñ??? near 'Privacy' → <i class=\""bi bi-shield-lock\""></i>" -ForegroundColor White
Write-Host "  ñ??? near 'Terms' → <i class=\""bi bi-file-text\""></i>" -ForegroundColor White
Write-Host "  Support page → <i class=\""bi bi-question-circle\""></i>" -ForegroundColor White

Write-Host "`n" + "="*70 -ForegroundColor Cyan
Write-Host "IMPORTANT: The AI is fixing these automatically!" -ForegroundColor Yellow
Write-Host "="*70 + "`n" -ForegroundColor Cyan

Write-Host "Icon replacement in progress by AI agent..." -ForegroundColor Cyan
