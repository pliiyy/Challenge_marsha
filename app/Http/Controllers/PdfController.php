<?php

namespace App\Http\Controllers;

use App\Models\SuratTugasMengajar;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    public function generateAndShow(Request $request)
    {
        // 1. Ambil data yang dibutuhkan
        $suratId = $request->query('id');

        // 2. Cari data surat tugas berdasarkan ID
        $suratTugas = SuratTugasMengajar::with(['dosen.user.Biodata', 'matakuliah.Prodi.fakultas','matakuliah.Prodi.kaprodi', 'kelas'])
                                ->findOrFail($suratId); 

        // 3. Siapkan data untuk view PDF
        $data = [
            'surat' => $suratTugas,
            // Tambahkan data lain yang dibutuhkan view PDF
        ];

        // 2. Load View dan data ke DomPDF
        $pdf = Pdf::loadView('pdf_surat_tugas', $data);
        
        // 3. Kembalikan respons stream
        // 'stream' akan mengirimkan file PDF ke browser (atau AJAX)
        return $pdf->stream('laporan_anda.pdf'); 
        
        // JANGAN gunakan ->download() karena akan langsung mengunduh
    }
}
