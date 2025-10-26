<?php

namespace App\Http\Controllers;

use App\Models\BarterJadwal;
use App\Models\Jadwal;
use App\Models\PindahJadwal;
use App\Models\SuratTugasMengajar;
use App\Models\User as ModelsUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class User extends Controller
{
    public function index(Request $request){
        $query = ModelsUser::query();

        // Searching berdasarkan nama
        if ($request->filled('search')) {
            $query->where('biodata.nama', 'like', '%'.$request->search.'%')
            ->orWhere('username', 'like', '%'.$request->search.'%')
            ->orWhere('email', 'like', '%'.$request->search.'%');
        }


        // Pagination, misal 10 data per halaman
        $user = $query->orderBy('id', 'desc')->paginate(10);

        // Biar query string tetap terbawa saat paginate link
        $user->appends($request->all());

        return view('data', compact('user'));
    }
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard'); // Arahkan ke halaman setelah login
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        // 1. Menghapus informasi otentikasi (session)
        Auth::logout(); 

        // 2. Membatalkan session saat ini agar tidak bisa digunakan lagi (regenerasi session ID)
        $request->session()->invalidate(); 

        // 3. Meregenerasi token CSRF baru untuk session berikutnya
        $request->session()->regenerateToken(); 

        // 4. Mengarahkan pengguna kembali ke halaman yang diinginkan (misalnya halaman login atau home)
        return redirect('/'); 
    }
    public function edit(Request $request){
        $user = ModelsUser::findOrFail(Auth::user()->id);
        
        $validated = $request->validate([
        'nama' => 'required|string|max:255',
        'email' => 'required|string|max:255',
        'no_telepon' => 'nullable|string',
        'alamat' => 'nullable|string',
        'keterangan' => 'required|string',
        ]);
        
        $user->update([
            "email" => $validated["email"],
            "no_telepon" => $validated["no_telepon"]
        ]);
        $bio = $user->Biodata;
        $bio->update([
            "nama" => $validated['nama'],
            "alamat" => $validated['alamat'],
            "keterangan" => $validated['keterangan'],
        ]);

        return redirect('/profil')->with('success', 'Pengguna ' . $user->Biodata->nama . ' berhasil diperbarui!');
    }
    public function editPassword(Request $request){
        $user = ModelsUser::findOrFail(Auth::user()->id);
        
        $validated = $request->validate([
        'nama' => 'required|string|max:255',
        'email' => 'required|string|max:255',
        'password_baru' => ['required', 'string'],
        'password_lama' => ['required', 'string', 'current_password'],
        ]);
        
        $user->update([
            "email" => $validated["email"],
            "password" => Hash::make($validated["password_baru"])
        ]);
        $bio = $user->Biodata;
        $bio->update([
            "nama" => $validated['nama'],
        ]);

        return redirect('/settings')->with('success', 'Pengguna ' . $user->Biodata->nama . ' berhasil diperbarui!');
    }
    public function dashboard(){
        $user = ModelsUser::where("status","AKTIF")->count();
        $surat = SuratTugasMengajar::count();
        $pindah = PindahJadwal::count();
        $barter = BarterJadwal::count();
        $total = $surat+$pindah+$barter;

        return view('dashboard',compact('user','total'));
    
    }
    // ProfilController.php
    public function updateFoto(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Mengambil model Biodata terkait
        $biodata = auth()->user()->Biodata;

        // 1. Hapus foto lama jika ada
        if ($biodata->foto_profil) {
            // Menggunakan facade Storage untuk menghapus file dari disk 'public'
            // Jalur di dalam disk 'public' adalah 'foto_profil/{filename}'
            Storage::disk('public')->delete('foto_profil/' . $biodata->foto_profil);
        }

        // 2. Simpan foto baru
        $filename = time() . '.' . $request->foto->extension();
        
        // Menggunakan facade Storage untuk menyimpan file ke folder 'foto_profil'
        // di dalam disk 'public'. Ini konsisten dengan langkah penghapusan.
        $request->file('foto')->storeAs('foto_profil', $filename, 'public');

        // 3. Perbarui nama file di database
        $biodata->foto_profil = $filename;
        $biodata->save();

        return back()->with('success', 'Foto profil berhasil diperbarui!');
    }

    public function jadwal()
    {
       $jadwal = Jadwal::with(['suratTugasMengajar.kelas','shift'])->get();

        $data = $jadwal->map(function ($event) {
            // buat tanggal dari hari dan jam_mulai/jam_selesai
            // misal ambil minggu ini sebagai acuan
            $hariArray = [
                "SENIN" => 1,
                "SELASA" => 2,
                "RABU" => 3,
                "KAMIS" => 4,
                "JUMAT" => 5,
                "SABTU" => 6,
                "MINGGU" => 7,
            ];

            $date = \Carbon\Carbon::now()->startOfWeek()->addDays($hariArray[$event->hari] - 1);

            $start = \Carbon\Carbon::parse($date->format('Y-m-d') . ' ' . $event->shift->jam_mulai);
            $end = \Carbon\Carbon::parse($date->format('Y-m-d') . ' ' . $event->shift->jam_selesai);

            return [
                'id' => $event->id,
                'title' => $event->suratTugasMengajar->kelas->tipe." " .$event->suratTugasMengajar->kelas->nama . " " . $event->suratTugasMengajar->matakuliah->nama,
                'start' => $start->toIso8601String(),
                'end' => $end->toIso8601String(),
            ];
        });

        return response()->json($data);
    }

}
