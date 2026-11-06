@extends('layouts.dashboard')
@section('title', $course->title)
@section('page-title', $course->title)
@section('page-subtitle', $course->instructor->name . ' · ' . $course->category->name)

@section('sidebar-nav')
<x-nav-item href="{{ route('student.dashboard') }}" :active="false"
    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>'>
    Dashboard
</x-nav-item>
<div class="mt-4 mb-1 px-3"><p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Belajar</p></div>
<x-nav-item href="{{ route('student.courses.index') }}" :active="true"
    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>'>
    Kursus Saya
</x-nav-item>
<x-nav-item href="{{ route('courses.index') }}" :active="false"
    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>'>
    Jelajahi Kursus
</x-nav-item>
@endsection

@section('content')
<div class="mb-5">
    <a href="{{ route('student.courses.index') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-indigo-600 transition-colors font-medium">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali ke Kursus Saya
    </a>
</div>

<div class="max-w-4xl space-y-6">

    {{-- Progress Card --}}
    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-sm font-semibold text-slate-700 mb-0.5">Progress Belajar Anda</p>
                <p class="text-xs text-slate-400">{{ count($completedLessonIds) }} dari {{ $course->lessons->count() }} materi telah diselesaikan</p>
            </div>
            <span class="text-3xl font-extrabold {{ $progress >= 100 ? 'text-emerald-600' : 'text-indigo-600' }}">{{ $progress }}%</span>
        </div>
        <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
            <div class="h-full rounded-full transition-all duration-700 {{ $progress >= 100 ? 'bg-gradient-to-r from-emerald-400 to-emerald-500' : 'bg-gradient-to-r from-indigo-500 to-violet-600' }}"
                 style="width: {{ $progress }}%"></div>
        </div>
        @if($progress >= 100)
            <div class="mt-5 bg-gradient-to-r from-amber-500/10 via-amber-400/15 to-yellow-500/10 border border-amber-300 rounded-2xl p-5 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 bg-gradient-to-tr from-amber-500 to-yellow-300 rounded-2xl flex items-center justify-center shrink-0 shadow-md">
                        <svg class="w-6 h-6 text-slate-950 font-bold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-amber-950 flex items-center gap-2">
                            <span>Sertifikat Kelulusan Siap Diterbitkan!</span>
                            <span class="text-[10px] bg-amber-500 text-slate-950 font-black px-2 py-0.5 rounded-full uppercase tracking-wider">Resmi</span>
                        </h3>
                        <p class="text-xs text-amber-800/90 mt-0.5">Selamat! Anda telah menyelesaikan seluruh materi. Klaim dan cetak sertifikat resmi Anda sekarang.</p>
                    </div>
                </div>
                <a href="{{ route('student.courses.certificate', $course->slug) }}"
                   target="_blank"
                   class="shrink-0 inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-xs text-slate-950 bg-gradient-to-r from-amber-400 via-amber-300 to-yellow-400 hover:from-amber-300 hover:to-yellow-300 shadow-md shadow-amber-500/20 hover:scale-[1.02] transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Lihat & Cetak Sertifikat
                </a>
            </div>
        @endif
    </div>

    {{-- Struktur Kurikulum Bab & Materi --}}
    <div class="space-y-5">
        <div class="flex items-center justify-between pb-1">
            <h2 class="font-extrabold text-slate-900 text-lg flex items-center gap-2.5">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                Silabus Bab & Modul Pembelajaran
            </h2>
            <span class="text-xs text-slate-500 bg-slate-100 border border-slate-200 px-3 py-1 rounded-full font-semibold">
                {{ $course->lessons->count() }} total materi
            </span>
        </div>

        @if($course->sections && $course->sections->count() > 0)
            @foreach($course->sections as $sec)
                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                    {{-- Header Bab --}}
                    <div class="px-6 py-4 bg-slate-50/80 border-b border-slate-200/80 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 bg-indigo-600 text-white rounded-xl flex items-center justify-center text-xs font-black shrink-0 shadow-xs">
                                {{ $sec->order }}
                            </span>
                            <div>
                                <h3 class="font-bold text-sm text-slate-900">Bab {{ $sec->order }}: {{ $sec->title }}</h3>
                                @if($sec->description)
                                    <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">{{ $sec->description }}</p>
                                @endif
                            </div>
                        </div>
                        <span class="text-xs text-slate-400 font-medium shrink-0">{{ $sec->lessons->count() }} materi</span>
                    </div>

                    {{-- Lessons inside Chapter --}}
                    <div class="divide-y divide-slate-100">
                        @forelse($sec->lessons as $lesson)
                            @php $isCompleted = in_array($lesson->id, $completedLessonIds); @endphp
                            <div class="flex items-center gap-4 px-6 py-4 hover:bg-slate-50 transition-colors group">
                                <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 transition-colors {{ $isCompleted ? 'bg-emerald-100 text-emerald-600 font-bold' : 'bg-slate-100 text-slate-500 group-hover:bg-indigo-50 group-hover:text-indigo-600' }}">
                                    @if($isCompleted)
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    @else
                                        <span class="text-xs font-bold">{{ $lesson->order }}</span>
                                    @endif
                                </div>
                                
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-wrap items-center gap-2 mb-0.5">
                                        <p class="font-semibold text-sm text-slate-800 {{ $isCompleted ? 'text-slate-400 line-through' : '' }} truncate">
                                            {{ $lesson->title }}
                                        </p>

                                        {{-- Badge Indikator Tipe Materi --}}
                                        @if($lesson->type === 'audio')
                                            <span class="text-[11px] bg-amber-50 text-amber-800 font-bold px-2 py-0.5 rounded-full border border-amber-200 shrink-0 flex items-center gap-1">
                                                🎧 Audio
                                            </span>
                                        @elseif($lesson->type === 'article')
                                            <span class="text-[11px] bg-blue-50 text-blue-800 font-bold px-2 py-0.5 rounded-full border border-blue-200 shrink-0 flex items-center gap-1">
                                                📄 Bacaan
                                            </span>
                                        @else
                                            <span class="text-[11px] bg-indigo-50 text-indigo-700 font-bold px-2 py-0.5 rounded-full border border-indigo-200 shrink-0 flex items-center gap-1">
                                                🎬 Video
                                            </span>
                                        @endif

                                        {{-- Badge Lampiran Berkas --}}
                                        @if($lesson->attachments && $lesson->attachments->count() > 0)
                                            <span class="text-[11px] bg-emerald-50 text-emerald-700 font-semibold px-2 py-0.5 rounded-full border border-emerald-200 shrink-0 flex items-center gap-1">
                                                📎 {{ $lesson->attachments->count() }} berkas
                                            </span>
                                        @endif
                                    </div>

                                    @if($lesson->duration)
                                        <p class="text-xs text-slate-400 mt-1 flex items-center gap-1 font-medium">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            {{ $lesson->duration }} menit
                                        </p>
                                    @endif
                                </div>

                                <a href="{{ route('student.lessons.show', $lesson->id) }}"
                                   class="text-xs font-bold px-4 py-2 rounded-xl transition-all shrink-0 {{ $isCompleted ? 'bg-slate-100 text-slate-600 hover:bg-slate-200' : 'bg-indigo-600 text-white hover:bg-indigo-700 hover:shadow-md hover:shadow-indigo-200' }}">
                                    {{ $isCompleted ? '↩ Ulangi' : '→ Belajar' }}
                                </a>
                            </div>
                        @empty
                            <div class="px-6 py-4 text-xs text-slate-400 text-center">Belum ada materi di bab ini.</div>
                        @endforelse
                    </div>
                </div>
            @endforeach

            {{-- Unassigned lessons --}}
            @php $unassigned = $course->lessons->whereNull('section_id'); @endphp
            @if($unassigned->count() > 0)
                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-3.5 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="font-bold text-xs text-slate-600 uppercase tracking-wider">Materi Tambahan</h3>
                        <span class="text-xs text-slate-400">{{ $unassigned->count() }} materi</span>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @foreach($unassigned as $lesson)
                            @php $isCompleted = in_array($lesson->id, $completedLessonIds); @endphp
                            <div class="flex items-center gap-4 px-6 py-4 hover:bg-slate-50 transition-colors group">
                                <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 transition-colors {{ $isCompleted ? 'bg-emerald-100 text-emerald-600 font-bold' : 'bg-slate-100 text-slate-500' }}">
                                    @if($isCompleted)
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    @else
                                        <span class="text-xs font-bold">{{ $lesson->order }}</span>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-sm text-slate-800 {{ $isCompleted ? 'text-slate-400 line-through' : '' }} truncate">{{ $lesson->title }}</p>
                                </div>
                                <a href="{{ route('student.lessons.show', $lesson->id) }}"
                                   class="text-xs font-bold px-4 py-2 rounded-xl transition-all shrink-0 {{ $isCompleted ? 'bg-slate-100 text-slate-600 hover:bg-slate-200' : 'bg-indigo-600 text-white hover:bg-indigo-700' }}">
                                    {{ $isCompleted ? '↩ Ulangi' : '→ Belajar' }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @else
            {{-- Default flat list --}}
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="divide-y divide-slate-100">
                    @forelse($course->lessons as $lesson)
                    @php $isCompleted = in_array($lesson->id, $completedLessonIds); @endphp
                    <div class="flex items-center gap-4 px-6 py-4 hover:bg-slate-50 transition-colors group">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 transition-colors {{ $isCompleted ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-500' }}">
                            @if($isCompleted)
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            @else
                                <span class="text-xs font-bold">{{ $lesson->order }}</span>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-sm text-slate-800 {{ $isCompleted ? 'line-through text-slate-400' : '' }}">{{ $lesson->title }}</p>
                            @if($lesson->duration)
                                <p class="text-xs text-slate-400 mt-0.5 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $lesson->duration }} menit
                                </p>
                            @endif
                        </div>
                        <a href="{{ route('student.lessons.show', $lesson->id) }}"
                           class="text-xs font-bold px-4 py-2 rounded-xl transition-all shrink-0 {{ $isCompleted ? 'bg-slate-100 text-slate-600 hover:bg-slate-200' : 'bg-indigo-600 text-white hover:bg-indigo-700' }}">
                            {{ $isCompleted ? '↩ Ulangi' : '→ Belajar' }}
                        </a>
                    </div>
                    @empty
                    <div class="px-6 py-12 text-center text-slate-400">
                        <p class="text-sm">Belum ada materi di kursus ini.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
