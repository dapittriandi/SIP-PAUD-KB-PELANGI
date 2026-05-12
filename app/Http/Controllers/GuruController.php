<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class GuruController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'guru');

        if ($request->filled('status')) {
            $query->where('aktif', $request->status === 'aktif');
        } else {
            $query->where('aktif', true);
        }

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('nama_lengkap', 'like', "%{$cari}%")
                  ->orWhere('username', 'like', "%{$cari}%")
                  ->orWhere('nip', 'like', "%{$cari}%");
            });
        }

        $guru = $query->orderBy('nama_lengkap')->paginate(15)->withQueryString();

        $totalAktif    = User::where('role', 'guru')->where('aktif', true)->count();
        $totalNonAktif = User::where('role', 'guru')->where('aktif', false)->count();

        return view('guru.index', compact('guru', 'totalAktif', 'totalNonAktif'));
    }

    public function create()
    {
        return view('guru.create');
    }

    public function store(Request $request)
    {
        $request->validate($this->rules(), $this->messages());

        $data = $request->only([
            'nama_lengkap', 'username',
            'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir',
            'alamat', 'no_hp', 'email',
            'nip', 'nuptk', 'status_kepegawaian',
            'jabatan', 'pendidikan_terakhir', 'jurusan', 'tanggal_bergabung',
        ]);

        $data['role']  = 'guru';
        $data['aktif'] = true;
        $data['pin']   = Hash::make($request->pin);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('foto-guru', 'public');
        }

        User::create($data);

        return redirect()->route('guru.index')
            ->with('success', 'Data guru berhasil ditambahkan.');
    }

    public function show(User $guru)
    {
        abort_unless($guru->role === 'guru', 404);
        $guru->load('absensi');
        return view('guru.show', compact('guru'));
    }

    public function edit(User $guru)
    {
        abort_unless($guru->role === 'guru', 404);
        return view('guru.edit', compact('guru'));
    }

    public function update(Request $request, User $guru)
    {
        abort_unless($guru->role === 'guru', 404);

        $request->validate($this->rules($guru->id), $this->messages());

        $data = $request->only([
            'nama_lengkap', 'username',
            'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir',
            'alamat', 'no_hp', 'email',
            'nip', 'nuptk', 'status_kepegawaian',
            'jabatan', 'pendidikan_terakhir', 'jurusan', 'tanggal_bergabung',
        ]);

        if ($request->filled('pin')) {
            $data['pin'] = Hash::make($request->pin);
        }

        if ($request->hasFile('foto')) {
            if ($guru->foto) {
                Storage::disk('public')->delete($guru->foto);
            }
            $data['foto'] = $request->file('foto')->store('foto-guru', 'public');
        }

        $guru->update($data);

        return redirect()->route('guru.show', $guru)
            ->with('success', 'Data guru berhasil diperbarui.');
    }

    public function toggle(User $guru)
    {
        abort_unless($guru->role === 'guru', 404);
        $guru->update(['aktif' => !$guru->aktif]);
        $status = $guru->aktif ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()
            ->with('success', "Guru {$guru->nama_lengkap} berhasil {$status}.");
    }

    public function resetPin(Request $request, User $guru)
    {
        abort_unless($guru->role === 'guru', 404);

        $request->validate([
            'pin_baru'              => 'required|digits:4',
            'pin_baru_confirmation' => 'required|same:pin_baru',
        ], [
            'pin_baru.required'          => 'PIN baru wajib diisi.',
            'pin_baru.digits'            => 'PIN harus 4 digit angka.',
            'pin_baru_confirmation.same' => 'Konfirmasi PIN tidak cocok.',
        ]);

        $guru->update(['pin' => Hash::make($request->pin_baru)]);

        return redirect()->route('guru.show', $guru)
            ->with('success', 'PIN guru berhasil direset.');
    }

    // ── Validasi ─────────────────────────────────────────────

    private function rules(?int $ignoreId = null): array
    {
        $uniqueUsername = 'unique:users,username' . ($ignoreId ? ",{$ignoreId}" : '');

        return [
            'nama_lengkap'        => 'required|string|max:100',
            'username'            => "required|string|max:50|{$uniqueUsername}",
            'pin'                 => $ignoreId ? 'nullable|digits:4|confirmed' : 'required|digits:4|confirmed',
            'jenis_kelamin'       => 'nullable|in:L,P',
            'tempat_lahir'        => 'nullable|string|max:100',
            'tanggal_lahir'       => 'nullable|date',
            'alamat'              => 'nullable|string|max:500',
            'no_hp'               => 'nullable|string|max:20',
            'email'               => 'nullable|email|max:100',
            'foto'                => 'nullable|image|max:2048',
            'nip'                 => 'nullable|string|max:30',
            'nuptk'               => 'nullable|string|max:20',
            'status_kepegawaian'  => 'nullable|in:pns,pppk,honorer,gtty',
            'jabatan'             => 'nullable|string|max:100',
            'pendidikan_terakhir' => 'nullable|string|max:20',
            'jurusan'             => 'nullable|string|max:100',
            'tanggal_bergabung'   => 'nullable|date',
        ];
    }

    private function messages(): array
    {
        return [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'username.required'     => 'Username wajib diisi.',
            'username.unique'       => 'Username sudah digunakan.',
            'pin.required'          => 'PIN wajib diisi.',
            'pin.digits'            => 'PIN harus 4 digit angka.',
            'pin.confirmed'         => 'Konfirmasi PIN tidak cocok.',
            'foto.image'            => 'File foto harus berupa gambar.',
            'foto.max'              => 'Ukuran foto maksimal 2MB.',
        ];
    }
}