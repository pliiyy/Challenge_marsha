@extends('apps.index')
@section('title', 'Surat Tugas')

@section('content')
    <div class="col-lg-10 col-md-9 content">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>📄 Surat Tugas Mengajar</span>
                @if (auth()->user()->dekan || auth()->user()->kaprodi)
                    <button class="btn btn-light btn-sm text-primary fw-semibold" data-bs-toggle="modal"
                        data-bs-target="#addSuratModal">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Surat Tugas
                    </button>
                @endif
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Dosen</th>
                                <th>Prodi</th>
                                <th>Mata Kuliah</th>
                                <th>Kelas</th>
                                <th>Cetak Surat</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($surat as $index => $kls)
                                <tr>
                                    <td>{{ $surat->firstItem() + $index }}</td>
                                    <td><span>{{ $kls->dosen->user->Biodata->nama }}</span></td>
                                    <td>{{ $kls->matakuliah->Prodi->nama }}</td>
                                    <td>{{ $kls->matakuliah->nama }}</td>
                                    <td>{{ $kls->kelas->nama }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info btn-tampilkan-pdf"
                                            title="Lihat PDF" data-surat-id="{{ $kls->id }}">
                                            <i class="bi bi-file-earmark-pdf-fill"></i>
                                        </button>
                                    </td>
                                    <td>
                                        @if ($kls->status == 'APPROVED')
                                            <span class="badge bg-success">{{ ucfirst(strtolower($kls->status)) }}</span>
                                        @elseif($kls->status == 'PENDING')
                                            <span class="badge bg-warning">{{ ucfirst(strtolower($kls->status)) }}</span>
                                        @else
                                            <span class="badge bg-danger">{{ ucfirst(strtolower($kls->status)) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($kls->status === 'PENDING' && (auth()->user()->dekan || auth()->user()->kaprodi))
                                            <button type="button" class="btn btn-outline-primary btn-sm btn-edit"
                                                data-bs-toggle="modal" data-bs-target="#editRoleModal"
                                                data-id="{{ $kls->id }}" data-dosen_id="{{ $kls->dosen_id }}"
                                                data-matakuliah_id="{{ $kls->matakuliah_id }}"
                                                data-status="{{ $kls->status }}" data-kelas_id="{{ $kls->kelas_id }}">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                        @endif
                                        @if ($kls->status == 'PENDING' && auth()->user()->dekan)
                                            <button type="button" class="btn btn-outline-success btn-sm btn-action"
                                                data-bs-toggle="modal" data-bs-target="#actionModal"
                                                data-id="{{ $kls->id }}" data-kelas-id="{{ $kls->kelas_id }}"
                                                data-dosen-id="{{ $kls->dosen_id }}"
                                                data-matakuliah-id="{{ $kls->matakuliah_id }}" data-action="approve"
                                                title="Setujui Tugas">
                                                <i class="bi bi-check-circle"></i>
                                            </button>

                                            <button type="button" class="btn btn-outline-danger btn-sm btn-action"
                                                data-bs-toggle="modal" data-bs-target="#actionModal"
                                                data-id="{{ $kls->id }}" data-kelas-id="{{ $kls->kelas_id }}"
                                                data-dosen-id="{{ $kls->dosen_id }}"
                                                data-matakuliah-id="{{ $kls->matakuliah_id }}" data-action="reject"
                                                title="Tolak Tugas">
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-outline-secondary btn-sm" disabled>
                                                <i class="bi bi-slash-circle" title="Sudah Diproses"></i>
                                            </button>
                                        @endif
                                        <button type="button" class="btn btn-outline-danger btn-sm btn-delete"
                                            data-bs-toggle="modal" data-bs-target="#deleteRoleModal"
                                            data-id="{{ $kls->id }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="mt-3">
            {{ $surat->links() }}
        </div>
        <div class="mt-4 alert alert-info bg-opacity-25 border-0 text-primary">
            <i class="bi bi-info-circle me-2"></i>
            Daftar surat tugas mengajar yang telah diterbitkan untuk setiap dosen.
        </div>
    </div>

    {{-- Modal Preview PDF --}}
    <div class="modal fade" id="pdfModal" tabindex="-1" aria-labelledby="pdfModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pdfModalLabel">Preview PDF</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <iframe id="pdfIframe" style="width: 100%; height: 600px;" frameborder="0"></iframe>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Tambah/Edit/Action/Delete tetap sama --}}
    {{-- ... (biarkan modal lain seperti di kode aslimu, tidak perlu diubah) ... --}}
    <div class="modal fade" id="actionModal" tabindex="-1" aria-labelledby="actionModalLabel" aria-hidden="true">
        <div class="modal-dialog"> {{-- Form akan diisi action URL-nya oleh JavaScript --}} <form class="modal-content" method="POST" id="actionForm"> @csrf
                {{-- Kita akan menggunakan method PUT/PATCH untuk update status --}} @method('PUT') <div class="modal-header text-white" id="actionModalHeader">
                    <h5 class="modal-title" id="actionModalLabel">Konfirmasi Aksi</h5> <button type="button"
                        class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body"> Apakah Anda yakin ingin <strong id="actionVerb">memproses</strong> surat tugas ini?
                    {{-- Input tersembunyi untuk menyimpan status baru --}} <input type="hidden" name="status" id="actionStatusInput"> <input
                        type="hidden" name="kelas_id" id="valueKelasId"> <input type="hidden" name="dosen_id"
                        id="valueDosenId"> <input type="hidden" name="matakuliah_id" id="valueMatakuliahId"> </div>
                <div class="modal-footer"> <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">Batal</button> <button type="submit" class="btn"
                        id="actionConfirmButton">Ya, Proses</button> </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg"> {{-- Form action akan diisi oleh JavaScript --}} <form class="modal-content" id="editRoleForm"
                action="" method="POST"> @csrf @method('PUT') {{-- Gunakan method PUT untuk update --}} <div
                    class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editRoleModalLabel">Edit Role: <span id="edit-role-name"></span></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body row g-3"> <input type="hidden" name="id" id="edit-id">
                    {{-- ID role yang akan diupdate --}} <input type="hidden" name="status" id="edit-status"> {{-- ID role yang akan diupdate --}}
                    <div class="col-md-6"> <label class="form-label">Dosen</label> <select class="form-select"
                            name="dosen_id" id="edit-dosen_id">
                            @foreach ($dosen as $item)
                                <option value="{{ $item->id }}">{{ $item->user->Biodata->nama }}</option>
                            @endforeach
                        </select> </div>
                    <div class="col-md-6"> <label class="form-label">Mata Kuliah</label> <select class="form-select"
                            name="matakuliah_id" id="edit-matakuliah_id">
                            @foreach ($matakuliah as $item)
                                <option value="{{ $item->id }}">{{ $item->nama }}</option>
                            @endforeach
                        </select> </div>
                    <div class="col-md-4"> <label class="form-label">Kelas</label> <select class="form-select"
                            name="kelas_id" id="edit-kelas_id">
                            @foreach ($kelas as $item)
                                <option value="{{ $item->id }}">{{ $item->nama }}</option>
                            @endforeach
                        </select> </div>
                </div>
                <div class="modal-footer"> <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">Batal</button> <button type="submit" class="btn btn-primary">Simpan
                        Perubahan</button> </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="deleteRoleModal" tabindex="-1" aria-labelledby="deleteRoleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-sm"> {{-- Form action akan diisi oleh JavaScript --}} <form class="modal-content" id="deleteRoleForm"
                action="" method="POST"> @csrf @method('DELETE') {{-- Gunakan method DELETE untuk hapus --}} <div
                    class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteRoleModalLabel">Konfirmasi Hapus</h5> <button type="button"
                        class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus Surat Tugas Mengajar ini? **<span id="delete-role-name"></span>**?
                    </p> <input type="hidden" name="id" id="delete-id">
                </div>
                <div class="modal-footer"> <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">Batal</button> <button type="submit" class="btn btn-danger">Ya, Hapus
                        Data Ini</button> </div>
            </form>
        </div>
    </div>
    <!-- Modal Tambah Surat -->
    <div class="modal fade" id="addSuratModal" tabindex="-1" aria-labelledby="addSuratModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form class="modal-content" method="POST">
                @csrf
                @method('POST')
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addSuratModalLabel">Tambah Surat Tugas Mengajar</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Dosen</label>
                        <select class="form-select" name="dosen_id">
                            @foreach ($dosen as $item)
                                <option value="{{ $item->id }}">{{ $item->user->Biodata->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Mata Kuliah</label>
                        <select class="form-select" name="matakuliah_id">
                            @foreach ($matakuliah as $item)
                                <option value="{{ $item->id }}">{{ $item->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Kelas</label>
                        <select class="form-select" name="kelas_id">
                            @foreach ($kelas as $item)
                                <option value="{{ $item->id }}">{{ $item->nama }}</option>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // === CETAK PDF ===
            const pdfButtons = document.querySelectorAll('.btn-tampilkan-pdf');
            const pdfIframe = document.getElementById('pdfIframe');
            const pdfModal = new bootstrap.Modal(document.getElementById('pdfModal'));

            pdfButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const suratId = this.getAttribute('data-surat-id');
                    const pdfUrl = "{{ route('laporan.pdf.show') }}" +
                        "?id=" + suratId + "&t=" + new Date().getTime();

                    pdfIframe.src = pdfUrl;
                    pdfModal.show();
                });
            });

            document.getElementById('pdfModal').addEventListener('hidden.bs.modal', function() {
                pdfIframe.src = '';
            });

            // === LOGIKA APPROVE / REJECT ===
            const actionButtons = document.querySelectorAll('.btn-action');
            const actionForm = document.getElementById('actionForm');
            const actionModalLabel = document.getElementById('actionModalLabel');
            const actionModalHeader = document.getElementById('actionModalHeader');
            const actionVerb = document.getElementById('actionVerb');
            const actionStatusInput = document.getElementById('actionStatusInput');
            const dosen = document.getElementById('valueDosenId');
            const kelas = document.getElementById('valueKelasId');
            const matkul = document.getElementById('valueMatakuliahId');
            const actionConfirmButton = document.getElementById('actionConfirmButton');

            actionButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const suratId = this.getAttribute('data-id');
                    const kelasId = this.getAttribute('data-kelas-id');
                    const dosenId = this.getAttribute('data-dosen-id');
                    const matakuliahId = this.getAttribute('data-matakuliah-id');
                    const action = this.getAttribute('data-action');

                    let verb = '';
                    let statusValue = '';
                    let headerClass = '';
                    let buttonText = '';

                    if (action === 'approve') {
                        verb = 'menyetujui';
                        statusValue = 'APPROVED';
                        headerClass = 'bg-success';
                        buttonText = 'Ya, Setujui';
                    } else if (action === 'reject') {
                        verb = 'menolak';
                        statusValue = 'REJECTED';
                        headerClass = 'bg-danger';
                        buttonText = 'Ya, Tolak';
                    }

                    actionModalLabel.textContent = `Konfirmasi ${verb.toUpperCase()}`;
                    actionVerb.textContent = verb;
                    actionModalHeader.className = `modal-header text-white ${headerClass}`;
                    actionConfirmButton.className = `btn ${headerClass}`;
                    actionConfirmButton.textContent = buttonText;
                    actionStatusInput.value = statusValue;
                    kelas.value = kelasId;
                    dosen.value = dosenId;
                    matkul.value = matakuliahId;
                    actionForm.action = `/surat_tugas/${suratId}`;
                });
            });
        });

        // === EDIT & DELETE ===
        $(document).ready(function() {
            $('.btn-edit').on('click', function() {
                var id = $(this).data('id');
                var dosen_id = $(this).data('dosen_id');
                var kelas_id = $(this).data('kelas_id');
                var matakuliah_id = $(this).data('matakuliah_id');
                var status = $(this).data('status');

                $('#edit-id').val(id);
                $('#edit-dosen_id').val(dosen_id);
                $('#edit-kelas_id').val(kelas_id);
                $('#edit-matakuliah_id').val(matakuliah_id);
                $('#edit-status').val(status);
                $('#editRoleForm').attr('action', '/surat_tugas/' + id);
            });

            $('.btn-delete').on('click', function() {
                var id = $(this).data('id');
                var nama = $(this).data('nama');
                $('#delete-id').val(id);
                $('#delete-role-name').text(nama);
                $('#deleteRoleForm').attr('action', '/surat_tugas/' + id);
            });
        });
    </script>
@endsection
