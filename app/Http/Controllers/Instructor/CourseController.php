<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function index()
    {
        $courses = auth()->user()->courses()
            ->with(['category', 'lessons', 'enrollments'])
            ->latest()
            ->paginate(10);

        return view('instructor.courses.index', compact('courses'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('instructor.courses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['required', 'string'],
            'level'       => ['required', 'in:beginner,intermediate,advanced'],
            'duration'    => ['required', 'integer', 'min:1'],
            'status'      => ['required', 'in:draft,published'],
            'thumbnail'   => ['nullable', 'image', 'mimes:jpeg,png,webp,jpg', 'max:2048'],
        ]);

        $validated['instructor_id'] = auth()->id();
        // Slug unik: jika ada judul sama, hasilkan belajar-laravel-2, dst.
        $validated['slug'] = Course::generateUniqueSlug(Str::slug($validated['title']));

        // Handle upload thumbnail jika ada
        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        Course::create($validated);

        return redirect()->route('instructor.courses.index')
            ->with('success', 'Kursus berhasil dibuat!');
    }

    public function edit(Course $course)
    {
        // Pastikan hanya instructor pemilik yang bisa edit
        abort_if($course->instructor_id !== auth()->id(), 403);

        $course->load(['sections.lessons', 'lessons.attachments']);
        $categories = Category::all();
        return view('instructor.courses.edit', compact('course', 'categories'));
    }

    public function update(Request $request, Course $course)
    {
        abort_if($course->instructor_id !== auth()->id(), 403);

        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['required', 'string'],
            'level'       => ['required', 'in:beginner,intermediate,advanced'],
            'duration'    => ['required', 'integer', 'min:1'],
            'status'      => ['required', 'in:draft,published'],
            'thumbnail'   => ['nullable', 'image', 'mimes:jpeg,png,webp,jpg', 'max:2048'],
        ]);

        if ($request->hasFile('thumbnail')) {
            // Hapus thumbnail lama jika ada
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $course->update($validated);

        return redirect()->route('instructor.courses.index')
            ->with('success', 'Kursus berhasil diperbarui!');
    }

    public function destroy(Course $course)
    {
        abort_if($course->instructor_id !== auth()->id(), 403);

        $course->delete();

        return redirect()->route('instructor.courses.index')
            ->with('success', 'Kursus berhasil dihapus.');
    }

    /** Daftar peserta kursus */
    public function students(Course $course)
    {
        abort_if($course->instructor_id !== auth()->id(), 403);

        $students = $course->students()->paginate(20);

        return view('instructor.courses.students', compact('course', 'students'));
    }
}
