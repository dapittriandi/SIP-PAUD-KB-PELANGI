@extends('layouts.app')

@section('title', 'Konfirmasi Pembayaran SPP — PAUD KB Pelangi')
@section('page-title', 'Konfirmasi SPP')

@push('styles')
<style>
/* ══════════════════════════════════════════════════════════
   KONFIRMASI SPP — impeccable pass
   Semua token dari app.blade.php (OKLCH).
   Hapus: Lora serif, Instrument Sans, teal custom, ink custom.
   Tambah: progress lunas, bulk toolbar sticky, period pill.
   Glass: HANYA modal backdrop — purposeful.
══════════════════════════════════════════════════════════ */

[x-cloak] { display:none !important; }

/* ─── Breadcrumb ─────────────────────────────── */
.bc { display:flex; align-items:center; gap:5px; font-size:var(--fs-xs); color:var(--text-3); margin-bottom:18px; }
.bc a { color:var(--text-3); text-decoration:none; transition:color var(--dur-fast) ease; }
.bc a:hover { color:var(--accent); }
.bc svg { width:12px; height:12px; opacity:.4; flex-shrink:0; }
.bc-now { color:var(--text-2); font-weight:500; }

/* ─── Page header ────────────────────────────── */
.pg-head {
    display:flex; align-items:flex-start; justify-content:space-between;
    flex-wrap:wrap; gap:14px; margin-bottom:20px;
}
.pg-head-title {
    font-size:var(--fs-xl); font-weight:700; color:var(--text-1);
    letter-spacing:-0.025em; line-height:1.15; margin-bottom:3px;
}
.pg-head-sub { font-size:var(--fs-xs); color:var(--text-3); }

/* ─── Period pill ────────────────────────────── */
.period-pill {
    display:inline-flex; align-items:center; gap:8px;
    padding:7px 12px;
    background:var(--surface); border:1px solid var(--border);
    border-radius:var(--r);
    box-shadow:var(--shadow-xs);
}
.period-pill svg { width:14px; height:14px; color:var(--text-3); flex-shrink:0; }
.period-pill select {
    border:none; background:transparent;
    font-size:var(--fs-sm); font-weight:600; color:var(--text-1);
    font-family:'Geist',sans-serif; outline:none; cursor:pointer;
    padding:0;
}
.period-sep { font-size:var(--fs-xs); color:var(--text-3); margin:0 -2px; }

/* ─── Alert ──────────────────────────────────── */
.alert {
    display:flex; align-items:flex-start; gap:10px;
    padding:11px 14px; border-radius:var(--r-lg);
    font-size:var(--fs-sm); border:1px solid; margin-bottom:14px;
}
.alert svg { width:15px; height:15px; flex-shrink:0; margin-top:1px; }
.alert-success { background:var(--success-bg); border-color:var(--success-border); color:var(--success); }
.alert-error   { background:var(--danger-bg);  border-color:var(--danger-border);  color:var(--danger); }

/* ─── Stat strip ─────────────────────────────── */
/* Bukan 3 card terpisah — strip horizontal asimetris.
   Siswa aktif (netral), Lunas (sukses), Belum (warning). */
.stat-strip {
    display:flex; gap:0;
    background:var(--surface); border:1px solid var(--border);
    border-radius:var(--r-xl); overflow:hidden;
    box-shadow:var(--shadow-xs);
    margin-bottom:14px;
}
.sstat {
    flex:1; display:flex; align-items:center; gap:12px;
    padding:14px 18px;
    border-right:1px solid var(--border);
    min-width:0;
}
.sstat:last-child { border-right:none; }
@media(max-width:560px) {
    .stat-strip { flex-wrap:wrap; }
    .sstat { border-right:none; border-bottom:1px solid var(--border); }
    .sstat:last-child { border-bottom:none; }
}

.sstat-ico {
    width:36px; height:36px; border-radius:var(--r-sm);
    display:flex; align-items:center; justify-content:center; flex-shrink:0;
}
.sstat-ico svg { width:16px; height:16px; }
.sstat-ico.blue { background:var(--accent-muted); border:1px solid var(--accent-ring); }
.sstat-ico.blue svg { color:var(--accent); }
.sstat-ico.green { background:var(--success-bg); border:1px solid var(--success-border); }
.sstat-ico.green svg { color:var(--success); }
.sstat-ico.amber { background:var(--warning-bg); border:1px solid color-mix(in oklch, var(--warning), transparent 55%); }
.sstat-ico.amber svg { color:var(--warning); }

