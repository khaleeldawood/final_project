<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAnalyticsController;
use App\Http\Controllers\Admin\AdminUniversityController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventParticipationRequestController;
use App\Http\Controllers\EventRequestController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\GamificationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;

Route::prefix('admin')->group(function () {
    Route::get('users', [AdminUserController::class, 'index']);
    Route::post('users', [AdminUserController::class, 'store']);
    Route::get('users/{id}', [AdminUserController::class, 'show']);
    Route::put('users/{id}', [AdminUserController::class, 'update']);
    Route::delete('users/{id}', [AdminUserController::class, 'destroy']);

    Route::get('universities', [AdminUniversityController::class, 'index']);
    Route::post('universities', [AdminUniversityController::class, 'store']);
    Route::put('universities/{id}', [AdminUniversityController::class, 'update']);
    Route::delete('universities/{id}', [AdminUniversityController::class, 'destroy']);

    Route::get('analytics', [AdminAnalyticsController::class, 'index']);
});

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('session', [AuthController::class, 'session']);
    Route::get('verify-email', [AuthController::class, 'verifyEmail']);
    Route::post('resend-verification', [AuthController::class, 'resendVerification']);
    Route::get('check', [AuthController::class, 'check']);
});

Route::prefix('events')->group(function () {
    Route::get('', [EventController::class, 'index']);
    Route::post('', [EventController::class, 'store']);
    Route::get('my-events', [EventController::class, 'myEvents']);
    Route::get('my-participations', [EventController::class, 'myParticipations']);
    Route::get('{id}/participants', [EventController::class, 'participants']);
    Route::post('{id}/join', [EventController::class, 'join']);
    Route::post('{id}/leave', [EventController::class, 'leave']);
    Route::put('{id}/approve', [EventController::class, 'approve']);
    Route::put('{id}/reject', [EventController::class, 'reject']);
    Route::put('{id}/cancel', [EventController::class, 'cancel']);
    Route::get('{id}', [EventController::class, 'show']);
    Route::put('{id}', [EventController::class, 'update']);
    Route::delete('{id}', [EventController::class, 'destroy']);
});

Route::prefix('event-participation-requests')->group(function () {
    Route::post('events/{eventId}', [EventParticipationRequestController::class, 'submitRequest']);
    Route::get('events/{eventId}', [EventParticipationRequestController::class, 'getEventRequests']);
    Route::get('my-requests', [EventParticipationRequestController::class, 'getMyRequests']);
    Route::post('{requestId}/approve', [EventParticipationRequestController::class, 'approveRequest']);
    Route::post('{requestId}/reject', [EventParticipationRequestController::class, 'rejectRequest']);
});

Route::prefix('event-requests')->group(function () {
    Route::post('', [EventRequestController::class, 'store']);
    Route::put('{id}/accept', [EventRequestController::class, 'accept']);
    Route::put('{id}/reject', [EventRequestController::class, 'reject']);
    Route::get('my-requests', [EventRequestController::class, 'myRequests']);
    Route::get('event/{eventId}', [EventRequestController::class, 'eventRequests']);
});

Route::prefix('blogs')->group(function () {
    Route::get('', [BlogController::class, 'index']);
    Route::post('', [BlogController::class, 'store']);
    Route::get('my-blogs', [BlogController::class, 'myBlogs']);
    Route::get('pending', [BlogController::class, 'pending']);
    Route::get('{id}', [BlogController::class, 'show']);
    Route::put('{id}', [BlogController::class, 'update']);
    Route::put('{id}/approve', [BlogController::class, 'approve']);
    Route::put('{id}/reject', [BlogController::class, 'reject']);
    Route::delete('{id}', [BlogController::class, 'destroy']);
});

Route::prefix('notifications')->group(function () {
    Route::get('', [NotificationController::class, 'index']);
    Route::get('unread-count', [NotificationController::class, 'unreadCount']);
    Route::put('{id}/read', [NotificationController::class, 'markAsRead']);
    Route::put('read-all', [NotificationController::class, 'markAllAsRead']);
});

Route::prefix('gamification')->group(function () {
    Route::get('leaderboard', [GamificationController::class, 'leaderboard']);
    Route::get('top-members', [GamificationController::class, 'topMembers']);
    Route::get('top-events', [GamificationController::class, 'topEvents']);
    Route::get('my-rank', [GamificationController::class, 'myRank']);
    Route::get('rank/{userId}', [GamificationController::class, 'userRank']);
    Route::get('badges', [GamificationController::class, 'badges']);
    Route::get('my-badges', [GamificationController::class, 'myBadges']);
});

Route::prefix('reports')->group(function () {
    Route::post('blogs/{id}', [ReportController::class, 'reportBlog']);
    Route::post('events/{id}', [ReportController::class, 'reportEvent']);
    Route::get('blogs', [ReportController::class, 'getBlogReports']);
    Route::get('events', [ReportController::class, 'getEventReports']);
    Route::put('blogs/{id}/review', [ReportController::class, 'reviewBlogReport']);
    Route::put('blogs/{id}/dismiss', [ReportController::class, 'dismissBlogReport']);
    Route::put('events/{id}/review', [ReportController::class, 'reviewEventReport']);
    Route::put('events/{id}/dismiss', [ReportController::class, 'dismissEventReport']);
});

Route::prefix('users')->group(function () {
    Route::get('me', [UserController::class, 'getCurrentUser']);
    Route::get('', [UserController::class, 'getAllUsers']);
    Route::get('{id}', [UserController::class, 'getUserById']);
    Route::put('me', [UserController::class, 'updateProfile']);
    Route::put('change-password', [UserController::class, 'changePassword']);
    Route::put('set-password', [UserController::class, 'setPassword']);
    Route::put('university', [UserController::class, 'updateUniversity']);
});