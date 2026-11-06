<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseSection;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    /**
     * Tambah Bab / Modul baru pada Kursus
     */
    public function store(Request $request, Course $course)
    {
        abort_if($course->instructor_id !== auth()->id(), 403);

        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'order'       => ['nullable', 'integer', 'min:1'],
        ]);

        if (empty($validated['order'])) {
            $maxOrder = $course->sections()->max('order') ?? 0;
            $validated['order'] = $maxOrder + 1;
        }

        $validated['course_id'] = $course->id;

        $course->sections()->create($validated);

        return back()->with('success', 'Bab / Section baru berhasil ditambahkan!');
    }

    /**
     * Update Bab / Modul
     */
    public function update(Request $request, CourseSection $section)
    {
        abort_if($section->course->instructor_id !== auth()->id(), 403);

        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'order'       => ['required', 'integer', 'min:1'],
        ]);

        $section->update($validated);

        return back()->with('success', 'Bab / Section berhasil diperbarui!');
    }

    /**
     * Hapus Bab / Modul
     */
    public function destroy(CourseSection $section)
    {
        abort_if($section->course->instructor_id !== auth()->id(), 403);

        $section->delete();

        return back()->with('success', 'Bab / Section berhasil dihapus.');
    }
}

