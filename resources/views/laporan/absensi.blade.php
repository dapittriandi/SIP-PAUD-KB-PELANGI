{{-- SIMPAN SEBAGAI: resources/views/laporan/absensi.blade.php --}}
@extends('layouts.app')

@section('title', 'Laporan Absensi — PAUD KB Pelangi')
@section('page-title', 'Laporan Absensi')

@section('content')
<div class="ab-wrap">

    {{-- ══════════════════════════════════════════════
         FILTER BAR
         - setMode() hanya update UI + hidden input
         - Submit hanya terjadi saat klik "Tampilkan"
         - Cetak PDF adalah link terpisah
    ══════════════════════════════════════════════════ --}}
    <div class="ab-filter ab-card">
        <form method="GET" action="{{ route('absensi.laporan') }}" id="filter-form">
            <input type="hidden" name="mode" id="input-mode" value="{{ $mode ?? 'bulanan' }}">

            <div class="ab-filter-row">

                {{-- Mode toggle --}}
                <div class="ab-mode-tabs" role="group" aria-label="Mode laporan">
                    <button type="button" id="btn-bulanan" onclick="setMode('bulanan')"
                            class="ab-mode-tab" aria-pressed="{{ ($mode ?? 'bulanan') === 'bulanan' ? 'true' : 'false' }}">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        Bulanan
                    </button>
                    <button type="button" id="btn-tahunan" onclick="setMode('tahunan')"
                            class="ab-mode-tab" aria-pressed="{{ ($mode ?? 'bulanan') === 'tahunan' ? 'true' : 'false' }}">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 3v18h18"/><path d="M18.7 8l-5.1 5.2-2.8-2.7L7 14.3"/></svg>
                        Tahunan
                    </button>
                </div>

                {{-- Bulan --}}
                <div id="filter-bulan-wrap" class="ab-filter-field">
                    <label class="ab-filter-label" for="sel-bulan">Bulan</label>
                    <select id="sel-bulan" name="bulan" class="ab-select">
                        @foreach(range(1, 12) as $b)
                        <option value="{{ $b }}" {{ ($bulan ?? now()->month) == $b ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create(null, $b)->translatedFormat('F') }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tahun --}}
                <div class="ab-filter-field">
                    <label class="ab-filter-label" for="sel-tahun">Tahun</label>
                    <select id="sel-tahun" name="tahun" class="ab-select">
                        @foreach(range(now()->year - 3, now()->year) as $t)
                        <option value="{{ $t }}" {{ ($tahun ?? now()->year) == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Actions --}}
                <div class="ab-filter-actions">
                    <button type="submit" class="ab-btn-tampilkan">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                        Tampilkan
                    </button>
                    <a id="btn-cetak-pdf"
                       href="{{ route('absensi.cetak-laporan', ['bulan' => $bulan ?? now()->month, 'tahun' => $tahun ?? now()->year, 'mode' => $mode ?? 'bulanan']) }}"
                       target="_blank" rel="noopener"
                       class="ab-btn-cetak">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 17H7a2 2 0 01-2-2V5a2 2 0 012-2h7l5 5v10a2 2 0 01-2 2z"/><path d="M9 17v-5h6v5M9 9h1"/></svg>
                        PDF
                    </a>
                </div>

            </div>
        </form>
    </div>

    {{-- ══════════════════════════════════════════════
         VIEW BULANAN
    ══════════════════════════════════════════════════ --}}
    <div id="view-bulanan" class="{{ ($mode ?? 'bulanan') === 'tahunan' ? 'ab-hidden' : '' }}">

        {{-- Summary bar --}}
        <div class="ab-summary">
            <div class="ab-summary-text">
                <h2 class="ab-view-title">Rekap Absensi Tutor</h2>
                <p class="ab-view-sub">
                    {{ \Carbon\Carbon::create($tahun, $bulan)->translatedFormat('F Y') }}
                    <span class="ab-dot" aria-hidden="true">·</span>
                    {{ $hariKerja }} hari kerja efektif
                </p>
            </div>
            <div class="ab-summary-stats" role="group" aria-label="Ringkasan statistik">
                <div class="ab-sum-item">
                    <span class="ab-sum-num">{{ $ringkasan['total_guru'] }}</span>
                    <span class="ab-sum-lbl">Tutor</span>
                </div>
                <div class="ab-sum-div" aria-hidden="true"></div>
                <div class="ab-sum-item">
                    <span class="ab-sum-num ab-sum-green">{{ number_format($ringkasan['rata_hadir'], 1) }}</span>
                    <span class="ab-sum-lbl">Rata Hadir</span>
                </div>
                <div class="ab-sum-div" aria-hidden="true"></div>
                <div class="ab-sum-item">
                    <span class="ab-sum-num {{ $ringkasan['rata_persentase'] >= 90 ? 'ab-sum-green' : ($ringkasan['rata_persentase'] >= 75 ? 'ab-sum-amber' : 'ab-sum-red') }}">
                        {{ number_format($ringkasan['rata_persentase'], 1) }}%
                    </span>
                    <span class="ab-sum-lbl">Rata Hadir</span>
                </div>
            </div>
        </div>

        {{-- Tabel bulanan --}}
        <div class="ab-card ab-table-wrap">
            <div class="ab-scroll">
                <table class="ab-table" role="table" aria-label="Rekap absensi bulanan">
                    <thead>
                        <tr role="row">
                            <th class="ab-th ab-th-nama" scope="col">Nama Tutor</th>
                            <th class="ab-th ab-th-h"  scope="col" title="Hadir"><span class="ab-th-chip ab-chip-h">H</span></th>
                            <th class="ab-th ab-th-t"  scope="col" title="Terlambat"><span class="ab-th-chip ab-chip-t">T</span></th>
                            <th class="ab-th ab-th-i"  scope="col" title="Izin"><span class="ab-th-chip ab-chip-i">I</span></th>
                            <th class="ab-th ab-th-s"  scope="col" title="Sakit"><span class="ab-th-chip ab-chip-s">S</span></th>
                            <th class="ab-th ab-th-tl" scope="col" title="Tugas Luar"><span class="ab-th-chip ab-chip-tl">TL</span></th>
                            <th class="ab-th ab-th-a"  scope="col" title="Alpha"><span class="ab-th-chip ab-chip-a">A</span></th>
                            <th class="ab-th ab-th-pct" scope="col">% Hadir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rekap as $r)
                        @php
                            $pct = $r['persentase'];
                            $tier = $pct >= 90 ? 'high' : ($pct >= 75 ? 'mid' : 'low');
                        @endphp
                        <tr class="ab-row" role="row">
                            <td class="ab-td ab-td-nama" role="cell">
                                <div class="ab-guru-cell">
                                    <div class="ab-avatar ab-avatar-sm" aria-hidden="true">
                                        {{ strtoupper(substr($r['guru']->nama_lengkap, 0, 2)) }}
                                    </div>
                                    <div class="ab-guru-info">
                                        <span class="ab-guru-nama">{{ $r['guru']->nama_lengkap }}</span>
                                        @if($r['guru']->jabatan)
                                        <span class="ab-guru-jabatan">{{ $r['guru']->jabatan }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="ab-td ab-td-num" role="cell">
                                <span class="ab-num ab-num-h">{{ $r['hadir'] }}</span>
                            </td>
                            <td class="ab-td ab-td-num" role="cell">
                                <span class="ab-num {{ $r['terlambat'] > 0 ? 'ab-num-t' : 'ab-num-zero' }}">{{ $r['terlambat'] }}</span>
                            </td>
                            <td class="ab-td ab-td-num" role="cell">
                                <span class="ab-num {{ $r['izin'] > 0 ? 'ab-num-i' : 'ab-num-zero' }}">{{ $r['izin'] }}</span>
                            </td>
                            <td class="ab-td ab-td-num" role="cell">
                                <span class="ab-num {{ $r['sakit'] > 0 ? 'ab-num-s' : 'ab-num-zero' }}">{{ $r['sakit'] }}</span>
                            </td>
                            <td class="ab-td ab-td-num" role="cell">
                                <span class="ab-num {{ $r['tugas_luar'] > 0 ? 'ab-num-tl' : 'ab-num-zero' }}">{{ $r['tugas_luar'] }}</span>
                            </td>
                            <td class="ab-td ab-td-num" role="cell">
                                <span class="ab-num {{ $r['alpha'] > 0 ? 'ab-num-a' : 'ab-num-zero' }}">{{ $r['alpha'] }}</span>
                            </td>
                            <td class="ab-td ab-td-pct" role="cell">
                                <div class="ab-pct-wrap">
                                    <div class="ab-bar-track" role="progressbar"
                                         aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"
                                         aria-label="{{ $pct }}% kehadiran">
                                        <div class="ab-bar ab-bar-{{ $tier }}" style="width:{{ $pct }}%"></div>
                                    </div>
                                    <span class="ab-pct-val ab-pct-{{ $tier }}">{{ $pct }}%</span>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="ab-empty" role="cell">
                                <div class="ab-empty-inner">
                                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                    <p>Belum ada data absensi untuk periode ini.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if(isset($rekap) && $rekap->isNotEmpty())
                    <tfoot>
                        <tr class="ab-tfoot-row" role="row">
                            <td class="ab-td ab-tfoot-label" role="cell">Jumlah</td>
                            <td class="ab-td ab-td-num" role="cell"><span class="ab-num ab-num-h ab-tfoot-num">{{ $rekap->sum('hadir') }}</span></td>
                            <td class="ab-td ab-td-num" role="cell"><span class="ab-num ab-num-t ab-tfoot-num">{{ $rekap->sum('terlambat') }}</span></td>
                            <td class="ab-td ab-td-num" role="cell"><span class="ab-num ab-num-i ab-tfoot-num">{{ $rekap->sum('izin') }}</span></td>
                            <td class="ab-td ab-td-num" role="cell"><span class="ab-num ab-num-s ab-tfoot-num">{{ $rekap->sum('sakit') }}</span></td>
                            <td class="ab-td ab-td-num" role="cell"><span class="ab-num ab-num-tl ab-tfoot-num">{{ $rekap->sum('tugas_luar') }}</span></td>
                            <td class="ab-td ab-td-num" role="cell"><span class="ab-num ab-num-a ab-tfoot-num">{{ $rekap->sum('alpha') }}</span></td>
                            <td class="ab-td ab-td-pct" role="cell">
                                @php $rataFoot = $ringkasan['rata_persentase']; $tierFoot = $rataFoot >= 90 ? 'high' : ($rataFoot >= 75 ? 'mid' : 'low'); @endphp
                                <span class="ab-pct-val ab-pct-{{ $tierFoot }} ab-tfoot-num">{{ number_format($rataFoot, 1) }}%</span>
                            </td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>{{-- /view-bulanan --}}

    {{-- ══════════════════════════════════════════════
         VIEW TAHUNAN
    ══════════════════════════════════════════════════ --}}
    <div id="view-tahunan" class="{{ ($mode ?? 'bulanan') === 'tahunan' ? '' : 'ab-hidden' }}">

        {{-- Summary bar --}}
        <div class="ab-summary">
            <div class="ab-summary-text">
                <h2 class="ab-view-title">Rekap Absensi Tahunan</h2>
                <p class="ab-view-sub">
                    Tahun {{ $tahun }}
                    <span class="ab-dot" aria-hidden="true">·</span>
                    Persentase kehadiran per bulan
                </p>
            </div>
            <div class="ab-summary-stats" role="group" aria-label="Ringkasan statistik tahunan">
                <div class="ab-sum-item">
                    <span class="ab-sum-num">{{ $ringkasan['total_guru'] }}</span>
                    <span class="ab-sum-lbl">Tutor</span>
                </div>
                <div class="ab-sum-div" aria-hidden="true"></div>
                <div class="ab-sum-item">
                    <span class="ab-sum-num {{ $ringkasan['rata_persentase'] >= 90 ? 'ab-sum-green' : ($ringkasan['rata_persentase'] >= 75 ? 'ab-sum-amber' : 'ab-sum-red') }}">
                        {{ number_format($ringkasan['rata_persentase'], 1) }}%
                    </span>
                    <span class="ab-sum-lbl">Rata Hadir</span>
                </div>
            </div>
        </div>

        {{-- Tabel tahunan --}}
        <div class="ab-card ab-table-wrap">
            <div class="ab-scroll">
                <table class="ab-table" role="table" aria-label="Rekap absensi tahunan">
                    <thead>
                        <tr role="row">
                            <th class="ab-th ab-th-nama ab-th-sticky" scope="col">Nama Tutor</th>
                            @foreach(['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'] as $bl)
                            <th class="ab-th ab-th-bulan" scope="col">{{ $bl }}</th>
                            @endforeach
                            <th class="ab-th ab-th-pct" scope="col">Rata %</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rekapTahunan as $rt)
                        @php
                            $avgPct = $rt['rata_persentase'];
                            $tierAvg = $avgPct >= 90 ? 'high' : ($avgPct >= 75 ? 'mid' : 'low');
                        @endphp
                        <tr class="ab-row" role="row">
                            <td class="ab-td ab-td-nama ab-td-sticky" role="cell">
                                <div class="ab-guru-cell">
                                    <div class="ab-avatar ab-avatar-sm" aria-hidden="true">
                                        {{ strtoupper(substr($rt['guru']->nama_lengkap, 0, 2)) }}
                                    </div>
                                    <div class="ab-guru-info">
                                        <span class="ab-guru-nama">{{ $rt['guru']->nama_lengkap }}</span>
                                        @if($rt['guru']->jabatan)
                                        <span class="ab-guru-jabatan">{{ $rt['guru']->jabatan }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            @foreach($rt['per_bulan'] as $pb)
                            @php
                                $p = $pb['persentase'];
                                $tierPb = $p >= 90 ? 'high' : ($p >= 75 ? 'mid' : ($p > 0 ? 'low' : 'none'));
                            @endphp
                            <td class="ab-td ab-td-bulan" role="cell">
                                @if($tierPb !== 'none')
                                <span class="ab-bulan-chip ab-bulan-{{ $tierPb }}">{{ $p }}%</span>
                                @else
                                <span class="ab-num-zero ab-bulan-empty">—</span>
                                @endif
                            </td>
                            @endforeach
                            <td class="ab-td ab-td-pct" role="cell">
                                <span class="ab-pct-val ab-pct-{{ $tierAvg }} ab-tfoot-num">
                                    {{ number_format($avgPct, 1) }}%
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="14" class="ab-empty" role="cell">
                                <div class="ab-empty-inner">
                                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 3v18h18"/><path d="M18.7 8l-5.1 5.2-2.8-2.7L7 14.3"/></svg>
                                    <p>Belum ada data absensi untuk tahun ini.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>{{-- /view-tahunan --}}

    {{-- Legenda --}}
    <div class="ab-legenda" role="note" aria-label="Keterangan singkatan">
        <div class="ab-legenda-codes">
            <span class="ab-leg-item"><span class="ab-num ab-num-h ab-leg-code">H</span> Hadir</span>
            <span class="ab-leg-item"><span class="ab-num ab-num-t ab-leg-code">T</span> Terlambat</span>
            <span class="ab-leg-item"><span class="ab-num ab-num-i ab-leg-code">I</span> Izin</span>
            <span class="ab-leg-item"><span class="ab-num ab-num-s ab-leg-code">S</span> Sakit</span>
            <span class="ab-leg-item"><span class="ab-num ab-num-tl ab-leg-code">TL</span> Tugas Luar</span>
            <span class="ab-leg-item"><span class="ab-num ab-num-a ab-leg-code">A</span> Alpha</span>
        </div>
        <div class="ab-legenda-tiers">
            <span class="ab-leg-tier"><span class="ab-tier-dot ab-tier-high" aria-hidden="true"></span>≥ 90%</span>
            <span class="ab-leg-tier"><span class="ab-tier-dot ab-tier-mid"  aria-hidden="true"></span>75–89%</span>
            <span class="ab-leg-tier"><span class="ab-tier-dot ab-tier-low"  aria-hidden="true"></span>&lt; 75%</span>
        </div>
    </div>

</div>

<style>
/* ============================================================
   LAPORAN ABSENSI — Impeccable craft
   SIP-Pelangi design system · OKLCH · Geist · .dark Alpine
   Scene: Kepala sekolah scan absensi pagi di laptop
   Color strategy: Restrained + semantic (hadir/alpha/izin)
   ============================================================ */

.ab-wrap {
    /* Semantic palette — selaras app.blade.php tokens */
    --ab-hadir:      oklch(50%  0.162 148);   /* green  */
    --ab-hadir-soft: oklch(95%  0.038 148);
    --ab-hadir-ring: oklch(72%  0.095 148);

    --ab-terlambat:  oklch(60%  0.160 75);    /* amber */
    --ab-trlbt-soft: oklch(96%  0.040 80);
    --ab-trlbt-ring: oklch(76%  0.090 80);

    --ab-izin:       oklch(52%  0.180 258);   /* blue-accent */
    --ab-izin-soft:  oklch(95%  0.030 258);
    --ab-izin-ring:  oklch(72%  0.080 258);

    --ab-sakit:      oklch(54%  0.165 260);   /* indigo */
    --ab-sakit-soft: oklch(95%  0.028 260);

    --ab-tugas:      oklch(52%  0.170 292);   /* violet */
    --ab-tugas-soft: oklch(95%  0.028 292);

    --ab-alpha:      oklch(50%  0.210 27);    /* red */
    --ab-alpha-soft: oklch(96%  0.038 27);
    --ab-alpha-ring: oklch(72%  0.095 27);

    /* Glass tokens */
    --ab-glass-bg:      oklch(99.5% 0.003 250 / 74%);
    --ab-glass-border:  oklch(90%   0.007 250 / 65%);
    --ab-glass-border-a:oklch(52%   0.190 260 / 14%);
    --ab-glass-blur:    blur(20px) saturate(1.6);
    --ab-glass-shadow:  0 2px 14px oklch(52% 0.190 260 / 6%), 0 1px 3px oklch(0% 0 0 / 4%);

    --ab-row-hover:   oklch(97%  0.008 255 / 70%);
    --ab-row-divider: oklch(92%  0.006 255 / 60%);
    --ab-tfoot-bg:    oklch(97%  0.010 260 / 70%);

    display: flex;
    flex-direction: column;
    gap: 16px;
    font-family: 'Geist', system-ui, sans-serif;
    font-size: var(--fs-base, 14px);
}

.dark .ab-wrap {
    --ab-hadir-soft: oklch(20%  0.060 148);
    --ab-hadir-ring: oklch(35%  0.090 148);
    --ab-trlbt-soft: oklch(20%  0.055 80);
    --ab-trlbt-ring: oklch(35%  0.080 80);
    --ab-izin-soft:  oklch(18%  0.040 258);
    --ab-izin-ring:  oklch(34%  0.070 258);
    --ab-sakit-soft: oklch(18%  0.035 260);
    --ab-tugas-soft: oklch(18%  0.038 292);
    --ab-alpha-soft: oklch(20%  0.060 27);
    --ab-alpha-ring: oklch(35%  0.095 27);

    --ab-glass-bg:      oklch(15.5% 0.012 255 / 82%);
    --ab-glass-border:  oklch(28%   0.010 255 / 55%);
    --ab-glass-border-a:oklch(63%   0.185 260 / 22%);
    --ab-glass-shadow:  0 4px 24px oklch(0% 0 0 / 42%), 0 1px 4px oklch(0% 0 0 / 28%);
    --ab-row-hover:     oklch(20%   0.018 260 / 60%);
    --ab-row-divider:   oklch(28%   0.010 255 / 45%);
    --ab-tfoot-bg:      oklch(18%   0.018 260 / 70%);
}

.ab-hidden { display: none !important; }

/* ── Card shell ── */
.ab-card {
    background: var(--ab-glass-bg);
    border: 1px solid var(--ab-glass-border);
    border-radius: var(--r-lg, 14px);
    box-shadow: var(--ab-glass-shadow);
    backdrop-filter: var(--ab-glass-blur);
    -webkit-backdrop-filter: var(--ab-glass-blur);
    overflow: hidden;
    transition:
        box-shadow var(--dur-mid) var(--ease-out),
        border-color var(--dur-mid) var(--ease-out);
}

/* ══════════════════════════════
   FILTER BAR
══════════════════════════════ */
.ab-filter { padding: 14px 16px; }
.ab-filter-row {
    display: flex;
    align-items: flex-end;
    flex-wrap: wrap;
    gap: 10px;
}

/* Mode tabs */
.ab-mode-tabs {
    display: flex;
    gap: 2px;
    background: oklch(52% 0.190 260 / 6%);
    border: 1px solid var(--ab-glass-border);
    border-radius: var(--r, 10px);
    padding: 3px;
    flex-shrink: 0;
}
.dark .ab-mode-tabs {
    background: oklch(63% 0.185 260 / 8%);
}
.ab-mode-tab {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 6px 14px;
    font-size: var(--fs-xs, 11px); font-weight: 600;
    color: var(--text-2);
    border-radius: calc(var(--r, 10px) - 3px);
    border: none; background: none; cursor: pointer;
    font-family: inherit;
    transition:
        background var(--dur-fast) var(--ease-out),
        color      var(--dur-fast) var(--ease-out),
        box-shadow var(--dur-mid)  var(--ease-out);
    white-space: nowrap;
}
.ab-mode-tab:hover { color: var(--text-1); }
.ab-mode-tab.active {
    background: var(--ab-glass-bg);
    color: var(--accent);
    font-weight: 700;
    box-shadow: 0 1px 5px oklch(52% 0.190 260 / 14%);
    border: 1px solid var(--ab-glass-border-a);
}
.dark .ab-mode-tab.active { background: oklch(20% 0.015 255); }

/* Filter fields */
.ab-filter-field { display: flex; flex-direction: column; gap: 4px; }
.ab-filter-label {
    font-size: 9px; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.08em;
    color: var(--text-3);
}
.ab-select {
    padding: 7px 30px 7px 11px;
    background: oklch(99% 0.002 250 / 82%);
    border: 1.5px solid var(--ab-glass-border);
    border-radius: var(--r, 10px);
    font-size: var(--fs-sm, 13px);
    color: var(--text-1);
    font-family: inherit;
    outline: none;
    cursor: pointer;
    -webkit-appearance: none;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 10px center;
    transition:
        border-color var(--dur-fast) var(--ease-out),
        box-shadow   var(--dur-mid)  var(--ease-out);
}
.ab-select:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px oklch(52% 0.190 260 / 12%);
    background-color: oklch(99.5% 0.002 250 / 96%);
}
.dark .ab-select {
    background-color: oklch(18% 0.013 255 / 70%);
    border-color: oklch(30% 0.010 255 / 70%);
}
.dark .ab-select:focus {
    background-color: oklch(21% 0.015 255 / 90%);
    border-color: var(--accent);
}

