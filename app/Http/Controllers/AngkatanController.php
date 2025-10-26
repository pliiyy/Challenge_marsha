<?php

namespace App\Http\Controllers;

use App\Models\Angkatan;
use Illuminate\Http\Request;

class AngkatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Angkatan::query();
        $query->with('kelas');

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
        $angkatan = $query->orderBy('id', 'desc')->paginate(10);

        // Biar query string tetap terbawa saat paginate link
        $angkatan->appends($request->all());

        return view('angkatan', compact('angkatan'));
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
        'tahun' => 'required|string|max:255|unique:angkatan,tahun',
        'keterangan' => 'nullable',
        ]);
        $validated["status"] = "AKTIF";
        Angkatan::create($validated);
        return redirect('/angkatan')->with('success', 'Angkatan berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Angkatan $angkatan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Angkatan $angkatan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $angkatan = Angkatan::findOrFail($id);
        
        $validated = $request->validate([
        'tahun' => 'required|string|max:255|unique:angkatan,tahun,'.$id,
        'keterangan' => 'nullable',
        ]);

        
        // Simpan sebagai string JSON double-encoded
        $validated['status'] = "AKTIF";
        // ===============================================================================

        $angkatan->update($validated);

        return redirect('/angkatan')->with('success', 'Angkatan ' . $angkatan->nama . ' berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        $angkatan = Angkatan::findOrFail($id);
        $angkatanName = $angkatan->tahun;
        // $angkatan->status = "NONAKTIF";
        $angkatan->delete();

        return redirect('/angkatan')->with('success', 'Angkatan ' . $angkatanName . ' berhasil dihapus!');
    }
}
