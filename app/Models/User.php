<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    protected $fillable = [
        'username', 'password', 'pin', 'role', 'aktif',
        'nama_lengkap', 'nik', 'nip', 'nuptk',
        'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir',
        'agama', 'alamat', 'no_hp', 'email', 'foto',
        'status_kepegawaian', 'jabatan',
        'pendidikan_terakhir', 'jurusan', 'tanggal_bergabung',
    ];

    protected $hidden = ['password', 'pin'];

    protected $casts = [
        'aktif'             => 'boolean',
        'tanggal_lahir'     => 'date',
        'tanggal_bergabung' => 'date',
        'roles'             => 'array',
    ];

    // ── Relasi ───────────────────────────────────────────────

    public function absensi(): HasMany
    {
        return $this->hasMany(AbsensiGuru::class, 'guru_id');
    }

    public function pembayaranDicatat(): HasMany
    {
        return $this->hasMany(PembayaranSpp::class, 'dicatat_oleh');
    }

    // ── Helper Role ──────────────────────────────────────────

    public function isAdmin(): bool         { return $this->role === 'admin'; }
    public function isGuru(): bool          { return $this->role === 'guru'; }
    public function isBendahara(): bool     { return $this->role === 'bendahara'; }
    public function isKepalaSekolah(): bool { return $this->role === 'kepala_sekolah'; }
    public function hasRole(string $role): bool
{
    $roles = $this->roles ?? [$this->role];
    return in_array($role, $roles);
}
public function hasAnyRole(array $roles): bool
{
    return collect($roles)->contains(fn($role) => $this->hasRole($role));
}
    // ── Helper Absensi ───────────────────────────────────────

    public function sudahAbsenHariIni(): bool
    {
        return $this->absensi()
                    ->whereDate('tanggal', today())
                    ->exists();
    }

    // ── Accessor ─────────────────────────────────────────────

    public function getUmurAttribute(): ?int
    {
        return $this->tanggal_lahir ? $this->tanggal_lahir->age : null;
    }

    public function getLabelStatusKepegawaianAttribute(): string
    {
        return match ($this->status_kepegawaian) {
            'pns'     => 'PNS',
            'pppk'    => 'P3K',
            'honorer' => 'Honorer',
            'gtty'    => 'GTT Yayasan',
            default   => '-',
        };
    }
}