.sstat-body { min-width:0; }
.sstat-val {
    font-size:22px; font-weight:700; color:var(--text-1);
    line-height:1; font-variant-numeric:tabular-nums; letter-spacing:-0.02em;
}
.sstat-val.green { color:var(--success); }
.sstat-val.amber { color:var(--warning); }
.sstat-lbl { font-size:var(--fs-xs); color:var(--text-3); margin-top:3px; }

/* ─── Progress bar lunas ─────────────────────── */
.progress-wrap { margin-bottom:14px; }
.progress-meta {
    display:flex; align-items:center; justify-content:space-between;
    margin-bottom:5px;
}
.progress-label { font-size:var(--fs-xs); color:var(--text-3); font-family:'Geist Mono',monospace; text-transform:uppercase; letter-spacing:.06em; }
.progress-pct   { font-size:var(--fs-xs); font-weight:600; color:var(--text-2); font-variant-numeric:tabular-nums; }
.progress-track {
    width:100%; height:5px; border-radius:9999px;
    background:var(--border); overflow:hidden;
}
.progress-fill {
    height:100%; border-radius:9999px;
    background:var(--success);
    transition:width .6s var(--ease-out);
}

/* ─── Tarif info bar ─────────────────────────── */
/* Tidak lagi gradient gelap — pakai surface + accent tokens */
.tarif-bar {
    background:var(--surface); border:1px solid var(--border);
    border-radius:var(--r-lg); padding:13px 18px;
    display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;
    margin-bottom:14px;
    box-shadow:var(--shadow-xs);
    border-left:3px solid var(--accent);
}
.tarif-bar-left { display:flex; align-items:center; gap:12px; }
.tarif-ico {
    width:34px; height:34px; border-radius:var(--r-sm);
    background:var(--accent-muted); border:1px solid var(--accent-ring);
    display:flex; align-items:center; justify-content:center; flex-shrink:0;
}
.tarif-ico svg { width:15px; height:15px; color:var(--accent); }
.tarif-info .ti-lbl {
    font-size:var(--fs-2xs); color:var(--text-3);
    font-family:'Geist Mono',monospace; text-transform:uppercase; letter-spacing:.06em;
    margin-bottom:2px;
}
.tarif-info .ti-val {
    font-size:var(--fs-sm); font-weight:600; color:var(--text-1);
    display:flex; align-items:center; gap:6px; flex-wrap:wrap;
}
.tarif-info .ti-sep { color:var(--text-3); font-weight:400; }
.tarif-total { font-size:var(--fs-sm); font-weight:700; color:var(--accent); }

.tarif-edit-link {
    display:inline-flex; align-items:center; gap:5px;
    font-size:var(--fs-xs); font-weight:500;
    color:var(--accent); text-decoration:none;
    padding:5px 11px;
    background:var(--accent-muted); border:1px solid var(--accent-ring);
    border-radius:var(--r-sm);
    transition:background var(--dur-fast) ease;
    white-space:nowrap;
}
.tarif-edit-link:hover { background:color-mix(in oklch, var(--accent), transparent 82%); color:var(--accent); }
.tarif-edit-link svg { width:12px; height:12px; }

/* ─── Filter row ─────────────────────────────── */
.filter-row {
    background:var(--surface); border:1px solid var(--border);
    border-radius:var(--r-lg); padding:12px 14px;
    display:flex; flex-wrap:wrap; gap:8px; align-items:flex-end;
    margin-bottom:10px; box-shadow:var(--shadow-xs);
}
.srch-wrap { position:relative; flex:1; min-width:180px; }
.srch-wrap svg { position:absolute; left:10px; top:50%; transform:translateY(-50%); width:13px; height:13px; color:var(--text-3); pointer-events:none; }
.srch-wrap input { padding-left:30px; }

.fc {
    width:100%; padding:8px 11px;
    background:var(--bg-2); border:1px solid var(--border);
    border-radius:var(--r-sm);
    font-size:var(--fs-sm); font-family:'Geist',sans-serif; color:var(--text-1);
    outline:none;
    transition:border-color var(--dur-fast) ease, box-shadow var(--dur-fast) ease;
    appearance:none; -webkit-appearance:none;
}
.fc::placeholder { color:var(--text-3); }
.fc:focus {
    border-color:var(--accent-ring);
    box-shadow:0 0 0 3px color-mix(in oklch, var(--accent), transparent 84%);
}
select.fc {
    cursor:pointer;
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' viewBox='0 0 24 24'%3E%3Cpath stroke='%2394A3B8' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
    background-repeat:no-repeat; background-position:right 9px center;
    padding-right:28px;
}

.flt-select { width:auto; min-width:100px; }

