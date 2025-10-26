<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HasRoleModel
{
    /**
     * Handle an incoming request.
     *
     * Contoh penggunaan:
     * ->middleware('has.role:dekan')
     * ->middleware('has.role:dekan,kaprodi')
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        if (! $user) {
            return redirect('/')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Jika user memiliki salah satu dari role model yang diberikan
        foreach ($roles as $role) {
            // pastikan nama relasi sesuai di model User
            if (method_exists($user, $role) && $user->$role()->exists()) {
                return $next($request); // akses diizinkan
            }
        }

        // Jika tidak punya salah satu relasi tersebut
        abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
    }
}
