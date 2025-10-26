@extends('apps.index')
@section('title', 'Mahasiswa')

@section('content')

    <div class="col-lg-10 col-md-9 content">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-journal-text me-2"></i>🎓 Data Mahasiswa</h5>
                <form action="/mahasiswa" method="GET" class="mb-3 d-flex gap-2">
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
                        <i class="bi bi-plus-circle me-1"></i> Tambah Mahasiswa
                    </button>
                @endif
            </div>
            <div class="card-body">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr class="text-center">
                            <th>#</th>
                            <th>NIM</th>
                            <th>Nama Mahasiswa</th>
                            <th>Program Studi</th>
                            <th>Fakultas</th>
                            <th>Kelas</th>
                            <th>Angkatan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @foreach ($mahasiswa as $index => $kls)
                            <tr>
                                <td>{{ $mahasiswa->firstItem() + $index }}</td>
                                <td><span>{{ $kls->nim }}</span></td>
                                <td><span>{{ $kls->user->Biodata->nama }}</span></td>
                                <td>{{ $kls->prodi->nama }}</td>
                                <td>{{ $kls->prodi->fakultas->nama }}</td>
                                <td>{{ $kls->kelas->nama }}</td>
                                <td>{{ $kls->kelas->Angkatan->tahun }}</td>
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
                                            data-username="{{ $kls->user->username }}"data-nim="{{ $kls->nim }}"
                                            data-email="{{ $kls->user->email }}"
                                            data-no_telepon="{{ $kls->user->no_telepon }}"
                                            data-tempat_lahir="{{ $kls->user->Biodata->tempat_lahir }}"
                                            data-tanggal_lahir="{{ $kls->user->Biodata->tanggal_lahir }}"
                                            data-nama="{{ $kls->user->Biodata->nama }}"
                                            data-jenis_kelamin="{{ $kls->user->Biodata->jenis_kelamin }}"
                                            data-agama="{{ $kls->user->Biodata->agama }}"
                                            data-prov_id="{{ $kls->user->Biodata->prov_id }}"
                                            data-kab_id="{{ $kls->user->Biodata->kab_id }}"
                                            data-kec_id="{{ $kls->user->Biodata->kec_id }}"
                                            data-kelurahan="{{ $kls->user->Biodata->kelurahan }}"
                                            data-alamat="{{ $kls->user->Biodata->alamat }}"
                                            data-prodi_id="{{ $kls->prodi_id }}" data-kelas_id="{{ $kls->kelas_id }}"> <i
                                                class="bi bi-pencil"></i>
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
    <div class="mt-3">
        {{ $mahasiswa->links() }}
    </div>
    <div class="modal fade" id="addKelasModal" tabindex="-1" aria-labelledby="addKelasModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form class="modal-content" action="/mahasiswa" method="POST">
                @csrf
                @method('post')
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addKelasModalLabel">Tambah Mahasiswa Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="mb-3 col-md-6">
                        <label class="form-label">NIM</label>
                        <input type="text" class="form-control" name="nim">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" name="nama">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" name="username">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Email</label>
                        <input type="text" class="form-control" name="email">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">No Telepon</label>
                        <input type="text" class="form-control" name="no_telepon">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Program Studi</label>
                        <select class="form-select" name="prodi_id">
                            <option value="">-- Prodi --</option>
                            @foreach ($prodi as $index => $kls)
                                <option value="{{ $kls->id }}">{{ $kls->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Kelas</label>
                        <select class="form-select" name="kelas_id">
                            <option value="">-- Kelas --</option>
                            @foreach ($kelas as $index => $kls)
                                <option value="{{ $kls->id }}">{{ $kls->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Tempat lahir</label>
                        <input type="text" class="form-control" name="tempat_lahir">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Tanggal lahir</label>
                        <input type="date" class="form-control" name="tanggal_lahir">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Jenis Kelamin</label>
                        <select class="form-select" name="jenis_kelamin">
                            <option value="L">Laki Laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Agama</label>
                        <select class="form-select" name="agama">
                            <option value="ISLAM">ISLAM</option>
                            <option value="HINDU">HINDU</option>
                            <option value="BUDHA">BUDHA</option>
                            <option value="KATOLIK">KATOLIK</option>
                            <option value="PROTESTAN">PROTESTAN</option>
                            <option value="KONGHUCU">KONGHUCU</option>
                        </select>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Provinsi</label>
                        <select class="form-select" name="prov_id" id="prov_id" onchange="RubahProvinsi(this.value)">
                        </select>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Kota/Kabupaten</label>
                        <select class="form-select" name="kab_id" id="kab_id" onchange="RubahKota(this.value)">
                        </select>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Kecamatan</label>
                        <select class="form-select" name="kec_id" id="kec_id">
                        </select>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Kelurahan</label>
                        <input type="data" class="form-control" name="kelurahan">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Alamat</label>
                        <input type="text" class="form-control" name="alamat">
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
        <div class="modal-dialog modal-lg">
            {{-- Form action akan diisi oleh JavaScript --}}
            <form class="modal-content" id="editRoleForm" action="" method="POST">
                @csrf
                @method('PUT') {{-- Gunakan method PUT untuk update --}}
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editRoleModalLabel">Edit Mahasiswa: <span id="edit-role-name"></span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body row g-3">
                    <input type="hidden" name="id" id="edit-id"> {{-- ID role yang akan diupdate --}}
                    <div class="mb-3 col-md-6">
                        <label class="form-label">NIM</label>
                        <input type="text" class="form-control" placeholder="0012" name="nim" id="edit-nim">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" placeholder="0012" name="nama" id="edit-nama">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" name="username" id="edit-username">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Email</label>
                        <input type="text" class="form-control" name="email" id="edit-email">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">No Telepon</label>
                        <input type="text" class="form-control" name="no_telepon" id="edit-no_telepon">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Program Studi</label>
                        <select class="form-select" name="prodi_id" id="edit-prodi_id">
                            <option value="">-- Prodi --</option>
                            @foreach ($prodi as $index => $kls)
                                <option value="{{ $kls->id }}">{{ $kls->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Kelas</label>
                        <select class="form-select" name="kelas_id" id="edit-kelas_id">
                            <option value="">-- Kelas --</option>
                            @foreach ($kelas as $index => $kls)
                                <option value="{{ $kls->id }}">{{ $kls->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Tempat lahir</label>
                        <input type="text" class="form-control" name="tempat_lahir" id="edit-tempat_lahir">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Tanggal lahir</label>
                        <input type="date" class="form-control" name="tanggal_lahir" id="edit-tanggal_lahir">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Jenis Kelamin</label>
                        <select class="form-select" name="jenis_kelamin" id="edit-jenis_kelamin">
                            <option value="L">Laki Laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Agama</label>
                        <select class="form-select" name="agama" id="edit-agama">
                            <option value="ISLAM">ISLAM</option>
                            <option value="HINDU">HINDU</option>
                            <option value="BUDHA">BUDHA</option>
                            <option value="KATOLIK">KATOLIK</option>
                            <option value="PROTESTAN">PROTESTAN</option>
                            <option value="KONGHUCU">KONGHUCU</option>
                        </select>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Provinsi</label>
                        <select class="form-select" name="prov_id" id="edit-prov_id"
                            onchange="RubahProvinsi(this.value)">
                        </select>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Kota/Kabupaten</label>
                        <select class="form-select" name="kab_id" id="edit-kab_id" onchange="RubahKota(this.value)">
                        </select>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Kecamatan</label>
                        <select class="form-select" name="kec_id" id="edit-kec_id">
                        </select>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Kelurahan</label>
                        <input type="data" class="form-control" name="kelurahan" id="edit-kelurahan">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Alamat</label>
                        <input type="text" class="form-control" name="alamat" id="edit-alamat">
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
                    <p>Apakah Anda yakin ingin menghapus Mahasiswa ini? **<span id="delete-role-name"></span>**?</p>
                    <input type="hidden" name="id" id="delete-id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Hapus Mahasiswa</button>
                </div>
            </form>
        </div>
    </div>


    <script>
        function Add() {
            fetch("/api/provinces").then(res => res.json()).then(res => {
                const data = res.data;
                const temp = data.map((d) => `
                        <option  value="${d.code}" >${d.name}</option>
            `);
                document.getElementById("prov_id").innerHTML = temp.join("");
                document.getElementById("edit-prov_id").innerHTML = temp.join("");
            })
        }



        function RubahProvinsi(id) {
            fetch(`/api/regencies/${id}`).then(res => res.json()).then(res => {
                const data = res.data;
                const temp = data.map((d) => `
                        <option value="${d.code}" >${d.name}</option>
            `);
                document.getElementById("kab_id").innerHTML = temp.join("");
                document.getElementById("edit-kab_id").innerHTML = temp.join("");
            })
        }

        function RubahKota(id) {
            fetch(`/api/districts/${id}`).then(res => res.json()).then(res => {
                const data = res.data;
                const temp = data.map((d) => `
                        <option value="${d.code}" >${d.name}</option>
            `);
                document.getElementById("kec_id").innerHTML = temp.join("");
                document.getElementById("edit-kec_id").innerHTML = temp.join("");
            })
        }
        $(document).ready(function() {
            // Tangkap saat tombol edit diklik
            Add();
            $('.btn-edit').on('click', function() {
                // 1. Ambil data dari data-attributes
                var id = $(this).data('id');
                var nim = $(this).data('nim');
                var nama = $(this).data('nama');
                var username = $(this).data('username');
                var email = $(this).data('email');
                var no_telepon = $(this).data('no_telepon');
                var jenis_kelamin = $(this).data('jenis_kelamin');
                var agama = $(this).data('agama');
                var tempat_lahir = $(this).data('tempat_lahir');
                var tanggal_lahir = $(this).data('tanggal_lahir');
                var prov_id = $(this).data('prov_id');
                var kec_id = $(this).data('kec_id');
                var kab_id = $(this).data('kab_id');
                var kecamatan = $(this).data('kecamatan');
                var kelurahan = $(this).data('kelurahan');
                var alamat = $(this).data('alamat');
                var prodi_id = $(this).data('prodi_id');
                var kelas_id = $(this).data('kelas_id');
                // var izinAksesJson = $(this).data('izin_akses');
                RubahProvinsi(prov_id);
                RubahKota(kab_id);
                // 2. Isi data Role ke dalam form modal
                $('#edit-id').val(id);
                $('#edit-nim').val(nim);
                $('#edit-nama').val(nama);
                $('#edit-username').val(username);
                $('#edit-email').val(email);
                $('#edit-no_telepon').val(no_telepon);
                $('#edit-tempat_lahir').val(tempat_lahir);
                $('#edit-tanggal_lahir').val(tanggal_lahir);
                $('#edit-jenis_kelamin').val(jenis_kelamin);
                $('#edit-agama').val(agama);
                $('#edit-prov_id').val(prov_id);
                $('#edit-kab_id').val(kab_id);
                $('#edit-kec_id').val(kec_id);
                $('#edit-kecamatan').val(kecamatan);
                $('#edit-kelurahan').val(kelurahan);
                $('#edit-alamat').val(alamat);
                $('#edit-kelas_id').val(kelas_id);
                $('#edit-prodi_id').val(prodi_id);
                $('#edit-role-name').text(nama); // Tampilkan nama role di header modal

                // 3. Atur action form
                // Ganti '/role/' dengan URL route Anda yang benar, misal '/roles' atau sejenisnya
                $('#editRoleForm').attr('action', '/mahasiswa/' + id);

            });
            $('.btn-delete').on('click', function() {
                var id = $(this).data('id');
                var nama = $(this).data('nama');

                // Isi data ke dalam form modal
                $('#delete-id').val(id);
                $('#delete-role-name').text(nama);

                // Atur action form
                // Ganti '/role/' dengan URL route Anda yang benar, misal '/roles' atau sejenisnya
                $('#deleteRoleForm').attr('action', '/mahasiswa/' + id);
            });
        });
    </script>
@endsection
