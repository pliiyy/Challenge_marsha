@extends('apps.index')
@section('title', 'Jadwal')

@section('content')
    <div class="col-lg-10 col-md-9 content">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>🕒 Data Jadwal Perkuliahan</span>
                <form action="/jadwal" method="GET" class="mb-3 d-flex gap-2">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama jadwal"
                        value="{{ request('search') }}">

                    <select name="status" class="form-select">
                        <option value="">-- Semua Status --</option>
                        <option value="AKTIF" {{ request('status') == 'AKTIF' ? 'selected' : '' }}>Aktif</option>
                        <option value="NONAKTIF" {{ request('status') == 'NONAKTIF' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    <select name="semester_id" class="form-select">
                        <option value="">-- Semeter --</option>
                        @foreach ($semester as $item)
                            <option value="{{ $item->id }}" {{ request('semester_id') ? 'selected' : '' }}>
                                {{ $item->nama }}</option>
                        @endforeach
                    </select>

                    <button type="submit" class="btn btn-primary">Filter</button>
                </form>
                @if (!auth()->user()->mahasiswa)
                    <button class="btn btn-light btn-sm text-primary fw-semibold" data-bs-toggle="modal"
                        data-bs-target="#addJadwalModal">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Jadwal
                    </button>
                @endif
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Mata Kuliah</th>
                                <th>Dosen</th>
                                <th>Ruangan</th>
                                <th>Kelas</th>
                                <th>Hari</th>
                                <th>Jam</th>
                                <th>Semester</th>
                                <th>Tahun Ajaran</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jadwal as $index => $kls)
                                <tr>
                                    <td>{{ $jadwal->firstItem() + $index }}</td>
                                    <td><span>{{ $kls->suratTugasMengajar->matakuliah->nama }}</span></td>
                                    <td><span>{{ $kls->suratTugasMengajar->Dosen->user->Biodata->nama }}</span></td>
                                    <td>{{ $kls->Ruangan->nama }}</td>
                                    <td>{{ $kls->suratTugasMengajar->kelas->nama }}</td>
                                    <td>{{ $kls->hari }}</td>
                                    <td>{{ $kls->shift->nama }} ({{ $kls->shift->jam_mulai }} -
                                        {{ $kls->shift->jam_selesai }})</td>
                                    <td><span>{{ $kls->suratTugasMengajar->matakuliah->semester->nama }}</span></td>
                                    <td><span>{{ $kls->suratTugasMengajar->matakuliah->semester->tahun_akademik }}</span>
                                    </td>
                                    <td>
                                        @if ($kls->status == 'AKTIF')
                                            <span class="badge bg-success">{{ ucfirst(strtolower($kls->status)) }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst(strtolower($kls->status)) }}</span>
                                        @endif
                                    </td>
                                    @if (!auth()->user()->mahasiswa)
                                        <td>
                                            <button type="button" class="btn btn-outline-primary btn-sm btn-edit"
                                                data-bs-toggle="modal" data-bs-target="#editJadwalModal"
                                                data-id="{{ $kls->id }}"
                                                data-surat_tugas_mengajar_id="{{ $kls->surat_tugas_mengajar_id }}"
                                                data-ruangan_id="{{ $kls->ruangan_id }}" data-hari="{{ $kls->hari }}"
                                                data-shift_id="{{ $kls->shift_id }}"> <i class="bi bi-pencil"></i>
                                            </button>
                                            {{-- Tombol Delete: Memicu modal konfirmasi hapus --}}
                                            <button type="button" class="btn btn-outline-danger btn-sm btn-delete"
                                                data-bs-toggle="modal" data-bs-target="#deleteRoleModal"
                                                data-id="{{ $kls->id }}"data-nama="{{ $kls->suratTugasMengajar->Matakuliah->nama }}">
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
            {{ $jadwal->links() }}
        </div>
        <div class="mt-4 alert alert-info bg-opacity-25 border-0 text-primary">
            <i class="bi bi-info-circle me-2"></i>
            Kelola jadwal perkuliahan dengan memastikan tidak ada bentrok antar waktu dan ruangan.
        </div>
    </div>
    </div>
    </div>

    <!-- Modal Tambah Jadwal -->
    <div class="modal fade" id="addJadwalModal" tabindex="-1" aria-labelledby="addJadwalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form class="modal-content" method="POST" action="/jadwal">
                @csrf
                @method('POST')
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addJadwalModalLabel">Tambah Jadwal Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Mata Kuliah</label>
                        <select class="form-select" name="surat_tugas_mengajar_id" id="matkulSelect">
                            @foreach ($surat as $item)
                                <option value="{{ $item->id }}"
                                    data-dosen="{{ $item->dosen->user->biodata->keterangan }}">
                                    {{ $item->matakuliah->nama }}
                                    ({{ $item->kelas->nama }})
                                    {{ $item->dosen->User->biodata->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Ruangan</label>
                        <select class="form-select" name="ruangan_id">
                            @foreach ($ruangan as $item)
                                <option value="{{ $item->id }}">{{ $item->nama }} ({{ $item->kode }})</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Preferensi Dosen akan muncul di sini -->
                    <div class="col-md-12 text-xs mt-2" id="preferensiDosen" style="display:none;">
                        <div class="text-danger" id="preferensiText" style="font-size: 0.85rem;">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Hari</label>
                        <select class="form-select" name="hari">
                            <option value="SENIN">Senin</option>
                            <option value="SELASA">Selasa</option>
                            <option value="RABU">Rabu</option>
                            <option value="KAMIS">Kamis</option>
                            <option value="JUMAT">Jumat</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jam</label>
                        <select class="form-select" name="shift_id">
                            @foreach ($shift as $item)
                                <option value="{{ $item->id }}">{{ $item->nama }} ({{ $item->jam_mulai }} -
                                    {{ $item->jam_selesai }})</option>
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
    <div class="modal fade" id="editJadwalModal" tabindex="-1" aria-labelledby="editJadwalModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form class="modal-content" method="POST" action="/pindah_jadwal">
                @csrf
                @method('POST')
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addJadwalModalLabel">Pindahkan Jadwal</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-6">
                        <input type="hidden" name="id" id="edit-id">
                        <label class="form-label">Mata Kuliah</label>
                        <select class="form-select" name="surat_tugas_mengajar_id" id="edit-surat_tuga_mengajar_id"
                            disabled>
                            @foreach ($surat as $item)
                                <option value="{{ $item->id }}">{{ $item->matakuliah->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Ruangan</label>
                        <select class="form-select" name="ruangan_id" id="edit-ruangan_id">
                            @foreach ($ruangan as $item)
                                <option value="{{ $item->id }}">{{ $item->nama }} ({{ $item->kode }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Hari</label>
                        <select class="form-select" name="hari" id="edit-hari">
                            <option value="SENIN">Senin</option>
                            <option value="SELASA">Selasa</option>
                            <option value="RABU">Rabu</option>
                            <option value="KAMIS">Kamis</option>
                            <option value="JUMAT">Jumat</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jam</label>
                        <select class="form-select" name="shift_id" id="edit-shift_id">
                            @foreach ($shift as $item)
                                <option value="{{ $item->id }}">{{ $item->nama }} ({{ $item->jam_mulai }} -
                                    {{ $item->jam_selesai }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Alasan</label>
                        <textarea class="form-control" rows="3" placeholder="Alasan Pindah Jadwal" name="alasan"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
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
                    <p>Apakah Anda yakin ingin menghapus Jadwal ini? **<span id="delete-role-name"></span>**?</p>
                    <input type="hidden" name="id" id="delete-id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Hapus Jadwal Ini</button>
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
                var surat_tugas_mengajar_id = $(this).data('surat_tugas_mengajar_id');
                var ruangan_id = $(this).data('ruangan_id');
                var shift_id = $(this).data('shift_id');
                var hari = $(this).data('hari');

                // 2. Isi data Role ke dalam form modal
                $('#edit-id').val(id);
                $('#edit-surat_tugas_mengajar_id').val(surat_tugas_mengajar_id);
                $('#edit-ruangan_id').val(ruangan_id);
                $('#edit-shift_id').val(shift_id);
                $('#edit-hari').val(hari);

                // 3. Atur action form
                // Ganti '/role/' dengan URL route Anda yang benar, misal '/roles' atau sejenisnya
                $('#editRoleForm').attr('action', '/pindah_jadwal/' + id);

            });
            $('.btn-delete').on('click', function() {
                var id = $(this).data('id');
                var nama = $(this).data('nama');

                // Isi data ke dalam form modal
                $('#delete-id').val(id);
                $('#delete-role-name').text(nama);

                // Atur action form
                // Ganti '/role/' dengan URL route Anda yang benar, misal '/roles' atau sejenisnya
                $('#deleteRoleForm').attr('action', '/jadwal/' + id);
            });
            $('#matkulSelect').on('change', function() {
                var dosen = $(this).find(':selected').data('dosen');
                if (dosen) {
                    $('#preferensiText').text('Preferensi Dosen: ' + dosen);
                    $('#preferensiDosen').show();
                } else {
                    $('#preferensiDosen').hide();
                }
            });
        });
    </script>
@endsection
