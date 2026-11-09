@extends('layouts.dashboard')
@section('title', 'Edit Materi')
@section('page-title', 'Edit Materi — ' . $lesson->title)
@section('sidebar-nav')
    <a href="{{ route('instructor.courses.edit', $course->id) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-100 transition-all">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali ke Kursus
    </a>
@endsection

@section('content')
<form method="POST" action="{{ route('instructor.lessons.update', $lesson->id) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="max-w-3xl space-y-6" x-data="{ lessonType: '{{ old('type', $lesson->type ?? 'video') }}' }">
        
        {{-- Kartu Informasi Utama --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-5 shadow-sm">
            <h2 class="text-base font-bold text-slate-900 pb-2 border-b border-slate-100 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span>
                Informasi & Bab Materi
            </h2>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Judul Materi <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title', $lesson->title) }}" required
                       class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Pilihan Bab / Section --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Pilih Bab / Modul</label>
                    <select name="section_id" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
                        <option value="">-- Tanpa Bab (Materi Umum) --</option>
                        @foreach($sections as $sec)
                            <option value="{{ $sec->id }}" {{ old('section_id', $lesson->section_id) == $sec->id ? 'selected' : '' }}>
                                Bab {{ $sec->order }}: {{ $sec->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tipe Materi --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tipe Pembelajaran <span class="text-red-500">*</span></label>
                    <select name="type" x-model="lessonType" required class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
                        <option value="video">🎬 Video Interaktif</option>
                        <option value="audio">🎧 Audio / Podcast Pembelajaran</option>
                        <option value="article">📄 Artikel & Bacaan (Teks/Kode)</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Deskripsi Singkat</label>
                <input type="text" name="description" value="{{ old('description', $lesson->description) }}"
                       class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Urutan Materi <span class="text-red-500">*</span></label>
                    <input type="number" name="order" value="{{ old('order', $lesson->order) }}" min="1" required
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Estimasi Durasi (menit)</label>
                    <input type="number" name="duration" value="{{ old('duration', $lesson->duration) }}" min="1"
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>
        </div>

        {{-- Kartu Media & Konten --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-5 shadow-sm">
            <h2 class="text-base font-bold text-slate-900 pb-2 border-b border-slate-100 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-violet-600"></span>
                Media & Konten Pelajaran
            </h2>

            {{-- Input Video --}}
            <div x-show="lessonType === 'video'" x-cloak>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5 flex items-center justify-between">
                    <span>URL Video YouTube / Embed</span>
                    <span class="text-xs text-slate-400 font-normal">Contoh: https://www.youtube.com/embed/...</span>
                </label>
                <input type="url" name="video_url" value="{{ old('video_url', $lesson->video_url) }}"
                       placeholder="https://www.youtube.com/embed/..."
                       class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @error('video_url')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Input Audio --}}
            <div x-show="lessonType === 'audio'" x-cloak>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5 flex items-center justify-between">
                    <span>URL Stream / File Audio / Podcast (MP3/WAV)</span>
                    <span class="text-xs text-slate-400 font-normal">Direct link file audio</span>
                </label>
                <input type="url" name="audio_url" value="{{ old('audio_url', $lesson->audio_url) }}"
                       placeholder="https://example.com/audio/episode-1.mp3"
                       class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @error('audio_url')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Konten Teks / Markdown --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5 flex items-center justify-between">
                    <span>Konten Penjelasan & Rangkuman Materi</span>
                    <span class="text-xs text-indigo-600 font-medium">Format Teks & Markdown</span>
                </label>
                <textarea name="content" rows="9"
                          class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none font-mono">{{ old('content', $lesson->content) }}</textarea>
            </div>
        </div>

        {{-- Kartu Lampiran Unduhan --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-4 shadow-sm">
            <h2 class="text-base font-bold text-slate-900 pb-2 border-b border-slate-100 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
                Berkas & Lampiran Unduhan Siswa
            </h2>

            {{-- Daftar Lampiran yang Sudah Ada --}}
            @if(isset($attachments) && $attachments->count() > 0)
                <div class="space-y-2 mb-3">
                    <p class="text-xs font-bold text-slate-700 uppercase tracking-wider">Lampiran Tersimpan:</p>
                    <div class="divide-y divide-slate-100 border border-slate-200 rounded-xl overflow-hidden bg-slate-50">
                        @foreach($attachments as $att)
                            <div class="flex items-center justify-between p-3 text-xs">
                                <div class="flex items-center gap-2.5 truncate mr-3">
                                    <span class="px-2 py-0.5 rounded font-mono font-bold uppercase text-[10px] {{ $att->is_external ? 'bg-blue-100 text-blue-700' : 'bg-emerald-100 text-emerald-700' }}">
                                        {{ $att->file_type ?? 'file' }}
                                    </span>
                                    <span class="font-medium text-slate-800 truncate">{{ $att->title }}</span>
                                    <span class="text-slate-400 text-[11px]">({{ $att->file_size }})</span>
                                </div>
                                <a href="{{ $att->url }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 font-semibold shrink-0">Buka ↗</a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Form Tambah Lampiran Baru --}}
            <div class="space-y-3">
                <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-2">
                    <p class="text-xs font-bold text-slate-700 uppercase tracking-wider">+ Tambah File Upload (PDF / ZIP)</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1">Judul File</label>
                            <input type="text" name="attachment_titles[]" placeholder="Contoh: Modul Rangkuman PDF"
                                   class="w-full px-3 py-2 border border-slate-300 rounded-lg text-xs bg-white focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1">Pilih File (Maks 10MB)</label>
                            <input type="file" name="attachment_files[]"
                                   class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-2">
                    <p class="text-xs font-bold text-slate-700 uppercase tracking-wider">+ Tambah Link Tautan Eksternal (GitHub / Drive)</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1">Judul Tautan</label>
                            <input type="text" name="external_titles[]" placeholder="Contoh: Source Code di GitHub"
                                   class="w-full px-3 py-2 border border-slate-300 rounded-lg text-xs bg-white focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1">URL Tautan</label>
                            <input type="url" name="external_urls[]" placeholder="https://github.com/..."
                                   class="w-full px-3 py-2 border border-slate-300 rounded-lg text-xs bg-white focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-all shadow-md shadow-indigo-100 cursor-pointer">
                Simpan Perubahan
            </button>
            <a href="{{ route('instructor.courses.edit', $course->id) }}" class="text-sm text-slate-600 px-4 py-2.5 rounded-xl hover:bg-slate-100 transition-colors">
                Batal
            </a>
        </div>
    </div>
</form>

{{-- Form Hapus Lampiran Satuan --}}
@if(isset($attachments) && $attachments->count() > 0)
    <div class="max-w-3xl mt-4 p-5 bg-white border border-slate-200 rounded-2xl">
        <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Hapus Lampiran Materi:</h3>
        <div class="space-y-2">
            @foreach($attachments as $att)
                <form method="POST" action="{{ route('instructor.lessons.attachment.destroy', $att->id) }}" onsubmit="return confirm('Hapus lampiran ini?')" class="flex items-center justify-between text-xs py-2 px-3 bg-slate-50 rounded-lg border border-slate-100">
                    @csrf @method('DELETE')
                    <span class="text-slate-700 font-medium">{{ $att->title }} ({{ $att->file_type }})</span>
                    <button type="submit" class="text-red-500 hover:text-red-700 font-semibold cursor-pointer">Hapus Lampiran</button>
                </form>
            @endforeach
        </div>
    </div>
@endif
@endsection
