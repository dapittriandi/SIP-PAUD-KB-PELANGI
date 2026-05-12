@extends('layouts.app')

@section('title', 'Data Guru — PAUD KB Pelangi')
@section('page-title', 'Data Guru')

@section('content')
<div class="guru-page">

    {{-- Page Header --}}
    <div class="page-header">
        <div class="header-left">
            <h2 class="page-title">Data Guru</h2>
            <div class="page-meta">
                <span class="badge-aktif">
                    <span class="dot"></span>
                    {{ $totalAktif }} aktif
                </span>
                @if($totalNonAktif > 0)
                <span class="sep">·</span>
                <span class="badge-nonaktif">{{ $totalNonAktif }} nonaktif</span>
                @endif
            </div>
        </div>
        @if(Auth::user()->isAdmin())
        <a href="{{ route('guru.create') }}" class="btn-primary">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
            Tambah Guru
        </a>
        @endif
    </div>

    {{-- Filter Bar --}}
    <div class="filter-bar glass-card">
        <form method="GET" action="{{ route('guru.index') }}" class="filter-form">
            <div class="search-wrap">
                <svg class="search-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama, username, NIP…" class="search-input" autocomplete="off">
                @if(request('cari'))
                <button type="button" onclick="this.closest('form').querySelector('[name=cari]').value='';this.closest('form').submit()" class="search-clear">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
                </button>
                @endif
            </div>
            <div class="filter-right">
                <div class="status-tabs">
                    <label class="status-tab {{ request('status', 'aktif') === 'aktif' ? 'active' : '' }}">
                        <input type="radio" name="status" value="aktif" {{ request('status', 'aktif') === 'aktif' ? 'checked' : '' }} onchange="this.form.submit()"> Aktif
                    </label>
                    <label class="status-tab {{ request('status') === 'nonaktif' ? 'active' : '' }}">
                        <input type="radio" name="status" value="nonaktif" {{ request('status') === 'nonaktif' ? 'checked' : '' }} onchange="this.form.submit()"> Nonaktif
                    </label>
                </div>
                <button type="submit" class="btn-search">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    Cari
                </button>
                @if(request()->hasAny(['cari','status']))
                <a href="{{ route('guru.index') }}" class="btn-reset">Reset</a>
                @endif
            </div>
        </form>
    </div>

    {{-- Content --}}
    @if($guru->isEmpty())
    <div class="empty-state glass-card">
        <div class="empty-icon">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
        </div>
        <p class="empty-title">Tidak ada data guru</p>
        <p class="empty-sub">Belum ada guru yang terdaftar{{ request('status') === 'nonaktif' ? ' dengan status nonaktif' : '' }}.</p>
        @if(Auth::user()->isAdmin())
        <a href="{{ route('guru.create') }}" class="btn-primary" style="margin-top:16px">+ Tambah Guru Pertama</a>
        @endif
    </div>

    @else
    {{-- Meta info --}}
    <div class="result-meta">
        <span class="result-count">{{ $guru->total() }} guru ditemukan</span>
        @if($guru->hasPages())
        <span class="result-sep">·</span>
        <span class="result-page">Halaman {{ $guru->currentPage() }} / {{ $guru->lastPage() }}</span>
        @endif
    </div>

    {{-- ===================== DESKTOP: LIST VIEW ===================== --}}
    <div class="guru-list glass-card">
        <div class="list-header">
            <div class="lh-guru">Guru</div>
            <div class="lh-jabatan">Jabatan</div>
            <div class="lh-status">Status Kepegawaian</div>
            <div class="lh-aktif">Status</div>
            <div class="lh-aksi">Aksi</div>
        </div>
        @foreach($guru as $g)
        <div class="list-row">
            <div class="lc-guru">
                <div class="avatar-sm {{ $g->aktif ? 'av-aktif' : 'av-nonaktif' }}">
                    @if($g->foto)
                        <img src="{{ Storage::url($g->foto) }}" alt="{{ $g->nama_lengkap }}" class="avatar-img">
                    @else
                        {{ strtoupper(substr($g->nama_lengkap, 0, 2)) }}
                    @endif
                </div>
                <div class="guru-info">
                    <span class="guru-nama">{{ $g->nama_lengkap }}</span>
                    <span class="guru-username">&#64;{{ $g->username }}</span>
                </div>
            </div>
            <div class="lc-jabatan">
                @if($g->jabatan)
                <span class="text-cell">{{ $g->jabatan }}</span>
                @else
                <span class="text-empty">—</span>
                @endif
            </div>
            <div class="lc-status">
                @if($g->label_status_kepegawaian !== '-')
                <span class="chip-kepeg">{{ $g->label_status_kepegawaian }}</span>
                @else
                <span class="text-empty">—</span>
                @endif
            </div>
            <div class="lc-aktif">
                @if($g->aktif)
                <span class="chip-aktif"><span class="dot"></span>Aktif</span>
                @else
                <span class="chip-nonaktif">Nonaktif</span>
                @endif
            </div>
            <div class="lc-aksi">
                <a href="{{ route('guru.show', $g) }}" class="btn-row-detail">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    Detail
                </a>
                @if(Auth::user()->isAdmin())
                <a href="{{ route('guru.edit', $g) }}" class="btn-row-edit">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    Edit
                </a>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    {{-- ===================== MOBILE: CARD VIEW ===================== --}}
    <div class="guru-cards">
        @foreach($guru as $g)
        <div class="guru-card glass-card">
            <div class="card-top">
                <div class="avatar-md {{ $g->aktif ? 'av-aktif' : 'av-nonaktif' }}">
                    @if($g->foto)
                        <img src="{{ Storage::url($g->foto) }}" alt="{{ $g->nama_lengkap }}" class="avatar-img">
                    @else
                        {{ strtoupper(substr($g->nama_lengkap, 0, 2)) }}
                    @endif
                </div>
                <div class="card-info">
                    <div class="card-name-row">
                        <span class="card-nama">{{ $g->nama_lengkap }}</span>
                        @if($g->aktif)
                        <span class="chip-aktif"><span class="dot"></span>Aktif</span>
                        @else
                        <span class="chip-nonaktif">Nonaktif</span>
                        @endif
                    </div>
                    <span class="card-username">&#64;{{ $g->username }}</span>
                    <div class="card-chips">
                        @if($g->jabatan)
                        <span class="chip-info">{{ $g->jabatan }}</span>
                        @endif
                        @if($g->label_status_kepegawaian !== '-')
                        <span class="chip-kepeg">{{ $g->label_status_kepegawaian }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('guru.show', $g) }}" class="btn-row-detail">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    Detail
                </a>
                @if(Auth::user()->isAdmin())
                <a href="{{ route('guru.edit', $g) }}" class="btn-row-edit">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    Edit
                </a>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($guru->hasPages())
    <div class="pagination-wrap">{{ $guru->links() }}</div>
    @endif
    @endif

</div>

<style>
/* ============================================
   GURU INDEX — Glass · Professional · Indigo
   Desktop: List Table | Mobile: Card
   Dark Mode ✓ | Fixed username ✓
   ============================================ */

:root {
    --indigo:        #6366f1;
    --indigo-dark:   #4f46e5;
    --indigo-light:  #eef2ff;
    --indigo-mid:    #c7d2fe;
    --glass-bg:      rgba(255,255,255,0.72);
    --glass-border:  rgba(99,102,241,0.13);
    --glass-blur:    blur(18px);
    --glass-shadow:  0 4px 24px rgba(99,102,241,0.07), 0 1px 4px rgba(0,0,0,0.04);
    --tx-1:  #1e1b4b;
    --tx-2:  #4338ca;
    --tx-3:  #6b7280;
    --tx-4:  #9ca3af;
    --row-hover:  rgba(99,102,241,0.045);
    --row-border: rgba(99,102,241,0.09);
    --radius:  14px;
    --radius-s: 8px;
    --t: 0.16s ease;
    --font: 'Inter', 'Plus Jakarta Sans', system-ui, sans-serif;
}

@media (prefers-color-scheme: dark) {
    :root {
        --glass-bg:      rgba(18,20,35,0.84);
        --glass-border:  rgba(99,102,241,0.2);
        --glass-shadow:  0 4px 28px rgba(0,0,0,0.55), 0 1px 4px rgba(0,0,0,0.3);
        --indigo-light:  rgba(99,102,241,0.14);
        --indigo-mid:    rgba(99,102,241,0.28);
        --tx-1:  #e0e7ff;
        --tx-2:  #a5b4fc;
        --tx-3:  #6b7280;
        --tx-4:  #374151;
        --row-hover:  rgba(99,102,241,0.07);
        --row-border: rgba(99,102,241,0.11);
    }
}

*, *::before, *::after { box-sizing: border-box; }

.guru-page {
    display: flex;
    flex-direction: column;
    gap: 16px;
    font-family: var(--font);
}

/* Glass Card */
.glass-card {
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius);
    box-shadow: var(--glass-shadow);
    backdrop-filter: var(--glass-blur);
    -webkit-backdrop-filter: var(--glass-blur);
}

/* ---- Header ---- */
.page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
}
.page-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--tx-1);
    letter-spacing: -0.025em;
    margin: 0 0 5px;
}
.page-meta { display: flex; align-items: center; gap: 6px; }
.badge-aktif {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 0.73rem;
    font-weight: 600;
    color: var(--indigo-dark);
    background: var(--indigo-light);
    border: 1px solid var(--indigo-mid);
    padding: 3px 9px;
    border-radius: 20px;
}
.badge-aktif .dot {
    width: 5px; height: 5px;
    border-radius: 50%;
    background: var(--indigo);
    animation: blink 2.4s ease-in-out infinite;
}
@keyframes blink { 0%,100%{opacity:1} 50%{opacity:.3} }
.badge-nonaktif { font-size: 0.73rem; color: var(--tx-3); font-weight: 500; }
.sep { color: var(--tx-4); }

/* ---- Buttons ---- */
.btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 17px;
    background: var(--indigo);
    color: #fff;
    font-size: 0.81rem;
    font-weight: 600;
    border-radius: var(--radius-s);
    text-decoration: none;
    transition: background var(--t), transform var(--t), box-shadow var(--t);
    box-shadow: 0 2px 10px rgba(99,102,241,0.32);
    white-space: nowrap;
}
.btn-primary:hover {
    background: var(--indigo-dark);
    transform: translateY(-1px);
    box-shadow: 0 5px 18px rgba(99,102,241,0.42);
}

/* ---- Filter ---- */
.filter-bar { padding: 12px 14px; }
.filter-form { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }

.search-wrap { position: relative; flex: 1; min-width: 200px; }
.search-icon {
    position: absolute; left: 11px; top: 50%; transform: translateY(-50%);
    color: var(--tx-4); pointer-events: none;
}
.search-input {
    width: 100%;
    padding: 8px 32px 8px 33px;
    background: rgba(255,255,255,0.55);
    border: 1.5px solid var(--glass-border);
    border-radius: var(--radius-s);
    font-size: 0.82rem;
    color: var(--tx-1);
    outline: none;
    font-family: var(--font);
    transition: border-color var(--t), box-shadow var(--t), background var(--t);
}
.search-input::placeholder { color: var(--tx-4); }
.search-input:focus {
    background: rgba(255,255,255,0.88);
    border-color: var(--indigo);
    box-shadow: 0 0 0 3px rgba(99,102,241,0.13);
}
@media (prefers-color-scheme: dark) {
    .search-input { background: rgba(255,255,255,0.04); }
    .search-input:focus { background: rgba(255,255,255,0.07); }
}
.search-clear {
    position: absolute; right: 9px; top: 50%; transform: translateY(-50%);
    background: none; border: none; color: var(--tx-3); cursor: pointer;
    display: flex; padding: 2px; line-height: 1;
}

.filter-right { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }

.status-tabs {
    display: flex;
    gap: 2px;
    background: rgba(99,102,241,0.06);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius-s);
    padding: 3px;
}
.status-tab {
    padding: 5px 13px;
    font-size: 0.77rem;
    font-weight: 500;
    color: var(--tx-3);
    border-radius: 6px;
    cursor: pointer;
    transition: background var(--t), color var(--t), box-shadow var(--t);
    user-select: none;
    white-space: nowrap;
}
.status-tab input { display: none; }
.status-tab:hover { color: var(--tx-1); }
.status-tab.active {
    background: var(--glass-bg);
    color: var(--indigo-dark);
    font-weight: 700;
    box-shadow: 0 1px 4px rgba(99,102,241,0.14);
}

.btn-search {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 7px 15px;
    background: var(--tx-1); color: #fff;
    font-size: 0.79rem; font-weight: 600;
    border: none; border-radius: var(--radius-s);
    cursor: pointer; transition: opacity var(--t), transform var(--t);
    font-family: var(--font); white-space: nowrap;
}
.btn-search:hover { opacity: 0.82; transform: translateY(-1px); }
@media (prefers-color-scheme: dark) {
    .btn-search { background: #e0e7ff; color: #1e1b4b; }
}

.btn-reset {
    padding: 7px 13px;
    font-size: 0.79rem; font-weight: 500;
    color: var(--tx-3);
    background: transparent;
    border: 1.5px solid var(--glass-border);
    border-radius: var(--radius-s);
    text-decoration: none;
    transition: background var(--t), color var(--t);
    white-space: nowrap;
}
.btn-reset:hover { background: rgba(99,102,241,0.06); color: var(--tx-1); }

/* ---- Result Meta ---- */
.result-meta {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 0 2px;
}
.result-count { font-size: 0.77rem; font-weight: 600; color: var(--tx-1); }
.result-sep   { color: var(--tx-4); font-size: 0.77rem; }
.result-page  { font-size: 0.75rem; color: var(--tx-3); }

/* =============================================
   DESKTOP LIST TABLE
   ============================================= */
.guru-list { overflow: hidden; }
.guru-cards { display: none; }

.list-header {
    display: grid;
    grid-template-columns: 2.4fr 1.4fr 1.4fr 0.85fr 1fr;
    padding: 10px 20px;
    border-bottom: 1px solid var(--row-border);
    background: rgba(99,102,241,0.03);
}
.list-header > div {
    font-size: 0.68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    color: var(--tx-3);
}

.list-row {
    display: grid;
    grid-template-columns: 2.4fr 1.4fr 1.4fr 0.85fr 1fr;
    align-items: center;
    padding: 11px 20px;
    border-bottom: 1px solid var(--row-border);
    transition: background var(--t);
}
.list-row:last-child { border-bottom: none; }
.list-row:hover { background: var(--row-hover); }

/* Cols */
.lc-guru {
    display: flex;
    align-items: center;
    gap: 11px;
    min-width: 0;
}
.guru-info { display: flex; flex-direction: column; gap: 1px; min-width: 0; }
.guru-nama {
    font-size: 0.84rem;
    font-weight: 600;
    color: var(--tx-1);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.guru-username {
    font-size: 0.7rem;
    color: var(--tx-3);
    font-family: 'SF Mono', 'Cascadia Code', 'Fira Code', monospace;
}
.text-cell  { font-size: 0.79rem; color: var(--tx-1); }
.text-empty { font-size: 0.79rem; color: var(--tx-4); }

.lc-aksi { display: flex; align-items: center; gap: 6px; }

/* =============================================
   AVATARS
   ============================================= */
.avatar-sm {
    width: 34px; height: 34px;
    border-radius: 9px;
    flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.72rem; font-weight: 700; letter-spacing: 0.02em;
    color: #fff; overflow: hidden;
}
.avatar-md {
    width: 44px; height: 44px;
    border-radius: 12px;
    flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.82rem; font-weight: 700;
    color: #fff; overflow: hidden;
}
.av-aktif    { background: linear-gradient(135deg, #6366f1 0%, #818cf8 100%); }
.av-nonaktif { background: linear-gradient(135deg, #94a3b8 0%, #cbd5e1 100%); }
.avatar-img  { width: 100%; height: 100%; object-fit: cover; }

/* =============================================
   CHIPS
   ============================================= */
.chip-aktif {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 0.68rem; font-weight: 700;
    color: var(--indigo-dark);
    background: var(--indigo-light);
    border: 1px solid var(--indigo-mid);
    padding: 2px 8px; border-radius: 20px; white-space: nowrap;
}
.chip-aktif .dot {
    width: 5px; height: 5px; border-radius: 50%;
    background: var(--indigo);
}
.chip-nonaktif {
    display: inline-flex; align-items: center;
    font-size: 0.68rem; font-weight: 600;
    color: var(--tx-3);
    background: rgba(107,114,128,0.09);
    border: 1px solid rgba(107,114,128,0.18);
    padding: 2px 8px; border-radius: 20px; white-space: nowrap;
}
.chip-kepeg {
    display: inline-flex; align-items: center;
    font-size: 0.69rem; font-weight: 500;
    color: var(--tx-2);
    background: rgba(99,102,241,0.08);
    border: 1px solid rgba(99,102,241,0.15);
    padding: 2px 8px; border-radius: 6px; white-space: nowrap;
}
.chip-info {
    display: inline-flex; align-items: center;
    font-size: 0.69rem; font-weight: 500;
    color: var(--tx-3);
    background: rgba(107,114,128,0.07);
    border: 1px solid rgba(107,114,128,0.12);
    padding: 2px 8px; border-radius: 6px; white-space: nowrap;
}

/* =============================================
   ROW BUTTONS
   ============================================= */
.btn-row-detail, .btn-row-edit {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 5px 11px;
    font-size: 0.74rem; font-weight: 600;
    border-radius: 7px; text-decoration: none;
    transition: background var(--t), transform var(--t), box-shadow var(--t);
    white-space: nowrap;
}
.btn-row-detail {
    background: rgba(99,102,241,0.07);
    border: 1px solid rgba(99,102,241,0.15);
    color: var(--indigo-dark);
}
.btn-row-detail:hover {
    background: rgba(99,102,241,0.13);
    transform: translateY(-1px);
}
.btn-row-edit {
    background: var(--indigo);
    border: 1px solid transparent;
    color: #fff;
    box-shadow: 0 2px 7px rgba(99,102,241,0.22);
}
.btn-row-edit:hover {
    background: var(--indigo-dark);
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(99,102,241,0.38);
}

/* =============================================
   MOBILE CARD
   ============================================= */
.guru-card .card-top {
    padding: 14px 14px 8px;
    display: flex; gap: 12px; align-items: flex-start;
}
.card-info { flex: 1; min-width: 0; }
.card-name-row {
    display: flex; align-items: center;
    justify-content: space-between; gap: 6px;
    margin-bottom: 2px;
}
.card-nama {
    font-size: 0.86rem; font-weight: 700; color: var(--tx-1);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.card-username {
    font-size: 0.7rem; color: var(--tx-3);
    font-family: 'SF Mono', 'Fira Code', monospace;
    display: block; margin-bottom: 7px;
}
.card-chips { display: flex; flex-wrap: wrap; gap: 5px; }
.guru-card .card-footer {
    padding: 0 10px 10px;
    display: flex; gap: 8px;
}
.guru-card .btn-row-detail,
.guru-card .btn-row-edit { flex: 1; justify-content: center; padding: 8px; }

/* =============================================
   EMPTY STATE
   ============================================= */
.empty-state {
    padding: 52px 24px; text-align: center;
    display: flex; flex-direction: column; align-items: center;
}
.empty-icon {
    width: 58px; height: 58px;
    border-radius: 14px;
    background: rgba(99,102,241,0.06);
    border: 1.5px solid var(--glass-border);
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 14px; color: var(--tx-3);
}
.empty-title { font-size: 0.9rem; font-weight: 700; color: var(--tx-1); margin: 0 0 4px; }
.empty-sub   { font-size: 0.79rem; color: var(--tx-3); margin: 0; }

/* Pagination */
.pagination-wrap { display: flex; justify-content: center; }

/* =============================================
   RESPONSIVE
   ============================================= */
@media (max-width: 768px) {
    .guru-list  { display: none; }
    .guru-cards { display: flex; flex-direction: column; gap: 10px; }

    .filter-form { flex-direction: column; align-items: stretch; }
    .filter-right { justify-content: space-between; }
    .page-header  { flex-direction: column; align-items: stretch; }
    .btn-primary  { justify-content: center; }
}
</style>
@endsection