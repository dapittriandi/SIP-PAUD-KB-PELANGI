<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class AbsensiGuru extends Model
{
    protected $table = 'absensi_guru';

    protected $fillable = [
        'guru_id', 'tanggal', 'jam_masuk', 'status',
        'terlambat_menit', 'keterangan',
        'latitude', 'longitude', 'jarak_meter', 'lokasi_valid',
        'dicatat_oleh',
    ];

    protected $casts = [
        'tanggal'      => 'date',
        'lokasi_valid' => 'boolean',
    ];

    // ── Relasi ───────────────────────────────────────────────

    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function dicatatOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dicatat_oleh');
    }

    // ── Scopes ───────────────────────────────────────────────

    public function scopePeriodik(Builder $query, int $bulan, int $tahun): Builder
    {
        return $query->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
    }

    public function scopeBulanIni(Builder $query): Builder
    {
        return $query->whereMonth('tanggal', now()->month)
                     ->whereYear('tanggal', now()->year);
    }

    // ── Helper ───────────────────────────────────────────────

    public function isSelfCheckIn(): bool { return is_null($this->dicatat_oleh); }

    public function getLabelStatusAttribute(): string
    {
        return match ($this->status) {
            'hadir'      => 'Hadir',
            'terlambat'  => 'Terlambat',
            'izin'       => 'Izin',
            'sakit'      => 'Sakit',
            'tugas_luar' => 'Tugas Luar',
            'alpha'      => 'Alpha',
            default      => ucfirst($this->status),
        };
    }

    public function getWarnaBadgeAttribute(): string
    {
        return match ($this->status) {
            'hadir'      => 'bg-green-100 text-green-800',
            'terlambat'  => 'bg-yellow-100 text-yellow-800',
            'izin'       => 'bg-blue-100 text-blue-800',
            'sakit'      => 'bg-blue-100 text-blue-800',
            'tugas_luar' => 'bg-purple-100 text-purple-800',
            'alpha'      => 'bg-red-100 text-red-800',
            default      => 'bg-gray-100 text-gray-800',
        };
    }
}
