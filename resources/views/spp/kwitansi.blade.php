@extends('layouts.app')

@section('title', 'Kwitansi ' . $spp->no_kwitansi . ' — PAUD KB Pelangi')
@section('page-title', 'Kwitansi Pembayaran')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=DM+Serif+Display&display=swap');

    :root {
        --navy: #0f1e3c;
        --navy-mid: #1a3260;
        --blue: #2563eb;
        --blue-light: #3b82f6;
        --blue-pale: #eff6ff;
        --glass-bg: rgba(255,255,255,0.82);
        --glass-border: rgba(37,99,235,0.10);
        --shadow-soft: 0 4px 24px rgba(15,30,60,0.07);
        --shadow-md: 0 8px 40px rgba(15,30,60,0.13);
        --radius: 20px;
        --radius-sm: 12px;
    }

    body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f0f4fb; }
    .kwit-wrap { max-width: 640px; margin: 0 auto; padding-bottom: 60px; }

    /* ── BREADCRUMB ── */
    .breadcrumb {
        display: flex; align-items: center; gap: 8px;
        font-size: 13px; color: #94a3b8; margin-bottom: 24px;
    }
    .breadcrumb a { color: #64748b; text-decoration: none; }
    .breadcrumb a:hover { color: var(--blue); }

    /* ── SUCCESS ALERT ── */
    .alert-success {
        display: flex; align-items: center; gap: 12px;
        padding: 14px 18px;
        background: #f0fdf4; border: 1px solid #bbf7d0;
        border-radius: var(--radius-sm);
        font-size: 13.5px; color: #15803d;
        margin-bottom: 20px;
    }

    /* ── KWITANSI CARD ── */
    .kwit-card {
        background: var(--glass-bg);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius);
        overflow: hidden;
        box-shadow: var(--shadow-md);
        margin-bottom: 20px;
    }

    /* Header gradient */
    .kwit-header {
        background: linear-gradient(135deg, var(--navy) 0%, var(--navy-mid) 55%, #1e40af 100%);
        padding: 28px 32px;
        position: relative; overflow: hidden;
    }
    .kwit-header::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(ellipse at 85% 50%, rgba(59,130,246,0.22) 0%, transparent 65%);
    }
    .kwit-header::after {
        content: '';
        position: absolute;
        top: -50px; right: -50px;
        width: 180px; height: 180px;
        border-radius: 50%;
        border: 1px solid rgba(255,255,255,0.08);
        background: rgba(59,130,246,0.06);
    }
    .kwit-header-inner { position: relative; z-index: 1; display: flex; align-items: flex-start; justify-content: space-between; }
    .kwit-school { font-family: 'DM Serif Display', serif; font-size: 22px; color: #fff; margin-bottom: 3px; }
    .kwit-type { font-size: 12.5px; color: rgba(255,255,255,0.55); font-weight: 400; }
    .kwit-no-wrap { text-align: right; }
    .kwit-no-lbl { font-size: 10px; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 4px; }
    .kwit-no-val { font-family: 'Courier New', monospace; font-size: 13.5px; font-weight: 700; color: #fff; background: rgba(255,255,255,0.12); padding: 4px 10px; border-radius: 7px; border: 1px solid rgba(255,255,255,0.15); }

    /* Body */
    .kwit-body { padding: 28px 32px; }

    /* Info grid */
    .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px 24px; margin-bottom: 24px; }
    .info-item {}
    .info-lbl { font-size: 10.5px; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 4px; }
    .info-val { font-size: 14px; font-weight: 600; color: var(--navy); }

    /* Divider */
    .dashed-divider { border: none; border-top: 1.5px dashed #e2e8f0; margin: 20px 0; }

    /* Section title */
    .section-ttl { font-size: 10.5px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 14px; }

    /* Rincian rows */
    .rincian-row {
        display: flex; justify-content: space-between; align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #f1f5ff;
    }
    .rincian-row:last-child { border-bottom: none; }
    .rincian-lbl { font-size: 13.5px; color: #374151; }
    .rincian-val { font-size: 13.5px; font-weight: 600; color: var(--navy); }

    /* Total block */
    .total-block {
        background: linear-gradient(135deg, var(--navy) 0%, #1a3a6e 100%);
        border-radius: var(--radius-sm);
        padding: 16px 20px;
        display: flex; align-items: center; justify-content: space-between;
        margin-top: 14px;
        position: relative; overflow: hidden;
    }
    .total-block::after {
        content: ''; position: absolute; right: -20px; top: -20px;
        width: 100px; height: 100px; border-radius: 50%;
        background: rgba(59,130,246,0.10);
    }
    .total-block .t-lbl { font-size: 13px; font-weight: 700; color: rgba(255,255,255,0.7); letter-spacing: 0.06em; text-transform: uppercase; }
    .total-block .t-val { font-size: 22px; font-weight: 800; color: #fff; position: relative; z-index: 1; letter-spacing: -0.3px; }

    /* Footer info */
    .footer-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .badge-lunas {
        display: inline-flex; align-items: center; gap: 6px;
        background: #dcfce7; color: #166534;
        border: 1px solid #86efac;
        border-radius: 30px; padding: 5px 13px;
        font-size: 11.5px; font-weight: 700; letter-spacing: 0.04em;
    }
    .recorded-box {
        background: #f8faff; border: 1px solid #dbeafe;
        border-radius: var(--radius-sm); padding: 12px 16px;
        display: flex; align-items: center; gap: 10px; margin-top: 16px;
    }
    .recorded-box p { font-size: 12.5px; color: #1e40af; font-weight: 500; }

    /* ── ACTION BUTTONS ── */
    .action-row { display: flex; gap: 12px; flex-wrap: wrap; }
    .btn-primary {
        flex: 1; min-width: 160px;
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        padding: 13px 22px;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #fff; font-size: 13.5px; font-weight: 700;
        border-radius: var(--radius-sm); text-decoration: none;
        box-shadow: 0 4px 16px rgba(37,99,235,0.35);
        transition: all .2s;
    }
    .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 24px rgba(37,99,235,0.45); }
    .btn-secondary {
        flex: 1; min-width: 160px;
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        padding: 13px 22px;
        background: var(--glass-bg);
        backdrop-filter: blur(12px);
        border: 1.5px solid #e2e8f0;
        color: var(--navy); font-size: 13.5px; font-weight: 600;
        border-radius: var(--radius-sm); text-decoration: none;
        transition: all .15s;
    }
    .btn-secondary:hover { background: #f1f5ff; border-color: #bfdbfe; }
    .btn-ghost {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        padding: 13px 20px;
        border: 1.5px solid #e2e8f0; background: transparent;
        color: #64748b; font-size: 13.5px; font-weight: 500;
        border-radius: var(--radius-sm); text-decoration: none;
        transition: all .15s;
    }
    .btn-ghost:hover { background: #f8fafc; }
</style>
@endpush

@section('content')
<div class="kwit-wrap">

    {{-- Breadcrumb --}}
    <div class="breadcrumb">
        <a href="{{ route('spp.index') }}">Pembayaran SPP</a>
        <span style="color:#cbd5e1">›</span>
        <span style="color:#1e293b; font-weight:600;">Kwitansi</span>
    </div>

    {{-- Flash --}}
    @if(session('success'))
    <div class="alert-success">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink:0">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Kwitansi --}}
    <div class="kwit-card">

        {{-- Header --}}
        <div class="kwit-header">
            <div class="kwit-header-inner">
                <div>
                    <div class="kwit-school">PAUD KB PELANGI</div>
                    <div class="kwit-type">Bukti Pembayaran SPP</div>
                </div>
                <div class="kwit-no-wrap">
                    <div class="kwit-no-lbl">No. Kwitansi</div>
                    <div class="kwit-no-val">{{ $spp->no_kwitansi }}</div>
                </div>
            </div>
        </div>

        {{-- Body --}}
        <div class="kwit-body">

            <div class="info-grid">
                <div class="info-item">
                    <div class="info-lbl">Nama Siswa</div>
                    <div class="info-val">{{ $spp->siswa->nama_lengkap }}</div>
                </div>
                <div class="info-item">
                    <div class="info-lbl">NIS</div>
                    <div class="info-val">{{ $spp->siswa->nis ?? '—' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-lbl">Kelompok</div>
                    <div class="info-val">{{ $spp->siswa->kelompok }}</div>
                </div>
                <div class="info-item">
                    <div class="info-lbl">Periode</div>
                    <div class="info-val">{{ $spp->nama_bulan }} {{ $spp->tahun }}</div>
                </div>
            </div>

            <hr class="dashed-divider">

            <p class="section-ttl">Rincian Pembayaran</p>

            <div class="rincian-row">
                <span class="rincian-lbl">SPP Bulanan</span>
                <span class="rincian-val">Rp {{ number_format($spp->nominal_spp, 0, ',', '.') }}</span>
            </div>
            <div class="rincian-row">
                <span class="rincian-lbl">Biaya Kebersihan</span>
                <span class="rincian-val">Rp {{ number_format($spp->nominal_kebersihan, 0, ',', '.') }}</span>
            </div>

            <div class="total-block">
                <span class="t-lbl">Total</span>
                <span class="t-val">{{ $spp->total_rupiah }}</span>
            </div>

            <hr class="dashed-divider">

            <div class="footer-grid">
                <div>
                    <div class="info-lbl">Tanggal Bayar</div>
                    <div class="info-val" style="margin-bottom:12px;">{{ $spp->tanggal_bayar->translatedFormat('d F Y') }}</div>
                    <span class="badge-lunas">
                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                        </svg>
                        Lunas
                    </span>
                </div>
                <div>
                    <div class="info-lbl">Dicatat oleh</div>
                    <div class="info-val">{{ $spp->dicatatOleh?->nama_lengkap ?? '—' }}</div>
                </div>
            </div>

            <div class="recorded-box">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink:0; color:#2563eb">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p>Pembayaran telah diterima dan tercatat dalam sistem</p>
            </div>

        </div>
    </div>

    {{-- Actions --}}
    <div class="action-row">
        <a href="{{ route('spp.cetak', $spp) }}" target="_blank" class="btn-primary">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Cetak / Download PDF
        </a>
        <a href="{{ route('spp.create') }}" class="btn-secondary">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            Input Pembayaran Lain
        </a>
        <a href="{{ route('spp.index') }}" class="btn-ghost">Kembali</a>
    </div>

</div>
@endsection