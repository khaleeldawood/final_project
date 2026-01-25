<?php

use Illuminate\Support\Facades\Route;
Route::get('/', function () {
    return view('home');
});

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/register', function () {
    return view('auth.register');
});

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
});

Route::get('/reset-password', function () {
    return view('auth.reset-password');
});





Route::get('/about', function () {
    return view('about');
});

Route::get('/features', function () {
    return view('features');
});

Route::get('/faq', function () {
    return view('faq');
});

Route::get('/privacy-policy', function () {
    return view('privacy-policy');
});

Route::get('/terms-of-service', function () {
    return view('terms-of-service');
});

Route::get('/cookie-policy', function () {
    return view('cookie-policy');
});

Route::get('/guidelines', function () {
    return view('guidelines');
});

Route::get('/contact', function () {
    return view('contact');
});

Route::get('/support', function () {
    return view('support');
});

Route::get('/leaderboard', function () {
    return view('leaderboard.index');
});

Route::get('/badges', function () {
    return view('badges.index');
});

Route::get('/dashboard', function () {
    return view('dashboard.index');
});

Route::get('/settings', function () {
    return view('settings.index');
});

Route::get('/profile/{id}', function () {
    return view('profile.show');
});

Route::get('/events', function () {
    return view('events.index');
});

Route::get('/events/approvals', function () {
    return view('events.approvals');
});

Route::get('/events/new', function () {
    return view('events.create');
});

Route::get('/events/{id}/edit', function () {
    return view('events.edit');
});

Route::get('/events/{id}', function () {
    return view('events.show');
});

Route::get('/my-events', function () {
    return view('events.my-events');
});

Route::get('/participation-requests', function () {
    return view('events.participation-requests');
});

Route::get('/my-event-requests', function () {
    return view('events.my-event-requests');
});

Route::get('/blogs', function () {
    return view('blogs.index');
});

Route::get('/blogs/approvals', function () {
    return view('blogs.approvals');
});

Route::get('/blogs/new', function () {
    return view('blogs.create');
});

Route::get('/blogs/{id}/edit', function () {
    return view('blogs.edit');
});

Route::get('/blogs/{id}', function () {
    return view('blogs.show');
});

Route::get('/my-blogs', function () {
    return view('blogs.my-blogs');
});

Route::get('/notifications', function () {
    return view('notifications.index');
});

Route::get('/reports', function () {
    return view('reports.index');
});

/*
|--------------------------------------------------------------------------
| Admin Users UI (Blade)
|--------------------------------------------------------------------------
*/
Route::get('/admin/users/ui', function () {
    return view('admin.users.index');
});

Route::get('/admin/users', function () {
    return view('admin.users.index');
});

Route::get('/admin/universities', function () {
    return view('admin.universities.index');
});

Route::get('/admin/analytics', function () {
    return view('admin.analytics.index');
});

Route::fallback(function () {
    return response()->view('not-found', [], 404);
});