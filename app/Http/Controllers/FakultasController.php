<?php

namespace App\Http\Controllers;

use App\Models\Dekan;
use App\Models\Fakultas;
use Illuminate\Http\Request;

class FakultasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Fakultas::query();
        $query->with(['prodi','dekan.user.biodata']);

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
        $fakultas = $query->orderBy('id', 'desc')->paginate(10);

        // Biar query string tetap terbawa saat paginate link
        $fakultas->appends($request->all());
        $dekan = Dekan::all();

        return view('fakultas', compact('fakultas','dekan'));
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
        'nama' => 'required|string|max:255|unique:fakultas,nama',
        'kode' => 'nullable|string',
        'dekan_id' => 'nullable|string',
        // Kita tidak memvalidasi izin_akses langsung karena akan diproses
        // Kita berasumsi inputnya aman
        ]);
        $validated["status"] = "AKTIF";
        Fakultas::create($validated);
        return redirect('/fakultas')->with('success', 'Fakultas berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Fakultas $fakultas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Fakultas $fakultas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $fakultas = Fakultas::findOrFail($id);
        
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:fakultas,nama,' . $id, // Ignor ID saat validasi unique
            'kode' => 'nullable|string',
            'dekan_id' => 'nullable|string',
        ]);

        
        // Simpan sebagai string JSON double-encoded
       $validated['status'] = "AKTIF";
        // ===============================================================================

        $fakultas->update($validated);

        return redirect('/fakultas')->with('success', 'Fakultas ' . $fakultas->nama . ' berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $fakultas = Fakultas::findOrFail($id);
        $fakultasName = $fakultas->nama;
        // $fakultas->status = "NONAKTIF";
        // $fakultas->update();
        $fakultas->delete();

        return redirect('/fakultas')->with('success', 'Fakultas ' . $fakultasName . ' berhasil dihapus!');
    }
} 
