<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'section_id',
        'title',
        'type', // video, audio, article
        'description',
        'content',
        'video_url',
        'audio_url',
        'order',
        'duration',
    ];

    // ==========================================
    // RELATIONSHIPS
    // ==========================================

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function section()
    {
        return $this->belongsTo(CourseSection::class, 'section_id');
    }

    public function progress()
    {
        return $this->hasMany(LessonProgress::class);
    }

    public function attachments()
    {
        return $this->hasMany(LessonAttachment::class);
    }

    public function notes()
    {
        return $this->hasMany(LessonNote::class);
    }

    // ==========================================
    // HELPERS
    // ==========================================

    /** Cek apakah lesson sudah diselesaikan oleh user tertentu */
    public function isCompletedBy(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        return $this->progress()->where('user_id', $user->id)->exists();
    }

    /** Cek apakah materi ini merupakan pratinjau gratis (minimal 3 materi pertama selalu bisa dibuka) */
    public function isFreePreview(): bool
    {
        return $this->order <= 3;
    }

    /** Cek apakah user saat ini dapat melihat materi ini */
    public function canBeViewedBy(?User $user): bool
    {
        // Minimal 3 modul pertama selalu bisa dibuka oleh siapapun (termasuk tamu)
        if ($this->isFreePreview()) {
            return true;
        }

        if (!$user) {
            return false;
        }

        if ($user->isAdmin() || $user->id === $this->course->instructor_id) {
            return true;
        }

        return $user->isEnrolledIn($this->course);
    }

    /** Cek apakah materi memiliki audio/podcast */
    public function hasAudio(): bool
    {
        return $this->type === 'audio' || !empty($this->audio_url);
    }

    /** Cek apakah materi memiliki video */
    public function hasVideo(): bool
    {
        return !empty($this->video_url) && $this->type !== 'audio';
    }
}
