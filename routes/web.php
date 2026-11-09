<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Instructor;
use App\Http\Controllers\Student;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ==========================================
// PUBLIC ROUTES
// ==========================================

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{course:slug}', [CourseController::class, 'show'])->name('courses.show');
Route::get('/courses/{course:slug}/lessons/{lesson}', [CourseController::class, 'lesson'])->name('courses.lessons.show');
Route::get('/verify-certificate/{code}', [HomeController::class, 'verifyCertificate'])->name('certificate.verify');

// ==========================================
// AUTH ROUTES
// ==========================================

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // Socialite OAuth Routes (Google & Facebook)
    Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])->name('auth.social.redirect');
    Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback'])->name('auth.social.callback');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// ==========================================
// PROFILE ROUTES (all authenticated users)
// ==========================================

Route::middleware('auth')->prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'edit'])->name('edit');
    Route::put('/', [ProfileController::class, 'update'])->name('update');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password');
    Route::delete('/avatar', [ProfileController::class, 'deleteAvatar'])->name('avatar.delete');
});

// ==========================================
// STUDENT ROUTES
// ==========================================

Route::prefix('student')->name('student.')->middleware(['auth', 'role:student'])->group(function () {
    Route::get('/dashboard', [Student\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/courses', [Student\CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/{course:slug}', [Student\CourseController::class, 'show'])->name('courses.show');
    Route::get('/courses/{course:slug}/certificate', [Student\CourseController::class, 'certificate'])->name('courses.certificate');
    Route::post('/courses/{course}/enroll', [Student\CourseController::class, 'enroll'])->name('courses.enroll');
    Route::get('/lessons/{lesson}', [Student\LessonController::class, 'show'])->name('lessons.show')->middleware('enrolled');
    Route::post('/lessons/{lesson}/complete', [Student\LessonController::class, 'complete'])->name('lessons.complete')->middleware('enrolled');
    Route::get('/leaderboard', [Student\LeaderboardController::class, 'index'])->name('leaderboard');
    Route::post('/quests/claim', [Student\LeaderboardController::class, 'claimQuest'])->name('quests.claim');
    Route::post('/lessons/{lesson}/focus-complete', [Student\FocusSessionController::class, 'complete'])->name('lessons.focus-complete');

    // Student Notes
    Route::get('/lessons/{lesson}/notes', [Student\NoteController::class, 'index'])->name('lessons.notes.index');
    Route::post('/lessons/{lesson}/notes', [Student\NoteController::class, 'store'])->name('lessons.notes.store');
    Route::delete('/notes/{note}', [Student\NoteController::class, 'destroy'])->name('notes.destroy');
});

// ==========================================
// INSTRUCTOR ROUTES
// ==========================================

Route::prefix('instructor')->name('instructor.')->middleware(['auth', 'role:instructor'])->group(function () {
    Route::get('/dashboard', [Instructor\DashboardController::class, 'index'])->name('dashboard');

    // Course CRUD (exclude show, pakai route explicit)
    Route::get('/courses', [Instructor\CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/create', [Instructor\CourseController::class, 'create'])->name('courses.create');
    Route::post('/courses', [Instructor\CourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/{course}/edit', [Instructor\CourseController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{course}', [Instructor\CourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{course}', [Instructor\CourseController::class, 'destroy'])->name('courses.destroy');
    Route::get('/courses/{course}/students', [Instructor\CourseController::class, 'students'])->name('courses.students');

    // Section / Bab CRUD
    Route::post('/courses/{course}/sections', [Instructor\SectionController::class, 'store'])->name('sections.store');
    Route::put('/sections/{section}', [Instructor\SectionController::class, 'update'])->name('sections.update');
    Route::delete('/sections/{section}', [Instructor\SectionController::class, 'destroy'])->name('sections.destroy');

    // Lesson CRUD
    Route::get('/courses/{course}/lessons/create', [Instructor\LessonController::class, 'create'])->name('lessons.create');
    Route::post('/courses/{course}/lessons', [Instructor\LessonController::class, 'store'])->name('lessons.store');
    Route::get('/lessons/{lesson}/edit', [Instructor\LessonController::class, 'edit'])->name('lessons.edit');
    Route::put('/lessons/{lesson}', [Instructor\LessonController::class, 'update'])->name('lessons.update');
    Route::delete('/lessons/{lesson}', [Instructor\LessonController::class, 'destroy'])->name('lessons.destroy');
    Route::delete('/attachments/{attachment}', [Instructor\LessonController::class, 'destroyAttachment'])->name('lessons.attachment.destroy');
});

// ==========================================
// ADMIN ROUTES
// ==========================================

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // User CRUD
    Route::get('/users', [Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [Admin\UserController::class, 'create'])->name('users.create');
    Route::post('/users', [Admin\UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [Admin\UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [Admin\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [Admin\UserController::class, 'destroy'])->name('users.destroy');

    // Category CRUD
    Route::get('/categories', [Admin\CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [Admin\CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [Admin\CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [Admin\CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [Admin\CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [Admin\CategoryController::class, 'destroy'])->name('categories.destroy');

    // Course overview
    Route::get('/courses', [Admin\CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/{course}', [Admin\CourseController::class, 'show'])->name('courses.show');
    Route::delete('/courses/{course}', [Admin\CourseController::class, 'destroy'])->name('courses.destroy');
});
