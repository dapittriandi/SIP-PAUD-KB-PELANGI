@extends('layouts.app')

@section('title', 'Tarif SPP — PAUD KB Pelangi')
@section('page-title', 'Tarif SPP')

@push('styles')
<style>
/* ══════════════════════════════════════════════════════════
   TARIF SPP INDEX — impeccable pass
   Semua token dari app.blade.php (OKLCH).
   Migrasi dari Tailwind hardcoded classes + glass-as-default.
   Modal: konsisten dengan pattern show/edit/create siswa.
   Glass: HANYA pada modal backdrop overlay — purposeful.
══════════════════════════════════════════════════════════ */

[x-cloak] { display: none !important; }

/* ─── Alert / Toast ──────────────────────────── */
.alert {
    display: flex; align-items: flex-start; gap: 10px;
    padding: 12px 14px;
    border-radius: var(--r-lg);
    font-size: var(--fs-sm);
    border: 1px solid;
}
.alert-success {
    background: var(--success-bg);
    border-color: var(--success-border);
    color: var(--success);
}
.alert-error {
    background: var(--danger-bg);
    border-color: var(--danger-border);
    color: var(--danger);
}
.alert svg.alert-ico { width: 16px; height: 16px; flex-shrink: 0; margin-top: 1px; }
.alert-body { flex: 1; }
.alert-close {
    background: transparent; border: none; cursor: pointer;
    color: inherit; opacity: .6;
    display: flex; align-items: center; justify-content: center;
    padding: 2px; border-radius: var(--r-sm);
    transition: opacity var(--dur-fast) ease;
    flex-shrink: 0;
}
.alert-close:hover { opacity: 1; }
.alert-close svg { width: 13px; height: 13px; }

/* ─── Summary strip ──────────────────────────── */
/* Bukan metric cards — satu baris tipis di atas tabel */
.summary-strip {
    display: flex; align-items: center; gap: 0;
    padding: 10px 0;
    border-bottom: 1px solid var(--border);
    margin-bottom: 16px;
    overflow-x: auto;
}
.summary-strip::-webkit-scrollbar { display: none; }
.sitem {
    flex: 1; min-width: 90px;
    display: flex; flex-direction: column; gap: 1px;
    padding: 0 16px;
    border-right: 1px solid var(--border);
}
.sitem:first-child { padding-left: 0; }
.sitem:last-child  { border-right: none; }
.s-lbl {
    font-size: var(--fs-2xs); color: var(--text-3);
    font-family: 'Geist Mono', monospace;
    text-transform: uppercase; letter-spacing: .06em;
}
.s-val {
    font-size: var(--fs-sm); font-weight: 600;
    color: var(--text-1); white-space: nowrap;
    font-variant-numeric: tabular-nums;
}

/* ─── Panel (tabel wrapper) ──────────────────── */
.tbl-panel {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--r-xl);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}

/* ─── Table ──────────────────────────────────── */
.tarif-tbl { width: 100%; border-collapse: collapse; }
.tarif-tbl thead tr {
    border-bottom: 1px solid var(--border);
    background: var(--bg);
}
.tarif-tbl th {
    text-align: left; padding: 10px 16px;
    font-size: var(--fs-2xs); font-weight: 600;
    color: var(--text-3); letter-spacing: .07em; text-transform: uppercase;
    font-family: 'Geist Mono', monospace; white-space: nowrap;
}
.tarif-tbl th.r { text-align: right; }
.tarif-tbl th.c { text-align: center; }

.tarif-tbl td {
    padding: 12px 16px;
    font-size: var(--fs-sm); color: var(--text-2);
    border-bottom: 1px solid var(--border);
    vertical-align: middle;
}
.tarif-tbl tbody tr:last-child td { border-bottom: none; }
.tarif-tbl tbody tr { transition: background var(--dur-micro) ease; }
.tarif-tbl tbody tr:hover td { background: var(--accent-soft); }

