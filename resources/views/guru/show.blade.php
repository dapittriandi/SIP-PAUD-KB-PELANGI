{{-- SIMPAN SEBAGAI: resources/views/guru/show.blade.php --}}
@extends('layouts.app')
@section('title', $guru->nama_lengkap . ' — PAUD KB Pelangi')
@section('page-title', 'Detail Guru')

@section('content')

@php
    $absenBulanIni = $guru->absensi->filter(fn($a) => $a->tanggal->month === now()->month && $a->tanggal->year === now()->year);
    $hadir  = $absenBulanIni->whereIn('status', ['hadir','terlambat'])->count();
    $izin   = $absenBulanIni->whereIn('status', ['izin','sakit','tugas_luar'])->count();
    $alpha  = $absenBulanIni->where('status', 'alpha')->count();
    $total  = $hadir + $izin + $alpha;
    $pct    = $total > 0 ? round($hadir / $total * 100) : 0;
    $circ   = round(2 * M_PI * 32, 2);
    $offset = round($circ * (1 - $pct / 100), 2);
@endphp

<div class="sv-wrap">

    {{-- ── Topbar: breadcrumb + actions ── --}}
    <div class="sv-topbar">
        <nav class="sv-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('guru.index') }}" class="sv-bc-link">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                Data Guru
            </a>
            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
            <span class="sv-bc-now" aria-current="page">{{ $guru->nama_lengkap }}</span>
        </nav>

        @if(Auth::user()->isAdmin())
        <div class="sv-topbar-actions">
            <a href="{{ route('guru.edit', $guru) }}" class="sv-btn-edit">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Edit
            </a>
            <form method="POST" action="{{ route('guru.toggle', $guru) }}">
                @csrf @method('PUT')
                <button type="submit" class="sv-btn-toggle {{ $guru->aktif ? 'is-deactivate' : 'is-activate' }}">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18.36 6.64a9 9 0 1 1-12.73 0"/><line x1="12" y1="2" x2="12" y2="12"/></svg>
                    {{ $guru->aktif ? 'Nonaktifkan' : 'Aktifkan' }}
                </button>
            </form>
        </div>
        @endif
    </div>

    {{-- ══════════════════════════════════════
         HERO CARD
    ══════════════════════════════════════════ --}}
    <div class="sv-hero sv-card">
        {{-- Top accent bar --}}
        <div class="sv-hero-bar {{ $guru->aktif ? 'bar-aktif' : 'bar-nonaktif' }}" aria-hidden="true"></div>

        <div class="sv-hero-body">

            {{-- Avatar --}}
            <div class="sv-avatar-wrap" aria-hidden="true">
                <div class="sv-avatar {{ $guru->aktif ? 'av-on' : 'av-off' }}">
                    @if($guru->foto)
                        <img src="{{ Storage::url($guru->foto) }}" alt="{{ $guru->nama_lengkap }}" class="sv-avatar-img">
                    @else
                        <span class="sv-avatar-init">{{ strtoupper(substr($guru->nama_lengkap, 0, 2)) }}</span>
                    @endif
                </div>
                <span class="sv-avatar-badge {{ $guru->aktif ? 'badge-on' : 'badge-off' }}"
                      :title="'{{ $guru->aktif ? 'Aktif' : 'Nonaktif' }}'"></span>
            </div>

            {{-- Hero info --}}
            <div class="sv-hero-info">
                <div class="sv-hero-name-row">
                    <div>
                        <h1 class="sv-name">{{ $guru->nama_lengkap }}</h1>
                        <p class="sv-username" aria-label="Username">&#64;{{ $guru->username }}</p>
                    </div>
                </div>

                {{-- Chips --}}
                <div class="sv-chips" role="list" aria-label="Status dan jabatan">
                    @if($guru->jabatan)
                    <span class="sv-chip sv-chip-jabatan" role="listitem">
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                        {{ $guru->jabatan }}
                    </span>
                    @endif
                    @if($guru->label_status_kepegawaian !== '-')
                    <span class="sv-chip sv-chip-kepeg" role="listitem">{{ $guru->label_status_kepegawaian }}</span>
                    @endif
                    @if($guru->aktif)
                    <span class="sv-chip sv-chip-aktif" role="listitem"><span class="sv-dot" aria-hidden="true"></span>Aktif</span>
                    @else
                    <span class="sv-chip sv-chip-off" role="listitem">Nonaktif</span>
                    @endif
                </div>

                {{-- Stat bar bulan ini --}}
                <div class="sv-stat-bar" role="group" aria-label="Ringkasan absensi {{ now()->translatedFormat('F Y') }}">
                    <div class="sv-stat-item">
                        <span class="sv-stat-num sv-num-hadir" aria-label="{{ $hadir }} hari hadir">{{ $hadir }}</span>
                        <span class="sv-stat-lbl">Hadir</span>
                    </div>
                    <div class="sv-stat-divider" aria-hidden="true"></div>
                    <div class="sv-stat-item">
                        <span class="sv-stat-num sv-num-izin" aria-label="{{ $izin }} hari izin atau sakit">{{ $izin }}</span>
                        <span class="sv-stat-lbl">Izin/Sakit</span>
                    </div>
                    <div class="sv-stat-divider" aria-hidden="true"></div>
                    <div class="sv-stat-item">
                        <span class="sv-stat-num sv-num-alpha" aria-label="{{ $alpha }} hari alpha">{{ $alpha }}</span>
                        <span class="sv-stat-lbl">Alpha</span>
                    </div>
                    <div class="sv-stat-divider" aria-hidden="true"></div>
                    <div class="sv-stat-item">
                        <span class="sv-stat-num sv-num-pct" aria-label="{{ $pct }} persen kehadiran">{{ $pct }}%</span>
                        <span class="sv-stat-lbl">Kehadiran</span>
                    </div>
                    <div class="sv-stat-month" aria-label="Periode">{{ now()->translatedFormat('M Y') }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════
         INFO GRID: Pribadi + Kepegawaian
    ══════════════════════════════════════════ --}}
    <div class="sv-grid-2">

        {{-- Data Pribadi --}}
        <div class="sv-card sv-info-card">
            <div class="sv-card-head">
                <div class="sv-card-icon" aria-hidden="true">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
                <h2 class="sv-card-title">Data Pribadi</h2>
            </div>
            <dl class="sv-rows">
                <div class="sv-row">
                    <dt class="sv-row-label">No. HP</dt>
                    <dd class="sv-row-val">
                        @if($guru->no_hp)
                        <a href="tel:{{ $guru->no_hp }}" class="sv-link sv-mono">{{ $guru->no_hp }}</a>
                        @else<span class="sv-empty">—</span>@endif
                    </dd>
                </div>
                <div class="sv-row">
                    <dt class="sv-row-label">Email</dt>
                    <dd class="sv-row-val">
                        @if($guru->email)
                        <a href="mailto:{{ $guru->email }}" class="sv-link">{{ $guru->email }}</a>
                        @else<span class="sv-empty">—</span>@endif
                    </dd>
                </div>
                <div class="sv-row">
                    <dt class="sv-row-label">Jenis Kelamin</dt>
                    <dd class="sv-row-val">
                        {{ $guru->jenis_kelamin === 'L' ? 'Laki-laki' : ($guru->jenis_kelamin === 'P' ? 'Perempuan' : '') }}
                        @if(!in_array($guru->jenis_kelamin, ['L','P']))<span class="sv-empty">—</span>@endif
                    </dd>
                </div>
                @if($guru->tempat_lahir || $guru->tanggal_lahir)
                <div class="sv-row">
                    <dt class="sv-row-label">Tempat / Tgl Lahir</dt>
                    <dd class="sv-row-val">
                        {{ collect([$guru->tempat_lahir, $guru->tanggal_lahir?->translatedFormat('d F Y')])->filter()->join(', ') ?: '—' }}
                    </dd>
                </div>
                @endif
                <div class="sv-row sv-row-last">
                    <dt class="sv-row-label">Alamat</dt>
                    <dd class="sv-row-val sv-row-multiline">{{ $guru->alamat ?? '' }}
                        @if(!$guru->alamat)<span class="sv-empty">—</span>@endif
                    </dd>
                </div>
            </dl>
        </div>

        {{-- Data Kepegawaian --}}
        <div class="sv-card sv-info-card">
            <div class="sv-card-head">
                <div class="sv-card-icon sv-card-icon-alt" aria-hidden="true">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                </div>
                <h2 class="sv-card-title">Data Kepegawaian</h2>
            </div>
            <dl class="sv-rows">
                <div class="sv-row">
                    <dt class="sv-row-label">NIP</dt>
                    <dd class="sv-row-val sv-mono">{{ $guru->nip ?? '' }}@if(!$guru->nip)<span class="sv-empty">—</span>@endif</dd>
                </div>
                <div class="sv-row">
                    <dt class="sv-row-label">NUPTK</dt>
                    <dd class="sv-row-val sv-mono">{{ $guru->nuptk ?? '' }}@if(!$guru->nuptk)<span class="sv-empty">—</span>@endif</dd>
                </div>
                <div class="sv-row">
                    <dt class="sv-row-label">Status</dt>
                    <dd class="sv-row-val">
                        @if($guru->label_status_kepegawaian !== '-')
                        <span class="sv-chip sv-chip-kepeg">{{ $guru->label_status_kepegawaian }}</span>
                        @else<span class="sv-empty">—</span>@endif
                    </dd>
                </div>
                <div class="sv-row">
                    <dt class="sv-row-label">Pendidikan</dt>
                    <dd class="sv-row-val">
                        {{ trim(($guru->pendidikan_terakhir ?? '') . ' ' . ($guru->jurusan ?? '')) ?: '' }}
                        @if(!trim(($guru->pendidikan_terakhir ?? '') . ($guru->jurusan ?? '')))<span class="sv-empty">—</span>@endif
                    </dd>
                </div>
                <div class="sv-row sv-row-last">
                    <dt class="sv-row-label">Bergabung</dt>
                    <dd class="sv-row-val">{{ $guru->tanggal_bergabung?->translatedFormat('d F Y') ?? '' }}
                        @if(!$guru->tanggal_bergabung)<span class="sv-empty">—</span>@endif
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    {{-- ══════════════════════════════════════
         REKAP ABSENSI
    ══════════════════════════════════════════ --}}
    <div class="sv-card sv-absen-card">
        <div class="sv-card-head">
            <div class="sv-card-icon sv-card-icon-cal" aria-hidden="true">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </div>
            <h2 class="sv-card-title">Rekap Absensi</h2>
            <span class="sv-card-badge" aria-label="Periode">{{ now()->translatedFormat('F Y') }}</span>
        </div>
        <div class="sv-absen-body">

            {{-- Bars --}}
            <div class="sv-absen-bars" role="group" aria-label="Detail absensi">
                <div class="sv-absen-row">
                    <div class="sv-absen-meta">
                        <span class="sv-absen-icon absen-icon-hadir" aria-hidden="true">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
                        </span>
                        <span class="sv-absen-lbl">Hadir</span>
                        <span class="sv-absen-num hadir-num" aria-label="{{ $hadir }} hari">{{ $hadir }}</span>
                    </div>
                    <div class="sv-bar-track" role="progressbar" aria-valuenow="{{ $total > 0 ? round($hadir/$total*100) : 0 }}" aria-valuemin="0" aria-valuemax="100">
                        <div class="sv-bar sv-bar-hadir" style="width:{{ $total > 0 ? round($hadir/$total*100) : 0 }}%"></div>
                    </div>
                </div>
                <div class="sv-absen-row">
                    <div class="sv-absen-meta">
                        <span class="sv-absen-icon absen-icon-izin" aria-hidden="true">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
                        </span>
                        <span class="sv-absen-lbl">Izin / Sakit</span>
                        <span class="sv-absen-num izin-num" aria-label="{{ $izin }} hari">{{ $izin }}</span>
                    </div>
                    <div class="sv-bar-track" role="progressbar" aria-valuenow="{{ $total > 0 ? round($izin/$total*100) : 0 }}" aria-valuemin="0" aria-valuemax="100">
                        <div class="sv-bar sv-bar-izin" style="width:{{ $total > 0 ? round($izin/$total*100) : 0 }}%"></div>
                    </div>
                </div>
                <div class="sv-absen-row">
                    <div class="sv-absen-meta">
                        <span class="sv-absen-icon absen-icon-alpha" aria-hidden="true">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6L6 18M6 6l12 12"/></svg>
                        </span>
                        <span class="sv-absen-lbl">Alpha</span>
                        <span class="sv-absen-num alpha-num" aria-label="{{ $alpha }} hari">{{ $alpha }}</span>
                    </div>
                    <div class="sv-bar-track" role="progressbar" aria-valuenow="{{ $total > 0 ? round($alpha/$total*100) : 0 }}" aria-valuemin="0" aria-valuemax="100">
                        <div class="sv-bar sv-bar-alpha" style="width:{{ $total > 0 ? round($alpha/$total*100) : 0 }}%"></div>
                    </div>
                </div>
            </div>

            {{-- Ring --}}
            <div class="sv-ring-wrap" aria-hidden="true">
                <svg class="sv-ring-svg" width="92" height="92" viewBox="0 0 80 80">
                    {{-- Track --}}
                    <circle cx="40" cy="40" r="32" fill="none"
                        stroke="oklch(52% 0.190 260 / 10%)"
                        stroke-width="7"/>
                    {{-- Fill --}}
                    <circle cx="40" cy="40" r="32" fill="none"
                        stroke="var(--sv-accent)"
                        stroke-width="7"
                        stroke-linecap="round"
                        stroke-dasharray="{{ $circ }}"
                        stroke-dashoffset="{{ $offset }}"
                        transform="rotate(-90 40 40)"
                        class="sv-ring-fill"/>
                    @if($pct >= 80)
                    {{-- Secondary arc for good attendance --}}
                    <circle cx="40" cy="40" r="26" fill="none"
                        stroke="oklch(52% 0.190 260 / 8%)"
                        stroke-width="3"/>
                    @endif
                </svg>
                <div class="sv-ring-center">
                    <span class="sv-ring-pct">{{ $pct }}%</span>
                    <span class="sv-ring-sub">kehadiran</span>
                </div>
                @if($pct >= 90)
                <div class="sv-ring-badge" title="Kehadiran sangat baik" aria-label="Kehadiran sangat baik">
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                </div>
                @endif
            </div>

        </div>
    </div>

    {{-- ══════════════════════════════════════
         RESET PIN (Admin only)
    ══════════════════════════════════════════ --}}
    @if(Auth::user()->isAdmin())
    <div class="sv-card sv-pin-card" x-data="{ open: false }">
        <button type="button"
                @click="open = !open"
                class="sv-pin-trigger"
                :aria-expanded="open.toString()"
                aria-controls="pin-panel">
            <div class="sv-pin-trigger-left">
                <div class="sv-card-icon sv-card-icon-pin" aria-hidden="true">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                </div>
                <div>
                    <p class="sv-pin-title">Reset PIN Login</p>
                    <p class="sv-pin-sub">Atur ulang PIN guru ini</p>
                </div>
            </div>
            <svg class="sv-pin-chevron" :class="{ 'rotated': open }"
                 width="15" height="15" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2.2"
                 stroke-linecap="round" stroke-linejoin="round"
                 aria-hidden="true">
                <path d="M6 9l6 6 6-6"/>
            </svg>
        </button>

        <div id="pin-panel" x-show="open" x-collapse class="sv-pin-panel">
            @if ($errors->hasAny(['pin_baru']))
            <div class="sv-pin-error" role="alert">
                @error('pin_baru'){{ $message }}@enderror
            </div>
            @endif
            <form method="POST" action="{{ route('guru.reset-pin', $guru) }}">
                @csrf
                <div class="sv-pin-fields">
                    <div class="sv-field">
                        <label class="sv-label" for="pin_baru">
                            PIN Baru <span class="sv-req" aria-label="wajib">*</span>
                        </label>
                        <input type="password" id="pin_baru" name="pin_baru"
                               placeholder="••••" maxlength="4" required
                               class="sv-input sv-input-pin @error('pin_baru') is-err @enderror"
                               inputmode="numeric" pattern="[0-9]*"
                               autocomplete="new-password">
                    </div>
                    <div class="sv-field">
                        <label class="sv-label" for="pin_baru_confirmation">
                            Konfirmasi PIN <span class="sv-req" aria-label="wajib">*</span>
                        </label>
                        <input type="password" id="pin_baru_confirmation" name="pin_baru_confirmation"
                               placeholder="••••" maxlength="4" required
                               class="sv-input sv-input-pin"
                               inputmode="numeric" pattern="[0-9]*"
                               autocomplete="new-password">
                    </div>
                </div>
                <button type="submit" class="sv-btn-reset-pin">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 2v6h-6"/><path d="M3 12a9 9 0 0 1 15-6.7L21 8"/><path d="M3 22v-6h6"/><path d="M21 12a9 9 0 0 1-15 6.7L3 16"/></svg>
                    Reset PIN
                </button>
            </form>
        </div>
    </div>
    @endif

