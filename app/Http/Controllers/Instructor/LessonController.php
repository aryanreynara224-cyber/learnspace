<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LessonController extends Controller
{
    public function create(Course $course)
    {
        abort_if($course->instructor_id !== auth()->id(), 403);

        $sections = $course->sections()->orderBy('order')->get();

        return view('instructor.lessons.create', compact('course', 'sections'));
    }

    public function store(Request $request, Course $course)
    {
        abort_if($course->instructor_id !== auth()->id(), 403);

        $validated = $request->validate([
            'section_id'  => ['nullable', 'exists:course_sections,id'],
            'title'       => ['required', 'string', 'max:255'],
            'type'        => ['required', 'in:video,audio,article'],
            'description' => ['nullable', 'string'],
            'content'     => ['nullable', 'string'],
            'video_url'   => ['nullable', 'url'],
            'audio_url'   => ['nullable', 'url'],
            'order'       => ['required', 'integer', 'min:1'],
            'duration'    => ['nullable', 'integer', 'min:1'],

            // Lampiran file (PDF, ZIP, source code)
            'attachment_files.*' => ['nullable', 'file', 'max:10240'], // maks 10MB
            'attachment_titles.*'=> ['nullable', 'string', 'max:255'],

            // Lampiran link eksternal (Google Drive, GitHub repo, dll)
            'external_titles.*'  => ['nullable', 'string', 'max:255'],
            'external_urls.*'    => ['nullable', 'url'],
        ]);

        $validated['course_id'] = $course->id;

        $lesson = Lesson::create($validated);

        // Simpan File Attachment Upload
        if ($request->hasFile('attachment_files')) {
            $files = $request->file('attachment_files');
            $titles = $request->input('attachment_titles', []);

            foreach ($files as $index => $file) {
                if ($file && $file->isValid()) {
                    $path = $file->store('lesson-attachments/' . $lesson->id, 'public');
                    $extension = strtolower($file->getClientOriginalExtension());
                    $fileSizeFormatted = round($file->getSize() / 1024 / 1024, 2) . ' MB';
                    if ($file->getSize() < 1048576) {
                        $fileSizeFormatted = round($file->getSize() / 1024, 1) . ' KB';
                    }

                    $title = !empty($titles[$index]) ? $titles[$index] : $file->getClientOriginalName();

                    $lesson->attachments()->create([
                        'title'       => $title,
                        'file_path'   => $path,
                        'file_type'   => $extension,
                        'file_size'   => $fileSizeFormatted,
                        'is_external' => false,
                    ]);
                }
            }
        }

        // Simpan Link Eksternal
        if ($request->has('external_urls')) {
            $extUrls = $request->input('external_urls', []);
            $extTitles = $request->input('external_titles', []);

            foreach ($extUrls as $index => $url) {
                if (!empty($url)) {
                    $title = !empty($extTitles[$index]) ? $extTitles[$index] : 'Tautan Pendukung';
                    $lesson->attachments()->create([
                        'title'       => $title,
                        'file_path'   => $url,
                        'file_type'   => 'link',
                        'file_size'   => 'Eksternal',
                        'is_external' => true,
                    ]);
                }
            }
        }

        return redirect()->route('instructor.courses.edit', $course->id)
            ->with('success', 'Materi pembelajaran berhasil ditambahkan!');
    }

    public function edit(Lesson $lesson)
    {
        abort_if($lesson->course->instructor_id !== auth()->id(), 403);

        $course = $lesson->course;
        $sections = $course->sections()->orderBy('order')->get();
        $attachments = $lesson->attachments;

        return view('instructor.lessons.edit', compact('lesson', 'course', 'sections', 'attachments'));
    }

    public function update(Request $request, Lesson $lesson)
    {
        abort_if($lesson->course->instructor_id !== auth()->id(), 403);

        $validated = $request->validate([
            'section_id'  => ['nullable', 'exists:course_sections,id'],
            'title'       => ['required', 'string', 'max:255'],
            'type'        => ['required', 'in:video,audio,article'],
            'description' => ['nullable', 'string'],
            'content'     => ['nullable', 'string'],
            'video_url'   => ['nullable', 'url'],
            'audio_url'   => ['nullable', 'url'],
            'order'       => ['required', 'integer', 'min:1'],
            'duration'    => ['nullable', 'integer', 'min:1'],

            // Lampiran baru
            'attachment_files.*' => ['nullable', 'file', 'max:10240'],
            'attachment_titles.*'=> ['nullable', 'string', 'max:255'],
            'external_titles.*'  => ['nullable', 'string', 'max:255'],
            'external_urls.*'    => ['nullable', 'url'],
        ]);

        $lesson->update($validated);

        // Simpan File Attachment Upload Baru
        if ($request->hasFile('attachment_files')) {
            $files = $request->file('attachment_files');
            $titles = $request->input('attachment_titles', []);

            foreach ($files as $index => $file) {
                if ($file && $file->isValid()) {
                    $path = $file->store('lesson-attachments/' . $lesson->id, 'public');
                    $extension = strtolower($file->getClientOriginalExtension());
                    $fileSizeFormatted = round($file->getSize() / 1024 / 1024, 2) . ' MB';
                    if ($file->getSize() < 1048576) {
                        $fileSizeFormatted = round($file->getSize() / 1024, 1) . ' KB';
                    }

                    $title = !empty($titles[$index]) ? $titles[$index] : $file->getClientOriginalName();

                    $lesson->attachments()->create([
                        'title'       => $title,
                        'file_path'   => $path,
                        'file_type'   => $extension,
                        'file_size'   => $fileSizeFormatted,
                        'is_external' => false,
                    ]);
                }
            }
        }

        // Simpan Link Eksternal Baru
        if ($request->has('external_urls')) {
            $extUrls = $request->input('external_urls', []);
            $extTitles = $request->input('external_titles', []);

            foreach ($extUrls as $index => $url) {
                if (!empty($url)) {
                    $title = !empty($extTitles[$index]) ? $extTitles[$index] : 'Tautan Pendukung';
                    $lesson->attachments()->create([
                        'title'       => $title,
                        'file_path'   => $url,
                        'file_type'   => 'link',
                        'file_size'   => 'Eksternal',
                        'is_external' => true,
                    ]);
                }
            }
        }

        return redirect()->route('instructor.courses.edit', $lesson->course_id)
            ->with('success', 'Materi berhasil diperbarui!');
    }

    public function destroy(Lesson $lesson)
    {
        abort_if($lesson->course->instructor_id !== auth()->id(), 403);

        $courseId = $lesson->course_id;

        // Hapus file attachment jika ada
        foreach ($lesson->attachments as $attachment) {
            if (!$attachment->is_external && Storage::disk('public')->exists($attachment->file_path)) {
                Storage::disk('public')->delete($attachment->file_path);
            }
        }

        $lesson->delete();

        return redirect()->route('instructor.courses.edit', $courseId)
            ->with('success', 'Materi berhasil dihapus.');
    }

    /**
     * Hapus lampiran materi
     */
    public function destroyAttachment(LessonAttachment $attachment)
    {
        $lesson = $attachment->lesson;
        abort_if($lesson->course->instructor_id !== auth()->id(), 403);

        if (!$attachment->is_external && Storage::disk('public')->exists($attachment->file_path)) {
            Storage::disk('public')->delete($attachment->file_path);
        }

        $attachment->delete();

        return back()->with('success', 'Lampiran berhasil dihapus.');
    }
}
