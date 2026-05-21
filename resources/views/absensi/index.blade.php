@extends('layouts.app')

@section('title', 'Absensi — PAUD KB Pelangi')
@section('page-title', 'Absensi Saya')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Geist+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<style>
    /* ═══════════════════════════════════════════════════
       ABSENSI PAGE — Glassmorphism Layer
       Menggunakan design tokens dari layouts.app (OKLCH).
       Glass purposeful: hanya cards utama, bukan semua elemen.
    ═══════════════════════════════════════════════════ */

    /* ── Ambient glow background (dark-only, non-intrusive) ── */
    .abs-glow-1 {
        position: fixed; pointer-events: none; z-index: 0;
        top: -120px; left: -80px;
        width: 340px; height: 340px; border-radius: 50%;
        background: radial-gradient(circle, oklch(52% 0.190 260 / 12%) 0%, transparent 68%);
    }
    .abs-glow-2 {
        position: fixed; pointer-events: none; z-index: 0;
        top: 80px; right: -100px;
        width: 280px; height: 280px; border-radius: 50%;
        background: radial-gradient(circle, oklch(52% 0.175 155 / 10%) 0%, transparent 68%);
    }
    .dark .abs-glow-1,
    .dark .abs-glow-2 { opacity: 1; }
    .abs-glow-1, .abs-glow-2 { opacity: 0; }

    /* ── Glass card base ── */
    .glass-card {
        position: relative;
        background: oklch(99.5% 0.003 250 / 85%);
        backdrop-filter: blur(18px) saturate(1.3);
        -webkit-backdrop-filter: blur(18px) saturate(1.3);
        border: 1px solid oklch(85% 0.007 250 / 70%);
        border-radius: var(--r-xl);
        overflow: hidden;
    }
    .dark .glass-card {
        background: oklch(18% 0.013 255 / 72%);
        border-color: oklch(32% 0.010 255 / 40%);
    }

    /* Frost shimmer top edge */
    .glass-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 1px;
        background: linear-gradient(90deg,
            transparent 0%,
            oklch(90% 0.010 255 / 40%) 30%,
            oklch(90% 0.010 255 / 55%) 50%,
            oklch(90% 0.010 255 / 40%) 70%,
            transparent 100%
        );
        pointer-events: none;
    }
    .dark .glass-card::before {
        background: linear-gradient(90deg,
            transparent 0%,
            oklch(60% 0.012 255 / 25%) 30%,
            oklch(60% 0.012 255 / 40%) 50%,
            oklch(60% 0.012 255 / 25%) 70%,
            transparent 100%
        );
    }

    /* ── Status accent bar ── */
    .status-bar-glass { height: 3px; width: 100%; border-radius: 0; }
    .status-hadir     { background: linear-gradient(90deg, oklch(52% 0.175 155), oklch(65% 0.165 155)); }
    .status-terlambat { background: linear-gradient(90deg, oklch(60% 0.180 70),  oklch(75% 0.170 70)); }
    .status-izin,
    .status-sakit,
    .status-tugas_luar{ background: linear-gradient(90deg, oklch(52% 0.190 260), oklch(65% 0.185 260)); }
    .status-alpha     { background: linear-gradient(90deg, oklch(52% 0.210 27),  oklch(65% 0.200 27)); }

    /* ── Date-time header card ── */
    .header-card {
        background: oklch(99.5% 0.003 250 / 80%);
        backdrop-filter: blur(14px) saturate(1.2);
        -webkit-backdrop-filter: blur(14px) saturate(1.2);
        border: 1px solid oklch(85% 0.007 250 / 60%);
        border-radius: var(--r-xl);
        padding: 14px 18px;
        display: flex; align-items: center; justify-content: space-between;
    }
    .dark .header-card {
        background: oklch(16% 0.013 255 / 75%);
        border-color: oklch(30% 0.010 255 / 45%);
    }

    /* ── Icon container ── */
    .icon-box {
        width: 40px; height: 40px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .icon-box-success {
        background: oklch(94% 0.050 155);
        border: 1px solid oklch(82% 0.080 155 / 60%);
    }
    .dark .icon-box-success {
        background: oklch(22% 0.045 155 / 70%);
        border-color: oklch(35% 0.075 155 / 55%);
    }
    .icon-box-info {
        background: oklch(93% 0.040 260);
        border: 1px solid oklch(80% 0.065 260 / 60%);
    }
    .dark .icon-box-info {
        background: oklch(20% 0.038 260 / 70%);
        border-color: oklch(32% 0.068 260 / 55%);
    }
    .icon-box-warn {
        background: oklch(97% 0.045 70);
        border: 1px solid oklch(85% 0.075 70 / 50%);
    }
    .dark .icon-box-warn {
        background: oklch(22% 0.042 70 / 70%);
        border-color: oklch(34% 0.078 70 / 55%);
    }
    .icon-box-danger {
        background: oklch(97% 0.040 27);
        border: 1px solid oklch(86% 0.070 27 / 50%);
    }
    .dark .icon-box-danger {
        background: oklch(20% 0.042 27 / 70%);
        border-color: oklch(32% 0.078 27 / 55%);
    }
    .icon-box-neutral {
        background: var(--surface-2);
        border: 1px solid var(--border);
    }

    /* ── Badge ── */
    .abs-badge {
        display: inline-flex; align-items: center;
        padding: 3px 10px; border-radius: 9999px;
        font-size: 10px; font-weight: 600; letter-spacing: 0.05em; text-transform: uppercase;
    }
    .badge-success {
        background: oklch(93% 0.055 155);
        color: oklch(28% 0.100 155);
        border: 1px solid oklch(78% 0.090 155 / 55%);
    }
    .dark .badge-success {
        background: oklch(22% 0.045 155 / 65%);
        color: oklch(74% 0.160 155);
        border-color: oklch(35% 0.080 155 / 50%);
    }
    .badge-warn {
        background: oklch(97% 0.050 70);
        color: oklch(32% 0.095 70);
        border: 1px solid oklch(82% 0.085 70 / 50%);
    }
    .dark .badge-warn {
        background: oklch(22% 0.042 70 / 65%);
        color: oklch(80% 0.158 70);
        border-color: oklch(35% 0.080 70 / 50%);
    }
    .badge-info {
        background: oklch(94% 0.040 260);
        color: oklch(28% 0.095 260);
        border: 1px solid oklch(78% 0.075 260 / 50%);
    }
    .dark .badge-info {
        background: oklch(20% 0.035 260 / 65%);
        color: oklch(72% 0.160 260);
        border-color: oklch(32% 0.068 260 / 50%);
    }
    .badge-danger {
        background: oklch(97% 0.040 27);
        color: oklch(30% 0.095 27);
        border: 1px solid oklch(84% 0.070 27 / 50%);
    }
    .dark .badge-danger {
        background: oklch(20% 0.042 27 / 65%);
        color: oklch(70% 0.190 27);
        border-color: oklch(32% 0.080 27 / 50%);
    }
    .badge-neutral {
        background: var(--surface-2);
        color: var(--text-2);
        border: 1px solid var(--border);
    }

    /* ── Jam hero block ── */
    .jam-hero {
        background: oklch(97% 0.005 250 / 60%);
        border: 1px solid oklch(86% 0.007 250 / 50%);
        border-radius: var(--r-lg);
        padding: 10px 14px;
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 10px;
    }
    .dark .jam-hero {
        background: oklch(20% 0.012 255 / 55%);
        border-color: oklch(32% 0.010 255 / 40%);
    }

    /* ── Alert banners (status tepat waktu) ── */
    .alert-banner {
        display: flex; align-items: center; gap: 8px;
        border-radius: var(--r-lg);
        padding: 10px 14px;
        font-size: 11px; font-weight: 600;
        margin-bottom: 10px;
    }
    .alert-success {
        background: oklch(94% 0.055 155 / 80%);
        color: oklch(28% 0.100 155);
        border: 1px solid oklch(78% 0.090 155 / 50%);
    }
    .dark .alert-success {
        background: oklch(20% 0.042 155 / 60%);
        color: oklch(74% 0.160 155);
        border-color: oklch(32% 0.075 155 / 45%);
    }
    .alert-warn {
        background: oklch(97% 0.050 70 / 80%);
        color: oklch(32% 0.095 70);
        border: 1px solid oklch(82% 0.085 70 / 50%);
    }
    .dark .alert-warn {
        background: oklch(20% 0.042 70 / 60%);
        color: oklch(80% 0.158 70);
        border-color: oklch(32% 0.080 70 / 45%);
    }
    .alert-danger {
        background: oklch(97% 0.040 27 / 80%);
        color: oklch(30% 0.095 27);
        border: 1px solid oklch(84% 0.070 27 / 50%);
    }
    .dark .alert-danger {
        background: oklch(20% 0.042 27 / 60%);
        color: oklch(70% 0.190 27);
        border-color: oklch(32% 0.080 27 / 45%);
    }

    /* ── Detail rows ── */
    .detail-table {
        border: 1px solid var(--border);
        border-radius: var(--r-lg);
        overflow: hidden;
    }
    .dark .detail-table {
        border-color: oklch(28% 0.010 255 / 50%);
    }
    .detail-row {
        display: flex; align-items: flex-start; justify-content: space-between;
        gap: 12px; padding: 9px 14px;
        border-bottom: 1px solid var(--border);
        font-size: 12px;
    }
    .dark .detail-row { border-color: oklch(26% 0.010 255 / 40%); }
    .detail-row:last-child { border-bottom: none; }
    .detail-label {
        display: flex; align-items: center; gap: 6px;
        color: var(--text-3); font-weight: 500;
        flex-shrink: 0; width: 7rem;
    }
    .detail-value {
        color: var(--text-1); font-weight: 600; text-align: right;
    }

    /* ── Mode selector cards ── */
    .mode-card-glass {
        flex: 1; padding: 14px 10px;
        border-radius: var(--r-lg);
        background: oklch(99% 0.004 250 / 70%);
        border: 1.5px solid oklch(85% 0.008 250 / 60%);
        text-align: center; cursor: pointer;
        transition: transform var(--dur-mid) var(--ease-out),
                    border-color var(--dur-mid) var(--ease-out),
                    background var(--dur-mid) ease;
        will-change: transform;
        text-decoration: none;
        display: block;
    }
    .dark .mode-card-glass {
        background: oklch(19% 0.012 255 / 65%);
        border-color: oklch(30% 0.010 255 / 45%);
    }
    .mode-card-glass:hover { transform: translateY(-2px); }
    .mode-card-glass:active { transform: scale(0.96); }

    /* Active hadir */
    .mode-card-glass[data-selected="true"],
    .mode-card-glass.mode-hadir-active {
        background: oklch(93% 0.055 155 / 80%);
        border-color: oklch(65% 0.130 155 / 60%);
    }
    .dark .mode-card-glass[data-selected="true"],
    .dark .mode-card-glass.mode-hadir-active {
        background: oklch(20% 0.042 155 / 65%);
        border-color: oklch(38% 0.085 155 / 60%);
    }
    /* Izin card hover */
    .mode-card-glass.mode-izin:hover {
        border-color: oklch(70% 0.140 260 / 60%);
    }

    /* ── Info jam kerja table ── */
    .jam-info-table {
        border: 1px solid var(--border);
        border-radius: var(--r-lg);
        overflow: hidden;
        margin-bottom: 10px;
        font-size: 11px;
    }
    .dark .jam-info-table { border-color: oklch(28% 0.010 255 / 50%); }
    .jam-info-header {
        padding: 8px 14px;
        background: oklch(97% 0.005 250 / 50%);
        border-bottom: 1px solid var(--border);
    }
    .dark .jam-info-header {
        background: oklch(20% 0.012 255 / 50%);
        border-color: oklch(28% 0.010 255 / 40%);
    }
    .jam-info-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: 8px 14px;
        border-bottom: 1px solid var(--border);
    }
    .dark .jam-info-row { border-color: oklch(26% 0.010 255 / 35%); }
    .jam-info-row:last-child { border-bottom: none; }
    .jam-info-row.cutoff {
        background: oklch(97% 0.040 27 / 40%);
    }
    .dark .jam-info-row.cutoff {
        background: oklch(18% 0.038 27 / 50%);
    }

    /* ── GPS strip ── */
    .gps-strip {
        border-radius: var(--r-lg);
        padding: 10px 13px;
        font-size: 11px; line-height: 1.5;
        display: flex; align-items: flex-start; gap: 10px;
        transition: background 0.25s ease, border-color 0.25s ease, color 0.25s ease;
        margin-bottom: 8px;
    }
    .gps-idle {
        background: oklch(97% 0.005 250 / 60%);
        border: 1px solid var(--border);
        color: var(--text-3);
    }
    .dark .gps-idle {
        background: oklch(20% 0.012 255 / 55%);
        border-color: oklch(28% 0.010 255 / 45%);
    }
    .gps-loading {
        background: oklch(94% 0.040 260 / 70%);
        border: 1px solid oklch(78% 0.065 260 / 50%);
        color: oklch(32% 0.100 260);
    }
    .dark .gps-loading {
        background: oklch(20% 0.035 260 / 60%);
        border-color: oklch(30% 0.065 260 / 50%);
        color: oklch(72% 0.160 260);
    }
    .gps-ready {
        background: oklch(94% 0.055 155 / 70%);
        border: 1px solid oklch(76% 0.090 155 / 50%);
        color: oklch(28% 0.100 155);
    }
    .dark .gps-ready {
        background: oklch(20% 0.042 155 / 60%);
        border-color: oklch(32% 0.075 155 / 50%);
        color: oklch(74% 0.160 155);
    }
    .gps-error {
        background: oklch(97% 0.040 27 / 70%);
        border: 1px solid oklch(82% 0.070 27 / 50%);
        color: oklch(32% 0.100 27);
    }
    .dark .gps-error {
        background: oklch(20% 0.042 27 / 60%);
        border-color: oklch(32% 0.080 27 / 50%);
        color: oklch(70% 0.190 27);
    }
    .gps-jauh {
        background: oklch(97% 0.050 70 / 70%);
        border: 1px solid oklch(82% 0.085 70 / 50%);
        color: oklch(32% 0.095 70);
    }
    .dark .gps-jauh {
        background: oklch(20% 0.042 70 / 60%);
        border-color: oklch(32% 0.080 70 / 50%);
        color: oklch(80% 0.158 70);
    }

    /* GPS pulse dot */
    @keyframes gps-pulse {
        0%, 100% { box-shadow: 0 0 0 3px oklch(52% 0.190 260 / 18%); }
        50%       { box-shadow: 0 0 0 6px oklch(52% 0.190 260 / 6%); }
    }
    .gps-dot {
        width: 7px; height: 7px; border-radius: 50%;
        background: var(--accent);
        flex-shrink: 0; margin-top: 3px;
        animation: gps-pulse 2s ease-in-out infinite;
    }

    /* ── Buttons ── */
    .btn-primary-glass {
        background: var(--accent);
        color: var(--text-inv);
        border: none;
        border-radius: var(--r-lg);
        width: 100%; padding: 14px;
        font-size: 13px; font-weight: 600;
        cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 7px;
        box-shadow: 0 4px 20px oklch(52% 0.190 260 / 28%);
        transition: background var(--dur-fast) ease,
                    transform var(--dur-fast) var(--ease-out),
                    box-shadow var(--dur-fast) ease;
    }
    .btn-primary-glass:hover:not(:disabled) {
        background: var(--accent-h);
        box-shadow: 0 6px 24px oklch(52% 0.190 260 / 38%);
    }
    .btn-primary-glass:active:not(:disabled) { transform: scale(0.97) translateY(1px); }
    .btn-primary-glass:disabled {
        background: var(--border); box-shadow: none;
        color: var(--text-3); cursor: not-allowed;
    }

    .btn-outline-glass {
        background: oklch(99% 0.004 250 / 50%);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        color: var(--text-2);
        border: 1px solid var(--border);
        border-radius: var(--r-lg);
        width: 100%; padding: 11px;
        font-size: 13px; font-weight: 500;
        cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 7px;
        margin-bottom: 8px;
        transition: all var(--dur-fast) var(--ease-out);
    }
    .dark .btn-outline-glass {
        background: oklch(20% 0.012 255 / 50%);
        border-color: oklch(30% 0.010 255 / 50%);
    }
    .btn-outline-glass:hover:not(:disabled) {
        border-color: var(--accent-ring);
        color: var(--accent);
        background: var(--accent-soft);
    }
    .btn-outline-glass:active:not(:disabled) { transform: scale(0.97); }
    .btn-outline-glass:disabled { opacity: 0.45; cursor: not-allowed; }

    /* ── Rekap stat pills ── */
    .stat-pill-glass {
        flex: 1; padding: 11px 6px;
        border-radius: var(--r-lg);
        text-align: center;
        transition: transform var(--dur-mid) var(--ease-out);
    }
    .stat-pill-glass:hover { transform: translateY(-2px); }
    .stat-hadir  { background: oklch(93% 0.055 155 / 70%); border: 1px solid oklch(78% 0.090 155 / 40%); }
    .stat-lambat { background: oklch(97% 0.050 70  / 70%); border: 1px solid oklch(82% 0.085 70  / 40%); }
    .stat-izin   { background: oklch(94% 0.040 260 / 70%); border: 1px solid oklch(78% 0.065 260 / 40%); }
    .stat-alpha  { background: oklch(97% 0.040 27  / 70%); border: 1px solid oklch(84% 0.070 27  / 40%); }
    .dark .stat-hadir  { background: oklch(20% 0.042 155 / 60%); border-color: oklch(32% 0.075 155 / 45%); }
    .dark .stat-lambat { background: oklch(20% 0.042 70  / 60%); border-color: oklch(32% 0.078 70  / 45%); }
    .dark .stat-izin   { background: oklch(18% 0.035 260 / 60%); border-color: oklch(28% 0.065 260 / 45%); }
    .dark .stat-alpha  { background: oklch(20% 0.042 27  / 60%); border-color: oklch(32% 0.078 27  / 45%); }

    /* ── History rows ── */
    .history-row-glass {
        display: flex; align-items: center; gap: 12px;
        padding: 11px 0;
        border-bottom: 1px solid var(--border);
        transition: background var(--dur-fast) ease;
    }
    .dark .history-row-glass { border-color: oklch(26% 0.010 255 / 35%); }
    .history-row-glass:last-child { border-bottom: none; }
    .history-row-glass:hover { background: oklch(97% 0.005 250 / 30%); }
    .dark .history-row-glass:hover { background: oklch(22% 0.012 255 / 40%); }

    /* ── Toast ── */
    @keyframes toast-in { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
    .toast-glass {
        border-radius: var(--r-lg);
        padding: 11px 14px;
        font-size: 12px; font-weight: 600; text-align: center;
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        animation: toast-in var(--dur-mid) var(--ease-out) both;
    }
    .toast-ok {
        background: oklch(93% 0.055 155 / 85%);
        color: oklch(28% 0.100 155);
        border: 1px solid oklch(76% 0.090 155 / 50%);
    }
    .dark .toast-ok {
        background: oklch(20% 0.042 155 / 75%);
        color: oklch(74% 0.160 155);
        border-color: oklch(34% 0.078 155 / 50%);
    }
    .toast-err {
        background: oklch(97% 0.040 27 / 85%);
        color: oklch(30% 0.095 27);
        border: 1px solid oklch(82% 0.070 27 / 50%);
    }
    .dark .toast-err {
        background: oklch(20% 0.042 27 / 75%);
        color: oklch(70% 0.190 27);
        border-color: oklch(32% 0.080 27 / 50%);
    }

    /* ── Batas masuk banner ── */
    .batas-banner {
        display: flex; align-items: center; gap: 6px;
        border-radius: var(--r-lg);
        padding: 8px 12px;
        font-size: 11px;
        margin-bottom: 10px;
        background: oklch(97% 0.050 70 / 70%);
        border: 1px solid oklch(82% 0.085 70 / 50%);
        color: oklch(32% 0.095 70);
    }
    .dark .batas-banner {
        background: oklch(20% 0.042 70 / 60%);
        border-color: oklch(32% 0.080 70 / 45%);
        color: oklch(80% 0.158 70);
    }

    /* ── Section label ── */
    .section-label-glass {
        font-size: 9px; font-weight: 600;
        letter-spacing: 0.12em; text-transform: uppercase;
        color: var(--text-3);
        font-family: 'Geist Mono', monospace;
    }

    /* ── Stagger mount animation ── */
    .stagger-glass > * {
        opacity: 0;
        transform: translateY(8px);
        animation: fadeUp var(--dur-page) var(--ease-out) forwards;
    }
    .stagger-glass > *:nth-child(1) { animation-delay: 0.04s; }
    .stagger-glass > *:nth-child(2) { animation-delay: 0.10s; }
    .stagger-glass > *:nth-child(3) { animation-delay: 0.17s; }
    .stagger-glass > *:nth-child(4) { animation-delay: 0.24s; }
    .stagger-glass > *:nth-child(5) { animation-delay: 0.31s; }
    @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }

    /* Mono util */
    .g-mono { font-family: 'Geist Mono', 'Courier New', monospace; }