</div>

@push('styles')
<style>
/* ============================================================
   GURU SHOW — Impeccable craft · SIP-Pelangi design system
   OKLCH tokens · Geist font · .dark Alpine class
   ============================================================ */

.sv-wrap {
    --sv-glass-bg:      oklch(99.5% 0.003 250 / 74%);
    --sv-glass-bg-2:    oklch(98%   0.005 250 / 62%);
    --sv-glass-border:  oklch(90%   0.007 250 / 65%);
    --sv-glass-border-a:oklch(52%   0.190 260 / 15%);
    --sv-glass-blur:    blur(22px) saturate(1.65);
    --sv-glass-shadow:  0 2px 16px oklch(52% 0.190 260 / 6%), 0 1px 3px oklch(0% 0 0 / 4%);
    --sv-glass-shadow-h:0 6px 28px oklch(52% 0.190 260 / 11%), 0 2px 6px oklch(0% 0 0 / 5%);

    /* Semantic color tokens (map to app.blade vars) */
    --sv-accent:        var(--accent);
    --sv-accent-ring:   var(--accent-ring);
    --sv-accent-soft:   var(--accent-soft);
    --sv-field-bg:      oklch(99%   0.002 250 / 82%);
    --sv-field-border:  oklch(87%   0.009 255);
    --sv-field-ring:    oklch(52%   0.190 260 / 13%);

    /* Named semantic colors (not hard-coded hex) */
    --sv-hadir:  oklch(50% 0.165 145);    /* green  */
    --sv-izin:   oklch(62% 0.170 60);     /* amber  */
    --sv-alpha:  oklch(50% 0.210 27);     /* red    */
    --sv-pin:    oklch(58% 0.155 60);     /* amber-ish for pin section */

    max-width: 760px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 16px;
    font-family: 'Geist', system-ui, sans-serif;
    font-size: var(--fs-base, 14px);
}

