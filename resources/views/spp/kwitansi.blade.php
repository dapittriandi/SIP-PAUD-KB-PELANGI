{{-- SIMPAN SEBAGAI: resources/views/spp/kwitansi.blade.php --}}
@extends('layouts.app')

@section('title', 'Kwitansi ' . $spp->no_kwitansi . ' — PAUD KB Pelangi')
@section('page-title', 'Kwitansi Pembayaran')

@push('styles')
<style>
/* ============================================================
   KWITANSI SPP — Receipt aesthetic · Committed color
   SIP-Pelangi design system · OKLCH · Geist font
   Scene: Orang tua buka di HP setelah bayar, siang hari —
          butuh konfirmasi visual bahwa pembayaran sah.
   Color strategy: Committed (deep blue-indigo carries 40% surface)
   ============================================================ */

.kw-wrap {
    /* Local tokens — selaras app.blade.php :root */
    --kw-ink:          oklch(18%  0.025 260);   /* deep ink, bukan #000 */
    --kw-ink-2:        oklch(35%  0.020 260);
    --kw-ink-3:        oklch(52%  0.012 255);
    --kw-ink-4:        oklch(68%  0.008 255);

    --kw-surface:      oklch(99%  0.003 255);   /* paper white, tinted */
    --kw-surface-2:    oklch(97%  0.006 255);
    --kw-border:       oklch(90%  0.008 255);
    --kw-border-a:     oklch(52%  0.190 260 / 14%);

    /* Deep header (committed color — tidak pakai --accent supaya beda dari UI) */
    --kw-deep:         oklch(24%  0.055 265);   /* navy-blue tinted */
    --kw-deep-2:       oklch(30%  0.060 265);
    --kw-deep-text:    oklch(96%  0.010 255);
    --kw-deep-sub:     oklch(72%  0.025 260);
    --kw-deep-ring:    oklch(99%  0.003 255 / 12%);

    /* Semantic */
    --kw-lunas:        oklch(50%  0.162 148);
    --kw-lunas-soft:   oklch(95%  0.040 148);
    --kw-lunas-ring:   oklch(72%  0.100 148);

    --kw-glass-blur:   blur(20px) saturate(1.6);
    --kw-shadow:       0 2px 16px oklch(24% 0.055 265 / 8%), 0 1px 3px oklch(0% 0 0 / 4%);
    --kw-shadow-h:     0 8px 32px oklch(24% 0.055 265 / 14%), 0 2px 6px oklch(0% 0 0 / 6%);

    max-width: 600px;
    margin: 0 auto;
    padding-bottom: 48px;
    font-family: 'Geist', system-ui, sans-serif;
    font-size: var(--fs-base, 14px);
    display: flex;
    flex-direction: column;
    gap: 16px;
}

/* Dark mode — pake .dark (Alpine), bukan prefers-color-scheme */
.dark .kw-wrap {
    --kw-ink:          oklch(94%  0.010 255);
    --kw-ink-2:        oklch(80%  0.012 260);
    --kw-ink-3:        oklch(62%  0.010 255);
    --kw-ink-4:        oklch(42%  0.008 255);
    --kw-surface:      oklch(14%  0.012 258);
    --kw-surface-2:    oklch(18%  0.014 258);
    --kw-border:       oklch(28%  0.010 255);
    --kw-border-a:     oklch(63%  0.185 260 / 20%);
    --kw-deep:         oklch(20%  0.045 265);
    --kw-deep-2:       oklch(26%  0.050 265);
    --kw-lunas-soft:   oklch(22%  0.060 148);
    --kw-lunas-ring:   oklch(38%  0.090 148);
    --kw-shadow:       0 4px 24px oklch(0% 0 0 / 45%), 0 1px 4px oklch(0% 0 0 / 30%);
    --kw-shadow-h:     0 8px 36px oklch(0% 0 0 / 55%), 0 2px 8px oklch(0% 0 0 / 32%);
}

/* ── Topbar ── */
.kw-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    flex-wrap: wrap;
}

/* Breadcrumb */
.kw-bc {
    display: flex; align-items: center; gap: 5px;
    font-size: var(--fs-xs, 11px); color: var(--kw-ink-3);
}
.kw-bc-link {
    color: var(--accent); font-weight: 600;
    text-decoration: none;
    transition: opacity var(--dur-fast);
}
.kw-bc-link:hover { opacity: 0.72; text-decoration: underline; }
.kw-bc-now { font-weight: 700; color: var(--kw-ink); }