</style>
@endpush

@section('content')

{{-- Ambient glows (dark mode) --}}
<div class="abs-glow-1" aria-hidden="true"></div>
<div class="abs-glow-2" aria-hidden="true"></div>

<div class="max-w-sm mx-auto space-y-3 pb-8 relative z-10 stagger-glass" x-data="absensiGPS()">

    {{-- ── 1. Header: Tanggal & Jam ── --}}
    <div class="header-card">
        <div>
            <p class="section-label-glass">{{ now()->translatedFormat('l') }}</p>
            <p class="text-sm font-semibold mt-0.5" style="color:var(--text-1)">
                {{ now()->translatedFormat('d F Y') }}
            </p>
        </div>
        <div class="text-right">
            <p class="g-mono tabular" style="font-size:26px;font-weight:700;color:var(--text-1);letter-spacing:-0.02em;line-height:1;" x-text="jamSekarang"></p>
            <p class="section-label-glass mt-1">WIB</p>
        </div>
    </div>

    {{-- ══════════════════════════════════════════
         2a. SUDAH ABSEN
    ══════════════════════════════════════════ --}}
    @if($absenHariIni)
    @php
        $st      = $absenHariIni->status;
        $barKey  = match($st) {
            'hadir','terlambat'         => $st,
            'izin','sakit','tugas_luar' => 'izin',
            default                     => 'alpha',
        };
        $iconBoxClass = match($st) {
            'hadir','terlambat'         => 'icon-box-success',
            'izin','sakit','tugas_luar' => 'icon-box-info',
            'alpha'                     => 'icon-box-danger',
            default                     => 'icon-box-neutral',
        };
        $iconStroke = match($st) {
            'hadir'                     => 'oklch(46% 0.160 155)',
            'terlambat'                 => 'oklch(50% 0.170 70)',
            'izin','sakit','tugas_luar' => 'oklch(44% 0.175 260)',
            'alpha'                     => 'oklch(48% 0.195 27)',
            default                     => 'var(--text-3)',
        };
        $badgeClass = match($st) {
            'hadir'                     => 'badge-success',
            'terlambat'                 => 'badge-warn',
            'izin','sakit','tugas_luar' => 'badge-info',
            'alpha'                     => 'badge-danger',
            default                     => 'badge-neutral',
        };
        $jamMasuk   = $absenHariIni->jam_masuk ? substr($absenHariIni->jam_masuk, 0, 5) : null;
        $jamResmi   = config('sekolah.jam_masuk',     '08:00');
        $jamToleran = config('sekolah.jam_toleransi', '08:15');
    @endphp

    <div class="glass-card">
        <div class="status-bar-glass status-{{ $barKey }}"></div>

        {{-- Top row: icon + status --}}
        <div class="px-5 pt-5 pb-4 flex items-center gap-3">
            <div class="icon-box {{ $iconBoxClass }}">
                @if(in_array($st, ['hadir','terlambat']))
                    <svg width="18" height="18" fill="none" stroke="{{ $iconStroke }}" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                @elseif(in_array($st, ['izin','sakit','tugas_luar']))
                    <svg width="18" height="18" fill="none" stroke="{{ $iconStroke }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                @else
                    <svg width="18" height="18" fill="none" stroke="{{ $iconStroke }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <p style="font-size:10px;color:var(--text-3);">Absensi Hari Ini</p>
                <div class="flex items-center gap-2 mt-1">
                    <p class="text-sm font-bold" style="color:var(--text-1)">Sudah Tercatat</p>
                    <span class="abs-badge {{ $badgeClass }}">{{ $absenHariIni->label_status }}</span>
                </div>
            </div>
        </div>

        {{-- Jam masuk hero --}}
        @if($jamMasuk)
        <div class="mx-5 mb-1">
            <div class="jam-hero">
                <div class="flex items-center gap-2.5">
                    <div class="icon-box {{ $st === 'terlambat' ? 'icon-box-warn' : 'icon-box-success' }}" style="width:32px;height:32px;border-radius:9px;">
                        <svg width="14" height="14" fill="none" stroke="{{ $st === 'terlambat' ? 'oklch(50% 0.170 70)' : 'oklch(46% 0.160 155)' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                    </div>
                    <div>
                        <p class="section-label-glass">Jam Masuk</p>
                        <p class="g-mono font-bold mt-0.5" style="font-size:20px;line-height:1;color:var(--text-1);">
                            {{ $jamMasuk }} <span style="font-size:10px;font-weight:400;color:var(--text-3);">WIB</span>
                        </p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="section-label-glass">Jam Resmi Masuk</p>
                    <p class="g-mono font-semibold mt-0.5" style="font-size:13px;color:var(--text-2);">{{ $jamResmi }} WIB</p>
                </div>
            </div>

            {{-- Status ketepatan waktu --}}
            @if($st === 'terlambat')
            <div class="alert-banner alert-danger">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                Terlambat <span class="g-mono mx-1">{{ $absenHariIni->terlambat_menit }} menit</span> dari jam resmi <span class="g-mono ml-1">{{ $jamResmi }} WIB</span>
            </div>
            @elseif($absenHariIni->terlambat_menit > 0)
            <div class="alert-banner alert-warn">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                Dalam toleransi — <span class="g-mono mx-1">{{ $absenHariIni->terlambat_menit }} menit</span> setelah jam resmi, masih &le; <span class="g-mono ml-1">{{ $jamToleran }} WIB</span>
            </div>
            @else
            <div class="alert-banner alert-success">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                Tepat waktu — masuk pada atau sebelum jam resmi <span class="g-mono ml-1">{{ $jamResmi }} WIB</span>
            </div>
            @endif
        </div>
        @endif

        {{-- Detail rows --}}
        <div class="px-5 pb-5">
            <div class="detail-table">
                <div class="detail-row">
                    <span class="detail-label">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Metode
                    </span>
                    <span class="detail-value">
                        @if($absenHariIni->isSelfCheckIn())
                            GPS &middot; <span class="g-mono">{{ $absenHariIni->jarak_meter }}m</span> dari sekolah
                        @else
                            Dicatat oleh admin
                        @endif
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        Tanggal
                    </span>
                    <span class="detail-value g-mono" style="font-size:11px;">{{ now()->translatedFormat('d M Y') }}</span>
                </div>
                @if($absenHariIni->keterangan)
                <div class="detail-row">
                    <span class="detail-label">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
                        Keterangan
                    </span>
                    <span class="detail-value font-normal italic text-right" style="max-width:10rem;color:var(--text-2);">
                        "{{ $absenHariIni->keterangan }}"
                    </span>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════
         2b. BELUM ABSEN
    ══════════════════════════════════════════ --}}
    @else

    {{-- Mode selector --}}
    <div class="flex gap-2.5">
        {{-- Absen Hadir --}}
        <button type="button"
                @click="pilihan = 'hadir'"
                class="mode-card-glass"
                :class="{ 'mode-hadir-active': pilihan === 'hadir' }">
            <div class="icon-box icon-box-success mx-auto mb-2.5" style="width:36px;height:36px;">
                <svg width="16" height="16" fill="none" stroke="oklch(46% 0.160 155)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <p class="text-sm font-bold" style="color:var(--text-1)">Absen Hadir</p>
            <p style="font-size:10px;margin-top:2px;color:var(--text-3);">Check-in via GPS</p>
        </button>

        {{-- Lapor Izin --}}
        <a href="{{ route('absensi.izin.form') }}" class="mode-card-glass mode-izin">
            <div class="icon-box icon-box-info mx-auto mb-2.5" style="width:36px;height:36px;">
                <svg width="16" height="16" fill="none" stroke="oklch(44% 0.175 260)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <p class="text-sm font-bold" style="color:var(--text-1)">Lapor Izin</p>
            <p style="font-size:10px;margin-top:2px;color:var(--text-3);">Izin · Sakit · Tugas</p>
        </a>
    </div>

    {{-- GPS Check-in Panel --}}
    <div x-show="pilihan === 'hadir'"
         x-transition:enter="transition duration-200 ease-out"
         x-transition:enter-start="opacity-0 -translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="glass-card">

        <div class="px-5 pt-5 pb-2">
            <p class="text-sm font-bold mb-3" style="color:var(--text-1)">Absensi Kehadiran</p>

            {{-- Info jam kerja --}}
            @php
                $cfgJamMasuk   = config('sekolah.jam_masuk',          '08:00');
                $cfgJamToleran = config('sekolah.jam_toleransi',       '08:15');
                $cfgCutoff     = config('sekolah.jam_cutoff_checkin',  '10:30');
            @endphp

            {{-- Batas cutoff banner --}}
            @if(now()->format('H:i') >= $cfgJamToleran && now()->format('H:i') < $cfgCutoff)
            <div class="batas-banner">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                Absen sekarang dicatat <strong>terlambat</strong> — melewati {{ $cfgJamToleran }} WIB
            </div>
            @endif

            <div class="jam-info-table">
                <div class="jam-info-header">
                    <p class="section-label-glass">Info Jam Kerja</p>
                </div>
                <div class="jam-info-row">
                    <span class="flex items-center gap-1.5" style="color:var(--text-2);">
                        <span style="width:6px;height:6px;border-radius:50%;background:var(--success);display:inline-block;flex-shrink:0;"></span>
                        Tepat waktu
                    </span>
                    <span class="g-mono font-bold" style="color:var(--success);">&le; {{ $cfgJamMasuk }} WIB</span>
                </div>
                <div class="jam-info-row">
                    <span class="flex items-center gap-1.5" style="color:var(--text-2);">
                        <span style="width:6px;height:6px;border-radius:50%;background:var(--warning);display:inline-block;flex-shrink:0;"></span>
                         Tetap hadir (status terlambat)
                    </span>
                    <span class="g-mono font-bold" style="color:var(--warning);">{{ $cfgJamMasuk }} – {{ $cfgJamToleran }} WIB</span>
                </div>
               
                <div class="jam-info-row cutoff">
                    <span class="flex items-center gap-1.5" style="color:var(--danger);">
                        <span style="width:6px;height:6px;border-radius:50%;background:var(--danger);display:inline-block;flex-shrink:0;opacity:0.7;"></span>
                        Check-in ditutup (status tidak hadir apabila tidak absen)
                    </span>
                    <span class="g-mono font-bold" style="color:var(--danger);">&gt; {{ $cfgCutoff }} WIB</span>
                </div>
            </div>

            {{-- GPS status strip --}}
            <div class="gps-strip"
                 :class="{
                     'gps-idle':    status === 'idle',
                     'gps-loading': status === 'loading',
                     'gps-ready':   status === 'ready',
                     'gps-error':   status === 'error',
                     'gps-jauh':    status === 'jauh',
                 }">
                <div class="relative flex-shrink-0 mt-0.5">
                    <template x-if="status === 'loading'">
                        <svg class="animate-spin w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </template>
                    <template x-if="status === 'ready'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    </template>
                    <template x-if="status === 'idle'">
                        <span class="gps-dot"></span>
                    </template>
                    <template x-if="status === 'error' || status === 'jauh'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </template>
                </div>
                <span x-text="pesanGPS" class="leading-relaxed"></span>
            </div>

            {{-- Ambil Lokasi --}}
            <button type="button"
                    @click="ambilLokasi()"
                    :disabled="status === 'loading' || status === 'submitting'"
                    class="btn-outline-glass">
                <template x-if="status !== 'loading'">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </template>
                <template x-if="status === 'loading'">
                    <svg class="animate-spin w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                </template>
                <span x-text="status === 'ready' ? 'Lokasi Terdeteksi' : status === 'loading' ? 'Mendeteksi...' : 'Ambil Lokasi Saya'"></span>
            </button>

            {{-- Absen Sekarang --}}
            <button type="button"
                    @click="kirimAbsensi()"
                    :disabled="status !== 'ready' || submitting"
                    class="btn-primary-glass">
                <template x-if="!submitting">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                </template>
                <template x-if="submitting">
                    <svg class="animate-spin w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                </template>
                <span x-text="submitting ? 'Menyimpan...' : 'Absen Sekarang'"></span>
            </button>
        </div>

        {{-- Toast --}}
        <div x-show="toast.show"
             x-transition:enter="transition duration-200"
             x-transition:enter-start="opacity-0 translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition duration-150"
             x-transition:leave-end="opacity-0"
             class="mx-5 mb-5 mt-3 toast-glass"
             :class="toast.success ? 'toast-ok' : 'toast-err'"
             x-text="toast.message">
        </div>
    </div>

    {{-- Hint idle --}}
    <div x-show="pilihan === null"
         x-transition:enter="transition duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="text-center py-2">
        <p style="font-size:11px;color:var(--text-3);">Pilih jenis absensi di atas untuk memulai</p>
    </div>

    @endif

    {{-- ── 3. Rekap Bulan Ini ── --}}
    <div class="glass-card overflow-hidden">
        {{-- Header --}}
        <div class="px-5 py-3.5 flex items-center justify-between" style="border-bottom:1px solid var(--border);">
            <h3 class="text-sm font-bold" style="color:var(--text-1)">Rekap {{ now()->translatedFormat('F Y') }}</h3>
        </div>

        {{-- Stats --}}
        @php
            $hadir     = $absenBulanIni->whereIn('status', ['hadir','terlambat'])->count();
            $terlambat = $absenBulanIni->where('status', 'terlambat')->count();
            $izin      = $absenBulanIni->whereIn('status', ['izin','sakit','tugas_luar'])->count();
            $alpha     = $absenBulanIni->where('status', 'alpha')->count();
        @endphp
        <div class="p-4 flex gap-2">
            <div class="stat-pill-glass stat-hadir">
                <p class="g-mono text-xl font-bold" style="color:oklch(28% 0.100 155);">{{ $hadir }}</p>
                <p style="font-size:9px;font-weight:600;margin-top:3px;letter-spacing:.07em;text-transform:uppercase;color:oklch(42% 0.130 155);">Hadir</p>
            </div>
            <div class="stat-pill-glass stat-lambat">
                <p class="g-mono text-xl font-bold" style="color:oklch(32% 0.095 70);">{{ $terlambat }}</p>
                <p style="font-size:9px;font-weight:600;margin-top:3px;letter-spacing:.07em;text-transform:uppercase;color:oklch(45% 0.120 70);">Lambat</p>
            </div>
            <div class="stat-pill-glass stat-izin">
                <p class="g-mono text-xl font-bold" style="color:oklch(28% 0.095 260);">{{ $izin }}</p>
                <p style="font-size:9px;font-weight:600;margin-top:3px;letter-spacing:.07em;text-transform:uppercase;color:oklch(42% 0.120 260);">Izin</p>
            </div>
            <div class="stat-pill-glass stat-alpha">
                <p class="g-mono text-xl font-bold" style="color:oklch(30% 0.095 27);">{{ $alpha }}</p>
                <p style="font-size:9px;font-weight:600;margin-top:3px;letter-spacing:.07em;text-transform:uppercase;color:oklch(44% 0.120 27);">Alpha</p>
            </div>
        </div>

        {{-- History list --}}
        @if($absenBulanIni->isNotEmpty())
        <div style="border-top:1px solid var(--border);">
            <div class="px-5 py-2" style="background:oklch(97% 0.005 250 / 40%);">
                <p class="section-label-glass" style="color:var(--text-3);">Riwayat</p>
            </div>
            <div class="dark" style="">
                {{-- override bg for inner section --}}
            </div>
            <div class="max-h-72 overflow-y-auto px-5">
                @foreach($absenBulanIni as $a)
                @php
                    $hBadge = match($a->status) {
                        'hadir'      => 'badge-success',
                        'terlambat'  => 'badge-warn',
                        'izin'       => 'badge-info',
                        'sakit'      => 'badge-info',
                        'tugas_luar' => 'badge-info',
                        'alpha'      => 'badge-danger',
                        default      => 'badge-neutral',
                    };
                @endphp
                <div class="history-row-glass">
                    <div class="text-center flex-shrink-0" style="width:32px;">
                        <p class="g-mono text-sm font-bold" style="color:var(--text-1)">{{ $a->tanggal->format('d') }}</p>
                        <p style="font-size:9px;text-transform:uppercase;color:var(--text-3);">{{ $a->tanggal->translatedFormat('D') }}</p>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <span class="abs-badge {{ $hBadge }}">{{ $a->label_status }}</span>
                            @if($a->terlambat_menit > 0)
                                <span class="abs-badge badge-warn">+<span class="g-mono">{{ $a->terlambat_menit }}</span>m</span>
                            @endif
                        </div>
                        @if($a->keterangan)
                            <p style="font-size:10px;margin-top:3px;font-style:italic;color:var(--text-3);" class="truncate">{{ $a->keterangan }}</p>
                        @endif
                    </div>
                    <div class="text-right flex-shrink-0">
                        @if($a->jam_masuk)
                            <p class="g-mono font-semibold" style="font-size:11px;color:var(--text-1);">{{ substr($a->jam_masuk, 0, 5) }}</p>
                            <p style="font-size:9px;text-transform:uppercase;letter-spacing:.06em;margin-top:2px;color:var(--text-3);">jam masuk</p>
                        @else
                            <p class="g-mono" style="font-size:11px;color:var(--text-3);">—</p>
                            <p style="font-size:9px;text-transform:uppercase;letter-spacing:.06em;margin-top:2px;color:var(--text-3);">jam masuk</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="px-5 py-10 text-center">
            <div class="icon-box icon-box-neutral mx-auto mb-3" style="width:44px;height:44px;border-radius:14px;">
                <svg width="20" height="20" fill="none" stroke="var(--text-3)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </div>
            <p class="text-sm font-semibold" style="color:var(--text-2)">Belum ada riwayat</p>
            <p style="font-size:11px;margin-top:4px;color:var(--text-3)">Riwayat bulan ini akan muncul di sini</p>
        </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
