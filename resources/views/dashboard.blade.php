@extends('layouts.app')

@section('title', 'Dashboard | CeLOE LMS')

@push('styles')
<style>
    .dash { --dash-red: #b91c1c; --dash-ink: #0f172a; --dash-muted: #64748b; --dash-line: #e2e8f0; max-width: 1350px; margin: 0 auto; }
    .dash * { box-sizing: border-box; }
    .dash a { text-decoration: none; }
    .dash-hero { position: relative; overflow: hidden; background: linear-gradient(115deg, #991b1b 0%, #b91c1c 55%, #ef4444 100%); color: #fff; padding: 2rem 2.25rem; border-radius: 16px; margin-bottom: 1.5rem; }
    .dash-hero::after { content: ''; position: absolute; width: 240px; height: 240px; border: 45px solid rgba(255,255,255,.075); border-radius: 50%; right: -65px; top: -110px; pointer-events: none; }
    .dash-hero-inner { position: relative; z-index: 1; }
    .dash-eyebrow { font-size: .76rem; font-weight: 700; letter-spacing: .09em; text-transform: uppercase; opacity: .9; margin-bottom: .6rem; }
    .dash-hero h1 { font-size: clamp(1.35rem, 3vw, 2rem); color: white; font-weight: 750; margin: 0 0 .5rem; line-height: 1.25; }
    .dash-hero p { color: #fee2e2; font-size: .9rem; margin: 0; max-width: 650px; line-height: 1.6; }
    .dash-hero-bottom { display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: .75rem; margin-top: 1.2rem; }
    .dash-role { display: inline-flex; align-items: center; padding: .35rem .75rem; font-size: .78rem; font-weight: 700; background: rgba(255,255,255,.18); border: 1px solid rgba(255,255,255,.3); border-radius: 999px; }
    .dash-semester { color: #fee2e2; font-size: .78rem; }
    .dash-stats { display: grid; grid-template-columns: repeat(4,minmax(0,1fr)); gap: 1rem; margin-bottom: 1.5rem; }
    .dash-stat { background: #fff; border: 1px solid var(--dash-line); border-radius: 13px; padding: 1.25rem; min-width: 0; box-shadow: 0 3px 10px rgba(15,23,42,.035); }
    .dash-stat-head { display: flex; align-items: center; justify-content: space-between; gap: .5rem; margin-bottom: 1rem; }
    .dash-stat-label { color: var(--dash-muted); font-size: .8rem; font-weight: 650; line-height: 1.5; }
    .dash-stat-icon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; background: #fef2f2; color: #b91c1c; font-weight: 800; flex-shrink: 0; }
    .dash-stat-value { color: var(--dash-ink); font-size: 2rem; font-weight: 800; line-height: 1; }
    .dash-stat-note { color: #94a3b8; font-size: .73rem; margin-top: .5rem; }
    .dash-section-title { margin: 0 0 1rem; font-size: 1.075rem; color: var(--dash-ink); font-weight: 750; }
    .dash-actions { display: grid; grid-template-columns: repeat(3,minmax(0,1fr)); gap: 1rem; margin-bottom: 1.6rem; }
    .dash-action { background: #fff; border: 1px solid var(--dash-line); border-radius: 13px; padding: 1.15rem; display: flex; gap: .9rem; align-items: center; min-width: 0; transition: transform .15s, border-color .15s, box-shadow .15s; }
    .dash-action:hover { border-color: #fca5a5; box-shadow: 0 7px 16px rgba(15,23,42,.06); transform: translateY(-2px); }
    .dash-action-symbol { width: 43px; height: 43px; display: flex; align-items: center; justify-content: center; border-radius: 11px; background: #fef2f2; color: #b91c1c; font-size: 1.3rem; flex-shrink: 0; }
    .dash-action-title { font-size: .9rem; font-weight: 750; color: var(--dash-ink); margin-bottom: .25rem; }
    .dash-action-note { font-size: .76rem; color: var(--dash-muted); line-height: 1.4; }
    .dash-content { display: grid; grid-template-columns: minmax(0,1.7fr) minmax(290px,1fr); align-items: start; gap: 1.25rem; }
    .dash-panel { background: #fff; border: 1px solid var(--dash-line); border-radius: 13px; overflow: hidden; margin-bottom: 1.25rem; box-shadow: 0 3px 10px rgba(15,23,42,.03); }
    .dash-panel-header { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; padding: 1.25rem 1.35rem; border-bottom: 1px solid var(--dash-line); }
    .dash-panel-title { margin: 0; color: var(--dash-ink); font-size: 1rem; font-weight: 750; }
    .dash-panel-description { margin-top: .3rem; color: var(--dash-muted); font-size: .77rem; }
    .dash-link { color: #b91c1c; font-size: .8rem; font-weight: 750; }
    .dash-list { padding: 0 1.35rem; }
    .dash-list-row { padding: 1rem 0; display: flex; align-items: center; justify-content: space-between; gap: 1rem; border-bottom: 1px solid #f1f5f9; }
    .dash-list-row:last-child { border-bottom: 0; }
    .dash-item-title { display: block; color: var(--dash-ink); font-size: .89rem; font-weight: 750; line-height: 1.4; }
    .dash-item-title:hover { color: #b91c1c; }
    .dash-item-meta { margin-top: .28rem; color: var(--dash-muted); font-size: .76rem; line-height: 1.55; }
    .dash-item-sub { color: #94a3b8; font-size: .73rem; margin-top: .2rem; }
    .dash-pill { display: inline-flex; border-radius: 99px; padding: .28rem .6rem; background: #f1f5f9; color: #475569; font-size: .72rem; font-weight: 750; white-space: nowrap; }
    .dash-pill.is-green { color: #047857; background: #ecfdf5; }
    .dash-pill.is-yellow { color: #92400e; background: #fffbeb; }
    .dash-pill.is-red { color: #b91c1c; background: #fef2f2; }
    .dash-empty { text-align: center; padding: 2.5rem 1rem; color: var(--dash-muted); font-size: .84rem; }
    .dash-announcement { padding: .95rem 0; border-bottom: 1px solid #f1f5f9; }
    .dash-announcement:last-child { border: 0; }
    .dash-announcement-top { display: flex; justify-content: space-between; gap: .6rem; margin-bottom: .4rem; }
    .dash-announcement-category { color: #b91c1c; font-size: .71rem; font-weight: 800; }
    .dash-announcement-date { color: #94a3b8; font-size: .71rem; }
    .dash-announcement-title { color: var(--dash-ink); font-size: .84rem; font-weight: 750; }
    .dash-announcement-body { color: var(--dash-muted); font-size: .77rem; margin-top: .35rem; line-height: 1.55; }
    @media(max-width: 980px) { .dash-stats { grid-template-columns: repeat(2,minmax(0,1fr)); } .dash-content { grid-template-columns: 1fr; } }
    @media(max-width: 650px) { .dash-hero { padding: 1.5rem 1.25rem; } .dash-stats { gap: .65rem; } .dash-stat { padding: 1rem; } .dash-stat-value { font-size: 1.65rem; } .dash-actions { grid-template-columns: 1fr; gap: .7rem; } .dash-panel-header { padding: 1rem; } .dash-list { padding: 0 1rem; } }
</style>
@endpush

@section('content')
@php
    $user = auth()->user();
    $role = strtolower($user->role ?? 'mahasiswa');
    $isAdmin = $role === 'admin';
    $isDosen = $role === 'dosen';
    $isMahasiswa = $role === 'mahasiswa';
    $stats = $stats ?? [];
    $courses = $courses ?? collect();
    $upcomingAssignments = $upcomingAssignments ?? collect();
    $announcements = $announcements ?? [];

    $roleLabel = $isAdmin ? 'Administrator' : ($isDosen ? 'Dosen' : 'Mahasiswa');
    $roleDescription = $isAdmin
        ? 'Pantau aktivitas dan kelola kegiatan akademik di CeLOE LMS.'
        : ($isDosen
            ? 'Kelola mata kuliah, materi, tugas, dan pendaftaran mahasiswa.'
            : 'Akses mata kuliah, pantau tugas, dan ikuti aktivitas pembelajaran.');

    $statCards = $isAdmin ? [
        ['label' => 'Total Mata Kuliah', 'value' => $stats['total_courses'] ?? '—', 'symbol' => 'MK', 'note' => 'Katalog mata kuliah'],
        ['label' => 'Total Mahasiswa', 'value' => $stats['total_students'] ?? '—', 'symbol' => 'M', 'note' => 'Akun mahasiswa'],
        ['label' => 'Menunggu Persetujuan', 'value' => $stats['pending_approvals'] ?? '—', 'symbol' => '!', 'note' => 'Permintaan pendaftaran'],
        ['label' => 'Materi & Tugas', 'value' => isset($stats['total_materials'], $stats['total_assignments']) ? $stats['total_materials'] + $stats['total_assignments'] : '—', 'symbol' => '📚', 'note' => 'Konten pembelajaran'],
    ] : ($isDosen ? [
        ['label' => 'Mata Kuliah Diampu', 'value' => $stats['total_courses'] ?? '—', 'symbol' => 'MK', 'note' => 'Mata kuliah Anda'],
        ['label' => 'Mahasiswa Aktif', 'value' => $stats['total_students'] ?? '—', 'symbol' => 'M', 'note' => 'Dalam mata kuliah Anda'],
        ['label' => 'Menunggu Persetujuan', 'value' => $stats['pending_approvals'] ?? '—', 'symbol' => '!', 'note' => 'Permintaan pendaftaran'],
        ['label' => 'Materi & Tugas', 'value' => isset($stats['total_materials'], $stats['total_assignments']) ? $stats['total_materials'] + $stats['total_assignments'] : '—', 'symbol' => '📚', 'note' => 'Konten pembelajaran Anda'],
    ] : [
        ['label' => 'Mata Kuliah Diikuti', 'value' => $stats['enrolled_courses'] ?? '—', 'symbol' => 'MK', 'note' => 'Pendaftaran disetujui'],
        ['label' => 'Total Materi', 'value' => $stats['total_materials'] ?? '—', 'symbol' => '📚', 'note' => 'Materi perkuliahan'],
        ['label' => 'Total Tugas', 'value' => $stats['total_assignments'] ?? '—', 'symbol' => 'T', 'note' => 'Tugas tersedia'],
        ['label' => 'Tugas Dikumpulkan', 'value' => $stats['submitted_count'] ?? '—', 'symbol' => '✓', 'note' => 'Pengumpulan Anda'],
    ]);
@endphp

<div class="dash">
    <header class="dash-hero">
        <div class="dash-hero-inner">
            <div class="dash-eyebrow">CeLOE LMS · Dashboard {{ $roleLabel }}</div>
            <h1>Selamat datang, {{ $user->name }}!</h1>
            <p>{{ $roleDescription }}</p>
            <div class="dash-hero-bottom">
                <span class="dash-role">{{ $roleLabel }}</span>
                <span class="dash-semester">Semester Ganjil 2026/2027</span>
            </div>
        </div>
    </header>

    <section class="dash-stats" aria-label="Ringkasan dashboard">
        @foreach($statCards as $stat)
            <div class="dash-stat">
                <div class="dash-stat-head">
                    <span class="dash-stat-label">{{ $stat['label'] }}</span>
                    <span class="dash-stat-icon" aria-hidden="true">{{ $stat['symbol'] }}</span>
                </div>
                <div class="dash-stat-value">{{ $stat['value'] }}</div>
                <div class="dash-stat-note">{{ $stat['note'] }}</div>
            </div>
        @endforeach
    </section>

    <h2 class="dash-section-title">Akses Cepat</h2>
    <nav class="dash-actions" aria-label="Menu cepat berdasarkan role">
        @if($isAdmin)
            <a class="dash-action" href="{{ route('courses.index') }}">
                <span class="dash-action-symbol" aria-hidden="true">▦</span>
                <span><span class="dash-action-title">Kelola Mata Kuliah</span><span class="dash-action-note">Lihat katalog dan seluruh mata kuliah</span></span>
            </a>
            <a class="dash-action" href="{{ route('courses.create') }}">
                <span class="dash-action-symbol" aria-hidden="true">＋</span>
                <span><span class="dash-action-title">Tambah Mata Kuliah</span><span class="dash-action-note">Buat mata kuliah baru</span></span>
            </a>
            <a class="dash-action" href="{{ route('profile.edit') }}">
                <span class="dash-action-symbol" aria-hidden="true">◉</span>
                <span><span class="dash-action-title">Profil Administrator</span><span class="dash-action-note">Perbarui informasi akun Anda</span></span>
            </a>
        @elseif($isDosen)
            <a class="dash-action" href="{{ route('courses.index') }}">
                <span class="dash-action-symbol" aria-hidden="true">▦</span>
                <span><span class="dash-action-title">Mata Kuliah Saya</span><span class="dash-action-note">Buka mata kuliah yang diampu</span></span>
            </a>
            <a class="dash-action" href="{{ route('courses.create') }}">
                <span class="dash-action-symbol" aria-hidden="true">＋</span>
                <span><span class="dash-action-title">Buat Mata Kuliah</span><span class="dash-action-note">Tambah kelas untuk pembelajaran</span></span>
            </a>
            <a class="dash-action" href="{{ route('profile.edit') }}">
                <span class="dash-action-symbol" aria-hidden="true">◉</span>
                <span><span class="dash-action-title">Profil Dosen</span><span class="dash-action-note">Kelola data pribadi Anda</span></span>
            </a>
        @else
            <a class="dash-action" href="{{ route('courses.index') }}">
                <span class="dash-action-symbol" aria-hidden="true">⌕</span>
                <span><span class="dash-action-title">Jelajahi Mata Kuliah</span><span class="dash-action-note">Temukan dan daftar mata kuliah</span></span>
            </a>
            <a class="dash-action" href="#dashboard-tugas">
                <span class="dash-action-symbol" aria-hidden="true">☷</span>
                <span><span class="dash-action-title">Tugas Saya</span><span class="dash-action-note">Periksa tugas dan tenggat waktu</span></span>
            </a>
            <a class="dash-action" href="{{ route('profile.edit') }}">
                <span class="dash-action-symbol" aria-hidden="true">◉</span>
                <span><span class="dash-action-title">Profil Mahasiswa</span><span class="dash-action-note">Perbarui informasi akun Anda</span></span>
            </a>
        @endif
    </nav>

    <div class="dash-content">
        <section class="dash-panel" aria-label="Daftar mata kuliah">
            <div class="dash-panel-header">
                <div>
                    <h2 class="dash-panel-title">{{ $isAdmin ? 'Daftar Mata Kuliah' : ($isDosen ? 'Mata Kuliah yang Diampu' : 'Mata Kuliah Saya') }}</h2>
                    <div class="dash-panel-description">{{ $courses->count() }} mata kuliah ditampilkan</div>
                </div>
                <a class="dash-link" href="{{ route('courses.index') }}">Lihat semua &rarr;</a>
            </div>
            <div class="dash-list">
                @forelse($courses as $course)
                    <div class="dash-list-row">
                        <div style="min-width:0;">
                            <a class="dash-item-title" href="{{ route('courses.show', $course->id) }}">{{ $course->nama_mk }}</a>
                            <div class="dash-item-meta">
                                {{ $course->kode_mk }} &bull; {{ $course->sks }} SKS &bull;
                                {{ $course->dosen->name ?? 'Dosen belum ditentukan' }}
                            </div>
                            <div class="dash-item-sub">
                                {{ $course->materials_count ?? $course->materials->count() }} materi &bull;
                                {{ $course->assignments_count ?? $course->assignments->count() }} tugas
                            </div>
                        </div>
                        <a class="dash-link" href="{{ route('courses.show', $course->id) }}">Buka &rarr;</a>
                    </div>
                @empty
                    <div class="dash-empty">Belum ada mata kuliah untuk ditampilkan.</div>
                @endforelse
            </div>
        </section>

        <div>
            <section class="dash-panel" id="dashboard-tugas" aria-label="Tugas mendatang">
                <div class="dash-panel-header">
                    <div>
                        <h2 class="dash-panel-title">{{ $isMahasiswa ? 'Tugas Mendatang' : 'Pemantauan Tugas' }}</h2>
                        <div class="dash-panel-description">{{ $isMahasiswa ? 'Status dan batas waktu pengumpulan' : 'Tenggat dan aktivitas pengumpulan' }}</div>
                    </div>
                </div>
                <div class="dash-list">
                    @forelse($upcomingAssignments as $assignment)
                        @php
                            $submissionCount = $assignment->submissions_count ?? null;
                            $hasSubmitted = $isMahasiswa && $assignment->submissions->isNotEmpty();
                            $isLate = $assignment->deadline && $assignment->deadline->isPast();
                        @endphp
                        <div class="dash-list-row" style="align-items:flex-start;">
                            <div style="min-width:0;">
                                <a class="dash-item-title" href="{{ route('courses.show', $assignment->course_id) }}">{{ $assignment->judul }}</a>
                                <div class="dash-item-meta">{{ $assignment->course->nama_mk ?? 'Mata kuliah' }}</div>
                                <div class="dash-item-sub">Tenggat: {{ $assignment->deadline ? $assignment->deadline->translatedFormat('d M Y, H:i') : 'Tidak ditentukan' }}</div>
                                <div style="margin-top:.55rem;">
                                    @if($isMahasiswa)
                                        @if($hasSubmitted)
                                            <span class="dash-pill is-green">Sudah dikumpulkan</span>
                                        @elseif($isLate)
                                            <span class="dash-pill is-red">Terlewat</span>
                                        @else
                                            <span class="dash-pill is-yellow">Belum dikumpulkan</span>
                                        @endif
                                    @else
                                        <span class="dash-pill">{{ $submissionCount ?? $assignment->submissions->count() }} pengumpulan</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="dash-empty">Tidak ada tugas mendatang.</div>
                    @endforelse
                </div>
            </section>

            @if(!empty($announcements))
                <section class="dash-panel" aria-label="Pengumuman perkuliahan">
                    <div class="dash-panel-header">
                        <div>
                            <h2 class="dash-panel-title">Pengumuman Perkuliahan</h2>
                            <div class="dash-panel-description">Informasi akademik terkini</div>
                        </div>
                    </div>
                    <div class="dash-list">
                        @foreach($announcements as $announcement)
                            <article class="dash-announcement">
                                <div class="dash-announcement-top">
                                    <span class="dash-announcement-category">{{ $announcement['kategori'] ?? 'Akademik' }}</span>
                                    <span class="dash-announcement-date">{{ $announcement['tanggal'] ?? '' }}</span>
                                </div>
                                <div class="dash-announcement-title">{{ $announcement['judul'] ?? '' }}</div>
                                <div class="dash-announcement-body">{{ $announcement['isi'] ?? '' }}</div>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    </div>
</div>
@endsection