.dark .sv-wrap {
    --sv-glass-bg:        oklch(15.5% 0.012 255 / 82%);
    --sv-glass-bg-2:      oklch(13%   0.012 255 / 72%);
    --sv-glass-border:    oklch(28%   0.010 255 / 55%);
    --sv-glass-border-a:  oklch(63%   0.185 260 / 22%);
    --sv-glass-shadow:    0 4px 28px oklch(0% 0 0 / 42%), 0 1px 4px oklch(0% 0 0 / 28%);
    --sv-glass-shadow-h:  0 8px 36px oklch(0% 0 0 / 52%), 0 2px 8px oklch(0% 0 0 / 32%);
    --sv-field-bg:        oklch(18%   0.013 255 / 70%);
    --sv-field-border:    oklch(30%   0.010 255 / 70%);
    --sv-field-ring:      oklch(63%   0.185 260 / 17%);
}

/* ── Shared card ── */
.sv-card {
    background: var(--sv-glass-bg);
    border: 1px solid var(--sv-glass-border);
    border-radius: var(--r-lg, 14px);
    box-shadow: var(--sv-glass-shadow);
    backdrop-filter: var(--sv-glass-blur);
    -webkit-backdrop-filter: var(--sv-glass-blur);
    overflow: hidden;
    transition:
        box-shadow var(--dur-mid) var(--ease-out),
        border-color var(--dur-mid) var(--ease-out);
}

