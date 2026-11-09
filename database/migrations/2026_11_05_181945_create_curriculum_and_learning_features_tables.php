<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Course Sections (Bab / Modul)
        Schema::create('course_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('order')->default(1);
            $table->timestamps();
        });

        // 2. Modifikasi Lessons untuk Section & Tipe Materi (Audio / Video / Article)
        Schema::table('lessons', function (Blueprint $table) {
            $table->foreignId('section_id')->nullable()->after('course_id')->constrained('course_sections')->nullOnDelete();
            $table->string('type')->default('video')->after('title'); // video, audio, article
            $table->string('audio_url')->nullable()->after('video_url');
        });

        // 3. Lesson Attachments (PDF Modul, Source Code ZIP, Cheat Sheet)
        Schema::create('lesson_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('file_path');
            $table->string('file_type')->nullable(); // pdf, zip, doc, other
            $table->string('file_size')->nullable();
            $table->boolean('is_external')->default(false);
            $table->timestamps();
        });

        // 4. Lesson Notes (Catatan Pribadi Siswa)
        Schema::create('lesson_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();
            $table->integer('timestamp_seconds')->nullable();
            $table->text('content');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lesson_notes');
        Schema::dropIfExists('lesson_attachments');

        Schema::table('lessons', function (Blueprint $table) {
            $table->dropForeign(['section_id']);
            $table->dropColumn(['section_id', 'type', 'audio_url']);
        });

        Schema::dropIfExists('course_sections');
    }
};
