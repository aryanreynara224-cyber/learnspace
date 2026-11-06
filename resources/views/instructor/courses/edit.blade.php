@extends('layouts.dashboard')
@section('title', 'Edit Kursus')
@section('page-title', 'Edit Kursus')
@section('page-subtitle', $course->title)

@section('sidebar-nav')
<x-nav-item href="{{ route('instructor.dashboard') }}" :active="false"
    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>'>
    Dashboard
</x-nav-item>
<div class="mt-4 mb-1 px-3"><p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Konten</p></div>
<x-nav-item href="{{ route('instructor.courses.index') }}" :active="true"
    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>'>
    Kursus Saya
</x-nav-item>
<x-nav-item href="{{ route('instructor.courses.create') }}" :active="false"
    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>'>
    Tambah Kursus
</x-nav-item>
@endsection

@section('content')
<div class="mb-5">
    <a href="{{ route('instructor.courses.index') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-indigo-600 transition-colors font-medium">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali ke Kursus Saya
    </a>
</div>

<div class="space-y-8 max-w-5xl">

    {{-- 1. INFORMASI UMUM KURSUS --}}
    <form method="POST" action="{{ route('instructor.courses.update', $course->id) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Main Fields --}}
            <div class="lg:col-span-2 space-y-5">
                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 space-y-5">
                    <h2 class="font-bold text-slate-900 text-sm pb-2 border-b border-slate-100 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span>
                        Informasi Utama Kursus
                    </h2>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Judul Kursus <span class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title', $course->title) }}" required
                               class="w-full px-4 py-3 border {{ $errors->has('title') ? 'border-red-400 bg-red-50' : 'border-slate-200 bg-slate-50 focus:bg-white' }} rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                        @error('title')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Deskripsi Kursus <span class="text-red-500">*</span></label>
                        <textarea name="description" rows="5" required
                                  class="w-full px-4 py-3 border {{ $errors->has('description') ? 'border-red-400 bg-red-50' : 'border-slate-200 bg-slate-50 focus:bg-white' }} rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all resize-none">{{ old('description', $course->description) }}</textarea>
                        @error('description')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Thumbnail --}}
                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">
                    <h2 class="font-bold text-slate-900 text-sm pb-2 border-b border-slate-100 mb-4 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-violet-600"></span>
                        Thumbnail Kursus
                    </h2>
                    <label for="thumbnail-input" class="block cursor-pointer">
                        <div class="border-2 border-dashed border-slate-200 hover:border-indigo-400 rounded-2xl p-6 text-center transition-colors group">
                            @if($course->thumbnail)
                            <img id="thumbnail-img" src="{{ asset('storage/' . $course->thumbnail) }}"
                                 class="mx-auto max-h-48 rounded-xl object-cover mb-3" alt="Thumbnail">
                            <p class="text-xs text-slate-400 group-hover:text-indigo-500 transition-colors">Klik untuk ganti gambar</p>
                            @else
                            <div id="thumbnail-placeholder">
                                <div class="w-12 h-12 bg-slate-100 group-hover:bg-indigo-50 rounded-2xl flex items-center justify-center mx-auto mb-3 transition-colors">
                                    <svg class="w-6 h-6 text-slate-400 group-hover:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <p class="text-sm font-semibold text-slate-600">Klik untuk upload gambar</p>
                                <p class="text-xs text-slate-400 mt-1">PNG, JPG, WebP · Maks 2MB</p>
                            </div>
                            <img id="thumbnail-new" class="hidden mx-auto max-h-48 rounded-xl object-cover" alt="Preview Baru">
                            @endif
                        </div>
                        <input type="file" id="thumbnail-input" name="thumbnail" accept="image/jpeg,image/png,image/webp" class="hidden"
                               onchange="previewThumbnail(this)">
                    </label>
                    @error('thumbnail')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Sidebar Settings --}}
            <div class="space-y-5">
                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5 space-y-4">
                    <h2 class="font-bold text-slate-900 text-sm pb-1 border-b border-slate-100">Pengaturan</h2>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kategori <span class="text-red-500">*</span></label>
                        <select name="category_id" required class="w-full px-4 py-3 border border-slate-200 bg-slate-50 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                            <option value="">Pilih Kategori…</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $course->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Level <span class="text-red-500">*</span></label>
                        <select name="level" required class="w-full px-4 py-3 border border-slate-200 bg-slate-50 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                            <option value="beginner" {{ old('level', $course->level) === 'beginner' ? 'selected' : '' }}>🟢 Pemula</option>
                            <option value="intermediate" {{ old('level', $course->level) === 'intermediate' ? 'selected' : '' }}>🟡 Menengah</option>
                            <option value="advanced" {{ old('level', $course->level) === 'advanced' ? 'selected' : '' }}>🔴 Mahir</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Durasi <span class="text-slate-400 font-normal">(menit)</span></label>
                        <input type="number" name="duration" value="{{ old('duration', $course->duration) }}" min="1" required
                               class="w-full px-4 py-3 border border-slate-200 bg-slate-50 focus:bg-white rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Status Publikasi</label>
                        <div class="space-y-2">
                            @foreach([['draft', 'Draft', 'Hanya Anda yang dapat melihat', 'bg-amber-50 border-amber-200'], ['published', 'Published', 'Terbuka untuk semua siswa', 'bg-emerald-50 border-emerald-200']] as $s)
                            <label class="flex items-start gap-3 p-3 border rounded-xl cursor-pointer hover:opacity-90 transition-opacity {{ $s[3] }}">
                                <input type="radio" name="status" value="{{ $s[0] }}" class="mt-0.5 text-indigo-600 focus:ring-indigo-500" {{ old('status', $course->status) === $s[0] ? 'checked' : '' }}>
                                <div>
                                    <p class="text-sm font-semibold text-slate-800">{{ $s[1] }}</p>
                                    <p class="text-xs text-slate-500">{{ $s[2] }}</p>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Info Card --}}
                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 text-xs text-slate-500 space-y-1.5">
                    <p><span class="font-semibold text-slate-700">Dibuat:</span> {{ $course->created_at->format('d M Y') }}</p>
                    <p><span class="font-semibold text-slate-700">Peserta:</span> {{ $course->enrollments->count() }} orang</p>
                    <p><span class="font-semibold text-slate-700">Jumlah Bab:</span> {{ $course->sections->count() }} bab</p>
                    <p><span class="font-semibold text-slate-700">Total Materi:</span> {{ $course->lessons->count() }} pelajaran</p>
                </div>

                <div class="space-y-2.5">
                    <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 text-white font-bold py-3 rounded-xl text-sm transition-all hover:shadow-lg hover:shadow-indigo-200 cursor-pointer">
                        Simpan Perubahan Kursus
                    </button>
                </div>
            </div>
        </div>
    </form>

    {{-- 2. MANAJEMEN STRUKTUR KURIKULUM (BAB & MATERI) --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 space-y-6" x-data="{ showAddSection: false }">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-3">
            <div>
                <h2 class="text-lg font-extrabold text-slate-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    Struktur Kurikulum & Bab Pembelajaran
                </h2>
                <p class="text-xs text-slate-500 mt-1">Kelola pembagian bab kursus agar siswa belajar secara bertahap dan teratur.</p>
            </div>
            <div class="flex items-center gap-2.5">
                <button type="button" @click="showAddSection = !showAddSection"
                        class="inline-flex items-center gap-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold px-3.5 py-2 rounded-xl transition-colors cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span x-text="showAddSection ? 'Tutup Form' : '+ Tambah Bab Baru'"></span>
                </button>
                <a href="{{ route('instructor.lessons.create', $course->id) }}"
                   class="inline-flex items-center gap-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold px-4 py-2 rounded-xl transition-colors shadow-xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    + Tambah Materi
                </a>
            </div>
        </div>

        {{-- Form Tambah Bab Baru --}}
        <div x-show="showAddSection" x-cloak class="p-4 bg-indigo-50/60 border border-indigo-100 rounded-xl space-y-3">
            <form method="POST" action="{{ route('instructor.sections.store', $course->id) }}" class="space-y-3">
                @csrf
                <p class="text-xs font-bold text-indigo-950 uppercase tracking-wider">Form Tambah Bab Baru</p>
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-medium text-slate-700 mb-1">Judul Bab <span class="text-red-500">*</span></label>
                        <input type="text" name="title" required placeholder="Contoh: Bab 3 - Database & Migrasi"
                               class="w-full px-3 py-2 border border-slate-300 rounded-lg text-xs bg-white focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">Nomor Urutan</label>
                        <input type="number" name="order" value="{{ ($course->sections()->max('order') ?? 0) + 1 }}" min="1"
                               class="w-full px-3 py-2 border border-slate-300 rounded-lg text-xs bg-white focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold py-2 px-4 rounded-lg transition-colors cursor-pointer">
                            Simpan Bab
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">Deskripsi Singkat Bab (Opsional)</label>
                    <input type="text" name="description" placeholder="Apa yang akan dipelajari di bab ini?"
                           class="w-full px-3 py-2 border border-slate-300 rounded-lg text-xs bg-white focus:ring-2 focus:ring-indigo-500">
                </div>
            </form>
        </div>

        {{-- Daftar Bab dan Materi di dalamnya --}}
        <div class="space-y-4">
            @forelse($course->sections as $sec)
                <div class="border border-slate-200 rounded-2xl overflow-hidden bg-slate-50/50">
                    {{-- Header Bab --}}
                    <div class="px-5 py-3.5 bg-slate-100/80 border-b border-slate-200 flex flex-wrap items-center justify-between gap-2">
                        <div class="flex items-center gap-3">
                            <span class="w-7 h-7 bg-indigo-600 text-white text-xs font-black rounded-lg flex items-center justify-center shrink-0">
                                {{ $sec->order }}
                            </span>
                            <div>
                                <h3 class="font-bold text-sm text-slate-900">Bab {{ $sec->order }}: {{ $sec->title }}</h3>
                                @if($sec->description)
                                    <p class="text-xs text-slate-500">{{ $sec->description }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs bg-white border border-slate-200 text-slate-600 px-2.5 py-0.5 rounded-full font-medium">
                                {{ $sec->lessons->count() }} materi
                            </span>
                            <form method="POST" action="{{ route('instructor.sections.destroy', $sec->id) }}" onsubmit="return confirm('Hapus bab ini? Materi di dalamnya tidak akan terhapus, melainkan menjadi materi umum.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-semibold px-2 py-1 cursor-pointer">Hapus Bab</button>
                            </form>
                        </div>
                    </div>

                    {{-- Materi di dalam Bab ini --}}
                    <div class="divide-y divide-slate-100 bg-white">
                        @forelse($sec->lessons as $l)
                            <div class="flex items-center justify-between px-5 py-3.5 hover:bg-slate-50 transition-colors group">
                                <div class="flex items-center gap-3 min-w-0">
                                    <span class="text-xs font-semibold text-slate-400 w-5 text-right">{{ $l->order }}.</span>
                                    
                                    {{-- Tipe Badge --}}
                                    @if($l->type === 'audio')
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200 shrink-0">🎧 Audio</span>
                                    @elseif($l->type === 'article')
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-800 border border-blue-200 shrink-0">📄 Bacaan</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 shrink-0">🎬 Video</span>
                                    @endif

                                    <p class="text-xs font-medium text-slate-800 truncate">{{ $l->title }}</p>

                                    @if($l->attachments->count() > 0)
                                        <span class="text-[10px] bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded border border-emerald-200 shrink-0 font-medium">
                                            📎 {{ $l->attachments->count() }} berkas
                                        </span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-3 shrink-0">
                                    @if($l->duration)<span class="text-xs text-slate-400">{{ $l->duration }}m</span>@endif
                                    <a href="{{ route('instructor.lessons.edit', $l->id) }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">Edit</a>
                                    <form method="POST" action="{{ route('instructor.lessons.destroy', $l->id) }}" onsubmit="return confirm('Hapus materi ini?')">
                                        @csrf @method('DELETE')
                                        <button class="text-xs text-red-500 hover:text-red-700 font-medium cursor-pointer">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="px-5 py-4 text-xs text-slate-400 text-center">
                                Belum ada materi di bab ini. Tambahkan materi dan pilih bab ini pada form materi.
                            </div>
                        @endforelse
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-slate-400 text-sm">
                    <p>Kursus ini belum memiliki bab pembelajaran.</p>
                </div>
            @endforelse

            {{-- Materi yang Belum Memiliki Bab (Unassigned) --}}
            @php $unassignedLessons = $course->lessons->whereNull('section_id'); @endphp
            @if($unassignedLessons->count() > 0)
                <div class="border border-dashed border-slate-300 rounded-2xl overflow-hidden bg-white">
                    <div class="px-5 py-3 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                        <h3 class="font-bold text-xs text-slate-600 uppercase tracking-wider">Materi Tambahan (Belum Diatur Babnya)</h3>
                        <span class="text-xs text-slate-400">{{ $unassignedLessons->count() }} materi</span>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @foreach($unassignedLessons as $l)
                            <div class="flex items-center justify-between px-5 py-3 hover:bg-slate-50 transition-colors">
                                <div class="flex items-center gap-3 min-w-0">
                                    <span class="text-xs font-semibold text-slate-400 w-5 text-right">{{ $l->order }}.</span>
                                    @if($l->type === 'audio')
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200 shrink-0">🎧 Audio</span>
                                    @elseif($l->type === 'article')
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-800 border border-blue-200 shrink-0">📄 Bacaan</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 shrink-0">🎬 Video</span>
                                    @endif
                                    <p class="text-xs font-medium text-slate-800 truncate">{{ $l->title }}</p>
                                </div>
                                <div class="flex items-center gap-3 shrink-0">
                                    <a href="{{ route('instructor.lessons.edit', $l->id) }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">Edit</a>
                                    <form method="POST" action="{{ route('instructor.lessons.destroy', $l->id) }}" onsubmit="return confirm('Hapus materi ini?')">
                                        @csrf @method('DELETE')
                                        <button class="text-xs text-red-500 hover:text-red-700 font-medium cursor-pointer">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewThumbnail(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = (e) => {
            const ph = document.getElementById('thumbnail-placeholder');
            const newImg = document.getElementById('thumbnail-new');
            const existImg = document.getElementById('thumbnail-img');
            if (ph) ph.classList.add('hidden');
            if (existImg) { existImg.src = e.target.result; }
            if (newImg) { newImg.src = e.target.result; newImg.classList.remove('hidden'); }
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