/* ── Topbar ── */
.sv-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
}
.sv-breadcrumb {
    display: flex; align-items: center; gap: 5px;
    font-size: var(--fs-xs, 11px); color: var(--text-3);
}
.sv-bc-link {
    display: inline-flex; align-items: center; gap: 4px;
    color: var(--accent); font-weight: 600;
    text-decoration: none;
    transition: opacity var(--dur-fast);
}
.sv-bc-link:hover { opacity: 0.72; text-decoration: underline; }
.sv-bc-now { font-weight: 700; color: var(--text-1); }

.sv-topbar-actions { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }

.sv-btn-edit {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 16px;
    background: var(--accent);
    color: var(--text-inv);
    font-size: var(--fs-sm, 13px); font-weight: 700;
    border-radius: var(--r, 10px);
    text-decoration: none;
    transition:
        background var(--dur-fast) var(--ease-out),
        transform  var(--dur-fast) var(--ease-out),
        box-shadow var(--dur-mid)  var(--ease-out);
    box-shadow: 0 2px 10px oklch(52% 0.190 260 / 28%);
    white-space: nowrap;
}
.sv-btn-edit:hover {
    background: var(--accent-h);
    transform: translateY(-1px);
    box-shadow: 0 5px 18px oklch(52% 0.190 260 / 40%);
}

