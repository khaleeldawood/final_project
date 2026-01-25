<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ReportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(private ReportService $reportService)
    {
    }

    public function reportBlog(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'reason' => 'required|string',
        ]);

        $user = $this->getSessionUser($request);
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        try {
            $report = $this->reportService->reportBlog($id, $user, $data['reason']);
        } catch (\RuntimeException $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        }

        return response()->json($report, 201);
    }

    public function reportEvent(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'reason' => 'required|string',
        ]);

        $user = $this->getSessionUser($request);
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        try {
            $report = $this->reportService->reportEvent($id, $user, $data['reason']);
        } catch (\RuntimeException $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        }

        return response()->json($report, 201);
    }

    public function getBlogReports(Request $request): JsonResponse
    {
        $status = $request->query('status');
        $pending = filter_var($request->query('pending', 'false'), FILTER_VALIDATE_BOOLEAN);

        if ($status) {
            return response()->json($this->reportService->getBlogReportsByStatus($status));
        }

        if ($pending) {
            return response()->json($this->reportService->getPendingBlogReports());
        }

        return response()->json($this->reportService->getAllBlogReports());
    }

    public function getEventReports(Request $request): JsonResponse
    {
        $status = $request->query('status');
        $pending = filter_var($request->query('pending', 'false'), FILTER_VALIDATE_BOOLEAN);

        if ($status) {
            return response()->json($this->reportService->getEventReportsByStatus($status));
        }

        if ($pending) {
            return response()->json($this->reportService->getPendingEventReports());
        }

        return response()->json($this->reportService->getAllEventReports());
    }

    public function reviewBlogReport(Request $request, int $id): JsonResponse
    {
        $user = $this->getSessionUser($request);
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $this->reportService->reviewBlogReport($id);
        return response()->json(['message' => 'Blog report reviewed']);
    }

    public function dismissBlogReport(Request $request, int $id): JsonResponse
    {
        $user = $this->getSessionUser($request);
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $this->reportService->dismissBlogReport($id);
        return response()->json(['message' => 'Blog report dismissed']);
    }

    public function reviewEventReport(Request $request, int $id): JsonResponse
    {
        $user = $this->getSessionUser($request);
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $this->reportService->reviewEventReport($id);
        return response()->json(['message' => 'Event report reviewed']);
    }

    public function dismissEventReport(Request $request, int $id): JsonResponse
    {
        $user = $this->getSessionUser($request);
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $this->reportService->dismissEventReport($id);
        return response()->json(['message' => 'Event report dismissed']);
    }

    private function getSessionUser(Request $request): ?User
    {
        $userId = $request->session()->get('user_id');
        if (!$userId) {
            return null;
        }

        return User::find($userId);
    }
}
