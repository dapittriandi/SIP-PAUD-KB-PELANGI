<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::query();

        // Filter status
        if ($request->filled('status')) {
            $query->where('aktif', $request->status === 'aktif');
        } else {
            $query->where('aktif', true); // default: tampilkan yang aktif
        }

        // Filter kelompok
        if ($request->filled('kelompok')) {
            $query->where('kelompok', $request->kelompok);
        }

        // Pencarian nama / NIS
        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('nama_lengkap', 'like', "%{$cari}%")
                  ->orWhere('nis', 'like', "%{$cari}%");
            });
        }

        $siswa = $query->orderBy('nama_lengkap')->paginate(15)->withQueryString();

        $totalAktif   = Siswa::where('aktif', true)->count();
        $totalNonAktif = Siswa::where('aktif', false)->count();

        return view('siswa.index', compact('siswa', 'totalAktif', 'totalNonAktif'));
    }

    public function create()
    {
        return view('siswa.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules(), $this->messages());

        // Upload foto
        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('foto-siswa', 'public');
        }

        $validated['aktif'] = true;

        Siswa::create($validated);

        return redirect()->route('siswa.index')
            ->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function show(Siswa $siswa)
    {
        $siswa->load('pembayaranSpp');
        return view('siswa.show', compact('siswa'));
    }

    public function edit(Siswa $siswa)
    {
        return view('siswa.edit', compact('siswa'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $validated = $request->validate($this->rules($siswa->id), $this->messages());

        // Upload foto baru
        if ($request->hasFile('foto')) {
            // Hapus foto lama
            if ($siswa->foto) {
                Storage::disk('public')->delete($siswa->foto);
            }
            $validated['foto'] = $request->file('foto')->store('foto-siswa', 'public');
        }

        $siswa->update($validated);

        return redirect()->route('siswa.show', $siswa)
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa)
    {
        // Nonaktifkan saja, tidak hapus permanen (ada relasi pembayaran)
        $siswa->update([
            'aktif'            => false,
            'tanggal_keluar'   => now()->toDateString(),
            'keterangan_keluar'=> 'Dinonaktifkan oleh admin',
        ]);

        return redirect()->route('siswa.index')
            ->with('success', 'Siswa berhasil dinonaktifkan.');
    }

    // ── Validasi ─────────────────────────────────────────────

    private function rules(?int $ignoreId = null): array
    {
        return [
            // Identitas
            'nis'              => 'nullable|string|max:20|unique:siswa,nis' . ($ignoreId ? ",{$ignoreId}" : ''),
            'nama_lengkap'     => 'required|string|max:100',
            'jenis_kelamin'    => 'required|in:L,P',
            'tempat_lahir'     => 'nullable|string|max:100',
            'tanggal_lahir'    => 'nullable|date',
            'agama'            => 'nullable|string|max:20',
            'alamat'           => 'nullable|string|max:500',
            'foto'             => 'nullable|image|max:2048',
            // Akademik
            'kelompok'         => 'required|string|max:10',
            'tanggal_masuk'    => 'nullable|date',
            'tahun_ajaran'     => 'nullable|string|max:10',
            // Ayah
            'nama_ayah'        => 'nullable|string|max:100',
            'no_hp_ayah'       => 'nullable|string|max:20',
            'pekerjaan_ayah'   => 'nullable|string|max:100',
            // Ibu
            'nama_ibu'         => 'nullable|string|max:100',
            'no_hp_ibu'        => 'nullable|string|max:20',
            'pekerjaan_ibu'    => 'nullable|string|max:100',
            // Wali
            'nama_wali'        => 'nullable|string|max:100',
            'hubungan_wali'    => 'nullable|string|max:50',
            'no_hp_wali'       => 'nullable|string|max:20',
        ];
    }

    private function messages(): array
    {
        return [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'kelompok.required'      => 'Kelompok wajib dipilih.',
            'foto.image'             => 'File foto harus berupa gambar.',
            'foto.max'               => 'Ukuran foto maksimal 2MB.',
            'nis.unique'             => 'NIS sudah digunakan siswa lain.',
        ];
    }
}