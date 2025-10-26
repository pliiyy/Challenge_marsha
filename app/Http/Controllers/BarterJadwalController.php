<?php

namespace App\Http\Controllers;

use App\Models\BarterJadwal;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarterJadwalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
       
        $query = BarterJadwal::query();
        
        // $query->with(['jadwalAwal.suratTugasMengajar','jadwalTukar.suratTugasMengajar']);
        // Searching berdasarkan nama
        if ($request->filled('search')) {
            $query->where('jadwal_awal.matakuliah.nama', 'like', '%'.$request->search.'%');
        }

        // Filter berdasarkan status (AKTIF / NONAKTIF)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Pagination, misal 10 data per halaman
        $barter = $query->orderBy('id', 'desc')->paginate(10);

        // Biar query string tetap terbawa saat paginate link
        $barter->appends($request->all());
        $jadwal = Jadwal::where('status',"AKTIF")->get();

        return view('barter_jadwal', compact('barter','jadwal'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
    $request->validate([
        'jadwal_awal_id' => 'required|exists:jadwal,id',
        'jadwal_tukar_id' => 'required|exists:jadwal,id',
        'alasan' => 'required|string',
    ]);

    // Ambil data jadwal
    $jadwalAwal = Jadwal::find($request->jadwal_awal_id);
    $jadwalTukar = Jadwal::find($request->jadwal_tukar_id);

    // Pastikan kedua jadwal milik dosen yang berbeda
    // if ($jadwalAwal->dosen_id == $jadwalTukar->dosen_id) {
    //     return back()->with('error', 'Tidak dapat barter dengan jadwal sendiri.');
    // }

    // 2. Buat Permintaan
    BarterJadwal::create([
        'jadwal_awal_id' => $jadwalAwal->id,
        'jadwal_tukar_id' => $jadwalTukar->id,
        'alasan' => $request->alasan,
        'status' => 'PENDING',
    ]);

    // 3. Notifikasi
    // Kirim notifikasi kepada Dosen Tujuan (Dosen B)
    // (Misalnya: menggunakan Laravel Notification via email atau database)

    return redirect("/barter_jadwal")->with('success', 'Permintaan barter berhasil diajukan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(BarterJadwal $barterJadwal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BarterJadwal $barterJadwal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // if (auth()->user()->id != $barterJadwal->dosen_tujuan_id) {
        //     return back()->with('error', 'Anda tidak memiliki hak untuk menyetujui permintaan ini.');
        // }

    // 2. LOGIKA UTAMA: Melakukan Pertukaran
    $barterJadwal = BarterJadwal::findOrFail($id);
    $jadwalA = $barterJadwal->jadwalAwal; // Jadwal Awal milik Dosen A
    $jadwalB = $barterJadwal->jadwalTukar; // Jadwal Tukar milik Dosen B

    // **PENTING: LAKUKAN BARTER ID DOSEN**
    if($request["status"] === "APPROVED"){
try {
        DB::beginTransaction();
        $temp = $jadwalA->surat_tugas_mengajar_id;
        // Simpan sementara ID Dosen A
        // Jadwal A sekarang dimiliki oleh Dosen B
        $jadwalA->surat_tugas_mengajar_id = $jadwalB->surat_tugas_mengajar_id;
        $jadwalA->save();

        // Jadwal B sekarang dimiliki oleh Dosen A
        $jadwalB->surat_tugas_mengajar_id = $temp;
        $jadwalB->save();

        // 3. Perbarui Status Permintaan
        $barterJadwal->status = 'APPROVED';
        $barterJadwal->save();

        DB::commit();

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Gagal melakukan pertukaran jadwal: ' . $e->getMessage());
    }
    }else{
        $saveBarter = BarterJadwal::findOrFail($barterJadwal->id);
        $saveBarter->update([
            "status" => $request["status"]
        ]);
    }

    // 4. Notifikasi
    // Kirim notifikasi kepada Dosen Pengaju (Dosen A) bahwa permintaan disetujui.

    return back()->with('success', 'Barter jadwal  '.$barterJadwal->status.' dan status jadwal telah diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $barter = BarterJadwal::findOrFail($id);
        $barter->delete();

        return redirect('/barter_jadwal')->with('success', 'Barter Jadwal berhasil dihapus!');
    }
}
