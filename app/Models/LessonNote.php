<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lesson_id',
        'timestamp_seconds',
        'content',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Format timestamp detik menjadi mm:ss (contoh: 125 detik -> 02:05)
     */
    public function getFormattedTimestampAttribute(): ?string
    {
        if ($this->timestamp_seconds === null) {
            return null;
        }

        $minutes = floor($this->timestamp_seconds / 60);
        $seconds = $this->timestamp_seconds % 60;

        return sprintf('%02d:%02d', $minutes, $seconds);
    }
}

