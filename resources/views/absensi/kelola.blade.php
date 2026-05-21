@extends('layouts.app')

@section('title', 'Kelola Absensi — PAUD KB Pelangi')
@section('page-title', 'Kelola Absensi')

@push('styles')
<style>
    /* ═══════════════════════════════════════════════════
       KELOLA PAGE — Glassmorphism Layer
       Design tokens dari layouts.app + konsisten dengan
       index_blade_glass.php (badge, glass-card, icon-box).
    ═══════════════════════════════════════════════════ */

    /* ── Ambient glows ── */
    .kl-glow-1 {
        position: fixed; pointer-events: none; z-index: 0;
        top: -100px; right: -80px;
        width: 320px; height: 320px; border-radius: 50%;
        background: radial-gradient(circle, oklch(52% 0.190 260 / 10%) 0%, transparent 68%);
        opacity: 0;
    }
    .kl-glow-2 {
        position: fixed; pointer-events: none; z-index: 0;
        bottom: -60px; left: -80px;
        width: 280px; height: 280px; border-radius: 50%;
        background: radial-gradient(circle, oklch(52% 0.175 155 / 9%) 0%, transparent 68%);
        opacity: 0;
    }
    .dark .kl-glow-1,
    .dark .kl-glow-2 { opacity: 1; }

    /* ── Glass card ── */
    .kl-glass {
        position: relative;
        background: oklch(99.5% 0.003 250 / 88%);
        backdrop-filter: blur(18px) saturate(1.3);
        -webkit-backdrop-filter: blur(18px) saturate(1.3);
        border: 1px solid oklch(85% 0.007 250 / 65%);
        border-radius: var(--r-xl);
        overflow: hidden;
    }
    .dark .kl-glass {
        background: oklch(18% 0.013 255 / 72%);
        border-color: oklch(30% 0.010 255 / 40%);
    }
    .kl-glass::before {
        content: '';
        position: absolute; top: 0; left: 0; right: 0; height: 1px; pointer-events: none;
        background: linear-gradient(90deg, transparent, oklch(88% 0.010 255 / 45%) 40%, oklch(88% 0.010 255 / 55%) 50%, oklch(88% 0.010 255 / 45%) 60%, transparent);
    }
    .dark .kl-glass::before {
        background: linear-gradient(90deg, transparent, oklch(55% 0.012 255 / 22%) 40%, oklch(55% 0.012 255 / 35%) 50%, oklch(55% 0.012 255 / 22%) 60%, transparent);
    }

    /* ── Header glass (date + controls) ── */
    .kl-header-glass {
        background: oklch(99.5% 0.003 250 / 82%);
        backdrop-filter: blur(14px) saturate(1.2);
        -webkit-backdrop-filter: blur(14px) saturate(1.2);
        border: 1px solid oklch(85% 0.007 250 / 58%);
        border-radius: var(--r-xl);
        padding: 16px 18px;
    }
    .dark .kl-header-glass {
        background: oklch(16% 0.013 255 / 76%);
        border-color: oklch(28% 0.010 255 / 45%);
    }

    /* ── Stat pills ── */
    .kl-stat {
        flex: 1; padding: 14px 8px;
        border-radius: var(--r-xl);
        text-align: center;
        transition: transform var(--dur-mid) var(--ease-out);
        cursor: default;
    }
    .kl-stat:hover { transform: translateY(-2px); }
    .kl-stat-total { background: oklch(97% 0.005 250 / 70%); border: 1px solid oklch(84% 0.007 250 / 45%); }
    .kl-stat-hadir  { background: oklch(93% 0.055 155 / 70%); border: 1px solid oklch(78% 0.090 155 / 40%); }
    .kl-stat-tidak  { background: oklch(97% 0.050 70  / 70%); border: 1px solid oklch(82% 0.085 70  / 40%); }
    .kl-stat-belum  { background: oklch(97% 0.040 27  / 70%); border: 1px solid oklch(84% 0.070 27  / 40%); }
    .dark .kl-stat-total { background: oklch(20% 0.012 255 / 58%); border-color: oklch(30% 0.010 255 / 40%); }
    .dark .kl-stat-hadir  { background: oklch(20% 0.042 155 / 60%); border-color: oklch(32% 0.075 155 / 42%); }
    .dark .kl-stat-tidak  { background: oklch(20% 0.042 70  / 60%); border-color: oklch(32% 0.078 70  / 42%); }
    .dark .kl-stat-belum  { background: oklch(20% 0.042 27  / 60%); border-color: oklch(32% 0.078 27  / 42%); }

    /* ── Badges ── */
    .kl-badge {
        display: inline-flex; align-items: center;
        padding: 3px 9px; border-radius: 9999px;
        font-size: 10px; font-weight: 600; letter-spacing: 0.05em; text-transform: uppercase;
        white-space: nowrap;
    }
    .kb-hadir     { background: oklch(93% 0.055 155); color: oklch(28% 0.100 155); border: 1px solid oklch(78% 0.090 155 / 55%); }
    .kb-terlambat { background: oklch(97% 0.050 70 ); color: oklch(32% 0.095 70 ); border: 1px solid oklch(82% 0.085 70  / 50%); }
    .kb-izin      { background: oklch(94% 0.040 260); color: oklch(28% 0.095 260); border: 1px solid oklch(78% 0.065 260 / 50%); }
    .kb-sakit     { background: oklch(94% 0.040 260); color: oklch(28% 0.095 260); border: 1px solid oklch(78% 0.065 260 / 50%); }
    .kb-tugas     { background: oklch(95% 0.040 290); color: oklch(30% 0.095 290); border: 1px solid oklch(80% 0.065 290 / 50%); }
    .kb-alpha     { background: oklch(97% 0.040 27 ); color: oklch(30% 0.095 27 ); border: 1px solid oklch(84% 0.070 27  / 50%); }
    .kb-belum     { background: var(--surface-2); color: var(--text-3); border: 1px solid var(--border); }
    .dark .kb-hadir     { background: oklch(22% 0.045 155 / 65%); color: oklch(74% 0.160 155); border-color: oklch(35% 0.080 155 / 48%); }
    .dark .kb-terlambat { background: oklch(22% 0.042 70  / 65%); color: oklch(80% 0.158 70 ); border-color: oklch(35% 0.080 70  / 48%); }
    .dark .kb-izin      { background: oklch(20% 0.035 260 / 65%); color: oklch(72% 0.160 260); border-color: oklch(32% 0.068 260 / 48%); }
    .dark .kb-sakit     { background: oklch(20% 0.035 260 / 65%); color: oklch(72% 0.160 260); border-color: oklch(32% 0.068 260 / 48%); }
    .dark .kb-tugas     { background: oklch(20% 0.038 290 / 65%); color: oklch(72% 0.160 290); border-color: oklch(32% 0.068 290 / 48%); }
    .dark .kb-alpha     { background: oklch(20% 0.042 27  / 65%); color: oklch(70% 0.190 27 ); border-color: oklch(32% 0.080 27  / 48%); }

    /* ── Table ── */
    .kl-table { width: 100%; border-collapse: collapse; font-size: 12px; }
    .kl-table thead tr {
        background: oklch(97% 0.005 250 / 50%);
        border-bottom: 1px solid var(--border);
    }
    .dark .kl-table thead tr {
        background: oklch(20% 0.012 255 / 50%);
        border-color: oklch(28% 0.010 255 / 40%);
    }
    .kl-table th {
        padding: 9px 14px; text-align: left;
        font-size: 9px; font-weight: 600; letter-spacing: 0.10em;
        text-transform: uppercase; color: var(--text-3);
        white-space: nowrap;
    }
    .kl-table td { padding: 10px 14px; vertical-align: middle; }
    .kl-table tbody tr { border-bottom: 1px solid var(--border); transition: background 0.15s ease; }
    .dark .kl-table tbody tr { border-color: oklch(26% 0.010 255 / 35%); }
    .kl-table tbody tr:last-child { border-bottom: none; }
    .kl-table tbody tr:hover { background: oklch(97% 0.005 250 / 35%); }
    .dark .kl-table tbody tr:hover { background: oklch(22% 0.012 255 / 40%); }

    /* ── Avatar ── */
    .kl-avatar {
        width: 34px; height: 34px; border-radius: 10px;
        flex-shrink: 0; display: flex; align-items: center; justify-content: center;
        font-size: 13px; font-weight: 700;
        background: var(--accent-muted);
        color: var(--accent);
        border: 1px solid oklch(80% 0.065 260 / 40%);
        font-family: 'Geist', system-ui;
    }
    .dark .kl-avatar {
        background: oklch(20% 0.038 260 / 70%);
        color: oklch(68% 0.175 260);
        border-color: oklch(32% 0.068 260 / 50%);
    }

    /* ── Action buttons ── */
    .kl-action-btn {
        width: 30px; height: 30px; border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        border: 1px solid transparent;
        cursor: pointer; background: transparent;
        transition: all var(--dur-fast) var(--ease-out);
        color: var(--text-3);
    }
    .kl-action-btn:hover {
        background: var(--accent-muted);
        border-color: var(--accent-ring);
        color: var(--accent);
    }
    .kl-action-btn.danger:hover {
        background: oklch(97% 0.040 27 / 70%);
        border-color: oklch(84% 0.070 27 / 50%);
        color: oklch(36% 0.100 27);
    }
    .dark .kl-action-btn:hover {
        background: oklch(20% 0.035 260 / 55%);
        border-color: oklch(30% 0.065 260 / 50%);
        color: oklch(68% 0.175 260);
    }
    .dark .kl-action-btn.danger:hover {
        background: oklch(20% 0.042 27 / 55%);
        border-color: oklch(30% 0.078 27 / 50%);
        color: oklch(68% 0.190 27);
    }

    /* ── Date input ── */
    .kl-date-input {
        padding: 8px 12px;
        border: 1px solid var(--border);
        border-radius: var(--r-lg);
        font-size: 12px; font-weight: 500;
        color: var(--text-1);
        background: oklch(99.5% 0.003 250 / 60%);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        outline: none;
        transition: border-color var(--dur-fast) ease, box-shadow var(--dur-fast) ease;
        font-family: 'Geist Mono', monospace;
        cursor: pointer;
    }
    .dark .kl-date-input {
        background: oklch(20% 0.012 255 / 55%);
        border-color: oklch(30% 0.010 255 / 45%);
        color: var(--text-1);
        color-scheme: dark;
    }
    .kl-date-input:focus {
        border-color: var(--accent-ring);
        box-shadow: 0 0 0 3px oklch(52% 0.190 260 / 12%);
    }

    /* ── Auto Alpha button ── */
    .btn-alpha {
        display: flex; align-items: center; gap: 6px;
        padding: 8px 14px; border-radius: var(--r-lg);
        font-size: 12px; font-weight: 600;
        background: oklch(97% 0.040 27 / 70%);
        color: oklch(32% 0.095 27);
        border: 1px solid oklch(84% 0.070 27 / 50%);
        cursor: pointer;
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        transition: all var(--dur-fast) var(--ease-out);
    }
    .dark .btn-alpha {
        background: oklch(20% 0.042 27 / 60%);
        color: oklch(70% 0.190 27);
        border-color: oklch(32% 0.080 27 / 50%);
    }
    .btn-alpha:hover {
        background: oklch(95% 0.050 27 / 85%);
        border-color: oklch(78% 0.080 27 / 60%);
        transform: translateY(-1px);
    }
    .dark .btn-alpha:hover {
        background: oklch(24% 0.048 27 / 72%);
        border-color: oklch(38% 0.090 27 / 58%);
        transform: translateY(-1px);
    }

    /* ── GPS chip ── */
    .kl-gps-chip {
        display: inline-flex; align-items: center; gap: 4px;
        font-size: 10px; font-weight: 600;
        color: oklch(36% 0.120 155);
        padding: 2px 7px; border-radius: 9999px;
        background: oklch(93% 0.055 155 / 60%);
        border: 1px solid oklch(76% 0.090 155 / 40%);
    }
    .dark .kl-gps-chip {
        color: oklch(72% 0.155 155);
        background: oklch(20% 0.042 155 / 55%);
        border-color: oklch(32% 0.075 155 / 45%);
    }

    /* ── Modal backdrop + card ── */
    .kl-modal-backdrop {
        position: fixed; inset: 0; z-index: 50;
        display: flex; align-items: center; justify-content: center;
        padding: 16px;
        background: oklch(8% 0.010 255 / 55%);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
    }
    .kl-modal-card {
        position: relative;
        background: oklch(99.5% 0.003 250 / 96%);
        backdrop-filter: blur(24px) saturate(1.4);
        -webkit-backdrop-filter: blur(24px) saturate(1.4);
        border: 1px solid oklch(84% 0.007 250 / 70%);
        border-radius: var(--r-xl);
        width: 100%; max-width: 420px;
        overflow: hidden;
        box-shadow: 0 24px 60px oklch(8% 0.010 255 / 30%);
    }
    .dark .kl-modal-card {
        background: oklch(17% 0.013 255 / 92%);
        border-color: oklch(30% 0.010 255 / 45%);
        box-shadow: 0 24px 60px oklch(4% 0.008 255 / 55%);
    }
    .kl-modal-card::before {
        content: '';
        position: absolute; top: 0; left: 0; right: 0; height: 1px; pointer-events: none;
        background: linear-gradient(90deg, transparent, oklch(85% 0.010 255 / 50%) 40%, oklch(85% 0.010 255 / 65%) 50%, oklch(85% 0.010 255 / 50%) 60%, transparent);
    }

    /* ── Modal status pills ── */
    .kl-status-pill {
        display: block; text-align: center;
        padding: 9px 4px; border-radius: var(--r-lg);
        font-size: 10px; font-weight: 600;
        cursor: pointer;
        background: oklch(97% 0.005 250 / 60%);
        border: 1.5px solid var(--border);
        color: var(--text-2);
        transition: all var(--dur-fast) var(--ease-out);
        user-select: none;
    }
    .dark .kl-status-pill {
        background: oklch(20% 0.012 255 / 55%);
        border-color: oklch(28% 0.010 255 / 45%);
    }
    .kl-status-pill:hover {
        border-color: oklch(72% 0.100 260 / 55%);
        color: var(--text-1);
    }

    /* Active states per status */
    .kl-status-pill[data-val="hadir"].active,
    .kl-status-pill[data-val="hadir"]:has(input:checked) { background: oklch(93% 0.055 155 / 80%); border-color: oklch(62% 0.120 155); color: oklch(26% 0.100 155); }
    .kl-status-pill[data-val="terlambat"].active           { background: oklch(97% 0.055 70  / 80%); border-color: oklch(68% 0.125 70 ); color: oklch(28% 0.095 70 ); }
    .kl-status-pill[data-val="izin"].active                { background: oklch(94% 0.040 260 / 80%); border-color: oklch(62% 0.115 260); color: oklch(26% 0.095 260); }
    .kl-status-pill[data-val="sakit"].active               { background: oklch(94% 0.040 260 / 80%); border-color: oklch(62% 0.115 260); color: oklch(26% 0.095 260); }
    .kl-status-pill[data-val="tugas_luar"].active          { background: oklch(95% 0.040 290 / 80%); border-color: oklch(62% 0.115 290); color: oklch(26% 0.095 290); }
    .kl-status-pill[data-val="alpha"].active               { background: oklch(97% 0.040 27  / 80%); border-color: oklch(68% 0.120 27 ); color: oklch(26% 0.095 27 ); }
    .dark .kl-status-pill[data-val="hadir"].active     { background: oklch(20% 0.042 155 / 68%); border-color: oklch(40% 0.085 155); color: oklch(74% 0.160 155); }
    .dark .kl-status-pill[data-val="terlambat"].active { background: oklch(20% 0.042 70  / 68%); border-color: oklch(40% 0.088 70 ); color: oklch(80% 0.158 70 ); }
    .dark .kl-status-pill[data-val="izin"].active      { background: oklch(18% 0.035 260 / 68%); border-color: oklch(36% 0.075 260); color: oklch(72% 0.160 260); }
    .dark .kl-status-pill[data-val="sakit"].active     { background: oklch(18% 0.035 260 / 68%); border-color: oklch(36% 0.075 260); color: oklch(72% 0.160 260); }
    .dark .kl-status-pill[data-val="tugas_luar"].active{ background: oklch(18% 0.038 290 / 68%); border-color: oklch(36% 0.075 290); color: oklch(72% 0.160 290); }
    .dark .kl-status-pill[data-val="alpha"].active     { background: oklch(20% 0.042 27  / 68%); border-color: oklch(38% 0.082 27 ); color: oklch(70% 0.190 27 ); }

    /* ── Modal form inputs ── */
    .kl-input {
        width: 100%; padding: 10px 13px;
        border: 1px solid var(--border);
        border-radius: var(--r-lg);
        font-size: 12px; font-weight: 500;
        color: var(--text-1);
        background: oklch(99.5% 0.003 250 / 60%);
        outline: none;
        transition: border-color var(--dur-fast) ease, box-shadow var(--dur-fast) ease;
    }
    .dark .kl-input {
        background: oklch(20% 0.012 255 / 55%);
        border-color: oklch(28% 0.010 255 / 45%);
        color-scheme: dark;
    }
    .kl-input:focus {
        border-color: var(--accent-ring);
        box-shadow: 0 0 0 3px oklch(52% 0.190 260 / 12%);
    }
    .kl-input::placeholder { color: var(--text-3); }
    .kl-label {
        display: block; font-size: 11px; font-weight: 600;
        color: var(--text-2); margin-bottom: 6px;
        letter-spacing: 0.03em;
    }

    /* ── Modal buttons ── */
    .kl-btn-cancel {
        flex: 1; padding: 11px;
        border: 1px solid var(--border);
        border-radius: var(--r-lg);
        font-size: 12px; font-weight: 600;
        color: var(--text-2); cursor: pointer;
        background: oklch(97% 0.005 250 / 50%);
        transition: all var(--dur-fast) var(--ease-out);
    }
    .dark .kl-btn-cancel {
        background: oklch(20% 0.012 255 / 50%);
        border-color: oklch(28% 0.010 255 / 45%);
    }
    .kl-btn-cancel:hover { background: var(--surface-2); border-color: var(--border); color: var(--text-1); }
    .kl-btn-save {
        flex: 1; padding: 11px;
        background: var(--accent);
        border: none; border-radius: var(--r-lg);
        font-size: 12px; font-weight: 700;
        color: var(--text-inv); cursor: pointer;
        box-shadow: 0 4px 16px oklch(52% 0.190 260 / 25%);
        transition: all var(--dur-fast) var(--ease-out);
    }
    .kl-btn-save:hover {
        background: var(--accent-h);
        box-shadow: 0 6px 20px oklch(52% 0.190 260 / 35%);
        transform: translateY(-1px);
    }
    .kl-btn-save:active { transform: scale(0.97); }

    /* ── Mono util ── */
    .g-mono { font-family: 'Geist Mono', 'Courier New', monospace; }

    /* ── Stagger mount ── */
    .kl-stagger > * {
        opacity: 0; transform: translateY(8px);
        animation: kl-up var(--dur-page) var(--ease-out) forwards;
    }
    .kl-stagger > *:nth-child(1) { animation-delay: 0.04s; }
    .kl-stagger > *:nth-child(2) { animation-delay: 0.10s; }
    .kl-stagger > *:nth-child(3) { animation-delay: 0.17s; }
    .kl-stagger > *:nth-child(4) { animation-delay: 0.24s; }
    @keyframes kl-up { to { opacity: 1; transform: translateY(0); } }

    /* ── Section label ── */
    .kl-section-label {
        font-size: 9px; font-weight: 600;
        letter-spacing: 0.12em; text-transform: uppercase;
        color: var(--text-3); font-family: 'Geist Mono', monospace;
    }
