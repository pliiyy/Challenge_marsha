<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use Illuminate\Http\Request;

class SemesterController extends Controller
{
    /** 
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Semester::query();

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
        $semester = $query->orderBy('id', 'desc')->paginate(10);

        // Biar query string tetap terbawa saat paginate link
        $semester->appends($request->all());
        return view('semester', compact('semester'));
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
        'nama' => 'required|string|max:255|unique:semester,nama',
        'kode' => 'required|string',
        'keterangan' => 'nullable|string',
        'tahun_akademik' => 'required|string',
        'tipe' => 'required|string',
        ]);
        $validated["status"] = "AKTIF";
        Semester::create($validated);
        return redirect('/semester')->with('success', 'Semester berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Semester $semester)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Semester $semester)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $semester = Semester::findOrFail($id);
        
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:semester,nama,' . $id, // Ignor ID saat validasi unique
            'kode' => 'required|string',
            'keterangan' => 'required|string',
            'tahun_akademik' => 'required|string',
            'tipe' => 'required|string',
        ]);

        
        // Simpan sebagai string JSON double-encoded
        $validated['status'] = "AKTIF";
        // ===============================================================================

        $semester->update($validated);

        return redirect('/semester')->with('success', 'Semester ' . $semester->nama . ' berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $semester = Semester::findOrFail($id);
        $semesterName = $semester->nama;
        // $semester->status = "NONAKTIF";
        // $semester->update();
        $semester->delete();

        return redirect('/semester')->with('success', 'Semester ' . $semesterName . ' berhasil dihapus!');
    }
}
