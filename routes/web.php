<?php

use App\Http\Controllers\AngkatanController;
use App\Http\Controllers\BarterJadwalController;
use App\Http\Controllers\DekanController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\FakultasController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\KaprodiController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\KosmaController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\MatakuliahController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\PindahJadwalController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\SekprodiController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\SuratTugasMengajarController;
use App\Http\Controllers\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
});
Route::post('/login', [User::class, 'login'])->name("login");
Route::post('/logout', [User::class, 'logout'])->name('logout')->middleware('auth');
Route::get('/data', [User::class, 'index'])->name('logout')->middleware('auth');
Route::get('/profil', function () {
    return view('profil');
})->middleware("auth");
Route::put('/profil', [User::class, 'edit'])->middleware('auth');
Route::put('/settings', [User::class, 'editPassword'])->middleware('auth');

// Route::prefix('admin')->middleware('auth')->group(function () {
//     Route::resource('roles', RoleController::class);
// });
Route::resource('kelas', KelasController::class)->middleware('auth');
Route::resource('fakultas', FakultasController::class)->middleware('auth');
Route::resource('prodi', ProdiController::class)->middleware('auth');
Route::resource('semester', SemesterController::class)->middleware('auth');
Route::resource('matakuliah', MatakuliahController::class)->middleware('auth');
Route::resource('dekan', DekanController::class)->middleware('auth');
Route::resource('angkatan', AngkatanController::class)->middleware('auth');
Route::resource('shift', ShiftController::class)->middleware('auth');
Route::resource('ruangan', RuanganController::class)->middleware('auth');
Route::resource('dosen', DosenController::class)->middleware(['auth']);
Route::resource('surat_tugas', SuratTugasMengajarController::class);
Route::resource('jadwal', JadwalController::class)->middleware('auth');
Route::resource('kaprodi', KaprodiController::class)->middleware('auth');
Route::resource('sekprodi', SekprodiController::class)->middleware('auth');
Route::resource('mahasiswa', MahasiswaController::class)->middleware('auth');
Route::resource('kosma', KosmaController::class)->middleware('auth');
Route::resource('barter_jadwal', BarterJadwalController::class)->middleware(['auth']);
Route::resource('pindah_jadwal', PindahJadwalController::class)->middleware(['auth']);

Route::get('/laporan/pdf/show', [PdfController::class, 'generateAndShow'])->name('laporan.pdf.show');

Route::get('/dashboard', [User::class,'dashboard'])->middleware('auth');
Route::get('/jadwal_global', [JadwalController::class,'jadwal_global'])->middleware('auth')->name("jadwal_global");
Route::get('/jadwal_global/export', [JadwalController::class,'export'])->middleware('auth')->name('jadwal_export_ruang');
Route::get('/settings', function () {
    return view('settings');
})->middleware('auth');


// Route::get('/barter_jadwal', function () {
//     return view('barter_jadwal');
// });



Route::get('/api/provinces', function () {
    $response = Http::get('https://wilayah.id/api/provinces.json');
    return response()->json($response->json());
});
Route::get('/api/regencies/{provinceId}', function ($provinceId) {
    $response = Http::get("https://wilayah.id/api/regencies/{$provinceId}.json");
    return response()->json($response->json());
});

// Ambil daftar kecamatan berdasarkan ID kabupaten
Route::get('/api/districts/{regencyId}', function ($regencyId) {
    $response = Http::get("https://wilayah.id/api/districts/{$regencyId}.json");
    return response()->json($response->json());
});

Route::put('/profil/foto', [User::class, 'updateFoto'])->name('profil.updateFoto');
Route::get('/rekap', [User::class, 'jadwal']);
