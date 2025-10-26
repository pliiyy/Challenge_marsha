@extends('apps.index')
@section('title', 'Fakultas')

@section('content')
    <div class="col-md-9 col-lg-10 content">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-building me-2"></i>Data Fakultas</h5>
                <form action="/fakultas" method="GET" class="mb-3 d-flex gap-2 align-items-center">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama fakultas"
                        value="{{ request('search') }}">

                    <select name="status" class="form-select form-select-sm">
                        <option value="">-- Semua Status --</option>
                        <option value="AKTIF" {{ request('status') == 'AKTIF' ? 'selected' : '' }}>Aktif</option>
                        <option value="NONAKTIF" {{ request('status') == 'NONAKTIF' ? 'selected' : '' }}>Nonaktif</option>
                    </select>

                    <button type="submit" class="btn btn-primary">Filter</button>
                </form>
                @if (auth()->user()->dekan || auth()->user()->kaprodi || auth()->user()->sekprodi)
                    <button class="btn btn-light btn-sm text-primary fw-semibold"data-bs-toggle="modal"
                        data-bs-target="#addKelasModal">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Fakultas
                    </button>
                @endif
            </div>
            <div class="card-body">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr class="text-center">
                            <th>No</th>
                            <th>Nama Fakultas</th>
                            <th>Kode</th>
                            <th>Dekan</th>
                            <th>Jumlah Prodi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @foreach ($fakultas as $index => $kls)
                            <tr>
                                <td>{{ $fakultas->firstItem() + $index }}</td>
                                <td><span>{{ $kls->nama }}</span></td>
                                <td>{{ $kls->kode }}</td>
                                <td>{{ $kls->dekan?->User?->biodata?->nama }}</td>
                                <td>{{ $kls->prodi->count() }}</td>
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
                                            data-id="{{ $kls->id }}" data-nama="{{ $kls->nama }}"
                                            data-kode="{{ $kls->kode }}" data-dekan_id="{{ $kls->dekan_id }}"> <i
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
        {{ $fakultas->links() }}
    </div>
    <div class="modal fade" id="addKelasModal" tabindex="-1" aria-labelledby="addKelasModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" action="/fakultas" method="POST">
                @csrf
                @method('post')
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addKelasModalLabel">Tambah Fakultas</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Fakultas</label>
                        <input type="text" class="form-control" placeholder="Contoh: Komputer" name="nama">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kode</label>
                        <input class="form-control" placeholder="" name="kode">
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
                    <h5 class="modal-title" id="editRoleModalLabel">Edit Fakultas: <span id="edit-role-name"></span></h5>
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
                    <select class="form-select" name="dekan_id" id="edit-dekan_id">
                        <option value="">-- Pilih Dekan --</option>
                        @foreach ($dekan as $index => $kls)
                            <option value="{{ $kls->id }}">{{ $kls->user->biodata->nama }}</option>
                        @endforeach
                    </select>
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
            <form class="modal-content" id="deleteRoleForm" action="/fakultas" method="POST">
                @csrf
                @method('DELETE') {{-- Gunakan method DELETE untuk hapus --}}
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteRoleModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus Fakultas ini? **<span id="delete-role-name"></span>**?</p>
                    <input type="hidden" name="id" id="delete-id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Hapus Fakultas Ini</button>
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
                var dekan_id = $(this).data('dekan_id');
                // var izinAksesJson = $(this).data('izin_akses');

                // 2. Isi data Role ke dalam form modal
                $('#edit-id').val(id);
                $('#edit-nama').val(nama);
                $('#edit-kode').val(kode);
                $('#edit-dekan_id').val(dekan_id);
                $('#edit-role-name').text(nama); // Tampilkan nama role di header modal

                // 3. Atur action form
                // Ganti '/role/' dengan URL route Anda yang benar, misal '/roles' atau sejenisnya
                $('#editRoleForm').attr('action', '/fakultas/' + id);

                // 4. Proses dan centang checkbox Izin Akses

                // Pertama, hapus centang dari semua checkbox
                // $('#editRoleModal input[type="checkbox"]').prop('checked', false);

                // if (izinAksesJson) {
                //     try {
                //         // Decode JSON string terluar menjadi array string JSON
                //         var stringArray = JSON.parse(izinAksesJson);

                //         stringArray.forEach(function(permString) {
                //             // Decode setiap string JSON di dalamnya menjadi objek/array
                //             var perm = JSON.parse(permString);


                //             // Pisahkan string akses (misal: "read,create")
                //             var aksesArray = perm.akses.split(',');

                //             // Tentukan modulKey dari nama (sesuaikan dengan nama di form)
                //             var moduleKey = perm.nama === 'Dashboard' ? 'dashboard' :
                //                 perm.nama === 'Mahasiswa' ? 'mahasiswa' : null;

                //             if (moduleKey) {
                //                 aksesArray.forEach(function(akses) {
                //                     // Bentuk ID checkbox yang sesuai dan centang
                //                     var checkboxId = '#edit-' + moduleKey + '_' + akses
                //                         .trim();
                //                     $(checkboxId).prop('checked', true);
                //                 });
                //             }
                //         });
                //     } catch (e) {
                //         console.error("Gagal memproses izin akses JSON:", e);
                //     }
                // }
            });
            $('.btn-delete').on('click', function() {
                var id = $(this).data('id');
                var nama = $(this).data('nama');

                // Isi data ke dalam form modal
                $('#delete-id').val(id);
                $('#delete-role-name').text(nama);

                // Atur action form
                // Ganti '/role/' dengan URL route Anda yang benar, misal '/roles' atau sejenisnya
                $('#deleteRoleForm').attr('action', '/fakultas/' + id);
            });
        });
    </script>
@endsection
