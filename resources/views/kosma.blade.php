@extends('apps.index')
@section('title', 'Kosma')
@section('content')
    <!-- Content -->
    <div class="col-lg-10 col-md-9 content">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>👨‍🏫 Data Kosma</span>
                @if (!auth()->user()->mahasiswa)
                    <button class="btn btn-light btn-sm text-primary fw-semibold" data-bs-toggle="modal"
                        data-bs-target="#dekanFormModal">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Kosma
                    </button>
                @endif
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>NIM</th>
                                <th>Nama Lengkap</th>
                                <th>Kelas</th>
                                <th>Angkatan</th>
                                <th>Prodi</th>
                                <th>Fakultas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kosma as $index => $kls)
                                <tr>
                                    <td>{{ $kosma->firstItem() + $index }}</td>
                                    <td><span>{{ $kls->user->Mahasiswa->nim }}</span></td>
                                    <td><span>{{ $kls->user->Biodata->nama }}</span></td>
                                    <td><span>{{ $kls->kelas->nama }}</span></td>
                                    <td><span>{{ $kls->kelas->Angkatan->nama }}</span></td>
                                    <td><span>{{ $kls->user->Mahasiswa->prodi->nama }}</span></td>
                                    <td><span>{{ $kls->user->Mahasiswa->prodi->fakultas->nama }}</span></td>
                                    @if (!auth()->user()->mahasiswa)
                                        <td>
                                            {{-- Tombol Edit: Memicu modal dan mengirim data role ke fungsi JS/data attributes --}}
                                            <button type="button" class="btn btn-outline-primary btn-sm btn-edit"
                                                data-bs-toggle="modal" data-bs-target="#editRoleModal"
                                                data-id="{{ $kls->id }}" data-kelas_id="{{ $kls->kelas_id }}"
                                                data-user_id="{{ $kls->user_id }}"> <i class="bi bi-pencil"></i>
                                            </button>

                                            {{-- Tombol Delete: Memicu modal konfirmasi hapus --}}
                                            <button type="button" class="btn btn-outline-danger btn-sm btn-delete"
                                                data-bs-toggle="modal" data-bs-target="#deleteRoleModal"
                                                data-id="{{ $kls->id }}" data-nama="{{ $kls->user->Biodata->nama }}">
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
    </div>
    <div class="mt-3">
        {{ $kosma->links() }}
    </div>
    <!-- Modal Tambah Dekan -->
    <div class="modal fade" id="dekanFormModal" tabindex="-1" aria-labelledby="dekanFormModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="dekanFormModalLabel">Tambah data Kosma</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form method="POST" action="/kosma" method="POST">
                    @csrf
                    @method('POST')
                    <div class="modal-body">

                        <div class="mb-3">
                            <label class="form-label">Kelas</label>
                            <select class="form-select" name="kelas_id">
                                <option value="">-- Kelas --</option>
                                @foreach ($kelas as $index => $kls)
                                    <option value="{{ $kls->id }}">{{ $kls->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mahasiswa</label>
                            <select class="form-select" name="user_id">
                                <option value="">-- Mahasiswa --</option>
                                @foreach ($users as $index => $kls)
                                    <option value="{{ $kls->id }}">{{ $kls->Biodata->nama }}
                                        ({{ $kls->Mahasiswa->nim }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-1"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            {{-- Form action akan diisi oleh JavaScript --}}
            <form class="modal-content" id="editRoleForm" action="" method="POST">
                @csrf
                @method('PUT') {{-- Gunakan method PUT untuk update --}}
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editRoleModalLabel">Edit Kosma: <span id="edit-role-name"></span></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit-id"> {{-- ID role yang akan diupdate --}}
                    <div class="mb-3">
                        <label class="form-label">Kelas</label>
                        <select class="form-select" name="kelas_id" id="edit-kelas_id">
                            <option value="">-- Kelas --</option>
                            @foreach ($kelas as $index => $kls)
                                <option value="{{ $kls->id }}">{{ $kls->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mahasiswa</label>
                        <select class="form-select" name="user_id" id="edit-user_id">
                            <option value="">-- Mahasiswa --</option>
                            @foreach ($users as $index => $kls)
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
                    <p>Apakah Anda yakin ingin menghapus Kosma ini? **<span id="delete-role-name"></span>**?</p>
                    <input type="hidden" name="id" id="delete-id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Hapus Kosma Ini</button>
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
                var kelas_id = $(this).data('kelas_id');
                var user_id = $(this).data('user_id');
                // var izinAksesJson = $(this).data('izin_akses');
                RubahProvinsi(prov_id);
                RubahKota(kab_id);
                // 2. Isi data Role ke dalam form modal
                $('#edit-id').val(id);
                $('#edit-kelas_id').val(kelas_id);
                $('#edit-user_id').val(user_id);
                $('#edit-role-name').text(nama); // Tampilkan nama role di header modal

                // 3. Atur action form
                // Ganti '/role/' dengan URL route Anda yang benar, misal '/roles' atau sejenisnya
                $('#editRoleForm').attr('action', '/kosma/' + id);

            });
            $('.btn-delete').on('click', function() {
                var id = $(this).data('id');
                var nama = $(this).data('nama');

                // Isi data ke dalam form modal
                $('#delete-id').val(id);
                $('#delete-role-name').text(nama);

                // Atur action form
                // Ganti '/role/' dengan URL route Anda yang benar, misal '/roles' atau sejenisnya
                $('#deleteRoleForm').attr('action', '/kosma/' + id);
            });
        });
    </script>
@endsection
