@extends('apps.index')
@section('title', 'Data')

@section('content')
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa - Aplikasi Kampus</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

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
            min-height: 100vh;
            color: white;
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
    </style>
</head>

<body>

    <nav class="navbar navbar-dark">
        <div class="container-fluid px-4">
            <span class="navbar-brand">Aplikasi Kampus</span>
            <div class="d-flex align-items-center gap-3">
                <span class="text-white small">Halo, {{ auth()->user()->name ?? 'Admin' }} 👋</span>
                <form method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-light">Keluar</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-2 col-md-3 p-3 sidebar">
                <nav class="nav flex-column">
                    <a href="#" class="nav-link"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
                    <a href="#" class="nav-link"><i class="bi bi-person me-2"></i> Profil</a>
                    <a href="#" class="nav-link"><i class="bi bi-building me-2"></i> Data Fakultas</a>
                    <a href="#" class="nav-link"><i class="bi bi-mortarboard me-2"></i> Data Prodi</a>
                    <a href="#" class="nav-link"><i class="bi bi-person-gear me-2"></i> Data Role</a>
                    <a href="#" class="nav-link"><i class="bi bi-calendar3 me-2"></i> Data Semester</a>
                    <a href="#" class="nav-link"><i class="bi bi-journal-bookmark me-2"></i> Data Mata Kuliah</a>
                    <a href="#" class="nav-link"><i class="bi bi-clock-history me-2"></i> Data Jadwal</a>
                    <a href="#" class="nav-link"><i class="bi bi-door-closed me-2"></i> Data Ruangan</a>
                    <a href="#" class="nav-link"><i class="bi bi-people me-2"></i> Data Kelas</a>
                    <a href="#" class="nav-link active"><i class="bi bi-person-lines-fill me-2"></i> Data
                        Mahasiswa</a>
                    <a href="#" class="nav-link"><i class="bi bi-gear me-2"></i> Pengaturan</a>
                </nav>
            </div>

            <!-- Content -->
            <div class="col-lg-10 col-md-9 content">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>🎓 Data Mahasiswa</span>
                        <button class="btn btn-light btn-sm text-primary fw-semibold" data-bs-toggle="modal"
                            data-bs-target="#addMahasiswaModal" onclick="Add()">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Mahasiswa
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>NIM</th>
                                        <th>Nama Mahasiswa</th>
                                        <th>Program Studi</th>
                                        <th>Angkatan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>230101001</td>
                                        <td>Budi Santoso</td>
                                        <td>Teknik Informatika</td>
                                        <td>2023</td>
                                        <td><span class="badge bg-success">Aktif</span></td>
                                        <td>
                                            <button class="btn btn-outline-primary btn-sm"><i
                                                    class="bi bi-pencil"></i></button>
                                            <button class="btn btn-outline-danger btn-sm"><i
                                                    class="bi bi-trash"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>230101002</td>
                                        <td>Siti Aminah</td>
                                        <td>Sistem Informasi</td>
                                        <td>2022</td>
                                        <td><span class="badge bg-secondary">Tidak Aktif</span></td>
                                        <td>
                                            <button class="btn btn-outline-primary btn-sm"><i
                                                    class="bi bi-pencil"></i></button>
                                            <button class="btn btn-outline-danger btn-sm"><i
                                                    class="bi bi-trash"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="mt-4 alert alert-info bg-opacity-25 border-0 text-primary">
                    <i class="bi bi-info-circle me-2"></i>
                    Kelola data mahasiswa sesuai program studi dan status akademik.
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Mahasiswa -->
    <div class="modal fade" id="addMahasiswaModal" tabindex="-1" aria-labelledby="addMahasiswaModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addMahasiswaModalLabel">Tambah Mahasiswa Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">NIM</label>
                        <input type="text" class="form-control" placeholder="Masukkan NIM">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Mahasiswa</label>
                        <input type="text" class="form-control" placeholder="Masukkan Nama Mahasiswa">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Program Studi</label>
                        <select class="form-select">
                            <option>Teknik Informatika</option>
                            <option>Sistem Informasi</option>
                            <option>Manajemen Informatika</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Semester</label>
                        <input type="number" class="form-control" placeholder="Contoh: 2023">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Angkatan</label>
                        <input type="text" class="form-control" placeholder="">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Agama</label>
                        <input type="text" class="form-control" placeholder="">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gender</label>
                        <input type="text" class="form-control" placeholder="">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Provinsi</label>
                        <select class="form-select" id="provinsi" onchange="RubahProvinsi(this.value)">
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kota/Kabupaten</label>
                        <select class="form-select" id="kota" onchange="RubahKota(this.value)">
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">kecamatan</label>
                        <select class="form-select" id="kecamatan">
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Desa</label>
                        <input type="text" class="form-control" placeholder="">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea class="form-control" placeholder=""></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function Add() {
            fetch("/api/provinces").then(res => res.json()).then(res => {
                const data = res.data;
                const temp = data.map((d) => `
                        <option  value="${d.code}" >${d.name}</option>
            `);
                console.log({
                    temp
                })
                document.getElementById("provinsi").innerHTML = temp.join("");
            })
        }

        function RubahProvinsi(id) {
            fetch(`/api/regencies/${id}`).then(res => res.json()).then(res => {
                const data = res.data;
                const temp = data.map((d) => `
                        <option value="${d.code}" >${d.name}</option>
            `);
                console.log({
                    data,
                    temp,
                    hasil: temp.join("")
                });
                document.getElementById("kota").innerHTML = temp.join("");
            })
        }

        function RubahKota(id) {
            fetch(`/api/districts/${id}`).then(res => res.json()).then(res => {
                const data = res.data;
                const temp = data.map((d) => `
                        <option value="${d.code}" >${d.name}</option>
            `);
                console.log({
                    data,
                    temp,
                    hasil: temp.join("")
                });
                document.getElementById("kecamatan").innerHTML = temp.join("");
            })
        }
    </script>
</body>

</html>