function absensiGPS() {
    return {
        pilihan: null,
        status: 'idle',
        submitting: false,
        pesanGPS: 'Tekan "Ambil Lokasi" untuk mendeteksi posisi Anda.',
        latitude: null,
        longitude: null,
        toast: { show: false, success: false, message: '' },
        jamSekarang: '',

        init() {
            this.updateJam();
            setInterval(() => this.updateJam(), 1000);
        },

        updateJam() {
            const now = new Date();
            this.jamSekarang = now.toLocaleTimeString('id-ID', {
                hour: '2-digit', minute: '2-digit', second: '2-digit'
            });
        },

        ambilLokasi() {
            if (!navigator.geolocation) {
                this.status = 'error';
                this.pesanGPS = 'Browser Anda tidak mendukung GPS.';
                return;
            }
            this.status = 'loading';
            this.pesanGPS = 'Mendeteksi lokasi Anda...';

            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    this.latitude  = pos.coords.latitude;
                    this.longitude = pos.coords.longitude;
                    this.status    = 'ready';
                    this.pesanGPS  = `Lokasi terdeteksi. Akurasi \u00b1${Math.round(pos.coords.accuracy)}m.`;
                },
                (err) => {
                    this.status = 'error';
                    this.pesanGPS = err.code === 1
                        ? 'Izin lokasi ditolak. Aktifkan GPS dan izinkan akses lokasi.'
                        : 'Gagal mendapatkan lokasi. Coba lagi.';
                },
                { enableHighAccuracy: true, timeout: 10000 }
            );
        },

        async kirimAbsensi() {
            if (this.status !== 'ready' || this.submitting) return;
            this.submitting = true;
            this.status = 'submitting';

            try {
                const res = await fetch('{{ route('absensi.checkin') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ latitude: this.latitude, longitude: this.longitude }),
                });

                const data = await res.json();

                if (data.success) {
                    this.showToast(true, data.message);
                    setTimeout(() => location.reload(), 1500);
                } else {
                    this.status = res.status === 422 && data.message?.includes('jauh') ? 'jauh' : 'error';
                    this.pesanGPS = data.message;
                    this.submitting = false;
                    this.showToast(false, data.message);
                }
            } catch (e) {
                this.status = 'ready';
                this.submitting = false;
                this.showToast(false, 'Terjadi kesalahan. Coba lagi.');
            }
        },

        showToast(success, message) {
            this.toast = { show: true, success, message };
            setTimeout(() => this.toast.show = false, 4000);
        }
    }
}
</script>
@endpush