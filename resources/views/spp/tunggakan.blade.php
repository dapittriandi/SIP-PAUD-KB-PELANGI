@extends('layouts.app')

@section('title', 'Tunggakan SPP — PAUD KB Pelangi')
@section('page-title', 'Daftar Tunggakan SPP')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=DM+Serif+Display&display=swap');

    :root {
        --navy: #0f1e3c;
        --navy-mid: #1a3260;
        --blue: #2563eb;
        --blue-light: #3b82f6;
        --blue-pale: #eff6ff;
        --red: #dc2626;
        --glass-bg: rgba(255,255,255,0.80);
        --glass-border: rgba(37,99,235,0.10);
        --shadow-soft: 0 4px 24px rgba(15,30,60,0.07);
        --shadow-md: 0 8px 40px rgba(15,30,60,0.11);
        --radius: 20px;
        --radius-sm: 12px;
    }

    body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f0f4fb; }
    .tung-wrap { max-width: 1000px; margin: 0 auto; padding-bottom: 60px; }

    /* ── BREADCRUMB ── */
    .breadcrumb {
        display: flex; align-items: center; gap: 8px;
        font-size: 13px; color: #94a3b8; margin-bottom: 24px;
    }
    .breadcrumb a { color: #64748b; text-decoration: none; }
    .breadcrumb a:hover { color: var(--blue); }

    /* ── HERO ── */
    .page-hero {
        background: linear-gradient(135deg, var(--navy) 0%, var(--navy-mid) 60%, #1e40af 100%);
        border-radius: var(--radius);
        padding: 32px 36px 28px;
        margin-bottom: 24px;
        position: relative; overflow: hidden;
    }
    .page-hero::before {
        content: '';
        position: absolute; inset: 0;
        background: radial-gradient(ellipse at 80% 50%, rgba(59,130,246,0.18) 0%, transparent 70%);
    }
    .hero-inner { position: relative; z-index: 1; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; }
    .hero-left {}
    .hero-title { font-family: 'DM Serif Display', serif; font-size: 24px; color: #fff; margin-bottom: 4px; }
    .hero-sub { font-size: 13px; color: rgba(255,255,255,0.55); }
    .badge-count {
        display: flex; align-items: center; gap: 10px;
        background: rgba(220,38,38,0.18);
        border: 1px solid rgba(220,38,38,0.35);
        border-radius: var(--radius-sm);
        padding: 12px 18px;
    }
    .badge-count svg { color: #fca5a5; }
    .badge-count span { font-size: 14px; font-weight: 700; color: #fff; }

    /* ── FILTER CARD ── */
    .filter-card {
        background: var(--glass-bg);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius);
        padding: 20px 24px;
        margin-bottom: 20px;
        box-shadow: var(--shadow-soft);
        display: flex; flex-wrap: wrap; align-items: flex-end; gap: 12px;
    }
    .filter-card input, .filter-card select {
        padding: 9px 14px;
        border: 1.5px solid #e2e8f0;
        border-radius: var(--radius-sm);
        font-size: 13px; font-family: inherit;
        color: var(--navy); background: #fff;
        transition: all .15s; outline: none;
    }
    .filter-card input:focus, .filter-card select:focus {
        border-color: var(--blue-light);
        box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
    }
    .filter-lbl { font-size: 11px; color: #94a3b8; font-weight: 600; letter-spacing: 0.03em; margin-bottom: 5px; }
    .search-wrap { position: relative; flex: 1; min-width: 200px; }
    .search-wrap svg { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: #94a3b8; pointer-events: none; }
    .search-wrap input { padding-left: 36px; width: 100%; }

    .btn-filter {
        padding: 9px 22px;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #fff; font-size: 13px; font-weight: 700;
        border-radius: var(--radius-sm); border: none;
        cursor: pointer; font-family: inherit;
        box-shadow: 0 3px 12px rgba(37,99,235,0.3);
        transition: all .15s;
    }
    .btn-filter:hover { transform: translateY(-1px); }
    .btn-reset {
        padding: 9px 18px;
        border: 1.5px solid #e2e8f0; background: transparent;
        color: #64748b; font-size: 13px; font-weight: 600;
        border-radius: var(--radius-sm); text-decoration: none;
        font-family: inherit; cursor: pointer;
        transition: all .15s;
    }
    .btn-reset:hover { background: #f8fafc; }

    /* ── TABLE ── */
    .table-card {
        background: var(--glass-bg);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius);
        overflow: hidden;
        box-shadow: var(--shadow-soft);
    }
    .tung-table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
    .tung-table thead tr {
        background: linear-gradient(90deg, #f8faff 0%, #f1f5ff 100%);
        border-bottom: 1px solid #e8eeff;
    }
    .tung-table th {
        padding: 13px 18px; text-align: left;
        font-size: 10.5px; font-weight: 700; color: #64748b;
        letter-spacing: 0.07em; text-transform: uppercase; white-space: nowrap;
    }
    .tung-table tbody tr {
        border-bottom: 1px solid #f1f5ff;
        transition: background .15s;
    }
    .tung-table tbody tr:last-child { border-bottom: none; }
    .tung-table tbody tr:hover { background: #f8fbff; }
    .tung-table td { padding: 14px 18px; vertical-align: middle; color: var(--navy); }

    .num-cell { color: #94a3b8; font-size: 12.5px; }
    .nis-cell { font-family: 'Courier New', monospace; font-size: 12px; color: #64748b; background: #f1f5f9; padding: 2px 8px; border-radius: 6px; display: inline-block; }
    .nama-cell { font-weight: 600; color: var(--navy); }
    .kelompok-badge { display: inline-block; background: var(--blue-pale); color: #1d4ed8; padding: 3px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 600; }
    .hp-cell { font-family: 'Courier New', monospace; font-size: 12px; color: #64748b; }

    .btn-bayar {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 7px 14px;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #fff; font-size: 12px; font-weight: 700;
        border-radius: 9px; text-decoration: none;
        box-shadow: 0 2px 8px rgba(37,99,235,0.25);
        transition: all .15s; white-space: nowrap;
    }
    .btn-bayar:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(37,99,235,0.38); }

    /* empty state */
    .empty-state { padding: 72px 20px; text-align: center; }
    .empty-icon { width: 56px; height: 56px; margin: 0 auto 16px; color: #86efac; }
    .empty-title { font-size: 15.5px; font-weight: 700; color: #374151; margin-bottom: 6px; }
    .empty-sub { font-size: 13px; color: #94a3b8; }

    /* pagination */
    .pagination-wrap { padding: 16px 20px; border-top: 1px solid #f1f5ff; }

    /* info bar */
    .info-bar {
        background: linear-gradient(90deg, #fffbeb 0%, #fef9ec 100%);
        border: 1px solid #fde68a;
        border-radius: var(--radius);
        padding: 16px 22px;
        display: flex; align-items: flex-start; gap: 12px;
        margin-top: 20px;
    }
    .info-bar svg { flex-shrink: 0; color: #d97706; margin-top: 1px; }
    .info-bar .ib-title { font-size: 13px; font-weight: 700; color: #92400e; margin-bottom: 4px; }
    .info-bar .ib-body { font-size: 13px; color: #78350f; }
    .info-bar .ib-body strong { font-weight: 700; }
</style>
@endpush

@section('content')
<div class="tung-wrap">

    {{-- Breadcrumb --}}
    <div class="breadcrumb">
        <a href="{{ route('spp.index') }}">Pembayaran SPP</a>
        <span style="color:#cbd5e1">›</span>
        <span style="color:#1e293b; font-weight:600;">Tunggakan</span>
    </div>

    {{-- Hero --}}
    <div class="page-hero">
        <div class="hero-inner">
            <div class="hero-left">
                <div class="hero-title">Daftar Tunggakan SPP</div>
                <div class="hero-sub">
                    Siswa belum bayar bulan
                    <strong style="color:rgba(255,255,255,0.85)">
                        {{ \Carbon\Carbon::create(null, $bulan)->translatedFormat('F') }} {{ $tahun }}
                    </strong>
                </div>
            </div>
            <div class="badge-count">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
                <span>{{ $totalTunggakan }} siswa belum bayar</span>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="filter-card">
        <form method="GET" action="{{ route('spp.tunggakan') }}" style="display:contents">
            <div>
                <div class="filter-lbl">Bulan</div>
                <select name="bulan">
                    @foreach(range(1, 12) as $b)
                    <option value="{{ $b }}" @selected($b == $bulan)>
                        {{ \Carbon\Carbon::create(null, $b)->translatedFormat('F') }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <div class="filter-lbl">Tahun</div>
                <select name="tahun">
                    @foreach(range(now()->year, now()->year - 3, -1) as $y)
                    <option value="{{ $y }}" @selected($y == $tahun)>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div class="search-wrap">
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/>
                </svg>
                <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama / NIS...">
            </div>
            <button type="submit" class="btn-filter">Tampilkan</button>
            @if(request()->hasAny(['cari']))
            <a href="{{ route('spp.tunggakan', ['bulan' => $bulan, 'tahun' => $tahun]) }}" class="btn-reset">Reset</a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="table-card">
        @if($tunggakan->isEmpty())
        <div class="empty-state">
            <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="empty-title">Semua siswa sudah lunas!</p>
            <p class="empty-sub">
                Tidak ada tunggakan untuk bulan
                {{ \Carbon\Carbon::create(null, $bulan)->translatedFormat('F') }} {{ $tahun }}
            </p>
        </div>
        @else
        <div style="overflow-x:auto">
            <table class="tung-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
                        <th>Kelompok</th>
                        <th>Wali / Orang Tua</th>
                        <th>No HP</th>
                        <th style="text-align:right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tunggakan as $i => $siswa)
                    <tr>
                        <td class="num-cell">{{ $tunggakan->firstItem() + $i }}</td>
                        <td><span class="nis-cell">{{ $siswa->nis ?? '—' }}</span></td>
                        <td><span class="nama-cell">{{ $siswa->nama_lengkap }}</span></td>
                        <td><span class="kelompok-badge">{{ $siswa->kelompok }}</span></td>
                        <td style="color:#374151">{{ $siswa->nama_wali ?? $siswa->nama_ibu ?? $siswa->nama_ayah ?? '—' }}</td>
                        <td><span class="hp-cell">{{ $siswa->kontak_utama ?? '—' }}</span></td>
                        <td style="text-align:right">
                            <a href="{{ route('spp.create', ['siswa_id' => $siswa->id]) }}" class="btn-bayar">
                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                </svg>
                                Bayar
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($tunggakan->hasPages())
        <div class="pagination-wrap">{{ $tunggakan->links() }}</div>
        @endif
        @endif
    </div>

    {{-- Info tagihan --}}
    <div class="info-bar">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div>
            <p class="ib-title">Tagihan SPP per siswa</p>
            <p class="ib-body">
                SPP Bulanan <strong>Rp 50.000</strong>
                &nbsp;+&nbsp; Biaya Kebersihan <strong>Rp 5.000</strong>
                &nbsp;=&nbsp; Total <strong>Rp 55.000</strong>
            </p>
        </div>
    </div>

</div>
@endsection