/* numeric cells */
.cell-num {
    text-align: right;
    font-family: 'Geist Mono', monospace;
    font-variant-numeric: tabular-nums;
    font-size: var(--fs-xs);
    color: var(--text-2);
    font-weight: 500;
}
.cell-total {
    text-align: right;
    font-family: 'Geist Mono', monospace;
    font-variant-numeric: tabular-nums;
    font-weight: 700;
    color: var(--text-1);
    white-space: nowrap;
}
.cell-keterangan {
    color: var(--text-3);
    font-size: var(--fs-xs);
    max-width: 180px;
}
.cell-keterangan.has-val { color: var(--text-2); }

/* ─── Tahun + badge aktif ────────────────────── */
.tahun-wrap { display: flex; align-items: center; gap: 7px; }
.tahun-val  { font-size: var(--fs-sm); font-weight: 700; color: var(--text-1); font-variant-numeric: tabular-nums; }
.pill-aktif {
    display: inline-flex; align-items: center; gap: 3px;
    padding: 1px 8px; border-radius: 9999px;
    font-size: var(--fs-2xs); font-weight: 600;
    background: var(--success-bg); color: var(--success);
    border: 1px solid var(--success-border);
    white-space: nowrap;
}
.pill-aktif::before {
    content: '';
    width: 5px; height: 5px; border-radius: 50%;
    background: var(--success);
    display: inline-block;
}

/* ─── Action buttons in table ────────────────── */
.acts { display: flex; align-items: center; justify-content: center; gap: 4px; }
.act-btn {
    position: relative; width: 30px; height: 30px;
    border-radius: var(--r-sm); border: 1px solid var(--border);
    background: transparent; color: var(--text-3);
    cursor: pointer; display: flex; align-items: center; justify-content: center;
    transition: all var(--dur-fast) var(--ease-out);
}
.act-btn svg { width: 13px; height: 13px; }
.act-btn:hover { transform: translateY(-1px); box-shadow: var(--shadow-xs); }
.act-btn.edit:hover {
    border-color: color-mix(in oklch, var(--warning), transparent 50%);
    background: var(--warning-bg); color: var(--warning);
}
.act-btn.del:hover {
    border-color: var(--danger-border);
    background: var(--danger-bg); color: var(--danger);
}
/* tooltip */
.act-btn .tt {
    position: absolute; bottom: calc(100% + 6px); left: 50%;
    transform: translateX(-50%);
    background: var(--text-1); color: var(--surface);
    font-size: var(--fs-2xs); padding: 3px 8px;
    border-radius: var(--r-sm); white-space: nowrap;
    opacity: 0; pointer-events: none;
    transition: opacity var(--dur-fast) ease;
    font-family: 'Geist', sans-serif;
}
.act-btn:hover .tt { opacity: 1; }

/* ─── Empty state ────────────────────────────── */
.empty-state {
    padding: 60px 20px; text-align: center;
    display: flex; flex-direction: column; align-items: center; gap: 4px;
}
.empty-ico {
    width: 44px; height: 44px; border-radius: var(--r-lg);
    background: var(--accent-muted); border: 1px solid var(--accent-ring);
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 10px;
}
.empty-ico svg { width: 20px; height: 20px; color: var(--accent); }
.empty-title { font-size: var(--fs-sm); font-weight: 600; color: var(--text-1); }
.empty-sub { font-size: var(--fs-xs); color: var(--text-3); margin-bottom: 14px; }

/* ─── Panel footer ───────────────────────────── */
.tbl-footer {
    padding: 10px 16px;
    border-top: 1px solid var(--border);
    background: var(--bg);
    display: flex; align-items: center; justify-content: space-between;
}
.tbl-footer-txt { font-size: var(--fs-xs); color: var(--text-3); font-family: 'Geist Mono', monospace; }

