@extends('apps.index')
@section('title', 'Ruangan')

@section('content')
    <div class="col-lg-10 col-md-9 content">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>🚪 Data Angkatan</span>
                <form action="/angkatan" method="GET" class="mb-3 d-flex gap-2">
                    <input type="text" name="search" class="form-control" placeholder="Cari angkatan"
                        value="{{ request('search') }}">

                    <select name="status" class="form-select">
                        <option value="">-- Semua Status --</option>
                        <option value="AKTIF" {{ request('status') == 'AKTIF' ? 'selected' : '' }}>Aktif</option>
                        <option value="NONAKTIF" {{ request('status') == 'NONAKTIF' ? 'selected' : '' }}>Nonaktif</option>
                    </select>

                    <button type="submit" class="btn btn-primary">Filter</button>
                </form>
                <button class="btn btn-light btn-sm text-primary fw-semibold" data-bs-toggle="modal"
                    data-bs-target="#addRuanganModal">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Angkatan
                </button>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tahun</th>
                                <th>Keterangan</th>
                                <th>Jumlah Kelas</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($angkatan as $index => $kls)
                                <tr>
                                    <td>{{ $angkatan->firstItem() + $index }}</td>
                                    <td><span>{{ $kls->tahun }}</span></td>
                                    <td>{{ $kls->keterangan }}</td>
                                    <td>{{ $kls->kelas->count() }}</td>
                                    <td>
                                        @if ($kls->status == 'AKTIF')
                                            <span class="badge bg-success">{{ ucfirst(strtolower($kls->status)) }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst(strtolower($kls->status)) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{-- Tombol Edit: Memicu modal dan mengirim data role ke fungsi JS/data attributes --}}
                                        <button type="button" class="btn btn-outline-primary btn-sm btn-edit"
                                            data-bs-toggle="modal" data-bs-target="#editRoleModal"
                                            data-id="{{ $kls->id }}" data-tahun="{{ $kls->tahun }}"
                                            data-keterangan="{{ $kls->keterangan }}"> <i class="bi bi-pencil"></i>
                                        </button>

                                        {{-- Tombol Delete: Memicu modal konfirmasi hapus --}}
                                        <button type="button" class="btn btn-outline-danger btn-sm btn-delete"
                                            data-bs-toggle="modal" data-bs-target="#deleteRoleModal"
                                            data-id="{{ $kls->id }}" data-tahun="{{ $kls->tahun }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                    {{-- ... akhir loop ... --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="mt-3">
            {{ $angkatan->links() }}
        </div>
    </div>
    </div>
    </div>

    <!-- Modal Tambah Ruangan -->
    <div class="modal fade" id="addRuanganModal" tabindex="-1" aria-labelledby="addRuanganModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" action="/angkatan" method="POST">
                @csrf
                @method('POST')
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addRuanganModalLabel">Tambah Angkatan Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tahun Angkatan</label>
                        <input type="text" class="form-control" placeholder="2024" name="tahun">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea class="form-control" rows="3" placeholder="Deskripsi singkat angkatan ini" name="keterangan"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            {{-- Form action akan diisi oleh JavaScript --}}
            <form class="modal-content" id="editRoleForm" action="" method="POST">
                @csrf
                @method('PUT') {{-- Gunakan method PUT untuk update --}}
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editRoleModalLabel">Edit Angkatan: <span id="edit-role-name"></span></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit-id"> {{-- ID role yang akan diupdate --}}

                    <div class="mb-3">
                        <label for="edit-nama" class="form-label">Tahun Angkatan</label>
                        <input type="text" class="form-control" id="edit-tahun" name="tahun" required />
                    </div>
                    <div class="mb-3">
                        <label for="edit-keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" rows="3" placeholder="Deskripsi singkat angkatan ini" name="keterangan"
                            id="edit-keterangan"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>


    <div class="modal fade" id="deleteRoleModal" tabindex="-1" aria-labelledby="deleteRoleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-sm">
            {{-- Form action akan diisi oleh JavaScript --}}
            <form class="modal-content" id="deleteRoleForm" action="" method="POST">
                @csrf
                @method('DELETE') {{-- Gunakan method DELETE untuk hapus --}}
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteRoleModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus Angkatan ini? **<span id="delete-role-name"></span>**?</p>
                    <input type="hidden" name="id" id="delete-id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Hapus Angkatan Ini</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Tangkap saat tombol edit diklik
            $('.btn-edit').on('click', function() {
                // 1. Ambil data dari data-attributes
                var id = $(this).data('id');
                var tahun = $(this).data('tahun');
                var keterangan = $(this).data('keterangan');
                // var izinAksesJson = $(this).data('izin_akses');

                // 2. Isi data Role ke dalam form modal
                $('#edit-id').val(id);
                $('#edit-tahun').val(tahun);
                $('#edit-keterangan').val(keterangan);
                $('#edit-role-name').text(tahun); // Tampilkan nama role di header modal

                // 3. Atur action form
                // Ganti '/role/' dengan URL route Anda yang benar, misal '/roles' atau sejenisnya
                $('#editRoleForm').attr('action', '/angkatan/' + id);


            });
            $('.btn-delete').on('click', function() {
                var id = $(this).data('id');
                var tahun = $(this).data('tahun');

                // Isi data ke dalam form modal
                $('#delete-id').val(id);
                $('#delete-role-name').text(tahun);

                // Atur action form
                // Ganti '/role/' dengan URL route Anda yang benar, misal '/roles' atau sejenisnya
                $('#deleteRoleForm').attr('action', '/angkatan/' + id);
            });
        });
    </script>

@endsection
