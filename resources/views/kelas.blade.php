@extends('apps.index')
@section('title', 'Kelas')

@section('content')
    <div class="col-lg-10 col-md-9 content">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>🏫 Data Kelas</span>
                <form action="/kelas" method="GET" class="mb-3 d-flex gap-2 align-items-center">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama kelas"
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
                        data-bs-target="#addKelasModal">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Kelas
                    </button>
                @endif
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Kelas</th>
                                <th>Tahun ajaran</th>
                                <th>Kapasitas</th>
                                <th>Tipe</th>
                                <th>Kosma</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kelas as $index => $kls)
                                <tr>
                                    <td>{{ $kelas->firstItem() + $index }}</td>
                                    <td><span>{{ $kls->nama }}</span></td>
                                    <td>{{ $kls->angkatan->tahun }}</td>
                                    <td>{{ $kls->kapasitas }}</td>
                                    <td>
                                        @if ($kls->tipe === 'R')
                                            <span>REGULLER</span>
                                        @else
                                            <span>NON REGULLER</span>
                                        @endif
                                    </td>
                                    <td>{{ $kls->kosma?->User?->Biodata?->nama }} @if ($kls->kosma_id !== null)
                                            <span>({{ $kls->kosma?->User?->Mahasiswa?->nim }})</span>
                                        @endif
                                    </td>

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
                                                data-nama="{{ $kls->nama }}"data-angkatan_id="{{ $kls->angkatan_id }}"
                                                data-kapasitas="{{ $kls->kapasitas }}" data-tipe="{{ $kls->tipe }}"> <i
                                                    class="bi bi-pencil"></i>
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
            {{ $kelas->links() }}
        </div>
        <div class="mt-4 alert alert-info bg-opacity-25 border-0 text-primary">
            <i class="bi bi-info-circle me-2"></i>
            Kelola data kelas untuk pengaturan jumlah mahasiswa dan kapasitas.
        </div>
    </div>
    </div>
    </div>

    <!-- Modal Tambah Kelas -->
    <div class="modal fade" id="addKelasModal" tabindex="-1" aria-labelledby="addKelasModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" action="/kelas" method="POST">
                @csrf
                @method('post')
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addKelasModalLabel">Tambah Kelas Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Kelas</label>
                        <input type="text" class="form-control" placeholder="Contoh: S1 SI/ D3 KA" name="nama">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tahun Angkatan</label>
                        <select class="form-select" name="angkatan_id">
                            <option value="">-- Tahun Angkatan --</option>
                            @foreach ($angkatan as $index => $kls)
                                <option value="{{ $kls->id }}">{{ $kls->tahun }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kapasitas</label>
                        <input type="number" class="form-control" placeholder="Contoh: 40" name="kapasitas">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tipe Kelas</label>
                        <select class="form-select" name="tipe">
                            <option value="">-- Tipe --</option>
                            <option value="R">REGULLER</option>
                            <option value="NR">NON REGULLER</option>
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
                    <h5 class="modal-title" id="editRoleModalLabel">Edit Kelas: <span id="edit-role-name"></span></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit-id"> {{-- ID role yang akan diupdate --}}

                    <div class="mb-3">
                        <label for="edit-nama" class="form-label">Nama Kelas</label>
                        <input type="text" class="form-control" id="edit-nama" name="nama" required />
                    </div>
                    <div class="mb-3">
                        <label for="edit-tahun_ajaran" class="form-label">Tahun Angkatan</label>
                        <select class="form-select" name="angkatan_id" id="edit-angkatan_id">
                            <option value="">-- Tahun Angkatan --</option>
                            @foreach ($angkatan as $index => $kls)
                                <option value="{{ $kls->id }}">{{ $kls->tahun }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit-kapasitans" class="form-label">Kapasitas</label>
                        <input class="form-control" id="edit-kapasitas" name="kapasitas"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit-kapasitans" class="form-label">Tipe</label>
                        <input class="form-control" id="edit-tipe" name="tipe"></textarea>
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
                    <p>Apakah Anda yakin ingin menghapus kelas **<span id="delete-role-name"></span>**?</p>
                    <input type="hidden" name="id" id="delete-id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Hapus Kelas Ini</button>
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
                var angkatan_id = $(this).data('angkatan_id');
                var kapasitas = $(this).data('kapasitas');
                var tipe = $(this).data('tipe');
                // var izinAksesJson = $(this).data('izin_akses');

                // 2. Isi data Role ke dalam form modal
                $('#edit-id').val(id);
                $('#edit-nama').val(nama);
                $('#edit-angkatan_id').val(angkatan_id);
                $('#edit-kapasitas').val(kapasitas);
                $('#edit-tipe').val(tipe);
                $('#edit-role-name').text(nama); // Tampilkan nama role di header modal

                $('#editRoleForm').attr('action', '/kelas/' + id);


            });
            $('.btn-delete').on('click', function() {
                var id = $(this).data('id');
                var nama = $(this).data('nama');

                $('#delete-id').val(id);
                $('#delete-role-name').text(nama);

                // Atur action form
                $('#deleteRoleForm').attr('action', '/kelas/' + id);
            });
        });
    </script>
@endsection
