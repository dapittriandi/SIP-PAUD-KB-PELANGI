<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    // =========================================================================
    // TAMPILKAN FORM LUPA PASSWORD (GET /lupa-password)
    // =========================================================================

    public function showForm()
    {
        return view('auth.lupa-password');
    }

    // =========================================================================
    // KIRIM LINK RESET KE EMAIL (POST /lupa-password)
    // =========================================================================

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
        ]);

        $user = User::where('email', $request->email)->first();

        // Selalu tampilkan pesan sukses meski email tidak ditemukan
        // → mencegah enumeration attack (tidak memberi tahu apakah email terdaftar)
        if (! $user) {
            return back()->with(
                'success',
                'Jika email tersebut terdaftar, link reset password telah dikirim. Silakan cek inbox atau folder spam Anda.'
            );
        }

        // Hapus token lama untuk email ini (jika ada)
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        // Buat token baru
        $token = Str::random(64);

        DB::table('password_reset_tokens')->insert([
            'email'      => $request->email,
            'token'      => Hash::make($token),
            'created_at' => Carbon::now(),
        ]);

        // Kirim email
        $resetUrl = route('password.reset', ['token' => $token]) . '?email=' . urlencode($request->email);

        Mail::send('emails.reset-password', [
            'user'     => $user,
            'resetUrl' => $resetUrl,
        ], function ($message) use ($user) {
            $message->to($user->email, $user->nama_lengkap)
                    ->subject('Reset Password — PAUD KB Pelangi');
        });

        return back()->with(
            'success',
            'Link reset password telah dikirim ke email Anda. Silakan cek inbox atau folder spam.'
        );
    }

    // =========================================================================
    // TAMPILKAN FORM RESET PASSWORD (GET /reset-password/{token})
    // =========================================================================

    public function showReset(Request $request, string $token)
    {
        $email = $request->query('email', '');

        // Validasi token sebelum menampilkan form
        $record = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (! $record || ! Hash::check($token, $record->token)) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Link reset password tidak valid atau sudah kadaluarsa.']);
        }

        // Cek expired: token berlaku 60 menit
        if (Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();

            return redirect()->route('password.request')
                ->withErrors(['email' => 'Link reset password sudah kadaluarsa. Silakan minta link baru.']);
        }

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    // =========================================================================
    // PROSES RESET PASSWORD (POST /reset-password)
    // =========================================================================

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ], [
            'password.min'       => 'Password minimal 8 karakter.',
            'password.letters'   => 'Password harus mengandung huruf.',
            'password.numbers'   => 'Password harus mengandung angka.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Cek token
        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (! $record || ! Hash::check($request->token, $record->token)) {
            return back()->withErrors(['email' => 'Link reset password tidak valid atau sudah kadaluarsa.']);
        }

        // Cek expired
        if (Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            return back()->withErrors(['email' => 'Link reset password sudah kadaluarsa. Silakan minta link baru.']);
        }

        // Update password
        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()->withErrors(['email' => 'Akun tidak ditemukan.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        // Hapus token setelah berhasil digunakan
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')
            ->with('status', 'Password berhasil direset. Silakan login dengan password baru Anda.');
    }
}