.btn-filter {
    padding:8px 16px;
    background:var(--accent); color:var(--text-inv);
    font-size:var(--fs-sm); font-weight:500; font-family:'Geist',sans-serif;
    border:none; border-radius:var(--r-sm); cursor:pointer;
    transition:background var(--dur-fast) ease;
    white-space:nowrap;
    box-shadow:0 1px 3px color-mix(in oklch, var(--accent), transparent 60%);
}
.btn-filter:hover { background:var(--accent-h); }

.btn-reset {
    padding:8px 13px;
    background:transparent; border:1px solid var(--border);
    color:var(--text-2); font-size:var(--fs-sm); font-weight:500;
    font-family:'Geist',sans-serif; border-radius:var(--r-sm);
    text-decoration:none; cursor:pointer; white-space:nowrap;
    transition:background var(--dur-fast) ease;
}
.btn-reset:hover { background:var(--bg-2); }

/* ─── Bulk toolbar ───────────────────────────── */
/* Sticky di bawah — muncul saat ada siswa dipilih */
.bulk-toolbar {
    position:sticky; bottom:16px; z-index:20;
    background:var(--surface); border:1px solid var(--border);
    border-radius:var(--r-xl); padding:12px 16px;
    display:flex; align-items:center; justify-content:space-between;
    flex-wrap:wrap; gap:10px;
    box-shadow:var(--shadow-lg);
    margin-bottom:10px;
    transition:opacity var(--dur-mid) ease, transform var(--dur-mid) var(--ease-out);
}
.bulk-toolbar[style*="display:none"] { display:none !important; }
/* CSS-driven show/hide via Alpine — keduanya harus ada */
.bulk-toolbar.hidden-bulk {
    opacity:0; pointer-events:none;
    transform:translateY(8px);
}

.bulk-left { display:flex; align-items:center; gap:10px; }
.bulk-count {
    font-size:var(--fs-sm); font-weight:700; color:var(--text-1);
    display:flex; align-items:center; gap:5px;
}
.bulk-count-num { color:var(--accent); font-size:var(--fs-md); font-variant-numeric:tabular-nums; }

.bulk-right { display:flex; align-items:center; gap:8px; flex-wrap:wrap; }

.bulk-date-wrap { display:flex; flex-direction:column; gap:2px; }
.bulk-date-lbl {
    font-size:var(--fs-2xs); color:var(--text-3);
    font-family:'Geist Mono',monospace; text-transform:uppercase; letter-spacing:.05em;
}
.bulk-date-input {
    padding:6px 10px;
    background:var(--bg-2); border:1px solid var(--border);
    color:var(--text-1); border-radius:var(--r-sm);
    font-size:var(--fs-xs); font-family:'Geist',sans-serif;
    outline:none;
    transition:border-color var(--dur-fast) ease;
}
.bulk-date-input:focus { border-color:var(--accent-ring); }

.btn-bulk {
    display:inline-flex; align-items:center; gap:6px;
    padding:8px 16px;
    background:var(--success); color:var(--text-inv);
    font-size:var(--fs-sm); font-weight:600; font-family:'Geist',sans-serif;
    border:none; border-radius:var(--r-sm); cursor:pointer;
    box-shadow:0 1px 4px color-mix(in oklch, var(--success), transparent 60%);
    transition:filter var(--dur-fast) ease, transform var(--dur-micro) ease;
    white-space:nowrap;
}
.btn-bulk svg { width:13px; height:13px; }
.btn-bulk:hover { filter:brightness(1.08); }
.btn-bulk:active { transform:scale(0.97); }

.btn-deselect {
    padding:7px 12px;
    background:transparent; border:1px solid var(--border);
    color:var(--text-3); font-size:var(--fs-xs); font-weight:500;
    font-family:'Geist',sans-serif; border-radius:var(--r-sm); cursor:pointer;
    transition:background var(--dur-fast) ease;
}
.btn-deselect:hover { background:var(--bg-2); color:var(--text-2); }

/* ─── Table panel ────────────────────────────── */
.tbl-panel {
    background:var(--surface); border:1px solid var(--border);
    border-radius:var(--r-xl); overflow:hidden; box-shadow:var(--shadow-sm);
}
.tbl-head-bar {
    display:flex; align-items:center; justify-content:space-between;
    padding:12px 16px; border-bottom:1px solid var(--border);
    background:var(--bg);
}
.tbl-head-title { font-size:var(--fs-sm); font-weight:600; color:var(--text-1); }
.tbl-badge {
    font-size:var(--fs-xs); color:var(--text-3);
    background:var(--bg-2); border:1px solid var(--border);
    padding:2px 9px; border-radius:9999px;
    font-family:'Geist Mono',monospace;
}

