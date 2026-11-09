@extends('layouts.dashboard')
@section('title', $lesson->title)
@section('page-title', $lesson->title)
@section('page-subtitle', $course->title . ($lesson->section ? ' · ' . $lesson->section->title : ''))

@section('sidebar-nav')
<div x-data="{ sidebarTab: 'curriculum' }" class="space-y-3">
    <x-nav-item href="{{ route('student.courses.show', $course->slug) }}" :active="false"
        icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>'>
        Kembali ke Kursus
    </x-nav-item>

    {{-- Switcher Tab Sidebar --}}
    <div class="flex items-center p-1 bg-slate-100 rounded-xl text-xs font-semibold">
        <button type="button" @click="sidebarTab = 'curriculum'"
                :class="sidebarTab === 'curriculum' ? 'bg-white text-indigo-600 shadow-sm font-bold' : 'text-slate-500 hover:text-slate-800'"
                class="flex-1 py-2 px-2.5 rounded-lg text-center transition-all flex items-center justify-center gap-1.5 cursor-pointer">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
            <span>Silabus Bab</span>
        </button>
        <button type="button" @click="sidebarTab = 'notes'"
                :class="sidebarTab === 'notes' ? 'bg-white text-indigo-600 shadow-sm font-bold' : 'text-slate-500 hover:text-slate-800'"
                class="flex-1 py-2 px-2.5 rounded-lg text-center transition-all flex items-center justify-center gap-1.5 cursor-pointer">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            <span>Catatan Saya</span>
            @if(isset($userNotes) && $userNotes->count() > 0)
                <span class="w-4 h-4 rounded-full bg-indigo-100 text-indigo-700 text-[10px] font-bold flex items-center justify-center">{{ $userNotes->count() }}</span>
            @endif
        </button>
    </div>

    {{-- TAB 1: SILABUS BAB & DAFTAR MATERI --}}
    <div x-show="sidebarTab === 'curriculum'" class="space-y-4 pt-1">
        @if($course->sections && $course->sections->count() > 0)
            @foreach($course->sections as $sec)
                <div class="space-y-1">
                    <div class="px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider text-slate-400 flex items-center justify-between">
                        <span class="truncate">Bab {{ $sec->order }}: {{ $sec->title }}</span>
                        <span class="text-[10px] text-slate-400 font-normal">({{ $sec->lessons->count() }})</span>
                    </div>
                    @foreach($sec->lessons as $l)
                        @php
                            $isDone = in_array($l->id, $completedLessonIds);
                            $canAccess = ($isEnrolled ?? false) || $l->isFreePreview();
                            $isCurrent = $l->id === $lesson->id;
                        @endphp
                        @if($canAccess)
                        <a href="{{ route('student.lessons.show', $l->id) }}"
                           class="sidebar-item flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs transition-all {{ $isCurrent ? 'bg-indigo-600 text-white font-semibold shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                            <span class="w-5 h-5 rounded-md flex items-center justify-center shrink-0 text-[11px] font-bold {{ $isCurrent ? 'bg-white/20 text-white' : ($isDone ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-500') }}">
                                {{ $isDone ? '✓' : $l->order }}
                            </span>
                            <span class="truncate flex-1">{{ $l->title }}</span>
                            @if($l->type === 'audio')
                                <span class="text-[11px] {{ $isCurrent ? 'text-indigo-200' : 'text-slate-400' }}" title="Audio Podcast">🎧</span>
                            @elseif($l->type === 'article')
                                <span class="text-[11px] {{ $isCurrent ? 'text-indigo-200' : 'text-slate-400' }}" title="Artikel">📄</span>
                            @endif
                        </a>
                        @else
                        <div class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs text-slate-400 opacity-60">
                            <span class="w-5 h-5 rounded-md flex items-center justify-center shrink-0 text-[11px] font-bold bg-slate-100 text-slate-400">{{ $l->order }}</span>
                            <span class="truncate flex-1">{{ $l->title }}</span>
                            <svg class="w-3 h-3 text-slate-300 ml-auto shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        @endif
                    @endforeach
                </div>
            @endforeach

            {{-- Unassigned lessons --}}
            @php $unassigned = $course->lessons->whereNull('section_id'); @endphp
            @if($unassigned->count() > 0)
                <div class="space-y-1 pt-2 border-t border-slate-100">
                    <div class="px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider text-slate-400">Materi Tambahan</div>
                    @foreach($unassigned as $l)
                        @php
                            $isDone = in_array($l->id, $completedLessonIds);
                            $canAccess = ($isEnrolled ?? false) || $l->isFreePreview();
                            $isCurrent = $l->id === $lesson->id;
                        @endphp
                        @if($canAccess)
                        <a href="{{ route('student.lessons.show', $l->id) }}"
                           class="sidebar-item flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs transition-all {{ $isCurrent ? 'bg-indigo-600 text-white font-semibold' : 'text-slate-600 hover:bg-slate-100' }}">
                            <span class="w-5 h-5 rounded-md flex items-center justify-center shrink-0 text-[11px] font-bold {{ $isCurrent ? 'bg-white/20 text-white' : ($isDone ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-500') }}">
                                {{ $isDone ? '✓' : $l->order }}
                            </span>
                            <span class="truncate flex-1">{{ $l->title }}</span>
                        </a>
                        @endif
                    @endforeach
                </div>
            @endif
        @else
            {{-- Flat lesson list fallback --}}
            <div class="space-y-1">
                @foreach($course->lessons as $l)
                    @php
                        $isDone = in_array($l->id, $completedLessonIds);
                        $canAccess = ($isEnrolled ?? false) || $l->isFreePreview();
                        $isCurrent = $l->id === $lesson->id;
                    @endphp
                    @if($canAccess)
                    <a href="{{ route('student.lessons.show', $l->id) }}"
                       class="sidebar-item flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs transition-all {{ $isCurrent ? 'bg-indigo-600 text-white font-semibold' : 'text-slate-600 hover:bg-slate-100' }}">
                        <span class="w-5 h-5 rounded-md flex items-center justify-center shrink-0 text-[11px] font-bold {{ $isCurrent ? 'bg-white/20 text-white' : ($isDone ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-500') }}">
                            {{ $isDone ? '✓' : $l->order }}
                        </span>
                        <span class="truncate flex-1">{{ $l->title }}</span>
                    </a>
                    @else
                    <div class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs text-slate-400 opacity-60">
                        <span class="w-5 h-5 rounded-md flex items-center justify-center shrink-0 text-[11px] font-bold bg-slate-100 text-slate-400">{{ $l->order }}</span>
                        <span class="truncate flex-1">{{ $l->title }}</span>
                    </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>

    {{-- TAB 2: CATATAN SAYA DI SIDEBAR --}}
    <div x-show="sidebarTab === 'notes'" x-cloak class="space-y-3 pt-1">
        <div class="p-3 bg-indigo-50/70 border border-indigo-100 rounded-xl text-xs text-indigo-900 leading-relaxed">
            <p class="font-bold flex items-center gap-1.5 mb-1 text-indigo-950">
                <span>📝 Catatan Materi Ini</span>
            </p>
            <p class="text-[11px] text-slate-600">Catatan yang kamu tulis di bawah materi akan otomatis terlihat di sini.</p>
        </div>

        <div class="space-y-2">
            @forelse($userNotes as $nt)
                <div class="p-3 bg-white border border-slate-200/90 rounded-xl text-xs space-y-1 shadow-xs">
                    @if($nt->timestamp_seconds !== null)
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded border border-indigo-100">
                            ⏱ {{ $nt->formatted_timestamp }}
                        </span>
                    @endif
                    <p class="text-slate-700 leading-relaxed whitespace-pre-line text-[11px]">{{ $nt->content }}</p>
                    <p class="text-[10px] text-slate-400 pt-1 border-t border-slate-50">{{ $nt->created_at->format('d M, H:i') }}</p>
                </div>
            @empty
                <div class="py-8 text-center text-xs text-slate-400 space-y-1">
                    <p class="text-base">🗒️</p>
                    <p>Belum ada catatan.</p>
                    <p class="text-[11px] text-slate-400">Tulis di kolom catatan di bawah materi.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-4xl space-y-6">

    {{-- Lesson Header --}}
    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm p-6">
        <div class="flex flex-wrap items-center justify-between gap-3 mb-3">
            <div class="flex flex-wrap items-center gap-2">
                @if($lesson->section)
                    <span class="text-xs bg-slate-100 text-slate-700 font-bold px-3 py-1 rounded-full border border-slate-200">
                        Bab {{ $lesson->section->order }}: {{ $lesson->section->title }}
                    </span>
                @endif

                <span class="text-xs bg-indigo-100 text-indigo-700 font-bold px-3 py-1 rounded-full">
                    Materi {{ $lesson->order }}
                </span>

                @if($lesson->type === 'audio')
                    <span class="text-xs bg-amber-100 text-amber-900 font-bold px-3 py-1 rounded-full flex items-center gap-1 border border-amber-200">
                        🎧 Audio Podcast
                    </span>
                @elseif($lesson->type === 'article')
                    <span class="text-xs bg-blue-100 text-blue-900 font-bold px-3 py-1 rounded-full flex items-center gap-1 border border-blue-200">
                        📄 Bacaan & Modul
                    </span>
                @else
                    <span class="text-xs bg-purple-100 text-purple-900 font-bold px-3 py-1 rounded-full flex items-center gap-1 border border-purple-200">
                        🎬 Video Pelajaran
                    </span>
                @endif

                @if($isCompleted)
                    <span class="text-xs bg-emerald-100 text-emerald-800 font-bold px-3 py-1 rounded-full flex items-center gap-1 border border-emerald-200">
                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        Selesai
                    </span>
                @endif
            </div>

            @if($lesson->duration)
                <span class="text-xs bg-slate-50 text-slate-600 font-medium px-3 py-1 rounded-full border border-slate-200 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $lesson->duration }} menit
                </span>
            @endif
        </div>

        <h1 class="text-2xl font-black text-slate-900 leading-tight">{{ $lesson->title }}</h1>
        @if($lesson->description)
            <p class="text-slate-500 text-sm mt-2 leading-relaxed">{{ $lesson->description }}</p>
        @endif
    </div>

    {{-- Focus Mode Bar (Pomodoro Timer & Ambience) --}}
    <x-focus-room-bar :course="$course" :lesson="$lesson" />

    {{-- 1. PEMUTAR VIDEO --}}
    @if($lesson->hasVideo())
    <div class="bg-black rounded-2xl overflow-hidden shadow-xl aspect-video relative group border border-slate-900">
        <iframe id="lesson-video-frame" src="{{ $lesson->video_url }}" class="w-full h-full" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
    </div>
    @endif

    {{-- 2. PEMUTAR AUDIO / PODCAST INTERAKTIF & SEDERHANA --}}
    @if($lesson->hasAudio())
    <div class="bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 border border-indigo-800/50 rounded-2xl p-6 sm:p-7 text-white shadow-xl space-y-5"
         x-data="{
            playbackSpeed: 1,
            setSpeed(speed) {
                this.playbackSpeed = speed;
                const audioEl = document.getElementById('lesson-audio-element');
                if (audioEl) audioEl.playbackRate = speed;
            }
         }">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-gradient-to-tr from-amber-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-500/30 shrink-0 text-2xl">
                🎧
            </div>
            <div class="min-w-0">
                <span class="text-[10px] font-bold uppercase tracking-wider text-amber-400 bg-amber-950/70 border border-amber-500/30 px-2.5 py-0.5 rounded-full">
                    Audio / Podcast Belajar
                </span>
                <h3 class="text-base font-bold text-white mt-1.5 truncate">{{ $lesson->title }}</h3>
                <p class="text-xs text-slate-300">Dengarkan materi pembelajaran sambil beraktivitas</p>
            </div>
        </div>

        {{-- Audio Element Bawaan HTML5 (Stabil, bebas error & responsif) --}}
        <div class="bg-slate-800/80 p-3 rounded-xl border border-slate-700/60">
            <audio id="lesson-audio-element" controls class="w-full" style="filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));">
                <source src="{{ $lesson->audio_url }}" type="audio/mpeg">
                Browser Anda tidak mendukung pemutar audio HTML5.
            </audio>
        </div>

        {{-- Kontrol Kecepatan Putar (Playback Speed) & Download --}}
        <div class="flex flex-wrap items-center justify-between gap-3 pt-1 border-t border-indigo-900/40 text-xs">
            <div class="flex items-center gap-2">
                <span class="text-slate-400 font-medium">Kecepatan:</span>
                <div class="inline-flex rounded-lg bg-slate-800 p-0.5 border border-slate-700">
                    @foreach([1, 1.25, 1.5, 2] as $speed)
                        <button type="button" @click="setSpeed({{ $speed }})"
                                :class="playbackSpeed == {{ $speed }} ? 'bg-indigo-600 text-white font-bold' : 'text-slate-400 hover:text-white'"
                                class="px-2.5 py-1 rounded-md text-xs transition-colors cursor-pointer">
                            {{ $speed }}x
                        </button>
                    @endforeach
                </div>
            </div>

            <a href="{{ $lesson->audio_url }}" download target="_blank"
               class="inline-flex items-center gap-1.5 text-xs text-indigo-300 hover:text-white transition-colors bg-indigo-950/60 hover:bg-indigo-900 px-3 py-1.5 rounded-lg border border-indigo-700/40">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                <span>Unduh File Audio</span>
            </a>
        </div>
    </div>
    @endif

    {{-- 3. KONTEN PENJELASAN (MARKDOWN) --}}
    @if($lesson->content)
    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm p-6 sm:p-8">
        <div class="prose prose-slate max-w-none text-slate-700 leading-relaxed">
            {!! \Illuminate\Support\Str::markdown($lesson->content) !!}
        </div>
    </div>
    @endif

    {{-- 4. BERKAS & LAMPIRAN UNDUHAN (ATTACHMENTS) --}}
    @if($lesson->attachments && $lesson->attachments->count() > 0)
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                Berkas & Lampiran Unduhan ({{ $lesson->attachments->count() }})
            </h3>
            <span class="text-xs text-slate-400">Siap diunduh</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            @foreach($lesson->attachments as $att)
                <div class="flex items-center justify-between p-3.5 bg-slate-50 hover:bg-indigo-50/40 border border-slate-200 hover:border-indigo-200 rounded-xl transition-all group">
                    <div class="flex items-center gap-3 min-w-0 mr-2">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 {{ $att->is_external ? 'bg-blue-100 text-blue-700' : 'bg-emerald-100 text-emerald-700' }}">
                            @if($att->is_external)
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            @elseif($att->file_type === 'pdf')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            @else
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/></svg>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-slate-800 truncate group-hover:text-indigo-600 transition-colors">{{ $att->title }}</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">{{ $att->file_size }} · {{ strtoupper($att->file_type ?? 'BERKAS') }}</p>
                        </div>
                    </div>
                    <a href="{{ $att->url }}" target="_blank" download="{{ !$att->is_external }}"
                       class="shrink-0 bg-white hover:bg-indigo-600 hover:text-white text-slate-700 border border-slate-200 text-xs font-semibold px-3.5 py-2 rounded-xl transition-all shadow-xs flex items-center gap-1.5">
                        <span>{{ $att->is_external ? 'Buka Link ↗' : 'Unduh ↓' }}</span>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- 5. FITUR CATATAN PRIBADI SISWA (SEDERHANA & BEBAS ERROR) --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4"
         x-data="{
            noteContent: '',
            noteTime: '',
            notesList: {{ Js::from($userNotes->map(fn($n) => [
                'id'                  => $n->id,
                'content'             => $n->content,
                'timestamp_seconds'   => $n->timestamp_seconds,
                'formatted_timestamp' => $n->formatted_timestamp,
                'created_at'          => $n->created_at->format('d M Y, H:i')
            ])) }},
            isSaving: false,
            successMessage: '',
            errorMessage: '',
            saveNote() {
                if (!this.noteContent.trim()) return;
                this.isSaving = true;
                this.successMessage = '';
                this.errorMessage = '';

                fetch('{{ route('student.lessons.notes.store', $lesson->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        content: this.noteContent,
                        timestamp: this.noteTime.trim() || null
                    })
                })
                .then(res => {
                    if (!res.ok) throw new Error('Terjadi kesalahan.');
                    return res.json();
                })
                .then(data => {
                    this.isSaving = false;
                    if (data.status === 'success') {
                        this.notesList.unshift(data.note);
                        this.noteContent = '';
                        this.noteTime = '';
                        this.successMessage = '✓ Catatan berhasil disimpan!';
                        setTimeout(() => { this.successMessage = ''; }, 3000);
                    } else {
                        this.errorMessage = data.message || 'Gagal menyimpan catatan.';
                    }
                })
                .catch(err => {
                    this.isSaving = false;
                    this.errorMessage = 'Gagal menyimpan catatan. Silakan coba lagi.';
                });
            },
            deleteNote(id) {
                if (!confirm('Hapus catatan ini?')) return;

                fetch('/student/notes/' + id, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        this.notesList = this.notesList.filter(n => n.id !== id);
                    }
                })
                .catch(() => {
                    alert('Gagal menghapus catatan.');
                });
            }
         }">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Catatan Belajar Pribadi
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Tulis ringkasan atau hal penting dari materi ini untuk kamu pelajari kembali nanti.</p>
            </div>
            <span class="text-xs font-semibold px-2.5 py-1 bg-indigo-50 text-indigo-700 rounded-full" x-text="notesList.length + ' catatan tersimpan'"></span>
        </div>

        {{-- Notifikasi Sukses / Error --}}
        <div x-show="successMessage" x-cloak class="p-2.5 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs rounded-xl font-medium" x-text="successMessage"></div>
        <div x-show="errorMessage" x-cloak class="p-2.5 bg-red-50 border border-red-200 text-red-800 text-xs rounded-xl font-medium" x-text="errorMessage"></div>

        {{-- Form Tulis Catatan Baru --}}
        <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-3">
            <textarea x-model="noteContent" rows="3" placeholder="Tulis catatan atau poin penting materi di sini..."
                      class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-xs bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none"></textarea>
            
            <div class="flex flex-wrap items-center justify-between gap-3 pt-1">
                <div class="flex items-center gap-2 text-xs text-slate-600">
                    <span class="font-medium">Menit/Detik (opsional):</span>
                    <input type="text" x-model="noteTime" placeholder="Contoh: 01:30"
                           class="w-32 px-3 py-1.5 border border-slate-300 rounded-lg text-xs bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <button type="button" @click="saveNote()" :disabled="isSaving || !noteContent.trim()"
                        class="bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white text-xs font-bold px-4 py-2 rounded-xl transition-all shadow-sm cursor-pointer flex items-center gap-1.5">
                    <span x-text="isSaving ? 'Menyimpan...' : 'Simpan Catatan'"></span>
                </button>
            </div>
        </div>

        {{-- Daftar Catatan yang Tersimpan --}}
        <div class="space-y-2 max-h-80 overflow-y-auto">
            <template x-for="item in notesList" :key="item.id">
                <div class="p-3.5 bg-white border border-slate-200 hover:border-slate-300 rounded-xl text-xs space-y-1.5 transition-all">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <template x-if="item.formatted_timestamp">
                                <span class="bg-indigo-50 text-indigo-700 font-bold px-2 py-0.5 rounded text-[11px] border border-indigo-100" x-text="'⏱ ' + item.formatted_timestamp"></span>
                            </template>
                            <span class="text-[11px] text-slate-400" x-text="item.created_at"></span>
                        </div>
                        <button type="button" @click="deleteNote(item.id)" class="text-[11px] text-red-500 hover:text-red-700 font-semibold cursor-pointer">
                            Hapus
                        </button>
                    </div>
                    <p class="text-slate-800 leading-relaxed whitespace-pre-line text-xs" x-text="item.content"></p>
                </div>
            </template>

            <template x-if="notesList.length === 0">
                <div class="py-6 text-center text-xs text-slate-400">
                    Belum ada catatan untuk materi ini. Tulis catatan pertamamu di atas!
                </div>
            </template>
        </div>
    </div>

    {{-- Interactive Live Code Playground --}}
    <x-code-playground :course="$course" :lesson="$lesson" />

    {{-- Navigation Bar --}}
    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm p-4 flex items-center justify-between gap-4">
        <div class="min-w-0">
            @if($prevLesson)
            <a href="{{ route('student.lessons.show', $prevLesson->id) }}"
               class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span class="hidden sm:inline truncate max-w-xs">{{ $prevLesson->title }}</span>
                <span class="sm:hidden">Sebelumnya</span>
            </a>
            @endif
        </div>

        <div class="flex items-center gap-3 shrink-0">
            @if(!$isCompleted)
                <form method="POST" action="{{ route('student.lessons.complete', $lesson->id) }}">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center gap-2 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition-all hover:shadow-lg hover:shadow-emerald-200 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        Tandai Selesai
                    </button>
                </form>
            @else
                <span class="inline-flex items-center gap-1.5 text-sm font-bold text-emerald-600 bg-emerald-50 px-4 py-2.5 rounded-xl border border-emerald-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    Sudah Selesai
                </span>
            @endif

            @if($nextLesson)
            <a href="{{ route('student.lessons.show', $nextLesson->id) }}"
               class="inline-flex items-center gap-2 text-sm font-bold text-indigo-600 hover:text-indigo-800 transition-colors">
                <span class="hidden sm:inline truncate max-w-xs">{{ $nextLesson->title }}</span>
                <span class="sm:hidden">Berikutnya</span>
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
            @endif
        </div>
    </div>
