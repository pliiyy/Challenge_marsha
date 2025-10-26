<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use Illuminate\Http\Request;

class RuanganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Ruangan::query();

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
        $ruangan = $query->orderBy('id', 'desc')->paginate(10);

        // Biar query string tetap terbawa saat paginate link
        $ruangan->appends($request->all());

        return view('ruangan', compact('ruangan'));
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
        'nama' => 'required|string|max:255|unique:ruangan,nama',
        'kode' => 'required|string|unique:shift,kode',
        'kapasitas' => 'required',
        // Kita tidak memvalidasi izin_akses langsung karena akan diproses
        // Kita berasumsi inputnya aman
        ]);
        $validated["status"] = "AKTIF";
        Ruangan::create($validated);
        return redirect('/ruangan')->with('success', 'Ruangan berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ruangan $ruangan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ruangan $ruangan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $ruangan = Ruangan::findOrFail($id);
        
        $validated = $request->validate([
        'nama' => 'required|string|max:255|unique:ruangan,nama,'.$id,
        'kode' => 'required|string|unique:shift,kode,'.$id,
        'kapasitas' => 'required',
        // Kita tidak memvalidasi izin_akses langsung karena akan diproses
        // Kita berasumsi inputnya aman
        ]);

        
        // Simpan sebagai string JSON double-encoded
        $validated['status'] = "AKTIF";
        // ===============================================================================

        $ruangan->update($validated);

        return redirect('/ruangan')->with('success', 'Ruangan ' . $ruangan->nama . ' berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        $ruangan = Ruangan::findOrFail($id);
        $ruanganName = $ruangan->nama;
        // $ruangan->status = "NONAKTIF";
        // $ruangan->update();
        $ruangan->delete();

        return redirect('/ruangan')->with('success', 'Ruangan ' . $ruanganName . ' berhasil dihapus!');
    }
}
