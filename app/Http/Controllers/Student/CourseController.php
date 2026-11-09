<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\LessonProgress;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /** Daftar kursus yang diikuti student */
    public function index()
    {
        $user = auth()->user();

        $enrollments = $user->enrollments()
            ->with(['course.lessons', 'course.category', 'course.instructor'])
            ->latest()
            ->paginate(12);

        return view('student.courses.index', compact('enrollments'));
    }

    /** Detail kursus + progress student */
    public function show(Course $course)
    {
        $user = auth()->user();

        // Pastikan sudah enroll
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->firstOrFail();

        $course->load(['sections.lessons', 'lessons.attachments', 'instructor', 'category']);

        // Ambil ID lesson yang sudah selesai
        $completedLessonIds = LessonProgress::where('user_id', $user->id)
            ->whereIn('lesson_id', $course->lessons->pluck('id'))
            ->pluck('lesson_id')
            ->toArray();

        $progress = $course->getProgressForUser($user);

        return view('student.courses.show', compact('course', 'enrollment', 'completedLessonIds', 'progress'));
    }

    /** Proses enrollment ke kursus */
    public function enroll(Course $course)
    {
        $user = auth()->user();

        // Cek apakah sudah enroll
        if ($user->isEnrolledIn($course)) {
            return redirect()->route('student.courses.show', $course->slug)
                ->with('info', 'Anda sudah terdaftar di kursus ini.');
        }

        // Hanya kursus published yang bisa dienroll
        abort_if(!$course->isPublished(), 404);

        Enrollment::create([
            'user_id'     => $user->id,
            'course_id'   => $course->id,
            'enrolled_at' => now(),
        ]);

        return redirect()->route('student.courses.show', $course->slug)
            ->with('success', 'Selamat! Anda berhasil mendaftar ke kursus "' . $course->title . '".');
    }

    /** Tampilkan sertifikat kelulusan resmi */
    public function certificate(Course $course)
    {
        $user = auth()->user();

        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->firstOrFail();

        $progress = $course->getProgressForUser($user);

        if ($progress < 100 && !$enrollment->isCompleted()) {
            return redirect()->route('student.courses.show', $course->slug)
                ->with('error', 'Sertifikat hanya dapat diakses setelah menyelesaikan 100% materi kursus.');
        }

        if (!$enrollment->completed_at) {
            $enrollment->update(['completed_at' => now()]);
        }

        $course->load(['instructor', 'category']);

        return view('student.certificate', compact('course', 'enrollment', 'user'));
    }
}
