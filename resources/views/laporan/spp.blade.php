{{-- resources/views/laporan/spp.blade.php --}}
@extends('layouts.app')

@section('title', 'Laporan SPP — ' . \Carbon\Carbon::create(null, $bulan)->translatedFormat('F') . ' ' . $tahun)
@section('page-title', 'Laporan SPP')

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════════════
   LAPORAN SPP — Token OKLCH dari app.blade.php
   Scene: Bendahara/Admin membuka laporan di siang hari,
          data finansial harus terbaca jelas, angka harus crisp.
   Strategy: Restrained + stat cards glass purposeful (finansial
             perlu feel premium), table solid & dense.
═══════════════════════════════════════════════════════════════ */

/* ── Ambient orbs ── */
.spp-ambient {
    position: fixed;
    inset: 0;
    pointer-events: none;
    z-index: 0;
    overflow: hidden;
}
.spp-ambient::before {
    content: '';
    position: absolute;
    top: -160px; right: -120px;
    width: 600px; height: 600px;
    border-radius: 50%;
    background: radial-gradient(circle,
        oklch(52% 0.190 260 / 5%) 0%,
        transparent 65%
    );
}
.spp-ambient::after {
    content: '';
    position: absolute;
    bottom: -100px; left: -80px;
    width: 440px; height: 440px;
    border-radius: 50%;
    background: radial-gradient(circle,
        oklch(46% 0.150 155 / 4%) 0%,
        transparent 65%
    );
}
.dark .spp-ambient::before {
    background: radial-gradient(circle,
        oklch(63% 0.185 260 / 8%) 0%,
        transparent 65%
    );
}
.dark .spp-ambient::after {
    background: radial-gradient(circle,
        oklch(70% 0.165 155 / 6%) 0%,
        transparent 65%
    );
}

/* ── Wrap ── */
.spp-wrap {
    position: relative;
    z-index: 1;
    max-width: 1160px;
    margin: 0 auto;
    padding: 1.75rem 1rem 4rem;
}

/* ══════════════════════════════════════════
   PAGE HEADER
══════════════════════════════════════════ */
.spp-page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 1.25rem;
    margin-bottom: 1.75rem;
    flex-wrap: wrap;
}

.spp-heading { flex: 1; min-width: 0; }

.spp-breadcrumb {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: var(--fs-xs);
    color: var(--text-3);
    margin-bottom: 7px;
}
.spp-breadcrumb a {
    color: var(--text-3);
    text-decoration: none;
    transition: color var(--dur-fast);
}
.spp-breadcrumb a:hover { color: var(--accent); }
.spp-breadcrumb .sep { color: var(--border-2); }

.spp-title {
    font-size: 1.45rem;
    font-weight: 800;
    color: var(--text-1);
    letter-spacing: -.035em;
    line-height: 1.2;
    margin-bottom: .35rem;
}

.spp-subtitle {
    font-size: var(--fs-sm);
    color: var(--text-3);
    display: flex;
    align-items: center;
    gap: 7px;
    flex-wrap: wrap;
}
.spp-subtitle strong { color: var(--text-2); font-weight: 600; }

/* Badge periode */
.periode-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 3px 10px;
    background: var(--accent-soft);
    border: 1px solid var(--accent-ring);
    color: var(--accent);
    border-radius: 99px;
    font-size: var(--fs-xs);
    font-weight: 700;
    letter-spacing: .01em;
}

/* ══════════════════════════════════════════
   TOOLBAR
══════════════════════════════════════════ */
.spp-toolbar {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    flex-shrink: 0;
}

.toolbar-label {
    font-size: var(--fs-xs);
    font-weight: 700;
    color: var(--text-3);
    text-transform: uppercase;
    letter-spacing: .05em;
    margin-right: 2px;
}

/* Select */
.tb-select {
    font-family: inherit;
    font-size: var(--fs-sm);
    font-weight: 500;
    padding: 8px 28px 8px 10px;
    border-radius: var(--r);
    border: 1.5px solid var(--border);
    background: var(--surface);
    color: var(--text-1);
    cursor: pointer;
    outline: none;
    appearance: none;
    -webkit-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='11' height='11' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 8px center;
    transition: border-color var(--dur-fast), box-shadow var(--dur-fast);
}
.tb-select:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px oklch(52% 0.190 260 / 12%);
}

