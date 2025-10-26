<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Surat Tugas Mengajar - No. {{ $surat->id ?? '[                         ]' }}</title>
    <style>
        /* CSS Umum dan Reset */
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 17cm;
            /* Ukuran mendekati A4 */
            margin: 1cm auto;
            padding: 0;
        }

        /* Header (Kop Surat) */
        .header {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 5px;
            margin-bottom: 20px;
        }

        .header h2,
        .header h3 {
            margin: 0;
            padding: 0;
        }

        .kop-img {
            float: left;
            margin-right: 15px;
            width: 70px;
            /* Sesuaikan ukuran logo */
        }

        /* Judul Surat */
        .judul-surat {
            text-align: center;
            margin: 25px 0 15px 0;
        }

        .judul-surat h4 {
            margin: 5px 0;
            text-decoration: underline;
        }

        /* Isi Surat */
        .isi-surat p {
            text-align: justify;
            line-height: 1.5;
            margin-bottom: 15px;
        }

        .data-list {
            width: 100%;
            margin: 10px 0 20px 0;
            padding-left: 20px;
        }

        .data-list td {
            padding-bottom: 5px;
        }

        /* Tanda Tangan */
        .ttd-section {
            width: 100%;
            margin-top: 50px;
            text-align: center;
        }

        .ttd-left {
            width: 50%;
            float: left;
            text-align: center;
        }

        .ttd-right {
            width: 50%;
            float: right;
            text-align: center;
        }

        .ttd-right p {
            margin: 0;
        }

        .clear {
            clear: both;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            {{-- <img src="{{ public_path('img/logo.png') }}" class="kop-img" alt="Logo"> --}}

            <h3>AL MASOEM UNIVERSITY</h3>
            <h2>KOP FAKULTAS/DEPARTEMEN {{ $surat->matakuliah->Prodi->fakultas->nama }}</h2>
            <p style="font-size: 10pt;">Alamat: Jl. Contoh No. 123, Kota Bandung | Telp: (021) 123456 | Email:
                info@institusi.ac.id</p>
        </div>

        <div class="judul-surat">
            <h4>SURAT TUGAS MENGAJAR</h4>
            <p style="font-size: 11pt;">Nomor: {{ $surat->id ?? '[]' }}</p>
        </div>

        <div class="isi-surat">
            <p>Yang bertanda tangan di bawah ini:</p>
            <table class="data-list">
                <tr>
                    <td style="width: 25%;">Nama</td>
                    <td style="width: 5%;">:</td>
                    <td>{{ $surat->matakuliah->prodi->Kaprodi?->user?->Biodata?->nama }}</td>
                </tr>
                <tr>
                    <td>Jabatan</td>
                    <td>:</td>
                    <td>Ketua Program Studi {{ $surat->matakuliah->Prodi->nama }}</td>
                </tr>
            </table>

            <p>Memberikan tugas mengajar kepada:</p>
            <table class="data-list">
                <tr>
                    <td style="width: 25%;">Nama Dosen</td>
                    <td style="width: 5%;">:</td>
                    <td>{{ $surat->dosen->user->biodata->nama }}</td>
                </tr>
                <tr>
                    <td>NIDN/NIP</td>
                    <td>:</td>
                    <td>{{ $surat->dosen->nidn ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Program Studi</td>
                    <td>:</td>
                    <td>{{ $surat->matakuliah->prodi->nama }}</td>
                </tr>
            </table>

            <p>Untuk melaksanakan kegiatan mengajar pada mata kuliah berikut di Semester
                {{ $surat->matakuliah->Semester->nama }} Tahun Akademik
                {{ $surat->matakuliah->Semester->tahun_akademik }}:</p>
        </div>

        <div class="detail-tugas">
            <table border="1" style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                <thead>
                    <tr style="background-color: #f2f2f2;">
                        <th style="padding: 8px;">No.</th>
                        <th style="padding: 8px;">Mata Kuliah</th>
                        <th style="padding: 8px;">SKS</th>
                        <th style="padding: 8px;">Kelas</th>
                        <th style="padding: 8px;">Prodi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding: 8px; text-align: center;">1</td>
                        <td style="padding: 8px;">{{ $surat->matakuliah->nama }}</td>
                        <td style="padding: 8px; text-align: center;">{{ $surat->matakuliah->sks ?? '-' }}</td>
                        <td style="padding: 8px; text-align: center;">{{ $surat->kelas->nama }}</td>
                        <td style="padding: 8px; text-align: center;">{{ $surat->matakuliah->prodi->nama }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="isi-surat">
            <p>Surat tugas ini berlaku sejak tanggal {{ '             ' }} sampai dengan {{ '                 ' }} dan
                harus dilaksanakan dengan penuh tanggung jawab.</p>
            <p>Demikian surat tugas ini dibuat untuk dipergunakan sebagaimana mestinya.</p>
        </div>

        <div class="ttd-section">
            <div class="ttd-right">
                <p>Jakarta, {{ \Carbon\Carbon::parse($surat->tanggal_surat ?? now())->isoFormat('D MMMM Y') }}</p>
                <p>Ketua Program Studi {{ $surat->matakuliah->Prodi->nama }}</p>

                <br><br><br><br>

                <p style="text-decoration: underline; margin-bottom: 0;">
                    {{ $surat->matakuliah->Prodi->Kaprodi?->user?->Biodata?->nama ?? '                                ' }}
                </p>
                <p>NIP. </p>
            </div>
            <div class="clear"></div>
        </div>

    </div>
</body>

</html>