.sv-btn-toggle {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 14px;
    font-size: var(--fs-sm, 13px); font-weight: 600;
    border-radius: var(--r, 10px);
    background: var(--sv-glass-bg);
    border: 1.5px solid var(--sv-glass-border);
    cursor: pointer; font-family: inherit;
    backdrop-filter: var(--sv-glass-blur);
    -webkit-backdrop-filter: var(--sv-glass-blur);
    transition:
        background var(--dur-fast) var(--ease-out),
        border-color var(--dur-fast) var(--ease-out),
        color var(--dur-fast) var(--ease-out);
    white-space: nowrap;
}
.sv-btn-toggle.is-deactivate {
    color: var(--sv-alpha);
}
.sv-btn-toggle.is-deactivate:hover {
    background: oklch(50% 0.210 27 / 7%);
    border-color: oklch(50% 0.210 27 / 22%);
}
.sv-btn-toggle.is-activate {
    color: var(--sv-hadir);
}
.sv-btn-toggle.is-activate:hover {
    background: oklch(50% 0.165 145 / 7%);
    border-color: oklch(50% 0.165 145 / 22%);
}

/* ── Hero card ── */
.sv-hero-bar {
    height: 3px;
}
.bar-aktif {
    background: linear-gradient(90deg,
        var(--accent) 0%,
        oklch(62% 0.175 255) 60%,
        oklch(72% 0.140 250) 100%);
}
.bar-nonaktif {
    background: linear-gradient(90deg,
        oklch(62% 0.010 255) 0%,
        oklch(75% 0.007 250) 100%);
}
.sv-hero-body {
    padding: 20px;
    display: flex;
    align-items: flex-start;
    gap: 18px;
}

/* Avatar */
.sv-avatar-wrap { position: relative; flex-shrink: 0; }
.sv-avatar {
    width: 80px; height: 80px;
    border-radius: var(--r-lg, 14px);
    display: flex; align-items: center; justify-content: center;
    overflow: hidden;
    box-shadow: 0 4px 16px oklch(0% 0 0 / 12%);
}
.av-on {
    background: linear-gradient(135deg,
        var(--accent) 0%,
        oklch(60% 0.175 255) 100%);
}
.av-off {
    background: linear-gradient(135deg,
        oklch(58% 0.010 255) 0%,
        oklch(72% 0.007 250) 100%);
}
.dark .av-off {
    background: linear-gradient(135deg,
        oklch(32% 0.010 255) 0%,
        oklch(42% 0.008 250) 100%);
}
.sv-avatar-img { width: 100%; height: 100%; object-fit: cover; }
.sv-avatar-init {
    font-size: var(--fs-lg, 18px);
    font-weight: 800;
    color: oklch(99% 0.003 250);
    letter-spacing: -0.02em;
}
.sv-avatar-badge {
    position: absolute;
    bottom: -2px; right: -2px;
    width: 14px; height: 14px;
    border-radius: 50%;
    border: 2.5px solid var(--sv-glass-bg);
}
.badge-on  { background: oklch(54% 0.178 145); box-shadow: 0 0 6px oklch(54% 0.178 145 / 50%); }
.badge-off { background: oklch(60% 0.012 255); }

/* Hero info */
.sv-hero-info { flex: 1; min-width: 0; }
.sv-hero-name-row {
    display: flex; align-items: flex-start;
    justify-content: space-between;
    gap: 12px; flex-wrap: wrap;
    margin-bottom: 10px;
}
.sv-name {
    font-size: var(--fs-xl, 20px);
    font-weight: 800;
    color: var(--text-1);
    letter-spacing: -0.03em;
    margin: 0 0 3px;
    line-height: 1.15;
}
.sv-username {
    font-size: var(--fs-xs, 11px);
    color: var(--text-3);
    font-family: 'Geist Mono', monospace;
    margin: 0;
    letter-spacing: -0.01em;
}

