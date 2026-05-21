@extends('layouts.app')

@section('title', 'Dashboard — SIP-Pelangi')
@section('page-title', 'Dashboard')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
/* ╔══════════════════════════════════════════════════════════╗
   ║  SIP-Pelangi Dashboard — v3 Premium Redesign             ║
   ║  Arah: Crisp editorial meets modern data dashboard       ║
   ║  Font: Outfit (display) + DM Mono (data) + Geist (body) ║
   ╚══════════════════════════════════════════════════════════╝ */

/* ── CSS Tokens (memperluas tokens dari layout) ── */
:root {
    --db-indigo:      #6366f1;
    --db-indigo-2:    #4f46e5;
    --db-violet:      #8b5cf6;
    --db-cyan:        #06b6d4;
    --db-emerald:     #10b981;
    --db-amber:       #f59e0b;
    --db-rose:        #f43f5e;

    --db-glass-bg:    rgba(255,255,255,0.72);
    --db-glass-border:rgba(0,0,0,0.07);
    --db-glass-blur:  blur(16px) saturate(1.4);

    --db-font-display: 'Outfit', 'Geist', sans-serif;
    --db-font-mono:    'DM Mono', 'Geist Mono', monospace;
}
.dark {
    --db-glass-bg:    rgba(20,24,32,0.80);
    --db-glass-border:rgba(255,255,255,0.07);
}

/* ── Keyframes ── */
@keyframes db-in {
    from { opacity:0; transform:translateY(14px); }
    to   { opacity:1; transform:translateY(0); }
}
@keyframes db-orb-float {
    0%,100% { transform:translateY(0) scale(1); }
    50%     { transform:translateY(-10px) scale(1.05); }
}
@keyframes db-shimmer {
    0%   { background-position:-200% center; }
    100% { background-position: 200% center; }
}
@keyframes db-pulse-dot {
    0%,100% { opacity:.3; transform:scale(1); }
    50%     { opacity:1;  transform:scale(1.3); }
}
@keyframes db-bar-grow {
    from { width:0; }
}
@keyframes db-count-pop {
    0%,100% { transform:scale(1); }
    50%     { transform:scale(1.06); }
}
@keyframes db-spin-slow {
    from { transform:translate(-50%,-50%) rotate(0deg); }
    to   { transform:translate(-50%,-50%) rotate(360deg); }
}
@keyframes db-ping {
    75%,100% { transform:scale(1.8); opacity:0; }
}

/* ── Stagger animation helpers ── */
.db-anim { animation: db-in .48s cubic-bezier(.22,1,.36,1) both; }
.d1 { animation-delay:.04s; } .d2 { animation-delay:.10s; }
.d3 { animation-delay:.16s; } .d4 { animation-delay:.22s; }
.d5 { animation-delay:.28s; } .d6 { animation-delay:.34s; }
.d7 { animation-delay:.40s; }

/* ════════════════════════════════════════
   GREETING HERO
════════════════════════════════════════ */
.db-hero {
    position: relative;
    margin-bottom: 1.75rem;
    padding: 1.75rem 2rem;
    border-radius: 20px;
    overflow: hidden;
    background: var(--db-glass-bg);
    backdrop-filter: var(--db-glass-blur);
    -webkit-backdrop-filter: var(--db-glass-blur);
    border: 1px solid var(--db-glass-border);
    box-shadow: 0 1px 3px rgba(0,0,0,.06), 0 8px 32px rgba(99,102,241,.06);
}
/* Mesh orbs */
.db-hero::before,
.db-hero::after {
    content:'';
    position:absolute;
    border-radius:50%;
    pointer-events:none;
}
.db-hero::before {
    top:-50px; right:-30px;
    width:220px; height:220px;
    background: radial-gradient(circle, rgba(99,102,241,.22) 0%, transparent 65%);
    animation: db-orb-float 7s ease-in-out infinite;
}
.db-hero::after {
    bottom:-40px; left:25%;
    width:160px; height:160px;
    background: radial-gradient(circle, rgba(6,182,212,.15) 0%, transparent 65%);
    animation: db-orb-float 9s ease-in-out infinite 2s;
}
.db-hero-inner {
    position:relative; z-index:1;
    display:flex; align-items:center;
    justify-content:space-between;
    flex-wrap:wrap; gap:12px;
}
.db-hero-chip {
    display:inline-flex; align-items:center; gap:6px;
    font-family: var(--db-font-mono);
    font-size:10px; font-weight:500;
    letter-spacing:.12em; text-transform:uppercase;
    color:var(--db-cyan);
    background:rgba(6,182,212,.10);
    border:1px solid rgba(6,182,212,.20);
    border-radius:9999px; padding:3px 10px;
    margin-bottom:8px;
}
.db-hero-chip-dot {
    width:5px; height:5px; border-radius:50%;
    background:var(--db-cyan);
    animation: db-pulse-dot 2s ease-in-out infinite;
}
.db-hero-name {
    font-family: var(--db-font-display);
    font-size:clamp(20px,3vw,28px);
    font-weight:800;
    letter-spacing:-.04em;
    line-height:1.1;
    background: linear-gradient(120deg, var(--text-1) 0%, #818cf8 55%, var(--db-cyan) 110%);
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
    background-clip:text;
    background-size:200% auto;
    animation: db-shimmer 5s linear infinite;
}
.dark .db-hero-name {
    background: linear-gradient(120deg, #f0f4ff 0%, #c7d2fe 50%, var(--db-cyan) 110%);
    -webkit-background-clip:text; background-clip:text;
    background-size:200% auto;
}
.db-hero-meta {
    display:flex; align-items:center; gap:8px;
    font-size:12px; color:var(--text-3);
    margin-top:8px;
    font-family: var(--db-font-mono);
}
.db-hero-meta .sep { opacity:.3; }
.db-role-badge {
    display:inline-flex; align-items:center; gap:5px;
    padding:3px 10px; border-radius:9999px;
    font-size:10.5px; font-weight:700;
    letter-spacing:.03em; text-transform:uppercase;
    font-family: var(--db-font-display);
    background:linear-gradient(135deg,rgba(99,102,241,.18),rgba(139,92,246,.12));
    border:1px solid rgba(99,102,241,.22);
    color:#a5b4fc;
}
/* Clock widget */
.db-clock-wrap {
    display:flex; flex-direction:column; align-items:flex-end; gap:4px;
    flex-shrink:0;
}
.db-clock {
    font-family: var(--db-font-display);
    font-size:clamp(22px,3vw,30px);
    font-weight:800;
    letter-spacing:-.04em; line-height:1;
    background: linear-gradient(135deg, var(--db-indigo), var(--db-cyan));
    -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;
}
.db-date {
    font-family: var(--db-font-mono);
    font-size:10.5px; color:var(--text-3);
    letter-spacing:.04em;
}

/* ════════════════════════════════════════
   SECTION LABEL
════════════════════════════════════════ */
.db-section-label {
    display:flex; align-items:center; gap:10px;
    margin-bottom:12px;
    font-family: var(--db-font-mono);
    font-size:9.5px; font-weight:500;
    letter-spacing:.14em; text-transform:uppercase;
}
.db-section-label > span {
    background: linear-gradient(90deg, var(--db-indigo), var(--db-cyan));
    -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;
    white-space:nowrap;
}
.db-section-label::after {
    content:''; flex:1; height:1px;
    background: linear-gradient(90deg, rgba(99,102,241,.25) 0%, rgba(6,182,212,.12) 50%, transparent 100%);
}

/* ════════════════════════════════════════
   GLASS CARD BASE
════════════════════════════════════════ */
.db-card {
    background: var(--db-glass-bg);
    backdrop-filter: var(--db-glass-blur);
    -webkit-backdrop-filter: var(--db-glass-blur);
    border: 1px solid var(--db-glass-border);
    border-radius: 18px;
    box-shadow: 0 1px 3px rgba(0,0,0,.06), 0 4px 16px rgba(0,0,0,.04);
    position: relative;
    overflow: hidden;
    transition: transform .24s cubic-bezier(.22,1,.36,1),
                box-shadow .24s ease,
                border-color .24s ease;
}
.db-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 32px rgba(0,0,0,.10), 0 0 0 1px rgba(99,102,241,.10) inset;
    border-color: rgba(99,102,241,.18);
}
/* Top highlight line */
.db-card::after {
    content:'';
    position:absolute; top:0; left:0; right:0;
    height:1px;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,.65) 40%, rgba(99,102,241,.30) 70%, transparent);
    pointer-events:none;
}

/* ════════════════════════════════════════
   STAT CARDS
════════════════════════════════════════ */
.db-stat-grid {
    display:grid;
    grid-template-columns: repeat(auto-fit, minmax(175px,1fr));
    gap:14px;
    margin-bottom:1.75rem;
}
.db-stat { padding:1.4rem 1.5rem 1.25rem; cursor:default; }

