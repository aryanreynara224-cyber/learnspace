@extends('layouts.app')
@section('title', $course->title)
@section('meta_description', Str::limit($course->description, 160))

@section('content')
<div class="min-h-screen bg-slate-50/70 pb-16">

    {{-- Breadcrumb Navigation --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-4">
        <nav class="flex items-center gap-2 text-xs sm:text-sm font-medium text-slate-500 flex-wrap">
            <a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span>Home</span>
            </a>
            <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <a href="{{ route('courses.index') }}" class="hover:text-indigo-600 transition-colors">Katalog Kursus</a>
            <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-indigo-600 bg-indigo-50 border border-indigo-100/80 px-2.5 py-0.5 rounded-full text-xs font-semibold truncate max-w-xs">{{ $course->category->name }}</span>
            <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-slate-800 font-semibold truncate max-w-sm">{{ $course->title }}</span>
        </nav>
    </div>

    {{-- Main Container --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            {{-- ==================== MAIN COLUMN (8 COLS) ==================== --}}
            <div class="lg:col-span-8 space-y-8">

                {{-- LUXURY HERO HEADER --}}
                <div class="relative overflow-hidden rounded-3xl p-7 sm:p-10 shadow-2xl shadow-slate-950/30 border border-slate-800 text-white"
                     style="background: linear-gradient(135deg, #090d16 0%, #0f172a 45%, #1e1b4b 100%) !important; background-color: #0f172a !important; color: #ffffff !important;">
                    {{-- Ambient Background Glows --}}
                    <div class="absolute -top-24 -right-24 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="absolute -bottom-24 -left-24 w-80 h-80 bg-violet-600/15 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="absolute top-1/2 left-1/3 w-64 h-64 bg-cyan-500/10 rounded-full blur-2xl pointer-events-none"></div>

                    <div class="relative z-10">
                        {{-- Badges Row --}}
                        <div class="flex flex-wrap items-center gap-2.5 mb-5">
                            <span class="inline-flex items-center gap-1.5 text-xs font-extrabold uppercase tracking-wider bg-indigo-500/20 text-indigo-300 border border-indigo-400/30 px-3.5 py-1 rounded-full backdrop-blur-md">
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 animate-pulse"></span>
                                {{ $course->category->name }}
                            </span>

                            <span class="inline-flex items-center text-xs font-bold px-3 py-1 rounded-full backdrop-blur-md
                                @if($course->level === 'beginner') bg-emerald-500/20 text-emerald-300 border border-emerald-400/30
                                @elseif($course->level === 'intermediate') bg-amber-500/20 text-amber-300 border border-amber-400/30
                                @else bg-rose-500/20 text-rose-300 border border-rose-400/30 @endif">
                                {{ $course->level_label }}
                            </span>

                            <span class="inline-flex items-center gap-1 text-xs font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 px-3 py-1 rounded-full">
                                <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                Min. 3 Modul Gratis
                            </span>
                        </div>

                        {{-- Title --}}
                        <h1 class="text-2xl sm:text-4xl font-extrabold leading-tight tracking-tight mb-4" style="color: #ffffff !important;">
                            {{ $course->title }}
                        </h1>

                        {{-- Short Description --}}
                        <p class="text-sm sm:text-base leading-relaxed max-w-2xl font-normal" style="color: #cbd5e1 !important;">
                            {{ $course->description }}
                        </p>

                        {{-- Rating & Highlights --}}
                        <div class="flex flex-wrap items-center gap-4 sm:gap-6 mt-6 pt-6 border-t border-slate-800/80 text-xs sm:text-sm">
                            <div class="flex items-center gap-1.5 text-amber-400">
                                <div class="flex text-amber-400">
                                    @for($i=0; $i<5; $i++)
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endfor
                                </div>
                                <span class="font-bold text-white text-sm">4.9</span>
                                <span class="text-slate-400 text-xs">(1.420+ ulasan)</span>
                            </div>

                            <div class="flex items-center gap-2 text-slate-300">
                                <div class="w-6 h-6 rounded-full overflow-hidden ring-2 ring-indigo-400/40 shrink-0">
                                    <img src="{{ $course->instructor->avatar_url ?? asset('images/avatars/instructor1.jpg') }}" alt="{{ $course->instructor->name }}" class="w-full h-full object-cover">
                                </div>
                                <span class="text-slate-400">Oleh:</span>
                                <span class="font-semibold text-white">{{ $course->instructor->name }}</span>
                            </div>

                            <div class="flex items-center gap-1.5 text-slate-400 ml-auto hidden sm:flex">
                                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                <span>Kurikulum Terverifikasi</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KEY OUTCOMES (YANG AKAN ANDA PELAJARI) --}}
                <div class="bg-white rounded-3xl p-7 sm:p-8 border border-slate-200/80 shadow-sm relative overflow-hidden">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-slate-900">Yang Akan Anda Kuasai di Kursus Ini</h2>
                            <p class="text-xs text-slate-500">Kompetensi praktis siap kerja dengan standar industri terkini</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach([
                            'Memahami konsep fundamental dan logika pemrograman secara mendalam',
                            'Menerapkan arsitektur kode yang bersih (clean code) dan mudah dipelihara',
                            'Menyelesaikan studi kasus nyata dan membangun portofolio proyek fungsional',
                            'Mempelajari teknik debugging dan pemecahan masalah (troubleshooting) efektif',
                            'Mengintegrasikan fitur modern dengan standar keamanan dan performa tinggi',
                            'Siap melanjutkan karier sebagai pengembang perangkat lunak profesional'
                        ] as $item)
                        <div class="flex items-start gap-3 p-3.5 rounded-2xl bg-slate-50/70 border border-slate-100 hover:border-indigo-200 transition-colors">
                            <div class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="text-xs sm:text-sm font-medium text-slate-700 leading-snug">{{ $item }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- DAFTAR MATERI PEMBELAJARAN (SILABUS) --}}
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden" id="daftar-materi">
                    <div class="p-6 sm:p-7 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2.5">
                                <span class="w-3 h-3 rounded-full bg-emerald-500 animate-ping"></span>
                                <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight">Silabus & Daftar Modul</h2>
                            </div>
                            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                                Total <strong>{{ $course->lessons->count() }} modul</strong> terstruktur dari nol hingga mahir
                            </p>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <span class="text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-200/80 px-3 py-1.5 rounded-xl flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                                Minimal 3 Modul Bebas Dibuka
                            </span>
                        </div>
                    </div>

                    {{-- Banner Highlight Pratinjau Terbuka --}}
                    <div class="bg-gradient-to-r from-emerald-600 via-teal-600 to-indigo-700 text-white px-6 py-3.5 flex items-center justify-between gap-4 shadow-inner">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <p class="text-xs sm:text-sm leading-snug">
                                <strong class="font-bold">Pratinjau Gratis Aktif:</strong> Silakan klik tombol <strong>"Buka Modul →"</strong> di materi 1, 2, dan 3 untuk langsung membaca materi pembelajaran tanpa harus registrasi!
                            </p>
                        </div>
                    </div>

                    {{-- Modul List (Grouped by Section if available) --}}
                    @if($course->lessons->isNotEmpty())
                        @if($course->sections && $course->sections->count() > 0)
                            <div class="divide-y divide-slate-100">
                                @foreach($course->sections as $sec)
                                    <div class="bg-slate-50/70">
                                        {{-- Header Bab --}}
                                        <div class="px-6 py-4 bg-slate-100/70 border-b border-slate-200 flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <span class="w-7 h-7 bg-indigo-600 text-white rounded-lg flex items-center justify-center text-xs font-black shrink-0">
                                                    {{ $sec->order }}
                                                </span>
                                                <div>
                                                    <h3 class="font-bold text-sm text-slate-900">Bab {{ $sec->order }}: {{ $sec->title }}</h3>
                                                    @if($sec->description)
                                                        <p class="text-xs text-slate-500 mt-0.5">{{ $sec->description }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <span class="text-xs text-slate-400 font-medium">{{ $sec->lessons->count() }} materi</span>
                                        </div>

                                        {{-- Lessons under Section --}}
                                        <div class="divide-y divide-slate-100 bg-white">
                                            @foreach($sec->lessons as $lesson)
                                                @php $canOpen = $isEnrolled || $lesson->isFreePreview(); @endphp
                                                <div class="p-5 sm:p-6 transition-all duration-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4 {{ $canOpen ? 'hover:bg-slate-50/80 bg-white' : 'bg-slate-50/40 opacity-80' }}">
                                                    <div class="flex items-start gap-4 flex-1 min-w-0">
                                                        <div class="w-9 h-9 rounded-xl flex items-center justify-center font-extrabold text-xs shrink-0 {{ $canOpen ? 'bg-gradient-to-br from-indigo-500 to-violet-600 text-white shadow-sm' : 'bg-slate-200 text-slate-500' }}">
                                                            {{ $lesson->order }}
                                                        </div>
                                                        <div class="min-w-0 flex-1">
                                                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                                                <h4 class="text-sm sm:text-base font-bold text-slate-900 leading-snug">{{ $lesson->title }}</h4>
                                                                @if($lesson->type === 'audio')
                                                                    <span class="text-[10px] bg-amber-50 text-amber-700 font-bold px-2 py-0.5 rounded border border-amber-200 shrink-0">🎧 Audio</span>
                                                                @elseif($lesson->type === 'article')
                                                                    <span class="text-[10px] bg-blue-50 text-blue-700 font-bold px-2 py-0.5 rounded border border-blue-200 shrink-0">📄 Bacaan</span>
                                                                @endif
                                                                @if($lesson->attachments && $lesson->attachments->count() > 0)
                                                                    <span class="text-[10px] bg-emerald-50 text-emerald-700 font-semibold px-2 py-0.5 rounded border border-emerald-200 shrink-0">📎 {{ $lesson->attachments->count() }} berkas</span>
                                                                @endif
                                                                @if($lesson->isFreePreview() && !$isEnrolled)
                                                                    <span class="inline-flex items-center gap-1 text-[11px] font-extrabold text-emerald-700 bg-emerald-100/80 border border-emerald-300 px-2.5 py-0.5 rounded-full shadow-xs">
                                                                        Bisa Dibuka (Gratis)
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            @if($lesson->description)
                                                                <p class="text-xs sm:text-sm text-slate-500 line-clamp-2 leading-relaxed">{{ $lesson->description }}</p>
                                                            @endif
                                                            @if($lesson->duration)
                                                                <p class="text-xs text-slate-400 mt-1.5 flex items-center gap-1">
                                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                                    {{ $lesson->duration }} menit
                                                                </p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="flex items-center justify-end shrink-0 pl-14 sm:pl-0">
                                                        @if($canOpen)
                                                            <a href="{{ route('courses.lessons.show', [$course->slug, $lesson->id]) }}"
                                                               class="inline-flex items-center gap-2 text-xs sm:text-sm font-extrabold text-white bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 px-5 py-2.5 rounded-2xl transition-all shadow-md shadow-indigo-500/20">
                                                                <span>Buka Modul</span>
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                                            </a>
                                                        @else
                                                            <a href="#cta-daftar" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-400 hover:text-indigo-600 transition-colors py-2 px-3 rounded-xl bg-slate-100 hover:bg-slate-200/80">
                                                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                                                <span>Daftar untuk Membuka</span>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach

                                {{-- Unassigned Lessons --}}
                                @php $unassigned = $course->lessons->whereNull('section_id'); @endphp
                                @if($unassigned->count() > 0)
                                    <div class="bg-slate-50/70">
                                        <div class="px-6 py-3.5 bg-slate-100 border-b border-slate-200">
                                            <h3 class="font-bold text-xs text-slate-600 uppercase tracking-wider">Materi Tambahan</h3>
                                        </div>
                                        <div class="divide-y divide-slate-100 bg-white">
                                            @foreach($unassigned as $lesson)
                                                @php $canOpen = $isEnrolled || $lesson->isFreePreview(); @endphp
                                                <div class="p-5 sm:p-6 transition-all duration-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4 {{ $canOpen ? 'hover:bg-slate-50/80 bg-white' : 'bg-slate-50/40 opacity-80' }}">
                                                    <div class="flex items-start gap-4 flex-1 min-w-0">
                                                        <div class="w-9 h-9 rounded-xl flex items-center justify-center font-extrabold text-xs shrink-0 {{ $canOpen ? 'bg-gradient-to-br from-indigo-500 to-violet-600 text-white shadow-sm' : 'bg-slate-200 text-slate-500' }}">
                                                            {{ $lesson->order }}
                                                        </div>
                                                        <div class="min-w-0 flex-1">
                                                            <h4 class="text-sm sm:text-base font-bold text-slate-900 leading-snug">{{ $lesson->title }}</h4>
                                                        </div>
                                                    </div>
                                                    <div class="flex items-center justify-end shrink-0 pl-14 sm:pl-0">
                                                        @if($canOpen)
                                                            <a href="{{ route('courses.lessons.show', [$course->slug, $lesson->id]) }}" class="inline-flex items-center gap-2 text-xs sm:text-sm font-extrabold text-white bg-indigo-600 px-5 py-2.5 rounded-2xl">Buka Modul</a>
                                                        @else
                                                            <span class="text-xs text-slate-400">Terkunci</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="divide-y divide-slate-100">
                                @foreach($course->lessons as $lesson)
                                @php
                                    $canOpen = $isEnrolled || $lesson->isFreePreview();
                                @endphp
                                <div class="p-5 sm:p-6 transition-all duration-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4
                                    {{ $canOpen ? 'hover:bg-slate-50/80 bg-white' : 'bg-slate-50/40 opacity-80' }}">

                                    <div class="flex items-start gap-4 flex-1 min-w-0">
                                        {{-- Order Badge --}}
                                        <div class="w-10 h-10 rounded-2xl flex items-center justify-center font-extrabold text-sm shrink-0 transition-all duration-300
                                            {{ $canOpen ? 'bg-gradient-to-br from-indigo-500 to-violet-600 text-white shadow-md shadow-indigo-200' : 'bg-slate-200/80 text-slate-500' }}">
                                            {{ $lesson->order }}
                                        </div>

                                        <div class="min-w-0 flex-1">
                                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                                <h3 class="text-sm sm:text-base font-bold text-slate-900 leading-snug">
                                                    {{ $lesson->title }}
                                                </h3>

                                                @if($lesson->isFreePreview() && !$isEnrolled)
                                                    <span class="inline-flex items-center gap-1 text-[11px] font-extrabold text-emerald-700 bg-emerald-100/80 border border-emerald-300 px-2.5 py-0.5 rounded-full shadow-xs">
                                                        <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                                                        Bisa Dibuka (Gratis)
                                                    </span>
                                                @elseif($isEnrolled)
                                                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-indigo-700 bg-indigo-50 border border-indigo-200 px-2.5 py-0.5 rounded-full">
                                                        Akses Terbuka
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 text-[11px] font-medium text-slate-400 bg-slate-100 px-2.5 py-0.5 rounded-full">
                                                        <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                                        Terkunci
                                                    </span>
                                                @endif
                                            </div>

                                            @if($lesson->description)
                                                <p class="text-xs sm:text-sm text-slate-500 line-clamp-2 leading-relaxed">
                                                    {{ $lesson->description }}
                                                </p>
                                            @endif

                                            <div class="flex items-center gap-3 mt-2 text-xs text-slate-400">
                                                @if($lesson->duration)
                                                <span class="flex items-center gap-1 font-medium">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    {{ $lesson->duration }} menit
                                                </span>
                                                @endif
                                                <span>•</span>
                                                <span>Modul Ke-{{ $lesson->order }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Action CTA --}}
                                    <div class="flex items-center justify-end shrink-0 pl-14 sm:pl-0">
                                        @if($canOpen)
                                            <a href="{{ route('courses.lessons.show', [$course->slug, $lesson->id]) }}"
                                               class="inline-flex items-center gap-2 text-xs sm:text-sm font-extrabold text-white bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 px-5 py-2.5 rounded-2xl transition-all duration-200 shadow-md shadow-indigo-500/20 hover:shadow-lg hover:shadow-indigo-500/30 hover:scale-[1.02] active:scale-[0.98]">
                                                <span>Buka Modul</span>
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                            </a>
                                        @else
                                            <a href="#cta-daftar"
                                               class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-400 hover:text-indigo-600 transition-colors py-2 px-3 rounded-xl bg-slate-100 hover:bg-slate-200/80">
                                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                                <span>Daftar untuk Membuka</span>
                                            </a>
                                        @endif
                                    </div>

                                </div>
                                @endforeach
                            </div>
                        @endif
                    @else
                    <div class="p-12 text-center text-slate-400">
                        <p class="text-sm">Belum ada materi pembelajaran yang diterbitkan.</p>
                    </div>
                    @endif
                </div>

                {{-- INSTRUKTUR PROFILE --}}
                <div class="bg-white rounded-3xl p-7 sm:p-8 border border-slate-200/80 shadow-sm relative overflow-hidden">
                    <h2 class="text-base font-bold text-slate-400 uppercase tracking-wider mb-5">Instruktur Pengajar</h2>

                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5">
                        <div class="relative shrink-0">
                            <div class="w-20 h-20 rounded-2xl overflow-hidden ring-4 ring-indigo-500/10 shadow-lg">
                                <img src="{{ $course->instructor->avatar_url ?? asset('images/avatars/instructor1.jpg') }}"
                                     alt="{{ $course->instructor->name }}"
                                     class="w-full h-full object-cover">
                            </div>
                            <div class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full bg-indigo-600 text-white flex items-center justify-center text-xs shadow-md border-2 border-white">
                                ✓
                            </div>
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="text-lg font-extrabold text-slate-900">{{ $course->instructor->name }}</h3>
                                <span class="text-xs bg-indigo-50 text-indigo-700 font-bold px-2.5 py-0.5 rounded-full border border-indigo-100">Mentor Berpengalaman</span>
                            </div>
                            @if($course->instructor->bio)
                                <p class="text-sm text-slate-600 mt-1.5 leading-relaxed">{{ $course->instructor->bio }}</p>
                            @endif

                            <div class="flex items-center gap-4 mt-3 text-xs text-slate-400">
                                <span>📚 {{ $course->instructor->courses()->where('status','published')->count() }} Kursus Terbit</span>
                                <span>•</span>
                                <span>⭐ 4.9 Rating Rata-rata</span>
                                <span>•</span>
                                <span>🎓 Mentor Praktisi Industri</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ==================== SIDEBAR STICKY CARD (4 COLS) ==================== --}}
            <div class="lg:col-span-4" id="cta-daftar">
                <div class="sticky top-20 space-y-6">

                    {{-- LUXURY ENROLLMENT CARD --}}
                    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xl shadow-slate-200/50 overflow-hidden relative group">

                        {{-- High Quality Image Banner --}}
                        <div class="aspect-video relative overflow-hidden bg-slate-900 group">
                            <img src="{{ $course->thumbnail_url }}"
                                 alt="{{ $course->title }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">

                            {{-- Subtle Dark Gradient Overlay --}}
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-black/20 to-transparent"></div>

                            {{-- Glass Level Badge --}}
                            <div class="absolute top-4 left-4 z-10">
                                <span class="backdrop-blur-md bg-black/60 text-white text-xs font-bold px-3 py-1 rounded-full border border-white/20 shadow-md">
                                    {{ $course->level_label }}
                                </span>
                            </div>

                            {{-- Play / Preview Indicator --}}
                            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                <div class="w-14 h-14 rounded-full bg-white/90 backdrop-blur-md text-indigo-600 flex items-center justify-center shadow-xl group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-6 h-6 ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="p-6 sm:p-7 space-y-6">

                            {{-- Price & Guarantee --}}
                            <div class="flex items-baseline justify-between border-b border-slate-100 pb-5">
                                <div>
                                    <div class="flex items-baseline gap-2">
                                        <span class="text-3xl font-black text-slate-900">Gratis</span>
                                        <span class="text-sm line-through text-slate-400">Rp 499.000</span>
                                    </div>
                                    <p class="text-xs font-bold text-emerald-600 mt-0.5 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        Akses Penuh Seumur Hidup
                                    </p>
                                </div>
                                <span class="bg-rose-50 text-rose-600 border border-rose-200/60 font-black text-xs px-2.5 py-1 rounded-xl">
                                    DISKON 100%
                                </span>
                            </div>

                            {{-- CTA BUTTONS --}}
                            <div class="space-y-3">
                                @auth
                                    @if($isEnrolled)
                                        <a href="{{ route('student.courses.show', $course->slug) }}"
                                           class="block w-full text-center bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-extrabold py-4 rounded-2xl transition-all shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/35 hover:scale-[1.01] active:scale-[0.99] text-base">
                                            Lanjut Belajar Sekarang →
                                        </a>
                                        <p class="text-center text-xs text-emerald-600 font-semibold flex items-center justify-center gap-1.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            Anda sudah terdaftar di kursus ini
                                        </p>
                                    @elseif(auth()->user()->isStudent())
                                        <form method="POST" action="{{ route('student.courses.enroll', $course->id) }}">
                                            @csrf
                                            <button type="submit"
                                                    class="w-full text-center bg-gradient-to-r from-indigo-600 via-indigo-700 to-violet-700 hover:from-indigo-700 hover:to-violet-800 text-white font-extrabold py-4 rounded-2xl transition-all shadow-xl shadow-indigo-600/25 hover:shadow-indigo-600/35 hover:scale-[1.01] active:scale-[0.99] text-base flex items-center justify-center gap-2">
                                                <span>Daftar Kursus Gratis</span>
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                            </button>
                                        </form>
                                        <p class="text-center text-xs text-slate-400">100% bebas biaya pendaftaran • Akses instan</p>
                                    @else
                                        <div class="text-center text-xs text-slate-500 bg-slate-50 border border-slate-200 rounded-2xl py-3 px-4">
                                            Masuk sebagai student untuk mendaftar kursus penuh.
                                        </div>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}"
                                       class="block w-full text-center bg-gradient-to-r from-indigo-600 via-indigo-700 to-violet-700 hover:from-indigo-700 hover:to-violet-800 text-white font-extrabold py-4 rounded-2xl transition-all shadow-xl shadow-indigo-600/25 hover:shadow-indigo-600/35 hover:scale-[1.01] active:scale-[0.99] text-base">
                                        Masuk untuk Mendaftar Gratis
                                    </a>
                                    <p class="text-center text-xs text-slate-400">
                                        Belum punya akun? <a href="{{ route('register') }}" class="text-indigo-600 font-bold hover:underline">Daftar sekarang</a>
                                    </p>
                                @endauth

                                {{-- Quick Link to Open Lesson 1 Directly --}}
                                @if($course->lessons->isNotEmpty())
                                <a href="{{ route('courses.lessons.show', [$course->slug, $course->lessons->first()->id]) }}"
                                   class="block w-full text-center bg-slate-50 hover:bg-slate-100 text-slate-700 font-bold py-3 rounded-2xl border border-slate-200 text-xs transition-colors">
                                    📖 Coba Baca Modul 1 Sekarang (Gratis)
                                </a>
                                @endif
                            </div>

                            <hr class="border-slate-100">

                            {{-- Course Meta Specifications --}}
                            <div class="space-y-3.5 text-xs sm:text-sm">
                                <p class="font-bold text-slate-900 text-xs uppercase tracking-wider mb-2">Fasilitas Kursus:</p>

                                @foreach([
                                    ['Total Modul', $course->lessons->count() . ' Materi Lengkap', 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'text-indigo-600'],
                                    ['Durasi Belajar', $course->formatted_duration, 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'text-emerald-600'],
                                    ['Tingkat Kesulitan', $course->level_label, 'M13 10V3L4 14h7v7l9-11h-7z', 'text-amber-500'],
                                    ['Bahasa Pengantar', 'Bahasa Indonesia', 'M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129', 'text-cyan-600'],
                                    ['Pratinjau Bebas', 'Min. 3 Modul Pertama', 'M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z', 'text-teal-600'],
                                    ['Sertifikat', 'Tersedia Setelah Lulus', 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z', 'text-violet-600'],
                                ] as $f)
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-slate-100 flex items-center justify-center shrink-0 {{ $f[3] }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $f[2] }}"/></svg>
                                    </div>
                                    <span class="text-slate-500 flex-1">{{ $f[0] }}</span>
                                    <span class="font-bold text-slate-800">{{ $f[1] }}</span>
                                </div>
                                @endforeach
                            </div>

                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

</div>
@endsection