<?php

namespace App\Services;

use App\Models\Blog;
use App\Models\BlogReport;
use App\Models\Event;
use App\Models\EventReport;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Carbon;

class ReportService
{
    public function __construct(private GamificationService $gamificationService)
    {
    }

    public function reportBlog(int $blogId, User $reporter, string $reason): BlogReport
    {
        $blog = Blog::findOrFail($blogId);

        if ($blog->status === 'REJECTED') {
            throw new \RuntimeException('Cannot report a rejected blog');
        }

        $alreadyReported = BlogReport::where('blog_id', $blogId)
            ->where('reported_by', $reporter->id)
            ->exists();
        if ($alreadyReported) {
            throw new \RuntimeException('You have already reported this blog');
        }

        return BlogReport::create([
            'blog_id' => $blog->id,
            'reported_by' => $reporter->id,
            'reason' => $reason,
            'status' => 'PENDING',
        ]);
    }

    public function reportEvent(int $eventId, User $reporter, string $reason): EventReport
    {
        $event = Event::findOrFail($eventId);

        if ($event->status === 'CANCELLED') {
            throw new \RuntimeException('Cannot report a cancelled event');
        }

        if ($event->end_date && Carbon::parse($event->end_date)->isPast()) {
            throw new \RuntimeException('Cannot report a completed event');
        }

        $alreadyReported = EventReport::where('event_id', $eventId)
            ->where('reported_by', $reporter->id)
            ->exists();
        if ($alreadyReported) {
            throw new \RuntimeException('You have already reported this event');
        }

        return EventReport::create([
            'event_id' => $event->id,
            'reported_by' => $reporter->id,
            'reason' => $reason,
            'status' => 'PENDING',
        ]);
    }

    public function getPendingBlogReports(): array
    {
        return BlogReport::with(['blog.author', 'reportedBy'])
            ->where('status', 'PENDING')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn (BlogReport $report) => $this->mapBlogReport($report))
            ->values()
            ->all();
    }

    public function getPendingEventReports(): array
    {
        return EventReport::with(['event.createdBy', 'reportedBy'])
            ->where('status', 'PENDING')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn (EventReport $report) => $this->mapEventReport($report))
            ->values()
            ->all();
    }

    public function getAllBlogReports(): array
    {
        return BlogReport::with(['blog.author', 'reportedBy'])
            ->orderByDesc('created_at')
            ->get()
            ->map(fn (BlogReport $report) => $this->mapBlogReport($report))
            ->values()
            ->all();
    }

    public function getAllEventReports(): array
    {
        return EventReport::with(['event.createdBy', 'reportedBy'])
            ->orderByDesc('created_at')
            ->get()
            ->map(fn (EventReport $report) => $this->mapEventReport($report))
            ->values()
            ->all();
    }

    public function getBlogReportsByStatus(string $status): array
    {
        return BlogReport::with(['blog.author', 'reportedBy'])
            ->where('status', $status)
            ->orderByDesc('created_at')
            ->get()
            ->map(fn (BlogReport $report) => $this->mapBlogReport($report))
            ->values()
            ->all();
    }

    public function getEventReportsByStatus(string $status): array
    {
        return EventReport::with(['event.createdBy', 'reportedBy'])
            ->where('status', $status)
            ->orderByDesc('created_at')
            ->get()
            ->map(fn (EventReport $report) => $this->mapEventReport($report))
            ->values()
            ->all();
    }

    public function reviewBlogReport(int $reportId): void
    {
        $report = BlogReport::with(['blog', 'reportedBy'])->findOrFail($reportId);
        $report->status = 'REVIEWED';
        $report->save();

        $reporter = $report->reportedBy;
        $this->gamificationService->awardPoints(
            $reporter,
            15,
            'REPORT_RESOLVED',
            $reportId,
            "Your report on blog '{$report->blog->title}' was resolved"
        );

        Notification::create([
            'user_id' => $reporter->id,
            'message' => "Your report on blog '{$report->blog->title}' was resolved. You earned 15 points!",
            'type' => 'SYSTEM_ALERT',
            'link_url' => "/blogs/{$report->blog->id}",
            'is_read' => false,
        ]);
    }

    public function dismissBlogReport(int $reportId): void
    {
        $report = BlogReport::with(['blog', 'reportedBy'])->findOrFail($reportId);
        $report->status = 'DISMISSED';
        $report->save();

        $reporter = $report->reportedBy;
        $this->gamificationService->deductPoints(
            $reporter,
            50,
            'REPORT_DISMISSED',
            $reportId,
            "Your report on blog '{$report->blog->title}' was dismissed (false report)"
        );

        Notification::create([
            'user_id' => $reporter->id,
            'message' => "Your report on blog '{$report->blog->title}' was dismissed. Penalty: -50 points",
            'type' => 'SYSTEM_ALERT',
            'link_url' => "/blogs/{$report->blog->id}",
            'is_read' => false,
        ]);
    }

    public function reviewEventReport(int $reportId): void
    {
        $report = EventReport::with(['event', 'reportedBy'])->findOrFail($reportId);
        $report->status = 'REVIEWED';
        $report->save();

        $reporter = $report->reportedBy;
        $this->gamificationService->awardPoints(
            $reporter,
            15,
            'REPORT_RESOLVED',
            $reportId,
            "Your report on event '{$report->event->title}' was resolved"
        );

        Notification::create([
            'user_id' => $reporter->id,
            'message' => "Your report on event '{$report->event->title}' was resolved. You earned 15 points!",
            'type' => 'SYSTEM_ALERT',
            'link_url' => "/events/{$report->event->id}",
            'is_read' => false,
        ]);
    }

    public function dismissEventReport(int $reportId): void
    {
        $report = EventReport::with(['event', 'reportedBy'])->findOrFail($reportId);
        $report->status = 'DISMISSED';
        $report->save();

        $reporter = $report->reportedBy;
        $this->gamificationService->deductPoints(
            $reporter,
            50,
            'REPORT_DISMISSED',
            $reportId,
            "Your report on event '{$report->event->title}' was dismissed (false report)"
        );

        Notification::create([
            'user_id' => $reporter->id,
            'message' => "Your report on event '{$report->event->title}' was dismissed. Penalty: -50 points",
            'type' => 'SYSTEM_ALERT',
            'link_url' => "/events/{$report->event->id}",
            'is_read' => false,
        ]);
    }

    private function mapBlogReport(BlogReport $report): array
    {
        return [
            'reportId' => $report->id,
            'blogId' => $report->blog?->id,
            'blogTitle' => $report->blog?->title,
            'blogAuthorName' => $report->blog?->author?->name,
            'reportedById' => $report->reportedBy?->id,
            'reportedByName' => $report->reportedBy?->name,
            'reason' => $report->reason,
            'status' => $report->status,
            'createdAt' => $report->created_at ? Carbon::parse($report->created_at)->toIso8601String() : null,
        ];
    }

    private function mapEventReport(EventReport $report): array
    {
        return [
            'reportId' => $report->id,
            'eventId' => $report->event?->id,
            'eventTitle' => $report->event?->title,
            'eventCreatorName' => $report->event?->createdBy?->name,
            'reportedById' => $report->reportedBy?->id,
            'reportedByName' => $report->reportedBy?->name,
            'reason' => $report->reason,
            'status' => $report->status,
            'createdAt' => $report->created_at ? Carbon::parse($report->created_at)->toIso8601String() : null,
        ];
    }
}
