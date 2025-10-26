@extends('apps.index')
@section('title', 'Semester')

@section('content')
    <div class="col-lg-10 col-md-9 content">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>📘 Data Semester</span>
                <form action="/semester" method="GET" class="mb-3 d-flex gap-2">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama semester"
                        value="{{ request('search') }}">

                    <select name="status" class="form-select">
                        <option value="">-- Semua Status --</option>
                        <option value="AKTIF" {{ request('status') == 'AKTIF' ? 'selected' : '' }}>Aktif</option>
                        <option value="NONAKTIF" {{ request('status') == 'NONAKTIF' ? 'selected' : '' }}>Nonaktif</option>
                    </select>

                    <button type="submit" class="btn btn-primary">Filter</button>
                </form>
                @if (!auth()->user()->mahasiswa)
                    <button class="btn btn-light btn-sm text-primary fw-semibold" data-bs-toggle="modal"
                        data-bs-target="#addSemesterModal">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Semester
                    </button>
                @endif
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Semester</th>
                                <th>Kode</th>
                                <th>Keterangan</th>
                                <th>Tahun Akademik</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($semester as $index => $kls)
                                <tr>
                                    <td>{{ $semester->firstItem() + $index }}</td>
                                    <td><span>{{ $kls->nama }}</span></td>
                                    <td>{{ $kls->kode }}</td>
                                    <td>{{ $kls->keterangan }}</td>
                                    <td>{{ $kls->tahun_akademik }}</td>
                                    <td>{{ $kls->tipe }}</td>
                                    <td>
                                        @if ($kls->status == 'AKTIF')
                                            <span class="badge bg-success">{{ ucfirst(strtolower($kls->status)) }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst(strtolower($kls->status)) }}</span>
                                        @endif
                                    </td>
                                    @if (!auth()->user()->mahasiswa)
                                        <td>
                                            {{-- Tombol Edit: Memicu modal dan mengirim data role ke fungsi JS/data attributes --}}
                                            <button type="button" class="btn btn-outline-primary btn-sm btn-edit"
                                                data-bs-toggle="modal" data-bs-target="#editRoleModal"
                                                data-id="{{ $kls->id }}"
                                                data-nama="{{ $kls->nama }}"data-kode="{{ $kls->kode }}"
                                                data-kode="{{ $kls->kode }}"data-keterangan="{{ $kls->keterangan }}"
                                                data-tahun_akademik="{{ $kls->tahun_akademik }}"
                                                data-tipe="{{ $kls->tipe }}"> <i class="bi bi-pencil"></i>
                                            </button>

                                            {{-- Tombol Delete: Memicu modal konfirmasi hapus --}}
                                            <button type="button" class="btn btn-outline-danger btn-sm btn-delete"
                                                data-bs-toggle="modal" data-bs-target="#deleteRoleModal"
                                                data-id="{{ $kls->id }}" data-nama="{{ $kls->nama }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    @endif
                                    {{-- ... akhir loop ... --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="mt-3">
            {{ $semester->links() }}
        </div>
        <div class="mt-4 alert alert-info bg-opacity-25 border-0 text-primary">
            <i class="bi bi-info-circle me-2"></i>
            Data semester digunakan untuk menentukan periode akademik aktif.
        </div>
    </div>
    </div>
    </div>

    <!-- Modal Tambah Semester -->
    <div class="modal fade" id="addSemesterModal" tabindex="-1" aria-labelledby="addSemesterModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" action="/semester" method="POST">
                @csrf
                @method('POST')
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addSemesterModalLabel">Tambah Semester Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Semester</label>
                        <select class="form-select" name="nama">
                            <option value="1">Semester 1</option>
                            <option value="2">Semester 2</option>
                            <option value="3">Semester 3</option>
                            <option value="4">Semester 4</option>
                            <option value="5">Semester 5</option>
                            <option value="6">Semester 6</option>
                            <option value="7">Semester 7</option>
                            <option value="8">Semester 8</option>
                        </select>
                        {{-- <input type="text" class="form-control" placeholder="Contoh: Semester 1/2/3 dll" name="nama"> --}}
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kode</label>
                        <input type="text" class="form-control" placeholder="Contoh: 20251" name="kode">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea class="form-control" rows="2" placeholder="Tuliskan deskripsi singkat semester" name="keterangan">  </textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tahun Akademik</label>
                        <input type="text" class="form-control" placeholder="Contoh: 2025/2026" name="tahun_akademik">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type</label>
                        <select class="form-select" name="tipe">
                            <option value="GANJIL">GANJIL</option>
                            <option value="GENAP">GENAP</option>
                            <option value="KHUSUS">KHUSUS</option>
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
                    <h5 class="modal-title" id="editRoleModalLabel">Edit Semester: <span id="edit-role-name"></span></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit-id"> {{-- ID role yang akan diupdate --}}

                    <div class="mb-3">
                        <label for="edit-nama" class="form-label">Nama Semester</label>
                        <select class="form-select" name="nama" id="edit-nama">
                            <option value="1">Semester 1</option>
                            <option value="2">Semester 2</option>
                            <option value="3">Semester 3</option>
                            <option value="4">Semester 4</option>
                            <option value="5">Semester 5</option>
                            <option value="6">Semester 6</option>
                            <option value="7">Semester 7</option>
                            <option value="8">Semester 8</option>
                        </select>
                        {{-- <input type="text" class="form-control" id="edit-nama" name="nama" required /> --}}
                    </div>
                    <div class="mb-3">
                        <label for="edit-kode" class="form-label">Kode</label>
                        <input class="form-control" id="edit-kode" name="kode"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit-keterangan" class="form-label">Keterangan</label>
                        <input class="form-control" id="edit-keterangan" name="keterangan"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit-tahun_akademik" class="form-label">Tahun Akademik</label>
                        <input class="form-control" id="edit-tahun_akademik" name="tahun_akademik"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit-type" class="form-label">Type</label>
                        <select class="form-select" name="tipe">
                            <option value="GANJIL">GANJIL</option>
                            <option value="GENAP">GENAP</option>
                            <option value="KHUSUS">KHUSUS</option>
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
            <form class="modal-content" id="deleteRoleForm" action="/semester" method="POST">
                @csrf
                @method('DELETE') {{-- Gunakan method DELETE untuk hapus --}}
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteRoleModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus Semester ini? **<span id="delete-role-name"></span>**?</p>
                    <input type="hidden" name="id" id="delete-id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Hapus Semester Ini</button>
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
                var keterangan = $(this).data('keterangan');
                var tahun_akademik = $(this).data('tahun_akademik');
                var tipe = $(this).data('tipe');

                // 2. Isi data Role ke dalam form modal
                $('#edit-id').val(id);
                $('#edit-nama').val(nama);
                $('#edit-keterangan').val(keterangan);
                $('#edit-kode').val(kode);
                $('#edit-keterangan').val(keterangan);
                $('#edit-tahun_akademik').val(tahun_akademik);
                $('#edit-tipe').val(tipe);
                $('#edit-role-name').text(nama); // Tampilkan nama role di header modal

                // 3. Atur action form
                // Ganti '/role/' dengan URL route Anda yang benar, misal '/roles' atau sejenisnya
                $('#editRoleForm').attr('action', '/semester/' + id);

                // 4. Proses dan centang checkbox Izin Akses

                // Pertama, hapus centang dari semua checkbox

            });
            $('.btn-delete').on('click', function() {
                var id = $(this).data('id');
                var nama = $(this).data('nama');

                // Isi data ke dalam form modal
                $('#delete-id').val(id);
                $('#delete-role-name').text(nama);

                // Atur action form
                // Ganti '/role/' dengan URL route Anda yang benar, misal '/roles' atau sejenisnya
                $('#deleteRoleForm').attr('action', '/semester/' + id);
            });
        });
    </script>

@endsection