.ab-filter-actions {
    display: flex; gap: 8px; align-items: flex-end;
    margin-left: auto;
}

.ab-btn-tampilkan {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 18px;
    background: var(--accent); color: var(--text-inv);
    font-size: var(--fs-sm, 13px); font-weight: 700;
    border: none; border-radius: var(--r, 10px);
    cursor: pointer; font-family: inherit;
    transition:
        background var(--dur-fast) var(--ease-out),
        transform  var(--dur-fast) var(--ease-out),
        box-shadow var(--dur-mid)  var(--ease-out);
    box-shadow: 0 2px 10px oklch(52% 0.190 260 / 28%);
    white-space: nowrap;
}
.ab-btn-tampilkan:hover {
    background: var(--accent-h);
    transform: translateY(-1px);
    box-shadow: 0 5px 18px oklch(52% 0.190 260 / 40%);
}

.ab-btn-cetak {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 14px;
    background: oklch(50% 0.210 27 / 8%);
    border: 1.5px solid oklch(50% 0.210 27 / 22%);
    color: oklch(46% 0.210 27);
    font-size: var(--fs-sm, 13px); font-weight: 600;
    border-radius: var(--r, 10px);
    text-decoration: none;
    transition:
        background   var(--dur-fast) var(--ease-out),
        border-color var(--dur-fast) var(--ease-out),
        transform    var(--dur-fast) var(--ease-out);
    white-space: nowrap;
}
.ab-btn-cetak:hover {
    background: oklch(50% 0.210 27 / 14%);
    border-color: oklch(50% 0.210 27 / 36%);
    transform: translateY(-1px);
}
.dark .ab-btn-cetak {
    color: oklch(65% 0.200 27);
    background: oklch(50% 0.210 27 / 10%);
    border-color: oklch(50% 0.210 27 / 28%);
}

