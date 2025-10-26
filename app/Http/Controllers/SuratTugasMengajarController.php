<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Matakuliah;
use App\Models\SuratTugasMengajar;
use App\Models\User;
use App\Notifications\NotifEmail;
use Illuminate\Http\Request;

class SuratTugasMengajarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SuratTugasMengajar::query();
        $query->with(['dosen.user.biodata','matakuliah.semester','matakuliah.prodi','kelas']);

        // Searching berdasarkan nama
        if ($request->filled('search')) {
            $query->where('dosen.nama', 'like', '%'.$request->search.'%')
            ->orWhere('kelas.nama','like', '%'.$request->search.'%')
            ->orWhere('matakuliah.nama','like', '%'.$request->search.'%');
        }

        // Filter berdasarkan status (AKTIF / NONAKTIF)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Pagination, misal 10 data per halaman
        $surat = $query->orderBy('status', 'desc')->paginate(10);

        // Biar query string tetap terbawa saat paginate link
        $surat->appends($request->all());
        $dosen= Dosen::all();
        $matakuliah= Matakuliah::all();
        $kelas= Kelas::all();

        return view('surat_tugas', compact('surat','dosen','matakuliah','kelas'));
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
        'dosen_id' => 'required',
        'kelas_id' => 'required',
        'matakuliah_id' => 'required',
        ]);
        $validated["status"] = "PENDING";
        SuratTugasMengajar::create($validated);
        return redirect('/surat_tugas')->with('success', 'Surat Tugas berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(SuratTugasMengajar $suratTugasMengajar)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SuratTugasMengajar $suratTugasMengajar)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $surat = SuratTugasMengajar::findOrFail($id);
        
        $validated = $request->validate([
            'dosen_id' => 'required',
            'kelas_id' => 'required',
            'matakuliah_id' => 'required',
            'status' => 'required',
        ]);

        if($validated['status'] !== "PENDING"){
            $user = User::where("id",$surat->dosen->user_id)->first();
            $statuss = $validated['status'] === "APPROVE"?"Telah Disetujui":"Telah Ditolak";
            $rejectMsg = "Mohon maaf surat tugas mengajar kamu dalam kelas ".$surat->kelas->nama ." telah ditolak. Mohon ajukan kembali nanti.";
            $approvMsg = "Selamat!!. surat tugas mengajar kamu dalam kelas ".$surat->kelas->nama ." telah disetujui. Silahkan buat jadwal dalam matakuliah ".$surat->Matakuliah->nama;
             $user->notify(new NotifEmail('Surat Tugas Mengajar '.$statuss, $validated['status'] === "APPROVED"?$approvMsg: $rejectMsg));
        }
    
        $surat->update($validated);

        return redirect('/surat_tugas')->with('success', 'Surat tugas  berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $surat = SuratTugasMengajar::findOrFail($id);
        $surat->delete();

        return redirect('/surat_tugas')->with('success', 'Surat tugas berhasil dihapus!');
    }
}
