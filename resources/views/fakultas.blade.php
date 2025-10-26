@extends('apps.index')
@section('title', 'Fakultas')

@section('content')
    <style>
        /* Variabel warna berdasarkan tema umum */
        :root {
            --primary-color: #0d6efd;
            /* Warna Biru Utama Bootstrap */
            --primary-dark: #0a58ca;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --warning-color: #ffc107;
        }

        /* Gaya Card Utama */
        .main-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        /* Gaya Header Card yang Modern */
        .main-card .card-header {
            background-color: #f8f9fa;
            /* Latar belakang abu muda */
            border-bottom: 1px solid #dee2e6;
            padding: 1.5rem 1.5rem;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }

        /* Gaya Tombol Aksi */
        .btn-action-group .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
            border-radius: 6px;
        }

        /* Gaya Input Filter */
        .form-control-sm,
        .form-select-sm {
            border-radius: 6px;
            border-color: #ced4da;
        }

        /* Gaya Tabel */
        .table {
            margin-bottom: 0;
        }

        .table thead th {
            font-weight: 600;
            color: var(--secondary-color);
            background-color: #e9ecef;
            /* Header tabel abu-abu gelap */
            border-bottom: 2px solid #dee2e6;
            vertical-align: middle;
        }

        .table-hover tbody tr:hover {
            background-color: #f1f1f1;
        }
    </style>

    <div class="col-md-9 col-lg-10 content">
        <h2 class="fw-bold mb-4" style="color: var(--primary-dark);"><i class="bi bi-bank me-2"></i>Manajemen Data Fakultas
        </h2>

        <div class="card main-card">

            {{-- HEADER DAN FILTER --}}
            <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <h5 class="mb-3 mb-md-0 fw-bold" style="color: var(--primary-dark);">Daftar Fakultas</h5>

                <div class="d-flex flex-column flex-sm-row gap-2 w-100 w-md-auto">
                    {{-- Form Filter --}}
                    <form action="/fakultas" method="GET"
                        class="d-flex gap-2 align-items-center flex-grow-1 flex-md-grow-0">
                        <input type="text" name="search" class="form-control form-control-sm"
                            placeholder="Cari nama atau kode..." value="{{ request('search') }}" style="min-width: 150px;">

                        <select name="status" class="form-select form-select-sm" style="min-width: 120px;">
                            <option value="">-- Status --</option>
                            <option value="AKTIF" {{ request('status') == 'AKTIF' ? 'selected' : '' }}>Aktif</option>
                            <option value="NONAKTIF" {{ request('status') == 'NONAKTIF' ? 'selected' : '' }}>Nonaktif
                            </option>
                        </select>

                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-funnel"></i>
                        </button>
                    </form>

                    {{-- Tombol Tambah --}}
                    @if (auth()->user()->dekan || auth()->user()->kaprodi || auth()->user()->sekprodi)
                        <button class="btn btn-primary btn-sm fw-semibold" data-bs-toggle="modal"
                            data-bs-target="#addKelasModal" style="white-space: nowrap;">
                            <i class="bi bi-plus-lg me-1"></i> Tambah Fakultas
                        </button>
                    @endif
                </div>
            </div>

            {{-- TABEL DATA --}}
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr class="text-center">
                                <th style="width: 50px;">#</th>
                                <th class="text-start" style="min-width: 250px;">Nama Fakultas</th>
                                <th style="width: 100px;">Kode</th>
                                <th style="min-width: 150px;">Dekan</th>
                                <th style="width: 120px;">Jml. Prodi</th>
                                <th style="width: 100px;">Status</th>
                                <th style="width: 120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @foreach ($fakultas as $index => $kls)
                                <tr>
                                    <td>{{ $fakultas->firstItem() + $index }}</td>
                                    <td class="text-start fw-semibold">{{ $kls->nama }}</td>
                                    <td>{{ $kls->kode }}</td>
                                    <td>
                                        <span
                                            class="text-muted small">{{ $kls->dekan?->User?->biodata?->nama ?? '— Belum Ditentukan —' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $kls->prodi->count() }}</span>
                                    </td>
                                    <td>
                                        @if ($kls->status == 'AKTIF')
                                            <span
                                                class="badge rounded-pill bg-success-subtle text-success fw-bold">{{ ucfirst(strtolower($kls->status)) }}</span>
                                        @else
                                            <span
                                                class="badge rounded-pill bg-danger-subtle text-danger fw-bold">{{ ucfirst(strtolower($kls->status)) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-action-group">
                                            @if (auth()->user()->dekan || auth()->user()->kaprodi || auth()->user()->sekprodi)
                                                {{-- Tombol Edit: Memicu modal dan mengirim data role ke fungsi JS/data attributes (FUNGSI TETAP SAMA) --}}
                                                <button type="button" class="btn btn-outline-primary btn-sm btn-edit"
                                                    data-bs-toggle="modal" data-bs-target="#editRoleModal"
                                                    data-id="{{ $kls->id }}" data-nama="{{ $kls->nama }}"
                                                    data-kode="{{ $kls->kode }}" data-dekan_id="{{ $kls->dekan_id }}">
                                                    <i class="bi bi-pencil"></i>
                                                </button>

                                                {{-- Tombol Delete: Memicu modal konfirmasi hapus (FUNGSI TETAP SAMA) --}}
                                                <button type="button" class="btn btn-outline-danger btn-sm btn-delete"
                                                    data-bs-toggle="modal" data-bs-target="#deleteRoleModal"
                                                    data-id="{{ $kls->id }}" data-nama="{{ $kls->nama }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    {{-- PAGINASI --}}
    <div class="col-12 mt-3 d-flex justify-content-end">
        {{ $fakultas->links() }}
    </div>

    {{-- MODAL TAMBAH FAKULTAS (TIDAK BERUBAH) --}}
    <div class="modal fade" id="addKelasModal" tabindex="-1" aria-labelledby="addKelasModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" action="/fakultas" method="POST">
                @csrf
                @method('post')
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addKelasModalLabel">Tambah Fakultas Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Fakultas</label>
                        <input type="text" class="form-control" placeholder="Contoh: Komputer" name="nama">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kode</label>
                        <input class="form-control" placeholder="Contoh: FKOM" name="kode">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Dekan</label>
                        <select class="form-select" name="dekan_id">
                            <option value="">-- Pilih Dekan --</option>
                            @foreach ($dekan as $index => $kls)
                                <option value="{{ $kls->id }}">{{ $kls->user->biodata->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Fakultas</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL EDIT FAKULTAS (TIDAK BERUBAH) --}}
    <div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" id="editRoleForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editRoleModalLabel">Edit Fakultas: <span id="edit-role-name"
                            class="fw-bold"></span></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit-id">

                    <div class="mb-3">
                        <label for="edit-nama" class="form-label">Nama Fakultas</label>
                        <input type="text" class="form-control" id="edit-nama" name="nama" required />
                    </div>
                    <div class="mb-3">
                        <label for="edit-kode" class="form-label">Kode</label>
                        <input class="form-control" id="edit-kode" name="kode">
                    </div>
                    <div class="mb-3">
                        <label for="edit-dekan_id" class="form-label">Dekan</label>
                        <select class="form-select" name="dekan_id" id="edit-dekan_id">
                            <option value="">-- Pilih Dekan --</option>
                            @foreach ($dekan as $index => $kls)
                                <option value="{{ $kls->id }}">{{ $kls->user->biodata->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>


    {{-- MODAL HAPUS FAKULTAS (TIDAK BERUBAH) --}}
    <div class="modal fade" id="deleteRoleModal" tabindex="-1" aria-labelledby="deleteRoleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <form class="modal-content" id="deleteRoleForm" action="/fakultas" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteRoleModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus Fakultas **<span id="delete-role-name" class="fw-bold"></span>**?
                    </p>
                    <input type="hidden" name="id" id="delete-id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Hapus Fakultas Ini</button>
                </div>
            </form>
        </div>
    </div>

    {{-- SCRIPT JAVASCRIPT (TIDAK BERUBAH) --}}
    <script>
        $(document).ready(function() {
            // Logika untuk Modal Edit
            $('.btn-edit').on('click', function() {
                // 1. Ambil data dari data-attributes
                var id = $(this).data('id');
                var nama = $(this).data('nama');
                var kode = $(this).data('kode');
                var dekan_id = $(this).data('dekan_id');

                // 2. Isi data ke dalam form modal
                $('#edit-id').val(id);
                $('#edit-nama').val(nama);
                $('#edit-kode').val(kode);
                $('#edit-dekan_id').val(dekan_id);
                $('#edit-role-name').text(nama); // Tampilkan nama di header modal

                // 3. Atur action form
                $('#editRoleForm').attr('action', '/fakultas/' + id);
            });

            // Logika untuk Modal Delete
            $('.btn-delete').on('click', function() {
                var id = $(this).data('id');
                var nama = $(this).data('nama');

                // Isi data ke dalam form modal
                $('#delete-id').val(id);
                $('#delete-role-name').text(nama);

                // Atur action form
                $('#deleteRoleForm').attr('action', '/fakultas/' + id);
            });
        });
    </script>
@endsection
