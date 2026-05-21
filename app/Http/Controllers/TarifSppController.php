<?php

namespace App\Http\Controllers;

use App\Models\TarifSpp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TarifSppController extends Controller
{
    // Middleware diatur di web.php: role:admin,bendahara
    // Destroy hanya boleh admin — dicek manual di method destroy()

    // =========================================================================
    // INDEX — daftar semua tarif per tahun
    // =========================================================================

    public function index()
    {
        $tarifs = TarifSpp::orderByDesc('tahun_berlaku')->get();

        // Tahun yang sudah ada (untuk cegah duplikat di form tambah)
        $tahunTerpakai = $tarifs->pluck('tahun_berlaku')->all();

        // Saran tahun baru = tahun terbesar + 1, minimal tahun ini
        $tahunBaru = max(now()->year, ($tarifs->first()?->tahun_berlaku ?? now()->year - 1) + 1);

        return view('spp.tarif.index', compact('tarifs', 'tahunTerpakai', 'tahunBaru'));
    }

    // =========================================================================
    // STORE — simpan tarif baru
    // =========================================================================

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun_berlaku'      => 'required|integer|min:2020|max:2099|unique:tarif_spp,tahun_berlaku',
            'nominal_spp'        => 'required|integer|min:0',
            'nominal_kebersihan' => 'required|integer|min:0',
            'keterangan'         => 'nullable|string|max:200',
        ], [
            'tahun_berlaku.required' => 'Tahun wajib diisi.',
            'tahun_berlaku.unique'   => 'Tarif untuk tahun ini sudah ada. Gunakan fitur Edit.',
            'tahun_berlaku.min'      => 'Tahun minimal 2020.',
            'nominal_spp.required'   => 'Nominal SPP wajib diisi.',
            'nominal_spp.min'        => 'Nominal SPP tidak boleh negatif.',
            'nominal_kebersihan.required' => 'Nominal kebersihan wajib diisi.',
            'nominal_kebersihan.min'      => 'Nominal kebersihan tidak boleh negatif.',
        ]);

        TarifSpp::create($validated);

        return redirect()->route('spp.tarif.index')
            ->with('success', "Tarif SPP tahun {$validated['tahun_berlaku']} berhasil ditambahkan.");
    }

    // =========================================================================
    // UPDATE — edit tarif yang sudah ada (inline)
    // =========================================================================

    public function update(Request $request, TarifSpp $tarif)
    {
        $validated = $request->validate([
            'nominal_spp'        => 'required|integer|min:0',
            'nominal_kebersihan' => 'required|integer|min:0',
            'keterangan'         => 'nullable|string|max:200',
        ], [
            'nominal_spp.required'        => 'Nominal SPP wajib diisi.',
            'nominal_spp.min'             => 'Nominal SPP tidak boleh negatif.',
            'nominal_kebersihan.required' => 'Nominal kebersihan wajib diisi.',
            'nominal_kebersihan.min'      => 'Nominal kebersihan tidak boleh negatif.',
        ]);

        $tarif->update($validated);

        return redirect()->route('spp.tarif.index')
            ->with('success', "Tarif SPP tahun {$tarif->tahun_berlaku} berhasil diperbarui.");
    }

    // =========================================================================
    // DESTROY — hapus tarif (admin only)
    // =========================================================================

    public function destroy(TarifSpp $tarif)
    {
        abort_unless(Auth::user()->role === 'admin', 403, 'Hanya admin yang dapat menghapus tarif.');

        $tahun = $tarif->tahun_berlaku;
        $tarif->delete();

        return redirect()->route('spp.tarif.index')
            ->with('success', "Tarif SPP tahun {$tahun} berhasil dihapus.");
    }
}