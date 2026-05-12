<?php

namespace App\Models;

use App\Services\KwitansiService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class PembayaranSpp extends Model
{
    protected $table = 'pembayaran_spp';

    protected $fillable = [
        'siswa_id', 'bulan', 'tahun',
        'nominal_spp', 'nominal_kebersihan', 'total',
        'tanggal_bayar', 'dicatat_oleh', 'no_kwitansi',
    ];

    protected $casts = ['tanggal_bayar' => 'date'];

    protected static function booted(): void
    {
        static::creating(function (PembayaranSpp $spp) {
            $spp->total = $spp->nominal_spp + $spp->nominal_kebersihan;
            if (empty($spp->no_kwitansi)) {
                $spp->no_kwitansi = KwitansiService::generateNomor();
            }
        });
    }

    // ── Relasi ───────────────────────────────────────────────

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function dicatatOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dicatat_oleh');
    }

    // ── Scopes ───────────────────────────────────────────────

    public function scopePeriodik(Builder $query, int $bulan, int $tahun): Builder
    {
        return $query->where('bulan', $bulan)->where('tahun', $tahun);
    }

    public function scopeBulanIni(Builder $query): Builder
    {
        return $query->where('bulan', now()->month)->where('tahun', now()->year);
    }

    // ── Accessor ─────────────────────────────────────────────

    public function getNamaBulanAttribute(): string
    {
        $bulan = ['','Januari','Februari','Maret','April','Mei','Juni',
                  'Juli','Agustus','September','Oktober','November','Desember'];
        return $bulan[(int) $this->bulan] ?? '-';
    }

    public function getTotalRupiahAttribute(): string
    {
        return 'Rp ' . number_format($this->total, 0, ',', '.');
    }
}