.toolbar-sep {
    width: 1px;
    height: 26px;
    background: var(--border);
    margin: 0 2px;
    flex-shrink: 0;
}

/* Btn tampilkan */
.btn-tampil {
    font-family: inherit;
    font-size: var(--fs-sm);
    font-weight: 700;
    padding: 8px 16px;
    border-radius: var(--r);
    border: none;
    background: var(--accent);
    color: var(--text-inv);
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition:
        background var(--dur-fast) var(--ease-out),
        transform  var(--dur-fast) var(--ease-out),
        box-shadow var(--dur-fast);
    box-shadow: 0 1px 3px oklch(52% 0.190 260 / 30%);
}
.btn-tampil:hover {
    background: var(--accent-h);
    transform: translateY(-1px);
    box-shadow: 0 3px 10px oklch(52% 0.190 260 / 28%);
}
.btn-tampil:active { transform: scale(.97); }
.btn-tampil svg { width: 13px; height: 13px; }

/* btn-ghost */
.btn-ghost-tb {
    font-family: inherit;
    font-size: var(--fs-sm);
    font-weight: 600;
    padding: 7.5px 14px;
    border-radius: var(--r);
    border: 1.5px solid var(--border);
    background: var(--surface);
    color: var(--text-2);
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
    transition:
        border-color var(--dur-fast),
        color        var(--dur-fast),
        background   var(--dur-fast),
        transform    var(--dur-fast);
}
.btn-ghost-tb:hover {
    border-color: var(--accent);
    color: var(--accent);
    background: var(--accent-soft);
    transform: translateY(-1px);
}
.btn-ghost-tb:active { transform: scale(.97); }
.btn-ghost-tb svg { width: 14px; height: 14px; flex-shrink: 0; }

/* btn download */
.btn-dl {
    font-family: inherit;
    font-size: var(--fs-sm);
    font-weight: 700;
    padding: 7.5px 14px;
    border-radius: var(--r);
    border: 1.5px solid oklch(46% 0.150 155 / 50%);
    background: var(--success-bg);
    color: var(--success);
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
    transition:
        background var(--dur-fast),
        border-color var(--dur-fast),
        transform var(--dur-fast);
}
.btn-dl:hover {
    background: oklch(92% 0.060 155);
    border-color: var(--success);
    transform: translateY(-1px);
}
.dark .btn-dl:hover { background: oklch(24% 0.060 155); }
.btn-dl:active { transform: scale(.97); }
.btn-dl svg { width: 14px; height: 14px; }

/* ══════════════════════════════════════════
   STAT CARDS — glass purposeful: angka KPI
   finansial butuh feel premium & authority
══════════════════════════════════════════ */
.spp-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
    margin-bottom: 1.75rem;
}

.scard {
    position: relative;
    border-radius: var(--r-lg);
    overflow: hidden;
    /* Glass layer — purposeful: KPI finansial */
    background: oklch(99% 0.004 260 / 70%);
    backdrop-filter: blur(16px) saturate(160%);
    -webkit-backdrop-filter: blur(16px) saturate(160%);
    border: 1px solid oklch(92% 0.010 260 / 65%);
    box-shadow:
        0 1px 0 oklch(100% 0 0 / 55%) inset,
        var(--shadow-sm);
    padding: 18px 18px 16px;
    transition: box-shadow var(--dur-mid) var(--ease-out), transform var(--dur-mid) var(--ease-out);
}
.scard:hover {
    box-shadow:
        0 1px 0 oklch(100% 0 0 / 55%) inset,
        var(--shadow-md);
    transform: translateY(-2px);
}
.dark .scard {
    background: oklch(17% 0.014 260 / 72%);
    border-color: oklch(30% 0.015 260 / 45%);
    box-shadow:
        0 1px 0 oklch(100% 0 0 / 7%) inset,
        var(--shadow-sm);
}