/* ── Flash success ── */
.kw-alert {
    display: flex; align-items: flex-start; gap: 10px;
    padding: 12px 16px;
    background: var(--kw-lunas-soft);
    border: 1px solid var(--kw-lunas-ring);
    border-radius: var(--r, 10px);
    font-size: var(--fs-sm, 13px);
    color: var(--kw-lunas);
    font-weight: 600;
    line-height: 1.5;
}
.kw-alert-icon {
    width: 22px; height: 22px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    margin-top: 1px;
}

/* ══════════════════════════════════════════════
   KWITANSI DOKUMEN
   Receipt-style: header committed, body paper
   ══════════════════════════════════════════════ */
.kw-doc {
    background: var(--kw-surface);
    border: 1px solid var(--kw-border);
    border-radius: var(--r-lg, 14px);
    box-shadow: var(--kw-shadow);
    overflow: hidden;
    transition: box-shadow var(--dur-mid) var(--ease-out);
}
.kw-doc:hover { box-shadow: var(--kw-shadow-h); }

/* ── Header: committed deep blue ── */
.kw-doc-header {
    background: var(--kw-deep);
    padding: 26px 28px 22px;
    position: relative;
    overflow: hidden;
}

/* Subtle geometric texture — bukan radial gradient besar */
.kw-doc-header::before {
    content: '';
    position: absolute;
    top: -32px; right: -32px;
    width: 130px; height: 130px;
    border-radius: 50%;
    border: 1px solid var(--kw-deep-ring);
}
.kw-doc-header::after {
    content: '';
    position: absolute;
    bottom: -48px; right: 40px;
    width: 110px; height: 110px;
    border-radius: 50%;
    border: 1px solid var(--kw-deep-ring);
}
.dark .kw-doc-header { background: var(--kw-deep); }

.kw-header-inner {
    position: relative; z-index: 1;
    display: flex; align-items: flex-start;
    justify-content: space-between; gap: 16px;
}

/* Sekolah identity */
.kw-school-block {}
.kw-school-eyebrow {
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--kw-deep-sub);
    margin: 0 0 5px;
}
.kw-school-name {
    font-size: var(--fs-xl, 20px);
    font-weight: 800;
    color: var(--kw-deep-text);
    letter-spacing: -0.03em;
    line-height: 1.1;
    margin: 0 0 4px;
    font-family: 'Geist', system-ui, sans-serif;
}
.kw-school-sub {
    font-size: var(--fs-xs, 11px);
    color: var(--kw-deep-sub);
    font-weight: 400;
    margin: 0;
}

/* No. Kwitansi */
.kw-no-block { text-align: right; flex-shrink: 0; }
.kw-no-label {
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 0.10em;
    text-transform: uppercase;
    color: var(--kw-deep-sub);
    margin: 0 0 6px;
}
.kw-no-val {
    display: inline-block;
    font-family: 'Geist Mono', 'SF Mono', monospace;
    font-size: var(--fs-sm, 13px);
    font-weight: 700;
    color: var(--kw-deep-text);
    background: var(--kw-deep-ring);
    border: 1px solid oklch(99% 0.003 255 / 16%);
    padding: 5px 12px;
    border-radius: 7px;
    letter-spacing: 0.04em;
}

/* ── Perforation line — receipt feel ── */
.kw-perf {
    display: flex;
    align-items: center;
    gap: 0;
    padding: 0 16px;
    background: var(--kw-surface);
    position: relative;
}
.kw-perf::before,
.kw-perf::after {
    content: '';
    width: 18px; height: 18px;
    border-radius: 50%;
    background: oklch(var(--bg-page, 96% 0.006 255));
    border: 1px solid var(--kw-border);
    flex-shrink: 0;
    position: relative;
    z-index: 1;
}
.dark .kw-perf::before,
.dark .kw-perf::after {
    background: oklch(12% 0.010 258);
}
.kw-perf-line {
    flex: 1;
    border: none;
    border-top: 2px dashed var(--kw-border);
    margin: 0;
}

/* ── Doc body ── */
.kw-doc-body {
    padding: 24px 28px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

/* Info grid */
.kw-info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px 24px;
}
.kw-info-item {}
.kw-info-label {
    font-size: 9px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--kw-ink-3);
    margin: 0 0 4px;
}
.kw-info-val {
    font-size: var(--fs-sm, 13px);
    font-weight: 600;
    color: var(--kw-ink);
    margin: 0;
    line-height: 1.4;
}
.kw-info-val.mono {
    font-family: 'Geist Mono', monospace;
    font-size: var(--fs-xs, 11px);
    letter-spacing: 0.03em;
}