/* ══════════════════════════════
   SUMMARY BAR
══════════════════════════════ */
.ab-summary {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
}
.ab-view-title {
    font-size: var(--fs-lg, 18px);
    font-weight: 800;
    color: var(--text-1);
    letter-spacing: -0.03em;
    margin: 0 0 3px;
    line-height: 1.2;
}
.ab-view-sub {
    font-size: var(--fs-sm, 13px);
    color: var(--text-2);
    margin: 0;
}
.ab-dot { color: var(--text-3); margin: 0 3px; }

.ab-summary-stats {
    display: flex;
    align-items: center;
    gap: 0;
    background: oklch(52% 0.190 260 / 4%);
    border: 1px solid var(--ab-glass-border-a);
    border-radius: var(--r, 10px);
    padding: 8px 4px;
    flex-shrink: 0;
}
.dark .ab-summary-stats { background: oklch(63% 0.185 260 / 6%); }

.ab-sum-item {
    display: flex; flex-direction: column;
    align-items: center; gap: 2px;
    padding: 2px 14px;
}
.ab-sum-num {
    font-size: var(--fs-lg, 18px);
    font-weight: 800; line-height: 1;
    letter-spacing: -0.03em;
    color: var(--text-1);
}
.ab-sum-green { color: var(--ab-hadir); }
.ab-sum-amber { color: var(--ab-terlambat); }
.ab-sum-red   { color: var(--ab-alpha); }
.ab-sum-lbl {
    font-size: 9px; font-weight: 600;
    color: var(--text-3);
    text-transform: uppercase; letter-spacing: 0.04em;
    white-space: nowrap;
}
.ab-sum-div {
    width: 1px; height: 28px;
    background: var(--ab-glass-border);
    flex-shrink: 0;
}

