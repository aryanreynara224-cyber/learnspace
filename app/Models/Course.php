<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'instructor_id',
        'title',
        'slug',
        'description',
        'thumbnail',
        'level',
        'duration',
        'status',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($course) {
            if (empty($course->slug)) {
                $course->slug = static::generateUniqueSlug(Str::slug($course->title));
            }
        });
    }

    /**
     * Buat slug yang unik: jika slug sudah ada, tambahkan suffix angka.
     * Contoh: belajar-laravel → belajar-laravel-2 → belajar-laravel-3
     */
    public static function generateUniqueSlug(string $baseSlug): string
    {
        $slug  = $baseSlug;
        $count = 2;

        while (static::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$count}";
            $count++;
        }

        return $slug;
    }

    // ==========================================
    // RELATIONSHIPS
    // ==========================================

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function sections()
    {
        return $this->hasMany(CourseSection::class)->orderBy('order');
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'enrollments')
            ->withPivot('enrolled_at', 'completed_at')
            ->withTimestamps();
    }

    // ==========================================
    // HELPERS
    // ==========================================

    /** Hitung progress seorang student di kursus ini */
    public function getProgressForUser(User $user): int
    {
        $lessons = $this->lessons;
        $totalLessons = $lessons->count();

        if ($totalLessons === 0) {
            return 0;
        }

        $lessonIds = $lessons->pluck('id');
        $completedLessons = LessonProgress::where('user_id', $user->id)
            ->whereIn('lesson_id', $lessonIds)
            ->count();

        return (int) round(($completedLessons / $totalLessons) * 100);
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function getLevelLabelAttribute(): string
    {
        return match ($this->level) {
            'beginner'     => 'Pemula',
            'intermediate' => 'Menengah',
            'advanced'     => 'Mahir',
            default        => $this->level,
        };
    }

    public function getFormattedDurationAttribute(): string
    {
        $hours = intdiv($this->duration, 60);
        $minutes = $this->duration % 60;

        if ($hours > 0 && $minutes > 0) {
            return "{$hours}j {$minutes}m";
        } elseif ($hours > 0) {
            return "{$hours} jam";
        } else {
            return "{$minutes} menit";
        }
    }

    public function getThumbnailUrlAttribute(): string
    {
        if (!$this->thumbnail) {
            return asset('images/courses/laravel.jpg');
        }

        if (str_starts_with($this->thumbnail, 'http://') || str_starts_with($this->thumbnail, 'https://')) {
            return $this->thumbnail;
        }

        if (file_exists(public_path('images/' . $this->thumbnail))) {
            return asset('images/' . $this->thumbnail);
        }

        return asset('storage/' . $this->thumbnail);
    }
}
