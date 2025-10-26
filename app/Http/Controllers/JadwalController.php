<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Ruangan;
use App\Models\Semester;
use App\Models\Shift;
use App\Models\SuratTugasMengajar;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Jadwal::query();
    $query->with('suratTugasMengajar.kelas','suratTugasMengajar.matakuliah.semester','ruangan',"shift");

    // Searching berdasarkan nama
    if ($request->filled('search')) {
        $query->whereHas('suratTugasMengajar', function ($q) use ($request) {
            $q->whereHas('dosen.user.biodata', function ($qDosen) use ($request) {
                $qDosen->where('nama', 'like', '%' . $request->search . '%'); 
            })
            ->orWhereHas('matakuliah', function ($qMatkul) use ($request) {
                $qMatkul->where('nama', 'like', '%' . $request->search . '%');
            });
        });
    }

    // Filter berdasarkan status (AKTIF / NONAKTIF)
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    } else {
        $query->where("status","AKTIF");
    }

    // ✅ Perbaikan disini
    if ($request->filled('semester_id')) {
        $query->whereHas('suratTugasMengajar.matakuliah', function ($q) use ($request) {
            $q->where('semester_id', $request->semester_id);
        });
    }
    $jadwalAll = $query->orderBy('hari', 'asc')->get();
    $jadwalPerHari = $jadwalAll->groupBy('hari');

    // Pagination
    $jadwal = $query->orderBy('hari', 'desc')->paginate(10);
    $jadwal->appends($request->all());

    $surat = SuratTugasMengajar::where("status","APPROVED")->get();
    $ruangan = Ruangan::where("status","AKTIF")->get();
    $shift = Shift::where("status","AKTIF")->get();
    $semester = Semester::where("status","AKTIF")->get();

    return view('jadwal', compact('jadwal','surat','ruangan','shift','semester'));
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
        $validated = $request->validate([
        'surat_tugas_mengajar_id' => 'required',
        'ruangan_id' => 'required',
        'shift_id' => 'required',
        'hari' => 'required',
        ]);
        $find = Jadwal::where("hari",$validated["hari"])->where("shift_id",$validated["shift_id"])->where("ruangan_id",$validated["ruangan_id"])->where("surat_tugas_mengajar_id",$validated["surat_tugas_mengajar_id"])->where("status","AKTIF")->get();
        
        if($find->isNotEmpty()){
            return redirect('/jadwal')->with('error', 'Jadwal bentrok! mohon pilih waktu yg lain atau ajukan barter!');
        }
        $validated["status"] = "AKTIF";
        Jadwal::create($validated);
        return redirect('/jadwal')->with('success', 'Jadwal berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Jadwal $jadwal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Jadwal $jadwal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        $jadwal = Jadwal::findOrFail($id);
        // $jadwal->status = "NONAKTIF";
        // $jadwal->update();
        $jadwal->delete();

        return redirect('/jadwal')->with('success', 'Jadwal berhasil dihapus!');
    }

   public function jadwal_global(Request $request)
   {
    // Ambil semua jadwal dengan relasi lengkap
    $jadwals = Jadwal::with([
        'ruangan',
        'shift',
        'suratTugasMengajar.kelas',
        'suratTugasMengajar.dosen',
        'suratTugasMengajar.mataKuliah.semester'
    ])->get();
    // Buat daftar tab dinamis berdasarkan kombinasi tipe kelas + semester
    $availableTabs = $jadwals->map(function ($j) {
        $kelas = $j->suratTugasMengajar->kelas;
        $semester = $j->suratTugasMengajar->mataKuliah->semester;
        if (!$kelas || !$semester) return null;
        $sems = $semester;
        return strtoupper($kelas->tipe) . $semester->nama;
    })->filter()->unique()->values();

    // Ambil tab aktif dari query
    $activeTab = $request->get('tab', $availableTabs->first());

    // Filter data sesuai tab aktif
    $filteredJadwals = $jadwals->filter(function ($j) use ($activeTab) {
        $kelas = $j->suratTugasMengajar->kelas;
        $semester = $j->suratTugasMengajar->mataKuliah->semester;
        if (!$kelas || !$semester) return false;
        return strtoupper($kelas->tipe) . $semester->nama === $activeTab;
    });

    // Kelompokkan data berdasarkan hari dan kelas
    $grouped = $filteredJadwals->groupBy(function ($item) {
        return strtoupper($item->hari ?? 'TANPA HARI');
    })->map(function ($items) {
        return $items->groupBy(function ($i) {
            return $i->suratTugasMengajar->kelas->nama ?? 'Tanpa Kelas';
        });
    });
    $sems = $filteredJadwals->first()?->suratTugasMengajar?->mataKuliah?->semester;

    return view('jadwal_global', compact('availableTabs', 'activeTab', 'grouped','sems'));
   }

   public function export(Request $request){
    $jadwals = Jadwal::with([
        'ruangan',
        'shift',
        'suratTugasMengajar.kelas',
        'suratTugasMengajar.dosen',
        'suratTugasMengajar.mataKuliah.semester'
    ])->get();

    // Buat daftar tab dinamis berdasarkan kombinasi tipe kelas + semester
    $availableTabs = $jadwals->map(function ($j) {
        $kelas = $j->suratTugasMengajar->kelas;
        $semester = $j->suratTugasMengajar->mataKuliah->semester;
        if (!$kelas || !$semester) return null;
        return strtoupper($kelas->tipe) . $semester->nama;
    })->filter()->unique()->values();

    // Ambil tab aktif dari query
    $activeTab = $request->get('tab', $availableTabs->first());

    // Filter data sesuai tab aktif
    $filteredJadwals = $jadwals->filter(function ($j) use ($activeTab) {
        $kelas = $j->suratTugasMengajar->kelas;
        $semester = $j->suratTugasMengajar->mataKuliah->semester;
        if (!$kelas || !$semester) return false;
        return strtoupper($kelas->tipe) . $semester->nama === $activeTab;
    });

    // Kelompokkan data berdasarkan hari dan kelas
    $grouped = $filteredJadwals->groupBy(function ($item) {
        return strtoupper($item->hari ?? 'TANPA HARI');
    })->map(function ($items) {
        return $items->groupBy(function ($i) {
            return $i->suratTugasMengajar->kelas->nama ?? 'Tanpa Kelas';
        });
    });
    
    $sems = $filteredJadwals->first()?->suratTugasMengajar?->mataKuliah?->semester;
    $pdf = Pdf::loadView('export_jadwal', compact('availableTabs', 'activeTab', 'grouped','sems'))->setOption('isHtml5ParserEnabled', true);
    
    return $pdf->download('jadwal.pdf');
   }
    

}