/* Table */
.conf-tbl { width:100%; border-collapse:collapse; }
.conf-tbl thead tr { border-bottom:1px solid var(--border); }
.conf-tbl th {
    padding:10px 14px; text-align:left;
    font-size:var(--fs-2xs); font-weight:600; color:var(--text-3);
    letter-spacing:.07em; text-transform:uppercase;
    font-family:'Geist Mono',monospace; white-space:nowrap;
    background:var(--bg);
}
.conf-tbl th.cb-col { width:44px; text-align:center; }
.conf-tbl th.r { text-align:right; }

.conf-tbl td {
    padding:11px 14px; font-size:var(--fs-sm);
    color:var(--text-2); border-bottom:1px solid var(--border);
    vertical-align:middle;
}
.conf-tbl tbody tr:last-child td { border-bottom:none; }
.conf-tbl td.cb-col { text-align:center; }
.conf-tbl tbody tr { transition:background var(--dur-micro) ease; }
.conf-tbl tbody tr:hover td { background:var(--accent-soft); }
.conf-tbl tbody tr.is-lunas td { background:color-mix(in oklch, var(--success-bg), transparent 50%); }
.conf-tbl tbody tr.is-lunas:hover td { background:var(--success-bg); }

/* checkbox */
.conf-cb {
    width:16px; height:16px; cursor:pointer;
    accent-color:var(--accent);
    border-radius:4px;
}
.conf-cb:disabled { cursor:not-allowed; opacity:.4; }

/* cells */
.num-cell { color:var(--text-3); font-size:var(--fs-xs); font-family:'Geist Mono',monospace; }
.nis-chip {
    font-family:'Geist Mono',monospace; font-size:var(--fs-xs);
    background:var(--bg-2); color:var(--text-2);
    padding:2px 7px; border-radius:var(--r-sm);
    border:1px solid var(--border); display:inline-block;
}
.nama-val { font-weight:600; color:var(--text-1); }

/* pills */
.pill {
    display:inline-flex; align-items:center; gap:4px;
    padding:2px 9px; border-radius:9999px;
    font-size:var(--fs-xs); font-weight:500; white-space:nowrap;
}
.pill-blue  { background:var(--accent-muted); color:var(--accent); border:1px solid var(--accent-ring); }
.pill-green { background:var(--success-bg); color:var(--success); border:1px solid var(--success-border); }
.pill-amber { background:var(--warning-bg); color:var(--warning); border:1px solid color-mix(in oklch, var(--warning), transparent 55%); }
.pill-green svg, .pill-amber svg { width:10px; height:10px; }

/* action buttons */
.acts { display:flex; justify-content:flex-end; gap:4px; }
.act-btn {
    display:inline-flex; align-items:center; gap:5px;
    padding:5px 12px;
    font-size:var(--fs-xs); font-weight:500; font-family:'Geist',sans-serif;
    border-radius:var(--r-sm); cursor:pointer;
    transition:all var(--dur-fast) ease;
    white-space:nowrap;
}
.act-btn svg { width:12px; height:12px; }
.btn-konfirm {
    background:var(--success); color:var(--text-inv); border:none;
    box-shadow:0 1px 3px color-mix(in oklch, var(--success), transparent 60%);
}
.btn-konfirm:hover { filter:brightness(1.08); transform:translateY(-1px); }

.btn-kwitansi {
    background:transparent; color:var(--text-2);
    border:1px solid var(--border);
    text-decoration:none;
}
.btn-kwitansi:hover { border-color:var(--accent-ring); background:var(--accent-soft); color:var(--accent); }

/* ─── Table footer ───────────────────────────── */
.tbl-footer {
    padding:10px 16px; border-top:1px solid var(--border);
    background:var(--bg);
}

/* ─── Empty state ────────────────────────────── */
.empty-state {
    padding:56px 20px; text-align:center;
    display:flex; flex-direction:column; align-items:center;
}
.empty-ico {
    width:44px; height:44px; border-radius:var(--r-lg);
    background:var(--success-bg); border:1px solid var(--success-border);
    display:flex; align-items:center; justify-content:center; margin-bottom:12px;
}
.empty-ico svg { width:20px; height:20px; color:var(--success); }
.empty-title { font-size:var(--fs-sm); font-weight:600; color:var(--text-1); margin-bottom:4px; }
.empty-sub   { font-size:var(--fs-xs); color:var(--text-3); }

/* ─── Modal ──────────────────────────────────── */
.modal-wrap {
    position:fixed; inset:0; z-index:50;
    display:flex; align-items:center; justify-content:center;
    padding:16px;
}
.modal-bd {
    position:absolute; inset:0;
    background:oklch(10% 0.01 265 / 48%);
    backdrop-filter:blur(8px); -webkit-backdrop-filter:blur(8px);
}
.modal-inner {
    position:relative;
    background:var(--surface); border:1px solid var(--border);
    border-radius:var(--r-xl); width:100%; max-width:420px;
    box-shadow:var(--shadow-lg); overflow:hidden;
}

