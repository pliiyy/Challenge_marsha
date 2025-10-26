@extends('apps.index')
@section('title', 'Data')

@section('content')
    <!-- Content -->
    <div class="col-lg-10 col-md-9 content">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>📋 Data Pengguna</span>
                {{-- <button class="btn btn-light btn-sm text-primary fw-semibold">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Data
                        </button> --}}
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Tanggal Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($user as $index => $item)
                                <tr>
                                    <td>{{ $user->firstItem() + $index }}</td>
                                    <td>{{ $item->Biodata->nama }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>
                                        @if ($item->dekan)
                                            <span class="badge bg-primary">Dekan</span>
                                        @endif
                                        @if ($item->kaprodi)
                                            <span class="badge bg-primary">Kaprodi</span>
                                        @endif
                                        @if ($item->sekprodi)
                                            <span class="badge bg-primary">Sekprodi</span>
                                        @endif
                                        @if ($item->dosen)
                                            <span class="badge bg-primary">Dosen</span>
                                        @endif
                                        @if ($item->kosma)
                                            <span class="badge bg-primary">Kosma</span>
                                        @endif
                                        @if ($item->mahasiswa)
                                            <span class="badge bg-primary">Mahasiswa</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->created_at }}</td>
                                    <td>
                                        {{-- <button class="btn btn-outline-primary btn-sm"><i class="bi bi-pencil"></i></button>
                                        <button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button> --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $user->links() }}
                </div>
            </div>
        </div>

        <div class="mt-4">
            <div class="alert alert-info bg-opacity-25 border-0 text-primary">
                <i class="bi bi-info-circle me-2"></i>
                Kamu dapat mengelola data pengguna di sini. Gunakan tombol <strong>Edit</strong> atau <strong>Hapus</strong>
                untuk memodifikasi data.
            </div>
        </div>
    </div>
    </div>
@endsection
