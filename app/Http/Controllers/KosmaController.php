<?php

namespace App\Http\Controllers;

use App\Models\Biodata;
use App\Models\Kelas;
use App\Models\Kosma;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\Request;

class KosmaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Kosma::query();
        $query->with(['user.biodata']);

        // Searching berdasarkan nama
        if ($request->filled('search')) {
            $query->where('nidn', 'like', '%'.$request->search.'%')
            ->where('user.biodata.nama', 'like', '%'.$request->search.'%')
            ->orWhere('user.username', 'like', '%'.$request->search.'%')
            ->orWhere('user.email', 'like', '%'.$request->search.'%');
        }


        // Pagination, misal 10 data per halaman
        $kosma = $query->orderBy('id', 'desc')->paginate(10);

        // Biar query string tetap terbawa saat paginate link
        $kosma->appends($request->all());

        $users = User::with(['biodata'])->has('Mahasiswa')->doesntHave("kosma")->get();
        $kelas = Kelas::where("status","AKTIF")->doesntHave("kosma")->get();

        return view('kosma', compact('kosma','users','kelas'));
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
        'user_id' => 'required|string',
        'kelas_id' => 'required|string',
        ]);
        $kosma = Kosma::create([
            "user_id" => $validated["user_id"]
        ]);
        $kelas = Kelas::findOrFail($validated["kelas_id"]);
        $kelas->update([
            "kosma_id" => $kosma->id
        ]);

        return redirect('/kosma')->with('success', 'Kosma berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kosma $kosma)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kosma $kosma)
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
    public function destroy($id)
    {
        $dekan = Kosma::findOrFail($id);
        $dekan->delete();

        return redirect('/kosma')->with('success', 'Kosma berhasil dihapus!');
    }
}