.modal-hd {
    display:flex; align-items:center; justify-content:space-between;
    padding:16px 20px; border-bottom:1px solid var(--border);
}
.modal-hd-left { display:flex; align-items:center; gap:10px; }
.modal-hd-ico {
    width:30px; height:30px; border-radius:var(--r-sm);
    background:var(--success-bg); border:1px solid var(--success-border);
    display:flex; align-items:center; justify-content:center; flex-shrink:0;
}
.modal-hd-ico svg { width:14px; height:14px; color:var(--success); }
.modal-hd-title { font-size:var(--fs-sm); font-weight:600; color:var(--text-1); }
.modal-hd-sub   { font-size:var(--fs-xs); color:var(--text-3); margin-top:1px; }
.modal-cls {
    width:28px; height:28px; border-radius:var(--r-sm);
    border:1px solid var(--border); background:transparent;
    color:var(--text-3); cursor:pointer;
    display:flex; align-items:center; justify-content:center;
    transition:background var(--dur-fast) ease;
}
.modal-cls:hover { background:var(--bg-2); color:var(--text-1); }
.modal-cls svg { width:13px; height:13px; }

.modal-body { padding:18px 20px; display:flex; flex-direction:column; gap:14px; }

/* nominal breakdown dalam modal */
.nom-breakdown {
    background:var(--bg-2); border:1px solid var(--border);
    border-radius:var(--r-sm); padding:12px 14px;
    display:flex; align-items:center; gap:12px; flex-wrap:wrap;
}
.nb-item { display:flex; flex-direction:column; gap:2px; }
.nb-lbl {
    font-size:var(--fs-2xs); color:var(--text-3);
    font-family:'Geist Mono',monospace; text-transform:uppercase; letter-spacing:.06em;
}
.nb-val { font-size:var(--fs-sm); font-weight:600; color:var(--text-1); font-variant-numeric:tabular-nums; }
.nb-val.total { font-size:var(--fs-md); font-weight:700; color:var(--accent); }
.nb-sep { font-size:16px; color:var(--text-3); flex-shrink:0; }

/* field */
.fld-lbl {
    display:flex; align-items:baseline; gap:4px;
    font-size:var(--fs-xs); font-weight:500;
    color:var(--text-2); margin-bottom:5px;
}
.fld-lbl .req { color:var(--danger); }

.modal-ft { padding:0 20px 18px; display:flex; align-items:center; justify-content:flex-end; gap:8px; }

.btn-primary {
    display:inline-flex; align-items:center; gap:6px;
    padding:9px 16px;
    background:var(--success); color:var(--text-inv);
    font-size:var(--fs-sm); font-weight:600; font-family:'Geist',sans-serif;
    border-radius:var(--r-sm); border:none; cursor:pointer;
    box-shadow:0 1px 4px color-mix(in oklch, var(--success), transparent 60%);
    transition:filter var(--dur-fast) ease, transform var(--dur-micro) ease;
    white-space:nowrap;
}
.btn-primary svg { width:13px; height:13px; }
.btn-primary:hover { filter:brightness(1.08); }
.btn-primary:active { transform:scale(0.97); }

.btn-ghost {
    padding:9px 15px;
    background:transparent; border:1px solid var(--border); color:var(--text-2);
    font-size:var(--fs-sm); font-weight:500; font-family:'Geist',sans-serif;
    border-radius:var(--r-sm); cursor:pointer;
    transition:background var(--dur-fast) ease;
}
.btn-ghost:hover { background:var(--bg-2); }

/* ─── Pagination ─────────────────────────────── */
.pager-wrap { padding:12px 16px; border-top:1px solid var(--border); }

/* ─── Entrance animation ─────────────────────── */
@media (prefers-reduced-motion:no-preference) {
    .au { animation:fadeUp var(--dur-page) var(--ease-out) both; }
    .d0 { animation-delay:0ms; }
    .d1 { animation-delay:45ms; }
    .d2 { animation-delay:85ms; }
    .d3 { animation-delay:120ms; }
    @keyframes fadeUp {
        from { opacity:0; transform:translateY(7px); }
        to   { opacity:1; transform:translateY(0); }
    }
}
</style>
@endpush

@section('content')
@php
    $pctLunas = $totalSiswa > 0 ? round(($totalLunas / $totalSiswa) * 100) : 0;
