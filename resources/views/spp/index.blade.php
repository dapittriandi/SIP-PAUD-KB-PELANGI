@extends('layouts.app')

@section('title', 'Dashboard SPP — PAUD KB Pelangi')
@section('page-title', 'Pembayaran SPP')

@push('styles')
<style>
    /* ═══════════════════════════════════════════════════
       SPP INDEX — Glassmorphism
       Konsisten dengan index_blade_glass & kelola_blade_glass.
       Token OKLCH dari layouts.app.
    ═══════════════════════════════════════════════════ */

    /* ── Ambient glows ── */
    .spp-glow-1 {
        position: fixed; pointer-events: none; z-index: 0;
        top: -100px; left: -80px;
        width: 340px; height: 340px; border-radius: 50%;
        background: radial-gradient(circle, oklch(52% 0.190 195 / 10%) 0%, transparent 68%);
        opacity: 0;
    }
    .spp-glow-2 {
        position: fixed; pointer-events: none; z-index: 0;
        bottom: -80px; right: -100px;
        width: 300px; height: 300px; border-radius: 50%;
        background: radial-gradient(circle, oklch(52% 0.190 260 / 9%) 0%, transparent 68%);
        opacity: 0;
    }
    .dark .spp-glow-1,
    .dark .spp-glow-2 { opacity: 1; }

    /* ── Glass card base ── */
    .spp-glass {
        position: relative;
        background: oklch(99.5% 0.003 250 / 88%);
        backdrop-filter: blur(18px) saturate(1.3);
        -webkit-backdrop-filter: blur(18px) saturate(1.3);
        border: 1px solid oklch(85% 0.007 250 / 65%);
        border-radius: var(--r-xl);
        overflow: hidden;
    }
    .dark .spp-glass {
        background: oklch(18% 0.013 255 / 72%);
        border-color: oklch(30% 0.010 255 / 40%);
    }
    .spp-glass::before {
        content: '';
        position: absolute; top: 0; left: 0; right: 0; height: 1px; pointer-events: none;
        background: linear-gradient(90deg,
            transparent,
            oklch(88% 0.010 255 / 45%) 35%,
            oklch(88% 0.010 255 / 60%) 50%,
            oklch(88% 0.010 255 / 45%) 65%,
            transparent
        );
    }
    .dark .spp-glass::before {
        background: linear-gradient(90deg,
            transparent,
            oklch(55% 0.012 255 / 22%) 35%,
            oklch(55% 0.012 255 / 38%) 50%,
            oklch(55% 0.012 255 / 22%) 65%,
            transparent
        );
    }

    /* ── Banner hero ── */
    .spp-banner {
        position: relative; overflow: hidden;
        background: linear-gradient(135deg,
            oklch(22% 0.060 255) 0%,
            oklch(28% 0.075 260) 55%,
            oklch(32% 0.095 258) 100%
        );
        border-radius: var(--r-xl);
        padding: 28px 32px;
        border: 1px solid oklch(38% 0.060 258 / 50%);
        box-shadow: 0 8px 32px oklch(15% 0.060 255 / 30%);
    }
    .dark .spp-banner {
        background: linear-gradient(135deg,
            oklch(16% 0.055 258) 0%,
            oklch(20% 0.065 260) 55%,
            oklch(24% 0.080 258) 100%
        );
        border-color: oklch(32% 0.058 258 / 50%);
    }
    /* Teal radial glow */
    .spp-banner::before {
        content: '';
        position: absolute; inset: 0; pointer-events: none;
        background: radial-gradient(ellipse at 85% 50%,
            oklch(65% 0.185 195 / 18%) 0%, transparent 62%
        );
    }
    /* Frost shimmer */
    .spp-banner::after {
        content: '';
        position: absolute; top: 0; left: 0; right: 0; height: 1px; pointer-events: none;
        background: linear-gradient(90deg,
            transparent,
            oklch(80% 0.020 255 / 20%) 30%,
            oklch(80% 0.020 255 / 35%) 50%,
            oklch(80% 0.020 255 / 20%) 70%,
            transparent
        );
    }
    /* "SPP" watermark */
    .spp-banner-wm {
        position: absolute; right: -10px; bottom: -28px; pointer-events: none;
        font-size: 120px; font-weight: 700; line-height: 1;
        letter-spacing: -4px;
        color: oklch(80% 0.010 255 / 5%);
        font-family: 'Geist', system-ui;
        user-select: none;
    }
    .spp-banner-inner {
        position: relative; z-index: 1;
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 16px;
    }

    /* ── Banner CTA button ── */
    .spp-cta {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 10px 22px;
        background: oklch(58% 0.185 195);
        color: oklch(99% 0.004 250);
        font-size: 13px; font-weight: 700;
        border-radius: var(--r-lg);
        text-decoration: none;
        border: none;
        box-shadow: 0 4px 16px oklch(52% 0.185 195 / 38%);
        transition: all var(--dur-mid) var(--ease-out);
        white-space: nowrap;
    }
    .spp-cta:hover {
        background: oklch(64% 0.190 195);
        box-shadow: 0 6px 22px oklch(52% 0.185 195 / 50%);
        transform: translateY(-1px);
    }
    .spp-cta:active { transform: scale(0.97); }

    /* ── Stat pills ── */
    .spp-stat-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 12px; }
    @media(max-width:768px) { .spp-stat-grid { grid-template-columns: repeat(2,1fr); } }
    @media(max-width:400px) { .spp-stat-grid { grid-template-columns: 1fr; } }

    .spp-stat {
        position: relative; overflow: hidden;
        border-radius: var(--r-xl);
        padding: 18px 20px;
        transition: transform var(--dur-mid) var(--ease-out), box-shadow var(--dur-mid) ease;
        cursor: default;
    }
    .spp-stat:hover { transform: translateY(-3px); }

    /* Stat teal — pemasukan */
    .spp-stat-teal {
        background: oklch(94% 0.050 195 / 80%);
        border: 1px solid oklch(75% 0.100 195 / 45%);
    }
    .dark .spp-stat-teal {
        background: oklch(20% 0.045 195 / 65%);
        border-color: oklch(32% 0.085 195 / 45%);
    }
    /* Stat green — lunas */
    .spp-stat-green {
        background: oklch(93% 0.055 155 / 80%);
        border: 1px solid oklch(78% 0.090 155 / 40%);
    }
    .dark .spp-stat-green {
        background: oklch(20% 0.042 155 / 65%);
        border-color: oklch(32% 0.075 155 / 45%);
    }
    /* Stat amber — tunggakan */
    .spp-stat-amber {
        background: oklch(97% 0.055 75 / 80%);
        border: 1px solid oklch(82% 0.090 75 / 40%);
    }
    .dark .spp-stat-amber {
        background: oklch(20% 0.045 75 / 65%);
        border-color: oklch(33% 0.082 75 / 45%);
    }
    /* Stat navy — total siswa */
    .spp-stat-navy {
        background: oklch(94% 0.040 260 / 80%);
        border: 1px solid oklch(78% 0.065 260 / 40%);
    }
    .dark .spp-stat-navy {
        background: oklch(18% 0.035 260 / 65%);
        border-color: oklch(28% 0.065 260 / 45%);
    }

    /* Stat icon box */
    .spp-stat-icon {
        width: 36px; height: 36px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 12px; flex-shrink: 0;
    }
    .spp-stat-teal  .spp-stat-icon { background: oklch(88% 0.080 195 / 60%); color: oklch(38% 0.175 195); }
    .spp-stat-green .spp-stat-icon { background: oklch(88% 0.080 155 / 60%); color: oklch(36% 0.145 155); }
    .spp-stat-amber .spp-stat-icon { background: oklch(92% 0.090 75  / 60%); color: oklch(42% 0.155 75 ); }
    .spp-stat-navy  .spp-stat-icon { background: oklch(88% 0.060 260 / 55%); color: oklch(34% 0.140 260); }
    .dark .spp-stat-teal  .spp-stat-icon { background: oklch(24% 0.065 195 / 60%); color: oklch(70% 0.175 195); }
    .dark .spp-stat-green .spp-stat-icon { background: oklch(24% 0.058 155 / 60%); color: oklch(70% 0.160 155); }
    .dark .spp-stat-amber .spp-stat-icon { background: oklch(24% 0.065 75  / 60%); color: oklch(80% 0.170 75 ); }
    .dark .spp-stat-navy  .spp-stat-icon { background: oklch(22% 0.048 260 / 60%); color: oklch(70% 0.160 260); }

    .spp-stat-val { font-size: 26px; font-weight: 700; line-height: 1; margin-bottom: 4px; font-family: 'Geist Mono', monospace; }
    .spp-stat-teal  .spp-stat-val { color: oklch(32% 0.150 195); }
    .spp-stat-green .spp-stat-val { color: oklch(28% 0.100 155); }
    .spp-stat-amber .spp-stat-val { color: oklch(34% 0.130 75 ); }
    .spp-stat-navy  .spp-stat-val { color: oklch(28% 0.095 260); }
    .dark .spp-stat-teal  .spp-stat-val { color: oklch(68% 0.175 195); }
    .dark .spp-stat-green .spp-stat-val { color: oklch(72% 0.155 155); }
    .dark .spp-stat-amber .spp-stat-val { color: oklch(80% 0.170 75 ); }
    .dark .spp-stat-navy  .spp-stat-val { color: oklch(72% 0.160 260); }

    .spp-stat-lbl { font-size: 11px; font-weight: 600; letter-spacing: 0.06em; text-transform: uppercase; }
    .spp-stat-teal  .spp-stat-lbl { color: oklch(42% 0.130 195); }
    .spp-stat-green .spp-stat-lbl { color: oklch(40% 0.115 155); }
    .spp-stat-amber .spp-stat-lbl { color: oklch(46% 0.125 75 ); }
    .spp-stat-navy  .spp-stat-lbl { color: oklch(40% 0.110 260); }
    .dark .spp-stat-teal  .spp-stat-lbl { color: oklch(55% 0.145 195); }
    .dark .spp-stat-green .spp-stat-lbl { color: oklch(52% 0.130 155); }
    .dark .spp-stat-amber .spp-stat-lbl { color: oklch(62% 0.150 75 ); }
    .dark .spp-stat-navy  .spp-stat-lbl { color: oklch(52% 0.130 260); }

    .spp-stat-sub { font-size: 11px; margin-top: 4px; color: var(--text-3); }

    /* Stat bg circle decoration */
    .spp-stat::after {
        content: ''; position: absolute;
        bottom: -18px; right: -18px;
        width: 72px; height: 72px; border-radius: 50%;
        pointer-events: none;
    }
    .spp-stat-teal::after  { background: oklch(58% 0.185 195 / 12%); }
    .spp-stat-green::after { background: oklch(52% 0.175 155 / 12%); }
    .spp-stat-amber::after { background: oklch(60% 0.175 75  / 12%); }
    .spp-stat-navy::after  { background: oklch(52% 0.165 260 / 10%); }

    /* ── Tarif strip ── */
    .spp-tarif {
        background: oklch(99.5% 0.003 250 / 82%);
        backdrop-filter: blur(14px) saturate(1.2);
        -webkit-backdrop-filter: blur(14px) saturate(1.2);
        border: 1px solid oklch(85% 0.007 250 / 60%);
        border-radius: var(--r-lg);
        padding: 12px 18px;
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 10px;
        font-size: 13px;
    }
    .dark .spp-tarif {
        background: oklch(19% 0.013 255 / 72%);
        border-color: oklch(28% 0.010 255 / 42%);
    }
    .spp-tarif-icon {
        width: 34px; height: 34px;
        border-radius: 9px;
        display: flex; align-items: center; justify-content: center;
        background: oklch(92% 0.055 195 / 60%);
        border: 1px solid oklch(78% 0.090 195 / 40%);
        color: oklch(38% 0.175 195);
        flex-shrink: 0;
    }
    .dark .spp-tarif-icon {
        background: oklch(22% 0.050 195 / 60%);
        border-color: oklch(32% 0.085 195 / 45%);
        color: oklch(68% 0.175 195);
    }
    .spp-tarif-lbl { font-size: 9px; font-weight: 600; letter-spacing: 0.12em; text-transform: uppercase; color: var(--text-3); font-family: 'Geist Mono', monospace; }
    .spp-tarif-val { font-size: 13px; font-weight: 600; color: var(--text-1); margin-top: 2px; }
    .spp-tarif-val b { color: oklch(38% 0.175 195); }
    .dark .spp-tarif-val b { color: oklch(68% 0.175 195); }

    .spp-tarif-edit {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 6px 13px;
        border-radius: var(--r-lg);
        font-size: 11px; font-weight: 600;
        color: oklch(38% 0.175 195);
        border: 1.5px solid oklch(70% 0.120 195 / 60%);
        background: oklch(92% 0.055 195 / 50%);
        text-decoration: none;
        transition: all var(--dur-fast) var(--ease-out);
        white-space: nowrap;
    }
    .dark .spp-tarif-edit {
        color: oklch(68% 0.175 195);
        border-color: oklch(32% 0.090 195 / 55%);
        background: oklch(22% 0.050 195 / 50%);
    }
    .spp-tarif-edit:hover {
        background: oklch(88% 0.070 195 / 70%);
        border-color: oklch(60% 0.145 195 / 65%);
        transform: translateY(-1px);
    }
    .dark .spp-tarif-edit:hover {
        background: oklch(26% 0.058 195 / 65%);
        border-color: oklch(40% 0.100 195 / 60%);
    }

    /* ── Filter bar ── */
    .spp-filter {
        background: oklch(99.5% 0.003 250 / 82%);
        backdrop-filter: blur(14px) saturate(1.2);
        -webkit-backdrop-filter: blur(14px) saturate(1.2);
        border: 1px solid oklch(85% 0.007 250 / 60%);
        border-radius: var(--r-xl);
        padding: 14px 18px;
        display: flex; flex-wrap: wrap; gap: 10px; align-items: flex-end;
    }
    .dark .spp-filter {
        background: oklch(18% 0.013 255 / 70%);
        border-color: oklch(28% 0.010 255 / 42%);
    }

    .spp-filter-lbl {
        font-size: 9px; font-weight: 600;
        letter-spacing: 0.12em; text-transform: uppercase;
        color: var(--text-3); font-family: 'Geist Mono', monospace;
        margin-bottom: 5px;
    }
    .spp-select, .spp-input {
        padding: 8px 11px;
        border: 1px solid var(--border);
        border-radius: var(--r-lg);
        font-size: 12px; font-weight: 500;
        color: var(--text-1);
        background: oklch(99.5% 0.003 250 / 65%);
        outline: none;
        transition: border-color var(--dur-fast) ease, box-shadow var(--dur-fast) ease;
        font-family: inherit;
    }
    .dark .spp-select, .dark .spp-input {
        background: oklch(20% 0.012 255 / 60%);
        border-color: oklch(28% 0.010 255 / 45%);
        color: var(--text-1);
        color-scheme: dark;
    }
    .spp-select:focus, .spp-input:focus {
        border-color: oklch(62% 0.140 195 / 70%);
        box-shadow: 0 0 0 3px oklch(58% 0.185 195 / 10%);
    }
    .spp-input::placeholder { color: var(--text-3); }

    /* Period select combo */
    .spp-period-wrap {
        display: inline-flex; align-items: center; gap: 5px;
        background: oklch(97% 0.005 250 / 55%);
        border: 1px solid var(--border);
        border-radius: var(--r-lg);
        padding: 4px 10px;
    }
    .dark .spp-period-wrap {
        background: oklch(20% 0.012 255 / 50%);
        border-color: oklch(28% 0.010 255 / 40%);
    }
    .spp-period-wrap select {
        border: none; background: transparent;
        font-size: 12px; font-weight: 600; color: var(--text-1);
        font-family: inherit; outline: none; cursor: pointer;
        color-scheme: light;
    }
    .dark .spp-period-wrap select { color-scheme: dark; }

    /* Search wrap */
    .spp-search-wrap { position: relative; flex: 1; min-width: 200px; }
    .spp-search-wrap .spp-search-icon {
        position: absolute; left: 10px; top: 50%; transform: translateY(-50%);
        color: var(--text-3); pointer-events: none;
    }
    .spp-search-wrap .spp-input { padding-left: 32px; width: 100%; }

    .spp-btn-search {
        padding: 8px 18px;
        background: oklch(58% 0.185 195);
        color: oklch(99% 0.004 250);
        font-size: 12px; font-weight: 700;
        border: none; border-radius: var(--r-lg);
        cursor: pointer;
        box-shadow: 0 3px 12px oklch(52% 0.185 195 / 28%);
        transition: all var(--dur-fast) var(--ease-out);
        font-family: inherit;
        white-space: nowrap;
    }
    .spp-btn-search:hover {
        background: oklch(64% 0.190 195);
        box-shadow: 0 4px 16px oklch(52% 0.185 195 / 38%);
    }
    .spp-btn-reset {
        padding: 8px 13px;
        border: 1px solid var(--border);
        background: oklch(97% 0.005 250 / 50%);
        color: var(--text-2);
        font-size: 12px; font-weight: 600;
        border-radius: var(--r-lg);
        text-decoration: none;
        transition: all var(--dur-fast) ease;
        font-family: inherit;
        white-space: nowrap;
    }
    .dark .spp-btn-reset {
        background: oklch(20% 0.012 255 / 50%);
        border-color: oklch(28% 0.010 255 / 45%);
    }
    .spp-btn-reset:hover { background: var(--surface-2); color: var(--text-1); }

    /* ── Table ── */
    .spp-table-wrap { overflow-x: auto; }
    .spp-table { width: 100%; border-collapse: collapse; font-size: 12px; }
    .spp-table thead tr {
        background: oklch(97% 0.005 250 / 50%);
        border-bottom: 1px solid var(--border);
    }
    .dark .spp-table thead tr {
        background: oklch(20% 0.012 255 / 50%);
        border-color: oklch(28% 0.010 255 / 40%);
    }
    .spp-table th {
        padding: 10px 16px; text-align: left;
        font-size: 9px; font-weight: 600; letter-spacing: 0.10em;
        text-transform: uppercase; color: var(--text-3);
        white-space: nowrap;
        font-family: 'Geist Mono', monospace;
    }
    .spp-table td { padding: 12px 16px; vertical-align: middle; }
    .spp-table tbody tr { border-bottom: 1px solid var(--border); transition: background 0.12s ease; }
    .dark .spp-table tbody tr { border-color: oklch(26% 0.010 255 / 35%); }
    .spp-table tbody tr:last-child { border-bottom: none; }
    .spp-table tbody tr:hover { background: oklch(97% 0.005 250 / 35%); }
    .dark .spp-table tbody tr:hover { background: oklch(22% 0.012 255 / 40%); }

    /* No kwitansi chip */
    .spp-no-kwit {
        font-family: 'Geist Mono', monospace;
        font-size: 10px; font-weight: 600;
        background: var(--surface-2);
        color: var(--text-2);
        border: 1px solid var(--border);
        padding: 2px 8px; border-radius: 5px;
        white-space: nowrap;
    }
    /* Kelompok pill */
    .spp-klp-pill {
        display: inline-flex; align-items: center;
        background: oklch(92% 0.040 260 / 70%);
        color: oklch(28% 0.090 260);
        border: 1px solid oklch(76% 0.065 260 / 45%);
        padding: 2px 9px; border-radius: 9999px;
        font-size: 10px; font-weight: 600;
    }
    .dark .spp-klp-pill {
        background: oklch(18% 0.038 260 / 65%);
        color: oklch(70% 0.160 260);
        border-color: oklch(30% 0.065 260 / 45%);
    }
    /* Lunas badge */
    .spp-badge-lunas {
        display: inline-flex; align-items: center; gap: 4px;
        background: oklch(93% 0.055 155 / 70%);
        color: oklch(28% 0.100 155);
        border: 1px solid oklch(78% 0.090 155 / 45%);
        padding: 3px 10px; border-radius: 9999px;
        font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;
    }
    .dark .spp-badge-lunas {
        background: oklch(20% 0.042 155 / 65%);
        color: oklch(74% 0.160 155);
        border-color: oklch(32% 0.075 155 / 48%);
    }
    /* Nominal */
    .spp-nominal {
        font-family: 'Geist Mono', monospace;
        font-size: 12px; font-weight: 700;
        color: var(--text-1);
    }
    /* Action buttons */
    .spp-btn-row { display: flex; align-items: center; gap: 5px; justify-content: flex-end; }
    .spp-btn-icon {
        width: 30px; height: 30px; border-radius: 8px;
        display: inline-flex; align-items: center; justify-content: center;
        border: 1px solid var(--border);
        background: transparent; color: var(--text-3);
        cursor: pointer; text-decoration: none;
        transition: all var(--dur-fast) var(--ease-out);
        flex-shrink: 0;
    }
    .spp-btn-icon:hover {
        background: oklch(92% 0.055 195 / 60%);
        border-color: oklch(70% 0.120 195 / 55%);
        color: oklch(38% 0.175 195);
    }
    .spp-btn-icon.danger:hover {
        background: oklch(97% 0.040 27 / 70%);
        border-color: oklch(82% 0.070 27 / 50%);
        color: oklch(36% 0.100 27);
    }
    .dark .spp-btn-icon:hover {
        background: oklch(22% 0.050 195 / 55%);
        border-color: oklch(32% 0.090 195 / 50%);
        color: oklch(68% 0.175 195);
    }
    .dark .spp-btn-icon.danger:hover {
        background: oklch(20% 0.042 27 / 55%);
        border-color: oklch(30% 0.078 27 / 50%);
        color: oklch(68% 0.190 27);
    }

    /* ── Alert banners ── */
    .spp-alert {
        display: flex; align-items: center; gap: 10px;
        padding: 11px 15px; border-radius: var(--r-lg);
        font-size: 12px; font-weight: 600;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }
    .spp-alert-ok {
        background: oklch(93% 0.055 155 / 80%);
        border: 1px solid oklch(76% 0.090 155 / 50%);
        color: oklch(28% 0.100 155);
    }
    .dark .spp-alert-ok {
        background: oklch(20% 0.042 155 / 72%);
        border-color: oklch(32% 0.075 155 / 48%);
        color: oklch(74% 0.160 155);
    }
    .spp-alert-err {
        background: oklch(97% 0.040 27 / 80%);
        border: 1px solid oklch(82% 0.070 27 / 50%);
        color: oklch(32% 0.095 27);
    }
    .dark .spp-alert-err {
        background: oklch(20% 0.042 27 / 72%);
        border-color: oklch(32% 0.080 27 / 48%);
        color: oklch(70% 0.190 27);
    }

    /* ── Empty state ── */
    .spp-empty { padding: 64px 20px; text-align: center; }
    .spp-empty-icon {
        width: 56px; height: 56px; border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 14px;
        background: var(--surface-2);
        border: 1px solid var(--border);
        color: var(--text-3);
    }

    /* ── Pagination wrapper ── */
    .spp-pagi { padding: 14px 20px; border-top: 1px solid var(--border); }
    .dark .spp-pagi { border-color: oklch(26% 0.010 255 / 35%); }

    /* ── Section label ── */
    .spp-label {
        font-size: 9px; font-weight: 600;
        letter-spacing: 0.12em; text-transform: uppercase;
        color: var(--text-3); font-family: 'Geist Mono', monospace;
    }

    /* ── Stagger mount ── */
    .spp-stagger > * {
        opacity: 0; transform: translateY(8px);
        animation: sppUp var(--dur-page) var(--ease-out) forwards;
    }
    .spp-stagger > *:nth-child(1) { animation-delay: 0.04s; }
    .spp-stagger > *:nth-child(2) { animation-delay: 0.10s; }
    .spp-stagger > *:nth-child(3) { animation-delay: 0.16s; }
    .spp-stagger > *:nth-child(4) { animation-delay: 0.22s; }
    .spp-stagger > *:nth-child(5) { animation-delay: 0.28s; }
    .spp-stagger > *:nth-child(6) { animation-delay: 0.34s; }
    @keyframes sppUp { to { opacity: 1; transform: translateY(0); } }

    .g-mono { font-family: 'Geist Mono', 'Courier New', monospace; }
