@extends('layouts.app')

@section('title', 'Data Siswa — PAUD KB Pelangi')
@section('page-title', 'Data Siswa')

@push('styles')
<style>
    /* ══════════════════════════════════════════════════
       DATA SISWA — polished v3
       Menggunakan token dari app.blade.php (OKLCH).
       Glass hanya di panel utama & dropdown — purposeful.
    ══════════════════════════════════════════════════ */

    /* ── Stat bar ── */
    .stat-bar {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
        margin-bottom: 0;
    }
    @media (max-width: 640px) { .stat-bar { grid-template-columns: repeat(2, 1fr); } }

    .stat-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--r-lg);
        padding: 14px 16px;
        box-shadow: var(--shadow-xs);
        position: relative;
        overflow: hidden;
        transition: border-color var(--dur-fast) ease, box-shadow var(--dur-fast) ease;
    }
    .stat-card::before {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: inherit;
        background: linear-gradient(135deg, color-mix(in oklch, var(--accent), transparent 94%) 0%, transparent 60%);
        pointer-events: none;
    }
    .stat-card:hover {
        border-color: var(--border-2);
        box-shadow: var(--shadow-sm);
    }
    .stat-card .lbl {
        font-size: var(--fs-2xs);
        color: var(--text-3);
        letter-spacing: .07em;
        text-transform: uppercase;
        font-family: 'Geist Mono', monospace;
        font-weight: 500;
        margin-bottom: 6px;
    }
    .stat-card .val {
        font-size: 24px;
        font-weight: 600;
        color: var(--text-1);
        line-height: 1;
        font-variant-numeric: tabular-nums;
        letter-spacing: -0.02em;
    }
    .stat-card .sub {
        font-size: var(--fs-2xs);
        color: var(--text-3);
        margin-top: 4px;
    }

    /* ── Glass panel (filter + tabel) ── */
    .glass-panel {
        background: color-mix(in oklch, var(--surface), transparent 18%);
        backdrop-filter: blur(20px) saturate(1.4);
        -webkit-backdrop-filter: blur(20px) saturate(1.4);
        border: 1px solid color-mix(in oklch, var(--border), transparent 20%);
        border-radius: var(--r-xl);
        overflow: hidden;
        box-shadow:
            var(--shadow-md),
            inset 0 1px 0 color-mix(in oklch, white, transparent 78%);
    }
    .dark .glass-panel {
        background: color-mix(in oklch, var(--surface), transparent 28%);
        border-color: color-mix(in oklch, var(--border), transparent 10%);
        box-shadow:
            var(--shadow-md),
            inset 0 1px 0 color-mix(in oklch, white, transparent 92%);
    }

    /* ── Filter row ── */
    .filter-row {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
        padding: 14px 16px;
        border-bottom: 1px solid var(--border);
    }

    .srch-wrap { position: relative; flex: 1; min-width: 180px; }
    .srch-wrap svg {
        position: absolute; left: 10px; top: 50%;
        transform: translateY(-50%);
        width: 14px; height: 14px;
        color: var(--text-3);
        pointer-events: none;
    }
    .srch-wrap input {
        width: 100%;
        padding: 8px 12px 8px 32px;
        background: var(--surface-2);
        border: 1px solid var(--border);
        border-radius: var(--r-sm);
        font-size: var(--fs-sm);
        font-family: 'Geist', sans-serif;
        color: var(--text-1);
        outline: none;
        transition: border-color var(--dur-fast) ease, box-shadow var(--dur-fast) ease;
    }
    .srch-wrap input::placeholder { color: var(--text-3); }
    .srch-wrap input:focus {
        border-color: var(--accent-ring);
        box-shadow: 0 0 0 3px color-mix(in oklch, var(--accent), transparent 84%);
    }

    .flt-select {
        padding: 8px 12px;
        background: var(--surface-2);
        border: 1px solid var(--border);
        border-radius: var(--r-sm);
        font-size: var(--fs-sm);
        font-family: 'Geist', sans-serif;
        color: var(--text-1);
        outline: none;
        cursor: pointer;
        transition: border-color var(--dur-fast) ease;
    }
    .flt-select:focus {
        border-color: var(--accent-ring);
        box-shadow: 0 0 0 3px color-mix(in oklch, var(--accent), transparent 84%);
    }

    .btn-cari {
        padding: 8px 16px;
        background: var(--accent);
        color: var(--text-inv);
        font-size: var(--fs-sm);
        font-weight: 500;
        font-family: 'Geist', sans-serif;
        border-radius: var(--r-sm);
        border: none;
        cursor: pointer;
        transition: background var(--dur-fast) ease, transform var(--dur-micro) ease;
        box-shadow: 0 1px 3px color-mix(in oklch, var(--accent), transparent 60%);
    }
    .btn-cari:hover { background: var(--accent-h); }
    .btn-cari:active { transform: scale(0.97); }

    .btn-reset {
        padding: 8px 14px;
        background: transparent;
        border: 1px solid var(--border);
        border-radius: var(--r-sm);
        font-size: var(--fs-sm);
        font-family: 'Geist', sans-serif;
        color: var(--text-2);
        cursor: pointer;
        transition: background var(--dur-fast) ease, border-color var(--dur-fast) ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }
    .btn-reset:hover {
        background: var(--accent-soft);
        border-color: var(--accent-ring);
    }

    /* ── View toggle ── */
    .view-toggle {
        display: flex;
        background: var(--bg-2);
        border: 1px solid var(--border);
        border-radius: var(--r-sm);
        padding: 3px;
        gap: 2px;
        margin-left: auto;
    }
    .tgl-btn {
        display: flex;
        align-items: center;
        gap: 5px;
        padding: 5px 11px;
        border: none;
        border-radius: calc(var(--r-sm) - 2px);
        font-size: var(--fs-xs);
        font-family: 'Geist', sans-serif;
        font-weight: 500;
        cursor: pointer;
        background: transparent;
        color: var(--text-2);
        transition: background var(--dur-fast) ease, color var(--dur-fast) ease, box-shadow var(--dur-fast) ease;
    }
    .tgl-btn.active {
        background: var(--accent);
        color: var(--text-inv);
        box-shadow: 0 1px 3px color-mix(in oklch, var(--accent), transparent 55%);
    }
    .tgl-btn:not(.active):hover { background: var(--surface-2); }

    /* ── Table ── */
    .tbl-scroll { overflow-x: auto; }
    table.siswa-tbl { width: 100%; border-collapse: collapse; }

    table.siswa-tbl thead tr {
        border-bottom: 1px solid var(--border);
    }
    table.siswa-tbl th {
        text-align: left;
        padding: 10px 14px;
        font-size: var(--fs-2xs);
        font-weight: 600;
        color: var(--text-3);
        letter-spacing: .08em;
        text-transform: uppercase;
        font-family: 'Geist Mono', monospace;
        white-space: nowrap;
        background: var(--bg);
    }
    table.siswa-tbl th.center { text-align: center; }

    table.siswa-tbl td {
        padding: 11px 14px;
        font-size: var(--fs-sm);
        color: var(--text-2);
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
    }
    table.siswa-tbl tbody tr:last-child td { border-bottom: none; }
    table.siswa-tbl tbody tr {
        transition: background var(--dur-micro) ease;
    }
    table.siswa-tbl tbody tr:hover td {
        background: var(--accent-soft);
    }

    /* ── Avatar initials ── */
    .ava {
        width: 34px; height: 34px;
        border-radius: var(--r);
        display: flex; align-items: center; justify-content: center;
        font-size: var(--fs-xs);
        font-weight: 600;
        color: var(--text-inv);
        flex-shrink: 0;
        background: var(--accent);
        box-shadow: 0 1px 4px color-mix(in oklch, var(--accent), transparent 55%);
    }
    .ava img { width: 100%; height: 100%; object-fit: cover; border-radius: inherit; }

    /* Avatar color helpers */
    .ava[data-color="0"] { background: oklch(52% 0.190 260); }
    .ava[data-color="1"] { background: oklch(52% 0.200 290); }
    .ava[data-color="2"] { background: oklch(54% 0.220 340); }
    .ava[data-color="3"] { background: oklch(46% 0.150 155); }
    .ava[data-color="4"] { background: oklch(60% 0.180 70); }
    .ava[data-color="5"] { background: oklch(48% 0.170 210); }
    .ava[data-color="6"] { background: oklch(52% 0.200 270); }
    .ava[data-color="7"] { background: oklch(50% 0.160 175); }

    /* ── Pills ── */
    .pill {
        display: inline-flex;
        align-items: center;
        padding: 2px 9px;
        border-radius: 9999px;
        font-size: var(--fs-xs);
        font-weight: 500;
        white-space: nowrap;
    }
    .pill-blue {
        background: var(--accent-muted);
        color: var(--accent);
        border: 1px solid var(--accent-ring);
    }
    .pill-green {
        background: var(--success-bg);
        color: var(--success);
        border: 1px solid var(--success-border);
    }
    .pill-red {
        background: var(--danger-bg);
        color: var(--danger);
        border: 1px solid var(--danger-border);
    }

    /* ── Action buttons ── */
    .acts { display: flex; align-items: center; justify-content: center; gap: 4px; }
    .act-btn {
        position: relative;
        width: 30px; height: 30px;
        border-radius: var(--r-sm);
        border: 1px solid var(--border);
        background: transparent;
        color: var(--text-3);
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: all var(--dur-fast) var(--ease-out);
    }
    .act-btn svg { width: 14px; height: 14px; }
    .act-btn:hover { transform: translateY(-1px); box-shadow: var(--shadow-xs); }
    .act-btn.view:hover {
        border-color: var(--accent-ring);
        background: var(--accent-soft);
        color: var(--accent);
    }
    .act-btn.edit:hover {
        border-color: color-mix(in oklch, var(--warning), transparent 55%);
        background: var(--warning-bg);
        color: var(--warning);
    }
    .act-btn.del:hover {
        border-color: var(--danger-border);
        background: var(--danger-bg);
        color: var(--danger);
    }
    /* tooltip */
    .act-btn .tt {
        position: absolute;
        bottom: calc(100% + 6px);
        left: 50%; transform: translateX(-50%);
        background: var(--text-1);
        color: var(--surface);
        font-size: var(--fs-2xs);
        padding: 3px 8px;
        border-radius: var(--r-sm);
        white-space: nowrap;
        opacity: 0; pointer-events: none;
        transition: opacity var(--dur-fast) ease;
        font-family: 'Geist', sans-serif;
    }
    .act-btn:hover .tt { opacity: 1; }

    /* ── Card grid ── */
    .card-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(175px, 1fr));
        gap: 12px;
        padding: 16px;
    }
    .student-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--r-lg);
        padding: 16px;
        cursor: pointer;
        transition:
            border-color var(--dur-fast) ease,
            box-shadow var(--dur-mid) var(--ease-out),
            transform var(--dur-mid) var(--ease-out);
        text-decoration: none;
        display: block;
        position: relative;
        overflow: hidden;
    }
    .student-card::before {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: inherit;
        background: linear-gradient(135deg, color-mix(in oklch, var(--accent), transparent 92%) 0%, transparent 55%);
        opacity: 0;
        transition: opacity var(--dur-fast) ease;
        pointer-events: none;
    }
    .student-card:hover {
        border-color: var(--accent-ring);
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }
    .student-card:hover::before { opacity: 1; }

    .student-card .card-ava {
        width: 44px; height: 44px;
        border-radius: var(--r);
        display: flex; align-items: center; justify-content: center;
        font-size: 17px; font-weight: 600; color: var(--text-inv);
        margin-bottom: 12px;
        overflow: hidden;
        box-shadow: 0 2px 6px color-mix(in oklch, currentColor, transparent 60%);
    }
    .student-card .card-ava img { width: 100%; height: 100%; object-fit: cover; }
    .student-card .card-name {
        font-size: var(--fs-sm);
        font-weight: 500;
        color: var(--text-1);
        margin-bottom: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .student-card .card-sub {
        font-size: var(--fs-xs);
        color: var(--text-3);
        margin-bottom: 10px;
        font-family: 'Geist Mono', monospace;
    }
    .student-card .card-foot {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 4px;
        padding-top: 10px;
        border-top: 1px solid var(--border);
    }

    /* ── Empty state ── */
    .empty-state {
        padding: 64px 20px;
        text-align: center;
        color: var(--text-3);
    }
    .empty-state svg { width: 44px; height: 44px; margin: 0 auto 12px; opacity: .35; }
    .empty-state p { font-size: var(--fs-sm); font-weight: 500; color: var(--text-2); }
    .empty-link {
        display: inline-block;
        margin-top: 10px;
        font-size: var(--fs-sm);
        color: var(--accent);
        text-decoration: none;
        font-weight: 500;
        transition: opacity var(--dur-fast) ease;
    }
    .empty-link:hover { opacity: 0.75; }

    /* ── Pagination wrapper ── */
    .pager-wrap { padding: 12px 16px; border-top: 1px solid var(--border); }

    /* ══════════════════════════════════════
       SPLIT BUTTON (Tambah Siswa)
    ══════════════════════════════════════ */
    .split-btn-wrap { position: relative; display: inline-flex; }

    .split-main {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 16px;
        background: var(--accent);
        color: var(--text-inv);
        font-size: var(--fs-sm);
        font-weight: 500;
        font-family: 'Geist', sans-serif;
        border-radius: var(--r) 0 0 var(--r);
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: background var(--dur-fast) ease;
        white-space: nowrap;
        box-shadow: 0 1px 3px color-mix(in oklch, var(--accent), transparent 55%);
    }
    .split-main:hover { background: var(--accent-h); color: var(--text-inv); }
    .split-main svg { width: 14px; height: 14px; flex-shrink: 0; }

    .split-chevron {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        background: var(--accent-h);
        color: var(--text-inv);
        border: none;
        border-radius: 0 var(--r) var(--r) 0;
        border-left: 1px solid color-mix(in oklch, white, transparent 75%);
        cursor: pointer;
        transition: background var(--dur-fast) ease;
        flex-shrink: 0;
    }
    .split-chevron:hover { background: oklch(from var(--accent-h) calc(l - 0.06) c h); }
    .split-chevron svg { width: 13px; height: 13px; transition: transform var(--dur-mid) var(--ease-out); }
    .split-chevron.open svg { transform: rotate(180deg); }

    /* Glass dropdown */
    .split-dropdown {
        position: absolute;
        top: calc(100% + 8px);
        right: 0;
        min-width: 210px;
        background: color-mix(in oklch, var(--surface), transparent 8%);
        backdrop-filter: blur(20px) saturate(1.3);
        -webkit-backdrop-filter: blur(20px) saturate(1.3);
        border: 1px solid color-mix(in oklch, var(--border), transparent 10%);
        border-radius: var(--r-lg);
        box-shadow:
            var(--shadow-lg),
            inset 0 1px 0 color-mix(in oklch, white, transparent 82%);
        overflow: hidden;
        display: none;
        z-index: 50;
        animation: dropIn var(--dur-mid) var(--ease-out);
    }
    .dark .split-dropdown {
        box-shadow:
            var(--shadow-lg),
            inset 0 1px 0 color-mix(in oklch, white, transparent 94%);
    }
    .split-dropdown.open { display: block; }

    @keyframes dropIn {
        from { opacity: 0; transform: translateY(-8px) scale(0.98); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }

    .dd-item {
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 11px 14px;
        font-size: var(--fs-sm);
        color: var(--text-1);
        text-decoration: none;
        transition: background var(--dur-micro) ease;
        cursor: pointer;
        border: none;
        background: transparent;
        width: 100%;
        text-align: left;
        font-family: 'Geist', sans-serif;
    }
    .dd-item:hover { background: var(--accent-soft); }
    .dd-item:not(:last-child) { border-bottom: 1px solid var(--border); }

    .dd-icon {
        width: 30px; height: 30px;
        border-radius: var(--r-sm);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .dd-icon svg { width: 14px; height: 14px; }
    .dd-icon.blue {
        background: var(--accent-muted);
        color: var(--accent);
        border: 1px solid var(--accent-ring);
    }
    .dd-icon.green {
        background: var(--success-bg);
        color: var(--success);
        border: 1px solid var(--success-border);
    }

    .dd-text { display: flex; flex-direction: column; gap: 1px; }
    .dd-text .dd-label { font-size: var(--fs-sm); font-weight: 500; color: var(--text-1); }
    .dd-text .dd-desc  { font-size: var(--fs-xs); color: var(--text-3); }

    /* ── Stagger animation masuk ── */
    @media (prefers-reduced-motion: no-preference) {
        .stat-card {
            animation: fadeUp var(--dur-page) var(--ease-out) both;
        }
        .stat-card:nth-child(1) { animation-delay: 0ms; }
        .stat-card:nth-child(2) { animation-delay: 40ms; }
        .stat-card:nth-child(3) { animation-delay: 80ms; }
        .stat-card:nth-child(4) { animation-delay: 120ms; }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    }
</style>
@endpush

@section('content')
<div class="space-y-4">

    {{-- ── Header ── --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-xl font-semibold" style="color:var(--text-1);letter-spacing:-0.02em">Data Siswa</h2>
            <p class="text-sm mt-1" style="color:var(--text-3)">
                <span class="font-medium tabular" style="color:var(--success)">{{ $totalAktif }} aktif</span>
                @if($totalNonAktif > 0)
                    <span style="margin:0 4px;opacity:.4">·</span>
                    <span class="tabular">{{ $totalNonAktif }} nonaktif</span>
                @endif
            </p>
        </div>

        {{-- ── SPLIT BUTTON TAMBAH SISWA ── --}}
        @if(Auth::user()->isAdmin())
        <div class="split-btn-wrap" id="splitWrap">
            <a href="{{ route('siswa.create') }}" class="split-main">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Siswa
            </a>

            <button type="button" class="split-chevron" id="splitChevron"
                    onclick="toggleSplit()" aria-haspopup="true" aria-expanded="false">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div class="split-dropdown" id="splitDropdown" role="menu">
                <a href="{{ route('siswa.create') }}" class="dd-item" role="menuitem">
                    <div class="dd-icon blue">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div class="dd-text">
                        <span class="dd-label">Input Manual</span>
                        <span class="dd-desc">Tambah satu per satu</span>
                    </div>
                </a>

                <a href="{{ route('siswa.import.form') }}" class="dd-item" role="menuitem">
                    <div class="dd-icon green">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0121 9.414V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="dd-text">
                        <span class="dd-label">Import Excel</span>
                        <span class="dd-desc">Upload banyak sekaligus</span>
                    </div>
                </a>
            </div>
        </div>
        @endif
    </div>

    {{-- ── Stat bar ── --}}
    <div class="stat-bar">
        <div class="stat-card">
            <div class="lbl">Total Siswa</div>
            <div class="val tabular">{{ $totalAktif + $totalNonAktif }}</div>
            <div class="sub">Semua status</div>
        </div>
        <div class="stat-card">
            <div class="lbl">Aktif</div>
            <div class="val tabular" style="color:var(--success)">{{ $totalAktif }}</div>
            <div class="sub">Tahun ajaran ini</div>
        </div>
        <div class="stat-card">
            <div class="lbl">Kelompok KB</div>
            <div class="val tabular" style="color:var(--accent)">{{ $totalAktif + $totalNonAktif }}</div>
            <div class="sub">1 kelompok</div>
        </div>
        <div class="stat-card">
            <div class="lbl">Nonaktif</div>
            <div class="val tabular" style="color:var(--danger)">{{ $totalNonAktif }}</div>
            <div class="sub">Perlu tindak lanjut</div>
        </div>
    </div>

    {{-- ── Filter + Toggle + Tabel ── --}}
    <div class="glass-panel">

        <form method="GET" action="{{ route('siswa.index') }}">
            <div class="filter-row">
                {{-- Search --}}
                <div class="srch-wrap">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/>
                    </svg>
                    <input type="text" name="cari" value="{{ request('cari') }}"
                           placeholder="Cari nama atau NIS...">
                </div>

                {{-- Kelompok --}}
                <select name="kelompok" class="flt-select">
                    <option value="">Semua Kelompok</option>
                    <option value="KB" {{ request('kelompok') === 'KB' ? 'selected' : '' }}>KB</option>
                </select>

                {{-- Status --}}
                <select name="status" class="flt-select">
                    <option value="aktif"    {{ request('status', 'aktif') === 'aktif'   ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') === 'nonaktif'         ? 'selected' : '' }}>Nonaktif</option>
                </select>

                <button type="submit" class="btn-cari">Cari</button>

                @if(request()->hasAny(['cari','kelompok','status']))
                <a href="{{ route('siswa.index') }}" class="btn-reset">Reset</a>
                @endif

                {{-- View toggle --}}
                <div class="view-toggle" role="group" aria-label="Mode tampilan">
                    <button type="button" class="tgl-btn active" id="btnTbl"
                            onclick="setView('table')" aria-pressed="true">
                        <svg style="width:13px;height:13px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 10h18M3 14h18M10 4v16M3 4h18v16H3z"/>
                        </svg>
                        Tabel
                    </button>
                    <button type="button" class="tgl-btn" id="btnCard"
                            onclick="setView('card')" aria-pressed="false">
                        <svg style="width:13px;height:13px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                        Kartu
                    </button>
                </div>
            </div>
        </form>

        {{-- ── TABLE VIEW ── --}}
        <div id="viewTable">
            @if($siswa->isEmpty())
            <div class="empty-state">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M17 20H5a2 2 0 01-2-2V7a2 2 0 012-2h3m4 0h5a2 2 0 012 2v1M9 5V3m6 2V3M3 10h18"/>
                </svg>
                <p>Tidak ada data siswa</p>
                @if(Auth::user()->isAdmin())
                <a href="{{ route('siswa.create') }}" class="empty-link">+ Tambah siswa pertama</a>
                @endif
            </div>
            @else
            <div class="tbl-scroll">
                <table class="siswa-tbl">
                    <thead>
                        <tr>
                            <th style="width:30%">Siswa</th>
                            <th style="width:11%" class="hidden md:table-cell">NIS</th>
                            <th style="width:10%" class="hidden sm:table-cell">Kel.</th>
                            <th style="width:19%" class="hidden lg:table-cell">Wali</th>
                            <th style="width:13%" class="hidden lg:table-cell">No. HP</th>
                            <th style="width:10%;text-align:center">Status</th>
                            <th style="width:14%;text-align:center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($siswa as $index => $s)
                        <tr>
                            {{-- Siswa --}}
                            <td>
                                <div style="display:flex;align-items:center;gap:10px">
                                    <div class="ava" data-color="{{ $index % 8 }}">
                                        @if($s->foto)
                                            <img src="{{ Storage::url($s->foto) }}" alt="{{ $s->nama_lengkap }}">
                                        @else
                                            {{ strtoupper(substr($s->nama_lengkap, 0, 1)) }}
                                        @endif
                                    </div>
                                    <div>
                                        <p style="font-size:var(--fs-sm);font-weight:500;color:var(--text-1);margin-bottom:2px">{{ $s->nama_lengkap }}</p>
                                        @if($s->umur)
                                        <p style="font-size:var(--fs-xs);color:var(--text-3);font-family:'Geist Mono',monospace">{{ $s->umur }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            {{-- NIS --}}
                            <td class="hidden md:table-cell" style="font-family:'Geist Mono',monospace;font-size:var(--fs-xs)">{{ $s->nis ?? '—' }}</td>
                            {{-- Kelompok --}}
                            <td class="hidden sm:table-cell">
                                <span class="pill pill-blue">{{ $s->kelompok }}</span>
                            </td>
                            {{-- Wali --}}
                            <td class="hidden lg:table-cell">{{ $s->nama_kontak ?? '—' }}</td>
                            {{-- No HP --}}
                            <td class="hidden lg:table-cell" style="font-family:'Geist Mono',monospace;font-size:var(--fs-xs)">{{ $s->kontak_utama ?? '—' }}</td>
                            {{-- Status --}}
                            <td style="text-align:center">
                                @if($s->aktif)
                                    <span class="pill pill-green">Aktif</span>
                                @else
                                    <span class="pill pill-red">Nonaktif</span>
                                @endif
                            </td>
                            {{-- Aksi --}}
                            <td>
                                <div class="acts">
                                    <a href="{{ route('siswa.show', $s) }}" class="act-btn view"
                                       title="Detail" aria-label="Lihat detail {{ $s->nama_lengkap }}">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <span class="tt">Detail</span>
                                    </a>

                                    @if(Auth::user()->isAdmin())
                                    <a href="{{ route('siswa.edit', $s) }}" class="act-btn edit"
                                       title="Edit" aria-label="Edit {{ $s->nama_lengkap }}">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        <span class="tt">Edit</span>
                                    </a>

                                    @if($s->aktif)
                                    <form method="POST" action="{{ route('siswa.destroy', $s) }}"
                                          style="display:contents"
                                          onsubmit="return confirm('Nonaktifkan siswa {{ addslashes($s->nama_lengkap) }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="act-btn del"
                                                title="Nonaktifkan" aria-label="Nonaktifkan {{ $s->nama_lengkap }}">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636"/>
                                            </svg>
                                            <span class="tt">Nonaktifkan</span>
                                        </button>
                                    </form>
                                    @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($siswa->hasPages())
            <div class="pager-wrap">
                {{ $siswa->appends(request()->query())->links() }}
            </div>
            @endif
            @endif
        </div>

        {{-- ── CARD VIEW ── --}}
        <div id="viewCard" style="display:none">
            @if($siswa->isEmpty())
            <div class="empty-state">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M17 20H5a2 2 0 01-2-2V7a2 2 0 012-2h3m4 0h5a2 2 0 012 2v1M9 5V3m6 2V3M3 10h18"/>
                </svg>
                <p>Tidak ada data siswa</p>
            </div>
            @else
            <div class="card-grid">
                @foreach($siswa as $index => $s)
                @php
                    $cardColors = [
                        'oklch(52% 0.190 260)',
                        'oklch(52% 0.200 290)',
                        'oklch(54% 0.220 340)',
                        'oklch(46% 0.150 155)',
                        'oklch(60% 0.180 70)',
                        'oklch(48% 0.170 210)',
                        'oklch(52% 0.200 270)',
                        'oklch(50% 0.160 175)',
                    ];
                    $cardColor = $cardColors[$index % count($cardColors)];
                @endphp
                <a href="{{ route('siswa.show', $s) }}" class="student-card">
                    <div class="card-ava" style="background:{{ $cardColor }}">
                        @if($s->foto)
                            <img src="{{ Storage::url($s->foto) }}" alt="{{ $s->nama_lengkap }}">
                        @else
                            {{ strtoupper(substr($s->nama_lengkap, 0, 2)) }}
                        @endif
                    </div>
                    <div class="card-name">{{ $s->nama_lengkap }}</div>
                    <div class="card-sub">{{ $s->nis ?? '—' }}@if($s->umur) · {{ $s->umur }}@endif</div>
                    <div class="card-foot">
                        @if($s->aktif)
                            <span class="pill pill-green">Aktif</span>
                        @else
                            <span class="pill pill-red">Nonaktif</span>
                        @endif
                        <span class="pill pill-blue">{{ $s->kelompok }}</span>
                    </div>
                </a>
                @endforeach
            </div>

            @if($siswa->hasPages())
            <div class="pager-wrap">
                {{ $siswa->appends(request()->query())->links() }}
            </div>
            @endif
            @endif
        </div>

    </div>{{-- /glass-panel --}}
</div>

@push('scripts')
<script>
/* ── View toggle ── */
function setView(v) {
    const tbl  = document.getElementById('viewTable');
    const card = document.getElementById('viewCard');
    const btnT = document.getElementById('btnTbl');
    const btnC = document.getElementById('btnCard');
    if (v === 'table') {
        tbl.style.display  = 'block';
        card.style.display = 'none';
        btnT.classList.add('active');    btnT.setAttribute('aria-pressed','true');
        btnC.classList.remove('active'); btnC.setAttribute('aria-pressed','false');
    } else {
        tbl.style.display  = 'none';
        card.style.display = 'block';
        btnT.classList.remove('active'); btnT.setAttribute('aria-pressed','false');
        btnC.classList.add('active');    btnC.setAttribute('aria-pressed','true');
    }
    localStorage.setItem('siswaView', v);
}

document.addEventListener('DOMContentLoaded', function () {
    const saved = localStorage.getItem('siswaView');
    if (saved === 'card') setView('card');
});

/* ── Split button dropdown ── */
function toggleSplit() {
    const dropdown = document.getElementById('splitDropdown');
    const chevron  = document.getElementById('splitChevron');
    const isOpen   = dropdown.classList.contains('open');

    if (isOpen) {
        dropdown.classList.remove('open');
        chevron.classList.remove('open');
        chevron.setAttribute('aria-expanded', 'false');
    } else {
        dropdown.classList.add('open');
        chevron.classList.add('open');
        chevron.setAttribute('aria-expanded', 'true');
    }
}

document.addEventListener('click', function (e) {
    const wrap = document.getElementById('splitWrap');
    if (wrap && !wrap.contains(e.target)) {
        document.getElementById('splitDropdown')?.classList.remove('open');
        const ch = document.getElementById('splitChevron');
        if (ch) { ch.classList.remove('open'); ch.setAttribute('aria-expanded','false'); }
    }
});
</script>
@endpush

@endsection