</div>

{{-- Prism.js untuk Code Highlight --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css" integrity="sha512-vswe+cgvic/XBoF1OcM/31QIitRLBnoOH68GmpKKZTrIE2bfHBqltPNjUrPmDAODVMmAKWiG9XM16enNoMa3Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
    .prose pre {
        background-color: #111d32 !important;
        border-radius: 0.85rem;
        padding: 1.15rem;
        position: relative;
        overflow-x: auto;
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.25);
    }
    .prose code {
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        font-size: 0.88rem;
    }
    .prose pre,
    .prose pre code,
    .prose pre code[class*="language-"] {
        color: #f4f8ff !important;
        text-shadow: none !important;
        font-size: 0.92rem;
        line-height: 1.8;
        tab-size: 4;
    }
    .prose pre .token.comment,
    .prose pre .token.prolog,
    .prose pre .token.doctype,
    .prose pre .token.cdata {
        color: #b8c8e2 !important;
        font-style: italic;
    }
    .prose pre .token.keyword,
    .prose pre .token.selector,
    .prose pre .token.important,
    .prose pre .token.atrule {
        color: #ffbd91 !important;
    }
    .prose pre .token.string,
    .prose pre .token.attr-value,
    .prose pre .token.inserted {
        color: #a7f3c4 !important;
    }
    .prose pre .token.function,
    .prose pre .token.class-name {
        color: #9ac8ff !important;
    }
    .prose pre .token.number,
    .prose pre .token.boolean,
    .prose pre .token.constant {
        color: #ffdc8a !important;
    }
    .prose pre .token.operator,
    .prose pre .token.punctuation {
        color: #e2eaf8 !important;
    }
    .prose :not(pre) > code {
        background-color: #eef2ff;
        color: #4338ca;
        padding: 0.2rem 0.45rem;
        border-radius: 0.375rem;
        font-weight: 600;
        border: 1px solid #e0e7ff;
    }
    .prose h1, .prose h2, .prose h3 {
        color: #0f172a;
        font-weight: 800;
        letter-spacing: -0.02em;
    }
    .prose h2 {
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 0.5rem;
        margin-top: 2rem;
        margin-bottom: 1rem;
    }
    .prose p {
        margin-bottom: 1.25rem;
        line-height: 1.8;
    }
    .prose ul, .prose ol {
        margin-bottom: 1.25rem;
        padding-left: 1.5rem;
    }
    .prose li {
        margin-bottom: 0.5rem;
        line-height: 1.7;
    }
    .prose blockquote {
        border-left: 4px solid #6366f1;
        background-color: #f8fafc;
        padding: 0.85rem 1.25rem;
        border-radius: 0 0.75rem 0.75rem 0;
        color: #475569;
        font-style: italic;
        margin: 1.5rem 0;
    }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/autoloader/prism-autoloader.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.prose pre').forEach(function(pre) {
            var btn = document.createElement('button');
            btn.className = 'absolute top-3 right-3 text-[11px] font-semibold bg-slate-700/90 hover:bg-slate-600 text-white hover:text-white px-2.5 py-1 rounded-md transition-all border border-white/10 flex items-center gap-1';
            btn.innerHTML = '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg> Salin';
            btn.onclick = function() {
                var code = pre.querySelector('code');
                var text = code ? code.innerText : pre.innerText;
                navigator.clipboard.writeText(text).then(function() {
                    btn.innerHTML = '✓ Tersalin!';
                    btn.classList.add('text-emerald-400');
                    setTimeout(function() {
                        btn.innerHTML = '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg> Salin';
                        btn.classList.remove('text-emerald-400');
                    }, 2000);
                });
            };
            pre.appendChild(btn);
        });
    });
</script>
@endsection