/* ══════════════════════════════
   TABLE
══════════════════════════════ */
.ab-table-wrap { }
.ab-scroll { overflow-x: auto; -webkit-overflow-scrolling: touch; }

.ab-table {
    width: 100%;
    border-collapse: collapse;
}

/* TH */
.ab-th {
    padding: 9px 10px;
    font-size: 9px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    color: var(--text-3);
    background: oklch(52% 0.190 260 / 3.5%);
    border-bottom: 1px solid var(--ab-row-divider);
    white-space: nowrap;
}
.dark .ab-th { background: oklch(63% 0.185 260 / 5%); }
.ab-th-nama  { text-align: left; padding-left: 16px; min-width: 200px; }
.ab-th-bulan { text-align: center; min-width: 52px; }
.ab-th-pct   { text-align: right; padding-right: 16px; min-width: 72px; }
.ab-th-sticky {
    position: sticky; left: 0; z-index: 2;
    background: oklch(97% 0.008 255 / 95%);
}
.dark .ab-th-sticky { background: oklch(17% 0.013 255 / 95%); }

/* Status column headers */
.ab-th-h, .ab-th-t, .ab-th-i, .ab-th-s, .ab-th-tl, .ab-th-a {
    text-align: center;
    padding: 9px 8px;
    min-width: 40px;
}

.ab-th-chip {
    display: inline-flex; align-items: center; justify-content: center;
    width: 22px; height: 22px;
    border-radius: 6px;
    font-size: 9px; font-weight: 800;
    letter-spacing: 0.02em;
}
.ab-chip-h  { background: var(--ab-hadir-soft); color: var(--ab-hadir);      border: 1px solid var(--ab-hadir-ring); }
.ab-chip-t  { background: var(--ab-trlbt-soft); color: var(--ab-terlambat);  border: 1px solid var(--ab-trlbt-ring); }
.ab-chip-i  { background: var(--ab-izin-soft);  color: var(--ab-izin);       border: 1px solid var(--ab-izin-ring); }
.ab-chip-s  { background: var(--ab-sakit-soft); color: var(--ab-sakit);      border: 1px solid oklch(70% 0.080 260); }
.ab-chip-tl { background: var(--ab-tugas-soft); color: var(--ab-tugas);      border: 1px solid oklch(70% 0.075 292); }
.ab-chip-a  { background: var(--ab-alpha-soft); color: var(--ab-alpha);      border: 1px solid var(--ab-alpha-ring); }

/* Row */
.ab-row {
    transition: background var(--dur-fast) var(--ease-out);
    border-bottom: 1px solid var(--ab-row-divider);
}
.ab-row:last-child { border-bottom: none; }
.ab-row:hover { background: var(--ab-row-hover); }

