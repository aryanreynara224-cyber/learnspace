<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;

class CourseController extends Controller
{
    /** Halaman daftar kursus publik dengan search dan filter */
    public function index()
    {
        $query = Course::with(['category', 'instructor', 'lessons'])
            ->where('status', 'published');

        // Search
        if (request('search')) {
            $query->where('title', 'like', '%' . request('search') . '%');
        }

        // Filter kategori
        if (request('category')) {
            $query->whereHas('category', function ($q) {
                $q->where('slug', request('category'));
            });
        }

        // Filter level
        if (request('level')) {
            $query->where('level', request('level'));
        }

        $courses = $query->latest()->paginate(12)->withQueryString();
        $categories = Category::all();

        return view('courses.index', compact('courses', 'categories'));
    }

    /** Halaman detail kursus */
    public function show(Course $course)
    {
        // Hanya tampilkan kursus yang published
        abort_if(!$course->isPublished(), 404);

        $course->load(['category', 'instructor', 'sections.lessons', 'lessons.attachments']);
        $isEnrolled = auth()->check() && auth()->user()->isEnrolledIn($course);

        return view('courses.show', compact('course', 'isEnrolled'));
    }

    /** Halaman modul pembelajaran (bisa dibuka minimal 3 materi pertama untuk pratinjau publik/gratis) */
    public function lesson(Course $course, \App\Models\Lesson $lesson)
    {
        abort_if(!$course->isPublished(), 404);
        abort_if($lesson->course_id !== $course->id, 404);

        $user = auth()->user();
        $isEnrolled = $user && $user->isEnrolledIn($course);

        // Jika bukan 3 materi pertama dan belum terdaftar, redirect ke show dengan notifikasi
        if (!$lesson->isFreePreview() && !$isEnrolled) {
            return redirect()->route('courses.show', $course->slug)
                ->with('info', 'Materi ke-' . $lesson->order . ' terkunci. Silakan daftar ke kursus untuk membuka seluruh materi pembelajaran.');
        }

        $course->load(['category', 'instructor', 'sections.lessons', 'lessons.attachments']);

        $prevLesson = $course->lessons->where('order', '<', $lesson->order)->last();
        $nextLesson = $course->lessons->where('order', '>', $lesson->order)->first();

        $isCompleted = false;
        $completedLessonIds = [];
        if ($user) {
            $isCompleted = $lesson->isCompletedBy($user);
            $completedLessonIds = \App\Models\LessonProgress::where('user_id', $user->id)
                ->whereIn('lesson_id', $course->lessons->pluck('id'))
                ->pluck('lesson_id')
                ->toArray();
        }

        return view('courses.lesson', compact(
            'course', 'lesson', 'isEnrolled', 'prevLesson', 'nextLesson', 'isCompleted', 'completedLessonIds'
        ));
    }
}
