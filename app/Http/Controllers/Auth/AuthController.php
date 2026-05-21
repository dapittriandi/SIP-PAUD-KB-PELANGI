<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ──────────────────────────────────────────────
    // LOGIN ADMIN / BENDAHARA / KEPALA SEKOLAH
    // ──────────────────────────────────────────────

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // Cari user berdasarkan username (bukan email)
        $user = User::where('username', $request->username)->first();

        // Validasi: user ada, password cocok, bukan role guru
        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()
                ->withInput($request->only('username'))
                ->withErrors(['msg' => 'Username atau password salah.']);
        }

        if ($user->role === 'guru') {
            return back()
                ->withInput($request->only('username'))
                ->withErrors(['msg' => 'Guru login menggunakan PIN. Gunakan halaman login guru.']);
        }

        if (!$user->aktif) {
            return back()
                ->withInput($request->only('username'))
                ->withErrors(['msg' => 'Akun Anda tidak aktif. Hubungi administrator.']);
        }

        Auth::login($user, $request->boolean('remember'));

        return redirect()->route('dashboard');
    }

    // ──────────────────────────────────────────────
    // LOGIN GURU (PIN 4 digit)
    // ──────────────────────────────────────────────

    public function showPinLogin()
    {
        return view('auth.login-guru');
    }

    public function pinLogin(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'pin'      => 'required|digits:4',
        ], [
            'username.required' => 'Username wajib diisi.',
            'pin.required'      => 'PIN wajib diisi.',
            'pin.digits'        => 'PIN harus 4 digit angka.',
        ]);

        $user = User::where('username', $request->username)
                    ->where('role', 'guru')
                    ->first();

        if (!$user || !Hash::check($request->pin, $user->pin)) {
            return back()
                ->withInput($request->only('username'))
                ->withErrors(['msg' => 'Username atau PIN salah.']);
        }

        if (!$user->aktif) {
            return back()
                ->withInput($request->only('username'))
                ->withErrors(['msg' => 'Akun Anda tidak aktif. Hubungi administrator.']);
        }

        Auth::login($user);

        return redirect()->route('absensi.index');
    }

    // ──────────────────────────────────────────────
    // LOGOUT
    // ──────────────────────────────────────────────

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}