/* Section head */
.kw-section-head {
    display: flex;
    align-items: center;
    gap: 8px;
}
.kw-section-label {
    font-size: 9px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.10em;
    color: var(--kw-ink-3);
}
.kw-section-line {
    flex: 1;
    height: 1px;
    background: var(--kw-border);
}

/* Rincian rows */
.kw-rincian { display: flex; flex-direction: column; }
.kw-rincian-row {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    gap: 12px;
    padding: 10px 0;
    border-bottom: 1px solid oklch(90% 0.008 255 / 55%);
}
.dark .kw-rincian-row { border-bottom-color: oklch(28% 0.010 255 / 50%); }
.kw-rincian-row:last-child { border-bottom: none; }
.kw-rincian-lbl {
    font-size: var(--fs-sm, 13px);
    color: var(--kw-ink-2);
    font-weight: 500;
}
.kw-rincian-val {
    font-size: var(--fs-sm, 13px);
    font-weight: 700;
    color: var(--kw-ink);
    font-family: 'Geist Mono', monospace;
    letter-spacing: 0.01em;
    white-space: nowrap;
}

/* Total — bukan hero-metric, bukan gradient text */
.kw-total {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 14px 16px;
    background: var(--kw-deep);
    border-radius: var(--r, 10px);
}
.dark .kw-total { background: oklch(22% 0.048 265); }
.kw-total-label {
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--kw-deep-sub);
}
.kw-total-val {
    font-size: var(--fs-lg, 18px);
    font-weight: 800;
    color: var(--kw-deep-text);
    font-family: 'Geist Mono', monospace;
    letter-spacing: -0.02em;
}

/* Footer area */
.kw-footer-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}
.kw-footer-item {}

.kw-badge-lunas {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 12px;
    background: var(--kw-lunas-soft);
    border: 1px solid var(--kw-lunas-ring);
    border-radius: 20px;
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: var(--kw-lunas);
    margin-top: 10px;
    display: flex;
}

/* Verification strip — pengganti recorded-box yang generik */
.kw-verify {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    background: oklch(52% 0.190 260 / 5%);
    border: 1px solid oklch(52% 0.190 260 / 14%);
    border-radius: var(--r, 10px);
}
.dark .kw-verify {
    background: oklch(63% 0.185 260 / 8%);
    border-color: oklch(63% 0.185 260 / 20%);
}
.kw-verify-icon {
    width: 28px; height: 28px; flex-shrink: 0;
    border-radius: 50%;
    background: oklch(52% 0.190 260 / 10%);
    border: 1px solid oklch(52% 0.190 260 / 18%);
    color: var(--accent);
    display: flex; align-items: center; justify-content: center;
}
.dark .kw-verify-icon {
    background: oklch(63% 0.185 260 / 14%);
    border-color: oklch(63% 0.185 260 / 28%);
}
.kw-verify-text {
    font-size: var(--fs-xs, 11px);
    color: var(--kw-ink-2);
    font-weight: 500;
    line-height: 1.5;
    margin: 0;
}
.kw-verify-text strong {
    color: var(--accent);
    font-weight: 700;
}

/* ── Action buttons ── */
.kw-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.kw-btn-cetak {
    flex: 1; min-width: 160px;
    display: inline-flex; align-items: center; justify-content: center; gap: 8px;
    padding: 11px 20px;
    background: var(--accent);
    color: var(--text-inv);
    font-size: var(--fs-sm, 13px); font-weight: 700;
    border-radius: var(--r, 10px);
    text-decoration: none;
    box-shadow: 0 2px 12px oklch(52% 0.190 260 / 30%);
    transition:
        background   var(--dur-fast) var(--ease-out),
        transform    var(--dur-fast) var(--ease-out),
        box-shadow   var(--dur-mid)  var(--ease-out);
    white-space: nowrap;
}
.kw-btn-cetak:hover {
    background: var(--accent-h);
    transform: translateY(-1px);
    box-shadow: 0 6px 22px oklch(52% 0.190 260 / 42%);
}
.kw-btn-cetak:active { transform: translateY(0); }

