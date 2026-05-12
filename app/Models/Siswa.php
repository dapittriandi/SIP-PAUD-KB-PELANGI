<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Siswa extends Model
{
    protected $table = 'siswa';

    protected $fillable = [
        // Identitas siswa
        'nis', 'nama_lengkap', 'jenis_kelamin',
        'tempat_lahir', 'tanggal_lahir', 'agama',
        'alamat', 'foto',
        // Akademik
        'kelompok', 'tanggal_masuk', 'tahun_ajaran',
        'aktif', 'tanggal_keluar', 'keterangan_keluar',
        // Data ayah
        'nama_ayah', 'nik_ayah', 'tempat_lahir_ayah',
        'tanggal_lahir_ayah', 'pendidikan_ayah',
        'pekerjaan_ayah', 'no_hp_ayah',
        // Data ibu
        'nama_ibu', 'nik_ibu', 'tempat_lahir_ibu',
        'tanggal_lahir_ibu', 'pendidikan_ibu',
        'pekerjaan_ibu', 'no_hp_ibu',
        // Wali (jika bukan ayah/ibu)
        'nama_wali', 'hubungan_wali', 'no_hp_wali',
    ];

    protected $casts = [
        'aktif'              => 'boolean',
        'tanggal_lahir'      => 'date',
        'tanggal_masuk'      => 'date',
        'tanggal_keluar'     => 'date',
        'tanggal_lahir_ayah' => 'date',
        'tanggal_lahir_ibu'  => 'date',
    ];

    // ── Relasi ───────────────────────────────────────────────

    public function pembayaranSpp(): HasMany
    {
        return $this->hasMany(PembayaranSpp::class, 'siswa_id');
    }

    // ── Scopes ───────────────────────────────────────────────

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('aktif', true);
    }

    public function scopeKelompok(Builder $query, string $kelompok): Builder
    {
        return $query->where('kelompok', $kelompok);
    }

    // ── Helper ───────────────────────────────────────────────

    public function sudahBayar(int $bulan, int $tahun): bool
    {
        return $this->pembayaranSpp()
                    ->where('bulan', $bulan)
                    ->where('tahun', $tahun)
                    ->exists();
    }

    // ── Accessor ─────────────────────────────────────────────

    /**
     * Umur siswa berdasarkan tanggal lahir.
     */
    public function getUmurAttribute(): ?string
    {
        if (!$this->tanggal_lahir) return null;
        $umur = $this->tanggal_lahir->diff(now());
        return $umur->y . ' tahun ' . $umur->m . ' bulan';
    }

    /**
     * Nama kontak utama untuk notifikasi (prioritas: wali → ibu → ayah).
     */
    public function getKontakUtamaAttribute(): ?string
    {
        return $this->no_hp_wali ?? $this->no_hp_ibu ?? $this->no_hp_ayah;
    }

    /**
     * Nama wali/orang tua yang bisa dihubungi.
     */
    public function getNamaKontakAttribute(): ?string
    {
        if ($this->no_hp_wali)  return $this->nama_wali;
        if ($this->no_hp_ibu)   return $this->nama_ibu;
        if ($this->no_hp_ayah)  return $this->nama_ayah;
        return null;
    }
}
