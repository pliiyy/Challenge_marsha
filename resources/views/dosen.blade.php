@extends('apps.index')
@section('title', 'Dosen')
@section('content')
    <!-- Content -->
    <div class="col-lg-10 col-md-9 content">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>👨‍🏫 Data Dosen</span>
                @if (auth()->user()->dekan || auth()->user()->kaprodi || auth()->user()->sekprodi)
                    <button class="btn btn-light btn-sm text-primary fw-semibold" data-bs-toggle="modal"
                        data-bs-target="#dekanFormModal">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Dosen
                    </button>
                @endif
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>NIDN</th>
                                <th>Nama Dosen</th>
                                <th>Email</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dosen as $index => $kls)
                                <tr>
                                    <td>{{ $dosen->firstItem() + $index }}</td>
                                    <td><span>{{ $kls->nidn }}</span></td>
                                    <td><span>{{ $kls->user->Biodata->nama }}</span></td>
                                    <td><span>{{ $kls->user->email }}</span></td>
                                    <td>
                                        @if (auth()->user()->dekan || auth()->user()->kaprodi || auth()->user()->sekprodi)
                                            {{-- Tombol Edit: Memicu modal dan mengirim data role ke fungsi JS/data attributes --}}
                                            <button type="button" class="btn btn-outline-primary btn-sm btn-edit"
                                                data-bs-toggle="modal" data-bs-target="#editRoleModal"
                                                data-id="{{ $kls->id }}"
                                                data-nidn="{{ $kls->nidn }}"data-preferensi="{{ $kls->preferensi }}"
                                                data-nama="{{ $kls->user->Biodata->nama }}"
                                                data-username="{{ $kls->user->username }}"
                                                data-email="{{ $kls->user->email }}"
                                                data-no_telepon="{{ $kls->user->no_telepon }}"
                                                data-tempat_lahir="{{ $kls->user->Biodata->tempat_lahir }}"
                                                data-tanggal_lahir="{{ $kls->user->Biodata->tanggal_lahir }}"
                                                data-jenis_kelamin="{{ $kls->user->Biodata->jenis_kelamin }}"
                                                data-agama="{{ $kls->user->Biodata->agama }}"
                                                data-prov_id="{{ $kls->user->Biodata->prov_id }}"
                                                data-kab_id="{{ $kls->user->Biodata->kab_id }}"
                                                data-kec_id="{{ $kls->user->Biodata->kec_id }}"
                                                data-kelurahan="{{ $kls->user->Biodata->kelurahan }}"
                                                data-alamat="{{ $kls->user->Biodata->alamat }}"> <i
                                                    class="bi bi-pencil"></i>
                                            </button>

                                            {{-- Tombol Delete: Memicu modal konfirmasi hapus --}}
                                            <button type="button" class="btn btn-outline-danger btn-sm btn-delete"
                                                data-bs-toggle="modal" data-bs-target="#deleteRoleModal"
                                                data-id="{{ $kls->id }}" data-nama="{{ $kls->user->Biodata->nama }}">
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
            {{ $dosen->links() }}
        </div>
        <div class="mt-4 alert alert-info bg-opacity-25 border-0 text-primary">
            <i class="bi bi-info-circle me-2"></i>
            Kelola data Dekan untuk setiap fakultas dan program studi.
        </div>
    </div>

    <!-- Modal Tambah Dekan -->
    <div class="modal fade" id="dekanFormModal" tabindex="-1" aria-labelledby="dekanFormModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="dekanFormModalLabel">Tambah data Dosen</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form method="POST" action="/dosen" method="POST">
                    @csrf
                    @method('POST')
                    <div class="modal-body">

                        <hr class="my-3">

                        {{-- Pilihan Tipe User (Toggle) --}}
                        <div class="mb-3">
                            <label for="user_selection_type" class="form-label">Tindakan User:</label>
                            <select id="user_selection_type" class="form-select">
                                <option value="existing">Pilih User/Dosen yang Sudah Ada</option>
                                <option value="new">Buat User/Dosen Baru</option>
                            </select>
                        </div>
                        {{-- 3. Blok Input untuk User Existing --}}
                        <div id="existing-user-block">
                            <div class="mb-3">
                                <label for="user_id" class="form-label">Pilih User/Dosen:</label>
                                <select name="user_id" id="user_id"
                                    class="form-select @error('user_id') is-invalid @enderror">
                                    <option value="">-- Pilih User --</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->Biodata->nama }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="nidn" class="form-label">NIDN:</label>
                                <input type="text" name="nidn" id="nidn_existing"
                                    class="form-control @error('nidn') is-invalid @enderror" value="{{ old('nidn') }}">
                                @error('nidn')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- 4. Blok Input untuk User Baru --}}
                        {{-- Catatan: Gunakan kelas 'd-none' untuk menyembunyikan secara default --}}
                        <div id="new-user-block" class="d-none">
                            <h5 class="mt-4 mb-3 text-primary">Data Dosen Baru</h5>
                            <div class="mb-3">
                                <label for="nidn" class="form-label">NIDN:</label>
                                <input type="text" name="nidn" id="nidn_new"
                                    class="form-control @error('nidn') is-invalid @enderror" value="{{ old('nidn') }}">
                                @error('nidn')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username:</label>
                                <input type="text" name="username" id="username"
                                    class="form-control @error('username') is-invalid @enderror"
                                    value="{{ old('username') }}">
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="user_name" class="form-label">Nama Lengkap:</label>
                                <input type="text" name="user_name" id="user_name"
                                    class="form-control @error('user_name') is-invalid @enderror"
                                    value="{{ old('user_name') }}">
                                @error('user_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="user_email" class="form-label">Email User:</label>
                                <input type="email" name="user_email" id="user_email"
                                    class="form-control @error('user_email') is-invalid @enderror"
                                    value="{{ old('user_email') }}">
                                @error('user_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="no_telepon" class="form-label">No Telepon:</label>
                                <input name="no_telepon" id="no_telepon"
                                    class="form-control @error('no_telepon') is-invalid @enderror"
                                    value="{{ old('no_telepon') }}">
                                @error('no_telepon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="user_password" class="form-label">Password:</label>
                                <input type="password" name="user_password" id="user_password"
                                    class="form-control @error('user_password') is-invalid @enderror">
                                @error('user_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="tempat_lahir" class="form-label">Tempat Lahir:</label>
                                <input name="tempat_lahir" id="tempat_lahir"
                                    class="form-control @error('tempat_lahir') is-invalid @enderror"
                                    value="{{ old('tempat_lahir') }}">
                                @error('tempat_lahir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir:</label>
                                <input name="tanggal_lahir" id="tanggal_lahir"
                                    class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                    value="{{ old('tanggal_lahir') }}" type="date">
                                @error('tanggal_lahir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="jenis_kelamin" class="form-label">Jenis Kelamin:</label>
                                <select name="jenis_kelamin" id="jenis_kelamin"
                                    class="form-select @error('jenis_kelamin') is-invalid @enderror">
                                    <option value="">-- Pilih JK --</option>
                                    <option value="L">Laki - Laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                                @error('jenis_kelamin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="agama" class="form-label">Agama:</label>
                                <select name="agama" id="agama"
                                    class="form-select @error('agama') is-invalid @enderror">
                                    <option value="">-- Pilih Agama --</option>
                                    <option value="ISLAM">ISLAM</option>
                                    <option value="HINDU">HINDU</option>
                                    <option value="BUDHA">BUDHA</option>
                                    <option value="PROTESTAN">PROTESTAN</option>
                                    <option value="KATOLIK">KATOLIK</option>
                                    <option value="KONGHUCU">KONGHUCU</option>
                                </select>
                                @error('agama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="prov_id" class="form-label">Provinsi:</label>
                                <select name="prov_id" id="prov_id"
                                    class="form-select @error('prov_id') is-invalid @enderror"
                                    onchange="RubahProvinsi(this.value)">
                                </select>
                                @error('prov_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="kab_id" class="form-label">Kota/Kabupaten:</label>
                                <select name="kab_id" id="kab_id"
                                    class="form-select @error('kab_id') is-invalid @enderror"
                                    onchange="RubahKota(this.value)">
                                </select>
                                @error('kab_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="kec_id" class="form-label">Kecamatan:</label>
                                <select name="kec_id" id="kec_id"
                                    class="form-select @error('kec_id') is-invalid @enderror">
                                </select>
                                @error('kec_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="kelurahan" class="form-label">Kelurahan:</label>
                                <input name="kelurahan" id="kelurahan"
                                    class="form-control @error('kelurahan') is-invalid @enderror"
                                    value="{{ old('kelurahan') }}">
                                @error('kelurahan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat:</label>
                                <textarea name="alamat" id="alamat" class="form-control @error('alamat') is-invalid @enderror"
                                    value="{{ old('alamat') }}"></textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
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
                    <h5 class="modal-title" id="editRoleModalLabel">Edit Dosen: <span id="edit-role-name"></span></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit-id"> {{-- ID role yang akan diupdate --}}
                    <div class="mb-3">
                        <label for="edit-nidn" class="form-label">NIDN Dosen</label>
                        <input type="text" class="form-control" id="edit-nidn" name="nidn" required />
                    </div>
                    <div class="mb-3">
                        <label for="edit-nama" class="form-label">Nama Dosen</label>
                        <input type="text" class="form-control" id="edit-nama" name="user_name" required />
                    </div>
                    <div class="mb-3">
                        <label for="edit-username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="edit-username" name="username" required />
                    </div>
                    <div class="mb-3">
                        <label for="edit-email" class="form-label">Email</label>
                        <input type="text" class="form-control" id="edit-email" name="user_email" required />
                    </div>
                    <div class="mb-3">
                        <label for="edit-no_telepon" class="form-label">Nomor Telepon</label>
                        <input type="text" class="form-control" id="edit-no_telepon" name="no_telepon" required />
                    </div>
                    <div class="mb-3">
                        <label for="edit-tempat_lahir" class="form-label">Tempat Lahir</label>
                        <input type="text" class="form-control" id="edit-tempat_lahir" name="tempat_lahir"
                            required />
                    </div>
                    <div class="mb-3">
                        <label for="edit-tanggal_lahir" class="form-label">Tanggal Lahir</label>
                        <input type="date" class="form-control" id="edit-tanggal_lahir" name="tanggal_lahir"
                            required />
                    </div>
                    <div class="mb-3">
                        <label for="edit-jenis_kelamin" class="form-label">Jenis Kelamin</label>
                        <select name="jenis_kelamin" id="edit-jenis_kelamin"
                            class="form-select @error('jenis_kelamin') is-invalid @enderror">
                            <option value="">-- Pilih JK --</option>
                            <option value="L">Laki - Laki</option>

                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit-agama" class="form-label">Agama</label>
                        <select name="agama" id="edit-agama" class="form-select @error('agama') is-invalid @enderror">
                            <option value="">-- Pilih Agama --</option>
                            <option value="P">Perempuan</option>
                            <option value="ISLAM">ISLAM</option>
                            <option value="HINDU">HINDU</option>
                            <option value="BUDHA">BUDHA</option>
                            <option value="PROTESTAN">PROTESTAN</option>
                            <option value="KATOLIK">KATOLIK</option>
                            <option value="KONGHUCU">KONGHUCU</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit-prov_id" class="form-label">Provinsi:</label>
                        <select name="prov_id" id="edit-prov_id"
                            class="form-select @error('prov_id') is-invalid @enderror"
                            onchange="RubahProvinsi(this.value)">
                        </select>
                        @error('prov_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="edit-kab_id" class="form-label">Kota/Kabupaten:</label>
                        <select name="kab_id" id="edit-kab_id"
                            class="form-select @error('kab_id') is-invalid @enderror" onchange="RubahKota(this.value)">
                        </select>
                        @error('kab_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="kec_id" class="form-label">Kecamatan:</label>
                        <select name="kec_id" id="edit-kec_id"
                            class="form-select @error('kec_id') is-invalid @enderror">
                        </select>
                        @error('kec_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="edit-kelurahan" class="form-label">edit-Kelurahan:</label>
                        <input name="kelurahan" id="edit-kelurahan"
                            class="form-control @error('kelurahan') is-invalid @enderror"
                            value="{{ old('edit-kelurahan') }}">
                        @error('kelurahan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="edit-alamat" class="form-label">Alamat:</label>
                        <textarea name="alamat" id="edit-alamat" class="form-control @error('alamat') is-invalid @enderror"
                            value="{{ old('alamat') }}"></textarea>
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
                    <p>Apakah Anda yakin ingin menghapus Dosen ini? **<span id="delete-role-name"></span>**?</p>
                    <input type="hidden" name="id" id="delete-id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Hapus Dosen Ini</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelector = document.getElementById('user_selection_type');
            const existingBlock = document.getElementById('existing-user-block');
            const newUserBlock = document.getElementById('new-user-block');
            const existingUserId = document.getElementById('user_id');
            const nidnExisting = document.getElementById('nidn_existing');
            const nidnNew = document.getElementById('nidn_new');

            // Field User Baru
            const newUserFields = {
                username: document.getElementById('username'),
                no_telepon: document.getElementById('no_telepon'),
                user_name: document.getElementById('user_name'),
                user_email: document.getElementById('user_email'),
                user_password: document.getElementById('user_password'),
                // Field Biodata (yang menyebabkan error)
                jenis_kelamin: document.getElementById('jenis_kelamin'),
                agama: document.getElementById('agama'),
                tempat_lahir: document.getElementById('tempat_lahir'),
                tanggal_lahir: document.getElementById('tanggal_lahir'),
                alamat: document.getElementById('alamat'),
                kelurahan: document.getElementById('kelurahan'),
                kec_id: document.getElementById('kec_id'), // Tambahkan ini
                kab_id: document.getElementById('kab_id'), // Tambahkan ini
                prov_id: document.getElementById('prov_id'), // Tambahkan ini
            };

            function toggleUserInputs() {
                if (typeSelector.value === 'new') {
                    // Skenario 1: Buat User Baru
                    existingBlock.classList.add('d-none');
                    newUserBlock.classList.remove('d-none');

                    // Menonaktifkan user_id agar tidak terkirim
                    existingUserId.value = "";
                    existingUserId.setAttribute('disabled', 'disabled');

                    // Mengaktifkan field User Baru agar terkirim
                    Object.values(newUserFields).forEach(field => field.removeAttribute('disabled'));
                    if (nidnExisting) nidnExisting.setAttribute('disabled', 'disabled');
                    if (nidnNew) nidnNew.removeAttribute('disabled');

                } else {
                    // Skenario 2: Pilih User Existing
                    existingBlock.classList.remove('d-none');
                    newUserBlock.classList.add('d-none');

                    // Mengaktifkan user_id
                    existingUserId.removeAttribute('disabled');

                    // Menonaktifkan field User Baru dan membersihkan nilainya
                    Object.values(newUserFields).forEach(field => {
                        field.setAttribute('disabled', 'disabled');
                        field.value = '';
                    });
                    if (nidnNew) nidnNew.setAttribute('disabled', 'disabled');
                    if (nidnExisting) nidnExisting.removeAttribute('disabled');
                }
            }

            // --- Logic Tambahan untuk Modal dan Error Validasi ---

            // 1. Cek apakah ada error validasi (old data) saat halaman dimuat
            const hasNewUserError = newUserFields.name && newUserFields.name.classList.contains('is-invalid');

            if (hasNewUserError) {
                // Jika ada error pada input 'new user', set selector ke 'new'
                typeSelector.value = 'new';
                // Tampilkan Modal (penting agar user bisa melihat errornya)
                var dekanModal = new bootstrap.Modal(document.getElementById('dekanFormModal'));
                dekanModal.show();
            }

            // 2. Terapkan toggle saat halaman dimuat atau setelah validasi gagal
            toggleUserInputs();

            // 3. Terapkan toggle saat pilihan berubah
            typeSelector.addEventListener('change', toggleUserInputs);

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


            Add();
        });

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
            $('.btn-edit').on('click', function() {
                // 1. Ambil data dari data-attributes
                var id = $(this).data('id');
                var nidn = $(this).data('nidn');
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
                // var izinAksesJson = $(this).data('izin_akses');
                RubahProvinsi(prov_id);
                RubahKota(kab_id);
                // 2. Isi data Role ke dalam form modal
                $('#edit-id').val(id);
                $('#edit-nidn').val(nidn);
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
                $('#edit-role-name').text(nama); // Tampilkan nama role di header modal

                // 3. Atur action form
                // Ganti '/role/' dengan URL route Anda yang benar, misal '/roles' atau sejenisnya
                $('#editRoleForm').attr('action', '/dekan/' + id);

            });
            $('.btn-delete').on('click', function() {
                var id = $(this).data('id');
                var nama = $(this).data('nama');

                // Isi data ke dalam form modal
                $('#delete-id').val(id);
                $('#delete-role-name').text(nama);

                // Atur action form
                // Ganti '/role/' dengan URL route Anda yang benar, misal '/roles' atau sejenisnya
                $('#deleteRoleForm').attr('action', '/dekan/' + id);
            });
        });
    </script>
@endsection
