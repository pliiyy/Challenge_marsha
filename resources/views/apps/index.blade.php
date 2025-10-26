<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My App')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>


    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7ff;
        }

        .navbar {
            background: linear-gradient(135deg, #4f46e5, #3b82f6);
        }

        .sidebar {
            background: #4f46e5;
            height: 92vh;
            overflow-y: auto;
            /* FIXED: Hapus tanda kutip */
            color: white;
            position: sticky;
            top: 0;
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
            color: #d1d5db;
            border-radius: 8px;
            margin-bottom: 6px;
        }

        .sidebar .nav-link.active,
        .sidebar .nav-link:hover {
            background-color: #6366f1;
            color: #fff;
        }

        .content {
            padding: 30px;
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
        }

        .card-header {
            background: linear-gradient(135deg, #4f46e5, #3b82f6);
            color: white;
            font-weight: 600;
            border-top-left-radius: 16px !important;
            border-top-right-radius: 16px !important;
        }

        .btn-primary {
            background-color: #4f46e5;
            border: none;
        }

        .btn-primary:hover {
            background-color: #4338ca;
        }

        .table thead {
            background-color: #eef2ff;
            color: #4f46e5;
        }

        .profile-card {
            background: #fff;
            border: none;
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .profile-header {
            background: linear-gradient(135deg, #4f46e5, #3b82f6);
            color: white;
            padding: 40px 20px;
            text-align: center;
        }

        .profile-header img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid white;
            margin-bottom: 15px;
        }

        .profile-header h4 {
            font-weight: 600;
        }

        .info-label {
            font-weight: 500;
            color: #4f46e5;
        }
    </style>
</head>

<body>
    {{-- SweetAlert Notifikasi --}}
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
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
                title: 'Oops...',
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
                title: 'Validasi Gagal!',
                html: errorMessages,
                confirmButtonText: 'OK',
                confirmButtonColor: '#d33'
            })
        </script>
    @endif

    <nav class="navbar navbar-dark">
        <div class="container-fluid px-4">
            <span class="navbar-brand">BEVARARI</span>
            <div class="d-flex align-items-center gap-3">
                <span class="text-white small">Halo, {{ auth()->user()->biodata->nama ?? 'Admin' }} 👋</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    @method('POST')
                    <button type="submit" class="btn btn-sm btn-outline-light">Keluar</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-2 col-md-3 p-3 sidebar border-end">
                <nav class="nav flex-column">
                    <!-- Dashboard -->
                    <a href="/dashboard" class="nav-link">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>

                    <!-- Profil -->
                    <a href="profil" class="nav-link">
                        <i class="bi bi-person me-2"></i> Profil
                    </a>

                    <!-- Data Master (Nested Menu) -->
                    <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse"
                        href="#dataMaster" role="button" aria-expanded="false" aria-controls="dataMaster">
                        <span><i class="bi bi-folder2-open me-2"></i> Data</span>
                        <i class="bi bi-chevron-down small"></i>
                    </a>
                    <div class="collapse ps-3" id="dataMaster">
                        <a href="/fakultas" class="nav-link"><i class="bi bi-building me-2"></i> Fakultas</a>
                        <a href="/prodi" class="nav-link"><i class="bi bi-mortarboard me-2"></i> Prodi</a>
                        <a href="/semester" class="nav-link"><i class="bi bi-calendar3 me-2"></i> Semester</a>
                        {{-- <a href="/role" class="nav-link"><i class="bi bi-person-gear me-2"></i> Role</a> --}}
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
                    <a href="/jadwal" class="nav-link"><i class="bi bi-clock-history me-2"></i>
                        Buat Jadwal</a>
                    <a href="/jadwal_global" class="nav-link"><i class="bi bi-clock-history me-2"></i>
                        Jadwal</a>
                    <a href="/pindah_jadwal" class="nav-link"><i class="bi bi-clock-history me-2"></i> Pindah Jadwal</a>
                    <a href="/barter_jadwal" class="nav-link">
                        <i class="bi bi-gear me-2"></i> Barter Jadwal
                    </a>
                    <a href="/surat_tugas" class="nav-link">
                        <i class="bi bi-gear me-2"></i> Surat Tugas Mengajar
                    </a>
                    <a href="/data" class="nav-link">
                        <i class="bi bi-person me-2"></i> Data Pengguna
                    </a>
                    <!-- Pengaturan -->
                    <a href="/settings" class="nav-link">
                        <i class="bi bi-gear me-2"></i> Pengaturan
                    </a>
                </nav>
            </div>

            <!-- Content -->
            @yield('content')
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
