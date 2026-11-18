@extends('layouts.app')

@php
    $role = strtolower(auth()->user()->role);
    $isAdmin = $role === 'admin';
    $isDosen = $role === 'dosen';
    $isMahasiswa = $role === 'mahasiswa';

    $pageTitle = match ($role) {
        'admin' => 'Manajemen Mata Kuliah',
        'dosen' => 'Mata Kuliah yang Diampu',
        default => 'Katalog Mata Kuliah',
    };

    $pageDescription = match ($role) {
        'admin' => 'Kelola data mata kuliah dan pantau kelas yang tersedia di CeLOE LMS.',
        'dosen' => 'Kelola materi, tugas, dan pendaftaran mahasiswa pada mata kuliah yang Anda ampu.',
        default => 'Temukan mata kuliah yang tersedia dan ajukan pendaftaran untuk bergabung.',
    };
@endphp

@section('title', $pageTitle)

@push('styles')
<style>
    .courses-page { max-width: 1240px; margin: 0 auto; }
    .courses-header { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem; }
    .courses-title { font-size: clamp(1.4rem, 3vw, 1.85rem); font-weight: 750; color: var(--slate-900, #0f172a); margin: 0 0 .375rem; }
    .courses-description { margin: 0; color: var(--slate-500, #64748b); font-size: .9rem; line-height: 1.65; }
    .courses-eyebrow { display: inline-block; color: var(--tel-red, #b91c1c); font-size: .75rem; font-weight: 750; text-transform: uppercase; letter-spacing: .09em; margin-bottom: .4rem; }
    .courses-toolbar { background: #fff; border: 1px solid var(--slate-200, #e2e8f0); border-radius: 12px; padding: 1rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: .8rem; flex-wrap: wrap; }
    .courses-search { flex: 1 1 300px; min-width: 0; }
    .courses-count { color: var(--slate-500, #64748b); font-size: .82rem; font-weight: 600; margin-left: auto; }
    .courses-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(min(100%, 300px), 1fr)); gap: 1.25rem; }
    .course-tile { min-width: 0; background: #fff; border: 1px solid var(--slate-200, #e2e8f0); border-radius: 12px; overflow: hidden; display: flex; flex-direction: column; box-shadow: 0 2px 10px rgba(15, 23, 42, .035); transition: transform .2s, box-shadow .2s; }
    .course-tile:hover { transform: translateY(-3px); box-shadow: 0 10px 24px rgba(15, 23, 42, .09); }
    .course-stripe { height: 5px; background: var(--tel-red, #b91c1c); }
    .course-content { padding: 1.25rem; flex: 1; }
    .course-topline { display: flex; justify-content: space-between; align-items: center; gap: .5rem; margin-bottom: .85rem; }
    .course-code { padding: .3rem .55rem; background: #fef2f2; color: #991b1b; border-radius: 6px; font-size: .78rem; font-weight: 750; overflow-wrap: anywhere; }
    .course-sks { color: var(--slate-500, #64748b); font-size: .8rem; white-space: nowrap; }
    .course-name { font-size: 1.08rem; line-height: 1.4; margin: 0 0 .55rem; font-weight: 750; }
    .course-name a { color: var(--slate-900, #0f172a); text-decoration: none; }
    .course-name a:hover { color: var(--tel-red, #b91c1c); }
    .course-summary { color: var(--slate-600, #475569); font-size: .83rem; line-height: 1.6; margin: 0 0 1rem; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
    .course-meta { padding-top: .85rem; border-top: 1px solid #f1f5f9; display: grid; gap: .5rem; font-size: .81rem; color: var(--slate-600, #475569); }
    .course-meta-row { display: flex; justify-content: space-between; gap: .65rem; }
    .course-meta-label { color: var(--slate-500, #64748b); flex-shrink: 0; }
    .course-meta-value { color: var(--slate-800, #1e293b); font-weight: 650; text-align: right; overflow-wrap: anywhere; }
    .course-footer { padding: 1rem 1.25rem; background: #f8fafc; border-top: 1px solid var(--slate-200, #e2e8f0); display: grid; gap: .75rem; }
    .course-enrollment-count { font-size: .78rem; color: var(--slate-500, #64748b); }
    .course-actions { display: flex; align-items: center; gap: .5rem; flex-wrap: wrap; }
    .course-actions form { margin: 0; }
    .course-actions .btn { text-decoration: none; }
    .course-status { display: inline-flex; align-items: center; padding: .3rem .55rem; border-radius: 6px; font-size: .75rem; font-weight: 750; }
    .course-status--approved { background: #dcfce7; color: #166534; }
    .course-status--pending { background: #fef3c7; color: #92400e; }
    .course-status--rejected { background: #fee2e2; color: #991b1b; }
    .courses-empty { padding: 3rem 1.5rem; text-align: center; background: #fff; border: 1px dashed var(--slate-200, #cbd5e1); border-radius: 12px; }
    .courses-empty h2 { font-size: 1rem; margin: 0 0 .5rem; color: var(--slate-800, #1e293b); }
    .courses-empty p { color: var(--slate-500, #64748b); font-size: .875rem; margin: 0; }
    .courses-pagination { margin-top: 1.5rem; }
    @media (max-width: 640px) {
        .courses-header { align-items: flex-start; }
        .courses-header > .btn { width: 100%; text-align: center; }
        .courses-toolbar > button, .courses-toolbar > a.btn { flex: 1; text-align: center; }
        .courses-count { width: 100%; margin-left: 0; }
    }
</style>
@endpush

@section('content')
<div class="courses-page">
    <header class="courses-header">
        <div>
            <span class="courses-eyebrow">CeLOE LMS / {{ ucfirst($role) }}</span>
            <h1 class="courses-title">{{ $pageTitle }}</h1>
            <p class="courses-description">{{ $pageDescription }}</p>
        </div>

        @if($isAdmin || $isDosen)
            <a href="{{ route('courses.create') }}" class="btn btn-primary">+ Buat Mata Kuliah</a>
        @endif
    </header>

    <form class="courses-toolbar" action="{{ route('courses.index') }}" method="GET" role="search">
        <div class="courses-search">
            <label for="course-search" class="visually-hidden" style="position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border:0;">Cari mata kuliah</label>
            <input id="course-search" type="search" name="search" class="form-control"
                   placeholder="Cari kode, nama mata kuliah, atau semester..."
                   value="{{ request('search') }}">
        </div>
        <button type="submit" class="btn btn-primary">Cari</button>
        @if(request()->filled('search'))
            <a href="{{ route('courses.index') }}" class="btn btn-secondary">Reset</a>
        @endif
        <span class="courses-count">{{ $courses->total() }} mata kuliah ditemukan</span>
    </form>

    <div class="courses-grid">
        @forelse($courses as $course)
            <article class="course-tile">
                <div class="course-stripe"></div>
                <div class="course-content">
                    <div class="course-topline">
                        <span class="course-code">{{ $course->kode_mk }}</span>
                        <span class="course-sks">{{ $course->sks }} SKS</span>
                    </div>

                    <h2 class="course-name">
                        <a href="{{ route('courses.show', $course->id) }}">{{ $course->nama_mk }}</a>
                    </h2>
                    <p class="course-summary">{{ $course->deskripsi ?: 'Belum ada deskripsi mata kuliah.' }}</p>

                    <div class="course-meta">
                        <div class="course-meta-row">
                            <span class="course-meta-label">Dosen pengampu</span>
                            <span class="course-meta-value">{{ $course->dosen?->name ?? 'Belum ditentukan' }}</span>
                        </div>
                        <div class="course-meta-row">
                            <span class="course-meta-label">Semester</span>
                            <span class="course-meta-value">{{ $course->semester }}</span>
                        </div>
                    </div>
                </div>

                <div class="course-footer">
                    <span class="course-enrollment-count">{{ $course->students_count ?? 0 }} mahasiswa aktif</span>

                    @if($isAdmin || $isDosen)
                        <div class="course-actions">
                            <a href="{{ route('courses.show', $course->id) }}" class="btn btn-primary btn-sm">
                                {{ $isAdmin ? 'Lihat Kelas' : 'Kelola Kelas' }}
                            </a>
                            <a href="{{ route('courses.edit', $course->id) }}" class="btn btn-secondary btn-sm">Edit</a>
                            <form action="{{ route('courses.destroy', $course->id) }}" method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus mata kuliah ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </div>
                    @elseif($isMahasiswa)
                        @php $enrollStatus = $course->getStudentEnrollmentStatus(auth()->id()); @endphp

                        <div class="course-actions">
                            @if($enrollStatus === 'approved')
                                <span class="course-status course-status--approved">Terdaftar</span>
                                <a href="{{ route('courses.show', $course->id) }}" class="btn btn-primary btn-sm">Masuk Kelas</a>
                            @elseif($enrollStatus === 'pending')
                                <span class="course-status course-status--pending">Menunggu Persetujuan</span>
                                <a href="{{ route('courses.show', $course->id) }}" class="btn btn-secondary btn-sm">Ringkasan</a>
                            @elseif($enrollStatus === 'rejected')
                                <span class="course-status course-status--rejected">Ditolak</span>
                                <form action="{{ route('courses.enroll-request', $course->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-secondary btn-sm">Ajukan Ulang</button>
                                </form>
                            @else
                                <form action="{{ route('courses.enroll-request', $course->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm">+ Ajukan Pendaftaran</button>
                                </form>
                                <a href="{{ route('courses.show', $course->id) }}" class="btn btn-secondary btn-sm">Ringkasan</a>
                            @endif
                        </div>
                    @endif
                </div>
            </article>
        @empty
            <div class="courses-empty" style="grid-column: 1 / -1;">
                <h2>Tidak Ada Mata Kuliah Ditemukan</h2>
                <p>
                    @if(request()->filled('search'))
                        Coba gunakan kata kunci lain atau reset pencarian.
                    @elseif($isAdmin || $isDosen)
                        Belum ada mata kuliah yang tersedia. Gunakan tombol Buat Mata Kuliah untuk menambahkan kelas.
                    @else
                        Belum ada mata kuliah yang tersedia saat ini.
                    @endif
                </p>
            </div>
        @endforelse
    </div>

    @if($courses->hasPages())
        <div class="courses-pagination">
            {{ $courses->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