/* Chips */
.sv-chips {
    display: flex; flex-wrap: wrap; gap: 6px;
    margin-bottom: 14px;
}
.sv-chip {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 9px; font-weight: 700;
    letter-spacing: 0.03em;
    padding: 3px 10px; border-radius: 20px;
    white-space: nowrap;
}
.sv-chip-jabatan {
    color: var(--accent);
    background: var(--accent-soft);
    border: 1px solid var(--accent-ring);
}
.dark .sv-chip-jabatan {
    background: var(--accent-muted);
    border-color: var(--accent-ring);
}
.sv-chip-kepeg {
    color: var(--text-2);
    background: oklch(52% 0.190 260 / 7%);
    border: 1px solid oklch(52% 0.190 260 / 16%);
    border-radius: var(--r-sm, 6px);
}
.dark .sv-chip-kepeg {
    background: oklch(63% 0.185 260 / 10%);
    border-color: oklch(63% 0.185 260 / 22%);
}
.sv-chip-aktif {
    color: var(--accent);
    background: var(--accent-soft);
    border: 1px solid var(--accent-ring);
}
.dark .sv-chip-aktif {
    background: var(--accent-muted);
    border-color: var(--accent-ring);
}
.sv-chip-off {
    color: var(--text-2);
    background: oklch(68% 0.008 255 / 10%);
    border: 1px solid oklch(68% 0.008 255 / 20%);
}
.dark .sv-chip-off {
    background: oklch(40% 0.010 255 / 20%);
    border-color: oklch(40% 0.010 255 / 35%);
    color: var(--text-3);
}
.sv-dot {
    width: 5px; height: 5px; border-radius: 50%;
    background: var(--accent);
    animation: sv-pulse 2.4s ease-in-out infinite;
}
@keyframes sv-pulse { 0%,100%{ opacity:1; transform:scale(1); } 50%{ opacity:.35; transform:scale(.8); } }

/* Stat bar */
.sv-stat-bar {
    display: flex;
    align-items: center;
    gap: 0;
    background: oklch(52% 0.190 260 / 4%);
    border: 1px solid var(--sv-glass-border-a);
    border-radius: var(--r, 10px);
    padding: 10px 4px;
    flex-wrap: wrap;
    position: relative;
}
.dark .sv-stat-bar {
    background: oklch(63% 0.185 260 / 6%);
}
.sv-stat-item {
    flex: 1; min-width: 56px;
    display: flex; flex-direction: column;
    align-items: center; gap: 2px;
    padding: 2px 8px;
}
.sv-stat-num {
    font-size: var(--fs-lg, 18px);
    font-weight: 800;
    line-height: 1;
    letter-spacing: -0.03em;
}
.sv-num-hadir { color: var(--sv-hadir); }
.sv-num-izin  { color: var(--sv-izin); }
.sv-num-alpha { color: var(--sv-alpha); }
.sv-num-pct   { color: var(--accent); }
.sv-stat-lbl {
    font-size: 9px; font-weight: 600;
    color: var(--text-3);
    text-transform: uppercase; letter-spacing: 0.04em;
}
.sv-stat-divider {
    width: 1px; height: 30px;
    background: var(--sv-glass-border);
    flex-shrink: 0;
}
.sv-stat-month {
    flex-basis: 100%;
    text-align: right;
    font-size: 9px; color: var(--text-3);
    padding: 4px 12px 0;
    font-weight: 500;
    letter-spacing: 0.02em;
}

/* ── Info grid ── */
.sv-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}
.sv-info-card {}

