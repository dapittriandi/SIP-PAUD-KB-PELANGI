@extends('layouts.app')

@section('title', $siswa->nama_lengkap . ' — PAUD KB Pelangi')
@section('page-title', 'Detail Siswa')

@push('styles')
<style>
    /* ══════════════════════════════════════════════════
       SHOW SISWA — polished v3
       Semua warna dari token OKLCH di app.blade.php.
       Glass di hero-card + modal overlay — purposeful.
    ══════════════════════════════════════════════════ */

    /* ── Breadcrumb ── */
    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: var(--fs-xs);
        color: var(--text-3);
    }
    .breadcrumb a {
        color: var(--text-3);
        text-decoration: none;
        transition: color var(--dur-fast) ease;
    }
    .breadcrumb a:hover { color: var(--accent); }
    .breadcrumb svg { width: 12px; height: 12px; opacity: .5; flex-shrink: 0; }
    .breadcrumb-current {
        color: var(--text-2);
        font-weight: 500;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 200px;
    }

    /* ── Banner nonaktif ── */
    .banner-nonaktif {
        background: var(--danger-bg);
        border: 1px solid var(--danger-border);
        border-radius: var(--r-lg);
        padding: 14px 16px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }
    .banner-nonaktif svg { color: var(--danger); width: 18px; height: 18px; flex-shrink: 0; margin-top: 1px; }
    .banner-nonaktif .bn-title { font-size: var(--fs-sm); font-weight: 600; color: var(--danger); margin-bottom: 3px; }
    .banner-nonaktif .bn-detail { font-size: var(--fs-xs); color: color-mix(in oklch, var(--danger), var(--text-2) 30%); }
    .banner-nonaktif .bn-detail span { font-weight: 500; }

    .btn-aktifkan {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 13px;
        background: var(--surface);
        border: 1px solid var(--danger-border);
        color: var(--danger);
        font-size: var(--fs-xs);
        font-weight: 600;
        font-family: 'Geist', sans-serif;
        border-radius: var(--r-sm);
        cursor: pointer;
        transition: background var(--dur-fast) ease, border-color var(--dur-fast) ease;
        flex-shrink: 0;
        white-space: nowrap;
    }
    .btn-aktifkan:hover { background: var(--danger-bg); border-color: var(--danger); }
    .btn-aktifkan svg { width: 13px; height: 13px; }

    /* ── Hero card (glass) ── */
    .hero-card {
        background: color-mix(in oklch, var(--surface), transparent 15%);
        backdrop-filter: blur(20px) saturate(1.4);
        -webkit-backdrop-filter: blur(20px) saturate(1.4);
        border: 1px solid color-mix(in oklch, var(--border), transparent 15%);
        border-radius: var(--r-xl);
        padding: 22px 24px;
        display: flex;
        align-items: center;
        gap: 20px;
        box-shadow:
            var(--shadow-md),
            inset 0 1px 0 color-mix(in oklch, white, transparent 78%);
        position: relative;
        overflow: hidden;
    }
    .dark .hero-card {
        box-shadow:
            var(--shadow-md),
            inset 0 1px 0 color-mix(in oklch, white, transparent 92%);
    }
    /* Subtle accent wash on hero */
    .hero-card::before {
        content: '';
        position: absolute;
        top: -40%;
        right: -10%;
        width: 280px;
        height: 280px;
        border-radius: 50%;
        background: radial-gradient(circle, color-mix(in oklch, var(--accent), transparent 88%) 0%, transparent 70%);
        pointer-events: none;
    }

    .hero-avatar {
        width: 80px;
        height: 80px;
        border-radius: var(--r-lg);
        overflow: hidden;
        flex-shrink: 0;
        background: var(--accent-muted);
        border: 1px solid var(--accent-ring);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        font-weight: 700;
        color: var(--accent);
        box-shadow: 0 2px 8px color-mix(in oklch, var(--accent), transparent 72%);
        position: relative;
        z-index: 1;
    }
    .hero-avatar img { width: 100%; height: 100%; object-fit: cover; }

    .hero-info { flex: 1; min-width: 0; position: relative; z-index: 1; }
    .hero-name {
        font-size: var(--fs-lg);
        font-weight: 700;
        color: var(--text-1);
        letter-spacing: -0.02em;
        line-height: 1.2;
        margin-bottom: 4px;
    }
    .hero-meta {
        font-size: var(--fs-xs);
        color: var(--text-3);
        margin-bottom: 10px;
        font-family: 'Geist Mono', monospace;
    }
    .hero-pills { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }

    /* ── Pills (shared) ── */
    .pill {
        display: inline-flex; align-items: center;
        padding: 3px 10px;
        border-radius: 9999px;
        font-size: var(--fs-xs);
        font-weight: 500;
        white-space: nowrap;
    }
    .pill-blue  { background: var(--accent-muted); color: var(--accent); border: 1px solid var(--accent-ring); }
    .pill-green { background: var(--success-bg); color: var(--success); border: 1px solid var(--success-border); }
    .pill-red   { background: var(--danger-bg); color: var(--danger); border: 1px solid var(--danger-border); }

    /* ── Hero action buttons ── */
    .hero-actions { display: flex; align-items: center; gap: 8px; flex-shrink: 0; flex-wrap: wrap; position: relative; z-index: 1; }

    .btn-edit {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 16px;
        background: var(--accent);
        color: var(--text-inv);
        font-size: var(--fs-sm);
        font-weight: 500;
        font-family: 'Geist', sans-serif;
        border-radius: var(--r);
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: background var(--dur-fast) ease, transform var(--dur-micro) ease;
        box-shadow: 0 1px 3px color-mix(in oklch, var(--accent), transparent 55%);
    }
    .btn-edit:hover { background: var(--accent-h); color: var(--text-inv); }
    .btn-edit:active { transform: scale(0.97); }
    .btn-edit svg { width: 14px; height: 14px; }

    .btn-keluarkan {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 16px;
        background: var(--surface);
        border: 1px solid var(--danger-border);
        color: var(--danger);
        font-size: var(--fs-sm);
        font-weight: 500;
        font-family: 'Geist', sans-serif;
        border-radius: var(--r);
        cursor: pointer;
        transition: background var(--dur-fast) ease, border-color var(--dur-fast) ease;
    }
    .btn-keluarkan:hover { background: var(--danger-bg); border-color: var(--danger); }
    .btn-keluarkan svg { width: 14px; height: 14px; }

    /* ── Detail card (panel info) ── */
    .detail-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--r-lg);
        overflow: hidden;
        box-shadow: var(--shadow-xs);
    }
    .detail-card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 18px;
        border-bottom: 1px solid var(--border);
    }
    .detail-card-head h3 {
        font-size: var(--fs-sm);
        font-weight: 600;
        color: var(--text-1);
        letter-spacing: -0.01em;
    }
    .detail-card-head a {
        font-size: var(--fs-xs);
        color: var(--accent);
        font-weight: 500;
        text-decoration: none;
        transition: opacity var(--dur-fast) ease;
    }
    .detail-card-head a:hover { opacity: .7; }
    .detail-card-body { padding: 18px; }

    /* ── Row item ── */
    .row-item {
        display: flex;
        gap: 12px;
        padding: 7px 0;
        border-bottom: 1px solid var(--border);
    }
    .row-item:last-child { border-bottom: none; padding-bottom: 0; }
    .row-item:first-child { padding-top: 0; }
    .row-label {
        font-size: var(--fs-xs);
        color: var(--text-3);
        width: 140px;
        flex-shrink: 0;
        padding-top: 1px;
        font-family: 'Geist Mono', monospace;
    }
    .row-value {
        font-size: var(--fs-sm);
        color: var(--text-1);
        font-weight: 500;
        line-height: 1.4;
    }
    .row-value.muted { color: var(--text-3); font-weight: 400; }

    /* ── Row item — keluar (merah) ── */
    .row-item-danger .row-label { color: var(--danger); }
    .row-item-danger .row-value { color: color-mix(in oklch, var(--danger), var(--text-1) 20%); }

    /* ── Parent sub-group ── */
    .parent-group { padding: 14px 0; border-bottom: 1px solid var(--border); }
    .parent-group:last-child { border-bottom: none; padding-bottom: 0; }
    .parent-group:first-child { padding-top: 0; }
    .parent-role {
        font-size: var(--fs-2xs);
        font-weight: 600;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: var(--text-3);
        font-family: 'Geist Mono', monospace;
        margin-bottom: 6px;
    }
    .parent-name {
        font-size: var(--fs-sm);
        font-weight: 600;
        color: var(--text-1);
        margin-bottom: 2px;
    }
    .parent-job {
        font-size: var(--fs-xs);
        color: var(--text-3);
        margin-bottom: 4px;
    }
    .parent-phone {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: var(--fs-xs);
        font-family: 'Geist Mono', monospace;
        color: var(--accent);
        font-weight: 500;
    }
    .parent-phone svg { width: 11px; height: 11px; }

    /* ── SPP table ── */
    .spp-table { width: 100%; border-collapse: collapse; }
    .spp-table thead tr { border-bottom: 1px solid var(--border); background: var(--bg); }
    .spp-table th {
        text-align: left;
        padding: 10px 18px;
        font-size: var(--fs-2xs);
        font-weight: 600;
        color: var(--text-3);
        letter-spacing: .08em;
        text-transform: uppercase;
        font-family: 'Geist Mono', monospace;
        white-space: nowrap;
    }
    .spp-table th.right { text-align: right; }
    .spp-table td {
        padding: 11px 18px;
        font-size: var(--fs-sm);
        color: var(--text-2);
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
    }
    .spp-table tbody tr:last-child td { border-bottom: none; }
    .spp-table tbody tr { transition: background var(--dur-micro) ease; }
    .spp-table tbody tr:hover td { background: var(--accent-soft); }
    .spp-table .mono { font-family: 'Geist Mono', monospace; font-size: var(--fs-xs); color: var(--text-3); }
    .spp-table .amount { text-align: right; font-weight: 600; color: var(--text-1); font-variant-numeric: tabular-nums; }
    .spp-table .period { font-weight: 500; color: var(--text-1); }

    .spp-empty {
        padding: 40px 20px;
        text-align: center;
        font-size: var(--fs-sm);
        color: var(--text-3);
    }

    /* ══════════════════════════════════════════════
       MODAL — KELUARKAN SISWA (glass overlay)
    ══════════════════════════════════════════════ */
    .modal-backdrop {
        position: fixed;
        inset: 0;
        z-index: 50;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
        background: oklch(0% 0 0 / 42%);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        animation: backdropIn var(--dur-mid) var(--ease-out);
    }
    @keyframes backdropIn {
        from { opacity: 0; }
        to   { opacity: 1; }
    }

    .modal-box {
        background: color-mix(in oklch, var(--surface), transparent 6%);
        backdrop-filter: blur(24px) saturate(1.5);
        -webkit-backdrop-filter: blur(24px) saturate(1.5);
        border: 1px solid color-mix(in oklch, var(--border), transparent 10%);
        border-radius: var(--r-xl);
        box-shadow:
            var(--shadow-lg),
            inset 0 1px 0 color-mix(in oklch, white, transparent 80%);
        width: 100%;
        max-width: 420px;
        overflow: hidden;
        animation: modalIn var(--dur-mid) var(--ease-out);
    }
    @keyframes modalIn {
        from { opacity: 0; transform: translateY(12px) scale(0.97); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }
    .dark .modal-box {
        box-shadow:
            var(--shadow-lg),
            inset 0 1px 0 color-mix(in oklch, white, transparent 93%);
    }

    .modal-head {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 18px 20px;
        border-bottom: 1px solid var(--border);
    }
    .modal-icon {
        width: 36px; height: 36px;
        border-radius: var(--r-sm);
        background: var(--danger-bg);
        border: 1px solid var(--danger-border);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .modal-icon svg { width: 16px; height: 16px; color: var(--danger); }
    .modal-head-title { font-size: var(--fs-sm); font-weight: 600; color: var(--text-1); line-height: 1.2; }
    .modal-head-sub { font-size: var(--fs-xs); color: var(--text-3); margin-top: 1px; }

    .modal-close {
        margin-left: auto;
        width: 30px; height: 30px;
        border-radius: var(--r-sm);
        border: 1px solid var(--border);
        background: transparent;
        color: var(--text-3);
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: background var(--dur-fast) ease, color var(--dur-fast) ease;
        flex-shrink: 0;
    }
    .modal-close:hover { background: var(--bg-2); color: var(--text-1); }
    .modal-close svg { width: 14px; height: 14px; }

    .modal-body { padding: 20px; }
    .modal-body .field { margin-bottom: 16px; }
    .modal-body .field:last-child { margin-bottom: 0; }
    .field-label {
        display: block;
        font-size: var(--fs-xs);
        font-weight: 500;
        color: var(--text-2);
        margin-bottom: 6px;
        font-family: 'Geist', sans-serif;
    }
    .field-label .req { color: var(--danger); margin-left: 2px; }

    .field-select, .field-input {
        width: 100%;
        padding: 9px 12px;
        background: var(--surface-2);
        border: 1px solid var(--border);
        border-radius: var(--r-sm);
        font-size: var(--fs-sm);
        font-family: 'Geist', sans-serif;
        color: var(--text-1);
        outline: none;
        transition: border-color var(--dur-fast) ease, box-shadow var(--dur-fast) ease;
    }
    .field-select:focus, .field-input:focus {
        border-color: var(--accent-ring);
        box-shadow: 0 0 0 3px color-mix(in oklch, var(--accent), transparent 84%);
    }

    .notice-box {
        background: var(--warning-bg);
        border: 1px solid color-mix(in oklch, var(--warning), transparent 60%);
        border-radius: var(--r-sm);
        padding: 11px 14px;
        font-size: var(--fs-xs);
        color: var(--text-2);
        display: flex;
        gap: 8px;
        align-items: flex-start;
        margin-top: 4px;
    }
    .notice-box svg { width: 13px; height: 13px; color: var(--warning); flex-shrink: 0; margin-top: 1px; }

    .modal-foot {
        padding: 0 20px 18px;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 8px;
    }
    .btn-cancel {
        padding: 8px 16px;
        font-size: var(--fs-sm);
        font-weight: 500;
        font-family: 'Geist', sans-serif;
        color: var(--text-2);
        border: 1px solid var(--border);
        border-radius: var(--r-sm);
        background: transparent;
        cursor: pointer;
        transition: background var(--dur-fast) ease;
    }
    .btn-cancel:hover { background: var(--bg-2); }
    .btn-submit-danger {
        padding: 8px 16px;
        font-size: var(--fs-sm);
        font-weight: 600;
        font-family: 'Geist', sans-serif;
        color: var(--text-inv);
        background: var(--danger);
        border: none;
        border-radius: var(--r-sm);
        cursor: pointer;
        transition: filter var(--dur-fast) ease, transform var(--dur-micro) ease;
        box-shadow: 0 1px 3px color-mix(in oklch, var(--danger), transparent 55%);
    }
    .btn-submit-danger:hover { filter: brightness(1.08); }
    .btn-submit-danger:active { transform: scale(0.97); }

    /* ── Stagger masuk ── */
    @media (prefers-reduced-motion: no-preference) {
        .anim-up {
            animation: fadeUp var(--dur-page) var(--ease-out) both;
        }
        .anim-up:nth-child(1) { animation-delay: 0ms; }
        .anim-up:nth-child(2) { animation-delay: 50ms; }
        .anim-up:nth-child(3) { animation-delay: 100ms; }
        .anim-up:nth-child(4) { animation-delay: 150ms; }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    }
</style>
@endpush

@section('content')
<div class="max-w-3xl mx-auto space-y-4">

    {{-- Breadcrumb --}}
    <div class="breadcrumb anim-up">
        <a href="{{ route('siswa.index') }}">Data Siswa</a>
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="breadcrumb-current">{{ $siswa->nama_lengkap }}</span>
    </div>

    {{-- Banner nonaktif --}}
    @if(!$siswa->aktif)
    <div class="banner-nonaktif anim-up">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
        </svg>
        <div class="flex-1 min-w-0">
            <p class="bn-title">Siswa Tidak Aktif</p>
            <div class="bn-detail" style="display:flex;flex-direction:column;gap:2px;margin-top:2px">
                @if($siswa->tanggal_keluar)
                    <span>Tanggal keluar: <span>{{ $siswa->tanggal_keluar->translatedFormat('d F Y') }}</span></span>
                @endif
                @if($siswa->keterangan_keluar)
                    <span>Keterangan: <span>{{ $siswa->keterangan_keluar }}</span></span>
                @endif
            </div>
        </div>
        @if(Auth::user()->isAdmin())
        <form method="POST" action="{{ route('siswa.aktifkan', $siswa) }}">
            @csrf
            <button type="submit" class="btn-aktifkan"
                    onclick="return confirm('Aktifkan kembali siswa {{ addslashes($siswa->nama_lengkap) }}?')">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Aktifkan Kembali
            </button>
        </form>
        @endif
    </div>
    @endif

    {{-- Hero card --}}
    <div class="hero-card anim-up">
        <div class="hero-avatar">
            @if($siswa->foto)
                <img src="{{ Storage::url($siswa->foto) }}" alt="{{ $siswa->nama_lengkap }}">
            @else
                {{ strtoupper(substr($siswa->nama_lengkap, 0, 1)) }}
            @endif
        </div>
        <div class="hero-info">
            <div class="hero-name">{{ $siswa->nama_lengkap }}</div>
            <div class="hero-meta">
                {{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                @if($siswa->umur) · {{ $siswa->umur }} @endif
            </div>
            <div class="hero-pills">
                <span class="pill pill-blue">{{ $siswa->kelompok }}</span>
                @if($siswa->aktif)
                    <span class="pill pill-green">Aktif</span>
                @else
                    <span class="pill pill-red">Nonaktif</span>
                @endif
            </div>
        </div>

        @if(Auth::user()->isAdmin())
        <div class="hero-actions">
            <a href="{{ route('siswa.edit', $siswa) }}" class="btn-edit">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </a>
            @if($siswa->aktif)
            <button type="button" class="btn-keluarkan"
                    onclick="document.getElementById('modalKeluarkan').classList.remove('hidden')">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Keluarkan
            </button>
            @endif
        </div>
        @endif
    </div>

    {{-- Grid detail --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        {{-- Identitas --}}
        <div class="detail-card anim-up">
            <div class="detail-card-head">
                <h3>Identitas</h3>
            </div>
            <div class="detail-card-body" style="padding-top:14px;padding-bottom:14px">
                @php
                $items = [
                    'NIS'               => $siswa->nis ?? null,
                    'Tempat, Tgl Lahir' => collect([$siswa->tempat_lahir, $siswa->tanggal_lahir?->translatedFormat('d F Y')])->filter()->join(', ') ?: null,
                    'Agama'             => $siswa->agama ?? null,
                    'Alamat'            => $siswa->alamat ?? null,
                    'Tahun Ajaran'      => $siswa->tahun_ajaran ?? null,
                    'Tgl Masuk'         => $siswa->tanggal_masuk?->translatedFormat('d F Y') ?? null,
                ];
                @endphp
                @foreach($items as $label => $value)
                <div class="row-item">
                    <span class="row-label">{{ $label }}</span>
                    <span class="row-value {{ !$value ? 'muted' : '' }}">{{ $value ?? '—' }}</span>
                </div>
                @endforeach

                @if(!$siswa->aktif && $siswa->tanggal_keluar)
                <div style="margin-top:10px;padding-top:10px;border-top:1px solid var(--danger-border)">
                    <div class="row-item row-item-danger" style="border-bottom:none;padding-bottom:0">
                        <span class="row-label">Tgl Keluar</span>
                        <span class="row-value">{{ $siswa->tanggal_keluar->translatedFormat('d F Y') }}</span>
                    </div>
                    <div class="row-item row-item-danger" style="border-bottom:none;padding-bottom:0;padding-top:6px">
                        <span class="row-label">Keterangan</span>
                        <span class="row-value">{{ $siswa->keterangan_keluar ?? '—' }}</span>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Orang Tua / Wali --}}
        <div class="detail-card anim-up">
            <div class="detail-card-head">
                <h3>Orang Tua / Wali</h3>
            </div>
            <div class="detail-card-body" style="padding-top:14px;padding-bottom:14px">
                @if(!$siswa->nama_ayah && !$siswa->nama_ibu && !$siswa->nama_wali)
                    <p style="font-size:var(--fs-sm);color:var(--text-3)">Belum ada data orang tua / wali.</p>
                @else
                    @if($siswa->nama_ayah)
                    <div class="parent-group">
                        <div class="parent-role">Ayah</div>
                        <div class="parent-name">{{ $siswa->nama_ayah }}</div>
                        @if($siswa->pekerjaan_ayah)
                        <div class="parent-job">{{ $siswa->pekerjaan_ayah }}</div>
                        @endif
                        @if($siswa->no_hp_ayah)
                        <div class="parent-phone">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            {{ $siswa->no_hp_ayah }}
                        </div>
                        @endif
                    </div>
                    @endif

                    @if($siswa->nama_ibu)
                    <div class="parent-group">
                        <div class="parent-role">Ibu</div>
                        <div class="parent-name">{{ $siswa->nama_ibu }}</div>
                        @if($siswa->pekerjaan_ibu)
                        <div class="parent-job">{{ $siswa->pekerjaan_ibu }}</div>
                        @endif
                        @if($siswa->no_hp_ibu)
                        <div class="parent-phone">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            {{ $siswa->no_hp_ibu }}
                        </div>
                        @endif
                    </div>
                    @endif

                    @if($siswa->nama_wali)
                    <div class="parent-group">
                        <div class="parent-role">Wali {{ $siswa->hubungan_wali ? '(' . $siswa->hubungan_wali . ')' : '' }}</div>
                        <div class="parent-name">{{ $siswa->nama_wali }}</div>
                        @if($siswa->no_hp_wali)
                        <div class="parent-phone">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            {{ $siswa->no_hp_wali }}
                        </div>
                        @endif
                    </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    {{-- Riwayat SPP --}}
    <div class="detail-card anim-up">
        <div class="detail-card-head">
            <h3>Riwayat Pembayaran SPP</h3>
            @if(in_array(Auth::user()->role, ['admin', 'bendahara']))
            <a href="{{ route('spp.create', ['siswa_id' => $siswa->id]) }}">+ Input SPP</a>
            @endif
        </div>
        @if($siswa->pembayaranSpp->isEmpty())
            <div class="spp-empty">Belum ada pembayaran SPP tercatat.</div>
        @else
        <div style="overflow-x:auto">
            <table class="spp-table">
                <thead>
                    <tr>
                        <th>Periode</th>
                        <th>No. Kwitansi</th>
                        <th class="right">Total</th>
                        <th>Tgl Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($siswa->pembayaranSpp->sortByDesc('tahun')->sortByDesc('bulan') as $spp)
                    <tr>
                        <td class="period">{{ $spp->nama_bulan }} {{ $spp->tahun }}</td>
                        <td class="mono">{{ $spp->no_kwitansi }}</td>
                        <td class="amount">{{ $spp->total_rupiah }}</td>
                        <td style="font-size:var(--fs-xs);color:var(--text-3)">{{ $spp->tanggal_bayar->translatedFormat('d M Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

</div>

{{-- ══════════════════════════════════════════════════════
     MODAL — KELUARKAN SISWA
══════════════════════════════════════════════════════ --}}
@if(Auth::user()->isAdmin() && $siswa->aktif)
<div id="modalKeluarkan" class="hidden modal-backdrop">
    <div class="modal-box">

        <div class="modal-head">
            <div class="modal-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
            </div>
            <div>
                <div class="modal-head-title">Keluarkan Siswa</div>
                <div class="modal-head-sub">{{ $siswa->nama_lengkap }}</div>
            </div>
            <button type="button" class="modal-close"
                    onclick="document.getElementById('modalKeluarkan').classList.add('hidden')"
                    aria-label="Tutup modal">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form method="POST" action="{{ route('siswa.keluarkan', $siswa) }}">
            @csrf
            <input type="hidden" name="redirect_to" value="show">

            <div class="modal-body">
                <div class="field">
                    <label class="field-label">
                        Alasan / Keterangan Keluar <span class="req">*</span>
                    </label>
                    <select name="keterangan_keluar" required class="field-select"
                            onchange="toggleKetLain(this.value)">
                        <option value="">Pilih alasan...</option>
                        <option value="Lulus / Tamat PAUD">Lulus / Tamat PAUD</option>
                        <option value="Pindah sekolah">Pindah sekolah</option>
                        <option value="Pindah domisili">Pindah domisili</option>
                        <option value="Mengundurkan diri">Mengundurkan diri</option>
                        <option value="lain">Lainnya (isi manual)</option>
                    </select>
                    <input type="text" id="ketLainInput" name="keterangan_keluar_manual"
                           placeholder="Tulis keterangan keluar..."
                           class="field-input hidden" style="margin-top:8px">
                </div>

                <div class="field">
                    <label class="field-label">
                        Tanggal Keluar <span class="req">*</span>
                    </label>
                    <input type="date" name="tanggal_keluar" required
                           value="{{ now()->toDateString() }}"
                           class="field-input">
                </div>

                <div class="notice-box">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Siswa akan dinonaktifkan dan tidak muncul di daftar aktif. Data tetap tersimpan dan dapat diaktifkan kembali.</span>
                </div>
            </div>

            <div class="modal-foot">
                <button type="button" class="btn-cancel"
                        onclick="document.getElementById('modalKeluarkan').classList.add('hidden')">
                    Batal
                </button>
                <button type="submit" class="btn-submit-danger">
                    Ya, Keluarkan Siswa
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleKetLain(val) {
    const select = document.querySelector('select[name="keterangan_keluar"]');
    const input  = document.getElementById('ketLainInput');
    if (val === 'lain') {
        input.classList.remove('hidden');
        input.required = true;
        select.name = '_keterangan_keluar_select';
        input.name  = 'keterangan_keluar';
    } else {
        input.classList.add('hidden');
        input.required = false;
        select.name = 'keterangan_keluar';
        input.name  = 'keterangan_keluar_manual';
    }
}

// Tutup modal klik backdrop
document.getElementById('modalKeluarkan').addEventListener('click', function(e) {
    if (e.target === this) this.classList.add('hidden');
});

// Tutup modal dengan Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.getElementById('modalKeluarkan')?.classList.add('hidden');
    }
});
</script>
@endif

@endsection