</style>
@endpush

@section('content')

{{-- Ambient glows --}}
<div class="kl-glow-1" aria-hidden="true"></div>
<div class="kl-glow-2" aria-hidden="true"></div>

@php
    $totalGuru  = $guruAktif->count();
    $sudahAbsen = $absensiHari->count();
    $hadir      = $absensiHari->whereIn('status', ['hadir','terlambat'])->count();
    $tidakHadir = $absensiHari->whereIn('status', ['izin','sakit','tugas_luar','alpha'])->count();
    $belum      = $totalGuru - $sudahAbsen;
@endphp

<div class="max-w-4xl mx-auto space-y-4 pb-8 relative z-10 kl-stagger" x-data="kelolaAbsensi()">

    {{-- ── 1. Header: tanggal + controls ── --}}
    <div class="kl-header-glass">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            {{-- Title --}}
            <div>
                <p class="kl-section-label">Manajemen Absensi</p>
                <h2 class="text-base font-bold mt-1" style="color:var(--text-1);">
                    {{ $tanggal->translatedFormat('l, d F Y') }}
                </h2>
                <p style="font-size:11px;color:var(--text-3);margin-top:2px;">
                    <span class="g-mono">{{ $sudahAbsen }}</span>/{{ $totalGuru }} guru tercatat
                </p>
            </div>

            {{-- Controls --}}
            <div class="flex items-center gap-2 flex-wrap">
                {{-- Date picker --}}
                <form method="GET" action="{{ route('absensi.kelola') }}">
                    <input type="date" name="tanggal"
                           value="{{ $tanggal->format('Y-m-d') }}"
                           max="{{ today()->format('Y-m-d') }}"
                           onchange="this.form.submit()"
                           class="kl-date-input">
                </form>

                {{-- Auto Alpha --}}
                <form method="POST" action="{{ route('absensi.auto-alpha') }}"
                      onsubmit="return confirm('Tandai semua guru yang belum absen sebagai ALPHA pada {{ $tanggal->format('d/m/Y') }}?')">
                    @csrf
                    <input type="hidden" name="tanggal" value="{{ $tanggal->format('Y-m-d') }}">
                    <button type="submit" class="btn-alpha">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Auto Alpha
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- ── 2. Stat pills ── --}}
    <div class="flex gap-3">
        <div class="kl-stat kl-stat-total">
            <p class="g-mono font-bold" style="font-size:24px;line-height:1;color:var(--text-1);">{{ $totalGuru }}</p>
            <p class="kl-section-label" style="margin-top:5px;color:var(--text-3);">Total</p>
        </div>
        <div class="kl-stat kl-stat-hadir">
            <p class="g-mono font-bold" style="font-size:24px;line-height:1;color:oklch(28% 0.100 155);">{{ $hadir }}</p>
            <p class="kl-section-label" style="margin-top:5px;color:oklch(42% 0.130 155);">Hadir</p>
        </div>
        <div class="kl-stat kl-stat-tidak">
            <p class="g-mono font-bold" style="font-size:24px;line-height:1;color:oklch(32% 0.095 70);">{{ $tidakHadir }}</p>
            <p class="kl-section-label" style="margin-top:5px;color:oklch(45% 0.120 70);">Tdk Hadir</p>
        </div>
        <div class="kl-stat kl-stat-belum">
            <p class="g-mono font-bold" style="font-size:24px;line-height:1;color:oklch(30% 0.095 27);">{{ $belum }}</p>
            <p class="kl-section-label" style="margin-top:5px;color:oklch(44% 0.120 27);">Belum</p>
        </div>
    </div>

    {{-- ── 3. Tabel Absensi ── --}}
    <div class="kl-glass">
        {{-- Table header --}}
        <div class="px-5 py-3.5 flex items-center justify-between" style="border-bottom:1px solid var(--border);">
            <h3 class="text-sm font-bold" style="color:var(--text-1);">Daftar Absensi</h3>
            <span class="kl-section-label">
                <span class="g-mono">{{ $sudahAbsen }}</span>/{{ $totalGuru }} tercatat
            </span>
        </div>

        {{-- Scroll container --}}
        <div class="overflow-x-auto">
            <table class="kl-table">
                <thead>
                    <tr>
                        <th style="width:40%;">Guru</th>
                        <th>Status</th>
                        <th class="hidden sm:table-cell">Jam</th>
                        <th class="hidden md:table-cell">Keterangan</th>
                        <th class="hidden lg:table-cell">Sumber</th>
                        <th style="width:80px;text-align:right;padding-right:16px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($guruAktif as $guru)
                    @php
                        $absen = $absensiHari->get($guru->id);
                        $badgeClass = $absen ? match($absen->status) {
                            'hadir'      => 'kb-hadir',
                            'terlambat'  => 'kb-terlambat',
                            'izin'       => 'kb-izin',
                            'sakit'      => 'kb-sakit',
                            'tugas_luar' => 'kb-tugas',
                            'alpha'      => 'kb-alpha',
                            default      => 'kb-belum',
                        } : 'kb-belum';
                        $initial = strtoupper(substr($guru->nama_lengkap, 0, 1));
                    @endphp
                    <tr>
                        {{-- Guru info --}}
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="kl-avatar">{{ $initial }}</div>
                                <div class="min-w-0">
                                    <p class="font-semibold truncate" style="color:var(--text-1);font-size:12px;">{{ $guru->nama_lengkap }}</p>
                                    @if($guru->jabatan)
                                        <p style="font-size:10px;color:var(--text-3);margin-top:1px;">{{ $guru->jabatan }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- Status --}}
                        <td>
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <span class="kl-badge {{ $badgeClass }}">
                                    {{ $absen ? $absen->label_status : 'Belum' }}
                                </span>
                                @if($absen && $absen->terlambat_menit > 0)
                                    <span class="kl-badge kb-terlambat" style="font-size:9px;">+<span class="g-mono">{{ $absen->terlambat_menit }}</span>m</span>
                                @endif
                            </div>
                        </td>

                        {{-- Jam masuk --}}
                        <td class="hidden sm:table-cell">
                            @if($absen?->jam_masuk)
                                <span class="g-mono font-semibold" style="font-size:12px;color:var(--text-1);">{{ substr($absen->jam_masuk, 0, 5) }}</span>
                            @else
                                <span style="color:var(--text-3);">—</span>
                            @endif
                        </td>

                        {{-- Keterangan --}}
                        <td class="hidden md:table-cell" style="max-width:160px;">
                            @if($absen?->keterangan)
                                <span class="truncate block" style="font-size:11px;color:var(--text-2);font-style:italic;">{{ $absen->keterangan }}</span>
                            @else
                                <span style="color:var(--text-3);">—</span>
                            @endif
                        </td>

                        {{-- Sumber --}}
                        <td class="hidden lg:table-cell">
                            @if($absen)
                                @if($absen->isSelfCheckIn())
                                    <span class="kl-gps-chip">
                                        <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        GPS{{ $absen->jarak_meter ? ' · ' . $absen->jarak_meter . 'm' : '' }}
                                    </span>
                                @else
                                    <span style="font-size:10px;color:var(--text-3);">Manual</span>
                                @endif
                            @else
                                <span style="color:var(--text-3);">—</span>
                            @endif
                        </td>

                        {{-- Aksi --}}
                        <td>
                            <div class="flex items-center gap-1 justify-end">
                                {{-- Edit / Input --}}
                                <button type="button"
                                        class="kl-action-btn"
                                        title="{{ $absen ? 'Edit' : 'Input' }} Absensi"
                                        @click="bukaModal(
                                            {{ $guru->id }},
                                            '{{ addslashes($guru->nama_lengkap) }}',
                                            {{ $absen ? "'" . $absen->status . "'" : 'null' }},
                                            {{ $absen ? "'" . substr($absen->jam_masuk ?? '', 0, 5) . "'" : "''" }},
                                            '{{ addslashes($absen?->keterangan ?? '') }}'
                                        )">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                        <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>

                                {{-- Hapus --}}
                                @if($absen)
                                <form method="POST" action="{{ route('absensi.hapus', $absen->id) }}"
                                      onsubmit="return confirm('Hapus absensi {{ addslashes($guru->nama_lengkap) }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="kl-action-btn danger" title="Hapus">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                            <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
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
    </div>

    {{-- ── 4. Modal Input Absensi ── --}}
    <div x-show="modal.open"
         x-transition:enter="transition duration-200 ease-out"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition duration-150 ease-in"
         x-transition:leave-end="opacity-0"
         class="kl-modal-backdrop"
         style="display:none;">

        <div @click.outside="modal.open = false"
             x-transition:enter="transition duration-200 ease-out"
             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition duration-150 ease-in"
             x-transition:leave-end="opacity-0 scale-95"
             class="kl-modal-card">

            {{-- Modal header --}}
            <div class="px-6 pt-6 pb-4 flex items-start justify-between" style="border-bottom:1px solid var(--border);">
                <div>
                    <p class="kl-section-label">Input / Edit Absensi</p>
                    <h3 class="text-sm font-bold mt-1" style="color:var(--text-1);" x-text="modal.namaGuru"></h3>
                    <p style="font-size:10px;color:var(--text-3);margin-top:2px;">{{ $tanggal->translatedFormat('d F Y') }}</p>
                </div>
                <button @click="modal.open = false"
                        class="kl-action-btn" style="flex-shrink:0;margin-top:2px;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Modal form --}}
            <form method="POST" action="{{ route('absensi.manual') }}" class="px-6 pb-6 pt-5 space-y-5">
                @csrf
                <input type="hidden" name="guru_id" :value="modal.guruId">
                <input type="hidden" name="tanggal" value="{{ $tanggal->format('Y-m-d') }}">

                {{-- Status selector --}}
                <div>
                    <label class="kl-label">Status <span style="color:var(--danger);">*</span></label>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach([
                            ['hadir',      'Hadir'],
                            ['terlambat',  'Terlambat'],
                            ['izin',       'Izin'],
                            ['sakit',      'Sakit'],
                            ['tugas_luar', 'Tugas Luar'],
                            ['alpha',      'Alpha'],
                        ] as [$val, $label])
                        <label class="kl-status-pill"
                               data-val="{{ $val }}"
                               :class="{ 'active': modal.status === '{{ $val }}' }">
                            <input type="radio" name="status" value="{{ $val }}"
                                   x-model="modal.status" class="sr-only">
                            {{ $label }}
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Jam masuk (hadir/terlambat) --}}
                <div x-show="['hadir','terlambat'].includes(modal.status)"
                     x-transition:enter="transition duration-150"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0">
                    <label class="kl-label">Jam Masuk</label>
                    <input type="time" name="jam_masuk" x-model="modal.jamMasuk" class="kl-input">
                </div>

                {{-- Keterangan --}}
                <div>
                    <label class="kl-label">
                        Keterangan
                        <span style="color:var(--text-3);font-weight:400;margin-left:4px;">(opsional)</span>
                    </label>
                    <input type="text" name="keterangan" x-model="modal.keterangan"
                           placeholder="Misal: izin keperluan keluarga"
                           class="kl-input">
                </div>

                {{-- Actions --}}
                <div class="flex gap-2.5 pt-1">
                    <button type="button" @click="modal.open = false" class="kl-btn-cancel">Batal</button>
                    <button type="submit" class="kl-btn-save">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="display:inline;vertical-align:-1px;margin-right:5px;"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function kelolaAbsensi() {
    return {
        modal: {
            open: false,
            guruId: null,
            namaGuru: '',
            status: 'hadir',
            jamMasuk: '',
            keterangan: '',
        },

        bukaModal(guruId, namaGuru, status, jamMasuk, keterangan) {
            this.modal = {
                open: true,
                guruId,
                namaGuru,
                status:      status      ?? 'hadir',
                jamMasuk:    jamMasuk    ?? '',
                keterangan:  keterangan  ?? '',
            };
        }
    };
}
</script>
@endpush