.sv-card-head {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 18px;
    border-bottom: 1px solid var(--sv-glass-border);
    background: oklch(52% 0.190 260 / 3.5%);
}
.dark .sv-card-head {
    background: oklch(63% 0.185 260 / 5%);
}
.sv-card-icon {
    width: 28px; height: 28px;
    border-radius: 8px;
    background: oklch(52% 0.190 260 / 10%);
    border: 1px solid oklch(52% 0.190 260 / 18%);
    color: var(--accent);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.sv-card-icon-alt {
    background: oklch(56% 0.160 285 / 10%);
    border-color: oklch(56% 0.160 285 / 18%);
    color: oklch(56% 0.160 285);
}
.sv-card-icon-cal {
    background: oklch(52% 0.190 260 / 10%);
    border-color: oklch(52% 0.190 260 / 18%);
    color: var(--accent);
}
.sv-card-icon-pin {
    background: oklch(58% 0.155 60 / 10%);
    border-color: oklch(58% 0.155 60 / 20%);
    color: var(--sv-pin);
}
.dark .sv-card-icon-alt {
    background: oklch(63% 0.160 285 / 12%);
    border-color: oklch(63% 0.160 285 / 25%);
    color: oklch(70% 0.140 285);
}
.dark .sv-card-icon-pin {
    background: oklch(62% 0.150 60 / 12%);
    border-color: oklch(62% 0.150 60 / 25%);
    color: oklch(68% 0.155 60);
}

.sv-card-title {
    font-size: var(--fs-sm, 13px);
    font-weight: 700;
    color: var(--text-1);
    letter-spacing: -0.02em;
    margin: 0;
}
.sv-card-badge {
    margin-left: auto;
    font-size: 9px; font-weight: 700;
    letter-spacing: 0.04em;
    color: var(--text-3);
    background: oklch(52% 0.190 260 / 6%);
    border: 1px solid oklch(52% 0.190 260 / 14%);
    padding: 2px 9px; border-radius: 20px;
    white-space: nowrap;
}

/* ── Info rows (dl) ── */
.sv-rows { margin: 0; padding: 0 18px; }
.sv-row {
    display: flex;
    align-items: baseline;
    gap: 10px;
    padding: 9px 0;
    border-bottom: 1px solid oklch(90% 0.007 250 / 40%);
}
.dark .sv-row { border-bottom-color: oklch(28% 0.010 255 / 35%); }
.sv-row-last { border-bottom: none; }
.sv-row-label {
    min-width: 96px;
    flex-shrink: 0;
    font-size: 9px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--text-3);
    padding-top: 1px;
}
.sv-row-val {
    flex: 1;
    font-size: var(--fs-sm, 13px);
    font-weight: 500;
    color: var(--text-1);
    word-break: break-word;
    margin: 0;
}
.sv-row-multiline { line-height: 1.6; }
.sv-mono {
    font-family: 'Geist Mono', 'SF Mono', monospace;
    font-size: var(--fs-xs, 11px);
    letter-spacing: 0.04em;
}
.sv-link {
    color: var(--accent);
    text-decoration: none;
    font-weight: 600;
    transition: opacity var(--dur-fast);
}
.sv-link:hover { opacity: 0.72; text-decoration: underline; }
.sv-empty { color: var(--text-3); }

/* ── Absensi card ── */
.sv-absen-body {
    padding: 18px;
    display: flex;
    gap: 24px;
    align-items: center;
}
.sv-absen-bars {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 14px;
}
.sv-absen-row { display: flex; flex-direction: column; gap: 6px; }
.sv-absen-meta {
    display: flex;
    align-items: center;
    gap: 7px;
}
.sv-absen-icon {
    width: 20px; height: 20px;
    border-radius: 6px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.absen-icon-hadir {
    background: oklch(50% 0.165 145 / 12%);
    color: var(--sv-hadir);
    border: 1px solid oklch(50% 0.165 145 / 20%);
}
.absen-icon-izin {
    background: oklch(62% 0.170 60 / 12%);
    color: var(--sv-izin);
    border: 1px solid oklch(62% 0.170 60 / 20%);
}
.absen-icon-alpha {
    background: oklch(50% 0.210 27 / 10%);
    color: var(--sv-alpha);
    border: 1px solid oklch(50% 0.210 27 / 18%);
}
.sv-absen-lbl {
    font-size: var(--fs-xs, 11px);
    font-weight: 600;
    color: var(--text-2);
    flex: 1;
}
.sv-absen-num {
    font-size: var(--fs-md, 15px);
    font-weight: 800;
    letter-spacing: -0.03em;
    line-height: 1;
    min-width: 24px;
    text-align: right;
}
.hadir-num { color: var(--sv-hadir); }
.izin-num  { color: var(--sv-izin); }
.alpha-num { color: var(--sv-alpha); }

.sv-bar-track {
    height: 6px;
    border-radius: 10px;
    background: oklch(52% 0.190 260 / 7%);
    overflow: hidden;
}
.dark .sv-bar-track { background: oklch(63% 0.185 260 / 10%); }
.sv-bar {
    height: 100%;
    border-radius: 10px;
    min-width: 4px;
    transition: width 0.65s cubic-bezier(.22,.61,.36,1);
}
.sv-bar-hadir { background: oklch(54% 0.178 145); }
.sv-bar-izin  { background: oklch(65% 0.175 60); }
.sv-bar-alpha { background: oklch(52% 0.210 27); }

/* Ring */
.sv-ring-wrap {
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    position: relative;
}
.sv-ring-svg { display: block; }
.sv-ring-fill {
    transition: stroke-dashoffset 0.85s cubic-bezier(.22,.61,.36,1);
}
.sv-ring-center {
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%, -54%);
    display: flex; flex-direction: column;
    align-items: center; gap: 1px;
}
.sv-ring-pct {
    font-size: var(--fs-md, 15px);
    font-weight: 800;
    color: var(--accent);
    line-height: 1;
    letter-spacing: -0.04em;
}
.sv-ring-sub {
    font-size: 8px;
    font-weight: 600;
    color: var(--text-3);
    letter-spacing: 0.03em;
    text-transform: uppercase;
}
.sv-ring-badge {
    position: absolute;
    top: -4px; right: -4px;
    width: 20px; height: 20px;
    border-radius: 50%;
    background: oklch(62% 0.170 60 / 15%);
    border: 1.5px solid oklch(62% 0.170 60 / 30%);
    color: oklch(60% 0.168 60);
    display: flex; align-items: center; justify-content: center;
}