.ab-td { padding: 9px 10px; vertical-align: middle; }
.ab-td-nama { text-align: left; padding-left: 16px; }
.ab-td-num  { text-align: center; }
.ab-td-pct  { text-align: right; padding-right: 16px; min-width: 100px; }
.ab-td-bulan { text-align: center; padding: 9px 4px; }
.ab-td-sticky {
    position: sticky; left: 0; z-index: 1;
    background: var(--ab-glass-bg);
}
.ab-row:hover .ab-td-sticky { background: var(--ab-row-hover); }

/* Guru cell */
.ab-guru-cell {
    display: flex; align-items: center; gap: 10px; min-width: 0;
}
.ab-avatar {
    border-radius: var(--r, 10px);
    flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700;
    color: oklch(99% 0.003 250);
    background: linear-gradient(135deg, var(--accent) 0%, oklch(60% 0.175 255) 100%);
}
.ab-avatar-sm { width: 32px; height: 32px; font-size: 9px; letter-spacing: 0.02em; }
.ab-guru-info {
    display: flex; flex-direction: column; gap: 1px; min-width: 0;
}
.ab-guru-nama {
    font-size: var(--fs-sm, 13px); font-weight: 600;
    color: var(--text-1);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.ab-guru-jabatan {
    font-size: 9px; color: var(--text-3);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    font-weight: 500;
}

/* Number values */
.ab-num {
    font-size: var(--fs-sm, 13px);
    font-weight: 700;
    font-family: 'Geist Mono', monospace;
    letter-spacing: 0.01em;
}
.ab-num-h   { color: var(--ab-hadir); }
.ab-num-t   { color: var(--ab-terlambat); }
.ab-num-i   { color: var(--ab-izin); }
.ab-num-s   { color: var(--ab-sakit); }
.ab-num-tl  { color: var(--ab-tugas); }
.ab-num-a   { color: var(--ab-alpha); }
.ab-num-zero {
    color: var(--text-3);
    font-weight: 400;
    font-size: var(--fs-xs, 11px);
    opacity: 0.45;
}

/* Percentage + bar */
.ab-pct-wrap {
    display: flex; align-items: center; gap: 7px; justify-content: flex-end;
}
.ab-bar-track {
    flex: 1;
    height: 5px;
    border-radius: 10px;
    background: oklch(52% 0.190 260 / 7%);
    overflow: hidden;
    min-width: 40px;
}
.dark .ab-bar-track { background: oklch(63% 0.185 260 / 10%); }
.ab-bar {
    height: 100%; border-radius: 10px;
    transition: width 0.6s cubic-bezier(.22,.61,.36,1);
    min-width: 3px;
}
.ab-bar-high { background: var(--ab-hadir); }
.ab-bar-mid  { background: var(--ab-terlambat); }
.ab-bar-low  { background: var(--ab-alpha); }

.ab-pct-val {
    font-size: var(--fs-xs, 11px); font-weight: 700;
    font-family: 'Geist Mono', monospace;
    min-width: 36px; text-align: right;
}
.ab-pct-high { color: var(--ab-hadir); }
.ab-pct-mid  { color: var(--ab-terlambat); }
.ab-pct-low  { color: var(--ab-alpha); }

/* Tahunan bulan chips */
.ab-bulan-chip {
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 9px; font-weight: 700;
    font-family: 'Geist Mono', monospace;
    padding: 2px 5px;
    border-radius: 5px;
    white-space: nowrap;
}
.ab-bulan-high {
    background: var(--ab-hadir-soft);
    color: var(--ab-hadir);
    border: 1px solid var(--ab-hadir-ring);
}
.ab-bulan-mid {
    background: var(--ab-trlbt-soft);
    color: var(--ab-terlambat);
    border: 1px solid var(--ab-trlbt-ring);
}
.ab-bulan-low {
    background: var(--ab-alpha-soft);
    color: var(--ab-alpha);
    border: 1px solid var(--ab-alpha-ring);
}
.ab-bulan-empty {
    font-size: 9px;
}

/* Tfoot */
.ab-tfoot-row {
    border-top: 2px solid oklch(52% 0.190 260 / 15%);
    background: var(--ab-tfoot-bg);
}
.dark .ab-tfoot-row { border-top-color: oklch(63% 0.185 260 / 20%); }
.ab-tfoot-label {
    font-size: 9px; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.08em;
    color: var(--text-3);
    padding-left: 16px;
}
.ab-tfoot-num {
    font-size: var(--fs-sm, 13px) !important;
    font-weight: 800 !important;
}

/* Empty state */
.ab-empty { padding: 0; }
.ab-empty-inner {
    padding: 48px 24px;
    display: flex; flex-direction: column;
    align-items: center; gap: 10px;
    color: var(--text-3);
    text-align: center;
}
.ab-empty-inner svg { opacity: 0.4; }
.ab-empty-inner p { font-size: var(--fs-sm, 13px); font-weight: 500; margin: 0; }

/* ══════════════════════════════
   LEGENDA
══════════════════════════════ */
.ab-legenda {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
    padding: 2px 0;
}
.ab-legenda-codes {
    display: flex; flex-wrap: wrap; gap: 12px;
}
.ab-leg-item {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 9px; font-weight: 500; color: var(--text-3);
}
.ab-leg-code {
    font-size: 9px !important;
    font-weight: 800 !important;
}
.ab-legenda-tiers {
    display: flex; align-items: center; gap: 12px; flex-shrink: 0;
}
.ab-leg-tier {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 9px; font-weight: 500; color: var(--text-3);
}
.ab-tier-dot {
    width: 10px; height: 4px; border-radius: 10px; flex-shrink: 0;
}
.ab-tier-high { background: var(--ab-hadir); }
.ab-tier-mid  { background: var(--ab-terlambat); }
.ab-tier-low  { background: var(--ab-alpha); }

/* ── Responsive ── */
@media (max-width: 640px) {
    .ab-filter-row    { gap: 8px; }
    .ab-filter-actions { margin-left: 0; }
    .ab-summary       { flex-direction: column; }
    .ab-summary-stats { width: 100%; justify-content: flex-start; }
    .ab-legenda       { flex-direction: column; align-items: flex-start; gap: 8px; }
    .ab-view-title    { font-size: var(--fs-md, 15px); }
}

/* ── Reduced motion ── */
@media (prefers-reduced-motion: reduce) {
    .ab-bar { transition: none; }
    .ab-mode-tab, .ab-btn-tampilkan, .ab-btn-cetak,
    .ab-row, .ab-select { transition: none; }
}
</style>

<script>
function setMode(mode) {
    document.getElementById('input-mode').value = mode;

    const isBulanan = mode === 'bulanan';

    // Update tab active state + aria-pressed
    ['bulanan','tahunan'].forEach(m => {
        const btn = document.getElementById('btn-' + m);
        const isActive = m === mode;
        btn.classList.toggle('active', isActive);
        btn.setAttribute('aria-pressed', isActive ? 'true' : 'false');
    });

    // Toggle filter bulan visibility
    document.getElementById('filter-bulan-wrap').classList.toggle('ab-hidden', !isBulanan);

    // Toggle table views
    document.getElementById('view-bulanan').classList.toggle('ab-hidden', !isBulanan);
    document.getElementById('view-tahunan').classList.toggle('ab-hidden', isBulanan);

    // Update href tombol cetak PDF
    updateCetakPdfHref(mode);
}

function updateCetakPdfHref(mode) {
    const btn = document.getElementById('btn-cetak-pdf');
    if (!btn) return;
    const url = new URL(btn.href, window.location.origin);
    url.searchParams.set('mode', mode);
    btn.href = url.toString();
}

document.addEventListener('DOMContentLoaded', function () {
    const mode = document.getElementById('input-mode').value || 'bulanan';
    setMode(mode);
});
</script>
@endsection