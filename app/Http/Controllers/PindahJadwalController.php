<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\PindahJadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PindahJadwalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
         $query = PindahJadwal::query();

        // Searching berdasarkan nama
        if ($request->filled('search')) {
            $query->where('jadwal.surat_tugas_mengajar.matakuliah.nama', 'like', '%'.$request->search.'%');
        }

        // Filter berdasarkan status (AKTIF / NONAKTIF)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Pagination, misal 10 data per halaman
        $pindah_jadwal = $query->orderBy('id', 'desc')->paginate(10);

        // Biar query string tetap terbawa saat paginate link
        $pindah_jadwal->appends($request->all());

        return view('pindah_jadwal', compact('pindah_jadwal'));
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
            'alasan' => 'required|string|max:255',
            'id' => 'required|string',
            'shift_id' => 'required|string',
            'ruangan_id' => 'required|string',
            'hari' => 'required|string',
        ]);
        $find = Jadwal::findOrFail($validated['id']);
        if($find->shift_id === $validated["shift_id"] && $find->ruangan_id === $validated["ruangan_id"] && $find->hari == $validated["hari"]){
            return back()->with('error', 'Gagal melakukan pindah jadwal karena data yg diinput sama!');
        }
        $findSome = Jadwal::where('hari',$validated["hari"])->where("ruangan_id",$validated["ruangan_id"])->where('shift_id',$validated["shift_id"]);
        if($findSome->count() !== 0){
            return back()->with('error', 'Gagal melakukan pindah jadwal bentrok dengan jadwal lain. Mohon ajukan barter saja!');
        }
        $validated["status"] = "AKTIF";
        PindahJadwal::create([
            "alasan" => $validated["alasan"],
            "hari" => $validated["hari"],
            "jadwal_id" => $validated["id"],
            "shift_id" => $validated["shift_id"],
            "ruangan_id" => $validated["ruangan_id"],
            "status" => "PENDING"
        ]);
        return redirect('/pindah_jadwal')->with('success', 'Permohonan pindah jadwal berhasil. tunggu kosma menyetujuinya!');
    }

    /**
     * Display the specified resource.
     */
    public function show(PindahJadwal $pindahJadwal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PindahJadwal $pindahJadwal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
         $pindahJadwal = PindahJadwal::findOrFail($id);
        
        $validated = $request->validate([
            'jadwal_id' => 'required|string',
            'shift_id' => 'required|string',
            'ruangan_id' => 'required|string',
            'hari' => 'required|string',
            'status' => 'required|string',
        ]);
        if($validated['status']==="APPROVED"){
            try{
                DB::beginTransaction();
                $jadwal = $pindahJadwal->jadwal;
                $jadwal->shift_id = $validated["shift_id"];
                $jadwal->ruangan_id = $validated["ruangan_id"];
                $jadwal->hari = $validated["hari"];
                $jadwal->save();

                $pindahJadwal->update([
                    'status'=> "APPROVED",
                    'kosma_id' => Auth::user()->Kosma->id
                ]);
                DB::commit();
            }catch(\Exception $e){
                DB::rollBack();
            }
        }else{
            $findSome = Jadwal::where('hari',$validated["hari"])->where("ruangan_id",$validated["ruangan_id"])->where('shift_id',$validated["shift_id"]);
            if($findSome->count() !== 0){
                return back()->with('error', 'Gagal melakukan pindah jadwal bentrok dengan jadwal lain. Mohon ajukan barter saja!');
            }
            if($validated["status"] !== "PENDING"){
                $validated["kosma_id"] = Auth::user()->Kosma->id;
                $pindahJadwal->update($validated);
            }
            $pindahJadwal->update($validated);
        }
    

        return redirect('/pindah_jadwal')->with('success', 'Jadwal  berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $pindah = PindahJadwal::findOrFail($id);
        $pindah->delete();

        return redirect('/pindah_jadwal')->with('success', 'Permohonan Pindah Jadwal berhasil dihapus!');
    }
}