/* ── PIN card ── */
.sv-pin-trigger {
    width: 100%;
    display: flex; align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 14px 18px;
    background: transparent; border: none;
    cursor: pointer;
    font-family: inherit;
    transition: background var(--dur-fast) var(--ease-out);
}
.sv-pin-trigger:hover {
    background: oklch(58% 0.155 60 / 4%);
}
.sv-pin-trigger-left {
    display: flex; align-items: center; gap: 12px;
}
.sv-pin-title {
    font-size: var(--fs-sm, 13px);
    font-weight: 700; color: var(--text-1);
    margin: 0 0 2px; text-align: left;
}
.sv-pin-sub {
    font-size: 9px; color: var(--text-3);
    margin: 0; text-align: left;
}
.sv-pin-chevron {
    color: var(--text-3);
    flex-shrink: 0;
    transition: transform var(--dur-mid) var(--ease-out);
}
.sv-pin-chevron.rotated { transform: rotate(180deg); }

.sv-pin-panel { padding: 0 18px 18px; }
.sv-pin-error {
    font-size: var(--fs-xs, 11px);
    color: var(--danger);
    font-weight: 600;
    margin-bottom: 12px;
    padding: 8px 12px;
    background: oklch(50% 0.210 27 / 6%);
    border: 1px solid oklch(50% 0.210 27 / 18%);
    border-radius: var(--r, 10px);
}
.sv-pin-fields {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-bottom: 14px;
}
.sv-field { display: flex; flex-direction: column; gap: 5px; }
.sv-label {
    font-size: 9px; font-weight: 700;
    color: var(--text-2);
    text-transform: uppercase; letter-spacing: 0.05em;
}
.sv-req { color: var(--danger); font-weight: 800; }
.sv-input {
    width: 100%;
    padding: 9px 12px;
    background: var(--sv-field-bg);
    border: 1.5px solid var(--sv-field-border);
    border-radius: var(--r, 10px);
    font-size: var(--fs-sm, 13px);
    color: var(--text-1);
    font-family: inherit;
    outline: none;
    -webkit-appearance: none;
    transition:
        border-color var(--dur-fast) var(--ease-out),
        box-shadow   var(--dur-mid)  var(--ease-out);
}
.sv-input::placeholder { color: var(--text-3); }
.sv-input:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px var(--sv-field-ring);
}
.sv-input.is-err {
    border-color: var(--danger);
    background: oklch(50% 0.210 27 / 4%);
}
.sv-input-pin {
    font-family: 'Geist Mono', monospace;
    font-size: 20px;
    letter-spacing: 0.3em;
    text-align: center;
    font-weight: 700;
}
.sv-btn-reset-pin {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 8px 18px;
    background: oklch(58% 0.155 60 / 10%);
    border: 1.5px solid oklch(58% 0.155 60 / 24%);
    color: var(--sv-pin);
    font-size: var(--fs-sm, 13px); font-weight: 700;
    border-radius: var(--r, 10px);
    cursor: pointer; font-family: inherit;
    transition:
        background var(--dur-fast) var(--ease-out),
        transform  var(--dur-fast) var(--ease-out),
        box-shadow var(--dur-mid)  var(--ease-out);
}
.sv-btn-reset-pin:hover {
    background: oklch(58% 0.155 60 / 18%);
    border-color: oklch(58% 0.155 60 / 36%);
    transform: translateY(-1px);
    box-shadow: 0 4px 14px oklch(58% 0.155 60 / 22%);
}
.dark .sv-btn-reset-pin {
    color: oklch(68% 0.155 60);
    background: oklch(62% 0.150 60 / 12%);
    border-color: oklch(62% 0.150 60 / 28%);
}

/* ── Responsive ── */
@media (max-width: 640px) {
    .sv-hero-body    { flex-direction: column; align-items: flex-start; gap: 14px; }
    .sv-avatar       { width: 64px; height: 64px; }
    .sv-name         { font-size: var(--fs-md, 15px); }
    .sv-grid-2       { grid-template-columns: 1fr; }
    .sv-absen-body   { flex-direction: column; align-items: flex-start; gap: 18px; }
    .sv-ring-wrap    { flex-direction: row; align-items: center; gap: 14px; }
    .sv-pin-fields   { grid-template-columns: 1fr; }
    .sv-topbar       { flex-direction: column; align-items: flex-start; }
    .sv-topbar-actions { width: 100%; }
    .sv-btn-edit,
    .sv-btn-toggle   { flex: 1; justify-content: center; }
}

/* ── Reduced motion ── */
@media (prefers-reduced-motion: reduce) {
    .sv-dot          { animation: none; }
    .sv-bar,
    .sv-ring-fill    { transition: none; }
}
</style>
@endpush

@push('scripts')
<script>
// PIN: hanya angka
document.querySelectorAll('.sv-input-pin').forEach(el => {
    el.addEventListener('input', () => {
        el.value = el.value.replace(/\D/g, '').slice(0, 4);
    });
});
</script>
@endpush
@endsection