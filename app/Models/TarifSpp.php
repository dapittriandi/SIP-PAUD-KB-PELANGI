<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TarifSpp extends Model
{
    // Nama tabel di DB (migration lama pakai tarif_spp, bukan tarif_spps)
    protected $table = 'tarif_spp';

    protected $fillable = [
        'tahun_berlaku',        // kolom year di DB
        'nominal_spp',
        'nominal_kebersihan',
        'keterangan',
        'dibuat_oleh',
    ];

    protected $casts = [
        'tahun_berlaku'      => 'integer',
        'nominal_spp'        => 'integer',
        'nominal_kebersihan' => 'integer',
    ];

    // =========================================================================
    // BOOTED — isi dibuat_oleh otomatis saat create
    // =========================================================================

    protected static function booted(): void
    {
        static::creating(function (self $tarif) {
            if (Auth::check() && empty($tarif->dibuat_oleh)) {
                $tarif->dibuat_oleh = Auth::id();
            }
        });
    }

    // =========================================================================
    // RELATIONS
    // =========================================================================

    public function dibuatOleh()
    {
        return $this->belongsTo(\App\Models\User::class, 'dibuat_oleh');
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    /** Tarif untuk tahun tertentu */
    public function scopeUntukTahun($query, int $tahun)
    {
        return $query->where('tahun_berlaku', $tahun);
    }

    // =========================================================================
    // ACCESSORS — properti virtual 'tahun' agar kode lama tetap jalan
    // =========================================================================

    /**
     * Alias read-only: $tarif->tahun == $tarif->tahun_berlaku
     * Dipakai di controller dan blade yang sudah terlanjur pakai ->tahun
     */
    public function getTahunAttribute(): int
    {
        return $this->tahun_berlaku;
    }

    /** "Rp 100.000" */
    public function getNominalSppRupiahAttribute(): string
    {
        return 'Rp ' . number_format($this->nominal_spp, 0, ',', '.');
    }

    /** "Rp 20.000" */
    public function getNominalKebersihanRupiahAttribute(): string
    {
        return 'Rp ' . number_format($this->nominal_kebersihan, 0, ',', '.');
    }

    /** "Rp 120.000" */
    public function getTotalRupiahAttribute(): string
    {
        return 'Rp ' . number_format($this->nominal_spp + $this->nominal_kebersihan, 0, ',', '.');
    }

    /** Total sebagai integer (untuk kalkulasi) */
    public function getTotalAttribute(): int
    {
        return $this->nominal_spp + $this->nominal_kebersihan;
    }

    // =========================================================================
    // HELPERS STATIS
    // =========================================================================

    /**
     * Ambil tarif aktif untuk tahun tertentu.
     * Jika belum ada, kembalikan instance kosong (nominal 0) agar view tidak crash.
     */
    public static function aktif(int $tahun): self
    {
        return static::untukTahun($tahun)->latest()->first()
            ?? new static([
                'tahun_berlaku'      => $tahun,
                'nominal_spp'        => 0,
                'nominal_kebersihan' => 0,
            ]);
    }
}