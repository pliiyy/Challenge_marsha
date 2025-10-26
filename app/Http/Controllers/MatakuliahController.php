<?php

namespace App\Http\Controllers;

use App\Models\Matakuliah;
use App\Models\Prodi;
use App\Models\Semester;
use Illuminate\Http\Request;

class MatakuliahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Matakuliah::query();
        $query->with('prodi');
        $query->with('semester');

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
        $matakuliah = $query->orderBy('id', 'desc')->paginate(10);

        // Biar query string tetap terbawa saat paginate link
        $matakuliah->appends($request->all());

        $prodi = Prodi::where('status',"AKTIF")->get();
        $semester = Semester::where('status',"AKTIF")->get();

        return view('matakuliah', compact('matakuliah','prodi','semester'));
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
        'nama' => 'required|string|max:255|unique:matakuliah,nama',
        'kode' => 'nullable|string',
        'sks' => 'nullable|string',
        'prodi_id' => 'required|string',
        'semester_id' => 'required|string',
        ]);
        $validated["status"] = "AKTIF";
        Matakuliah::create($validated);
        return redirect('/matakuliah')->with('success', 'Mata Kuliah berhasil ditambahkan!');
    }
    /**
     * Display the specified resource.
     */
    public function show(Matakuliah $matakuliah)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Matakuliah $matakuliah)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $matakuliah = Matakuliah::findOrFail($id);
        
        $validated = $request->validate([
        'nama' => 'required|string|max:255|unique:matakuliah,nama',
        'kode' => 'nullable|string',
        'sks' => 'nullable|string',
        'prodi_id' => 'nullable|string',
        'semester_id' => 'nullable|string',
        ]);

        $validated["prodi_id"] = $validated["prodi_id"]|| $matakuliah->prodi_id;
        $validated["semester_id"] = $validated["semester_id"]|| $matakuliah->semester_id;
        $validated["status"] = "AKTIF";
        
        // Simpan sebagai string JSON double-encoded
       $validated['status'] = "AKTIF";
        // ===============================================================================

        $matakuliah->update($validated);

        return redirect('/matakuliah')->with('success', 'Matakuliah ' . $matakuliah->nama . ' berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        $matakuliah = Matakuliah::findOrFail($id);
        $matakuliahName = $matakuliah->nama;
        // $matakuliah->status = "NONAKTIF";
        // $matakuliah->update();
        $matakuliah->delete();

        return redirect('/matakuliah')->with('success', 'Matakuliah ' . $matakuliahName . ' berhasil dihapus!');
    }
}
