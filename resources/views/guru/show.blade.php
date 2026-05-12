{{-- SIMPAN SEBAGAI: resources/views/guru/show.blade.php --}}
@extends('layouts.app')
@section('title', $guru->nama_lengkap . ' — PAUD KB Pelangi')
@section('page-title', 'Detail Guru')

@section('content')
<div class="show-wrap">

    {{-- Breadcrumb --}}
    <nav class="breadcrumb">
        <a href="{{ route('guru.index') }}" class="bc-link">Data Guru</a>
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
        <span class="bc-current">{{ $guru->nama_lengkap }}</span>
    </nav>

    {{-- ======================== HERO CARD ======================== --}}
    <div class="hero-card glass-card">
        {{-- Decorative gradient bar --}}
        <div class="hero-bar"></div>

        <div class="hero-body">
            {{-- Avatar --}}
            <div class="hero-avatar-wrap">
                <div class="hero-avatar {{ $guru->aktif ? 'av-aktif' : 'av-nonaktif' }}">
                    @if($guru->foto)
                        <img src="{{ Storage::url($guru->foto) }}" alt="{{ $guru->nama_lengkap }}" class="avatar-img">
                    @else
                        {{ strtoupper(substr($guru->nama_lengkap, 0, 2)) }}
                    @endif
                </div>
                <span class="avatar-dot {{ $guru->aktif ? 'dot-online' : 'dot-offline' }}"></span>
            </div>

            {{-- Info --}}
            <div class="hero-info">
                <div class="hero-top">
                    <div>
                        <h2 class="hero-name">{{ $guru->nama_lengkap }}</h2>
                        <p class="hero-username">&#64;{{ $guru->username }}</p>
                        <div class="hero-chips">
                            @if($guru->jabatan)
                            <span class="chip-jabatan">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                                {{ $guru->jabatan }}
                            </span>
                            @endif
                            @if($guru->label_status_kepegawaian !== '-')
                            <span class="chip-kepeg">{{ $guru->label_status_kepegawaian }}</span>
                            @endif
                            @if($guru->aktif)
                            <span class="chip-aktif"><span class="dot"></span>Aktif</span>
                            @else
                            <span class="chip-nonaktif">Nonaktif</span>
                            @endif
                        </div>
                    </div>

                    {{-- Actions --}}
                    @if(Auth::user()->isAdmin())
                    <div class="hero-actions">
                        <a href="{{ route('guru.edit', $guru) }}" class="btn-primary">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            Edit
                        </a>
                        <form method="POST" action="{{ route('guru.toggle', $guru) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn-toggle {{ $guru->aktif ? 'btn-deactivate' : 'btn-activate' }}">
                                @if($guru->aktif)
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M18.36 6.64a9 9 0 1 1-12.73 0"/><line x1="12" y1="2" x2="12" y2="12"/></svg>
                                Nonaktifkan
                                @else
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M18.36 6.64a9 9 0 1 1-12.73 0"/><line x1="12" y1="2" x2="12" y2="12"/></svg>
                                Aktifkan
                                @endif
                            </button>
                        </form>
                    </div>
                    @endif
                </div>

                {{-- Quick stats --}}
                @php
                    $absenBulanIni = $guru->absensi->filter(fn($a) => $a->tanggal->month === now()->month && $a->tanggal->year === now()->year);
                    $hadir  = $absenBulanIni->whereIn('status', ['hadir','terlambat'])->count();
                    $izin   = $absenBulanIni->whereIn('status', ['izin','sakit','tugas_luar'])->count();
                    $alpha  = $absenBulanIni->where('status', 'alpha')->count();
                    $total  = $hadir + $izin + $alpha;
                    $pct    = $total > 0 ? round($hadir / $total * 100) : 0;
                @endphp
                <div class="hero-stats">
                    <div class="stat-item">
                        <span class="stat-val stat-hadir">{{ $hadir }}</span>
                        <span class="stat-lbl">Hadir</span>
                    </div>
                    <div class="stat-sep"></div>
                    <div class="stat-item">
                        <span class="stat-val stat-izin">{{ $izin }}</span>
                        <span class="stat-lbl">Izin/Sakit</span>
                    </div>
                    <div class="stat-sep"></div>
                    <div class="stat-item">
                        <span class="stat-val stat-alpha">{{ $alpha }}</span>
                        <span class="stat-lbl">Alpha</span>
                    </div>
                    <div class="stat-sep"></div>
                    <div class="stat-item">
                        <span class="stat-val stat-pct">{{ $pct }}%</span>
                        <span class="stat-lbl">Kehadiran</span>
                    </div>
                    <div class="stat-month">{{ now()->translatedFormat('F Y') }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ======================== INFO GRID ======================== --}}
    <div class="info-grid">

        {{-- Data Pribadi --}}
        <div class="info-card glass-card">
            <div class="info-header">
                <div class="info-icon icon-blue">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
                <h3 class="info-title">Data Pribadi</h3>
            </div>
            <div class="info-body">
                <div class="info-row">
                    <span class="info-label">No. HP</span>
                    <span class="info-value">
                        @if($guru->no_hp)
                        <a href="tel:{{ $guru->no_hp }}" class="info-link">{{ $guru->no_hp }}</a>
                        @else —@endif
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email</span>
                    <span class="info-value">
                        @if($guru->email)
                        <a href="mailto:{{ $guru->email }}" class="info-link">{{ $guru->email }}</a>
                        @else —@endif
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Jenis Kelamin</span>
                    <span class="info-value">
                        {{ $guru->jenis_kelamin === 'L' ? 'Laki-laki' : ($guru->jenis_kelamin === 'P' ? 'Perempuan' : '—') }}
                    </span>
                </div>
                @if($guru->tempat_lahir || $guru->tanggal_lahir)
                <div class="info-row">
                    <span class="info-label">Tempat / Tgl Lahir</span>
                    <span class="info-value">
                        {{ collect([$guru->tempat_lahir, $guru->tanggal_lahir?->translatedFormat('d F Y')])->filter()->join(', ') ?: '—' }}
                    </span>
                </div>
                @endif
                <div class="info-row">
                    <span class="info-label">Alamat</span>
                    <span class="info-value">{{ $guru->alamat ?? '—' }}</span>
                </div>
            </div>
        </div>

        {{-- Data Kepegawaian --}}
        <div class="info-card glass-card">
            <div class="info-header">
                <div class="info-icon icon-violet">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                </div>
                <h3 class="info-title">Data Kepegawaian</h3>
            </div>
            <div class="info-body">
                <div class="info-row">
                    <span class="info-label">NIP</span>
                    <span class="info-value mono">{{ $guru->nip ?? '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">NUPTK</span>
                    <span class="info-value mono">{{ $guru->nuptk ?? '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status</span>
                    <span class="info-value">
                        @if($guru->label_status_kepegawaian !== '-')
                        <span class="chip-kepeg">{{ $guru->label_status_kepegawaian }}</span>
                        @else —@endif
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Pendidikan</span>
                    <span class="info-value">
                        {{ trim(($guru->pendidikan_terakhir ?? '') . ' ' . ($guru->jurusan ?? '')) ?: '—' }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Bergabung</span>
                    <span class="info-value">{{ $guru->tanggal_bergabung?->translatedFormat('d F Y') ?? '—' }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ======================== ABSENSI DETAIL ======================== --}}
    <div class="glass-card absen-card">
        <div class="info-header">
            <div class="info-icon icon-indigo">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </div>
            <h3 class="info-title">Rekap Absensi — {{ now()->translatedFormat('F Y') }}</h3>
        </div>
        <div class="absen-body">
            <div class="absen-stats">
                <div class="absen-stat-item">
                    <div class="absen-num hadir">{{ $hadir }}</div>
                    <div class="absen-lbl">Hadir</div>
                    <div class="absen-bar-wrap"><div class="absen-bar bar-hadir" style="width:{{ $total > 0 ? round($hadir/$total*100) : 0 }}%"></div></div>
                </div>
                <div class="absen-stat-item">
                    <div class="absen-num izin">{{ $izin }}</div>
                    <div class="absen-lbl">Izin / Sakit</div>
                    <div class="absen-bar-wrap"><div class="absen-bar bar-izin" style="width:{{ $total > 0 ? round($izin/$total*100) : 0 }}%"></div></div>
                </div>
                <div class="absen-stat-item">
                    <div class="absen-num alpha">{{ $alpha }}</div>
                    <div class="absen-lbl">Alpha</div>
                    <div class="absen-bar-wrap"><div class="absen-bar bar-alpha" style="width:{{ $total > 0 ? round($alpha/$total*100) : 0 }}%"></div></div>
                </div>
            </div>

            {{-- Progress ring area --}}
            <div class="absen-pct-wrap">
                <div class="absen-pct-ring">
                    <svg width="80" height="80" viewBox="0 0 80 80">
                        <circle cx="40" cy="40" r="32" fill="none" stroke="var(--ring-track)" stroke-width="7"/>
                        <circle cx="40" cy="40" r="32" fill="none" stroke="var(--indigo)" stroke-width="7"
                            stroke-dasharray="{{ round(2 * 3.14159 * 32) }}"
                            stroke-dashoffset="{{ round(2 * 3.14159 * 32 * (1 - $pct/100)) }}"
                            stroke-linecap="round"
                            transform="rotate(-90 40 40)"/>
                    </svg>
                    <div class="absen-pct-val">{{ $pct }}%</div>
                </div>
                <p class="absen-pct-lbl">Tingkat<br>Kehadiran</p>
            </div>
        </div>
    </div>

    {{-- ======================== RESET PIN ======================== --}}
    @if(Auth::user()->isAdmin())
    <div class="glass-card pin-card" x-data="{ open: false }">
        <button type="button" @click="open = !open" class="pin-toggle">
            <div class="pin-toggle-left">
                <div class="info-icon icon-amber">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                </div>
                <div>
                    <p class="pin-title">Reset PIN Guru</p>
                    <p class="pin-sub">Atur ulang PIN login guru ini</p>
                </div>
            </div>
            <svg class="pin-chevron" :class="open ? 'rotated' : ''" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
        </button>

        <div x-show="open" x-collapse class="pin-body">
            <form method="POST" action="{{ route('guru.reset-pin', $guru) }}">
                @csrf
                <div class="pin-fields">
                    <div class="field-group">
                        <label class="field-label">PIN Baru <span class="req">*</span></label>
                        <input type="password" name="pin_baru" placeholder="••••" maxlength="4" required
                               class="field-input field-pin {{ $errors->has('pin_baru') ? 'is-error' : '' }}"
                               inputmode="numeric" pattern="[0-9]*">
                        @error('pin_baru')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="field-group">
                        <label class="field-label">Konfirmasi PIN <span class="req">*</span></label>
                        <input type="password" name="pin_baru_confirmation" placeholder="••••" maxlength="4" required
                               class="field-input field-pin"
                               inputmode="numeric" pattern="[0-9]*">
                    </div>
                </div>
                <button type="submit" class="btn-reset-pin">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 2v6h-6"/><path d="M3 12a9 9 0 0 1 15-6.7L21 8"/><path d="M3 22v-6h6"/><path d="M21 12a9 9 0 0 1-15 6.7L3 16"/></svg>
                    Reset PIN
                </button>
            </form>
        </div>
    </div>
    @endif

</div>

@push('styles')
<style>
/* ============================================
   GURU SHOW — Glass · Professional · Indigo
   ============================================ */

:root {
    --indigo:        #6366f1;
    --indigo-dark:   #4f46e5;
    --indigo-light:  #eef2ff;
    --indigo-mid:    #c7d2fe;
    --glass-bg:      rgba(255,255,255,0.72);
    --glass-border:  rgba(99,102,241,0.13);
    --glass-blur:    blur(18px);
    --glass-shadow:  0 4px 24px rgba(99,102,241,0.07), 0 1px 4px rgba(0,0,0,0.04);
    --tx-1:  #1e1b4b;
    --tx-2:  #4338ca;
    --tx-3:  #6b7280;
    --tx-4:  #9ca3af;
    --ring-track: rgba(99,102,241,0.12);
    --border-field:   rgba(99,102,241,0.18);
    --bg-field:       rgba(255,255,255,0.7);
    --bg-field-focus: rgba(255,255,255,0.95);
    --radius:  16px;
    --radius-s: 9px;
    --t: 0.16s ease;
    --font: 'Inter', 'Plus Jakarta Sans', system-ui, sans-serif;
}

@media (prefers-color-scheme: dark) {
    :root {
        --glass-bg:       rgba(18,20,35,0.84);
        --glass-border:   rgba(99,102,241,0.2);
        --glass-shadow:   0 4px 28px rgba(0,0,0,0.55), 0 1px 4px rgba(0,0,0,0.3);
        --indigo-light:   rgba(99,102,241,0.14);
        --indigo-mid:     rgba(99,102,241,0.28);
        --tx-1:  #e0e7ff;
        --tx-2:  #a5b4fc;
        --tx-3:  #6b7280;
        --tx-4:  #374151;
        --ring-track: rgba(99,102,241,0.18);
        --border-field:   rgba(99,102,241,0.22);
        --bg-field:       rgba(255,255,255,0.05);
        --bg-field-focus: rgba(255,255,255,0.09);
    }
}

*, *::before, *::after { box-sizing: border-box; }

.show-wrap {
    max-width: 760px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 14px;
    font-family: var(--font);
}

.glass-card {
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius);
    box-shadow: var(--glass-shadow);
    backdrop-filter: var(--glass-blur);
    -webkit-backdrop-filter: var(--glass-blur);
    overflow: hidden;
}

/* ---- Breadcrumb ---- */
.breadcrumb {
    display: flex; align-items: center; gap: 6px;
    font-size: 0.79rem; color: var(--tx-3);
}
.bc-link {
    color: var(--indigo); text-decoration: none;
    font-weight: 500; transition: color var(--t);
}
.bc-link:hover { color: var(--indigo-dark); text-decoration: underline; }
.bc-current { font-weight: 600; color: var(--tx-1); }
.breadcrumb svg { color: var(--tx-4); flex-shrink: 0; }

/* ---- Hero Card ---- */
.hero-bar {
    height: 4px;
    background: linear-gradient(90deg, #6366f1, #818cf8, #a5b4fc);
}
.hero-body {
    padding: 20px;
    display: flex;
    gap: 18px;
    align-items: flex-start;
}

/* Avatar */
.hero-avatar-wrap { position: relative; flex-shrink: 0; }
.hero-avatar {
    width: 76px; height: 76px;
    border-radius: 20px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; font-weight: 800; color: #fff;
    overflow: hidden;
}
.av-aktif    { background: linear-gradient(135deg, #6366f1 0%, #818cf8 100%); }
.av-nonaktif { background: linear-gradient(135deg, #94a3b8 0%, #cbd5e1 100%); }
.avatar-img  { width: 100%; height: 100%; object-fit: cover; }
.avatar-dot {
    position: absolute; bottom: -2px; right: -2px;
    width: 14px; height: 14px;
    border-radius: 50%; border: 2.5px solid var(--glass-bg);
}
.dot-online  { background: #22c55e; }
.dot-offline { background: #94a3b8; }

/* Hero Info */
.hero-info { flex: 1; min-width: 0; }
.hero-top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 14px;
}
.hero-name {
    font-size: 1.2rem; font-weight: 800; color: var(--tx-1);
    letter-spacing: -0.025em; margin: 0 0 2px;
}
.hero-username {
    font-size: 0.77rem; color: var(--tx-3); margin: 0 0 8px;
    font-family: 'SF Mono', 'Fira Code', monospace;
}
.hero-chips { display: flex; align-items: center; flex-wrap: wrap; gap: 6px; }

/* Chips */
.chip-jabatan {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 0.7rem; font-weight: 600;
    color: var(--tx-2); background: var(--indigo-light);
    border: 1px solid var(--indigo-mid);
    padding: 3px 9px; border-radius: 6px;
}
.chip-kepeg {
    display: inline-flex; align-items: center;
    font-size: 0.7rem; font-weight: 500;
    color: var(--tx-2); background: rgba(99,102,241,0.08);
    border: 1px solid rgba(99,102,241,0.15);
    padding: 3px 9px; border-radius: 6px;
}
.chip-aktif {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 0.7rem; font-weight: 700;
    color: var(--indigo-dark); background: var(--indigo-light);
    border: 1px solid var(--indigo-mid);
    padding: 3px 9px; border-radius: 20px;
}
.chip-aktif .dot {
    width: 5px; height: 5px; border-radius: 50%;
    background: var(--indigo);
    animation: blink 2.4s ease-in-out infinite;
}
@keyframes blink { 0%,100%{opacity:1} 50%{opacity:.3} }
.chip-nonaktif {
    display: inline-flex; align-items: center;
    font-size: 0.7rem; font-weight: 600;
    color: var(--tx-3); background: rgba(107,114,128,0.09);
    border: 1px solid rgba(107,114,128,0.18);
    padding: 3px 9px; border-radius: 20px;
}

/* Hero Actions */
.hero-actions { display: flex; gap: 8px; flex-wrap: wrap; }
.btn-primary {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 16px;
    background: var(--indigo); color: #fff;
    font-size: 0.8rem; font-weight: 600;
    border-radius: var(--radius-s); text-decoration: none;
    transition: background var(--t), transform var(--t), box-shadow var(--t);
    box-shadow: 0 2px 8px rgba(99,102,241,0.3);
    white-space: nowrap;
}
.btn-primary:hover {
    background: var(--indigo-dark);
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(99,102,241,0.4);
}
.btn-toggle {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 14px;
    font-size: 0.8rem; font-weight: 600;
    border-radius: var(--radius-s);
    background: var(--glass-bg);
    border: 1.5px solid var(--glass-border);
    cursor: pointer; font-family: var(--font);
    transition: background var(--t), color var(--t);
    white-space: nowrap;
    backdrop-filter: var(--glass-blur);
}
.btn-deactivate { color: #dc2626; }
.btn-deactivate:hover { background: rgba(220,38,38,0.07); border-color: rgba(220,38,38,0.2); }
.btn-activate   { color: #16a34a; }
.btn-activate:hover   { background: rgba(22,163,74,0.07);  border-color: rgba(22,163,74,0.2); }

/* Hero Stats */
.hero-stats {
    display: flex;
    align-items: center;
    gap: 0;
    background: rgba(99,102,241,0.04);
    border: 1px solid var(--glass-border);
    border-radius: 12px;
    padding: 10px 0;
    flex-wrap: wrap;
}
.stat-item {
    flex: 1; min-width: 56px;
    display: flex; flex-direction: column; align-items: center; gap: 1px;
    padding: 4px 10px;
}
.stat-val {
    font-size: 1.15rem; font-weight: 800; line-height: 1;
}
.stat-hadir { color: #16a34a; }
.stat-izin  { color: #d97706; }
.stat-alpha { color: #dc2626; }
.stat-pct   { color: var(--indigo); }
.stat-lbl { font-size: 0.65rem; color: var(--tx-3); font-weight: 500; }
.stat-sep { width: 1px; height: 28px; background: var(--glass-border); flex-shrink: 0; }
.stat-month {
    flex-basis: 100%; text-align: right;
    font-size: 0.65rem; color: var(--tx-4);
    padding: 4px 14px 0;
    margin-top: 2px;
}

/* ---- Info Grid ---- */
.info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
}

/* ---- Info Card ---- */
.info-header {
    display: flex; align-items: center; gap: 10px;
    padding: 13px 18px;
    border-bottom: 1px solid var(--glass-border);
    background: rgba(99,102,241,0.025);
}
.info-icon {
    width: 30px; height: 30px;
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.icon-blue   { background: rgba(99,102,241,0.12); color: var(--indigo); }
.icon-violet { background: rgba(139,92,246,0.12); color: #7c3aed; }
.icon-indigo { background: rgba(99,102,241,0.12); color: var(--indigo); }
.icon-amber  { background: rgba(245,158,11,0.12); color: #d97706; }
@media (prefers-color-scheme: dark) {
    .icon-blue, .icon-indigo { background: rgba(99,102,241,0.18); }
    .icon-violet { background: rgba(139,92,246,0.18); }
    .icon-amber  { background: rgba(245,158,11,0.18); }
}
.info-title { font-size: 0.84rem; font-weight: 700; color: var(--tx-1); margin: 0; }

.info-body { padding: 14px 18px; display: flex; flex-direction: column; gap: 0; }
.info-row {
    display: flex; gap: 10px; align-items: baseline;
    padding: 7px 0;
    border-bottom: 1px solid var(--glass-border);
}
.info-row:last-child { border-bottom: none; }
.info-label {
    font-size: 0.71rem; font-weight: 600;
    color: var(--tx-3);
    min-width: 100px; flex-shrink: 0;
    padding-top: 1px;
}
.info-value {
    font-size: 0.8rem; color: var(--tx-1);
    font-weight: 500; flex: 1;
    word-break: break-word;
}
.info-value.mono { font-family: 'SF Mono', 'Fira Code', monospace; font-size: 0.74rem; }
.info-link {
    color: var(--indigo); text-decoration: none;
    transition: color var(--t);
}
.info-link:hover { color: var(--indigo-dark); text-decoration: underline; }

/* ---- Absensi Card ---- */
.absen-card { }
.absen-body {
    padding: 16px 18px;
    display: flex;
    gap: 20px;
    align-items: center;
}
.absen-stats { flex: 1; display: flex; flex-direction: column; gap: 12px; }
.absen-stat-item { display: flex; flex-direction: column; gap: 5px; }
.absen-num {
    font-size: 1.3rem; font-weight: 800; line-height: 1;
}
.absen-num.hadir { color: #16a34a; }
.absen-num.izin  { color: #d97706; }
.absen-num.alpha { color: #dc2626; }
.absen-lbl { font-size: 0.71rem; color: var(--tx-3); font-weight: 500; }
.absen-bar-wrap {
    height: 5px; border-radius: 10px;
    background: rgba(99,102,241,0.08);
    overflow: hidden;
}
.absen-bar {
    height: 100%; border-radius: 10px;
    transition: width 0.6s ease;
    min-width: 4px;
}
.bar-hadir { background: #22c55e; }
.bar-izin  { background: #f59e0b; }
.bar-alpha { background: #ef4444; }

.absen-pct-wrap {
    display: flex; flex-direction: column; align-items: center; gap: 6px;
    flex-shrink: 0;
}
.absen-pct-ring {
    position: relative; width: 80px; height: 80px;
    display: flex; align-items: center; justify-content: center;
}
.absen-pct-val {
    position: absolute;
    font-size: 1rem; font-weight: 800; color: var(--indigo);
}
.absen-pct-lbl {
    font-size: 0.67rem; color: var(--tx-3);
    text-align: center; margin: 0; line-height: 1.4;
}

/* ---- PIN Card ---- */
.pin-toggle {
    width: 100%; padding: 14px 18px;
    display: flex; align-items: center; justify-content: space-between;
    background: transparent; border: none; cursor: pointer;
    font-family: var(--font);
    transition: background var(--t);
}
.pin-toggle:hover { background: rgba(245,158,11,0.04); }
.pin-toggle-left { display: flex; align-items: center; gap: 12px; }
.pin-title { font-size: 0.85rem; font-weight: 700; color: var(--tx-1); margin: 0 0 2px; text-align: left; }
.pin-sub   { font-size: 0.72rem; color: var(--tx-3); margin: 0; text-align: left; }
.pin-chevron { color: var(--tx-3); flex-shrink: 0; transition: transform var(--t); }
.pin-chevron.rotated { transform: rotate(180deg); }

.pin-body { padding: 0 18px 18px; }
.pin-fields { display: grid; grid-template-columns: 1fr 1fr; gap: 13px; margin-bottom: 14px; }

/* Shared field styles */
.field-group { display: flex; flex-direction: column; gap: 5px; }
.field-label { font-size: 0.75rem; font-weight: 600; color: var(--tx-1); }
.req { color: #ef4444; }
.field-input {
    width: 100%; padding: 8px 12px;
    background: var(--bg-field); border: 1.5px solid var(--border-field);
    border-radius: var(--radius-s); font-size: 0.82rem; color: var(--tx-1);
    outline: none; font-family: var(--font);
    transition: border-color var(--t), box-shadow var(--t), background var(--t);
}
.field-input::placeholder { color: var(--tx-4); }
.field-input:focus {
    background: var(--bg-field-focus);
    border-color: var(--indigo);
    box-shadow: 0 0 0 3px rgba(99,102,241,0.13);
}
.field-input.is-error { border-color: #f87171; box-shadow: 0 0 0 3px rgba(248,113,113,0.12); }
.field-pin { letter-spacing: 0.3em; font-family: 'SF Mono','Fira Code',monospace; font-size: 1rem; text-align: center; }
.field-error { font-size: 0.71rem; color: #ef4444; margin: 0; }

.btn-reset-pin {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 18px;
    background: rgba(245,158,11,0.1);
    border: 1.5px solid rgba(245,158,11,0.25);
    color: #d97706;
    font-size: 0.8rem; font-weight: 700;
    border-radius: var(--radius-s); cursor: pointer;
    font-family: var(--font);
    transition: background var(--t), transform var(--t);
}
.btn-reset-pin:hover {
    background: rgba(245,158,11,0.18);
    transform: translateY(-1px);
}
@media (prefers-color-scheme: dark) {
    .btn-reset-pin { color: #fbbf24; background: rgba(245,158,11,0.12); }
}

/* ---- Responsive ---- */
@media (max-width: 640px) {
    .hero-body { flex-direction: column; align-items: flex-start; gap: 14px; }
    .hero-avatar { width: 64px; height: 64px; font-size: 1.1rem; }
    .hero-top { flex-direction: column; }
    .hero-actions { flex-direction: row; }
    .info-grid { grid-template-columns: 1fr; }
    .absen-body { flex-direction: column; align-items: flex-start; gap: 16px; }
    .absen-pct-wrap { flex-direction: row; align-items: center; gap: 12px; }
    .pin-fields { grid-template-columns: 1fr; }
}
</style>
@endpush
@endsection