.kw-btn-baru {
    flex: 1; min-width: 140px;
    display: inline-flex; align-items: center; justify-content: center; gap: 8px;
    padding: 11px 18px;
    background: var(--kw-surface);
    border: 1.5px solid var(--kw-border);
    color: var(--kw-ink);
    font-size: var(--fs-sm, 13px); font-weight: 600;
    border-radius: var(--r, 10px);
    text-decoration: none;
    transition:
        background    var(--dur-fast) var(--ease-out),
        border-color  var(--dur-fast) var(--ease-out),
        color         var(--dur-fast) var(--ease-out),
        transform     var(--dur-fast) var(--ease-out);
    white-space: nowrap;
}
.kw-btn-baru:hover {
    background: oklch(52% 0.190 260 / 5%);
    border-color: oklch(52% 0.190 260 / 28%);
    color: var(--accent);
    transform: translateY(-1px);
}

.kw-btn-back {
    display: inline-flex; align-items: center; justify-content: center; gap: 6px;
    padding: 11px 16px;
    background: transparent;
    border: 1.5px solid var(--kw-border);
    color: var(--kw-ink-3);
    font-size: var(--fs-sm, 13px); font-weight: 500;
    border-radius: var(--r, 10px);
    text-decoration: none;
    transition:
        background   var(--dur-fast) var(--ease-out),
        color        var(--dur-fast) var(--ease-out),
        border-color var(--dur-fast) var(--ease-out);
    white-space: nowrap;
}
.kw-btn-back:hover {
    background: var(--kw-surface-2);
    color: var(--kw-ink);
    border-color: oklch(68% 0.008 255 / 40%);
}

/* ── Responsive ── */
@media (max-width: 520px) {
    .kw-doc-header       { padding: 20px 18px 18px; }
    .kw-doc-body         { padding: 18px 18px; gap: 16px; }
    .kw-info-grid        { grid-template-columns: 1fr; gap: 10px; }
    .kw-footer-grid      { grid-template-columns: 1fr; gap: 12px; }
    .kw-school-name      { font-size: var(--fs-md, 15px); }
    .kw-total-val        { font-size: var(--fs-md, 15px); }
    .kw-actions          { flex-direction: column; }
    .kw-btn-cetak,
    .kw-btn-baru,
    .kw-btn-back         { min-width: 0; width: 100%; }
    .kw-header-inner     { flex-direction: column; gap: 12px; }
    .kw-no-block         { text-align: left; }
    .kw-perf             { padding: 0 12px; }
    .kw-perf::before,
    .kw-perf::after      { width: 14px; height: 14px; }
}

/* ── Reduced motion ── */
@media (prefers-reduced-motion: reduce) {
    .kw-btn-cetak,
    .kw-btn-baru,
    .kw-btn-back { transition: none; }
}

