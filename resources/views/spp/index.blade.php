@extends('layouts.app')

@section('title', 'Pembayaran SPP — PAUD KB Pelangi')
@section('page-title', 'Pembayaran SPP')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=DM+Serif+Display&display=swap');

    :root {
        --navy: #0f1e3c;
        --navy-mid: #1a3260;
        --blue: #2563eb;
        --blue-light: #3b82f6;
        --blue-pale: #eff6ff;
        --glass-bg: rgba(255,255,255,0.72);
        --glass-border: rgba(37,99,235,0.10);
        --shadow-soft: 0 4px 24px rgba(15,30,60,0.07);
        --shadow-md: 0 8px 40px rgba(15,30,60,0.11);
        --radius: 20px;
        --radius-sm: 12px;
    }

    body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f0f4fb; }

    .spp-wrap { max-width: 1100px; margin: 0 auto; padding: 0 0 60px; }

    /* ── PAGE HEADER ── */
    .page-hero {
        background: linear-gradient(135deg, var(--navy) 0%, var(--navy-mid) 60%, #1e40af 100%);
        border-radius: var(--radius);
        padding: 36px 40px 32px;
        margin-bottom: 28px;
        position: relative;
        overflow: hidden;
    }
    .page-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(ellipse at 80% 50%, rgba(59,130,246,0.18) 0%, transparent 70%);
    }
    .page-hero::after {
        content: '';
        position: absolute;
        top: -60px; right: -60px;
        width: 220px; height: 220px;
        border-radius: 50%;
        background: rgba(59,130,246,0.08);
        border: 1px solid rgba(59,130,246,0.12);
    }
    .hero-content { position: relative; z-index: 1; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; }
    .hero-title { font-family: 'DM Serif Display', serif; font-size: 26px; color: #fff; letter-spacing: 0.2px; margin-bottom: 4px; }
    .hero-sub { font-size: 13.5px; color: rgba(255,255,255,0.6); font-weight: 400; }
    .btn-primary {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 11px 22px;
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: #fff; font-size: 13.5px; font-weight: 600;
        border-radius: var(--radius-sm);
        text-decoration: none;
        box-shadow: 0 4px 16px rgba(59,130,246,0.38);
        transition: all .2s;
        border: none; cursor: pointer;
    }
    .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 24px rgba(59,130,246,0.48); }

    /* ── STATS ── */
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 24px; }
    @media(max-width:700px) { .stats-grid { grid-template-columns: 1fr 1fr; } }
    .stat-card {
        background: var(--glass-bg);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius);
        padding: 22px 20px;
        text-align: center;
        box-shadow: var(--shadow-soft);
        transition: transform .2s, box-shadow .2s;
    }
    .stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
    .stat-card .num { font-size: 28px; font-weight: 700; color: var(--navy); line-height: 1.1; margin-bottom: 5px; }
    .stat-card .lbl { font-size: 11.5px; color: #6b7280; font-weight: 500; letter-spacing: 0.04em; }
    .stat-card.green .num { color: #059669; }
    .stat-card.red .num { color: #dc2626; }
    .stat-card.blue .num { color: var(--blue); font-size: 20px; }
    .stat-card .dot {
        display: inline-block; width: 8px; height: 8px; border-radius: 50%;
        margin-right: 6px; vertical-align: middle;
    }

    /* ── FILTER ── */
    .filter-bar {
        background: var(--glass-bg);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius);
        padding: 18px 22px;
        margin-bottom: 20px;
        box-shadow: var(--shadow-soft);
        display: flex; flex-wrap: wrap; align-items: flex-end; gap: 12px;
    }
    .filter-bar input, .filter-bar select {
        padding: 9px 14px; border: 1px solid #e2e8f0;
        border-radius: var(--radius-sm); font-size: 13px;
        background: #fff; color: var(--navy);
        font-family: inherit; transition: border-color .15s, box-shadow .15s;
        outline: none;
    }
    .filter-bar input:focus, .filter-bar select:focus {
        border-color: var(--blue-light);
        box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
    }
    .filter-bar .search-wrap { position: relative; flex: 1; min-width: 200px; }
    .filter-bar .search-wrap svg { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: #94a3b8; }
    .filter-bar .search-wrap input { padding-left: 36px; width: 100%; }
    .filter-lbl { font-size: 11px; color: #94a3b8; margin-bottom: 4px; font-weight: 500; letter-spacing: 0.03em; }
    .btn-filter {
        padding: 9px 20px; background: var(--navy); color: #fff;
        font-size: 13px; font-weight: 600; border-radius: var(--radius-sm);
        border: none; cursor: pointer; font-family: inherit;
        transition: background .15s;
    }
    .btn-filter:hover { background: var(--navy-mid); }

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
    .spp-table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
    .spp-table thead tr { background: linear-gradient(90deg, #f8faff 0%, #f1f5ff 100%); border-bottom: 1px solid #e8eeff; }
    .spp-table th {
        padding: 13px 18px; text-align: left;
        font-size: 10.5px; font-weight: 700; color: #64748b;
        letter-spacing: 0.07em; text-transform: uppercase;
        white-space: nowrap;
    }
    .spp-table th:not(:first-child) { border-left: 1px solid #e8eeff; }
    .spp-table tbody tr {
        border-bottom: 1px solid #f1f5ff;
        transition: background .15s;
    }
    .spp-table tbody tr:last-child { border-bottom: none; }
    .spp-table tbody tr:hover { background: #f8fbff; }
    .spp-table td { padding: 14px 18px; vertical-align: middle; color: var(--navy); }
    .spp-table td:not(:first-child) { border-left: 1px solid #f1f5ff; }

    .student-name { font-weight: 600; color: var(--navy); margin-bottom: 2px; }
    .student-meta { font-size: 11.5px; color: #94a3b8; }
    .mono-tag { font-family: 'Courier New', monospace; font-size: 11.5px; color: #64748b; background: #f1f5f9; padding: 2px 8px; border-radius: 6px; }
    .amount { font-weight: 600; color: var(--navy); }
    .amount-total { font-weight: 700; color: var(--blue); }

    /* action buttons */
    .act-btn {
        display: inline-flex; align-items: center; justify-content: center;
        width: 32px; height: 32px; border-radius: 9px;
        color: #94a3b8; background: transparent;
        border: none; cursor: pointer; text-decoration: none;
        transition: all .15s;
    }
    .act-btn:hover.view { color: var(--blue); background: var(--blue-pale); }
    .act-btn:hover.print { color: #059669; background: #ecfdf5; }
    .act-btn:hover.del { color: #dc2626; background: #fef2f2; }
    .act-group { display: flex; align-items: center; justify-content: flex-end; gap: 4px; }

    /* empty state */
    .empty-state { padding: 72px 20px; text-align: center; }
    .empty-icon { width: 56px; height: 56px; margin: 0 auto 16px; color: #cbd5e1; }
    .empty-title { font-size: 15px; font-weight: 600; color: #64748b; margin-bottom: 6px; }
    .empty-sub { font-size: 13px; color: #94a3b8; }

    /* pagination */
    .pagination-wrap { padding: 16px 20px; border-top: 1px solid #f1f5ff; }

    /* responsive hidden */
    @media(max-width:640px) { .hidden-sm { display: none !important; } }
    @media(max-width:768px) { .hidden-md { display: none !important; } }
    @media(max-width:1024px) { .hidden-lg { display: none !important; } }
</style>
@endpush

@section('content')
<div class="spp-wrap">

    {{-- Page Hero --}}
    <div class="page-hero">
        <div class="hero-content">
            <div>
                <h1 class="hero-title">Pembayaran SPP</h1>
                <p class="hero-sub">
                    {{ \Carbon\Carbon::create($tahun, $bulan)->translatedFormat('F Y') }} &nbsp;·&nbsp; PAUD KB Pelangi
                </p>
            </div>
            @if(in_array(Auth::user()->role, ['admin', 'bendahara']))
            <a href="{{ route('spp.create') }}" class="btn-primary">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Input Pembayaran
            </a>
            @endif
        </div>
    </div>

    {{-- Stats --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="num">{{ $totalSiswa }}</div>
            <div class="lbl">Total Siswa</div>
        </div>
        <div class="stat-card green">
            <div class="num">{{ $totalBayar }}</div>
            <div class="lbl"><span class="dot" style="background:#059669"></span>Sudah Bayar</div>
        </div>
        <div class="stat-card red">
            <div class="num">{{ $tunggakan }}</div>
            <div class="lbl">
                <a href="{{ route('spp.tunggakan', ['bulan' => $bulan, 'tahun' => $tahun]) }}"
                   style="color:#dc2626; text-decoration:none; font-weight:600;">
                    <span class="dot" style="background:#dc2626"></span>Tunggakan →
                </a>
            </div>
        </div>
        <div class="stat-card blue">
            <div class="num">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</div>
            <div class="lbl">Total Pemasukan</div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="filter-bar">
        <form method="GET" action="{{ route('spp.index') }}" style="display:contents">
            <div class="search-wrap">
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/>
                </svg>
                <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama atau NIS...">
            </div>
            <div>
                <div class="filter-lbl">Bulan</div>
                <select name="bulan">
                    @foreach(range(1,12) as $b)
                    <option value="{{ $b }}" {{ $bulan == $b ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create(null, $b)->translatedFormat('F') }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <div class="filter-lbl">Tahun</div>
                <select name="tahun">
                    @foreach(range(now()->year, now()->year - 3) as $t)
                    <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn-filter">Tampilkan</button>
        </form>
    </div>

    {{-- Table --}}
    <div class="table-card">
        @if($pembayaran->isEmpty())
        <div class="empty-state">
            <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2"
                      d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <p class="empty-title">Belum ada pembayaran tercatat</p>
            @if(in_array(Auth::user()->role, ['admin', 'bendahara']))
            <a href="{{ route('spp.create') }}" class="empty-sub" style="color:#2563eb; font-weight:600; text-decoration:none;">
                + Input pembayaran pertama
            </a>
            @endif
        </div>
        @else
        <div style="overflow-x:auto">
            <table class="spp-table">
                <thead>
                    <tr>
                        <th>Siswa</th>
                        <th class="hidden-sm">No. Kwitansi</th>
                        <th class="hidden-md" style="text-align:right">SPP</th>
                        <th class="hidden-md" style="text-align:right">Kebersihan</th>
                        <th style="text-align:right">Total</th>
                        <th class="hidden-lg">Tgl Bayar</th>
                        <th style="text-align:right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pembayaran as $p)
                    <tr>
                        <td>
                            <div class="student-name">{{ $p->siswa->nama_lengkap }}</div>
                            <div class="student-meta">{{ $p->siswa->nis ?? $p->siswa->kelompok }}</div>
                        </td>
                        <td class="hidden-sm"><span class="mono-tag">{{ $p->no_kwitansi }}</span></td>
                        <td class="hidden-md" style="text-align:right; color:#64748b;">Rp {{ number_format($p->nominal_spp, 0, ',', '.') }}</td>
                        <td class="hidden-md" style="text-align:right; color:#64748b;">Rp {{ number_format($p->nominal_kebersihan, 0, ',', '.') }}</td>
                        <td style="text-align:right"><span class="amount-total">{{ $p->total_rupiah }}</span></td>
                        <td class="hidden-lg" style="color:#64748b; font-size:13px;">{{ $p->tanggal_bayar->translatedFormat('d M Y') }}</td>
                        <td>
                            <div class="act-group">
                                <a href="{{ route('spp.kwitansi', $p) }}" class="act-btn view" title="Lihat Kwitansi">
                                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('spp.cetak', $p) }}" target="_blank" class="act-btn print" title="Cetak PDF">
                                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                    </svg>
                                </a>
                                @if(Auth::user()->isAdmin())
                                <form method="POST" action="{{ route('spp.destroy', $p) }}"
                                      onsubmit="return confirm('Hapus data pembayaran ini?')" style="display:contents">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="act-btn del" title="Hapus">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($pembayaran->hasPages())
        <div class="pagination-wrap">{{ $pembayaran->links() }}</div>
        @endif
        @endif
    </div>

</div>
@endsection