/* Color accent bar */
.db-stat::before {
    content:'';
    position:absolute; left:0; top:0; bottom:0;
    width:3px; border-radius:18px 0 0 18px;
}
.s-indigo::before  { background:linear-gradient(180deg,#818cf8,#6366f1,#4f46e5); box-shadow:3px 0 16px rgba(99,102,241,.45); }
.s-cyan::before    { background:linear-gradient(180deg,#67e8f9,#22d3ee,#06b6d4); box-shadow:3px 0 16px rgba(6,182,212,.40); }
.s-emerald::before { background:linear-gradient(180deg,#6ee7b7,#10b981,#059669); box-shadow:3px 0 14px rgba(16,185,129,.38); }
.s-amber::before   { background:linear-gradient(180deg,#fcd34d,#f59e0b,#d97706); box-shadow:3px 0 14px rgba(245,158,11,.38); }
.s-rose::before    { background:linear-gradient(180deg,#fda4af,#f43f5e,#e11d48); box-shadow:3px 0 14px rgba(244,63,94,.38); }
.s-violet::before  { background:linear-gradient(180deg,#c4b5fd,#8b5cf6,#7c3aed); box-shadow:3px 0 14px rgba(139,92,246,.38); }

/* Glow orb */
.db-stat-glow {
    position:absolute; top:-28px; right:-28px;
    width:100px; height:100px; border-radius:50%;
    opacity:.12; filter:blur(22px); pointer-events:none;
    transition:opacity .3s;
}
.db-card:hover .db-stat-glow { opacity:.24; }

/* Icon */
.db-stat-icon {
    width:40px; height:40px; border-radius:12px;
    display:flex; align-items:center; justify-content:center;
    margin-bottom:14px; flex-shrink:0; position:relative; z-index:1;
    transition:transform .24s cubic-bezier(.34,1.56,.64,1);
}
.db-card:hover .db-stat-icon { transform:scale(1.10) rotate(-4deg); }
.i-indigo  { background:rgba(99,102,241,.14); color:#818cf8; border:1px solid rgba(99,102,241,.20); }
.i-cyan    { background:rgba(6,182,212,.13);  color:#67e8f9; border:1px solid rgba(6,182,212,.18); }
.i-emerald { background:rgba(16,185,129,.13); color:#6ee7b7; border:1px solid rgba(16,185,129,.18); }
.i-amber   { background:rgba(245,158,11,.13); color:#fcd34d; border:1px solid rgba(245,158,11,.18); }
.i-rose    { background:rgba(244,63,94,.12);  color:#fda4af; border:1px solid rgba(244,63,94,.18); }
.i-violet  { background:rgba(139,92,246,.13); color:#c4b5fd; border:1px solid rgba(139,92,246,.18); }

.db-stat-label {
    font-size:10px; font-weight:600;
    letter-spacing:.09em; text-transform:uppercase;
    color:var(--text-3); margin-bottom:5px;
    font-family: var(--db-font-mono); position:relative; z-index:1;
}
.db-stat-num {
    font-family: var(--db-font-display);
    font-size:36px; font-weight:800;
    color:var(--text-1); line-height:1;
    letter-spacing:-.04em; margin-bottom:10px;
    font-variant-numeric:tabular-nums; position:relative; z-index:1;
}
.db-stat-num.danger { color:#f43f5e; text-shadow:0 0 16px rgba(244,63,94,.25); }
.db-stat-num.success { color:#10b981; }

.db-stat-foot {
    display:flex; align-items:center; gap:6px;
    min-height:22px; position:relative; z-index:1;
}

/* Pills */
.db-pill {
    display:inline-flex; align-items:center; gap:4px;
    padding:2px 9px; border-radius:9999px;
    font-size:10.5px; font-weight:700;
    font-family: var(--db-font-display); letter-spacing:.01em;
}
.pill-up   { background:rgba(16,185,129,.14); color:#6ee7b7; border:1px solid rgba(16,185,129,.22); }
.pill-warn { background:rgba(244,63,94,.11);  color:#fda4af; border:1px solid rgba(244,63,94,.20); }
.pill-ok   { background:rgba(16,185,129,.11); color:#6ee7b7; border:1px solid rgba(16,185,129,.20); }
.pill-info { background:rgba(99,102,241,.13); color:#a5b4fc; border:1px solid rgba(99,102,241,.20); }
.pill-amber{ background:rgba(245,158,11,.12); color:#fcd34d; border:1px solid rgba(245,158,11,.20); }

.db-stat-link {
    font-size:11.5px; font-weight:600; color:var(--db-indigo);
    text-decoration:none; display:inline-flex; align-items:center; gap:4px;
    opacity:.75; transition:opacity .15s, gap .2s;
    font-family: var(--db-font-display);
}
.db-stat-link:hover { opacity:1; gap:8px; }

/* ════════════════════════════════════════
   QUICK ACTIONS
════════════════════════════════════════ */
.db-quick-grid {
    display:grid;
    grid-template-columns:repeat(auto-fit, minmax(155px,1fr));
    gap:12px;
    margin-bottom:2rem;
}
.db-quick-btn {
    display:flex; flex-direction:column;
    align-items:flex-start; gap:10px;
    padding:1.2rem 1.25rem;
    background:var(--db-glass-bg);
    backdrop-filter:var(--db-glass-blur);
    -webkit-backdrop-filter:var(--db-glass-blur);
    border:1px solid var(--db-glass-border);
    border-radius:16px; text-decoration:none;
    box-shadow:0 1px 3px rgba(0,0,0,.05);
    transition:all .24s cubic-bezier(.22,1,.36,1);
    cursor:pointer; position:relative; overflow:hidden;
}
.db-quick-btn::before {
    content:''; position:absolute; inset:0;
    background:linear-gradient(135deg,rgba(99,102,241,.07) 0%,rgba(6,182,212,.04) 100%);
    opacity:0; transition:opacity .24s; border-radius:16px;
}
.db-quick-btn::after {
    content:''; position:absolute;
    top:0; left:0; right:0; height:1px;
    background:linear-gradient(90deg,transparent,rgba(255,255,255,.55),transparent);
}
.db-quick-btn:hover {
    transform:translateY(-4px) scale(1.01);
    box-shadow:0 10px 28px rgba(0,0,0,.10), 0 0 0 1px rgba(99,102,241,.15) inset;
    border-color:rgba(99,102,241,.25);
}
.db-quick-btn:hover::before { opacity:1; }
.db-qb-icon {
    width:42px; height:42px; border-radius:12px;
    display:flex; align-items:center; justify-content:center;
    flex-shrink:0; position:relative; z-index:1;
    transition:transform .28s cubic-bezier(.34,1.56,.64,1);
}
.db-quick-btn:hover .db-qb-icon { transform:scale(1.12) rotate(-5deg); }
.db-qb-label {
    font-family: var(--db-font-display);
    font-size:13px; font-weight:700;
    color:var(--text-1); position:relative; z-index:1;
    line-height:1.2;
}
.db-qb-sub {
    font-family: var(--db-font-mono);
    font-size:10px; color:var(--text-3);
    position:relative; z-index:1;
}
.db-qb-arrow {
    margin-top:auto; font-size:12px;
    color:var(--db-indigo); opacity:.5;
    position:relative; z-index:1;
    transition:opacity .18s, transform .18s;
    font-family: var(--db-font-display); font-weight:700;
}
.db-quick-btn:hover .db-qb-arrow { opacity:1; transform:translateX(4px); }

/* ════════════════════════════════════════
   CHARTS GRID
════════════════════════════════════════ */
.db-charts-grid {
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:16px;
    margin-bottom:1.75rem;
}
@media (max-width:860px) {
    .db-charts-grid { grid-template-columns:1fr; }
}
.db-chart-panel { padding:1.35rem 1.5rem; }
.db-chart-head {
    display:flex; align-items:center;
    justify-content:space-between; margin-bottom:16px; gap:8px;
}
.db-chart-title {
    font-family: var(--db-font-display);
    font-size:15px; font-weight:800;
    color:var(--text-1); letter-spacing:-.03em;
}
.db-chart-tabs {
    display:flex; align-items:center; gap:4px;
    background:rgba(0,0,0,.05); border-radius:9999px; padding:3px;
}
.dark .db-chart-tabs { background:rgba(255,255,255,.05); }
.db-ctab {
    padding:3px 12px; border-radius:9999px;
    font-size:11px; font-weight:600;
    cursor:pointer; border:none; background:transparent;
    color:var(--text-3); transition:all .18s;
    font-family: var(--db-font-display);
}
.db-ctab.on {
    background:var(--surface); color:var(--text-1);
    box-shadow:0 1px 3px rgba(0,0,0,.10);
}
.dark .db-ctab.on { background:var(--surface-2); }

/* Chart legend */
.db-legend {
    display:flex; flex-wrap:wrap; gap:12px;
    margin-top:14px;
}
.db-ld {
    display:flex; align-items:center; gap:6px;
    font-size:11.5px; color:var(--text-2);
    font-family: var(--db-font-display); font-weight:500;
}
.db-ld-sq {
    width:9px; height:9px; border-radius:3px; display:inline-block; flex-shrink:0;
}

/* Right panel stack */
.db-right-stack { display:flex; flex-direction:column; gap:16px; }

/* ════════════════════════════════════════
   INCOME CARD (dark premium)
════════════════════════════════════════ */
.db-income-card {
    background: linear-gradient(145deg,
        rgba(8,12,36,.94) 0%,
        rgba(22,16,72,.88) 50%,
        rgba(6,14,38,.92) 100%
    ) !important;
    border-color:rgba(99,102,241,.25) !important;
    box-shadow:0 8px 32px rgba(0,0,0,.28), 0 0 0 1px rgba(99,102,241,.12) inset !important;
}
.light .db-income-card,
:root:not(.dark) .db-income-card {
    background: linear-gradient(145deg,
        rgba(22,16,72,.96) 0%,
        rgba(8,12,40,.94) 100%
    ) !important;
}
.db-income-card::before {
    content:'';
    position:absolute; top:50%; left:50%;
    width:220px; height:220px; border-radius:50%;
    background:conic-gradient(from 0deg,
        rgba(99,102,241,.10),
        rgba(6,182,212,.07),
        rgba(139,92,246,.10),
        rgba(99,102,241,.10)
    );
    animation: db-spin-slow 14s linear infinite;
    pointer-events:none; z-index:0;
}
.db-income-card::after { display:none; }
.db-income-card > * { position:relative; z-index:1; }
.db-income-label {
    font-size:10px; font-weight:600; color:rgba(199,210,254,.50);
    letter-spacing:.10em; text-transform:uppercase; margin-bottom:8px;
    font-family: var(--db-font-mono);
}
.db-income-amount {
    font-family: var(--db-font-display);
    font-size:26px; font-weight:800; color:#fff;
    letter-spacing:-.04em; line-height:1; margin-bottom:5px;
    font-variant-numeric:tabular-nums;
}
.db-income-sub { font-size:11.5px; color:rgba(199,210,254,.35); margin-bottom:16px; }
.db-progress-track {
    height:6px; border-radius:9999px;
    background:rgba(255,255,255,.07); overflow:hidden; margin-bottom:6px;
}
.db-progress-fill {
    height:100%; border-radius:9999px;
    background:linear-gradient(90deg,#6366f1,#818cf8,#22d3ee);
    background-size:200% auto;
    box-shadow:0 0 12px rgba(99,102,241,.50), 0 0 24px rgba(34,211,238,.20);
    transition:width 1.4s cubic-bezier(.22,1,.36,1);
    animation:db-shimmer 3s linear infinite;
}
.db-progress-labels {
    display:flex; justify-content:space-between; font-size:10.5px;
}

/* ════════════════════════════════════════
   DONUT CHART SECTION
════════════════════════════════════════ */
.db-donut-wrap { display:flex; align-items:center; gap:18px; }
.db-donut-legend { display:flex; flex-direction:column; gap:10px; flex:1; }
.db-dl-row {
    display:flex; align-items:center;
    justify-content:space-between; font-size:12.5px;
}
.db-dl-left { display:flex; align-items:center; gap:8px; color:var(--text-2); }
.db-dl-dot { width:9px; height:9px; border-radius:3px; flex-shrink:0; }
.db-dl-val {
    font-family: var(--db-font-display); font-weight:800;
    color:var(--text-1); font-size:14px;
}

/* ════════════════════════════════════════
   TUNGGAKAN LIST
════════════════════════════════════════ */
.db-list-panel { padding:1.35rem 1.5rem; }
.db-list-title {
    font-family: var(--db-font-display);
    font-size:15px; font-weight:800;
    color:var(--text-1); letter-spacing:-.03em; margin-bottom:16px;
}
.db-tung-item {
    display:flex; align-items:center;
    justify-content:space-between;
    padding:10px 0;
    border-bottom:1px solid var(--db-glass-border);
    transition:all .18s;
}
.db-tung-item:last-of-type { border-bottom:none; }
.db-tung-item:hover { padding-left:6px; border-left:2px solid rgba(244,63,94,.35); }
.db-tung-name { font-size:13px; font-weight:700; color:var(--text-1); line-height:1.3; }
.db-tung-sub  { font-size:11px; color:var(--text-3); margin-top:2px; font-family:var(--db-font-mono); }
.db-tung-badge {
    font-size:10.5px; font-weight:800;
    padding:3px 10px; border-radius:9999px;
    background:rgba(244,63,94,.10); color:#fda4af;
    border:1px solid rgba(244,63,94,.20);
    font-family:var(--db-font-display); white-space:nowrap;
}
.db-list-more {
    display:block; text-align:center; margin-top:14px;
    padding:8px; font-size:12px; font-weight:700;
    color:var(--db-indigo); cursor:pointer;
    border-radius:10px; background:rgba(99,102,241,.06);
    border:1px solid rgba(99,102,241,.12); text-decoration:none;
    transition:all .18s; font-family:var(--db-font-display);
}
.db-list-more:hover { background:rgba(99,102,241,.12); }

/* ════════════════════════════════════════
   ACTIVITY / RECENT LIST
════════════════════════════════════════ */
.db-activity-item {
    display:flex; align-items:center; gap:12px;
    padding:10px 0; border-bottom:1px solid var(--db-glass-border);
}
.db-activity-item:last-child { border-bottom:none; }
.db-activity-dot {
    width:8px; height:8px; border-radius:50%; flex-shrink:0;
    position:relative;
}
.db-activity-dot::after {
    content:''; position:absolute; inset:-2px;
    border-radius:50%; border:1px solid currentColor;
    opacity:.3;
}
.db-activity-text { flex:1; }
.db-activity-label { font-size:13px; font-weight:600; color:var(--text-1); }
.db-activity-time  { font-size:11px; color:var(--text-3); font-family:var(--db-font-mono); margin-top:1px; }
.db-activity-val   { font-family:var(--db-font-display); font-size:13px; font-weight:700; }

/* ════════════════════════════════════════
   BENDAHARA SPECIFIC
════════════════════════════════════════ */
.db-benda-grid {
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:16px;
    margin-bottom:2rem;
}
@media (max-width:640px) { .db-benda-grid { grid-template-columns:1fr; } }

/* ════════════════════════════════════════
   KEPALA SEKOLAH SPECIFIC
════════════════════════════════════════ */
.db-kepsek-overview {
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:16px;
    margin-bottom:2rem;
}
@media (max-width:640px) { .db-kepsek-overview { grid-template-columns:1fr; } }

/* Attendance ring card */
.db-ring-card { padding:1.5rem; text-align:center; }
.db-ring-wrap {
    position:relative; width:100px; height:100px;
    margin:0 auto 16px;
}
.db-ring-svg { transform:rotate(-90deg); }
.db-ring-track { fill:none; stroke:rgba(0,0,0,.06); stroke-width:8; }
.dark .db-ring-track { stroke:rgba(255,255,255,.06); }
.db-ring-fill { fill:none; stroke-width:8; stroke-linecap:round; transition:stroke-dashoffset 1.2s cubic-bezier(.22,1,.36,1); }
.db-ring-center {
    position:absolute; inset:0;
    display:flex; flex-direction:column;
    align-items:center; justify-content:center;
}
.db-ring-pct {
    font-family:var(--db-font-display); font-size:22px; font-weight:800;
    letter-spacing:-.04em; line-height:1;
}
.db-ring-sub { font-size:10px; color:var(--text-3); font-family:var(--db-font-mono); margin-top:2px; }

/* ════════════════════════════════════════
   GURU SPECIFIC
════════════════════════════════════════ */
.db-guru-wrap {
    max-width:500px; margin:0 auto;
    display:flex; flex-direction:column; gap:16px;
}
/* Status card */
.db-status-card { padding:2rem 1.75rem; text-align:center; }
.db-status-card::before {
    content:''; position:absolute;
    top:0; left:0; right:0; height:3px;
    border-radius:18px 18px 0 0;
}
.status-hadir::before {
    background:linear-gradient(90deg,#10b981,#34d399,#6ee7b7);
    box-shadow:0 0 20px rgba(16,185,129,.45);
}
.status-belum::before {
    background:linear-gradient(90deg,#f59e0b,#fbbf24,#fcd34d);
    box-shadow:0 0 20px rgba(245,158,11,.40);
}
.db-status-icon {
    width:68px; height:68px; border-radius:50%;
    display:flex; align-items:center; justify-content:center;
    margin:0 auto 16px; position:relative;
}
.si-green {
    background:rgba(16,185,129,.12);
    color:#6ee7b7;
}
.si-amber {
    background:rgba(245,158,11,.12);
    color:#fcd34d;
}
/* Ping ring */
.db-ping {
    position:absolute; inset:0; border-radius:50%;
    animation:db-ping 2s cubic-bezier(0,0,.2,1) infinite;
}
.ping-green { background:rgba(16,185,129,.20); }
.ping-amber { background:rgba(245,158,11,.18); }

.db-status-title {
    font-family:var(--db-font-display);
    font-size:22px; font-weight:800;
    letter-spacing:-.03em; margin-bottom:6px;
}
.st-green { color:#6ee7b7; }
.st-amber { color:#fcd34d; }
.db-status-sub { font-size:13px; color:var(--text-3); }
.db-jam-chip {
    display:inline-flex; align-items:center; gap:6px;
    margin-top:14px; padding:5px 16px;
    background:rgba(16,185,129,.10); border:1px solid rgba(16,185,129,.20);
    border-radius:9999px; color:#6ee7b7;
    font-family:var(--db-font-mono); font-size:12px; font-weight:500;
}
.db-absen-btn {
    display:inline-block; margin-top:20px;
    padding:12px 32px;
    background:linear-gradient(135deg,#6366f1,#8b5cf6,#06b6d4);
    background-size:200% auto;
    color:#fff;
    font-family:var(--db-font-display);
    font-size:14px; font-weight:800;
    border-radius:14px; text-decoration:none;
    box-shadow:0 6px 24px rgba(99,102,241,.38), 0 0 36px rgba(34,211,238,.16);
    transition:all .24s cubic-bezier(.22,1,.36,1);
    animation:db-shimmer 3s linear infinite;
}
.db-absen-btn:hover {
    transform:translateY(-3px) scale(1.02);
    box-shadow:0 10px 36px rgba(99,102,241,.48), 0 0 48px rgba(34,211,238,.24);
}

/* Rekap card */
.db-rekap-head {
    padding:12px 20px;
    border-bottom:1px solid var(--db-glass-border);
    font-family:var(--db-font-mono); font-size:10px;
    font-weight:600; letter-spacing:.12em; text-transform:uppercase;
    background:linear-gradient(90deg,var(--db-indigo),var(--db-cyan));
    -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;
}
.db-rekap-grid { display:grid; grid-template-columns:repeat(3,1fr); }
.db-rekap-cell { padding:1.4rem 1rem; text-align:center; }
.db-rekap-cell + .db-rekap-cell { border-left:1px solid var(--db-glass-border); }
.db-rekap-num {
    font-family:var(--db-font-display); font-size:32px;
    font-weight:800; line-height:1; letter-spacing:-.04em;
}
.rn-green { color:#6ee7b7; }
.rn-amber { color:#fcd34d; }
.rn-red   { color:#fda4af; }
.db-rekap-lbl {
    font-size:10.5px; color:var(--text-3); margin-top:5px;
    font-family:var(--db-font-mono); font-weight:500;
    letter-spacing:.04em; text-transform:uppercase;
}

/* Attendance history mini */
.db-hist-item {
    display:flex; align-items:center; gap:10px;
    padding:8px 0;
    border-bottom:1px solid var(--db-glass-border);
}
.db-hist-item:last-child { border-bottom:none; }
.db-hist-date { font-family:var(--db-font-mono); font-size:11px; color:var(--text-3); min-width:52px; }
.db-hist-status-badge {
    font-size:10.5px; font-weight:700; padding:2px 9px;
    border-radius:9999px; font-family:var(--db-font-display);
    letter-spacing:.01em;
}
.hs-hadir     { background:rgba(16,185,129,.12); color:#6ee7b7; border:1px solid rgba(16,185,129,.20); }
.hs-terlambat { background:rgba(245,158,11,.12); color:#fcd34d; border:1px solid rgba(245,158,11,.20); }
.hs-izin      { background:rgba(99,102,241,.12); color:#a5b4fc; border:1px solid rgba(99,102,241,.20); }
.hs-sakit     { background:rgba(6,182,212,.12);  color:#67e8f9; border:1px solid rgba(6,182,212,.20); }
.hs-alpha     { background:rgba(244,63,94,.11);  color:#fda4af; border:1px solid rgba(244,63,94,.20); }
.hs-tugas_luar{ background:rgba(139,92,246,.12); color:#c4b5fd; border:1px solid rgba(139,92,246,.20); }
.db-hist-jam  { margin-left:auto; font-family:var(--db-font-mono); font-size:11px; color:var(--text-3); }

/* ════════════════════════════════════════
   SUMMARY MINI CARD (Kepala Sekolah)
════════════════════════════════════════ */
.db-mini-grid {
    display:grid; grid-template-columns:repeat(3,1fr); gap:12px;
    margin-bottom:1.75rem;
}
.db-mini-card {
    padding:1rem 1.1rem; text-align:center;
}
.db-mini-num {
    font-family:var(--db-font-display); font-size:28px;
    font-weight:800; line-height:1; letter-spacing:-.04em;
    color:var(--text-1);
}
.db-mini-label {
    font-size:10px; color:var(--text-3);
    font-family:var(--db-font-mono); font-weight:500;
    letter-spacing:.06em; text-transform:uppercase;
    margin-top:4px;
}

/* ════════════════════════════════════════
   RESPONSIVE
════════════════════════════════════════ */
@media (max-width:640px) {
    .db-stat-grid       { grid-template-columns:repeat(2,1fr); gap:10px; }
    .db-quick-grid      { grid-template-columns:repeat(2,1fr); gap:10px; }
    .db-hero            { padding:1.25rem; }
    .db-stat-num        { font-size:28px; }
    .db-mini-grid       { grid-template-columns:repeat(3,1fr); gap:8px; }
    .db-mini-num        { font-size:22px; }
    .db-rekap-num       { font-size:26px; }
    .db-kepsek-overview { grid-template-columns:1fr; }
}
@media (max-width:400px) {
    .db-stat-grid  { grid-template-columns:1fr; }
    .db-quick-grid { grid-template-columns:1fr; }
}
</style>
@endpush

@section('content')
<div style="max-width:1120px;margin:0 auto;">

{{-- ══════════════════════════════════════
     GREETING HERO
══════════════════════════════════════ --}}
<div class="db-hero db-anim">
    <div class="db-hero-inner">
        <div>
            <div class="db-hero-chip">
                <span class="db-hero-chip-dot"></span>
                SIP-Pelangi
            </div>
            <p class="db-hero-name">
                Selamat datang, {{ Auth::user()->name ?? Auth::user()->nama_lengkap }} 👋
            </p>
            <div class="db-hero-meta">
                <span>{{ $data['bulan_ini'] }}</span>
                <span class="sep">·</span>
                <span class="db-role-badge">
                    @if(Auth::user()->isAdmin()) 🛡️ Administrator
                    @elseif(Auth::user()->isBendahara()) 💰 Bendahara
                    @elseif(Auth::user()->isKepalaSekolah()) 🏫 Kepala Sekolah
                    @else 📚 {{ Auth::user()->jabatan ?? 'Guru' }}
                    @endif
                </span>
            </div>
        </div>
        <div class="db-clock-wrap">
            <div class="db-clock" id="live-clock">--:--</div>
            <div class="db-date" id="live-date">...</div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════
     ■ ADMIN DASHBOARD
══════════════════════════════════════ --}}
@if(Auth::user()->isAdmin())

<div class="db-section-label db-anim d1"><span>ringkasan hari ini</span></div>

{{-- Stat cards --}}
<div class="db-stat-grid">

    {{-- Siswa Aktif --}}
    <div class="db-card db-stat s-indigo db-anim d1">
        <div class="db-stat-glow" style="background:var(--db-indigo);"></div>
        <div class="db-stat-icon i-indigo">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
        </div>
        <p class="db-stat-label">Siswa Aktif</p>
        <p class="db-stat-num counter" data-target="{{ $data['total_siswa'] }}">0</p>
        <div class="db-stat-foot">
            <a href="{{ route('siswa.index') }}" class="db-stat-link">
                Lihat data <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>

    {{-- Guru Aktif --}}
    <div class="db-card db-stat s-cyan db-anim d2">
        <div class="db-stat-glow" style="background:var(--db-cyan);"></div>
        <div class="db-stat-icon i-cyan">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
        <p class="db-stat-label">Guru Aktif</p>
        <p class="db-stat-num counter" data-target="{{ $data['total_guru'] }}">0</p>
        <div class="db-stat-foot">
            <a href="{{ route('guru.index') }}" class="db-stat-link">
                Lihat data <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>

    {{-- Absensi Hari Ini --}}
    <div class="db-card db-stat s-emerald db-anim d3">
        <div class="db-stat-glow" style="background:var(--db-emerald);"></div>
        <div class="db-stat-icon i-emerald">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="2"/><path d="m9 12 2 2 4-4"/></svg>
        </div>
        <p class="db-stat-label">Absensi Hari Ini</p>
        <p class="db-stat-num counter" data-target="{{ $data['absensi_hari_ini'] }}">0</p>
        <div class="db-stat-foot">
            @if($data['guru_belum_absen'] > 0)
                <span class="db-pill pill-warn">
                    <svg width="9" height="9" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    {{ $data['guru_belum_absen'] }} belum absen
                </span>
            @else
                <span class="db-pill pill-ok">
                    <svg width="9" height="9" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                    Semua sudah absen
                </span>
            @endif
        </div>
    </div>

    {{-- SPP Bulan Ini --}}
    <div class="db-card db-stat s-amber db-anim d4">
        <div class="db-stat-glow" style="background:var(--db-amber);"></div>
        <div class="db-stat-icon i-amber">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
        </div>
        <p class="db-stat-label">SPP Bulan Ini</p>
        <p class="db-stat-num counter" data-target="{{ $data['spp_bulan_ini'] }}">0</p>
        <div class="db-stat-foot">
            <span class="db-pill pill-info">siswa terbayar</span>
        </div>
    </div>

    {{-- Tunggakan --}}
    <div class="db-card db-stat s-rose db-anim d5">
        <div class="db-stat-glow" style="background:var(--db-rose);"></div>
        <div class="db-stat-icon i-rose">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        </div>
        <p class="db-stat-label">Tunggakan SPP</p>
        <p class="db-stat-num counter {{ $data['tunggakan_spp'] > 0 ? 'danger' : 'success' }}" data-target="{{ $data['tunggakan_spp'] }}">0</p>
        <div class="db-stat-foot">
            <a href="{{ route('spp.tunggakan') }}" class="db-stat-link" style="color:#fda4af;opacity:.80;">
                Lihat tunggakan <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>

</div>{{-- /stat-grid --}}

{{-- Quick Actions --}}
<div class="db-section-label db-anim d2"><span>aksi cepat</span></div>
<div class="db-quick-grid">
    <a href="{{ route('siswa.create') }}" class="db-quick-btn db-anim d1">
        <div class="db-qb-icon i-cyan">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
        </div>
        <span class="db-qb-label">Tambah Siswa</span>
        <span class="db-qb-sub">Daftarkan siswa baru</span>
        <span class="db-qb-arrow">→</span>
    </a>
    <a href="{{ route('guru.create') }}" class="db-quick-btn db-anim d2">
        <div class="db-qb-icon i-indigo">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
        <span class="db-qb-label">Tambah Guru</span>
        <span class="db-qb-sub">Tambah tenaga pengajar</span>
        <span class="db-qb-arrow">→</span>
    </a>
    <a href="{{ route('absensi.kelola') }}" class="db-quick-btn db-anim d3">
        <div class="db-qb-icon i-amber">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="2"/><path d="m9 12 2 2 4-4"/></svg>
        </div>
        <span class="db-qb-label">Kelola Absensi</span>
        <span class="db-qb-sub">Input & rekap absensi</span>
        <span class="db-qb-arrow">→</span>
    </a>
    <a href="{{ route('spp.create') }}" class="db-quick-btn db-anim d4">
        <div class="db-qb-icon i-emerald">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
        </div>
        <span class="db-qb-label">Input SPP</span>
        <span class="db-qb-sub">Catat pembayaran</span>
        <span class="db-qb-arrow">→</span>
    </a>
    <a href="{{ route('absensi.laporan') }}" class="db-quick-btn db-anim d5">
        <div class="db-qb-icon i-violet">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z"/></svg>
        </div>
        <span class="db-qb-label">Laporan Absensi</span>
        <span class="db-qb-sub">Lihat & cetak PDF</span>
        <span class="db-qb-arrow">→</span>
    </a>
    <a href="{{ route('spp.laporan') }}" class="db-quick-btn db-anim d6">
        <div class="db-qb-icon i-rose">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/></svg>
        </div>
        <span class="db-qb-label">Laporan SPP</span>
        <span class="db-qb-sub">Rekap pembayaran</span>
        <span class="db-qb-arrow">→</span>
    </a>
</div>

{{-- Charts Row 1 --}}
<div class="db-section-label db-anim d3"><span>analitik</span></div>
<div class="db-charts-grid">

    {{-- Absensi Chart --}}
    <div class="db-card db-chart-panel db-anim d1">
        <div class="db-chart-head">
            <span class="db-chart-title">Absensi Guru</span>
            <div class="db-chart-tabs">
                <button class="db-ctab on" id="tab-bulan" onclick="switchAbsen('bulan')">6 Bulan</button>
                <button class="db-ctab" id="tab-minggu" onclick="switchAbsen('minggu')">Minggu Ini</button>
            </div>
        </div>
        <div style="position:relative;width:100%;height:200px;">
            <canvas id="chartAbsen" role="img" aria-label="Grafik absensi guru"></canvas>
        </div>
        <div class="db-legend">
            <span class="db-ld"><span class="db-ld-sq" style="background:#10b981;"></span>Hadir</span>
            <span class="db-ld"><span class="db-ld-sq" style="background:#f59e0b;"></span>Izin / Sakit</span>
            <span class="db-ld"><span class="db-ld-sq" style="background:#f43f5e;"></span>Alpha</span>
        </div>
    </div>

    {{-- Right stack --}}
    <div class="db-right-stack">
        {{-- Pemasukan progress (data dari controller jika ada) --}}
        @if(isset($data['total_pemasukan']))
        <div class="db-card db-chart-panel db-income-card db-anim d2">
            <p class="db-income-label">Pemasukan SPP Bulan Ini</p>
            <p class="db-income-amount">Rp <span class="counter-rp" data-target="{{ $data['total_pemasukan'] }}">0</span></p>
            <p class="db-income-sub">dari target Rp {{ number_format($data['target_pemasukan'] ?? ($data['total_siswa'] * 150000), 0, ',', '.') }}</p>
            <div class="db-progress-track">
                <div class="db-progress-fill" id="income-bar" style="width:0%"></div>
            </div>
            <div class="db-progress-labels">
                <span style="color:rgba(199,210,254,.28);font-size:10px;">0%</span>
                <span style="color:var(--db-cyan);font-size:11px;font-weight:700;" id="income-pct">0%</span>
            </div>
        </div>
        @endif

        {{-- Donut SPP --}}
        <div class="db-card db-chart-panel db-anim d3">
            <p class="db-chart-title" style="margin-bottom:16px;">Pembayaran SPP</p>
            <div class="db-donut-wrap">
                <div style="position:relative;width:88px;height:88px;flex-shrink:0;">
                    <canvas id="chartDonut" role="img" aria-label="Donut chart SPP"></canvas>
                </div>
                <div class="db-donut-legend">
                    <div class="db-dl-row">
                        <div class="db-dl-left"><span class="db-dl-dot" style="background:#6366f1;"></span>Terbayar</div>
                        <span class="db-dl-val">{{ $data['spp_bulan_ini'] }}</span>
                    </div>
                    <div class="db-dl-row">
                        <div class="db-dl-left"><span class="db-dl-dot" style="background:#f43f5e;"></span>Tunggakan</div>
                        <span class="db-dl-val" style="color:#fda4af;">{{ $data['tunggakan_spp'] }}</span>
                    </div>
                    <div class="db-dl-row" style="border-top:1px solid var(--db-glass-border);padding-top:8px;margin-top:2px;">
                        <div class="db-dl-left"><span class="db-dl-dot" style="background:var(--text-3);"></span>Total Siswa</div>
                        <span class="db-dl-val">{{ $data['total_siswa'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Charts Row 2: SPP Bar + Tunggakan List --}}
<div class="db-charts-grid">
    {{-- SPP Bar 6 Bulan --}}
    <div class="db-card db-chart-panel db-anim d1">
        <div class="db-chart-head">
            <span class="db-chart-title">Pembayaran SPP — 6 Bulan</span>
            <span class="db-pill pill-ok" style="font-size:10.5px;">Terakhir</span>
        </div>
        <div style="position:relative;width:100%;height:168px;">
            <canvas id="chartSPP" role="img" aria-label="Grafik SPP 6 bulan"></canvas>
            <div id="chartSPP-loading" style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;font-size:12px;color:var(--text-3);font-family:var(--db-font-mono);">
                Memuat data...
            </div>
        </div>
        <div class="db-legend">
            <span class="db-ld"><span class="db-ld-sq" style="background:#6366f1;"></span>Terbayar</span>
            <span class="db-ld"><span class="db-ld-sq" style="background:rgba(244,63,94,.55);"></span>Tunggakan</span>
        </div>
    </div>

    {{-- Tunggakan List --}}
    <div class="db-card db-list-panel db-anim d2">
        <p class="db-list-title">Tunggakan Terbaru</p>
        @php
            $tunggakanList = $data['tunggakan_list'] ?? [];
        @endphp
        @forelse($tunggakanList as $tung)
        <div class="db-tung-item">
            <div>
                <p class="db-tung-name">{{ $tung->nama_lengkap }}</p>
                <p class="db-tung-sub">{{ $tung->kelompok ?? '-' }}</p>
            </div>
            <span class="db-tung-badge">{{ $tung->jumlah_bulan ?? '?' }} bln</span>
        </div>
        @empty
        {{-- Fallback placeholder --}}
        <div style="text-align:center;padding:1.5rem 0;">
            <div style="font-size:28px;margin-bottom:8px;">🎉</div>
            <p style="font-family:var(--db-font-display);font-size:14px;font-weight:700;color:var(--text-1);">Tidak ada tunggakan!</p>
            <p style="font-size:12px;color:var(--text-3);margin-top:4px;">Semua siswa sudah membayar SPP bulan ini.</p>
        </div>
        @endforelse
        @if(count($tunggakanList) > 0)
        <a href="{{ route('spp.tunggakan') }}" class="db-list-more">Lihat semua tunggakan →</a>
        @endif
    </div>
</div>

@endif {{-- /Admin --}}


{{-- ══════════════════════════════════════
     ■ BENDAHARA DASHBOARD
══════════════════════════════════════ --}}
@if(Auth::user()->isBendahara())

<div class="db-section-label db-anim d1"><span>keuangan bulan ini</span></div>

{{-- Stats --}}
<div class="db-stat-grid">
    <div class="db-card db-stat s-indigo db-anim d1">
        <div class="db-stat-glow" style="background:var(--db-indigo);"></div>
        <div class="db-stat-icon i-indigo">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
        </div>
        <p class="db-stat-label">Total Siswa</p>
        <p class="db-stat-num counter" data-target="{{ $data['total_siswa'] }}">0</p>
        <div class="db-stat-foot"><span class="db-pill pill-info">siswa aktif</span></div>
    </div>

    <div class="db-card db-stat s-emerald db-anim d2">
        <div class="db-stat-glow" style="background:var(--db-emerald);"></div>
        <div class="db-stat-icon i-emerald">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
        </div>
        <p class="db-stat-label">SPP Terbayar</p>
        <p class="db-stat-num counter" data-target="{{ $data['spp_bulan_ini'] }}">0</p>
        <div class="db-stat-foot">
            <a href="{{ route('spp.index') }}" class="db-stat-link">
                Lihat detail <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>

    <div class="db-card db-stat s-rose db-anim d3">
        <div class="db-stat-glow" style="background:var(--db-rose);"></div>
        <div class="db-stat-icon i-rose">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        </div>
        <p class="db-stat-label">Tunggakan</p>
        <p class="db-stat-num counter {{ $data['tunggakan_spp'] > 0 ? 'danger' : 'success' }}" data-target="{{ $data['tunggakan_spp'] }}">0</p>
        <div class="db-stat-foot">
            <a href="{{ route('spp.tunggakan') }}" class="db-stat-link" style="color:#fda4af;opacity:.80;">
                Cek tunggakan <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</div>

{{-- Quick actions Bendahara --}}
<div class="db-section-label db-anim d2"><span>aksi cepat</span></div>
<div class="db-quick-grid">
    <a href="{{ route('spp.create') }}" class="db-quick-btn db-anim d1">
        <div class="db-qb-icon i-emerald">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
        </div>
        <span class="db-qb-label">Input SPP</span>
        <span class="db-qb-sub">Catat pembayaran baru</span>
        <span class="db-qb-arrow">→</span>
    </a>
    <a href="{{ route('spp.tunggakan') }}" class="db-quick-btn db-anim d2">
        <div class="db-qb-icon i-rose">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        </div>
        <span class="db-qb-label">Cek Tunggakan</span>
        <span class="db-qb-sub">Daftar belum bayar</span>
        <span class="db-qb-arrow">→</span>
    </a>
    <a href="{{ route('spp.index') }}" class="db-quick-btn db-anim d3">
        <div class="db-qb-icon i-indigo">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z"/></svg>
        </div>
        <span class="db-qb-label">Data SPP</span>
        <span class="db-qb-sub">Semua riwayat bayar</span>
        <span class="db-qb-arrow">→</span>
    </a>
    <a href="{{ route('siswa.index') }}" class="db-quick-btn db-anim d4">
        <div class="db-qb-icon i-cyan">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
        </div>
        <span class="db-qb-label">Data Siswa</span>
        <span class="db-qb-sub">Lihat semua siswa</span>
        <span class="db-qb-arrow">→</span>
    </a>
</div>

{{-- Pemasukan card + Donut --}}
<div class="db-section-label db-anim d3"><span>analitik keuangan</span></div>
<div class="db-charts-grid">

    {{-- Income premium card --}}
    <div class="db-card db-chart-panel db-income-card db-anim d1">
        <p class="db-income-label">Total Pemasukan SPP Bulan Ini</p>
        <p class="db-income-amount">Rp <span class="counter-rp" data-target="{{ $data['total_pemasukan'] ?? 0 }}">0</span></p>
        @php
            $targetBenda = ($data['total_siswa'] ?? 0) * 150000;
            $pctBenda = $targetBenda > 0 ? round(($data['total_pemasukan'] ?? 0) / $targetBenda * 100) : 0;
        @endphp
        <p class="db-income-sub">Estimasi target: Rp {{ number_format($targetBenda, 0, ',', '.') }}</p>
        <div class="db-progress-track">
            <div class="db-progress-fill" id="income-bar-benda" style="width:0%" data-pct="{{ min($pctBenda,100) }}"></div>
        </div>
        <div class="db-progress-labels">
            <span style="color:rgba(199,210,254,.28);font-size:10px;">0%</span>
            <span style="color:var(--db-cyan);font-size:11px;font-weight:700;" id="income-pct-benda">{{ $pctBenda }}%</span>
        </div>

        {{-- Mini stat row --}}
        <div style="display:flex;gap:16px;margin-top:18px;padding-top:16px;border-top:1px solid rgba(255,255,255,.07);">
            <div>
                <p style="font-size:10px;color:rgba(199,210,254,.40);font-family:var(--db-font-mono);text-transform:uppercase;letter-spacing:.08em;margin-bottom:4px;">Terbayar</p>
                <p style="font-family:var(--db-font-display);font-size:18px;font-weight:800;color:#6ee7b7;">{{ $data['spp_bulan_ini'] }}<span style="font-size:11px;font-weight:500;color:rgba(199,210,254,.35);"> siswa</span></p>
            </div>
            <div style="border-left:1px solid rgba(255,255,255,.07);padding-left:16px;">
                <p style="font-size:10px;color:rgba(199,210,254,.40);font-family:var(--db-font-mono);text-transform:uppercase;letter-spacing:.08em;margin-bottom:4px;">Tunggakan</p>
                <p style="font-family:var(--db-font-display);font-size:18px;font-weight:800;color:#fda4af;">{{ $data['tunggakan_spp'] }}<span style="font-size:11px;font-weight:500;color:rgba(199,210,254,.35);"> siswa</span></p>
            </div>
        </div>
    </div>

    {{-- Donut + Tunggakan list --}}
    <div class="db-right-stack">
        <div class="db-card db-chart-panel db-anim d2">
            <p class="db-chart-title" style="margin-bottom:16px;">Rasio Pembayaran</p>
            <div class="db-donut-wrap">
                <div style="position:relative;width:88px;height:88px;flex-shrink:0;">
                    <canvas id="chartDonutBenda" role="img" aria-label="Donut SPP Bendahara"></canvas>
                </div>
                <div class="db-donut-legend">
                    <div class="db-dl-row">
                        <div class="db-dl-left"><span class="db-dl-dot" style="background:#6366f1;"></span>Terbayar</div>
                        <span class="db-dl-val">{{ $data['spp_bulan_ini'] }}</span>
                    </div>
                    <div class="db-dl-row">
                        <div class="db-dl-left"><span class="db-dl-dot" style="background:#f43f5e;"></span>Tunggakan</div>
                        <span class="db-dl-val" style="color:#fda4af;">{{ $data['tunggakan_spp'] }}</span>
                    </div>
                    <div class="db-dl-row" style="border-top:1px solid var(--db-glass-border);padding-top:8px;margin-top:2px;">
                        <div class="db-dl-left"><span class="db-dl-dot" style="background:var(--text-3);"></span>Total</div>
                        <span class="db-dl-val">{{ $data['total_siswa'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="db-card db-chart-panel db-anim d3" style="padding:1.1rem 1.25rem;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
                <p style="font-family:var(--db-font-display);font-size:13px;font-weight:700;color:var(--text-1);">Aksi Cepat</p>
            </div>
            <div style="display:flex;flex-direction:column;gap:8px;">
                <a href="{{ route('spp.laporan') }}" style="display:flex;align-items:center;gap:10px;padding:9px 12px;background:rgba(99,102,241,.07);border:1px solid rgba(99,102,241,.12);border-radius:10px;text-decoration:none;transition:all .18s;" onmouseover="this.style.background='rgba(99,102,241,.12)'" onmouseout="this.style.background='rgba(99,102,241,.07)'">
                    <span style="font-size:14px;">📊</span>
                    <span style="font-family:var(--db-font-display);font-size:12.5px;font-weight:600;color:var(--text-1);">Laporan SPP Lengkap</span>
                    <span style="margin-left:auto;font-size:11px;color:var(--db-indigo);">→</span>
                </a>
                <a href="{{ route('spp.tunggakan') }}" style="display:flex;align-items:center;gap:10px;padding:9px 12px;background:rgba(244,63,94,.06);border:1px solid rgba(244,63,94,.12);border-radius:10px;text-decoration:none;transition:all .18s;" onmouseover="this.style.background='rgba(244,63,94,.11)'" onmouseout="this.style.background='rgba(244,63,94,.06)'">
                    <span style="font-size:14px;">⚠️</span>
                    <span style="font-family:var(--db-font-display);font-size:12.5px;font-weight:600;color:var(--text-1);">Daftar Tunggakan</span>
                    <span style="margin-left:auto;font-size:11px;color:#fda4af;">→</span>
                </a>
            </div>
        </div>
    </div>
</div>

@endif {{-- /Bendahara --}}


{{-- ══════════════════════════════════════
     ■ KEPALA SEKOLAH DASHBOARD
══════════════════════════════════════ --}}
@if(Auth::user()->isKepalaSekolah())

<div class="db-section-label db-anim d1"><span>ringkasan sekolah</span></div>

{{-- Stat cards --}}
<div class="db-stat-grid">
    <div class="db-card db-stat s-indigo db-anim d1">
        <div class="db-stat-glow" style="background:var(--db-indigo);"></div>
        <div class="db-stat-icon i-indigo">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
        </div>
        <p class="db-stat-label">Siswa Aktif</p>
        <p class="db-stat-num counter" data-target="{{ $data['total_siswa'] }}">0</p>
        <div class="db-stat-foot"><span class="db-pill pill-info">terdaftar</span></div>
    </div>

    <div class="db-card db-stat s-cyan db-anim d2">
        <div class="db-stat-glow" style="background:var(--db-cyan);"></div>
        <div class="db-stat-icon i-cyan">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
        <p class="db-stat-label">Guru Aktif</p>
        <p class="db-stat-num counter" data-target="{{ $data['total_guru'] }}">0</p>
        <div class="db-stat-foot">
            <a href="{{ route('guru.index') }}" class="db-stat-link">Lihat data <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a>
        </div>
    </div>

    <div class="db-card db-stat s-emerald db-anim d3">
        <div class="db-stat-glow" style="background:var(--db-emerald);"></div>
        <div class="db-stat-icon i-emerald">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="2"/><path d="m9 12 2 2 4-4"/></svg>
        </div>
        <p class="db-stat-label">Hadir Hari Ini</p>
        <p class="db-stat-num counter success" data-target="{{ $data['guru_hadir'] ?? 0 }}">0</p>
        <div class="db-stat-foot">
            @php $totalGuru = $data['total_guru'] ?? 0; $hadir = $data['guru_hadir'] ?? 0; @endphp
            <span class="db-pill {{ $hadir >= $totalGuru ? 'pill-ok' : 'pill-warn' }}">
                {{ $hadir }}/{{ $totalGuru }} guru
            </span>
        </div>
    </div>

    <div class="db-card db-stat s-amber db-anim d4">
        <div class="db-stat-glow" style="background:var(--db-amber);"></div>
        <div class="db-stat-icon i-amber">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
        </div>
        <p class="db-stat-label">SPP Bulan Ini</p>
        <p class="db-stat-num counter" data-target="{{ $data['spp_bulan_ini'] }}">0</p>
        <div class="db-stat-foot"><span class="db-pill pill-info">terbayar</span></div>
    </div>

    <div class="db-card db-stat s-rose db-anim d5">
        <div class="db-stat-glow" style="background:var(--db-rose);"></div>
        <div class="db-stat-icon i-rose">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        </div>
        <p class="db-stat-label">Tunggakan SPP</p>
        <p class="db-stat-num counter {{ $data['tunggakan_spp'] > 0 ? 'danger' : 'success' }}" data-target="{{ $data['tunggakan_spp'] }}">0</p>
        <div class="db-stat-foot">
            <a href="{{ route('spp.tunggakan') }}" class="db-stat-link" style="color:#fda4af;opacity:.80;">
                Detail <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</div>

{{-- Kehadiran ring + Chart --}}
<div class="db-section-label db-anim d2"><span>kehadiran & analitik</span></div>
<div class="db-kepsek-overview">

    {{-- Attendance ring visual --}}
    <div class="db-card db-ring-card db-anim d1">
        @php
            $pctHadir = $data['total_guru'] > 0 ? round(($data['guru_hadir'] ?? 0) / $data['total_guru'] * 100) : 0;
            $circumference = 2 * M_PI * 44; // r=44
            $offset = $circumference - ($pctHadir / 100 * $circumference);
            $ringColor = $pctHadir >= 80 ? '#10b981' : ($pctHadir >= 50 ? '#f59e0b' : '#f43f5e');
        @endphp
        <p style="font-family:var(--db-font-display);font-size:15px;font-weight:800;color:var(--text-1);margin-bottom:16px;letter-spacing:-.03em;">Kehadiran Guru Hari Ini</p>
        <div class="db-ring-wrap">
            <svg class="db-ring-svg" width="100" height="100" viewBox="0 0 100 100">
                <circle class="db-ring-track" cx="50" cy="50" r="44"/>
                <circle class="db-ring-fill"
                    cx="50" cy="50" r="44"
                    stroke="{{ $ringColor }}"
                    stroke-dasharray="{{ $circumference }}"
                    stroke-dashoffset="{{ $offset }}"
                    id="ring-fill-kepsek"/>
            </svg>
            <div class="db-ring-center">
                <span class="db-ring-pct" style="color:{{ $ringColor }};" id="ring-pct-kepsek">{{ $pctHadir }}%</span>
                <span class="db-ring-sub">hadir</span>
            </div>
        </div>
        <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;margin-top:4px;">
            <div style="text-align:center;">
                <p style="font-family:var(--db-font-display);font-size:20px;font-weight:800;color:#6ee7b7;">{{ $data['guru_hadir'] ?? 0 }}</p>
                <p style="font-size:10px;color:var(--text-3);font-family:var(--db-font-mono);text-transform:uppercase;letter-spacing:.05em;">Hadir</p>
            </div>
            <div style="border-left:1px solid var(--db-glass-border);padding-left:12px;text-align:center;">
                <p style="font-family:var(--db-font-display);font-size:20px;font-weight:800;color:#fda4af;">{{ $data['total_guru'] - ($data['guru_hadir'] ?? 0) }}</p>
                <p style="font-size:10px;color:var(--text-3);font-family:var(--db-font-mono);text-transform:uppercase;letter-spacing:.05em;">Tidak Hadir</p>
            </div>
            <div style="border-left:1px solid var(--db-glass-border);padding-left:12px;text-align:center;">
                <p style="font-family:var(--db-font-display);font-size:20px;font-weight:800;color:var(--text-1);">{{ $data['total_guru'] }}</p>
                <p style="font-size:10px;color:var(--text-3);font-family:var(--db-font-mono);text-transform:uppercase;letter-spacing:.05em;">Total</p>
            </div>
        </div>
    </div>

    {{-- Absensi bar chart --}}
    <div class="db-card db-chart-panel db-anim d2">
        <div class="db-chart-head">
            <span class="db-chart-title">Tren Absensi Guru</span>
            <div class="db-chart-tabs">
                <button class="db-ctab on" id="tab-bulan-ks" onclick="switchAbsenKs('bulan')">6 Bulan</button>
                <button class="db-ctab" id="tab-minggu-ks" onclick="switchAbsenKs('minggu')">Minggu Ini</button>
            </div>
        </div>
        <div style="position:relative;width:100%;height:190px;">
            <canvas id="chartAbsenKs" role="img" aria-label="Grafik absensi kepala sekolah"></canvas>
        </div>
        <div class="db-legend">
            <span class="db-ld"><span class="db-ld-sq" style="background:#10b981;"></span>Hadir</span>
            <span class="db-ld"><span class="db-ld-sq" style="background:#f59e0b;"></span>Izin</span>
            <span class="db-ld"><span class="db-ld-sq" style="background:#f43f5e;"></span>Alpha</span>
        </div>
    </div>
</div>

{{-- Quick links Kepsek --}}
<div class="db-section-label db-anim d3"><span>akses laporan</span></div>
<div class="db-quick-grid">
    <a href="{{ route('absensi.laporan') }}" class="db-quick-btn db-anim d1">
        <div class="db-qb-icon i-indigo">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z"/></svg>
        </div>
        <span class="db-qb-label">Laporan Absensi</span>
        <span class="db-qb-sub">Rekap & cetak PDF</span>
        <span class="db-qb-arrow">→</span>
    </a>
    <a href="{{ route('laporan.spp') }}" class="db-quick-btn db-anim d2">
        <div class="db-qb-icon i-emerald">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/></svg>
        </div>
        <span class="db-qb-label">Laporan SPP</span>
        <span class="db-qb-sub">Rekapitulasi keuangan</span>
        <span class="db-qb-arrow">→</span>
    </a>
    <a href="{{ route('siswa.index') }}" class="db-quick-btn db-anim d3">
        <div class="db-qb-icon i-cyan">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
        </div>
        <span class="db-qb-label">Data Siswa</span>
        <span class="db-qb-sub">Daftar peserta didik</span>
        <span class="db-qb-arrow">→</span>
    </a>
    <a href="{{ route('guru.index') }}" class="db-quick-btn db-anim d4">
        <div class="db-qb-icon i-violet">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
        <span class="db-qb-label">Data Guru</span>
        <span class="db-qb-sub">Profil tenaga pengajar</span>
        <span class="db-qb-arrow">→</span>
    </a>
</div>

@endif {{-- /Kepala Sekolah --}}


{{-- ══════════════════════════════════════
     ■ GURU DASHBOARD
══════════════════════════════════════ --}}
@if(Auth::user()->isGuru())

<div class="db-guru-wrap">

    {{-- Status Absensi Hari Ini --}}
    @if($data['sudah_absen'])
    <div class="db-card db-status-card status-hadir db-anim d1">
        <div class="db-status-icon si-green">
            <div class="db-ping ping-green"></div>
            <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <p class="db-status-title st-green">Sudah Absen ✓</p>
        <p class="db-status-sub">
            Status:
            <strong style="color:var(--text-1);">
                @switch($data['status_hari_ini'])
                    @case('hadir')      Hadir @break
                    @case('terlambat')  Terlambat @break
                    @case('izin')       Izin @break
                    @case('sakit')      Sakit @break
                    @case('tugas_luar') Tugas Luar @break
                    @default            {{ ucfirst($data['status_hari_ini'] ?? '-') }}
                @endswitch
            </strong>
        </p>
        @if($data['jam_masuk'])
        <div class="db-jam-chip">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            Jam masuk: {{ \Carbon\Carbon::parse($data['jam_masuk'])->format('H:i') }} WIB
        </div>
        @endif
    </div>
    @else
    <div class="db-card db-status-card status-belum db-anim d1">
        <div class="db-status-icon si-amber">
            <div class="db-ping ping-amber"></div>
            <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <p class="db-status-title st-amber">Belum Absen</p>
        <p class="db-status-sub">Anda belum melakukan absensi hari ini.</p>
        <a href="{{ route('absensi.index') }}" class="db-absen-btn">✦ Absen Sekarang</a>
    </div>
    @endif

    {{-- Rekap Bulan Ini --}}
    <div class="db-card db-anim d2">
        <div class="db-rekap-head">Rekap Bulan Ini</div>
        <div class="db-rekap-grid">
            <div class="db-rekap-cell">
                <p class="db-rekap-num rn-green counter" data-target="{{ $data['total_hadir'] }}">0</p>
                <p class="db-rekap-lbl">Hadir</p>
            </div>
            <div class="db-rekap-cell">
                <p class="db-rekap-num rn-amber counter" data-target="{{ $data['total_izin'] }}">0</p>
                <p class="db-rekap-lbl">Izin / Sakit</p>
            </div>
            <div class="db-rekap-cell">
                <p class="db-rekap-num rn-red counter" data-target="{{ $data['total_alpha'] }}">0</p>
                <p class="db-rekap-lbl">Alpha</p>
            </div>
        </div>
    </div>

    {{-- Mini progress kehadiran --}}
    @php
        $totalHariKerja = $data['total_hadir'] + $data['total_izin'] + $data['total_alpha'];
        $pctGuru = $totalHariKerja > 0 ? round($data['total_hadir'] / $totalHariKerja * 100) : 0;
    @endphp
    <div class="db-card db-anim d3" style="padding:1.35rem 1.5rem;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
            <p style="font-family:var(--db-font-display);font-size:15px;font-weight:800;color:var(--text-1);letter-spacing:-.03em;">Tingkat Kehadiran</p>
            <span style="font-family:var(--db-font-display);font-size:18px;font-weight:800;color:#6ee7b7;">{{ $pctGuru }}%</span>
        </div>
        <div style="height:8px;background:var(--db-glass-border);border-radius:9999px;overflow:hidden;margin-bottom:10px;">
            <div style="height:100%;width:{{ $pctGuru }}%;border-radius:9999px;
                background:linear-gradient(90deg,#10b981,#34d399);
                box-shadow:0 0 10px rgba(16,185,129,.40);
                transition:width 1.2s cubic-bezier(.22,1,.36,1);">
            </div>
        </div>
        <div style="display:flex;justify-content:space-between;font-size:11px;color:var(--text-3);font-family:var(--db-font-mono);">
            <span>{{ $data['total_hadir'] }} hari hadir</span>
            <span>dari {{ $totalHariKerja }} hari kerja</span>
        </div>
    </div>

    {{-- Quick links guru --}}
    <div class="db-quick-grid db-anim d4" style="grid-template-columns:1fr 1fr;">
        <a href="{{ route('absensi.index') }}" class="db-quick-btn">
            <div class="db-qb-icon i-emerald">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="2"/><path d="m9 12 2 2 4-4"/></svg>
            </div>
            <span class="db-qb-label">Riwayat Absensi</span>
            <span class="db-qb-arrow">→</span>
        </a>
        <a href="{{ route('absensi.izin.form') }}" class="db-quick-btn">
            <div class="db-qb-icon i-amber">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            </div>
            <span class="db-qb-label">Ajukan Izin</span>
            <span class="db-qb-arrow">→</span>
        </a>
    </div>

</div>{{-- /guru-wrap --}}
@endif {{-- /Guru --}}

</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
<script>
(function(){
    'use strict';

    /* ── Live Clock ── */
    function updateClock() {
        const now = new Date();
        const c = document.getElementById('live-clock');
        const d = document.getElementById('live-date');
        if (c) c.textContent = now.toLocaleTimeString('id-ID', { hour:'2-digit', minute:'2-digit' });
        if (d) d.textContent = now.toLocaleDateString('id-ID', { weekday:'long', day:'numeric', month:'long' });
    }
    updateClock();
    setInterval(updateClock, 1000);

    /* ── Counter animation ── */
    function animCount(el, target, dur, isRp) {
        const s = performance.now();
        (function tick(now) {
            const p = Math.min((now - s) / dur, 1);
            const e = 1 - Math.pow(1 - p, 3);
            const v = Math.round(e * target);
            el.textContent = isRp ? v.toLocaleString('id-ID') : v;
            if (p < 1) { requestAnimationFrame(tick); }
            else {
                el.style.transform = 'scale(1.06)';
                el.style.transition = 'transform .18s cubic-bezier(.34,1.56,.64,1)';
                setTimeout(() => { el.style.transform = 'scale(1)'; }, 180);
            }
        })(s);
    }

    document.querySelectorAll('.counter').forEach((el, i) => {
        setTimeout(() => animCount(el, +el.dataset.target || 0, 900, false), 160 + i * 70);
    });
    document.querySelectorAll('.counter-rp').forEach((el, i) => {
        setTimeout(() => animCount(el, +el.dataset.target || 0, 1200, true), 160 + i * 70);
    });

    /* ── Progress bars ── */
    function initProgressBar(barId, pctId, totalVal, targetVal) {
        const bar = document.getElementById(barId);
        const pctEl = document.getElementById(pctId);
        if (!bar) return;
        const pct = targetVal > 0 ? Math.round((totalVal / targetVal) * 100) : 0;
        setTimeout(() => {
            bar.style.width = Math.min(pct, 100) + '%';
            if (pctEl) pctEl.textContent = pct + '%';
        }, 500);
    }

    // Admin income bar
    const totalPemasukan = {{ $data['total_pemasukan'] ?? 0 }};
    const targetPemasukan = {{ $data['target_pemasukan'] ?? (($data['total_siswa'] ?? 0) * 150000) }};
    initProgressBar('income-bar', 'income-pct', totalPemasukan, targetPemasukan);

    // Bendahara income bar
    const bendaBar = document.getElementById('income-bar-benda');
    if (bendaBar) {
        const pctBenda = parseInt(bendaBar.dataset.pct || 0);
        setTimeout(() => { bendaBar.style.width = pctBenda + '%'; }, 500);
    }

    /* ── Dark mode util ── */
    function isDark() { return document.documentElement.classList.contains('dark'); }
    function cColors() {
        return isDark()
            ? { grid:'rgba(255,255,255,.04)', tick:'#3a4d66' }
            : { grid:'rgba(0,0,0,.04)',       tick:'#94a3b8' };
    }
    function tooltipDefaults() {
        const d = isDark();
        return {
            backgroundColor: d ? 'rgba(10,15,30,.96)' : 'rgba(255,255,255,.97)',
            titleColor:       d ? '#e2e8f0' : '#1e293b',
            bodyColor:        d ? '#94a3b8' : '#475569',
            borderColor: 'rgba(99,102,241,.18)',
            borderWidth:1, padding:10, cornerRadius:10,
        };
    }

    /* ── Helper: build bar chart ── */
    function buildBarChart(ctxId, datasets, labels, height) {
        const ctx = document.getElementById(ctxId);
        if (!ctx) return null;
        const c = cColors();
        return new Chart(ctx, {
            type: 'bar',
            data: { labels, datasets },
            options: {
                responsive:true, maintainAspectRatio:false,
                animation: { duration:700, easing:'easeOutQuart' },
                plugins: {
                    legend: { display:false },
                    tooltip: { mode:'index', intersect:false, ...tooltipDefaults() },
                },
                scales: {
                    x: { grid:{display:false}, ticks:{font:{size:11,family:"'DM Mono',monospace"},color:c.tick}, border:{display:false} },
                    y: { grid:{color:c.grid},  ticks:{font:{size:11,family:"'DM Mono',monospace"},color:c.tick,stepSize:2}, border:{display:false} },
                }
            }
        });
    }

    /* ── Absensi data — fetch real dari server ── */
const absenCache = {};   // cache agar tidak re-fetch saat toggle
let absenChart   = null;

async function fetchAbsenData(period) {
    if (absenCache[period]) return absenCache[period];

    const url = `{{ route('dashboard.chart-absensi') }}?period=${period}`;
    const res = await fetch(url, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    });

    if (!res.ok) throw new Error('Gagal memuat data chart');
    const data = await res.json();
    absenCache[period] = data;
    return data;
}

   async function buildAbsen(period, chartId) {
    const d = await fetchAbsenData(period);

    const datasets = [
        { label:'Hadir', data:d.hadir, backgroundColor:'rgba(16,185,129,.85)', borderRadius:6, borderSkipped:false },
        { label:'Izin',  data:d.izin,  backgroundColor:'rgba(245,158,11,.78)', borderRadius:6, borderSkipped:false },
        { label:'Alpha', data:d.alpha, backgroundColor:'rgba(244,63,94,.78)',  borderRadius:6, borderSkipped:false },
    ];
    const ctx = document.getElementById(chartId);
    if (!ctx) return null;
    if (absenChart) absenChart.destroy();
    absenChart = buildBarChart(chartId, datasets, d.labels);
    return absenChart;
}

    // Admin chart
   // Admin chart
if (document.getElementById('chartAbsen')) buildAbsen('bulan', 'chartAbsen');

window.switchAbsen = function(period) {
    document.getElementById('tab-bulan')?.classList.toggle('on', period === 'bulan');
    document.getElementById('tab-minggu')?.classList.toggle('on', period === 'minggu');
    buildAbsen(period, 'chartAbsen');
};
    // Kepsek chart
   if (document.getElementById('chartAbsenKs')) {
    let ksChart = null;

    window.switchAbsenKs = async function(period) {
        document.getElementById('tab-bulan-ks')?.classList.toggle('on', period === 'bulan');
        document.getElementById('tab-minggu-ks')?.classList.toggle('on', period === 'minggu');

        const d = await fetchAbsenData(period);   // pakai cache yang sama
        const ctx = document.getElementById('chartAbsenKs');
        if (!ctx) return;
        if (ksChart) ksChart.destroy();

        const c = cColors();
        ksChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: d.labels,
                datasets: [
                    { label:'Hadir', data:d.hadir, backgroundColor:'rgba(16,185,129,.85)', borderRadius:6, borderSkipped:false },
                    { label:'Izin',  data:d.izin,  backgroundColor:'rgba(245,158,11,.78)', borderRadius:6, borderSkipped:false },
                    { label:'Alpha', data:d.alpha, backgroundColor:'rgba(244,63,94,.78)',  borderRadius:6, borderSkipped:false },
                ]
            },
            options: {
                responsive:true, maintainAspectRatio:false,
                animation: { duration:700, easing:'easeOutQuart' },
                plugins: { legend:{ display:false }, tooltip:{ mode:'index', intersect:false, ...tooltipDefaults() } },
                scales: {
                    x: { grid:{display:false}, ticks:{font:{size:11},color:c.tick}, border:{display:false} },
                    y: { grid:{color:c.grid},  ticks:{font:{size:11},color:c.tick, stepSize:2}, border:{display:false} }
                }
            }
        });
    };

    window.switchAbsenKs('bulan');
}
    window.switchAbsen = function(period) {
        document.getElementById('tab-bulan')?.classList.toggle('on', period==='bulan');
        document.getElementById('tab-minggu')?.classList.toggle('on', period==='minggu');
        buildAbsen(period, 'chartAbsen');
    };

    /* ── SPP Bar chart — fetch real dari server ── */
    const sppCtx = document.getElementById('chartSPP');
    if (sppCtx) {
        fetch(`{{ route('dashboard.chart-spp') }}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(d => {
            const loading = document.getElementById('chartSPP-loading');
            if (loading) loading.style.display = 'none';
            buildBarChart('chartSPP', [
                { label:'Terbayar',  data: d.terbayar,  backgroundColor:'rgba(99,102,241,.85)', borderRadius:6, borderSkipped:false },
                { label:'Tunggakan', data: d.tunggakan, backgroundColor:'rgba(244,63,94,.50)',  borderRadius:6, borderSkipped:false },
            ], d.labels);
        })
        .catch(() => {
            const loading = document.getElementById('chartSPP-loading');
            if (loading) loading.textContent = 'Gagal memuat data.';
        });
    }

    /* ── Donut charts ── */
    function buildDonut(ctxId, terbayar, tunggakan) {
        const ctx = document.getElementById(ctxId);
        if (!ctx) return;
        new Chart(ctx, {
            type:'doughnut',
            data:{
                labels:['Terbayar','Tunggakan'],
                datasets:[{
                    data:[terbayar, tunggakan],
                    backgroundColor:['#6366f1','#f43f5e'],
                    hoverBackgroundColor:['#818cf8','#fb7185'],
                    borderWidth:0, hoverOffset:6,
                }]
            },
            options:{
                responsive:true, maintainAspectRatio:true, cutout:'74%',
                animation:{ animateRotate:true, duration:900 },
                plugins:{
                    legend:{ display:false },
                    tooltip:{ callbacks:{ label: ctx => ' '+ctx.label+': '+ctx.raw },
                        backgroundColor:'rgba(10,15,30,.96)', titleColor:'#e2e8f0',
                        bodyColor:'#94a3b8', borderColor:'rgba(99,102,241,.18)',
                        borderWidth:1, padding:8, cornerRadius:8,
                    }
                }
            }
        });
    }

    buildDonut('chartDonut', {{ $data['spp_bulan_ini'] ?? 0 }}, {{ $data['tunggakan_spp'] ?? 0 }});
    buildDonut('chartDonutBenda', {{ $data['spp_bulan_ini'] ?? 0 }}, {{ $data['tunggakan_spp'] ?? 0 }});

    /* ── Attendance ring animation (Kepala Sekolah) ── */
    const ringFill = document.getElementById('ring-fill-kepsek');
    if (ringFill) {
        const circumference = 2 * Math.PI * 44;
        const pct = {{ $pctHadir ?? 0 }};
        const targetOffset = circumference - (pct / 100 * circumference);
        ringFill.style.strokeDashoffset = circumference; // start at 0
        setTimeout(() => {
            ringFill.style.transition = 'stroke-dashoffset 1.2s cubic-bezier(.22,1,.36,1)';
            ringFill.style.strokeDashoffset = targetOffset;
        }, 300);
    }

    /* ── Dark mode toggle: re-render charts ── */
    window.addEventListener('toggle-dark', () => {
        setTimeout(() => {
            if (document.getElementById('chartAbsen')) {
                const period = document.getElementById('tab-bulan')?.classList.contains('on') ? 'bulan' : 'minggu';
                buildAbsen(period, 'chartAbsen');
            }
            if (typeof window.switchAbsenKs === 'function') {
                const period = document.getElementById('tab-bulan-ks')?.classList.contains('on') ? 'bulan' : 'minggu';
                window.switchAbsenKs(period);
            }
        }, 50);
    });

})();
</script>
@endpush