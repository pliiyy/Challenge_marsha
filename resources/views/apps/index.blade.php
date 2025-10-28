<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Web Masrha')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>


    <style>
        /* Definisi Warna Biru Muda/Cyan */
        :root {
            --primary-color: #00bcd4;
            /* Cyan 500 */
            --primary-dark: #0097a7;
            /* Cyan 700 */
            --primary-light: #4cdefa;
            /* Cyan 300 */
            --bg-gradient: linear-gradient(135deg, var(--primary-light), var(--primary-dark));
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #e0f7fa;
            /* Background halaman yang lebih terang */
        }

        .navbar {
            background: var(--bg-gradient);
            /* Navbar menggunakan gradient biru muda */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar {
            background: var(--primary-dark);
            /* Sidebar menggunakan warna gelap dari palet biru */
            height: 92vh;
            overflow-y: auto;
            color: white;
            position: sticky;
            top: 0;
            transition: width 0.3s ease;
        }

        /* Custom Scrollbar untuk Sidebar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        .sidebar .nav-link {
            color: #e0f7fa;
            /* Warna teks link lebih terang */
            border-radius: 8px;
            margin-bottom: 6px;
            transition: all 0.2s;
        }

        .sidebar .nav-link.active,
        .sidebar .nav-link:hover {
            background-color: var(--primary-color);
            /* Warna hover/active yang lebih cerah */
            color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .content {
            padding: 30px;
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            /* Bayangan sedikit diperkuat */
        }

        .card-header {
            background: var(--bg-gradient);
            /* Card header menggunakan gradient */
            color: white;
            font-weight: 600;
            border-top-left-radius: 16px !important;
            border-top-right-radius: 16px !important;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
        }

        .btn-outline-light {
            color: #fff !important;
            border-color: #fff !important;
            transition: all 0.2s;
        }

        .btn-outline-light:hover {
            background-color: var(--primary-light) !important;
            color: var(--primary-dark) !important;
        }

        .table thead {
            background-color: #b2ebf2;
            /* Warna header tabel biru muda */
            color: var(--primary-dark);
        }

        .profile-header {
            background: var(--bg-gradient);
            color: white;
            padding: 40px 20px;
            text-align: center;
        }

        .info-label {
            font-weight: 500;
            color: var(--primary-dark);
            /* Label informasi menggunakan warna biru gelap */
        }
    </style>
</head>

<body>
    {{-- SweetAlert Notifikasi (Tetap) --}}
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil! 🎉',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 2000
            })
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops... 😟',
                text: '{{ session('error') }}',
                showConfirmButton: false,
                timer: 2500
            })
        </script>
    @endif

    @if ($errors->any())
        <script>
            let errorMessages = `{!! implode('<br>', $errors->all()) !!}`;
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal! 🚨',
                html: errorMessages,
                confirmButtonText: 'OK',
                confirmButtonColor: '#d33'
            })
        </script>
    @endif

    {{-- <nav class="navbar navbar-dark">
        <div class="container-fluid px-4">
            <span class="navbar-brand fw-bold">MARSHA APP</span>
            <div class="d-flex align-items-center gap-3 text-white small">
                <span class="">Halo, {{ auth()->user()->biodata->nama ?? 'Admin' }}</span>
                <a href="profil" class="nav-link">
                    <i class="bi bi-person me-2"></i> Profil
                </a>
            </div>
        </div>
    </nav> --}}
    {{-- NAV BAR BARU (TATA LETAK BERBEDA) --}}
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid px-4">
            {{-- Kelompok Kiri: Brand & Profil (Pindah ke Kiri) --}}
            <div class="d-flex align-items-center gap-3">
                <span class="navbar-brand fw-bold">MARSHA <span class="text-primary-color">APP</span></span>
            </div>

            {{-- Kelompok Kanan: Notifikasi dan Info User (Pindah ke Kanan) --}}
            <div class="d-flex align-items-center gap-3 user-info-area">
                {{-- Notifikasi (Ikon Fiktif) --}}
                <button class="btn btn-sm btn-light position-relative d-none d-md-block">
                    <i class="bi bi-bell"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{ $total??"0" }}
                        <span class="visually-hidden">unread messages</span>
                    </span>
                </button>

                {{-- Hanya Tampilkan Nama di Kanan --}}
                <span class="d-none d-md-inline small text-dark">
                    <i class="bi bi-person-circle me-1"></i> {{ auth()->user()->biodata->nama ?? 'Admin' }}
                </span>

                {{-- Hamburger/Offcanvas Toggle (Opsional jika ingin menu mobile) --}}
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </div>
    </nav>
    {{-- AKHIR NAV BAR BARU --}}

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-2 col-md-3 p-3 sidebar border-end">
                <nav class="nav flex-column">
                    <a href="/dashboard" class="nav-link active">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>

                    <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse"
                        href="#dataMaster" role="button" aria-expanded="false" aria-controls="dataMaster">
                        <span><i class="bi bi-folder2-open me-2"></i> Data Master</span>
                        <i class="bi bi-chevron-down small"></i>
                    </a>
                    <div class="collapse ps-3" id="dataMaster">
                        <a href="/fakultas" class="nav-link"><i class="bi bi-building me-2"></i> Fakultas</a>
                        <a href="/prodi" class="nav-link"><i class="bi bi-mortarboard me-2"></i> Prodi</a>
                        <a href="/semester" class="nav-link"><i class="bi bi-calendar3 me-2"></i> Semester</a>
                        <a href="/matakuliah" class="nav-link"><i class="bi bi-journal-bookmark me-2"></i> Mata
                            Kuliah</a>
                        @if (!auth()->user()->mahasiswa)
                            <a href="/shift" class="nav-link"><i class="bi bi-person-lines-fill me-2"></i> Shift</a>
                        @endif
                        <a href="/kaprodi" class="nav-link"><i class="bi bi-person-lines-fill me-2"></i> Kaprodi</a>
                        <a href="/dosen" class="nav-link"><i class="bi bi-person-lines-fill me-2"></i> Dosen</a>
                        <a href="/sekprodi" class="nav-link"><i class="bi bi-person-lines-fill me-2"></i>
                            Sekprodi</a>
                        @if (!auth()->user()->mahasiswa)
                            <a href="/angkatan" class="nav-link"><i class="bi bi-person-lines-fill me-2"></i>
                                Angkatan</a>
                            <a href="/kelas" class="nav-link"><i class="bi bi-people me-2"></i> Kelas</a>
                        @endif
                        <a href="/ruangan" class="nav-link"><i class="bi bi-door-closed me-2"></i> Ruangan</a>
                        <a href="/kosma" class="nav-link"><i class="bi bi-person-lines-fill me-2"></i> Kosma</a>
                        <a href="/mahasiswa" class="nav-link"><i class="bi bi-person-lines-fill me-2"></i>
                            Mahasiswa</a>
                        <a href="/dekan" class="nav-link"><i class="bi bi-person-lines-fill me-2"></i>
                            Dekan</a>
                    </div>

                    <h6 class="text-white-50 small mt-3 mb-1 ms-3">JADWAL</h6>
                    <a href="/jadwal" class="nav-link"><i class="bi bi-calendar-plus me-2"></i>
                        Buat Jadwal</a>
                    <a href="/jadwal_global" class="nav-link"><i class="bi bi-calendar-check me-2"></i>
                        Jadwal Global</a>
                    <a href="/pindah_jadwal" class="nav-link"><i class="bi bi-arrow-left-right me-2"></i> Pindah
                        Jadwal</a>
                    <a href="/barter_jadwal" class="nav-link">
                        <i class="bi bi-arrow-repeat me-2"></i> Barter Jadwal
                    </a>
                    <a href="/surat_tugas" class="nav-link">
                        <i class="bi bi-file-earmark-text me-2"></i> Surat Tugas
                    </a>

                    <h6 class="text-white-50 small mt-3 mb-1 ms-3">ADMINISTRASI</h6>
                    <a href="/data" class="nav-link">
                        <i class="bi bi-people-fill me-2"></i> Data Pengguna
                    </a>
                    <a href="profil" class="nav-link profile-link d-none d-sm-flex">
                    <i class="bi bi-person me-1"></i> Profil
                </a>
                    <a href="/settings" class="nav-link">
                        <i class="bi bi-gear me-2"></i> Pengaturan Aplikasi
                    </a>
                </nav>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    @method('POST')
                    <button type="submit" class="btn btn-sm btn-outline-light fw-semibold">Keluar</button>
                </form>
            </div>

            @yield('content')
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
