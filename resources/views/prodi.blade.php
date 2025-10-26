@extends('apps.index')
@section('title', 'Prodi')

@section('content')

    <div class="col-lg-10 col-md-9 content">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-journal-text me-2"></i>Data Prodi</h5>
                <form action="/prodi" method="GET" class="mb-3 d-flex gap-2">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama prodi"
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
                        data-bs-target="#addKelasModal">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Prodi
                    </button>
                @endif
            </div>
            <div class="card-body">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr class="text-center">
                            <th>No</th>
                            <th>Nama Prodi</th>
                            <th>Kode</th>
                            <th>Fakultas</th>
                            <th>Kaprodi</th>
                            <th>Sekprodi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @foreach ($prodi as $index => $kls)
                            <tr>
                                <td>{{ $prodi->firstItem() + $index }}</td>
                                <td><span>{{ $kls->nama }}</span></td>
                                <td>{{ $kls->kode }}</td>
                                <td>{{ $kls->fakultas->nama }}</td>
                                <td>{{ $kls->kaprodi?->user?->Biodata?->nama }}</td>
                                <td>{{ $kls->sekprodi?->user?->Biodata?->nama }}</td>
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
                                            data-fakultas_id="{{ $kls->fakultas_id }}"
                                            data-kaprodi_id="{{ $kls->kaprodi_id }}"
                                            data-sekprodi_id="{{ $kls->sekprodi_id }}"> <i class="bi bi-pencil"></i>
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
        {{ $prodi->links() }}
    </div>
    <div class="modal fade" id="addKelasModal" tabindex="-1" aria-labelledby="addKelasModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" action="" method="POST">
                @csrf
                @method('post')
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addKelasModalLabel">Tambah prodi Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama prodi</label>
                        <input type="text" class="form-control" placeholder="Contoh: Sistem Informasi/Bisnis Digital dll"
                            name="nama">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kode</label>
                        <input class="form-control" placeholder="Contoh: SI/BD" name="kode">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fakultas</label>
                        <select class="form-select" name="fakultas_id">
                            <option value="">-- Fakultas --</option>
                            @foreach ($fakultas as $index => $kls)
                                <option value="{{ $kls->id }}">{{ $kls->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kaprodi</label>
                        <select class="form-select" name="kaprodi_id">
                            <option value="">-- Kaprodi --</option>
                            @foreach ($kaprodi as $index => $kls)
                                <option value="{{ $kls->id }}">{{ $kls->user->Biodata->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sekprodi</label>
                        <select class="form-select" name="sekprodi_id">
                            <option value="">-- sekprodi --</option>
                            @foreach ($sekprodi as $index => $kls)
                                <option value="{{ $kls->id }}">{{ $kls->user->Biodata->nama }}</option>
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
                    <h5 class="modal-title" id="editRoleModalLabel">Edit Prodi: <span id="edit-role-name"></span></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit-id"> {{-- ID role yang akan diupdate --}}

                    <div class="mb-3">
                        <label for="edit-nama" class="form-label">Nama Prodi</label>
                        <input type="text" class="form-control" id="edit-nama" name="nama" required />
                    </div>
                    <div class="mb-3">
                        <label for="edit-kode" class="form-label">Kode</label>
                        <input class="form-control" id="edit-kode" name="kode" />
                    </div>
                    <div class="mb-3">
                        <label for="edit-fakultas" class="form-label">Fakultas</label>
                        <select class="form-select" name="fakultas_id" id="edit-fakultas_id">
                            <option value="">-- Fakultas --</option>
                            @foreach ($fakultas as $index => $kls)
                                <option value="{{ $kls->id }}">{{ $kls->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit-kaprodi" class="form-label">Kaprodi</label>
                        <select class="form-select" name="kaprodi_id" id="edit-kaprodi_id">
                            <option value="">-- Kaprodi --</option>
                            @foreach ($kaprodi as $index => $kls)
                                <option value="{{ $kls->id }}">{{ $kls->user->Biodata->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit-sekprodi" class="form-label">Sekprodi</label>
                        <select class="form-select" name="sekprodi_id" id="edit-sekprodi_id">
                            <option value="">-- Sekprodi --</option>
                            @foreach ($sekprodi as $index => $kls)
                                <option value="{{ $kls->id }}">{{ $kls->user->Biodata->nama }}</option>
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
                    <p>Apakah Anda yakin ingin menghapus Prodi ini? **<span id="delete-role-name"></span>**?</p>
                    <input type="hidden" name="id" id="delete-id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Hapus Prodi Ini</button>
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
                var fakultas_id = $(this).data('fakultas_id');
                var kaprodi_id = $(this).data('kaprodi_id');
                var sekprodi_id = $(this).data('sekprodi_id');
                // var izinAksesJson = $(this).data('izin_akses');

                // 2. Isi data Role ke dalam form modal
                $('#edit-id').val(id);
                $('#edit-nama').val(nama);
                $('#edit-kode').val(kode);
                $('#edit-fakultas_id').val(fakultas_id);
                $('#edit-kaprodi_id').val(kaprodi_id);
                $('#edit-sekprodi_id').val(sekprodi_id);
                $('#edit-role-name').text(nama); // Tampilkan nama role di header modal

                // 3. Atur action form
                // Ganti '/role/' dengan URL route Anda yang benar, misal '/roles' atau sejenisnya
                $('#editRoleForm').attr('action', '/prodi/' + id);

            });
            $('.btn-delete').on('click', function() {
                var id = $(this).data('id');
                var nama = $(this).data('nama');

                // Isi data ke dalam form modal
                $('#delete-id').val(id);
                $('#delete-role-name').text(nama);

                // Atur action form
                // Ganti '/role/' dengan URL route Anda yang benar, misal '/roles' atau sejenisnya
                $('#deleteRoleForm').attr('action', '/prodi/' + id);
            });
        });
    </script>
@endsection