</style>
@endpush

@section('content')

{{-- Ambient glows --}}
<div class="spp-glow-1" aria-hidden="true"></div>
<div class="spp-glow-2" aria-hidden="true"></div>

<div class="max-w-5xl mx-auto px-4 pb-16 relative z-10 space-y-4 spp-stagger">

    {{-- ── 1. Alert flash messages ── --}}
    @if(session('success'))
    <div class="spp-alert spp-alert-ok">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="spp-alert spp-alert-err">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
        {{ session('error') }}
    </div>
    @endif

    {{-- ── 2. Banner hero ── --}}
    <div class="spp-banner">
        <div class="spp-banner-wm" aria-hidden="true">SPP</div>
        <div class="spp-banner-inner">
            <div>
                <p class="spp-label" style="color:oklch(70% 0.010 255 / 60%);">PAUD KB Pelangi</p>
                <h1 style="font-size:22px;font-weight:700;color:#fff;margin:4px 0 6px;line-height:1.2;">Pembayaran SPP</h1>
                <p style="font-size:13px;color:oklch(80% 0.010 255 / 55%);">
                    {{ \Carbon\Carbon::create(null,$bulan)->translatedFormat('F') }} {{ $tahun }}
                </p>
            </div>
            <a href="{{ route('spp.konfirmasi', ['bulan'=>$bulan,'tahun'=>$tahun]) }}" class="spp-cta">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Konfirmasi Pembayaran
            </a>
        </div>
    </div>

    {{-- ── 3. Stat grid ── --}}
    @php
        $periodeLabel = \Carbon\Carbon::create(null,$bulan)->translatedFormat('F');
    @endphp
    <div class="spp-stat-grid">
        {{-- Pemasukan --}}
        <div class="spp-stat spp-stat-teal">
            <div class="spp-stat-icon">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="spp-stat-val">Rp {{ number_format($totalPemasukan/1000,0,',','.') }}K</div>
            <div class="spp-stat-lbl">Total Pemasukan</div>
            <div class="spp-stat-sub">{{ $periodeLabel }} {{ $tahun }}</div>
        </div>

        {{-- Lunas --}}
        <div class="spp-stat spp-stat-green">
            <div class="spp-stat-icon">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="spp-stat-val">{{ $totalBayar }}</div>
            <div class="spp-stat-lbl">Siswa Lunas</div>
            <div class="spp-stat-sub">dari {{ $totalSiswa }} siswa aktif</div>
        </div>

        {{-- Tunggakan --}}
        <div class="spp-stat spp-stat-amber">
            <div class="spp-stat-icon">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
            </div>
            <div class="spp-stat-val">{{ $tunggakan }}</div>
            <div class="spp-stat-lbl">Belum Bayar</div>
            <div class="spp-stat-sub">
                <a href="{{ route('spp.tunggakan',['bulan'=>$bulan,'tahun'=>$tahun]) }}"
                   style="font-weight:600;text-decoration:none;"
                   class="dark:text-amber-400">
                    Lihat daftar →
                </a>
            </div>
        </div>

        {{-- Total siswa --}}
        <div class="spp-stat spp-stat-navy">
            <div class="spp-stat-icon">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
            </div>
            <div class="spp-stat-val">{{ $totalSiswa }}</div>
            <div class="spp-stat-lbl">Total Siswa Aktif</div>
            <div class="spp-stat-sub">Tahun ajaran {{ $tahun }}</div>
        </div>
    </div>

    {{-- ── 4. Tarif strip ── --}}
    <div class="spp-tarif">
        <div style="display:flex;align-items:center;gap:12px;">
            <div class="spp-tarif-icon">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/></svg>
            </div>
            <div>
                <p class="spp-tarif-lbl">Tarif Aktif {{ $tahun }}</p>
                <p class="spp-tarif-val">
                    SPP {{ $tarif->nominal_spp_rupiah }} + Kebersihan {{ $tarif->nominal_kebersihan_rupiah }}
                    = <b>{{ $tarif->total_rupiah }}</b> / siswa
                </p>
            </div>
        </div>
        @if(Auth::user()->role === 'admin')
        <a href="{{ route('spp.tarif.index') }}" class="spp-tarif-edit">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Ubah Tarif
        </a>
        @endif
    </div>

    {{-- ── 5. Filter bar ── --}}
    <form method="GET" action="{{ route('spp.index') }}">
        <input type="hidden" name="bulan" value="{{ $bulan }}">
        <input type="hidden" name="tahun" value="{{ $tahun }}">
        <div class="spp-filter">
            {{-- Periode --}}
            <div>
                <p class="spp-filter-lbl">Periode</p>
                <div class="spp-period-wrap">
                    <select name="bulan" onchange="this.form.submit()">
                        @foreach(range(1,12) as $b)
                        <option value="{{ $b }}" @selected($b==$bulan)>{{ \Carbon\Carbon::create(null,$b)->translatedFormat('F') }}</option>
                        @endforeach
                    </select>
                    <span style="color:var(--text-3);font-size:11px;">/</span>
                    <select name="tahun" onchange="this.form.submit()">
                        @foreach(range(now()->year, now()->year-3, -1) as $y)
                        <option value="{{ $y }}" @selected($y==$tahun)>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Kelompok --}}
            <div>
                <p class="spp-filter-lbl">Kelompok</p>
                <select name="kelompok" class="spp-select">
                    <option value="">Semua</option>
                    @foreach($kelompokList as $k)
                    <option value="{{ $k }}" @selected(request('kelompok')==$k)>{{ $k }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Search --}}
            <div class="spp-search-wrap">
                <p class="spp-filter-lbl">Cari</p>
                <div style="position:relative;">
                    <svg class="spp-search-icon" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/></svg>
                    <input type="text" name="cari" class="spp-input" style="padding-left:32px;width:100%;"
                           value="{{ request('cari') }}" placeholder="Nama / NIS...">
                </div>
            </div>

            {{-- Actions --}}
            <div style="display:flex;align-items:flex-end;gap:6px;">
                <button type="submit" class="spp-btn-search">Cari</button>
                @if(request()->hasAny(['cari','kelompok']))
                <a href="{{ route('spp.index',['bulan'=>$bulan,'tahun'=>$tahun]) }}" class="spp-btn-reset">Reset</a>
                @endif
            </div>
        </div>
    </form>

    {{-- ── 6. Tabel pembayaran ── --}}
    <div class="spp-glass">
        {{-- Table header --}}
        <div class="px-5 py-3.5 flex items-center justify-between" style="border-bottom:1px solid var(--border);">
            <h3 class="text-sm font-bold" style="color:var(--text-1);">
                Riwayat Pembayaran —
                {{ \Carbon\Carbon::create(null,$bulan)->translatedFormat('F') }} {{ $tahun }}
            </h3>
            <span class="spp-label g-mono" style="background:var(--surface-2);border:1px solid var(--border);padding:3px 10px;border-radius:9999px;">
                {{ $pembayaran->total() }} transaksi
            </span>
        </div>

        @if($pembayaran->isEmpty())
        {{-- Empty state --}}
        <div class="spp-empty">
            <div class="spp-empty-icon">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <p class="text-sm font-bold" style="color:var(--text-2);">Belum ada pembayaran</p>
            <p style="font-size:11px;margin-top:4px;color:var(--text-3);">Periode ini belum ada data transaksi masuk.</p>
        </div>

        @else
        <div class="spp-table-wrap">
            <table class="spp-table">
                <thead>
                    <tr>
                        <th>No. Kwitansi</th>
                        <th>Siswa</th>
                        <th>Kelompok</th>
                        <th>Tgl Bayar</th>
                        <th>Nominal</th>
                        <th>Status</th>
                        <th style="text-align:right;padding-right:20px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pembayaran as $p)
                    <tr>
                        {{-- No kwitansi --}}
                        <td>
                            <span class="spp-no-kwit">{{ $p->no_kwitansi }}</span>
                        </td>

                        {{-- Siswa --}}
                        <td>
                            <p class="font-semibold" style="font-size:12px;color:var(--text-1);">{{ $p->siswa->nama_lengkap }}</p>
                            <p class="g-mono" style="font-size:10px;color:var(--text-3);margin-top:1px;">{{ $p->siswa->nis ?? '—' }}</p>
                        </td>

                        {{-- Kelompok --}}
                        <td>
                            <span class="spp-klp-pill">{{ $p->siswa->kelompok }}</span>
                        </td>

                        {{-- Tgl bayar --}}
                        <td>
                            <span class="g-mono" style="font-size:11px;color:var(--text-2);">{{ $p->tanggal_bayar->translatedFormat('d M Y') }}</span>
                        </td>

                        {{-- Nominal --}}
                        <td>
                            <span class="spp-nominal">{{ $p->total_rupiah }}</span>
                        </td>

                        {{-- Status --}}
                        <td>
                            <span class="spp-badge-lunas">
                                <svg width="9" height="9" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                                Lunas
                            </span>
                        </td>

                        {{-- Aksi --}}
                        <td>
                            <div class="spp-btn-row">
                                <a href="{{ route('spp.kwitansi', $p) }}"
                                   class="spp-btn-icon" title="Lihat Kwitansi">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </a>
                                <a href="{{ route('spp.cetak', $p) }}" target="_blank"
                                   class="spp-btn-icon" title="Cetak PDF">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                </a>
                                @if(Auth::user()->role === 'admin')
                                <form method="POST" action="{{ route('spp.destroy', $p) }}"
                                      onsubmit="return confirm('Hapus data pembayaran ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="spp-btn-icon danger" title="Hapus">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
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
        <div class="spp-pagi">{{ $pembayaran->links() }}</div>
        @endif

        @endif
    </div>

</div>
@endsection