/* Accent stripe atas */
.scard::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 2.5px;
}
.scard-navy::before  { background: var(--accent); }
.scard-green::before { background: var(--success); }
.scard-red::before   { background: var(--danger); }
.scard-amber::before { background: var(--warning); }

.scard-icon {
    width: 34px; height: 34px;
    border-radius: var(--r-sm);
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 12px;
    flex-shrink: 0;
}
.scard-icon svg { width: 16px; height: 16px; }
.scard-icon-navy  { background: var(--accent-soft);  color: var(--accent); }
.scard-icon-green { background: var(--success-bg);   color: var(--success); }
.scard-icon-red   { background: var(--danger-bg);    color: var(--danger); }
.scard-icon-amber {
    background: oklch(98% 0.030 70);
    color:      var(--warning);
}
.dark .scard-icon-amber { background: oklch(18% 0.040 70); }

.scard-label {
    font-size: var(--fs-2xs);
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .07em;
    color: var(--text-3);
    margin-bottom: 5px;
}

.scard-value {
    font-size: 1.75rem;
    font-weight: 800;
    line-height: 1;
    margin-bottom: 5px;
    letter-spacing: -.03em;
    font-variant-numeric: tabular-nums;
}
.scard-value.navy  { color: var(--accent); }
.scard-value.green { color: var(--success); }
.scard-value.red   { color: var(--danger); }
.scard-value.amber {
    color: var(--warning);
    font-size: 1.1rem;
    letter-spacing: -.015em;
}

.scard-meta {
    font-size: var(--fs-xs);
    color: var(--text-3);
    line-height: 1.4;
}

/* Progress bar */
.scard-bar {
    margin-top: 12px;
    height: 3px;
    background: var(--border);
    border-radius: 99px;
    overflow: hidden;
}
.scard-bar-fill {
    height: 100%;
    border-radius: 99px;
    transition: width .5s var(--ease-out);
}
.scard-bar-fill.green { background: var(--success); }
.scard-bar-fill.red   { background: var(--danger); }

/* ══════════════════════════════════════════
   SECTION HEADER
══════════════════════════════════════════ */
.spp-section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin: 1.75rem 0 .85rem;
    gap: 10px;
}

.section-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: var(--fs-xs);
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    padding: 5px 12px;
    border-radius: 99px;
    border: 1px solid;
}
.section-pill svg { width: 12px; height: 12px; }
.section-pill-blue {
    background: var(--accent-soft);
    color: var(--accent);
    border-color: var(--accent-ring);
}
.section-pill-red {
    background: var(--danger-bg);
    color: var(--danger);
    border-color: var(--danger-border);
}

.section-count {
    font-size: var(--fs-xs);
    color: var(--text-3);
    font-weight: 500;
}
.section-count.red { color: var(--danger); }

/* ══════════════════════════════════════════
   TABEL
══════════════════════════════════════════ */
.spp-table-wrap {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--r-lg);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}

