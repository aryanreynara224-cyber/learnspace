<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'bio',
        'xp_points',
        'provider',
        'provider_id',
        'provider_avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ==========================================
    // HELPER METHODS
    // ==========================================

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isInstructor(): bool
    {
        return $this->role === 'instructor';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    // ==========================================
    // RELATIONSHIPS
    // ==========================================

    /** Kursus yang dibuat oleh instructor ini */
    public function courses()
    {
        return $this->hasMany(Course::class, 'instructor_id');
    }

    /** Enrollment student ke kursus */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    /** Kursus yang diikuti student (melalui enrollment) */
    public function enrolledCourses()
    {
        return $this->belongsToMany(Course::class, 'enrollments')
            ->withPivot('enrolled_at', 'completed_at')
            ->withTimestamps();
    }

    /** Progress lesson student */
    public function lessonProgress()
    {
        return $this->hasMany(LessonProgress::class);
    }

    /** Cek apakah student sudah enroll ke course tertentu */
    public function isEnrolledIn(Course $course): bool
    {
        return $this->enrollments()->where('course_id', $course->id)->exists();
    }

    /** Badge yang dimiliki user */
    public function badges()
    {
        return $this->hasMany(UserBadge::class);
    }

    /** Catatan materi student */
    public function notes()
    {
        return $this->hasMany(LessonNote::class);
    }

    /** Log XP user */
    public function xpLogs()
    {
        return $this->hasMany(XpLog::class);
    }

    /** Klaim misi harian user */
    public function quests()
    {
        return $this->hasMany(UserQuest::class);
    }

    public function getAvatarUrlAttribute(): ?string
    {
        if (!$this->avatar) {
            return null;
        }

        if (str_starts_with($this->avatar, 'http://') || str_starts_with($this->avatar, 'https://')) {
            return $this->avatar;
        }

        if (file_exists(public_path('images/' . $this->avatar))) {
            return asset('images/' . $this->avatar);
        }

        return asset('storage/' . $this->avatar);
    }
}