/* ── Print styles ── */
@media print {
    .kw-actions, .kw-topbar, .kw-alert { display: none !important; }
    .kw-doc { box-shadow: none; border: 1px solid #ccc; }
    .kw-wrap { padding-bottom: 0; }
}
</style>
@endpush

@section('content')
<div class="kw-wrap">

    {{-- ── Topbar ── --}}
    <div class="kw-topbar">
        <nav class="kw-bc" aria-label="Breadcrumb">
            <a href="{{ route('spp.index') }}" class="kw-bc-link">Pembayaran SPP</a>
            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
            <span class="kw-bc-now" aria-current="page">Kwitansi {{ $spp->no_kwitansi }}</span>
        </nav>
    </div>

    {{-- ── Flash ── --}}
    @if(session('success'))
    <div class="kw-alert" role="alert" aria-live="polite">
        <span class="kw-alert-icon" aria-hidden="true">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 6L9 17l-5-5"/>
            </svg>
        </span>
        {{ session('success') }}
    </div>
    @endif

    {{-- ══════════════════════════════════
         DOKUMEN KWITANSI
    ════════════════════════════════════ --}}
    <div class="kw-doc" role="main" aria-label="Kwitansi pembayaran SPP">

        {{-- Header --}}
        <div class="kw-doc-header">
            <div class="kw-header-inner">
                <div class="kw-school-block">
                    <p class="kw-school-eyebrow">Bukti Pembayaran</p>
                    <h1 class="kw-school-name">PAUD KB Pelangi</h1>
                    <p class="kw-school-sub">Surat Pemberitahuan Pembayaran (SPP)</p>
                </div>
                <div class="kw-no-block">
                    <p class="kw-no-label">No. Kwitansi</p>
                    <span class="kw-no-val" aria-label="Nomor kwitansi {{ $spp->no_kwitansi }}">{{ $spp->no_kwitansi }}</span>
                </div>
            </div>
        </div>

        {{-- Perforation (receipt feel) --}}
        <div class="kw-perf" aria-hidden="true">
            <div class="kw-perf-line"></div>
        </div>

        {{-- Body --}}
        <div class="kw-doc-body">

            {{-- Info siswa --}}
            <dl class="kw-info-grid">
                <div class="kw-info-item">
                    <dt class="kw-info-label">Nama Siswa</dt>
                    <dd class="kw-info-val">{{ $spp->siswa->nama_lengkap }}</dd>
                </div>
                <div class="kw-info-item">
                    <dt class="kw-info-label">NIS</dt>
                    <dd class="kw-info-val mono">{{ $spp->siswa->nis ?? '—' }}</dd>
                </div>
                <div class="kw-info-item">
                    <dt class="kw-info-label">Kelompok</dt>
                    <dd class="kw-info-val">{{ $spp->siswa->kelompok }}</dd>
                </div>
                <div class="kw-info-item">
                    <dt class="kw-info-label">Periode</dt>
                    <dd class="kw-info-val">{{ $spp->nama_bulan }} {{ $spp->tahun }}</dd>
                </div>
            </dl>

            {{-- Divider rincian --}}
            <div class="kw-section-head" aria-hidden="true">
                <div class="kw-section-line"></div>
                <span class="kw-section-label">Rincian Pembayaran</span>
                <div class="kw-section-line"></div>
            </div>

            {{-- Rincian --}}
            <div class="kw-rincian" role="list" aria-label="Rincian biaya">
                <div class="kw-rincian-row" role="listitem">
                    <span class="kw-rincian-lbl">SPP Bulanan</span>
                    <span class="kw-rincian-val" aria-label="Rp {{ number_format($spp->nominal_spp, 0, ',', '.') }}">
                        Rp {{ number_format($spp->nominal_spp, 0, ',', '.') }}
                    </span>
                </div>
                <div class="kw-rincian-row" role="listitem">
                    <span class="kw-rincian-lbl">Biaya Kebersihan</span>
                    <span class="kw-rincian-val" aria-label="Rp {{ number_format($spp->nominal_kebersihan, 0, ',', '.') }}">
                        Rp {{ number_format($spp->nominal_kebersihan, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            {{-- Total --}}
            <div class="kw-total" aria-label="Total pembayaran {{ $spp->total_rupiah }}">
                <span class="kw-total-label">Total Dibayar</span>
                <span class="kw-total-val">{{ $spp->total_rupiah }}</span>
            </div>

            {{-- Divider footer --}}
            <div class="kw-section-head" aria-hidden="true">
                <div class="kw-section-line"></div>
                <span class="kw-section-label">Informasi</span>
                <div class="kw-section-line"></div>
            </div>

            {{-- Footer info --}}
            <dl class="kw-footer-grid">
                <div class="kw-footer-item">
                    <dt class="kw-info-label">Tanggal Bayar</dt>
                    <dd class="kw-info-val" style="margin-bottom:10px;">
                        {{ $spp->tanggal_bayar->translatedFormat('d F Y') }}
                    </dd>
                    <span class="kw-badge-lunas" aria-label="Status: Lunas">
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6L9 17l-5-5"/></svg>
                        Lunas
                    </span>
                </div>
                <div class="kw-footer-item">
                    <dt class="kw-info-label">Dicatat oleh</dt>
                    <dd class="kw-info-val">{{ $spp->dicatatOleh?->nama_lengkap ?? '—' }}</dd>
                </div>
            </dl>

            {{-- Verification strip --}}
            <div class="kw-verify" role="note" aria-label="Status verifikasi pembayaran">
                <div class="kw-verify-icon" aria-hidden="true">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                </div>
                <p class="kw-verify-text">
                    Pembayaran <strong>tercatat dan terverifikasi</strong> dalam sistem SIP-Pelangi.
                </p>
            </div>

        </div>{{-- /kw-doc-body --}}
    </div>{{-- /kw-doc --}}

    {{-- ── Actions ── --}}
    <div class="kw-actions">
        <a href="{{ route('spp.cetak', $spp) }}" target="_blank" class="kw-btn-cetak" rel="noopener">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M17 17h2a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h2m2 4h6a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2zm8-12V5a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v4h10z"/>
            </svg>
            Cetak / Download PDF
        </a>
        <a href="{{ route('spp.create') }}" class="kw-btn-baru">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M12 5v14M5 12h14"/>
            </svg>
            Input Pembayaran Lain
        </a>
        <a href="{{ route('spp.index') }}" class="kw-btn-back">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M19 12H5M12 5l-7 7 7 7"/>
            </svg>
            Kembali
        </a>
    </div>

</div>
@endsection