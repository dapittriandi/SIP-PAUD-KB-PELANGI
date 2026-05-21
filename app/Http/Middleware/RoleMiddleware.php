<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Contoh pemakaian di route: middleware('role:admin,bendahara')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Belum login → ke halaman login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Akun nonaktif → logout paksa
        if (!$user->aktif) {
            auth()->logout();
            return redirect()->route('login')
                ->withErrors(['msg' => 'Akun Anda telah dinonaktifkan. Hubungi administrator.']);
        }

        // Role tidak sesuai → 403
        if (!empty($roles) && !in_array($user->role, $roles)) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk halaman ini.');
        }

        return $next($request);
    }
}