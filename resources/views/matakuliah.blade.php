@extends('apps.index')
@section('title', 'Matakuliah')

@section('content')
    <div class="col-lg-10 col-md-9 content">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>📘 Data Mata Kuliah</span>
                <form action="/matakuliah" method="GET" class="mb-3 d-flex gap-2 align-items-center">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama matakuliah"
                        value="{{ request('search') }}">

                    <select name="status" class="form-select form-select-sm">
                        <option value="">-- Semua Status --</option>
                        <option value="AKTIF" {{ request('status') == 'AKTIF' ? 'selected' : '' }}>Aktif</option>
                        <option value="NONAKTIF" {{ request('status') == 'NONAKTIF' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    <button type="submit" class="btn btn-primary">Filter</button>
                </form>
                @if (auth()->user()->dekan || auth()->user()->kaprodi || auth()->user()->sekprodi)
                    <button class="btn btn-light btn-sm text-primary fw-semibold" data-bs-toggle="modal"
                        data-bs-target="#addMatkulModal">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Mata Kuliah
                    </button>
                @endif
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Mata Kuliah</th>
                                <th>Kode</th>
                                <th>SKS</th>
                                <th>Semester</th>
                                <th>Program Studi</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($matakuliah as $index => $kls)
                                <tr>
                                    <td>{{ $matakuliah->firstItem() + $index }}</td>
                                    <td><span>{{ $kls->nama }}</span></td>
                                    <td>{{ $kls->kode }}</td>
                                    <td>{{ $kls->sks }}</td>
                                    <td>{{ $kls->semester->nama }}</td>
                                    <td>{{ $kls->prodi->nama }}</td>
                                    <td>
                                        @if ($kls->status == 'AKTIF')
                                            <span class="badge bg-success">{{ ucfirst(strtolower($kls->status)) }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst(strtolower($kls->status)) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if (auth()->user()->dekan || auth()->user()->kaprodi || auth()->user()->sekprodi)
                                            {{-- Tombol Edit: Memicu modal dan mengirim data role ke fungsi JS/data attributes --}}
                                            <button type="button" class="btn btn-outline-primary btn-sm btn-edit"
                                                data-bs-toggle="modal" data-bs-target="#editRoleModal"
                                                data-id="{{ $kls->id }}"
                                                data-nama="{{ $kls->nama }}"data-kode="{{ $kls->kode }}"
                                                data-kode="{{ $kls->kode }}"data-sks="{{ $kls->sks }}"
                                                data-semester_id="{{ $kls->semester->id }}"
                                                data-prodi_id="{{ $kls->prodi->id }}"> <i class="bi bi-pencil"></i>
                                            </button>

                                            {{-- Tombol Delete: Memicu modal konfirmasi hapus --}}
                                            <button type="button" class="btn btn-outline-danger btn-sm btn-delete"
                                                data-bs-toggle="modal" data-bs-target="#deleteRoleModal"
                                                data-id="{{ $kls->id }}" data-nama="{{ $kls->nama }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @endif
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
            {{ $matakuliah->links() }}
        </div>
        <div class="mt-4 alert alert-info bg-opacity-25 border-0 text-primary">
            <i class="bi bi-info-circle me-2"></i>
            Kelola data mata kuliah di sini. Pastikan SKS dan status aktif sesuai dengan kurikulum.
        </div>
    </div>
    </div>
    </div>

    <!-- Modal Tambah Mata Kuliah -->
    <div class="modal fade" id="addMatkulModal" tabindex="-1" aria-labelledby="addMatkulModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" action="/matakuliah" method="POST">
                @csrf
                @method('post')
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addMatkulModalLabel">Tambah Mata Kuliah Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Mata Kuliah</label>
                        <input type="text" class="form-control" placeholder="Contoh: Pemrograman Web"name="nama">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kode</label>
                        <input type="text" class="form-control" placeholder="Contoh: PW101"name="kode">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">SKS</label>
                        <input type="number" class="form-control" placeholder="Contoh: 3"name="sks">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Semester</label>
                        <select class="form-select"name="semester_id">
                            @foreach ($semester as $index => $kls)
                                <option value="{{ $kls->id }}">{{ $kls->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Program Studi</label>
                        <select class="form-select"name="prodi_id">
                            @foreach ($prodi as $index => $kls)
                                <option value="{{ $kls->id }}">{{ $kls->nama }}</option>
                            @endforeach
                        </select>
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
                    <h5 class="modal-title" id="editRoleModalLabel">Edit Role: <span id="edit-role-name"></span></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit-id"> {{-- ID role yang akan diupdate --}}

                    <div class="mb-3">
                        <label for="edit-nama" class="form-label">Nama Fakultas</label>
                        <input type="text" class="form-control" id="edit-nama" name="nama" required />
                    </div>
                    <div class="mb-3">
                        <label for="edit-kode" class="form-label">Kode</label>
                        <input class="form-control" id="edit-kode" name="kode"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit-sks" class="form-label">SKS</label>
                        <input class="form-control" id="edit-sks" name="sks"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Semester</label>
                        <select class="form-select"name="semester_id" id="edit-semester_id">
                            @foreach ($semester as $index => $kls)
                                <option value="{{ $kls->id }}">{{ $kls->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Program Studi</label>
                        <select class="form-select"name="prodi_id" id="edit-prodi_id">
                            @foreach ($prodi as $index => $kls)
                                <option value="{{ $kls->id }}">{{ $kls->nama }}</option>
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
                    <p>Apakah Anda yakin ingin menghapus Mata Kuliah ini? **<span id="delete-role-name"></span>**?</p>
                    <input type="hidden" name="id" id="delete-id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Hapus Mata kuliah Ini</button>
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
                var nama = $(this).data('nama');
                var kode = $(this).data('kode');
                var sks = $(this).data('sks');
                var semester_id = $(this).data('semester_id');
                var prodi_id = $(this).data('prodi_id');
                // var izinAksesJson = $(this).data('izin_akses');

                // 2. Isi data Role ke dalam form modal
                $('#edit-id').val(id);
                $('#edit-nama').val(nama);
                $('#edit-kode').val(kode);
                $('#edit-sks').text(sks); // Tampilkan nama role di header modal
                $('#edit-semester_id').val(semester_id); // Tampilkan nama role di header modal
                $('#edit-prodi_id').val(prodi_id); // Tampilkan nama role di header modal


                // 3. Atur action form
                // Ganti '/role/' dengan URL route Anda yang benar, misal '/roles' atau sejenisnya
                $('#editRoleForm').attr('action', '/matakuliah/' + id);

            });
            $('.btn-delete').on('click', function() {
                var id = $(this).data('id');
                var nama = $(this).data('nama');

                // Isi data ke dalam form modal
                $('#delete-id').val(id);
                $('#delete-role-name').text(nama);

                // Atur action form
                // Ganti '/role/' dengan URL route Anda yang benar, misal '/roles' atau sejenisnya
                $('#deleteRoleForm').attr('action', '/matakuliah/' + id);
            });
        });
    </script>
@endsection
