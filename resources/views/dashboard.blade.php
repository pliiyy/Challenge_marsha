@extends('apps.index')
@section('title', 'Dashboard')

@section('content')
    <style>
        /* Gaya Tambahan untuk Card Statistik */
        .stat-card {
            transition: all 0.3s ease;
            cursor: pointer;
            border-left: 5px solid var(--primary-color);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .stat-icon-wrapper {
            background-color: var(--primary-light);
            color: white;
            padding: 15px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(0, 188, 212, 0.4);
        }

        .stat-card-text {
            color: var(--primary-dark);
            font-weight: 600;
        }
    </style>

    <div class="col-md-9 col-lg-10 content">
        <h2 class="fw-bold mb-4" style="color: var(--primary-dark);">Selamat Datang di Web Marsha 👋</h2>
        <p class="text-muted small">Ringkasan aktivitas dan data utama sistem.</p>

        {{-- BAGIAN 1: KARTU STATISTIK (RINGKASAN DATA) --}}
        <div class="row g-4 mb-5 row-cols-1 row-cols-sm-2 row-cols-md-4">

            <div class="col">
                <a href="/fakultas" class="text-dark stat-card card p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Total Fakultas</p>
                            <h4 class="stat-card-text">{{ $fakultas ?? 4 }}</h4>
                        </div>
                        <div class="stat-icon-wrapper bg-info">
                            <i class="bi bi-building fs-5"></i>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col">
                <a href="/prodi" class="text-dark stat-card card p-3 h-100" style="border-left-color: #ff9800;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Total Program Studi</p>
                            <h4 class="stat-card-text" style="color: #ff9800;">{{ $prodi ?? 12 }}</h4>
                        </div>
                        <div class="stat-icon-wrapper" style="background-color: #ff9800;">
                            <i class="bi bi-mortarboard fs-5"></i>
                        </div>
                    </div>
                </a>
            </div>

            {{-- <div class="col">
                <a href="/matakuliah" class="text-dark stat-card card p-3 h-100" style="border-left-color: #4caf50;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Total Mata Kuliah</p>
                            <h4 class="stat-card-text" style="color: #4caf50;">{{ $matakuliah ?? 150 }}</h4>
                        </div>
                        <div class="stat-icon-wrapper" style="background-color: #4caf50;">
                            <i class="bi bi-journal-bookmark fs-5"></i>
                        </div>
                    </div>
                </a>
            </div> --}}

            <div class="col">
                <a href="/data" class="text-dark stat-card card p-3 h-100"
                    style="border-left-color: var(--primary-dark);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Pengguna Terdaftar</p>
                            <h4 class="stat-card-text">{{ $user ?? 250 }}</h4>
                        </div>
                        <div class="stat-icon-wrapper bg-primary">
                            <i class="bi bi-person-circle fs-5"></i>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        {{-- BAGIAN 2: KALENDER UTAMA & NOTIFIKASI --}}
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card p-4 h-100">
                    <h5 class="fw-semibold mb-3" style="color: var(--primary-dark);">Jadwal Perkuliahan Global</h5>
                    <div id='calendar' style="height:500px; width: 100%;"></div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card p-4 h-100">
                    <h5 class="fw-semibold mb-3" style="color: var(--primary-dark);">Informasi & Pengumuman</h5>
                    <div class="list-group list-group-flush small">
                        <div class="list-group-item d-flex align-items-start py-2">
                            <i class="bi bi-check-circle-fill text-success me-3 mt-1"></i>
                            <div>
                                <div class="fw-semibold">Jadwal Disetujui</div>
                                <small class="text-muted">Jadwal Semester Ganjil 2024 telah final.</small>
                            </div>
                        </div>
                        <div class="list-group-item d-flex align-items-start py-2">
                            <i class="bi bi-exclamation-triangle-fill text-warning me-3 mt-1"></i>
                            <div>
                                <div class="fw-semibold">Perlu Tindakan</div>
                                <small class="text-muted">{{ $total ?? 3 }} permintaan pindah jadwal menunggu
                                    respon.</small>
                            </div>
                        </div>
                        <div class="list-group-item d-flex align-items-start py-2">
                            <i class="bi bi-info-circle-fill text-info me-3 mt-1"></i>
                            <div>
                                <div class="fw-semibold">Update Aplikasi</div>
                                <small class="text-muted">Fitur Barter Jadwal baru telah tersedia.</small>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="mt-4 text-end">
                        <a href="/notifications" class="text-primary small fw-semibold text-decoration-none">Lihat Semua</a>
                    </div> --}}
                </div>
            </div>
        </div>

        {{-- BAGIAN 3: DATA PENGGUNA (PROFIL SINGKAT) --}}
        <div class="card p-4 mt-4">
            <h5 class="fw-semibold mb-3" style="color: var(--primary-dark);">
                Detail Profil Singkat
            </h5>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-3">

                <div class="col">
                    <p class="fw-semibold mb-1 text-muted small">Nama Lengkap</p>
                    <p class="mb-0 fw-bold">{{ auth()->user()->Biodata->nama ?? 'Rafly Adrian Firmansyah' }}</p>
                </div>

                <div class="col">
                    <p class="fw-semibold mb-1 text-muted small">ID Pengguna</p>
                    <p class="mb-0">
                        @if (auth()->user()->Dekan)
                            NIDN: {{ auth()->user()->Dekan->nidn ?? '242505017' }} (Dekan)
                        @elseif(auth()->user()->Dosen)
                            NIDN: {{ auth()->user()->Dosen->nidn ?? '242505017' }} (Dosen)
                        @elseif(auth()->user()->Mahasiswa)
                            NIM: {{ auth()->user()->mahasiswa->nim ?? '242505017' }} (Mahasiswa)
                        @else
                            NIDN: {{ auth()->user()->kaprodi->nidn ?? (auth()->user()->sekprodi->nidn ?? '242505017') }}
                            (Admin)
                        @endif
                    </p>
                </div>

                <div class="col">
                    <p class="fw-semibold mb-1 text-muted small">Departemen/Prodi</p>
                    <p class="mb-0">
                        @if (auth()->user()->Dekan)
                            @foreach (auth()->user()->dekan->Fakultas ?? ['Fakultas Teknik'] as $fak)
                                {{ $fak->nama }}
                            @endforeach
                        @elseif(auth()->user()->Kaprodi || auth()->user()->Sekprodi)
                            {{ auth()->user()->Kaprodi->prodi->nama ?? (auth()->user()->Sekprodi->prodi->nama ?? 'S1 - Sistem Informasi') }}
                        @else
                            {{ auth()->user()->Mahasiswa->prodi->nama ?? 'S1 - Sistem Informasi' }}
                        @endif
                    </p>
                </div>

                <div class="col">
                    <p class="fw-semibold mb-1 text-muted small">Jenis Kelas</p>
                    <p class="mb-0">
                        @if (auth()->user()->mahasiswa)
                            {{ auth()->user()->mahasiswa->kelas->tipe ?? 'Reguler' }}
                        @else
                            Reguler/Non Reguler
                        @endif
                    </p>
                </div>
            </div>
            <div class="mt-4 text-end">
                <a href="/profil" class="btn btn-sm btn-outline-primary fw-semibold">Lihat Profil Lengkap <i
                        class="bi bi-arrow-right"></i></a>
            </div>
        </div>

    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: '/rekap',
                timeZone: 'local',
                locale: 'id',
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
                dayMaxEvents: true,

                buttonText: {
                    today: 'Hari Ini',
                    month: 'Bulan',
                    week: 'Minggu',
                    day: 'Hari'
                },

                eventColor: 'var(--primary-dark)',
                height: 550, // Atur ketinggian kalender agar lebih proporsional

            });

            calendar.render();

            // Mengubah style tombol di FullCalendar setelah render agar sesuai tema
            const primaryColor = getComputedStyle(document.documentElement).getPropertyValue('--primary-color')
                .trim();
            const primaryDark = getComputedStyle(document.documentElement).getPropertyValue('--primary-dark')
                .trim();

            $('.fc-toolbar-chunk button').addClass('btn btn-sm').css({
                'backgroundColor': primaryColor,
                'borderColor': primaryColor,
                'color': 'white'
            }).hover(function() {
                $(this).css('backgroundColor', primaryDark);
            }, function() {
                $(this).css('backgroundColor', primaryColor);
            });

            // Mengubah warna teks judul kalender
            $('.fc-toolbar-chunk h2').css('color', primaryDark);
        });
    </script>
@endsection
