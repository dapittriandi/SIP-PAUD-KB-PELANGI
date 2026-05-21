<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use App\Models\User;

class ProfileController extends Controller
{
    // =========================================================================
    // TAMPILKAN HALAMAN PROFIL (GET /profile)
    // =========================================================================

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    // =========================================================================
    // UPDATE DATA PROFIL (POST /profile)
    // =========================================================================

    public function update(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'nama_lengkap' => 'required|string|max:100',
            'email'        => 'nullable|email|max:100|unique:users,email,' . $user->id,
            'no_hp'        => 'nullable|string|max:20',
            'foto'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];

        $v = $request->validate($rules);

        // Handle upload foto
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($user->foto) {
                Storage::disk('public')->delete($user->foto);
            }
            $v['foto'] = $request->file('foto')->store('foto-profil', 'public');
        }

        $user->update($v);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    // =========================================================================
    // HAPUS FOTO PROFIL (DELETE /profile/foto)
    // =========================================================================

    public function hapusFoto()
    {
        $user = Auth::user();

        if ($user->foto) {
            Storage::disk('public')->delete($user->foto);
            $user->update(['foto' => null]);
        }

        return back()->with('success', 'Foto profil berhasil dihapus.');
    }

    // =========================================================================
    // GANTI PASSWORD SENDIRI (POST /profile/password)
    // =========================================================================

    public function gantiPassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required|string',
            'password'      => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ], [
            'password.min'       => 'Password minimal 8 karakter.',
            'password.letters'   => 'Password harus mengandung huruf.',
            'password.numbers'   => 'Password harus mengandung angka.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = Auth::user();

        // Verifikasi password lama
        if (!Hash::check($request->password_lama, $user->password)) {
            return back()->withErrors(['password_lama' => 'Password lama tidak sesuai.'])->withInput();
        }

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success_password', 'Password berhasil diubah. Silakan login ulang jika diperlukan.');
    }

    // =========================================================================
    // ADMIN: RESET PASSWORD USER LAIN (POST /admin/users/{user}/reset-password)
    // =========================================================================

    public function adminResetPassword(Request $request, User $user)
    {
        // Hanya admin yang boleh
        abort_unless(Auth::user()->role === 'admin', 403);

        $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ], [
            'password.min'       => 'Password minimal 8 karakter.',
            'password.letters'   => 'Password harus mengandung huruf.',
            'password.numbers'   => 'Password harus mengandung angka.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', "Password {$user->nama_lengkap} berhasil direset.");
    }
}