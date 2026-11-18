
@extends('layouts.app')

@section('title', 'Tambah Mata Kuliah Baru')

@section('content')
<div style="max-width: 760px; margin: 0 auto;">

    {{-- Tombol Kembali --}}
    <div style="margin-bottom: 1.25rem;">
        <a href="{{ route('courses.index') }}" class="btn btn-secondary btn-sm">
            &larr; Kembali ke Daftar Mata Kuliah
        </a>
    </div>

    <div class="card">

        {{-- Header --}}
        <div class="card-header">
            <div>
                <h1 class="card-title">Tambah Mata Kuliah Baru</h1>
                <p class="card-subtitle">
                    Lengkapi informasi mata kuliah yang akan Anda ampu pada semester aktif ini.
                </p>
            </div>
        </div>

        <div class="card-body">
            <form action="{{ route('courses.store') }}" method="POST">
                @csrf

                {{-- TODO 6: Buat input untuk data mata kuliah. --}}
                {{-- Hint:
                    - Buat field untuk kode MK, nama MK, SKS, dan semester.
                    - Gunakan jenis elemen form yang sesuai dengan tiap data.
                    - Sesuaikan atribut name dengan nama field yang dibaca controller.
                    - Gunakan old() agar isian tidak hilang setelah validasi gagal.
                    - Tampilkan pesan error validasi menggunakan directive Blade.
                    - Pola umum input teks: <input type="text" name="nama_field">
                --}}

                <div style="margin-bottom: 1.5rem;">
                    <h3 style="font-size: 1rem; font-weight: 700; margin-bottom: 1rem;">
                        Informasi Mata Kuliah
                    </h3>

                    <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1.25rem;">

                        {{-- Kode Mata Kuliah --}}
                        <div class="form-group">
                            <label for="kode_mk" class="form-label">
                                Kode Mata Kuliah
                            </label>
                            <input
                                type="text"
                                name="kode_mk"
                                id="kode_mk"
                                class="form-control @error('kode_mk') is-invalid @enderror"
                                value="{{ old('kode_mk') }}"
                                placeholder="Contoh: IF101"
                                maxlength="20"
                                required
                            >
                            @error('kode_mk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Nama Mata Kuliah --}}
                        <div class="form-group">
                            <label for="nama_mk" class="form-label">
                                Nama Mata Kuliah
                            </label>
                            <input
                                type="text"
                                name="nama_mk"
                                id="nama_mk"
                                class="form-control @error('nama_mk') is-invalid @enderror"
                                value="{{ old('nama_mk') }}"
                                placeholder="Contoh: Pemrograman Web"
                                required
                            >
                            @error('nama_mk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Jumlah SKS --}}
                        <div class="form-group">
                            <label for="sks" class="form-label">
                                Jumlah SKS
                            </label>
                            <input
                                type="number"
                                name="sks"
                                id="sks"
                                class="form-control @error('sks') is-invalid @enderror"
                                value="{{ old('sks') }}"
                                min="1"
                                max="6"
                                placeholder="Contoh: 3"
                                required
                            >
                            @error('sks')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Semester --}}
                        <div class="form-group">
                            <label for="semester" class="form-label">
                                Semester
                            </label>
                            <select
                                name="semester"
                                id="semester"
                                class="form-control @error('semester') is-invalid @enderror"
                                required
                            >
                                <option value="">Pilih Semester</option>
                                @for ($i = 1; $i <= 8; $i++)
                                    <option
                                        value="{{ $i }}"
                                        {{ old('semester') == $i ? 'selected' : '' }}
                                    >
                                        Semester {{ $i }}
                                    </option>
                                @endfor
                            </select>
                            @error('semester')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- Dosen Pengampu (Otomatis akun Dosen yang login) --}}
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label">
                        Dosen Pengampu Kelas
                    </label>

                    <div style="
                        display: flex;
                        align-items: center;
                        gap: 0.75rem;
                        padding: 1rem;
                        background-color: var(--slate-50);
                        border: 1px solid var(--slate-200);
                        border-radius: 8px;
                    ">
                        <div style="
                            width: 42px;
                            height: 42px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            background-color: var(--slate-200);
                            border-radius: 50%;
                            font-weight: 700;
                            color: var(--slate-800);
                        ">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>

                        <div>
                            <div style="font-weight: 600; color: var(--slate-800);">
                                {{ Auth::user()->name }}
                            </div>
                            <div style="font-size: 0.8rem; color: var(--slate-500);">
                                {{ Auth::user()->nomor_induk ?: Auth::user()->email }}
                            </div>
                        </div>
                    </div>

                    <div class="form-help">
                        Mata kuliah ini akan otomatis terhubung dengan akun dosen yang sedang login.
                    </div>
                </div>

                {{-- Deskripsi / Silabus --}}
                <div class="form-group">
                    <label for="deskripsi" class="form-label">
                        Silabus / Deskripsi Mata Kuliah
                    </label>

                    <textarea
                        name="deskripsi"
                        id="deskripsi"
                        rows="5"
                        class="form-control @error('deskripsi') is-invalid @enderror"
                        placeholder="Tuliskan ringkasan capaian pembelajaran, silabus, dan materi yang akan dipelajari..."
                    >{{ old('deskripsi') }}</textarea>

                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <div class="form-help">
                        Jelaskan secara singkat gambaran umum dan materi pembelajaran mata kuliah.
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div style="
                    display: flex;
                    justify-content: flex-end;
                    flex-wrap: wrap;
                    gap: 0.75rem;
                    margin-top: 1.5rem;
                    padding-top: 1.25rem;
                    border-top: 1px solid var(--slate-200);
                ">
                    <a href="{{ route('courses.index') }}" class="btn btn-secondary">
                        Batal
                    </a>

                    <button type="submit" class="btn btn-primary">
                        Simpan Mata Kuliah
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
