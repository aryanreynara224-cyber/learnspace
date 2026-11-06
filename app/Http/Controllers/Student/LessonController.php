<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\Enrollment;
use App\Models\XpLog;
use Illuminate\Support\Facades\DB;

class LessonController extends Controller
{
    /** Tampilkan lesson untuk dipelajari */
    public function show(Lesson $lesson)
    {
        $user = auth()->user();
        $course = $lesson->course->load(['sections.lessons', 'lessons', 'instructor']);
        $lesson->load(['attachments', 'section']);

        $userNotes = $user ? $lesson->notes()->where('user_id', $user->id)->orderBy('timestamp_seconds')->latest()->get() : collect();

        $isEnrolled = $user->isEnrolledIn($course);

        // Minimal 3 materi pertama selalu bisa dibuka bebas
        abort_unless($isEnrolled || $lesson->isFreePreview(), 403);

        $isCompleted = $lesson->isCompletedBy($user);

        // Lesson sebelum dan sesudah untuk navigasi
        $prevLesson = $course->lessons->where('order', '<', $lesson->order)->last();
        $nextLesson = $course->lessons->where('order', '>', $lesson->order)->first();

        // Progress kursus
        $completedLessonIds = LessonProgress::where('user_id', $user->id)
            ->whereIn('lesson_id', $course->lessons->pluck('id'))
            ->pluck('lesson_id')
            ->toArray();

        return view('student.lessons.show', compact(
            'lesson', 'course', 'isCompleted', 'isEnrolled', 'prevLesson', 'nextLesson', 'completedLessonIds', 'userNotes'
        ));
    }

    /** Tandai lesson sebagai selesai */
    public function complete(Lesson $lesson)
    {
        $user = auth()->user();
        $course = $lesson->course;

        // Jika belum enroll, arahkan untuk mendaftar terlebih dahulu agar progress tersimpan
        if (!$user->isEnrolledIn($course)) {
            return redirect()->route('student.courses.show', $course->slug)
                ->with('info', 'Silakan klik "Daftar Kursus" untuk dapat menyimpan dan mencatat progres materi Anda.');
        }

        // Simpan progress dan berikan XP jika materi baru selesai
        $progress = LessonProgress::firstOrCreate([
            'user_id'   => $user->id,
            'lesson_id' => $lesson->id,
        ], [
            'completed_at' => now(),
        ]);

        // Tambahkan 10 XP hanya jika progress baru berhasil dibuat
        if ($progress->wasRecentlyCreated) {
            XpLog::award($user, 10, 'lesson_completed', 'Menyelesaikan materi: ' . $lesson->title);

            // Badge untuk materi pertama yang diselesaikan
            $completedCount = $user->lessonProgress()->count();

            if ($completedCount >= 1) {
                DB::table('user_badges')->updateOrInsert(
                    ['user_id' => $user->id, 'badge_key' => 'first_lesson'],
                    ['earned_at' => now(), 'updated_at' => now(), 'created_at' => now()]
                );
            }

            // Badge untuk menyelesaikan 5 materi
            if ($completedCount >= 5) {
                DB::table('user_badges')->updateOrInsert(
                    ['user_id' => $user->id, 'badge_key' => 'five_lessons'],
                    ['earned_at' => now(), 'updated_at' => now(), 'created_at' => now()]
                );
            }
        }

        // Cek apakah seluruh kursus sudah selesai
        $totalLessons = $course->lessons()->count();
        $completedLessons = LessonProgress::where('user_id', $user->id)
            ->whereIn('lesson_id', $course->lessons()->pluck('id'))
            ->count();

        if ($totalLessons === $completedLessons) {
            // Update enrollment sebagai completed
            Enrollment::where('user_id', $user->id)
                ->where('course_id', $course->id)
                ->whereNull('completed_at')
                ->update(['completed_at' => now()]);

            return redirect()->route('student.courses.show', $course->slug)
                ->with('success', 'Selamat! Anda telah menyelesaikan semua materi kursus "' . $course->title . '"! 🎉');
        }

        // Lanjut ke lesson berikutnya jika ada
        $nextLesson = $course->lessons()
            ->where('order', '>', $lesson->order)
            ->first();

        if ($nextLesson) {
            return redirect()->route('student.lessons.show', $nextLesson->id)
                ->with('success', 'Materi berhasil ditandai selesai. Lanjut ke materi berikutnya!');
        }

        return redirect()->route('student.courses.show', $course->slug)
            ->with('success', 'Materi berhasil ditandai selesai.');
    }
}
