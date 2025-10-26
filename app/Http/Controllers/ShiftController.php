<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Shift::query();

        // Searching berdasarkan nama
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%'.$request->search.'%');
        }

        // Filter berdasarkan status (AKTIF / NONAKTIF)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }else{
            $query->where("status","AKTIF");
        }

        // Pagination, misal 10 data per halaman
        $shift = $query->orderBy('id', 'desc')->paginate(10);

        // Biar query string tetap terbawa saat paginate link
        $shift->appends($request->all());

        return view('shift', compact('shift'));
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
        'nama' => 'required|string|max:255|unique:kelas,nama',
        'kode' => 'required|string|unique:shift,kode',
        'jam_mulai' => 'required|string',
        'jam_selesai' => 'required|string',
        // Kita tidak memvalidasi izin_akses langsung karena akan diproses
        // Kita berasumsi inputnya aman
        ]);
        $validated["status"] = "AKTIF";
        Shift::create($validated);
        return redirect('/shift')->with('success', 'Shift berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Shift $shift)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shift $shift)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $shift = Shift::findOrFail($id);
        
        $validated = $request->validate([
        'nama' => 'required|string|max:255|unique:kelas,nama,'. $id,
        'kode' => 'required|string|unique:shift,kode,'. $id,
        'jam_mulai' => 'required|string',
        'jam_selesai' => 'required|string',
        // Kita tidak memvalidasi izin_akses langsung karena akan diproses
        // Kita berasumsi inputnya aman
        ]);

        
        // Simpan sebagai string JSON double-encoded
        $validated['status'] = "AKTIF";
        // ===============================================================================

        $shift->update($validated);

        return redirect('/shift')->with('success', 'Shift ' . $shift->nama . ' berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        $shift = Shift::findOrFail($id);
        $shiftName = $shift->nama;
        // $shift->status = "NONAKTIF";
        // $shift->update();
        $shift->delete();

        return redirect('/shift')->with('success', 'Shift ' . $shiftName . ' berhasil dihapus!');
    }
}
