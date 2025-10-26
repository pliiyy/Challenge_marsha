@extends('apps.index')
@section('title', 'Dashboard')

@section('content')
    <div class="col-md-9 col-lg-10 content">
        <h2 class="fw-semibold mb-4 text-primary">Dashboard</h2>

        {{-- Kartu Pengguna, Notifikasi, dan Pengaturan --}}
        <div class="row g-4 mt-4">
            <!-- Card Pengguna -->
            <div class="col-md-4">
                <a href="/data" style="text-decoration: none">
                    <div class="card p-4">
                        <div class="d-flex align-items-center">
                            <div class="icon bg-primary bg-opacity-10 text-primary rounded-3 p-3 me-3">
                                <i class="bi bi-people fs-4"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-1">Pengguna</h5>
                                <p class="card-text text-muted mb-0">{{ $user }} terdaftar</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card Notifikasi -->
            <div class="col-md-4">
                <div class="card p-4">
                    <div class="d-flex align-items-center">
                        <div class="icon bg-warning bg-opacity-10 text-warning rounded-3 p-3 me-3">
                            <i class="bi bi-bell fs-4"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-1">Notifikasi</h5>
                            <p class="card-text text-muted mb-0">{{ $total }} pesan belum dibaca</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Pengaturan -->
            <div class="col-md-4">
                <a href="/settings" style="text-decoration: none">
                    <div class="card p-4">
                        <div class="d-flex align-items-center">
                            <div class="icon bg-success bg-opacity-10 text-success rounded-3 p-3 me-3">
                                <i class="bi bi-gear fs-4"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-1">Pengaturan</h5>
                                <p class="card-text text-muted mb-0">Kelola preferensi akun</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        {{-- Kartu Data Mahasiswa --}}
        <div class="card p-4 mt-4">
            <h5 class="fw-semibold text-secondary mb-3">Data {{ auth()->user()->Dekan ? 'Dekan' : '' }}
                {{ auth()->user()->Dosen ? 'Dosen' : '' }} {{ auth()->user()->Kaprodi ? 'Kaprodi' : '' }}
                {{ auth()->user()->Sekprodi ? 'Sekprodi' : '' }} {{ auth()->user()->Mahasiswa ? 'Mahasiswa' : '' }}
                {{ auth()->user()->Kosma ? 'Kosma' : '' }}</h5>
            <div class="row">
                <div class="col-md-6 col-lg-3 mb-3">
                    <p class="fw-semibold mb-1 text-muted">Nama</p>
                    <p class="mb-0">{{ auth()->user()->Biodata->nama ?? 'Rafly Adrian Firmansyah' }}</p>
                </div>
                @if (auth()->user()->Dekan)
                    <div class="col-md-6 col-lg-3 mb-3">
                        <p class="fw-semibold mb-1 text-muted">NIDN</p>
                        <p class="mb-0">{{ auth()->user()->Dekan->nidn ?? '242505017' }}</p>
                    </div>
                @elseif(auth()->user()->Dosen)
                    <div class="col-md-6 col-lg-3 mb-3">
                        <p class="fw-semibold mb-1 text-muted">NIDN</p>
                        <p class="mb-0">{{ auth()->user()->Dosen->nidn ?? '242505017' }}</p>
                    </div>
                @elseif(auth()->user()->Mahasiswa)
                    <div class="col-md-6 col-lg-3 mb-3">
                        <p class="fw-semibold mb-1 text-muted">NIM</p>
                        <p class="mb-0">{{ auth()->user()->mahasiswa->nim ?? '242505017' }}</p>
                    </div>
                @else
                    <div class="col-md-6 col-lg-3 mb-3">
                        <p class="fw-semibold mb-1 text-muted">NIDN</p>
                        <p class="mb-0">
                            {{ auth()->user()->kaprodi->nidn ?? (auth()->user()->sekprodi->nidn ?? '242505017') }}</p>
                    </div>
                @endif
                @if (auth()->user()->Dekan)
                    <div class="col-md-6 col-lg-3 mb-3">
                        <p class="fw-semibold mb-1 text-muted">Fakultas</p>
                        @foreach (auth()->user()->dekan->Fakultas as $fak)
                            <p class="mb-0">{{ $fak->nama }}</p>
                        @endforeach
                    </div>
                @elseif(auth()->user()->Kaprodi || auth()->user()->Sekprodi)
                    <div class="col-md-6 col-lg-3 mb-3">
                        <p class="fw-semibold mb-1 text-muted">Prodi</p>
                        @if (auth()->user()->Kaprodi)
                            <p class="mb-0">{{ auth()->user()->Kaprodi->prodi->nama ?? 'S1 - Sistem Informasi' }}></p>
                        @else
                            <p class="mb-0">{{ auth()->user()->Sekprodi->prodi->nama ?? 'S1 - Sistem Informasi' }}></p>
                        @endif
                        </p>
                    </div>
                @else
                    <div class="col-md-6 col-lg-3 mb-3">
                        <p class="fw-semibold mb-1 text-muted">Prodi</p>
                        <p class="mb-0">{{ auth()->user()->Mahasiswa->prodi->nama ?? 'S1 - Sistem Informasi' }}
                        </p>
                    </div>
                @endif
                @if (auth()->user()->mahasiswa)
                    <div class="col-md-6 col-lg-3 mb-3">
                        <p class="fw-semibold mb-1 text-muted">Jenis Kelas</p>
                        <p class="mb-0">{{ auth()->user->mahasiswa->kelas->tipe ?? 'Reguler' }}</p>
                    </div>
                @else
                    <div class="col-md-6 col-lg-3 mb-3">
                        <p class="fw-semibold mb-1 text-muted">Jenis Kelas</p>
                        <p class="mb-0">Reguller/Non Reguller</p>
                    </div>
                @endif
            </div>
        </div>
        <div style="margin-top: 20px"></div>
        <div id='calendar' style="height:500px;width: 80%;margin: auto"></div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: '/rekap', // ambil semua jadwal dari controller
                timeZone: 'local',
                locale: 'id', // <-- tambahkan ini
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                },
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                editable: false,
                selectable: false,
                dayMaxEvents: true // otomatis membuat “+N more” jika terlalu banyak event
            });

            calendar.render();
        });
    </script>
@endsection
