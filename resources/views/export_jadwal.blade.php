<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Jadwal Mata Kuliah</title>
    <style>
        /* Reset & Base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9px;
            line-height: 1.4;
            color: #333;
            padding: 10px
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #4f46e5;
        }

        .header h3 {
            font-size: 14px;
            font-weight: bold;
            color: #4f46e5;
            margin-bottom: 5px;
        }

        .header h5 {
            font-size: 11px;
            color: #666;
            margin-bottom: 5px;
        }

        .semester-info {
            background-color: #4f46e5;
            color: white;
            padding: 6px;
            font-weight: bold;
            font-size: 10px;
            margin-bottom: 15px;
        }

        /* Hari Label */
        .hari-section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .hari-label {
            background-color: #ffc107;
            color: #000;
            padding: 6px 10px;
            font-weight: bold;
            font-size: 10px;
            margin-bottom: 8px;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 6px 8px;
            vertical-align: top;
            text-align: left;
        }

        table thead th {
            background-color: #eef2ff;
            color: #4f46e5;
            font-weight: bold;
            font-size: 9px;
            text-align: center;
        }

        table tbody td {
            font-size: 8px;
        }

        .waktu-col {
            width: 12%;
            font-weight: bold;
            background-color: #f9f9f9;
            white-space: nowrap;
        }

        /* Content dalam cell */
        .matkul-nama {
            font-weight: bold;
            font-size: 9px;
            margin-bottom: 3px;
            color: #000;
        }

        .matkul-detail {
            font-size: 7px;
            color: #666;
            line-height: 1.3;
        }

        .empty-cell {
            text-align: center;
            color: #999;
        }

        /* Page Break */
        .page-break {
            page-break-after: always;
        }

        /* Footer */
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 7px;
            color: #999;
            padding: 5px 0;
            border-top: 1px solid #ddd;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <h3>JADWAL MATA KULIAH</h3>
        <h5>SEMESTER {{ $sems?->tipe }}
            {{ $sems?->tahun_akademik }}</h5>
    </div>

    <div class="semester-info">
        Semester: {{ $activeTab }}
    </div>

    @if ($grouped->isEmpty())
        <div style="text-align: center; padding: 20px; background-color: #fff3cd; border: 1px solid #ffc107;">
            Tidak ada data untuk tab {{ $activeTab }}.
        </div>
    @else
        @foreach ($grouped as $hari => $kelasGroup)
            <div class="hari-section">
                <div class="hari-label">{{ strtoupper($hari) }}</div>

                <table>
                    <thead>
                        <tr>
                            <th class="waktu-col">WAKTU</th>
                            @foreach ($kelasGroup as $kelasNama => $jadwalPerKelas)
                                <th>{{ $kelasNama }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // Ambil semua waktu unik untuk hari ini
                            $allWaktu = $kelasGroup->flatten()->groupBy(function ($j) {
                                return $j->shift->jam_mulai . ' - ' . $j->shift->jam_selesai;
                            });
                        @endphp

                        @foreach ($allWaktu as $waktu => $jadwalWaktu)
                            <tr>
                                <td class="waktu-col">{{ $waktu }}</td>

                                @foreach ($kelasGroup as $kelasNama => $jadwalPerKelas)
                                    @php
                                        $data = $jadwalPerKelas->firstWhere(
                                            'shift.jam_mulai',
                                            explode(' - ', $waktu)[0],
                                        );
                                    @endphp

                                    <td>
                                        @if ($data)
                                            <div class="matkul-nama">
                                                {{ $data->suratTugasMengajar->mataKuliah->nama ?? '-' }}
                                            </div>
                                            <div class="matkul-detail">
                                                {{ $data->suratTugasMengajar->dosen->user->biodata->nama ?? '-' }}<br />
                                                {{ $data->ruangan->kode ?? '-' }} ({{ $data->ruangan->nama ?? '-' }})
                                            </div>
                                        @else
                                            <div class="empty-cell">-</div>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    @endif

    <!-- Footer -->
    <div class="footer">
        Dicetak pada: {{ date('d/m/Y H:i') }} | Halaman
        <script type="text/php">
            if (isset($pdf)) {
                $font = $fontMetrics->getFont("DejaVu Sans", "normal");
                $size = 7;
                $pageText = $PAGE_NUM . " dari " . $PAGE_COUNT;
                $y = 820;
                $x = 520;
                $pdf->text($x, $y, $pageText, $font, $size);
            }
        </script>
    </div>
</body>

</html>
