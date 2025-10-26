@extends('apps.index')
@section('title', 'Jadwal')

@section('content')
    <div class="col-md-9 col-lg-10 content">
        <h3 class="text-center mb-2 fw-bold">JADWAL MATA KULIAH</h3>
        <h5 class="text-center text-secondary mb-4">SEMESTER {{ $sems?->tipe }}
            {{ $sems?->tahun_akademik }}
        </h5>

        {{-- Tabs --}}
        <div class="d-flex justify-content-center mb-3">
            @foreach ($availableTabs as $tab)
                <a href="{{ route('jadwal_global', ['tab' => $tab]) }}"
                    class="btn btn-sm me-2 {{ $tab === $activeTab ? 'btn-primary' : 'btn-outline-secondary' }}">
                    {{ $tab }}
                </a>
            @endforeach
            {{-- <a href="{{ route('jadwal_global', ['tab' => 'RUANG']) }}"
                class="btn btn-sm {{ $activeTab === 'RUANG' ? 'btn-primary' : 'btn-outline-secondary' }}">
                RUANG
            </a> --}}
            <a href="{{ route('jadwal_export_ruang', ['tab' => $activeTab]) }}" target="_blank" class="btn btn-sm btn-success">
                <i class="bi bi-file-pdf"></i> DOWNLOAD PDF ({{ $activeTab }})
            </a>
        </div>

        {{-- Tabel Jadwal --}}
        @if ($grouped->isEmpty())
            <div class="alert alert-warning text-center">Tidak ada data untuk tab {{ $activeTab }}.</div>
        @else
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white fw-bold">
                    Semester {{ $activeTab }}
                </div>
                <div class="card-body">
                    @foreach ($grouped as $hari => $kelasGroup)
                        <div class="mb-4">
                            <div class="bg-warning text-dark fw-bold p-2 mb-2">{{ $hari }}</div>

                            <table class="table table-bordered table-striped align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>WAKTU</th>
                                        @foreach ($kelasGroup as $kelasNama => $jadwalPerKelas)
                                            <th class="text-center">{{ $kelasNama }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kelasGroup->flatten()->groupBy(fn($j) => $j->shift->jam_mulai . ' - ' . $j->shift->jam_selesai) as $waktu => $jadwalWaktu)
                                        <tr>
                                            <td class="fw-bold">{{ $waktu }}</td>
                                            @foreach ($kelasGroup as $kelasNama => $jadwalPerKelas)
                                                @php
                                                    $data = $jadwalPerKelas->firstWhere(
                                                        'shift.jam_mulai',
                                                        explode(' - ', $waktu)[0],
                                                    );
                                                @endphp
                                                <td>
                                                    @if ($data)
                                                        <div>{{ $data->suratTugasMengajar->mataKuliah->nama ?? '-' }}</div>
                                                        <small class="text-muted">
                                                            {{ $data->suratTugasMengajar->dosen->user->biodata->nama ?? '-' }}<br />
                                                            {{ $data->ruangan->kode ?? '-' }}
                                                            ({{ $data->ruangan->nama ?? '-' }})
                                                        </small>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection
