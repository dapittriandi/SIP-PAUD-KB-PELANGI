@extends('layouts.app')

@section('title', 'Data Siswa — PAUD KB Pelangi')
@section('page-title', 'Data Siswa')

@push('styles')
<style>
    /* ── Glassmorphism tokens ── */
    :root {
        --blue:       #3B82F6;
        --blue-d:     #1D4ED8;
        --blue-l:     rgba(59,130,246,0.08);
        --surface:    rgba(255,255,255,0.72);
        --surface2:   rgba(255,255,255,0.50);
        --border:     rgba(59,130,246,0.13);
        --border2:    rgba(0,0,0,0.07);
        --t1:         #0F172A;
        --t2:         #475569;
        --t3:         #94A3B8;
        --sh:         0 2px 16px rgba(59,130,246,0.07);
        --radius-sm:  8px;
        --radius-md:  12px;
        --radius-lg:  16px;
    }
    @media (prefers-color-scheme: dark) {
        :root {
            --surface:  rgba(15,23,42,0.78);
            --surface2: rgba(15,23,42,0.55);
            --border:   rgba(59,130,246,0.18);
            --border2:  rgba(255,255,255,0.06);
            --t1:       #F1F5F9;
            --t2:       #94A3B8;
            --t3:       #475569;
            --sh:       0 2px 16px rgba(0,0,0,0.40);
        }
    }

    /* ── Stat bar ── */
    .stat-bar { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 0; }
    @media (max-width: 640px) { .stat-bar { grid-template-columns: repeat(2, 1fr); } }
    .stat-card {
        background: var(--surface);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        border: 0.5px solid var(--border);
        border-radius: var(--radius-md);
        padding: 14px 16px;
        box-shadow: var(--sh);
    }
    .stat-card .lbl { font-size: 10px; color: var(--t3); letter-spacing: .06em; text-transform: uppercase; margin-bottom: 5px; }
    .stat-card .val { font-size: 22px; font-weight: 500; color: var(--t1); line-height: 1; }
    .stat-card .sub { font-size: 10px; color: var(--t3); margin-top: 3px; }

    /* ── Panel (filter + tabel) ── */
    .glass-panel {
        background: var(--surface);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        border: 0.5px solid var(--border);
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: var(--sh);
    }

    /* ── Filter row ── */
    .filter-row {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
        padding: 14px 16px;
        border-bottom: 0.5px solid var(--border2);
    }
    .srch-wrap { position: relative; flex: 1; min-width: 180px; }
    .srch-wrap svg { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); width: 15px; height: 15px; color: var(--t3); pointer-events: none; }
    .srch-wrap input {
        width: 100%;
        padding: 8px 12px 8px 32px;
        background: var(--surface2);
        border: 0.5px solid var(--border);
        border-radius: var(--radius-sm);
        font-size: 13px;
        color: var(--t1);
        outline: none;
        transition: border-color .15s;
    }
    .srch-wrap input::placeholder { color: var(--t3); }
    .srch-wrap input:focus { border-color: var(--blue); }
    .flt-select {
        padding: 8px 12px;
        background: var(--surface2);
        border: 0.5px solid var(--border);
        border-radius: var(--radius-sm);
        font-size: 13px;
        color: var(--t1);
        outline: none;
        cursor: pointer;
    }
    .btn-cari {
        padding: 8px 16px;
        background: var(--t1);
        color: var(--surface);
        font-size: 13px;
        font-weight: 500;
        border-radius: var(--radius-sm);
        border: none;
        cursor: pointer;
        transition: opacity .15s;
    }
    .btn-cari:hover { opacity: .80; }
    .btn-reset {
        padding: 8px 14px;
        background: transparent;
        border: 0.5px solid var(--border);
        border-radius: var(--radius-sm);
        font-size: 13px;
        color: var(--t2);
        cursor: pointer;
        transition: background .15s;
    }
    .btn-reset:hover { background: var(--blue-l); }

    /* ── View toggle ── */
    .view-toggle {
        display: flex;
        background: var(--surface2);
        border: 0.5px solid var(--border);
        border-radius: var(--radius-sm);
        padding: 3px;
        gap: 2px;
        margin-left: auto;
    }
    .tgl-btn {
        display: flex;
        align-items: center;
        gap: 5px;
        padding: 5px 12px;
        border: none;
        border-radius: 6px;
        font-size: 12px;
        cursor: pointer;
        background: transparent;
        color: var(--t2);
        transition: all .15s;
    }
    .tgl-btn.active { background: var(--blue); color: #fff; }

    /* ── Table ── */
    .tbl-scroll { overflow-x: auto; }
    table.siswa-tbl { width: 100%; border-collapse: collapse; }
    table.siswa-tbl thead tr {
        background: rgba(59,130,246,0.04);
        border-bottom: 0.5px solid var(--border2);
    }
    table.siswa-tbl th {
        text-align: left;
        padding: 10px 14px;
        font-size: 10px;
        font-weight: 500;
        color: var(--t3);
        letter-spacing: .07em;
        text-transform: uppercase;
        white-space: nowrap;
    }
    table.siswa-tbl th.center { text-align: center; }
    table.siswa-tbl td {
        padding: 11px 14px;
        font-size: 13px;
        color: var(--t2);
        border-bottom: 0.5px solid var(--border2);
        vertical-align: middle;
    }
    table.siswa-tbl tbody tr:last-child td { border-bottom: none; }
    table.siswa-tbl tbody tr:hover td { background: var(--blue-l); }

    /* ── Avatar initials ── */
    .ava {
        width: 34px; height: 34px;
        border-radius: 9px;
        display: flex; align-items: center; justify-content: center;
        font-size: 12px; font-weight: 500; color: #fff;
        flex-shrink: 0;
        background: var(--blue);
    }
    .ava img { width: 100%; height: 100%; object-fit: cover; border-radius: 9px; }

    /* ── Pills ── */
    .pill {
        display: inline-flex; align-items: center;
        padding: 2px 9px;
        border-radius: 20px;
        font-size: 11px; font-weight: 500;
        white-space: nowrap;
    }
    .pill-blue   { background: rgba(59,130,246,0.10); color: #1D4ED8; border: 0.5px solid rgba(59,130,246,0.20); }
    .pill-green  { background: rgba(34,197,94,0.10);  color: #15803D; border: 0.5px solid rgba(34,197,94,0.20); }
    .pill-red    { background: rgba(239,68,68,0.10);  color: #B91C1C; border: 0.5px solid rgba(239,68,68,0.18); }
    @media (prefers-color-scheme: dark) {
        .pill-blue  { color: #93C5FD; }
        .pill-green { color: #4ADE80; }
        .pill-red   { color: #FCA5A5; }
    }

    /* ── Action buttons ── */
    .acts { display: flex; align-items: center; justify-content: center; gap: 5px; }
    .act-btn {
        position: relative;
        width: 30px; height: 30px;
        border-radius: 8px;
        border: 0.5px solid var(--border2);
        background: transparent;
        color: var(--t3);
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: all .15s;
    }
    .act-btn svg { width: 15px; height: 15px; }
    .act-btn:hover { transform: translateY(-1px); }
    .act-btn.view:hover { border-color: rgba(59,130,246,0.35); background: rgba(59,130,246,0.08); color: var(--blue); }
    .act-btn.edit:hover { border-color: rgba(234,179,8,0.35);  background: rgba(234,179,8,0.08);  color: #CA8A04; }
    .act-btn.del:hover  { border-color: rgba(239,68,68,0.30);  background: rgba(239,68,68,0.07);  color: #DC2626; }
    /* tooltip */
    .act-btn .tt {
        position: absolute;
        bottom: calc(100% + 5px);
        left: 50%; transform: translateX(-50%);
        background: var(--t1); color: var(--surface);
        font-size: 10px; padding: 3px 8px;
        border-radius: 5px; white-space: nowrap;
        opacity: 0; pointer-events: none;
        transition: opacity .15s;
    }
    .act-btn:hover .tt { opacity: 1; }

    /* ── Card grid ── */
    .card-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(175px, 1fr));
        gap: 12px;
        padding: 16px;
    }
    .student-card {
        background: var(--surface2);
        border: 0.5px solid var(--border);
        border-radius: var(--radius-md);
        padding: 16px;
        cursor: pointer;
        transition: border-color .15s, transform .15s;
    }
    .student-card:hover { border-color: rgba(59,130,246,0.40); transform: translateY(-2px); }
    .student-card .card-ava {
        width: 44px; height: 44px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 17px; font-weight: 500; color: #fff;
        margin-bottom: 11px;
        overflow: hidden;
    }
    .student-card .card-ava img { width: 100%; height: 100%; object-fit: cover; }
    .student-card .card-name { font-size: 13px; font-weight: 500; color: var(--t1); margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .student-card .card-sub  { font-size: 11px; color: var(--t3); margin-bottom: 10px; }
    .student-card .card-foot { display: flex; align-items: center; justify-content: space-between; gap: 4px; padding-top: 10px; border-top: 0.5px solid var(--border2); }

    /* ── Empty state ── */
    .empty-state { padding: 60px 20px; text-align: center; color: var(--t3); }
    .empty-state svg { width: 48px; height: 48px; margin: 0 auto 12px; opacity: .40; }
    .empty-state p { font-size: 13px; font-weight: 500; color: var(--t2); }

    /* ── Pagination wrapper ── */
    .pager-wrap { padding: 12px 16px; border-top: 0.5px solid var(--border2); }

    /* ── Avatar color helpers (js assigns via data-color) ── */
    .ava[data-color="0"] { background: #3B82F6; }
    .ava[data-color="1"] { background: #8B5CF6; }
    .ava[data-color="2"] { background: #EC4899; }
    .ava[data-color="3"] { background: #10B981; }
    .ava[data-color="4"] { background: #F59E0B; }
    .ava[data-color="5"] { background: #0891B2; }
    .ava[data-color="6"] { background: #6366F1; }
    .ava[data-color="7"] { background: #14B8A6; }
</style>
@endpush

@section('content')
<div class="space-y-4">

    {{-- ── Header ── --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-xl font-semibold" style="color:var(--t1)">Data Siswa</h2>
            <p class="text-sm mt-1" style="color:var(--t3)">
                <span class="font-medium" style="color:#16A34A">{{ $totalAktif }} aktif</span>
                @if($totalNonAktif > 0)
                    &middot; <span>{{ $totalNonAktif }} nonaktif</span>
                @endif
            </p>
        </div>
        @if(Auth::user()->isAdmin())
        <a href="{{ route('siswa.create') }}"
           style="display:inline-flex;align-items:center;gap:7px;padding:9px 18px;background:var(--blue);color:#fff;font-size:13px;font-weight:500;border-radius:10px;text-decoration:none;transition:background .15s;"
           onmouseover="this.style.background='var(--blue-d)'"
           onmouseout="this.style.background='var(--blue)'">
            <svg style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Siswa
        </a>
        @endif
    </div>

    {{-- ── Stat bar ── --}}
    <div class="stat-bar">
        <div class="stat-card">
            <div class="lbl">Total Siswa</div>
            <div class="val">{{ $totalAktif + $totalNonAktif }}</div>
            <div class="sub">Semua status</div>
        </div>
        <div class="stat-card">
            <div class="lbl">Aktif</div>
            <div class="val" style="color:#16A34A">{{ $totalAktif }}</div>
            <div class="sub">Tahun ajaran ini</div>
        </div>
        <div class="stat-card">
            <div class="lbl">Kelompok KB</div>
            <div class="val" style="color:var(--blue)">{{ $totalAktif + $totalNonAktif }}</div>
            <div class="sub">1 kelompok</div>
        </div>
        <div class="stat-card">
            <div class="lbl">Nonaktif</div>
            <div class="val" style="color:#DC2626">{{ $totalNonAktif }}</div>
            <div class="sub">Perlu tindak lanjut</div>
        </div>
    </div>

    {{-- ── Filter + Toggle + Tabel ── --}}
    <div class="glass-panel">

        {{-- Filter row --}}
        <form method="GET" action="{{ route('siswa.index') }}">
            <div class="filter-row">
                {{-- Search --}}
                <div class="srch-wrap">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/>
                    </svg>
                    <input type="text" name="cari" value="{{ request('cari') }}"
                           placeholder="Cari nama atau NIS...">
                </div>

                {{-- Kelompok --}}
                <select name="kelompok" class="flt-select">
                    <option value="">Semua Kelompok</option>
                    <option value="KB" {{ request('kelompok') === 'KB' ? 'selected' : '' }}>KB</option>
                </select>

                {{-- Status --}}
                <select name="status" class="flt-select">
                    <option value="aktif"    {{ request('status', 'aktif') === 'aktif'   ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') === 'nonaktif'         ? 'selected' : '' }}>Nonaktif</option>
                </select>

                <button type="submit" class="btn-cari">Cari</button>

                @if(request()->hasAny(['cari','kelompok','status']))
                <a href="{{ route('siswa.index') }}" class="btn-reset" style="text-decoration:none;display:inline-flex;align-items:center;">Reset</a>
                @endif

                {{-- View toggle --}}
                <div class="view-toggle" role="group" aria-label="Mode tampilan">
                    <button type="button" class="tgl-btn active" id="btnTbl" onclick="setView('table')" aria-pressed="true">
                        <svg style="width:13px;height:13px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 10h18M3 14h18M10 4v16M3 4h18v16H3z"/>
                        </svg>
                        Tabel
                    </button>
                    <button type="button" class="tgl-btn" id="btnCard" onclick="setView('card')" aria-pressed="false">
                        <svg style="width:13px;height:13px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                        Kartu
                    </button>
                </div>
            </div>
        </form>

        {{-- ── TABLE VIEW ── --}}
        <div id="viewTable">
            @if($siswa->isEmpty())
            <div class="empty-state">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M17 20H5a2 2 0 01-2-2V7a2 2 0 012-2h3m4 0h5a2 2 0 012 2v1M9 5V3m6 2V3M3 10h18"/>
                </svg>
                <p>Tidak ada data siswa</p>
                @if(Auth::user()->isAdmin())
                <a href="{{ route('siswa.create') }}"
                   style="display:inline-block;margin-top:8px;font-size:13px;color:var(--blue);text-decoration:none;"
                   onmouseover="this.style.textDecoration='underline'"
                   onmouseout="this.style.textDecoration='none'">
                    + Tambah siswa pertama
                </a>
                @endif
            </div>
            @else
            <div class="tbl-scroll">
                <table class="siswa-tbl">
                    <thead>
                        <tr>
                            <th style="width:30%">Siswa</th>
                            <th style="width:11%" class="hidden md:table-cell">NIS</th>
                            <th style="width:10%" class="hidden sm:table-cell">Kel.</th>
                            <th style="width:19%" class="hidden lg:table-cell">Wali</th>
                            <th style="width:13%" class="hidden lg:table-cell">No. HP</th>
                            <th style="width:10%;text-align:center">Status</th>
                            <th style="width:14%;text-align:center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($siswa as $index => $s)
                        <tr>
                            {{-- Siswa --}}
                            <td>
                                <div style="display:flex;align-items:center;gap:10px">
                                    <div class="ava" data-color="{{ $index % 8 }}">
                                        @if($s->foto)
                                            <img src="{{ Storage::url($s->foto) }}" alt="{{ $s->nama_lengkap }}">
                                        @else
                                            {{ strtoupper(substr($s->nama_lengkap, 0, 1)) }}
                                        @endif
                                    </div>
                                    <div>
                                        <p style="font-size:13px;font-weight:500;color:var(--t1);margin-bottom:2px">{{ $s->nama_lengkap }}</p>
                                        @if($s->umur)
                                        <p style="font-size:11px;color:var(--t3)">{{ $s->umur }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            {{-- NIS --}}
                            <td class="hidden md:table-cell">{{ $s->nis ?? '-' }}</td>
                            {{-- Kelompok --}}
                            <td class="hidden sm:table-cell">
                                <span class="pill pill-blue">{{ $s->kelompok }}</span>
                            </td>
                            {{-- Wali --}}
                            <td class="hidden lg:table-cell">{{ $s->nama_kontak ?? '-' }}</td>
                            {{-- No HP --}}
                            <td class="hidden lg:table-cell">{{ $s->kontak_utama ?? '-' }}</td>
                            {{-- Status --}}
                            <td style="text-align:center">
                                @if($s->aktif)
                                    <span class="pill pill-green">Aktif</span>
                                @else
                                    <span class="pill pill-red">Nonaktif</span>
                                @endif
                            </td>
                            {{-- Aksi --}}
                            <td>
                                <div class="acts">
                                    {{-- Detail --}}
                                    <a href="{{ route('siswa.show', $s) }}" class="act-btn view" title="Detail" aria-label="Lihat detail {{ $s->nama_lengkap }}">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <span class="tt">Detail</span>
                                    </a>

                                    @if(Auth::user()->isAdmin())
                                    {{-- Edit --}}
                                    <a href="{{ route('siswa.edit', $s) }}" class="act-btn edit" title="Edit" aria-label="Edit {{ $s->nama_lengkap }}">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        <span class="tt">Edit</span>
                                    </a>

                                    @if($s->aktif)
                                    {{-- Nonaktifkan --}}
                                    <form method="POST" action="{{ route('siswa.destroy', $s) }}" style="display:contents"
                                          onsubmit="return confirm('Nonaktifkan siswa {{ addslashes($s->nama_lengkap) }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="act-btn del" title="Nonaktifkan" aria-label="Nonaktifkan {{ $s->nama_lengkap }}">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636"/>
                                            </svg>
                                            <span class="tt">Nonaktifkan</span>
                                        </button>
                                    </form>
                                    @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($siswa->hasPages())
            <div class="pager-wrap">
                {{ $siswa->appends(request()->query())->links() }}
            </div>
            @endif
            @endif
        </div>

        {{-- ── CARD VIEW ── --}}
        <div id="viewCard" style="display:none">
            @if($siswa->isEmpty())
            <div class="empty-state">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M17 20H5a2 2 0 01-2-2V7a2 2 0 012-2h3m4 0h5a2 2 0 012 2v1M9 5V3m6 2V3M3 10h18"/>
                </svg>
                <p>Tidak ada data siswa</p>
            </div>
            @else
            <div class="card-grid">
                @foreach($siswa as $index => $s)
                @php
                    $cardColors = ['#3B82F6','#8B5CF6','#EC4899','#10B981','#F59E0B','#0891B2','#6366F1','#14B8A6'];
                    $cardColor  = $cardColors[$index % count($cardColors)];
                @endphp
                <a href="{{ route('siswa.show', $s) }}" class="student-card" style="text-decoration:none">
                    <div class="card-ava" style="background:{{ $cardColor }}">
                        @if($s->foto)
                            <img src="{{ Storage::url($s->foto) }}" alt="{{ $s->nama_lengkap }}">
                        @else
                            {{ strtoupper(substr($s->nama_lengkap, 0, 2)) }}
                        @endif
                    </div>
                    <div class="card-name">{{ $s->nama_lengkap }}</div>
                    <div class="card-sub">{{ $s->nis ?? '-' }}@if($s->umur) &middot; {{ $s->umur }}@endif</div>
                    <div class="card-foot">
                        @if($s->aktif)
                            <span class="pill pill-green">Aktif</span>
                        @else
                            <span class="pill pill-red">Nonaktif</span>
                        @endif
                        <span class="pill pill-blue">{{ $s->kelompok }}</span>
                    </div>
                </a>
                @endforeach
            </div>

            @if($siswa->hasPages())
            <div class="pager-wrap">
                {{ $siswa->appends(request()->query())->links() }}
            </div>
            @endif
            @endif
        </div>

    </div>{{-- /glass-panel --}}
</div>

@push('scripts')
<script>
function setView(v) {
    const tbl  = document.getElementById('viewTable');
    const card = document.getElementById('viewCard');
    const btnT = document.getElementById('btnTbl');
    const btnC = document.getElementById('btnCard');
    if (v === 'table') {
        tbl.style.display  = 'block';
        card.style.display = 'none';
        btnT.classList.add('active');    btnT.setAttribute('aria-pressed','true');
        btnC.classList.remove('active'); btnC.setAttribute('aria-pressed','false');
    } else {
        tbl.style.display  = 'none';
        card.style.display = 'block';
        btnT.classList.remove('active'); btnT.setAttribute('aria-pressed','false');
        btnC.classList.add('active');    btnC.setAttribute('aria-pressed','true');
    }
    localStorage.setItem('siswaView', v);
}

// Restore last view preference
document.addEventListener('DOMContentLoaded', function () {
    const saved = localStorage.getItem('siswaView');
    if (saved === 'card') setView('card');
});
</script>
@endpush

@endsection