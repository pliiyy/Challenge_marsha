@extends('apps.index')
@section('title', 'Profil')

@section('content')
    <style>
        /* Mengambil variabel warna dari master layout */
        :root {
            --primary-color: #00bcd4;
            --primary-dark: #0097a7;
            --primary-light: #4cdefa;
        }

        /* Gaya Khusus untuk Card Profil Utama */
        .profile-container {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            background-color: white;
            display: flex;
            /* Aktifkan flex untuk layout row */
        }

        /* Gaya untuk Sidebar Kiri (Info dan Foto) */
        .profile-sidebar {
            flex: 0 0 300px;
            /* Lebar tetap 300px */
            background-color: var(--primary-dark);
            color: white;
            padding: 2.5rem 1.5rem;
            text-align: center;
        }

        /* Gaya untuk Konten Form Kanan */
        .profile-content {
            flex: 1;
            /* Ambil sisa lebar */
            padding: 2.5rem;
        }

        /* Gaya untuk Label Info */
        .info-label {
            font-weight: 600;
            color: var(--primary-dark);
            display: block;
            margin-bottom: 0.25rem !important;
        }

        /* Gaya tombol */
        .btn-primary {
            background-color: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark) !important;
            border-color: var(--primary-dark) !important;
        }
    </style>

    <div class="col-lg-10 col-md-9 p-4">
        <h2 class="fw-bold mb-4" style="color: var(--primary-dark);">Pengaturan Profil Saya</h2>

        <div class="profile-container mx-auto" style="max-width: 1000px;">

            {{-- KOLOM KIRI: SIDEBAR FOTO & INFO ID AKUN --}}
            <div class="profile-sidebar">

                {{-- Bagian Upload Foto (Form TETAP SAMA) --}}
                <form id="formUploadFoto" method="POST" action="{{ route('profil.updateFoto') }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="file" name="foto" id="fotoInput" accept="image/*" style="display: none;"
                        onchange="previewFoto(event)">

                    <div class="d-inline-block mb-3 position-relative" style="cursor: pointer;"
                        onclick="document.getElementById('fotoInput').click()">
                        <div id="previewContainer"
                            class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto"
                            style="width: 120px; height: 120px; border: 4px solid #fff; overflow: hidden; box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.5);">
                            @if (auth()->user()->biodata && auth()->user()->biodata->foto_profil)
                                <img id="fotoPreview"
                                    src="{{ asset('storage/foto_profil/' . auth()->user()->biodata->foto_profil) }}"
                                    class="w-100 h-100" style="object-fit: cover;">
                            @else
                                <i id="iconDefault" class="bi bi-person text-muted" style="font-size: 60px;"></i>
                            @endif
                        </div>
                        <div class="position-absolute bottom-0 end-0 translate-middle p-2 bg-white rounded-circle border shadow-sm"
                            style="width: 36px; height: 36px; border-color: var(--primary-light) !important;">
                            <i class="bi bi-camera-fill text-primary"></i>
                        </div>
                    </div>
                </form>

                <h4 class="mb-1 fw-bold text-white">{{ auth()->user()->Biodata->nama ?? 'Nama Pengguna' }}</h4>
                <p class="text-white-50 mb-4 small">{{ auth()->user()->email ?? 'email@contoh.com' }}</p>

                <div class="p-3 rounded" style="background-color: rgba(255, 255, 255, 0.1);">
                    <p class="text-uppercase fw-semibold mb-1 small text-white-50">Role Akun</p>
                    <span class="badge rounded-pill text-uppercase fw-semibold px-3 py-2 bg-light"
                        style="color: var(--primary-dark) !important;">
                        {{ auth()->user()->dekan ? 'DEKAN' : '' }}
                        {{ auth()->user()->dosen ? 'DOSEN' : '' }}
                        {{ auth()->user()->mahasiswa ? 'MAHASISWA' : '' }}
                        {{ auth()->user()->kaprodi ? 'KAPRODI' : '' }}
                        {{ auth()->user()->sekprodi ? 'SEKPRODI' : '' }}
                        {{ auth()->user()->kosma ? 'KOSMA' : '' }}
                    </span>

                    <p class="text-uppercase fw-semibold mt-3 mb-1 small text-white-50">ID Akun</p>
                    <h5 class="fw-bold text-white">
                        @if (auth()->user()->mahasiswa)
                            {{ auth()->user()->mahasiswa->nim ?? 'NIM Tidak Ada' }}
                        @elseif(auth()->user()->dekan)
                            {{ auth()->user()->dekan->nidn ?? 'NIDN Tidak Ada' }}
                        @elseif(auth()->user()->dosen)
                            {{ auth()->user()->dosen->nidn ?? 'NIDN Tidak Ada' }}
                        @elseif(auth()->user()->kaprodi)
                            {{ auth()->user()->kaprodi->nidn ?? 'NIDN Tidak Ada' }}
                        @elseif(auth()->user()->sekprodi)
                            {{ auth()->user()->sekprodi->nidn ?? 'NIDN Tidak Ada' }}
                        @else
                            ID Tidak Dikenal
                        @endif
                    </h5>
                </div>
            </div>

            {{-- KOLOM KANAN: FORMULIR DETAIL --}}
            <div class="profile-content">
                <h4 class="mb-4 fw-semibold" style="color: var(--primary-dark); padding-bottom: 5px;">
                    <i class="bi bi-person-lines-fill me-2"></i> Edit Data Pribadi
                </h4>

                {{-- Form Body (TIDAK BERUBAH) --}}
                <form method="POST" action="/profil">
                    @csrf
                    @method('PUT')
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="info-label mb-1">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control"
                                value="{{ auth()->user()->Biodata->nama ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="info-label mb-1">Email</label>
                            <input type="email" name="email" class="form-control"
                                value="{{ auth()->user()->email ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="info-label mb-1">No. Telepon</label>
                            <input type="text" name="no_telepon" class="form-control" placeholder="0812-3456-7890"
                                value="{{ auth()->user()->no_telepon ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="info-label mb-1">Alamat</label>
                            <input type="text" name="alamat" class="form-control" placeholder="Jl. Mawar No. 123"
                                value="{{ auth()->user()->Biodata->alamat ?? '' }}">
                        </div>
                        <div class="col-md-12 mt-3">
                            <label class="info-label mb-1">Tentang Saya</label>
                            <textarea class="form-control" rows="4" placeholder="Tuliskan sesuatu tentang dirimu..." name="keterangan">{{ auth()->user()->Biodata->keterangan ?? '' }}</textarea>
                        </div>
                    </div>

                    <div class="mt-5 pt-3 border-top text-end">
                        <button type="submit" class="btn btn-primary px-4 fw-semibold">
                            <i class="bi bi-save me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script preview foto (TIDAK BERUBAH) --}}
    <script>
        function previewFoto(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    let previewContainer = document.getElementById('previewContainer');
                    previewContainer.innerHTML =
                        `<img id="fotoPreview" src="${e.target.result}" class="w-100 h-100" style="object-fit: cover;">`;
                    document.getElementById('formUploadFoto').submit(); // auto submit setelah pilih foto
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
@endsection
