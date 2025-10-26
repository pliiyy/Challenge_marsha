<?php

namespace App\Http\Controllers;

use App\Models\Biodata;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Mahasiswa::query();
        $query->with(['user.biodata']);

        // Searching berdasarkan nama
        if ($request->filled('search')) {
            $query->where('nim', 'like', '%'.$request->search.'%')
            ->where('user.biodata.nama', 'like', '%'.$request->search.'%')
            ->orWhere('user.username', 'like', '%'.$request->search.'%')
            ->orWhere('user.email', 'like', '%'.$request->search.'%');
        }


        // Pagination, misal 10 data per halaman
        $mahasiswa = $query->orderBy('id', 'desc')->paginate(10);

        // Biar query string tetap terbawa saat paginate link
        $mahasiswa->appends($request->all());

        $kelas = Kelas::where('status','AKTIF')->get();
        $prodi = Prodi::where('status','AKTIF')->get();
        return view('mahasiswa', compact('mahasiswa','kelas','prodi'));
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
        'nim' => 'required|unique:mahasiswa,nim',
        'nama' => 'required',
        'username' => 'required|unique:user,username',
        'email' => 'required|unique:user,email',
        'no_telepon' => 'required|unique:user,no_telepon',
        'password' => 'required',
        'jenis_kelamin' => 'required',
        'agama' => 'required',
        'tempat_lahir' => 'required',
        'tanggal_lahir' => 'required',
        'prov_id' => 'required',
        'kab_id' => 'required',
        'kec_id' => 'required',
        'kelurahan' => 'required',
        'alamat' => 'required',
        'kelas_id' => 'required',
        'prodi_id' => 'required',
        ]);
        $user = User::create([
            "username" => $validated["username"],
            "email" => $validated["email"],
            "no_telepon" => $validated["no_telepon"],
            "password" => bcrypt($validated["password"]),
            "status" => "AKTIF"
        ]);
        Biodata::create([
            'nama' => $validated['username'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'tempat_lahir' => $validated['tempat_lahir'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'agama' => $validated['agama'],
            'alamat' => $validated['alamat'],
            'kelurahan' => $validated['kelurahan'],
            'kec_id' => $validated['kec_id'],
            'kab_id' => $validated['kab_id'],
            'prov_id' => $validated['prov_id'],
            'user_id' => $user->id,
        ]);
        Mahasiswa::create([
            "nim" => $validated["nim"],
            "user_id" => $user->id,
            "kelas_id" => $validated["kelas_id"],
            "prodi_id" => $validated["prodi_id"],
        ]);
        return redirect('/mahasiswa')->with('success', 'Mahasiswa berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Mahasiswa $mahasiswa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mahasiswa $mahasiswa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $mahasiswa = Mahasiswa::with('user')->findOrFail($id);
        $validated = $request->validate([
        'nim' => 'required|unique:mahasiswa,nim,'.$id,
        'nama' => 'required',
        'username' => 'required|unique:user,username,'.$mahasiswa->user->id,
        'email' => 'required|unique:user,email,'.$mahasiswa->user->id,
        'no_telepon' => 'required|unique:user,no_telepon,'.$mahasiswa->user->id,
        'jenis_kelamin' => 'required',
        'agama' => 'required',
        'tempat_lahir' => 'required',
        'tanggal_lahir' => 'required',
        'prov_id' => 'required',
        'kab_id' => 'required',
        'kec_id' => 'required',
        'kelurahan' => 'required',
        'alamat' => 'required',
        'kelas_id' => 'required',
        'prodi_id' => 'required',
        ]);
        $user = User::with('Biodata')->findOrFail($mahasiswa->user_id);
        $user->update([
            "username" => $validated["username"],
            "email" => $validated["email"],
            "no_telepon" => $validated["no_telepon"],
            "status" => "AKTIF"
        ]);
        $biodata = Biodata::findOrFail($user->Biodata->id);
        $biodata->update([
            'nama' => $validated['username'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'tempat_lahir' => $validated['tempat_lahir'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'agama' => $validated['agama'],
            'alamat' => $validated['alamat'],
            'kelurahan' => $validated['kelurahan'],
            'kec_id' => $validated['kec_id'],
            'kab_id' => $validated['kab_id'],
            'prov_id' => $validated['prov_id'],
            'user_id' => $user->id,
        ]);
        $mahasiswa->update([
            "nim" => $validated["nim"],
            "user_id" => $user->id,
            "kelas_id" => $validated["kelas_id"],
            "prodi_id" => $validated["prodi_id"],
        ]);

        return redirect('/mahasiswa')->with('success', 'Mahasiswa ' . $mahasiswa->user->nama . ' berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $mahasiswa = Mahasiswa::with('user.biodata')->findOrFail($id);
        $mahasiswaName = $mahasiswa->user->biodata->nama;
        $mahasiswa->delete();

        return redirect('/mahasiswa')->with('success', 'Mahasiswa ' . $mahasiswaName . ' berhasil dihapus!');
    }
}
