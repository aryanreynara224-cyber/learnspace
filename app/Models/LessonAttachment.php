<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'title',
        'file_path',
        'file_type',
        'file_size',
        'is_external',
    ];

    protected $casts = [
        'is_external' => 'boolean',
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Dapatkan URL file attachment (baik lokal storage maupun URL eksternal)
     */
    public function getUrlAttribute(): string
    {
        if ($this->is_external) {
            return $this->file_path;
        }

        return asset('storage/' . $this->file_path);
    }
}

