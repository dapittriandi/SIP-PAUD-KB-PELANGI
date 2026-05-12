{{-- resources/views/laporan/spp.blade.php --}}
@extends('layouts.app')

@section('title', 'Laporan SPP — ' . \Carbon\Carbon::create(null, $bulan)->translatedFormat('F') . ' ' . $tahun)

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Lora:wght@600;700&display=swap" rel="stylesheet">
<style>
    /* ─────────────────────────────────────────
       BASE
    ───────────────────────────────────────── */
    .lspp-wrap {
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: #1a2332;
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 1.5rem 3rem;
    }

    /* ─────────────────────────────────────────
       PAGE HEADER
    ───────────────────────────────────────── */
    .lspp-page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }
    .lspp-breadcrumb {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: #64748b;
        margin-bottom: 8px;
    }
    .lspp-breadcrumb a {
        color: #64748b;
        text-decoration: none;
    }
    .lspp-breadcrumb a:hover { color: #1e3a5f; }
    .lspp-breadcrumb .sep { color: #cbd5e1; }
    .lspp-title {
        font-family: 'Lora', serif;
        font-size: 28px;
        font-weight: 700;
        color: #0f1f35;
        line-height: 1.2;
        letter-spacing: -0.4px;
    }
    .lspp-subtitle {
        font-size: 13px;
        color: #64748b;
        margin-top: 5px;
    }
    .lspp-subtitle strong {
        color: #1e3a5f;
        font-weight: 600;
    }

    /* ─────────────────────────────────────────
       TOOLBAR (filter + actions)
    ───────────────────────────────────────── */
    .lspp-toolbar {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    .lspp-toolbar .toolbar-label {
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-right: 2px;
    }
    .lspp-toolbar select {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 13px;
        font-weight: 500;
        padding: 8px 12px;
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        background: #fff;
        color: #1a2332;
        cursor: pointer;
        transition: border-color .15s, box-shadow .15s;
    }
    .lspp-toolbar select:focus {
        outline: none;
        border-color: #1e3a5f;
        box-shadow: 0 0 0 3px rgba(30,58,95,.1);
    }
    .btn-tampil {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 13px;
        font-weight: 600;
        padding: 8px 18px;
        border-radius: 8px;
        border: none;
        background: #1e3a5f;
        color: #fff;
        cursor: pointer;
        transition: background .15s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-tampil:hover { background: #162d4a; }
    .btn-tampil svg { width: 14px; height: 14px; }

    /* separator */
    .toolbar-sep {
        width: 1px;
        height: 28px;
        background: #e2e8f0;
        margin: 0 4px;
    }

    /* cetak & download buttons */
    .btn-cetak, .btn-download {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 13px;
        font-weight: 600;
        padding: 8px 16px;
        border-radius: 8px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: background .15s, border-color .15s;
        text-decoration: none;
    }
    .btn-cetak {
        border: 1px solid #cbd5e1;
        background: #fff;
        color: #1a2332;
    }
    .btn-cetak:hover { background: #f1f5f9; border-color: #94a3b8; }
    .btn-download {
        border: 1px solid #0f6e56;
        background: #0f6e56;
        color: #fff;
    }
    .btn-download:hover { background: #085041; border-color: #085041; }
    .btn-cetak svg, .btn-download svg { width: 15px; height: 15px; flex-shrink: 0; }

    /* ─────────────────────────────────────────
       STAT CARDS
    ───────────────────────────────────────── */
    .lspp-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 12px;
        margin-bottom: 2rem;
    }
    .stat-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 18px 20px;
        position: relative;
        overflow: hidden;
    }
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        border-radius: 12px 12px 0 0;
    }
    .stat-card.card-navy::before  { background: #1e3a5f; }
    .stat-card.card-green::before { background: #0f6e56; }
    .stat-card.card-red::before   { background: #991b1b; }
    .stat-card.card-amber::before { background: #854f0b; }

    .stat-icon {
        width: 36px; height: 36px;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 12px;
    }
    .stat-icon.navy  { background: #e6f1fb; color: #1e3a5f; }
    .stat-icon.green { background: #e1f5ee; color: #0f6e56; }
    .stat-icon.red   { background: #fcebeb; color: #991b1b; }
    .stat-icon.amber { background: #faeeda; color: #854f0b; }
    .stat-icon svg   { width: 18px; height: 18px; }

    .stat-label {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #94a3b8;
        margin-bottom: 4px;
    }
    .stat-value {
        font-size: 30px;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 4px;
    }
    .stat-value.navy  { color: #1e3a5f; }
    .stat-value.green { color: #0f6e56; }
    .stat-value.red   { color: #991b1b; }
    .stat-value.amber { color: #854f0b; font-size: 20px; }
    .stat-meta {
        font-size: 12px;
        color: #94a3b8;
    }
    .stat-bar {
        margin-top: 10px;
        height: 4px;
        background: #f1f5f9;
        border-radius: 4px;
        overflow: hidden;
    }
    .stat-bar-fill {
        height: 100%;
        border-radius: 4px;
        transition: width .4s ease;
    }

    /* ─────────────────────────────────────────
       SECTION HEADER
    ───────────────────────────────────────── */
    .lspp-section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
        margin-top: 2rem;
    }
    .lspp-section-title {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .section-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        padding: 5px 12px;
        border-radius: 20px;
    }
    .section-pill.navy  { background: #e6f1fb; color: #0c447c; }
    .section-pill.red   { background: #fcebeb; color: #791f1f; }
    .section-pill svg   { width: 13px; height: 13px; }

    .section-count {
        font-size: 12px;
        color: #94a3b8;
        font-weight: 500;
    }

    /* ─────────────────────────────────────────
       TABEL
    ───────────────────────────────────────── */
    .lspp-table-wrap {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
    }
    .lspp-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }
    .lspp-table thead tr {
        background: #0f1f35;
    }
    .lspp-table thead th {
        padding: 11px 14px;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.4px;
        text-transform: uppercase;
        color: #94a3b8;
        text-align: left;
        white-space: nowrap;
    }
    .lspp-table thead th.text-right { text-align: right; }
    .lspp-table thead th.text-center { text-align: center; }

    .lspp-table thead.red-head tr { background: #450a0a; }

    .lspp-table tbody tr {
        border-top: 1px solid #f1f5f9;
        transition: background .1s;
    }
    .lspp-table tbody tr:hover { background: #f8fafc; }
    .lspp-table tbody td {
        padding: 10px 14px;
        color: #334155;
        vertical-align: middle;
    }
    .lspp-table tbody td.td-no {
        color: #94a3b8;
        font-size: 12px;
        text-align: center;
        width: 40px;
    }
    .lspp-table tbody td.td-nama {
        font-weight: 600;
        color: #0f1f35;
    }
    .lspp-table tbody td.td-kwitansi {
        font-family: 'Courier New', monospace;
        font-size: 11.5px;
        color: #64748b;
    }
    .lspp-table tbody td.td-tgl {
        text-align: center;
        color: #64748b;
        font-size: 12.5px;
        white-space: nowrap;
    }
    .lspp-table tbody td.td-nominal {
        text-align: right;
        font-variant-numeric: tabular-nums;
        color: #1e3a5f;
        font-weight: 500;
    }
    .lspp-table tbody td.td-total {
        text-align: right;
        font-variant-numeric: tabular-nums;
        color: #0f1f35;
        font-weight: 700;
    }
    .lspp-table tbody td.td-petugas {
        color: #64748b;
        font-size: 12.5px;
    }
    .lspp-table tbody td.td-tagihan {
        text-align: right;
        font-weight: 700;
        color: #991b1b;
    }
    .lspp-table tbody td.td-center { text-align: center; }
    .lspp-table tbody td.td-empty {
        text-align: center;
        padding: 32px;
        color: #94a3b8;
        font-size: 13px;
    }

    .lspp-table tfoot tr {
        background: #f8fafc;
        border-top: 2px solid #e2e8f0;
    }
    .lspp-table tfoot td {
        padding: 11px 14px;
        font-weight: 700;
        font-size: 13px;
        color: #0f1f35;
    }
    .lspp-table tfoot td.text-right { text-align: right; }
    .lspp-table tfoot td.td-total-sum { color: #1e3a5f; }
    .lspp-table tfoot td.td-tunggakan-sum { color: #991b1b; text-align: right; }

    /* ─────────────────────────────────────────
       BADGE KELOMPOK
    ───────────────────────────────────────── */
    .badge-kel {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px; height: 28px;
        border-radius: 50%;
        font-size: 12px;
        font-weight: 700;
        background: #faeeda;
        color: #633806;
    }

    /* ─────────────────────────────────────────
       ALL LUNAS NOTICE
    ───────────────────────────────────────── */
    .all-lunas-notice {
        display: flex;
        align-items: center;
        gap: 12px;
        background: #e1f5ee;
        border: 1px solid #5dcaa5;
        border-radius: 10px;
        padding: 14px 18px;
        color: #085041;
        font-size: 13.5px;
        font-weight: 500;
        margin-top: 2rem;
    }
    .all-lunas-notice svg { width: 22px; height: 22px; flex-shrink: 0; }

    /* ─────────────────────────────────────────
       FOOTER
    ───────────────────────────────────────── */
    .lspp-footer {
        margin-top: 2.5rem;
        padding-top: 1rem;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        font-size: 12px;
        color: #94a3b8;
    }
    .lspp-footer strong { color: #64748b; font-weight: 600; }

    /* ─────────────────────────────────────────
       PRINT
    ───────────────────────────────────────── */
    @media print {
        .lspp-toolbar .btn-cetak,
        .lspp-toolbar .btn-download,
        .lspp-toolbar .btn-tampil,
        .lspp-toolbar .toolbar-sep { display: none !important; }
        .lspp-page-header { border-bottom: 2px solid #0f1f35; }
        .stat-card { break-inside: avoid; }
        .lspp-table-wrap { break-inside: avoid; }
    }
</style>
@endpush

@section('content')
<div class="lspp-wrap">

    {{-- ═══════════════════════════════════════
         PAGE HEADER
    ═══════════════════════════════════════ --}}
    <div class="lspp-page-header">
        <div>
            <div class="lspp-breadcrumb">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <span class="sep">/</span>
                <span>Laporan</span>
                <span class="sep">/</span>
                <span>SPP</span>
            </div>
            <h1 class="lspp-title">Laporan Pembayaran SPP</h1>
            <p class="lspp-subtitle">
                <strong>{{ config('sekolah.nama', 'PAUD KB Pelangi') }}</strong>
                &nbsp;·&nbsp;
                Periode:
                <strong>{{ \Carbon\Carbon::create(null, $bulan)->translatedFormat('F') }} {{ $tahun }}</strong>
            </p>
        </div>

        {{-- Toolbar: filter + aksi --}}
        <div class="lspp-toolbar">
            <span class="toolbar-label">Periode</span>

            <form method="GET" action="{{ route('laporan.spp') }}"
                  style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;"
                  id="form-filter">
                <select name="bulan" aria-label="Pilih bulan">
                    @foreach(range(1, 12) as $b)
                        <option value="{{ $b }}" @selected($b == $bulan)>
                            {{ \Carbon\Carbon::create(null, $b)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>

                <select name="tahun" aria-label="Pilih tahun">
                    @foreach(range(now()->year - 3, now()->year + 1) as $y)
                        <option value="{{ $y }}" @selected($y == $tahun)>{{ $y }}</option>
                    @endforeach
                </select>

                <button type="submit" class="btn-tampil">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                    </svg>
                    Tampilkan
                </button>
            </form>

            <div class="toolbar-sep" aria-hidden="true"></div>

            {{-- Tombol Cetak --}}
            <button type="button" class="btn-cetak" onclick="window.print()" title="Cetak halaman ini">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <polyline points="6 9 6 2 18 2 18 9"/>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                    <rect x="6" y="14" width="12" height="8"/>
                </svg>
                Cetak
            </button>

            {{-- Tombol Unduh PDF --}}
            <a href="{{ route('laporan.spp', ['bulan' => $bulan, 'tahun' => $tahun, 'format' => 'pdf']) }}"
               class="btn-download"
               title="Unduh sebagai PDF">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="7 10 12 15 17 10"/>
                    <line x1="12" y1="15" x2="12" y2="3"/>
                </svg>
                Unduh PDF
            </a>
        </div>
    </div>

    {{-- ═══════════════════════════════════════
         STAT CARDS
    ═══════════════════════════════════════ --}}
    <div class="lspp-stats">

        {{-- Total Siswa --}}
        <div class="stat-card card-navy">
            <div class="stat-icon navy">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
            <div class="stat-label">Total Siswa Aktif</div>
            <div class="stat-value navy">{{ $ringkasan['total_siswa'] }}</div>
            <div class="stat-meta">siswa terdaftar</div>
        </div>

        {{-- Sudah Lunas --}}
        <div class="stat-card card-green">
            <div class="stat-icon green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                    <polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
            </div>
            <div class="stat-label">Sudah Lunas</div>
            <div class="stat-value green">{{ $ringkasan['sudah_bayar'] }}</div>
            <div class="stat-meta">{{ $ringkasan['persentase_lunas'] }}% dari total siswa</div>
            <div class="stat-bar">
                <div class="stat-bar-fill" style="width: {{ $ringkasan['persentase_lunas'] }}%; background: #0f6e56;"></div>
            </div>
        </div>

        {{-- Tunggakan --}}
        <div class="stat-card card-red">
            <div class="stat-icon red">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
            </div>
            <div class="stat-label">Masih Tunggakan</div>
            <div class="stat-value red">{{ $ringkasan['tunggakan'] }}</div>
            <div class="stat-meta">{{ 100 - $ringkasan['persentase_lunas'] }}% belum bayar</div>
            <div class="stat-bar">
                <div class="stat-bar-fill" style="width: {{ 100 - $ringkasan['persentase_lunas'] }}%; background: #991b1b;"></div>
            </div>
        </div>

        {{-- Total Pemasukan --}}
        <div class="stat-card card-amber">
            <div class="stat-icon amber">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <line x1="12" y1="1" x2="12" y2="23"/>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                </svg>
            </div>
            <div class="stat-label">Total Pemasukan</div>
            <div class="stat-value amber">Rp {{ number_format($ringkasan['total_pemasukan'], 0, ',', '.') }}</div>
            <div class="stat-meta">
                SPP Rp {{ number_format($ringkasan['total_spp'], 0, ',', '.') }}
                + Kebersihan Rp {{ number_format($ringkasan['total_kebersihan'], 0, ',', '.') }}
            </div>
        </div>

    </div>

    {{-- ═══════════════════════════════════════
         TABEL PEMBAYARAN
    ═══════════════════════════════════════ --}}
    <div class="lspp-section-header">
        <div class="lspp-section-title">
            <span class="section-pill navy">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/>
                    <line x1="1" y1="10" x2="23" y2="10"/>
                </svg>
                Daftar Pembayaran
            </span>
        </div>
        <span class="section-count">{{ $pembayaran->count() }} transaksi tercatat</span>
    </div>

    <div class="lspp-table-wrap">
        <table class="lspp-table">
            <thead>
                <tr>
                    <th class="text-center" style="width:44px">#</th>
                    <th>Nama Siswa</th>
                    <th style="width:130px">No. Kwitansi</th>
                    <th class="text-center" style="width:96px">Tgl. Bayar</th>
                    <th class="text-right" style="width:100px">SPP</th>
                    <th class="text-right" style="width:100px">Kebersihan</th>
                    <th class="text-right" style="width:108px">Total</th>
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
                        Belum ada data pembayaran pada periode ini.
                    </td>
                </tr>
                @endforelse
            </tbody>

            @if ($pembayaran->count() > 0)
            <tfoot>
                <tr>
                    <td colspan="4">Total Keseluruhan</td>
                    <td class="text-right">Rp {{ number_format($ringkasan['total_spp'], 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($ringkasan['total_kebersihan'], 0, ',', '.') }}</td>
                    <td class="text-right td-total-sum">Rp {{ number_format($ringkasan['total_pemasukan'], 0, ',', '.') }}</td>
                    <td>—</td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>

    {{-- ═══════════════════════════════════════
         TABEL TUNGGAKAN
    ═══════════════════════════════════════ --}}
    @if ($tunggakan->count() > 0)

        <div class="lspp-section-header">
            <div class="lspp-section-title">
                <span class="section-pill red">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                        <line x1="12" y1="9" x2="12" y2="13"/>
                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                    Daftar Tunggakan
                </span>
            </div>
            <span class="section-count" style="color:#991b1b;">{{ $tunggakan->count() }} siswa belum bayar</span>
        </div>

        <div class="lspp-table-wrap">
            <table class="lspp-table">
                <thead class="red-head">
                    <tr>
                        <th class="text-center" style="width:44px">#</th>
                        <th>Nama Siswa</th>
                        <th class="text-center" style="width:90px">Kelompok</th>
                        <th style="width:160px">Nama Wali</th>
                        <th style="width:140px">No. HP Wali</th>
                        <th class="text-right" style="width:120px">Est. Tagihan</th>
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
                        <td colspan="5">Total Potensi Tunggakan</td>
                        <td class="td-tunggakan-sum">
                            Rp {{ number_format($tunggakan->count() * 55000, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

    @else

        <div class="all-lunas-notice">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
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

    {{-- ═══════════════════════════════════════
         FOOTER
    ═══════════════════════════════════════ --}}
    <div class="lspp-footer">
        <span>
            Data per <strong>{{ now()->translatedFormat('d F Y, H:i') }} WIB</strong>
        </span>
        <span>
            Diakses oleh <strong>{{ Auth::user()->nama_lengkap ?? Auth::user()->name }}</strong>
            &nbsp;·&nbsp; {{ config('sekolah.nama', 'PAUD KB Pelangi') }}
        </span>
    </div>

</div>
@endsection