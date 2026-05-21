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
     *
     * Mendukung multi-role: user bisa punya lebih dari 1 role
     * yang disimpan di kolom `roles` sebagai JSON array.
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

        // Cek apakah salah satu role user cocok dengan yang dibutuhkan
        if (!empty($roles)) {
            $userRoles = $user->roles ?? [$user->role];

            $hasAccess = collect($roles)->contains(function ($role) use ($userRoles) {
                return in_array($role, $userRoles);
            });

            if (!$hasAccess) {
                abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk halaman ini.');
            }
        }

        return $next($request);
    }
}