/* ─── Buttons ────────────────────────────────── */
.btn-primary {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 16px;
    background: var(--accent); color: var(--text-inv);
    font-size: var(--fs-sm); font-weight: 500;
    font-family: 'Geist', sans-serif;
    border-radius: var(--r-sm); border: none; cursor: pointer;
    transition: background var(--dur-fast) ease, transform var(--dur-micro) ease;
    box-shadow: 0 1px 4px color-mix(in oklch, var(--accent), transparent 60%);
    white-space: nowrap;
}
.btn-primary svg { width: 13px; height: 13px; }
.btn-primary:hover { background: var(--accent-h); }
.btn-primary:active { transform: scale(0.97); }

.btn-ghost {
    display: inline-flex; align-items: center;
    padding: 8px 15px;
    background: transparent; border: 1px solid var(--border); color: var(--text-2);
    font-size: var(--fs-sm); font-weight: 500; font-family: 'Geist', sans-serif;
    border-radius: var(--r-sm); cursor: pointer;
    transition: background var(--dur-fast) ease;
}
.btn-ghost:hover { background: var(--bg-2); }

.btn-danger-solid {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 15px;
    background: var(--danger); color: var(--text-inv);
    font-size: var(--fs-sm); font-weight: 600; font-family: 'Geist', sans-serif;
    border-radius: var(--r-sm); border: none; cursor: pointer;
    box-shadow: 0 1px 3px color-mix(in oklch, var(--danger), transparent 58%);
    transition: filter var(--dur-fast) ease, transform var(--dur-micro) ease;
}
.btn-danger-solid:hover { filter: brightness(1.1); }
.btn-danger-solid:active { transform: scale(0.97); }