/* Scroll horizontal pada layar kecil */
.spp-table-scroll {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.spp-table {
    width: 100%;
    border-collapse: collapse;
    font-size: var(--fs-sm);
    min-width: 680px;
}

/* Thead */
.spp-table thead tr {
    background: oklch(14% 0.010 260);
}
.dark .spp-table thead tr {
    background: oklch(10% 0.010 255);
}
.spp-table thead th {
    padding: 11px 14px;
    font-size: var(--fs-2xs);
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
    color: oklch(62% 0.012 255);
    text-align: left;
    white-space: nowrap;
    border: none;
}
.spp-table thead th.ta-r { text-align: right; }
.spp-table thead th.ta-c { text-align: center; }

/* thead merah untuk tunggakan */
.spp-table thead.red-head tr {
    background: oklch(18% 0.090 27);
}
.dark .spp-table thead.red-head tr {
    background: oklch(13% 0.080 27);
}

/* Tbody */
.spp-table tbody tr {
    border-top: 1px solid var(--border);
    transition: background var(--dur-micro);
}
.spp-table tbody tr:first-child { border-top: none; }
.spp-table tbody tr:hover { background: var(--surface-2); }

.spp-table tbody td {
    padding: 10px 14px;
    color: var(--text-2);
    vertical-align: middle;
}

.td-no {
    color: var(--text-3);
    font-size: var(--fs-xs);
    text-align: center;
    font-weight: 500;
}
.td-nama {
    font-weight: 700;
    color: var(--text-1);
    white-space: nowrap;
}
.td-kwitansi {
    font-family: var(--font-mono, 'Courier New', monospace);
    font-size: var(--fs-xs);
    color: var(--text-3);
    letter-spacing: .03em;
}
.td-tgl {
    text-align: center;
    color: var(--text-3);
    font-size: var(--fs-xs);
    white-space: nowrap;
}
.td-nominal {
    text-align: right;
    font-variant-numeric: tabular-nums;
    color: var(--accent);
    font-weight: 600;
    white-space: nowrap;
}
.td-total {
    text-align: right;
    font-variant-numeric: tabular-nums;
    color: var(--text-1);
    font-weight: 800;
    white-space: nowrap;
}
.td-petugas {
    color: var(--text-3);
    font-size: var(--fs-xs);
}
.td-tagihan {
    text-align: right;
    font-weight: 800;
    color: var(--danger);
    font-variant-numeric: tabular-nums;
    white-space: nowrap;
}
.td-center { text-align: center; }
.td-empty {
    text-align: center;
    padding: 48px 24px;
    color: var(--text-3);
}
.td-empty-inner {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
}
.td-empty-inner svg {
    color: var(--border-2);
    opacity: .6;
}
.td-empty-inner span { font-size: var(--fs-sm); }

/* Tfoot */
.spp-table tfoot tr {
    background: var(--bg);
    border-top: 2px solid var(--border-2);
}
.spp-table tfoot td {
    padding: 11px 14px;
    font-weight: 700;
    font-size: var(--fs-sm);
    color: var(--text-1);
}
.spp-table tfoot td.ta-r { text-align: right; }
.spp-tfoot-sum { color: var(--accent) !important; font-size: var(--fs-md) !important; }
.spp-tfoot-red { color: var(--danger) !important; text-align: right; font-size: var(--fs-md) !important; }

/* Badge kelompok */
.badge-kel {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 26px; height: 26px;
    border-radius: 50%;
    font-size: var(--fs-xs);
    font-weight: 800;
    background: oklch(98% 0.030 70);
    color: oklch(35% 0.120 70);
    border: 1px solid oklch(88% 0.060 70);
}
.dark .badge-kel {
    background: oklch(18% 0.050 70);
    color:      oklch(75% 0.120 70);
    border-color: oklch(30% 0.080 70);
}

/* ══════════════════════════════════════════
   ALL LUNAS NOTICE
══════════════════════════════════════════ */
.all-lunas {
    display: flex;
    align-items: center;
    gap: 12px;
    background: var(--success-bg);
    border: 1px solid var(--success-border);
    border-radius: var(--r-lg);
    padding: 16px 20px;
    color: var(--success);
    font-size: var(--fs-sm);
    font-weight: 600;
    margin-top: 1.5rem;
    animation: slide-in var(--dur-mid) var(--ease-out);
}
.all-lunas svg { width: 22px; height: 22px; flex-shrink: 0; }
@keyframes slide-in {
    from { opacity: 0; transform: translateY(-6px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ══════════════════════════════════════════
   FOOTER
══════════════════════════════════════════ */
.spp-footer {
    margin-top: 2rem;
    padding-top: 1rem;
    border-top: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    font-size: var(--fs-xs);
    color: var(--text-3);
}
.spp-footer strong { color: var(--text-2); font-weight: 600; }

/* ══════════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════════ */
@media (max-width: 900px) {
    .spp-stats { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 600px) {
    .spp-page-header { flex-direction: column; }
    .spp-stats { grid-template-columns: 1fr 1fr; gap: 8px; }
    .scard-value { font-size: 1.4rem; }
    .spp-toolbar { width: 100%; }
    .spp-wrap { padding: 1rem .75rem 3rem; }
}
@media (max-width: 420px) {
    .spp-stats { grid-template-columns: 1fr; }
}

/* ══════════════════════════════════════════
   PRINT
══════════════════════════════════════════ */
@media print {
    .spp-ambient { display: none; }
    .spp-toolbar .btn-tampil,
    .spp-toolbar .btn-ghost-tb,
    .spp-toolbar .btn-dl,
    .toolbar-sep { display: none !important; }
    .scard {
        background: #fff !important;
        backdrop-filter: none !important;
        border: 1px solid #e2e8f0 !important;
        box-shadow: none !important;
    }
    .scard:hover { transform: none !important; }
    .spp-table-wrap { break-inside: avoid; }
    .scard { break-inside: avoid; }
}

@media (prefers-reduced-motion: reduce) {
    .scard { transition: none; }
    .scard:hover { transform: none; }
    .scard-bar-fill { transition: none; }
    .all-lunas { animation: none; }
}
</style>
@endpush

@section('content')
<div class="spp-ambient" aria-hidden="true"></div>

<div class="spp-wrap">

    {{-- ══════════════════════════════════════════
         PAGE HEADER
    ══════════════════════════════════════════ --}}
    <div class="spp-page-header">
        <div class="spp-heading">
            <nav class="spp-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <span class="sep" aria-hidden="true">/</span>
                <span>Laporan</span>
                <span class="sep" aria-hidden="true">/</span>
                <span>SPP</span>
            </nav>
            <h1 class="spp-title">Laporan Pembayaran SPP</h1>
            <div class="spp-subtitle">
                <strong>{{ config('sekolah.nama', 'PAUD KB Pelangi') }}</strong>
                <span style="color:var(--border-2)">·</span>
                <span class="periode-badge">
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8"  y1="2" x2="8"  y2="6"/>
                        <line x1="3"  y1="10" x2="21" y2="10"/>
                    </svg>
                    {{ \Carbon\Carbon::create(null, $bulan)->translatedFormat('F') }} {{ $tahun }}
                </span>
            </div>
        </div>

        {{-- Toolbar --}}
        <div class="spp-toolbar" role="group" aria-label="Filter dan aksi">
            <span class="toolbar-label">Periode</span>

            <form method="GET" action="{{ route('laporan.spp') }}"
                  id="form-filter"
                  style="display:flex;gap:7px;align-items:center;flex-wrap:wrap;">
                <select name="bulan" class="tb-select" aria-label="Pilih bulan">
                    @foreach(range(1, 12) as $b)
                        <option value="{{ $b }}" @selected($b == $bulan)>
                            {{ \Carbon\Carbon::create(null, $b)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>

                <select name="tahun" class="tb-select" aria-label="Pilih tahun">
                    @foreach(range(now()->year - 3, now()->year + 1) as $y)
                        <option value="{{ $y }}" @selected($y == $tahun)>{{ $y }}</option>
                    @endforeach
                </select>

                <button type="submit" class="btn-tampil">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                         stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="11" cy="11" r="8"/>
                        <path d="m21 21-4.35-4.35"/>
                    </svg>
                    Tampilkan
                </button>
            </form>

            <div class="toolbar-sep" aria-hidden="true"></div>

            <button type="button" class="btn-ghost-tb" onclick="window.print()" title="Cetak halaman ini">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <polyline points="6 9 6 2 18 2 18 9"/>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                    <rect x="6" y="14" width="12" height="8"/>
                </svg>
                Cetak
            </button>

            <a href="{{ route('laporan.spp.cetak', ['bulan' => $bulan, 'tahun' => $tahun]) }}"
               target="_blank"
               class="btn-dl"
               title="Unduh sebagai PDF">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="7 10 12 15 17 10"/>
                    <line x1="12" y1="15" x2="12" y2="3"/>
                </svg>
                Unduh PDF
            </a>
        </div>
    </div>

    {{-- ══════════════════════════════════════════
         STAT CARDS
    ══════════════════════════════════════════ --}}
    <div class="spp-stats" role="list" aria-label="Ringkasan statistik">

        {{-- Total Siswa Aktif --}}
        <div class="scard scard-navy" role="listitem">
            <div class="scard-icon scard-icon-navy" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
            <div class="scard-label">Total Siswa Aktif</div>
            <div class="scard-value navy">{{ $ringkasan['total_siswa'] }}</div>
            <div class="scard-meta">siswa terdaftar periode ini</div>
        </div>

        {{-- Sudah Lunas --}}
        <div class="scard scard-green" role="listitem">
            <div class="scard-icon scard-icon-green" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                    <polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
            </div>
            <div class="scard-label">Sudah Lunas</div>
            <div class="scard-value green">{{ $ringkasan['sudah_bayar'] }}</div>
            <div class="scard-meta">{{ $ringkasan['persentase_lunas'] }}% dari total siswa</div>
            <div class="scard-bar">
                <div class="scard-bar-fill green"
                     style="width:{{ $ringkasan['persentase_lunas'] }}%"></div>
            </div>
        </div>

        {{-- Masih Tunggakan --}}
        <div class="scard scard-red" role="listitem">
            <div class="scard-icon scard-icon-red" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
            </div>
            <div class="scard-label">Masih Tunggakan</div>
            <div class="scard-value red">{{ $ringkasan['tunggakan'] }}</div>
            <div class="scard-meta">{{ 100 - $ringkasan['persentase_lunas'] }}% belum bayar</div>
            <div class="scard-bar">
                <div class="scard-bar-fill red"
                     style="width:{{ 100 - $ringkasan['persentase_lunas'] }}%"></div>
            </div>
        </div>

        {{-- Total Pemasukan --}}
        <div class="scard scard-amber" role="listitem">
            <div class="scard-icon scard-icon-amber" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="1" x2="12" y2="23"/>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                </svg>
            </div>
            <div class="scard-label">Total Pemasukan</div>
            <div class="scard-value amber">
                Rp {{ number_format($ringkasan['total_pemasukan'], 0, ',', '.') }}
            </div>
            <div class="scard-meta">
                SPP Rp {{ number_format($ringkasan['total_spp'], 0, ',', '.') }}
                + Kebersihan Rp {{ number_format($ringkasan['total_kebersihan'], 0, ',', '.') }}
            </div>
        </div>

    </div>

    {{-- ══════════════════════════════════════════
         TABEL PEMBAYARAN
    ══════════════════════════════════════════ --}}
    <div class="spp-section-header">
        <span class="section-pill section-pill-blue">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/>
                <line x1="1" y1="10" x2="23" y2="10"/>
            </svg>
            Daftar Pembayaran
        </span>
        <span class="section-count">{{ $pembayaran->count() }} transaksi tercatat</span>
    </div>

    <div class="spp-table-wrap">
        <div class="spp-table-scroll">
            <table class="spp-table" aria-label="Tabel pembayaran SPP">
                <thead>
                    <tr>
                        <th class="ta-c" style="width:44px">#</th>
                        <th>Nama Siswa</th>
                        <th style="width:130px">No. Kwitansi</th>
                        <th class="ta-c" style="width:96px">Tgl. Bayar</th>
                        <th class="ta-r" style="width:108px">SPP</th>
                        <th class="ta-r" style="width:108px">Kebersihan</th>
                        <th class="ta-r" style="width:112px">Total</th>
                        <th style="width:130px">Dicatat Oleh</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pembayaran as $i => $p)
                    <tr>
                        <td class="td-no">{{ $i + 1 }}</td>
                        <td class="td-nama">{{ $p->siswa->nama_lengkap ?? $p->siswa->nama ?? '—' }}</td>
                        <td class="td-kwitansi">{{ $p->no_kwitansi }}</td>
                        <td class="td-tgl">{{ \Carbon\Carbon::parse($p->tanggal_bayar)->format('d/m/Y') }}</td>
                        <td class="td-nominal">Rp {{ number_format($p->nominal_spp, 0, ',', '.') }}</td>
                        <td class="td-nominal">Rp {{ number_format($p->nominal_kebersihan, 0, ',', '.') }}</td>
                        <td class="td-total">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                        <td class="td-petugas">{{ $p->dicatatOleh->nama_lengkap ?? $p->dicatatOleh->nama ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="td-empty">
                            <div class="td-empty-inner">
                                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/>
                                    <line x1="1" y1="10" x2="23" y2="10"/>
                                </svg>
                                <span>Belum ada data pembayaran pada periode ini.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>

                @if ($pembayaran->count() > 0)
                <tfoot>
                    <tr>
                        <td colspan="4" style="font-weight:700;color:var(--text-1);">Total Keseluruhan</td>
                        <td class="ta-r" style="font-weight:700;color:var(--accent);font-variant-numeric:tabular-nums;white-space:nowrap;">
                            Rp {{ number_format($ringkasan['total_spp'], 0, ',', '.') }}
                        </td>
                        <td class="ta-r" style="font-weight:700;color:var(--accent);font-variant-numeric:tabular-nums;white-space:nowrap;">
                            Rp {{ number_format($ringkasan['total_kebersihan'], 0, ',', '.') }}
                        </td>
                        <td class="ta-r spp-tfoot-sum" style="white-space:nowrap;">
                            Rp {{ number_format($ringkasan['total_pemasukan'], 0, ',', '.') }}
                        </td>
                        <td style="color:var(--text-3)">—</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

    {{-- ══════════════════════════════════════════
         TABEL TUNGGAKAN
    ══════════════════════════════════════════ --}}
    @if ($tunggakan->count() > 0)

        <div class="spp-section-header">
            <span class="section-pill section-pill-red">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                     stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                    <line x1="12" y1="9"  x2="12"    y2="13"/>
                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
                Daftar Tunggakan
            </span>
            <span class="section-count red">{{ $tunggakan->count() }} siswa belum bayar</span>
        </div>

        <div class="spp-table-wrap">
            <div class="spp-table-scroll">
                <table class="spp-table" aria-label="Tabel tunggakan SPP">
                    <thead class="red-head">
                        <tr>
                            <th class="ta-c" style="width:44px">#</th>
                            <th>Nama Siswa</th>
                            <th class="ta-c" style="width:88px">Kelompok</th>
                            <th style="width:160px">Nama Wali</th>
                            <th style="width:140px">No. HP Wali</th>
                            <th class="ta-r" style="width:128px">Est. Tagihan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tunggakan as $i => $s)
                        <tr>
                            <td class="td-no">{{ $i + 1 }}</td>
                            <td class="td-nama">{{ $s->nama_lengkap ?? $s->nama }}</td>
                            <td class="td-center">
                                <span class="badge-kel">{{ strtoupper($s->kelompok) }}</span>
                            </td>
                            <td>{{ $s->nama_wali ?? '—' }}</td>
                            <td class="td-kwitansi">{{ $s->no_hp_wali ?? '—' }}</td>
                            <td class="td-tagihan">Rp {{ number_format(55000, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" style="font-weight:700;color:var(--text-1);">Total Potensi Tunggakan</td>
                            <td class="spp-tfoot-red">
                                Rp {{ number_format($tunggakan->count() * 55000, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    @else

        <div class="all-lunas" role="status">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                <polyline points="22 4 12 14.01 9 11.01"/>
            </svg>
            <span>
                Semua siswa aktif telah melunasi SPP bulan
                <strong>{{ \Carbon\Carbon::create(null, $bulan)->translatedFormat('F') }} {{ $tahun }}</strong>.
                Luar biasa!
            </span>
        </div>

    @endif

    {{-- ══════════════════════════════════════════
         FOOTER
    ══════════════════════════════════════════ --}}
    <div class="spp-footer">
        <span>
            Data per <strong>{{ now()->translatedFormat('d F Y, H:i') }} WIB</strong>
        </span>
        <span>
            Diakses oleh
            <strong>{{ Auth::user()->nama_lengkap ?? Auth::user()->name }}</strong>
            &nbsp;·&nbsp;
            {{ config('sekolah.nama', 'PAUD KB Pelangi') }}
        </span>
    </div>

</div>
@endsection