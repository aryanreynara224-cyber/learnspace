@extends('layouts.dashboard')
@section('title', 'Tambah Materi')
@section('page-title', 'Tambah Materi — ' . $course->title)
@section('sidebar-nav')
    <a href="{{ route('instructor.courses.edit', $course->id) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-100 transition-all">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali ke Kursus
    </a>
@endsection

@section('content')
<form method="POST" action="{{ route('instructor.lessons.store', $course->id) }}" enctype="multipart/form-data">
    @csrf
    <div class="max-w-3xl space-y-6" x-data="{ lessonType: '{{ old('type', 'video') }}' }">
        
        {{-- Kartu Informasi Utama --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-5 shadow-sm">
            <h2 class="text-base font-bold text-slate-900 pb-2 border-b border-slate-100 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span>
                Informasi & Bab Materi
            </h2>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Judul Materi <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" required placeholder="Contoh: Pengenalan Sintaks & Struktur MVC"
                       class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Pilihan Bab / Section --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Pilih Bab / Modul Kurikulum</label>
                    <select name="section_id" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
                        <option value="">-- Tanpa Bab (Materi Umum) --</option>
                        @foreach($sections as $sec)
                            <option value="{{ $sec->id }}" {{ old('section_id') == $sec->id ? 'selected' : '' }}>
                                Bab {{ $sec->order }}: {{ $sec->title }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-slate-400 mt-1">Pilih bab agar materi masuk ke kelompok yang tepat.</p>
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
                <input type="text" name="description" value="{{ old('description') }}" placeholder="Gambaran umum apa yang akan dipelajari di materi ini"
                       class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Urutan Materi <span class="text-red-500">*</span></label>
                    <input type="number" name="order" value="{{ old('order', ($course->lessons()->max('order') ?? 0) + 1) }}" min="1" required
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Estimasi Durasi (menit)</label>
                    <input type="number" name="duration" value="{{ old('duration', 10) }}" min="1"
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

            {{-- Input Video (hanya jika tipe video) --}}
            <div x-show="lessonType === 'video'" x-cloak>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5 flex items-center justify-between">
                    <span>URL Video YouTube / Embed</span>
                    <span class="text-xs text-slate-400 font-normal">Contoh: https://www.youtube.com/embed/...</span>
                </label>
                <input type="url" name="video_url" value="{{ old('video_url') }}"
                       placeholder="https://www.youtube.com/embed/..."
                       class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @error('video_url')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Input Audio (hanya jika tipe audio) --}}
            <div x-show="lessonType === 'audio'" x-cloak>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5 flex items-center justify-between">
                    <span>URL Stream / File Audio / Podcast (MP3/WAV)</span>
                    <span class="text-xs text-slate-400 font-normal">Mendukung link file .mp3 atau podcast</span>
                </label>
                <input type="url" name="audio_url" value="{{ old('audio_url') }}"
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
                <textarea name="content" rows="9" placeholder="Tuliskan materi pembelajaran, rangkuman konsep, atau potongan kode di sini..."
                          class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none font-mono">{{ old('content') }}</textarea>
            </div>
        </div>

        {{-- Kartu Lampiran Unduhan --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-4 shadow-sm">
            <h2 class="text-base font-bold text-slate-900 pb-2 border-b border-slate-100 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
                Berkas & Lampiran Unduhan Siswa (Opsional)
            </h2>

            <p class="text-xs text-slate-500 leading-relaxed">
                Tambahkan file materi (slide modul PDF, starter pack zip, cheatsheet) atau tautan ke GitHub repo.
            </p>

            <div class="space-y-3">
                <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-2">
                    <p class="text-xs font-bold text-slate-700 uppercase tracking-wider">Unggah File (PDF / ZIP / DOCX)</p>
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
                    <p class="text-xs font-bold text-slate-700 uppercase tracking-wider">Atau Tautan Eksternal (GitHub / Drive)</p>
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
                Simpan Materi Baru
            </button>
            <a href="{{ route('instructor.courses.edit', $course->id) }}" class="text-sm text-slate-600 px-4 py-2.5 rounded-xl hover:bg-slate-100 transition-colors">
                Batal
            </a>
        </div>
    </div>
</form>
@endsection