@endphp
<div class="max-w-4xl mx-auto" x-data="konfirmasiSpp()">

    {{-- Breadcrumb --}}
    <div class="bc au d0">
        <a href="{{ route('spp.index') }}">Dashboard SPP</a>
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="bc-now">Konfirmasi Pembayaran</span>
    </div>

    {{-- Page header + period selector --}}
    <div class="pg-head au d0">
        <div>
            <div class="pg-head-title">Konfirmasi Pembayaran SPP</div>
            <div class="pg-head-sub">Pilih periode &rarr; konfirmasi satu-satu atau massal</div>
        </div>

        {{-- Period selector --}}
        <form method="GET" action="{{ route('spp.konfirmasi') }}">
            <input type="hidden" name="cari"     value="{{ request('cari') }}">
            <input type="hidden" name="kelompok" value="{{ request('kelompok') }}">
            <input type="hidden" name="status"   value="{{ request('status') }}">
            <div class="period-pill">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <select name="bulan" onchange="this.form.submit()">
                    @foreach(range(1,12) as $b)
                    <option value="{{ $b }}" @selected($b==$bulan)>
                        {{ \Carbon\Carbon::create(null,$b)->translatedFormat('F') }}
                    </option>
                    @endforeach
                </select>
                <span class="period-sep">/</span>
                <select name="tahun" onchange="this.form.submit()">
                    @foreach(range(now()->year, now()->year-3, -1) as $y)
                    <option value="{{ $y }}" @selected($y==$tahun)>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    {{-- Alert --}}
    @if(session('success'))
    <div class="alert alert-success au d0">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-error au d0">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z"/>
        </svg>
        {{ session('error') }}
    </div>
    @endif

    {{-- Stat strip --}}
    <div class="stat-strip au d1">
        <div class="sstat">
            <div class="sstat-ico blue">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>
                </svg>
            </div>
            <div class="sstat-body">
                <div class="sstat-val">{{ $totalSiswa }}</div>
                <div class="sstat-lbl">Siswa aktif</div>
            </div>
        </div>
        <div class="sstat">
            <div class="sstat-ico green">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="sstat-body">
                <div class="sstat-val green">{{ $totalLunas }}</div>
                <div class="sstat-lbl">Sudah lunas</div>
            </div>
        </div>
        <div class="sstat">
            <div class="sstat-ico amber">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="sstat-body">
                <div class="sstat-val amber">{{ $totalBelum }}</div>
                <div class="sstat-lbl">Belum bayar</div>
            </div>
        </div>
    </div>

    {{-- Progress bar --}}
    <div class="progress-wrap au d1">
        <div class="progress-meta">
            <span class="progress-label">Progres lunas bulan ini</span>
            <span class="progress-pct">{{ $totalLunas }}/{{ $totalSiswa }} &nbsp;·&nbsp; {{ $pctLunas }}%</span>
        </div>
        <div class="progress-track">
            <div class="progress-fill" style="width:{{ $pctLunas }}%"></div>
        </div>
    </div>

    {{-- Tarif info bar --}}
    <div class="tarif-bar au d1">
        <div class="tarif-bar-left">
            <div class="tarif-ico">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div class="tarif-info">
                <div class="ti-lbl">Tarif aktif {{ $tahun }}</div>
                <div class="ti-val">
                    <span>SPP {{ $tarif->nominal_spp_rupiah }}</span>
                    <span class="ti-sep">+</span>
                    <span>Kebersihan {{ $tarif->nominal_kebersihan_rupiah }}</span>
                    <span class="ti-sep">=</span>
                    <span class="tarif-total">{{ $tarif->total_rupiah }} / siswa</span>
                </div>
            </div>
        </div>
        @if(in_array(Auth::user()->role, ['admin', 'bendahara']))
        <a href="{{ route('spp.tarif.index') }}" class="tarif-edit-link">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Ubah Tarif
        </a>
        @endif
    </div>

    {{-- Filter --}}
    <form method="GET" action="{{ route('spp.konfirmasi') }}">
        <input type="hidden" name="bulan" value="{{ $bulan }}">
        <input type="hidden" name="tahun" value="{{ $tahun }}">
        <div class="filter-row au d2">
            <div class="srch-wrap">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/>
                </svg>
                <input type="text" name="cari" value="{{ request('cari') }}"
                       placeholder="Cari nama / NIS..." class="fc">
            </div>
            <select name="kelompok" class="fc flt-select">
                <option value="">Semua Kelompok</option>
                @foreach($kelompokList as $klp)
                <option value="{{ $klp }}" @selected(request('kelompok')==$klp)>{{ $klp }}</option>
                @endforeach
            </select>
            <select name="status" class="fc flt-select">
                <option value="">Semua Status</option>
                <option value="belum" @selected(request('status')=='belum')>Belum Bayar</option>
                <option value="lunas" @selected(request('status')=='lunas')>Sudah Lunas</option>
            </select>
            <button type="submit" class="btn-filter">Tampilkan</button>
            @if(request()->hasAny(['cari','kelompok','status']))
            <a href="{{ route('spp.konfirmasi', ['bulan'=>$bulan,'tahun'=>$tahun]) }}" class="btn-reset">Reset</a>
            @endif
        </div>
    </form>

    {{-- Bulk toolbar (sticky bottom, muncul saat ada pilihan) --}}
    <div class="bulk-toolbar" :class="{ 'hidden-bulk': selected.length === 0 }" x-show="selected.length > 0"
         x-transition:enter="transition duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2">
        <div class="bulk-left">
            <div class="bulk-count">
                <span class="bulk-count-num" x-text="selected.length"></span>
                siswa dipilih
            </div>
            <button type="button" class="btn-deselect" @click="deselectAll()">Batalkan pilihan</button>
        </div>
        <div class="bulk-right">
            <div class="bulk-date-wrap">
                <div class="bulk-date-lbl">Tanggal Bayar</div>
                <input type="date" class="bulk-date-input" x-model="bulkDate" :max="today">
            </div>
            <button type="button" class="btn-bulk" @click="submitBulk()">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Konfirmasi Massal
            </button>
        </div>
    </div>

    {{-- Bulk form (hidden) --}}
    <form id="bulkForm" method="POST" action="{{ route('spp.konfirmasi.massal') }}" style="display:none">
        @csrf
        <input type="hidden" name="bulan" value="{{ $bulan }}">
        <input type="hidden" name="tahun" value="{{ $tahun }}">
        <input type="hidden" name="tanggal_bayar" x-bind:value="bulkDate">
        <template x-for="id in selected" :key="id">
            <input type="hidden" name="siswa_ids[]" :value="id">
        </template>
    </form>

    {{-- Table panel --}}
    <div class="tbl-panel au d3">
        <div class="tbl-head-bar">
            <span class="tbl-head-title">
                {{ \Carbon\Carbon::create(null, $bulan)->translatedFormat('F') }} {{ $tahun }}
            </span>
            <span class="tbl-badge">{{ $siswaList->total() }} siswa</span>
        </div>

        @if($siswaList->isEmpty())
        <div class="empty-state">
            <div class="empty-ico">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="empty-title">Tidak ada data ditemukan</p>
            <p class="empty-sub">Coba ubah filter pencarian</p>
        </div>
        @else
        <div style="overflow-x:auto">
            <table class="conf-tbl">
                <thead>
                    <tr>
                        <th class="cb-col">
                            <input type="checkbox" class="conf-cb"
                                   @change="toggleAll($event)"
                                   :checked="allChecked"
                                   title="Pilih semua belum bayar">
                        </th>
                        <th style="width:36px">No</th>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
                        <th class="hidden sm:table-cell">Kelompok</th>
                        <th>Status</th>
                        <th class="r">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($siswaList as $i => $siswa)
                    @php
                        $bayar   = $siswa->pembayaranSpp->first();
                        $isLunas = $bayar !== null;
                    @endphp
                    <tr class="{{ $isLunas ? 'is-lunas' : '' }}">
                        <td class="cb-col">
                            @if(!$isLunas)
                            <input type="checkbox" class="conf-cb"
                                   value="{{ $siswa->id }}"
                                   @change="toggleOne($event, {{ $siswa->id }})">
                            @endif
                        </td>
                        <td class="num-cell">{{ $siswaList->firstItem() + $i }}</td>
                        <td><span class="nis-chip">{{ $siswa->nis ?? '—' }}</span></td>
                        <td><span class="nama-val">{{ $siswa->nama_lengkap }}</span></td>
                        <td class="hidden sm:table-cell">
                            <span class="pill pill-blue">{{ $siswa->kelompok }}</span>
                        </td>
                        <td>
                            @if($isLunas)
                                <span class="pill pill-green">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Lunas
                                </span>
                            @else
                                <span class="pill pill-amber">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Belum
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="acts">
                                @if($isLunas)
                                    <a href="{{ route('spp.kwitansi', $bayar) }}" class="act-btn btn-kwitansi">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        Kwitansi
                                    </a>
                                @else
                                    <button type="button" class="act-btn btn-konfirm"
                                            @click="openModal({{ $siswa->id }}, '{{ addslashes($siswa->nama_lengkap) }}')">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Konfirmasi
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($siswaList->hasPages())
        <div class="pager-wrap">
            {{ $siswaList->appends(request()->query())->links() }}
        </div>
        @endif
        @endif
    </div>


    {{-- ════════════════════════════════════════
         MODAL — KONFIRMASI SATU SISWA
    ════════════════════════════════════════ --}}
    <div x-show="showModal" x-cloak class="modal-wrap" role="dialog" aria-modal="true">
        <div class="modal-bd"
             x-transition:enter="transition duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             @click="showModal = false"></div>

        <div class="modal-inner"
             x-transition:enter="transition duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-2" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">

            <div class="modal-hd">
                <div class="modal-hd-left">
                    <div class="modal-hd-ico">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div>
                        <div class="modal-hd-title">Konfirmasi Pembayaran</div>
                        <div class="modal-hd-sub" x-text="modalSiswa"></div>
                    </div>
                </div>
                <button class="modal-cls" @click="showModal = false" aria-label="Tutup">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form :action="'{{ route('spp.konfirmasi.satu') }}'" method="POST">
                @csrf
                <input type="hidden" name="siswa_id"           :value="modalSiswaId">
                <input type="hidden" name="bulan"              value="{{ $bulan }}">
                <input type="hidden" name="tahun"              value="{{ $tahun }}">
                <input type="hidden" name="nominal_spp"        value="{{ $tarif->nominal_spp }}">
                <input type="hidden" name="nominal_kebersihan" value="{{ $tarif->nominal_kebersihan }}">

                <div class="modal-body">
                    {{-- Nominal breakdown --}}
                    <div class="nom-breakdown">
                        <div class="nb-item">
                            <div class="nb-lbl">SPP</div>
                            <div class="nb-val">{{ $tarif->nominal_spp_rupiah }}</div>
                        </div>
                        <div class="nb-sep">+</div>
                        <div class="nb-item">
                            <div class="nb-lbl">Kebersihan</div>
                            <div class="nb-val">{{ $tarif->nominal_kebersihan_rupiah }}</div>
                        </div>
                        <div class="nb-sep">=</div>
                        <div class="nb-item">
                            <div class="nb-lbl">Total</div>
                            <div class="nb-val total">{{ $tarif->total_rupiah }}</div>
                        </div>
                    </div>

                    {{-- Tanggal bayar --}}
                    <div>
                        <label class="fld-lbl">Tanggal Bayar <span class="req">*</span></label>
                        <input type="date" name="tanggal_bayar" class="fc"
                               :value="today" :max="today" required>
                    </div>
                </div>

                <div class="modal-ft">
                    <button type="button" class="btn-ghost" @click="showModal = false">Batal</button>
                    <button type="submit" class="btn-primary">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Konfirmasi &amp; Cetak Kwitansi
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection


@push('scripts')
<script>
function konfirmasiSpp() {
    const today = '{{ today()->format('Y-m-d') }}';
    return {
        today,
        selected: [],
        bulkDate: today,
        showModal:    false,
        modalSiswaId: null,
        modalSiswa:   '',

        get allChecked() {
            const boxes = document.querySelectorAll('.conf-cb:not([title])');
            return boxes.length > 0 && this.selected.length === boxes.length;
        },

        toggleAll(e) {
            const boxes = document.querySelectorAll('.conf-cb:not([title]):not(:disabled)');
            if (e.target.checked) {
                this.selected = [...boxes].map(b => parseInt(b.value));
            } else {
                this.selected = [];
            }
            boxes.forEach(b => b.checked = e.target.checked);
        },

        toggleOne(e, id) {
            if (e.target.checked) {
                if (!this.selected.includes(id)) this.selected.push(id);
            } else {
                this.selected = this.selected.filter(s => s !== id);
            }
        },

        deselectAll() {
            this.selected = [];
            document.querySelectorAll('.conf-cb').forEach(b => b.checked = false);
        },

        openModal(siswaId, nama) {
            this.modalSiswaId = siswaId;
            this.modalSiswa   = nama;
            this.showModal    = true;
        },

        submitBulk() {
            if (this.selected.length === 0) return;
            if (!this.bulkDate) {
                alert('Pilih tanggal bayar terlebih dahulu.');
                return;
            }
            if (confirm(`Konfirmasi pembayaran untuk ${this.selected.length} siswa pada tanggal ${this.bulkDate}?`)) {
                document.getElementById('bulkForm').submit();
            }
        }
    };
}

/* Escape menutup modal */
document.addEventListener('keydown', function (e) {
    if (e.key !== 'Escape') return;
    const comp = document.querySelector('[x-data]')?._x_dataStack?.[0];
    if (comp) comp.showModal = false;
});
</script>
@endpush