<?php

namespace App\Http\Controllers;

use App\Models\Biodata;
use App\Models\Sekprodi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class SekprodiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Sekprodi::query();
        $query->with(['user.biodata']);

        // Searching berdasarkan nama
        if ($request->filled('search')) {
            $query->where('nidn', 'like', '%'.$request->search.'%')
            ->where('user.biodata.nama', 'like', '%'.$request->search.'%')
            ->orWhere('user.username', 'like', '%'.$request->search.'%')
            ->orWhere('user.email', 'like', '%'.$request->search.'%');
        }


        // Pagination, misal 10 data per halaman
        $sekprodi = $query->orderBy('id', 'desc')->paginate(10);

        // Biar query string tetap terbawa saat paginate link
        $sekprodi->appends($request->all());

        $users = User::with(['biodata'])->doesntHave("sekprodi")->get();

        return view('sekprodi', compact('sekprodi','users'));
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
        $validatedData = $request->validate([
            'user_id' => [
                'nullable', 
                Rule::exists('user', 'id'),
            ],
            'user_name' => [
                Rule::requiredIf(is_null($request->user_id)), // Wajib jika user_id kosong
                'string', 
                'max:255',
            ],
            'username' => [
                Rule::requiredIf(is_null($request->user_id)), // Wajib jika user_id kosong
                'string', 
                'max:255',
                'unique:user,username'
            ],
            'user_email' => [
                Rule::requiredIf(is_null($request->user_id)), // Wajib jika user_id kosong
                'email', 
                'unique:user,email', // Pastikan email unik jika membuat baru
            ],
            'user_password' => [
                Rule::requiredIf(is_null($request->user_id)), // Wajib jika membuat baru
                'string', 
                'min:6',
            ],
            'no_telepon' => [
                Rule::requiredIf(is_null($request->user_id)), // Wajib jika membuat baru
                'string', 
                'max:15',
                'unique:user,no_telepon'
            ],
            'jenis_kelamin' => [
                Rule::requiredIf(is_null($request->user_id)), // Wajib jika membuat baru
                'string', 
                'max:1',
            ],
            'agama' => [
                Rule::requiredIf(is_null($request->user_id)), // Wajib jika membuat baru
                'string', 
            ],
            'tempat_lahir' => [
                Rule::requiredIf(is_null($request->user_id)), // Wajib jika membuat baru
                'string', 
            ],
            'tanggal_lahir' => [
                Rule::requiredIf(is_null($request->user_id)), // Wajib jika membuat baru
                'string', 
            ],
            'alamat' => [
                Rule::requiredIf(is_null($request->user_id)), // Wajib jika membuat baru
                'string', 
            ],
            'kelurahan' => [
                Rule::requiredIf(is_null($request->user_id)), // Wajib jika membuat baru
                'string', 
            ],
            'kec_id' => [
                Rule::requiredIf(is_null($request->user_id)), // Wajib jika membuat baru
                'string', 
            ],
            'kab_id' => [
                Rule::requiredIf(is_null($request->user_id)), // Wajib jika membuat baru
                'string', 
            ],
            'prov_id' => [
                Rule::requiredIf(is_null($request->user_id)), // Wajib jika membuat baru
                'string', 
            ],
            
        ]);
        // Kita gunakan Transaction untuk memastikan semua operasi berhasil atau gagal bersamaan
        DB::beginTransaction();

        try {
            $dekanUser = null;
            
            // ======================================================
            // A. FASE 1: GET OR CREATE USER
            // ======================================================
            if (!empty($validatedData['user_id'])) {
                // Kasus 1: User sudah ada dan dipilih
                $dekanUser = User::find($validatedData['user_id']);
            } else {
                // Kasus 2: Membuat User baru (data user_name, email, password harus ada)
                $dekanUser = User::create([
                    'username' => $validatedData['username'],
                    'email' => $validatedData['user_email'],
                    'no_telepon' => $validatedData['no_telepon'],
                    'password' => bcrypt($validatedData['user_password']),
                    'status'=>'AKTIF'
                    // Tambahkan field user lain yang relevan (misal: role)
                ]);
                Biodata::create([
                    'nama' => $validatedData['user_name'],
                    'jenis_kelamin' => $validatedData['jenis_kelamin'],
                    'tempat_lahir' => $validatedData['tempat_lahir'],
                    'tanggal_lahir' => $validatedData['tanggal_lahir'],
                    'agama' => $validatedData['agama'],
                    'alamat' => $validatedData['alamat'],
                    'kelurahan' => $validatedData['kelurahan'],
                    'kec_id' => $validatedData['kec_id'],
                    'kab_id' => $validatedData['kab_id'],
                    'prov_id' => $validatedData['prov_id'],
                    'user_id' => $dekanUser->id,
                ]);
                
            }

            if (!$dekanUser) {
                throw new \Exception("Gagal menemukan atau membuat data User.");
            }

            
            Sekprodi::create([
                    "user_id" => $dekanUser->id,
                ]);

            DB::commit();

            $message = 'User Sekprodi berhasil dtambahkan!';
            
            return redirect('/sekprodi')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            // Log error untuk debug
            
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data Sekprodi. ' . $e->getMessage());
        }
        return redirect('/sekprodi')->with('success', 'Sekprodi berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Sekprodi $sekprodi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sekprodi $sekprodi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $dekan = Sekprodi::findOrFail($id);
        
        $validated = $request->validate([
        'user_name' => ['string', 'max:255'],
        'username' => ['string', 'max:255','unique:user,username,'.$dekan->User->id],
        'user_email' => ['email', 'unique:user,email,'.$dekan->User->id],
        'no_telepon' => ['string', 'max:15','unique:user,no_telepon,'.$dekan->User->id],
        'jenis_kelamin' => ['string', 'max:1'],
        'agama' => ['string'],
        'tempat_lahir' => ['string'],
        'tanggal_lahir' => ['string'],
        'alamat' => ['string'],
        'kelurahan' => ['string'],
        'kec_id' => ['string'],
        'kab_id' => ['string'],
        'prov_id' => ['string'],
        ]);

        $user = User::findOrFail($dekan->User->id);
        $biodata = Biodata::findOrFail($dekan->User->Biodata->id);

        $user->update([
            'username' => $validated['username'],
            'email' => $validated['user_email'],
            'no_telepon' => $validated['no_telepon'],
            'status'=>'AKTIF'
        ]);
        $biodata->update([
        'nama' => $validated['user_name'],
        'jenis_kelamin' => $validated['jenis_kelamin'],
        'tempat_lahir' => $validated['tempat_lahir'],
        'tanggal_lahir' => $validated['tanggal_lahir'],
        'agama' => $validated['agama'],
        'alamat' => $validated['alamat'],
        'kelurahan' => $validated['kelurahan'],
        'kec_id' => $validated['kec_id'],
        'kab_id' => $validated['kab_id'],
        'prov_id' => $validated['prov_id'],
        'user_id' => $dekan->user_id,
    ]);

        return redirect('/sekprodi')->with('success', 'Sekprodi berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $dekan = Sekprodi::findOrFail($id);
        $dekan->delete();

        return redirect('/sekprodi')->with('success', 'Sekprodi berhasil dihapus!');
    }
}