/* ─── Modal shared ───────────────────────────── */
.modal-wrap {
    position: fixed; inset: 0; z-index: 50;
    display: flex; align-items: center; justify-content: center;
    padding: 16px;
}
.modal-bd {
    position: absolute; inset: 0;
    background: oklch(10% 0.01 265 / 48%);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
}
.modal-inner {
    position: relative;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--r-xl);
    width: 100%; max-width: 420px;
    box-shadow: var(--shadow-lg);
    overflow: hidden;
}
.modal-hd {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 20px; border-bottom: 1px solid var(--border);
}
.modal-hd-left { display: flex; align-items: center; gap: 10px; }
.modal-hd-ico {
    width: 30px; height: 30px; border-radius: var(--r-sm);
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.modal-hd-ico svg { width: 14px; height: 14px; }
.modal-hd-ico.blue { background: var(--accent-muted); border: 1px solid var(--accent-ring); }
.modal-hd-ico.blue svg { color: var(--accent); }
.modal-hd-ico.amber { background: var(--warning-bg); border: 1px solid color-mix(in oklch, var(--warning), transparent 55%); }
.modal-hd-ico.amber svg { color: var(--warning); }
.modal-hd-ico.red { background: var(--danger-bg); border: 1px solid var(--danger-border); }
.modal-hd-ico.red svg { color: var(--danger); }

.modal-hd-title { font-size: var(--fs-sm); font-weight: 600; color: var(--text-1); }
.modal-hd-sub   { font-size: var(--fs-xs); color: var(--text-3); margin-top: 1px; }

.modal-cls {
    width: 28px; height: 28px; border-radius: var(--r-sm);
    border: 1px solid var(--border); background: transparent;
    color: var(--text-3); cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: background var(--dur-fast) ease;
    flex-shrink: 0;
}
.modal-cls:hover { background: var(--bg-2); color: var(--text-1); }
.modal-cls svg { width: 13px; height: 13px; }

.modal-body { padding: 18px 20px; display: flex; flex-direction: column; gap: 14px; }
.modal-ft   { padding: 0 20px 18px; display: flex; align-items: center; justify-content: flex-end; gap: 8px; }

/* ─── Form controls (modal) ──────────────────── */
.fld-lbl {
    display: flex; align-items: baseline; gap: 4px;
    font-size: var(--fs-xs); font-weight: 500;
    color: var(--text-2); margin-bottom: 5px;
    font-family: 'Geist', sans-serif;
}
.fld-lbl .req { color: var(--danger); }
.fld-hint { font-size: var(--fs-xs); color: var(--text-3); margin-top: 4px; }
.fld-hint strong { color: var(--text-2); font-weight: 500; }

.fc {
    width: 100%; padding: 8px 11px;
    background: var(--bg-2); border: 1px solid var(--border);
    border-radius: var(--r-sm);
    font-size: var(--fs-sm); font-family: 'Geist', sans-serif; color: var(--text-1);
    outline: none;
    transition: border-color var(--dur-fast) ease, box-shadow var(--dur-fast) ease;
}
.fc::placeholder { color: var(--text-3); }
.fc:focus {
    border-color: var(--accent-ring);
    box-shadow: 0 0 0 3px color-mix(in oklch, var(--accent), transparent 84%);
}
.fc-warn:focus {
    border-color: color-mix(in oklch, var(--warning), transparent 40%);
    box-shadow: 0 0 0 3px color-mix(in oklch, var(--warning), transparent 82%);
}
textarea.fc { resize: none; line-height: 1.6; }

/* Currency input prefix */
.curr-wrap { position: relative; }
.curr-prefix {
    position: absolute; left: 10px; top: 50%;
    transform: translateY(-50%);
    font-size: var(--fs-xs); font-weight: 600;
    color: var(--text-3);
    font-family: 'Geist Mono', monospace;
    pointer-events: none;
}
.curr-wrap .fc { padding-left: 30px; font-family: 'Geist Mono', monospace; }

/* ─── Live total (dalam modal add/edit) ──────── */
.live-total {
    background: var(--bg-2); border: 1px solid var(--border);
    border-radius: var(--r-sm); padding: 10px 14px;
    display: flex; align-items: center; justify-content: space-between;
}
.lt-lbl { font-size: var(--fs-xs); color: var(--text-3); font-family: 'Geist Mono', monospace; text-transform: uppercase; letter-spacing: .06em; }
.lt-val { font-size: var(--fs-sm); font-weight: 700; color: var(--text-1); font-variant-numeric: tabular-nums; font-family: 'Geist Mono', monospace; }

/* ─── Delete modal confirm ───────────────────── */
.del-confirm {
    padding: 22px 20px 8px; text-align: center;
    display: flex; flex-direction: column; align-items: center; gap: 6px;
}
.del-ico {
    width: 44px; height: 44px; border-radius: 50%;
    background: var(--danger-bg); border: 1px solid var(--danger-border);
    display: flex; align-items: center; justify-content: center; margin-bottom: 6px;
}
.del-ico svg { width: 20px; height: 20px; color: var(--danger); }
.del-title  { font-size: var(--fs-sm); font-weight: 700; color: var(--text-1); }
.del-desc   { font-size: var(--fs-xs); color: var(--text-3); line-height: 1.5; max-width: 280px; }
.del-desc strong { color: var(--text-2); font-weight: 600; }

/* ─── Entrance animation ─────────────────────── */
@media (prefers-reduced-motion: no-preference) {
    .au { animation: fadeUp var(--dur-page) var(--ease-out) both; }
    .d0 { animation-delay: 0ms; }
    .d1 { animation-delay: 45ms; }
    .d2 { animation-delay: 85ms; }
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(7px); }
        to   { opacity: 1; transform: translateY(0); }
    }
}
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto" x-data="tarifSpp()">

    {{-- ── Header ── --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 au d0" style="margin-bottom:20px">
        <div>
            <h2 style="font-size:var(--fs-xl);font-weight:700;color:var(--text-1);letter-spacing:-0.02em">
                Tarif SPP
            </h2>
            <p style="font-size:var(--fs-xs);color:var(--text-3);margin-top:3px">
                Kelola nominal SPP &amp; kebersihan per tahun ajaran
            </p>
        </div>
        <button class="btn-primary" @click="openAddModal()">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Tarif
        </button>
    </div>

    {{-- ── Alerts ── --}}
    <div class="au d0" style="margin-bottom:16px;display:flex;flex-direction:column;gap:8px">
        @if(session('success'))
        <div class="alert alert-success" x-data="{ show: true }" x-show="show"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 -translate-y-1">
            <svg class="alert-ico" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="alert-body">{{ session('success') }}</span>
            <button class="alert-close" @click="show = false">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        @endif
        @if($errors->any())
        <div class="alert alert-error">
            <svg class="alert-ico" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z"/>
            </svg>
            <ul class="alert-body" style="padding-left:14px;margin:0">
                @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
            </ul>
        </div>
        @endif
    </div>

    {{-- ── Summary strip ── --}}
    @if(!$tarifs->isEmpty())
    @php
        $tarifAktif  = $tarifs->where('tahun_berlaku', now()->year)->first();
        $tarifLatest = $tarifs->sortByDesc('tahun_berlaku')->first();
    @endphp
    <div class="summary-strip au d1">
        <div class="sitem">
            <span class="s-lbl">Total Tarif</span>
            <span class="s-val">{{ $tarifs->count() }} data</span>
        </div>
        <div class="sitem">
            <span class="s-lbl">Tahun Aktif</span>
            <span class="s-val">{{ $tarifAktif ? $tarifAktif->tahun_berlaku : '—' }}</span>
        </div>
        <div class="sitem">
            <span class="s-lbl">SPP + Kebersihan</span>
            <span class="s-val">
                @if($tarifAktif)
                    Rp {{ number_format($tarifAktif->nominal_spp + $tarifAktif->nominal_kebersihan, 0, ',', '.') }}
                @else
                    —
                @endif
            </span>
        </div>
        <div class="sitem">
            <span class="s-lbl">Tahun Terbaru</span>
            <span class="s-val">{{ $tarifLatest->tahun_berlaku }}</span>
        </div>
    </div>
    @endif

    {{-- ── Tabel panel ── --}}
    <div class="tbl-panel au d2">

        @if($tarifs->isEmpty())
        {{-- Empty state --}}
        <div class="empty-state">
            <div class="empty-ico">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                </svg>
            </div>
            <p class="empty-title">Belum ada tarif SPP</p>
            <p class="empty-sub">Tambahkan tarif pertama untuk memulai.</p>
            <button class="btn-primary" @click="openAddModal()">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Tarif
            </button>
        </div>

        @else
        {{-- Table --}}
        <div style="overflow-x:auto">
            <table class="tarif-tbl">
                <thead>
                    <tr>
                        <th style="width:36px">#</th>
                        <th>Tahun Berlaku</th>
                        <th class="r">SPP</th>
                        <th class="r">Kebersihan</th>
                        <th class="r">Total / Bulan</th>
                        <th class="hidden md:table-cell">Keterangan</th>
                        <th class="c">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tarifs->sortByDesc('tahun_berlaku') as $i => $tarif)
                    <tr>
                        <td style="color:var(--text-3);font-family:'Geist Mono',monospace;font-size:var(--fs-xs)">
                            {{ $i + 1 }}
                        </td>
                        <td>
                            <div class="tahun-wrap">
                                <span class="tahun-val">{{ $tarif->tahun_berlaku }}</span>
                                @if($tarif->tahun_berlaku == now()->year)
                                <span class="pill-aktif">Aktif</span>
                                @endif
                            </div>
                        </td>
                        <td class="cell-num">Rp {{ number_format($tarif->nominal_spp, 0, ',', '.') }}</td>
                        <td class="cell-num">Rp {{ number_format($tarif->nominal_kebersihan, 0, ',', '.') }}</td>
                        <td class="cell-total" style="color:var(--accent)">
                            Rp {{ number_format($tarif->nominal_spp + $tarif->nominal_kebersihan, 0, ',', '.') }}
                        </td>
                        <td class="cell-keterangan {{ $tarif->keterangan ? 'has-val' : '' }} hidden md:table-cell">
                            <span style="display:block;max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                                {{ $tarif->keterangan ?? '—' }}
                            </span>
                        </td>
                        <td>
                            <div class="acts">
                                <button type="button" class="act-btn edit"
                                        @click="openEditModal({{ $tarif->id }}, {{ $tarif->tahun_berlaku }}, {{ $tarif->nominal_spp }}, {{ $tarif->nominal_kebersihan }}, '{{ addslashes($tarif->keterangan ?? '') }}')"
                                        aria-label="Edit tarif {{ $tarif->tahun_berlaku }}">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                                    </svg>
                                    <span class="tt">Edit</span>
                                </button>
                                @if(Auth::user()->role === 'admin')
                                <button type="button" class="act-btn del"
                                        @click="confirmDelete({{ $tarif->id }}, {{ $tarif->tahun_berlaku }})"
                                        aria-label="Hapus tarif {{ $tarif->tahun_berlaku }}">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                    </svg>
                                    <span class="tt">Hapus</span>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="tbl-footer">
            <span class="tbl-footer-txt">{{ $tarifs->count() }} tarif terdaftar</span>
        </div>
        @endif

    </div>{{-- /tbl-panel --}}


    {{-- ════════════════════════════════════════
         MODAL: TAMBAH TARIF
    ════════════════════════════════════════ --}}
    <div x-show="showAddModal" x-cloak class="modal-wrap" role="dialog" aria-modal="true" aria-labelledby="modal-add-title">

        <div class="modal-bd"
             x-transition:enter="transition duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             @click="showAddModal = false"></div>

        <div class="modal-inner"
             x-transition:enter="transition duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-2" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">

            <div class="modal-hd">
                <div class="modal-hd-left">
                    <div class="modal-hd-ico blue">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div>
                        <div class="modal-hd-title" id="modal-add-title">Tambah Tarif Baru</div>
                        <div class="modal-hd-sub">Berlaku untuk satu tahun ajaran</div>
                    </div>
                </div>
                <button class="modal-cls" @click="showAddModal = false" aria-label="Tutup">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form action="{{ route('spp.tarif.store') }}" method="POST" @submit="calcTotal">
                @csrf
                <div class="modal-body">

                    {{-- Tahun berlaku --}}
                    <div>
                        <label class="fld-lbl" for="add-tahun">Tahun Berlaku <span class="req">*</span></label>
                        <input id="add-tahun" type="number" name="tahun_berlaku"
                               :value="tahunBaru" min="2020" max="2099"
                               placeholder="Contoh: 2025" required class="fc">
                        @if($tahunTerpakai)
                        <p class="fld-hint">Sudah ada: <strong>{{ implode(', ', $tahunTerpakai) }}</strong></p>
                        @endif
                    </div>

                    {{-- SPP --}}
                    <div>
                        <label class="fld-lbl" for="add-spp">Nominal SPP <span class="req">*</span></label>
                        <div class="curr-wrap">
                            <span class="curr-prefix">Rp</span>
                            <input id="add-spp" type="number" name="nominal_spp"
                                   min="0" placeholder="0" required
                                   class="fc" x-model.number="addSpp"
                                   @input="calcAddTotal()">
                        </div>
                    </div>

                    {{-- Kebersihan --}}
                    <div>
                        <label class="fld-lbl" for="add-bersih">Nominal Kebersihan <span class="req">*</span></label>
                        <div class="curr-wrap">
                            <span class="curr-prefix">Rp</span>
                            <input id="add-bersih" type="number" name="nominal_kebersihan"
                                   min="0" placeholder="0" required
                                   class="fc" x-model.number="addBersih"
                                   @input="calcAddTotal()">
                        </div>
                    </div>

                    {{-- Live total --}}
                    <div class="live-total">
                        <span class="lt-lbl">Total / bulan</span>
                        <span class="lt-val" x-text="'Rp ' + (addSpp + addBersih).toLocaleString('id-ID')">Rp 0</span>
                    </div>

                    {{-- Keterangan --}}
                    <div>
                        <label class="fld-lbl" for="add-ket">Keterangan <span style="color:var(--text-3);font-size:var(--fs-2xs);font-weight:400;font-family:'Geist Mono',monospace">opsional</span></label>
                        <textarea id="add-ket" name="keterangan" rows="2" maxlength="200"
                                  placeholder="Misal: Kenaikan tarif dari tahun sebelumnya..."
                                  class="fc"></textarea>
                    </div>

                </div>
                <div class="modal-ft">
                    <button type="button" class="btn-ghost" @click="showAddModal = false">Batal</button>
                    <button type="submit" class="btn-primary">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Tarif
                    </button>
                </div>
            </form>
        </div>
    </div>


    {{-- ════════════════════════════════════════
         MODAL: EDIT TARIF
    ════════════════════════════════════════ --}}
    <div x-show="showEditModal" x-cloak class="modal-wrap" role="dialog" aria-modal="true" aria-labelledby="modal-edit-title">

        <div class="modal-bd"
             x-transition:enter="transition duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             @click="showEditModal = false"></div>

        <div class="modal-inner"
             x-transition:enter="transition duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-2" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">

            <div class="modal-hd">
                <div class="modal-hd-left">
                    <div class="modal-hd-ico amber">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="modal-hd-title" id="modal-edit-title">Edit Tarif</div>
                        <div class="modal-hd-sub">Tahun <span x-text="editData.tahun" style="color:var(--text-2);font-weight:600"></span></div>
                    </div>
                </div>
                <button class="modal-cls" @click="showEditModal = false" aria-label="Tutup">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form :action="editFormAction" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">

                    {{-- SPP --}}
                    <div>
                        <label class="fld-lbl">Nominal SPP <span class="req">*</span></label>
                        <div class="curr-wrap">
                            <span class="curr-prefix">Rp</span>
                            <input type="number" name="nominal_spp"
                                   :value="editData.nominal_spp"
                                   min="0" required class="fc fc-warn"
                                   x-model.number="editData.nominal_spp">
                        </div>
                    </div>

                    {{-- Kebersihan --}}
                    <div>
                        <label class="fld-lbl">Nominal Kebersihan <span class="req">*</span></label>
                        <div class="curr-wrap">
                            <span class="curr-prefix">Rp</span>
                            <input type="number" name="nominal_kebersihan"
                                   :value="editData.nominal_kebersihan"
                                   min="0" required class="fc fc-warn"
                                   x-model.number="editData.nominal_kebersihan">
                        </div>
                    </div>

                    {{-- Live total --}}
                    <div class="live-total">
                        <span class="lt-lbl">Total / bulan</span>
                        <span class="lt-val" x-text="'Rp ' + (editData.nominal_spp + editData.nominal_kebersihan).toLocaleString('id-ID')">Rp 0</span>
                    </div>

                    {{-- Keterangan --}}
                    <div>
                        <label class="fld-lbl">Keterangan <span style="color:var(--text-3);font-size:var(--fs-2xs);font-weight:400;font-family:'Geist Mono',monospace">opsional</span></label>
                        <textarea name="keterangan" rows="2" maxlength="200"
                                  x-text="editData.keterangan"
                                  placeholder="Opsional..."
                                  class="fc"></textarea>
                    </div>

                </div>
                <div class="modal-ft">
                    <button type="button" class="btn-ghost" @click="showEditModal = false">Batal</button>
                    <button type="submit" class="btn-primary" style="background:var(--warning);box-shadow:0 1px 4px color-mix(in oklch, var(--warning), transparent 60%)"
                            onmouseenter="this.style.filter='brightness(1.1)'" onmouseleave="this.style.filter=''">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>


    {{-- ════════════════════════════════════════
         MODAL: KONFIRMASI HAPUS
    ════════════════════════════════════════ --}}
    <div x-show="showDeleteModal" x-cloak class="modal-wrap" role="dialog" aria-modal="true" aria-labelledby="modal-del-title">

        <div class="modal-bd"
             x-transition:enter="transition duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             @click="showDeleteModal = false"></div>

        <div class="modal-inner" style="max-width:360px"
             x-transition:enter="transition duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">

            <div class="del-confirm">
                <div class="del-ico">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z"/>
                    </svg>
                </div>
                <p class="del-title" id="modal-del-title">Hapus Tarif?</p>
                <p class="del-desc">
                    Tarif SPP tahun <strong x-text="deleteData.tahun"></strong> akan dihapus secara permanen.
                    Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>

            <div style="display:flex;border-top:1px solid var(--border)">
                <button type="button" @click="showDeleteModal = false"
                        style="flex:1;padding:14px;font-size:var(--fs-sm);font-weight:500;font-family:'Geist',sans-serif;color:var(--text-2);background:transparent;border:none;cursor:pointer;border-radius:0 0 0 var(--r-xl);transition:background var(--dur-fast) ease"
                        onmouseenter="this.style.background='var(--bg-2)'" onmouseleave="this.style.background='transparent'">
                    Batal
                </button>
                <div style="width:1px;background:var(--border)"></div>
                <form :action="deleteFormAction" method="POST" style="flex:1;display:contents">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            style="flex:1;padding:14px;font-size:var(--fs-sm);font-weight:600;font-family:'Geist',sans-serif;color:var(--danger);background:transparent;border:none;cursor:pointer;border-radius:0 0 var(--r-xl) 0;transition:background var(--dur-fast) ease;width:100%"
                            onmouseenter="this.style.background='var(--danger-bg)'" onmouseleave="this.style.background='transparent'">
                        Ya, Hapus
                    </button>
                </form>
            </div>

        </div>
    </div>

</div>
@endsection


@push('scripts')
<script>
function tarifSpp() {
    return {
        showAddModal:    false,
        showEditModal:   false,
        showDeleteModal: false,

        tahunBaru: {{ $tahunBaru }},

        /* ─ Add modal state ─ */
        addSpp:    0,
        addBersih: 0,

        /* ─ Edit modal state ─ */
        editData: {
            id: null, tahun: null,
            nominal_spp: 0, nominal_kebersihan: 0,
            keterangan: '',
        },
        editFormAction: '',

        /* ─ Delete modal state ─ */
        deleteData: { id: null, tahun: null },
        deleteFormAction: '',

        /* ─ Methods ─ */
        openAddModal() {
            this.addSpp    = 0;
            this.addBersih = 0;
            this.showAddModal = true;
        },

        openEditModal(id, tahun, nominalSpp, nominalKebersihan, keterangan) {
            this.editData = {
                id, tahun,
                nominal_spp: nominalSpp,
                nominal_kebersihan: nominalKebersihan,
                keterangan
            };
            this.editFormAction = `/spp/tarif/${id}`;
            this.showEditModal  = true;
        },

        confirmDelete(id, tahun) {
            this.deleteData       = { id, tahun };
            this.deleteFormAction = `/spp/tarif/${id}`;
            this.showDeleteModal  = true;
        },
    };
}

/* Escape key menutup modal aktif */
document.addEventListener('keydown', function (e) {
    if (e.key !== 'Escape') return;
    const comp = document.querySelector('[x-data]')?._x_dataStack?.[0];
    if (!comp) return;
    comp.showAddModal    = false;
    comp.showEditModal   = false;
    comp.showDeleteModal = false;
});
</script>
@endpush