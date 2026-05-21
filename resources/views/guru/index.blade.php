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
                <button type="button" onclick="this.closest('form').querySelector('[name=cari]').value='';this.closest('form').submit()" class="search-clear" aria-label="Hapus pencarian">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
                </button>
                @endif
            </div>
            <div class="filter-right">
                <div class="status-tabs" role="group" aria-label="Filter status">
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
    <div class="guru-list glass-card" role="table" aria-label="Daftar guru">
        <div class="list-header" role="row">
            <div class="lh-guru" role="columnheader">Guru</div>
            <div class="lh-jabatan" role="columnheader">Jabatan</div>
            <div class="lh-status" role="columnheader">Status Kepegawaian</div>
            <div class="lh-aktif" role="columnheader">Status</div>
            <div class="lh-aksi" role="columnheader">Aksi</div>
        </div>
        @foreach($guru as $g)
        <div class="list-row" role="row">
            <div class="lc-guru" role="cell">
                <div class="avatar-sm {{ $g->aktif ? 'av-aktif' : 'av-nonaktif' }}" aria-hidden="true">
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
            <div class="lc-jabatan" role="cell">
                @if($g->jabatan)
                <span class="text-cell">{{ $g->jabatan }}</span>
                @else
                <span class="text-empty">—</span>
                @endif
            </div>
            <div class="lc-status" role="cell">
                @if($g->label_status_kepegawaian !== '-')
                <span class="chip-kepeg">{{ $g->label_status_kepegawaian }}</span>
                @else
                <span class="text-empty">—</span>
                @endif
            </div>
            <div class="lc-aktif" role="cell">
                @if($g->aktif)
                <span class="chip-aktif"><span class="dot" aria-hidden="true"></span>Aktif</span>
                @else
                <span class="chip-nonaktif">Nonaktif</span>
                @endif
            </div>
            <div class="lc-aksi" role="cell">
                <a href="{{ route('guru.show', $g) }}" class="btn-row-detail">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    Detail
                </a>
                @if(Auth::user()->isAdmin())
                <a href="{{ route('guru.edit', $g) }}" class="btn-row-edit">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
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
                <div class="avatar-md {{ $g->aktif ? 'av-aktif' : 'av-nonaktif' }}" aria-hidden="true">
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
                        <span class="chip-aktif"><span class="dot" aria-hidden="true"></span>Aktif</span>
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
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    Detail
                </a>
                @if(Auth::user()->isAdmin())
                <a href="{{ route('guru.edit', $g) }}" class="btn-row-edit">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
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
/* ============================================================
   GURU INDEX — Glassmorphism · SIP-Pelangi v2
   Selaras dengan design system app.blade.php:
   - OKLCH tokens dari :root app.blade
   - Geist font
   - Dark mode via .dark class (Alpine x-data)
   - Glass: purposeful, bukan dekoratif
   ============================================================ */

/* ── Local tokens (extend app.blade tokens) ── */
.guru-page {
    --glass-bg:       oklch(99.5% 0.003 250 / 72%);
    --glass-bg-2:     oklch(98% 0.005 250 / 60%);
    --glass-border:   oklch(90% 0.007 250 / 65%);
    --glass-border-a: oklch(52% 0.190 260 / 14%);
    --glass-blur:     blur(20px) saturate(1.6);
    --glass-shadow:   0 4px 24px oklch(52% 0.190 260 / 7%), 0 1px 3px oklch(0% 0 0 / 5%);
    --glass-shadow-h: 0 8px 32px oklch(52% 0.190 260 / 12%), 0 2px 6px oklch(0% 0 0 / 6%);

    --row-hover:    oklch(96% 0.025 260 / 55%);
    --row-divider:  oklch(90% 0.007 250 / 50%);

    display: flex;
    flex-direction: column;
    gap: var(--sp-9, 24px);
    font-family: 'Geist', system-ui, sans-serif;
    font-size: var(--fs-base, 14px);
}

/* Dark mode override */
.dark .guru-page {
    --glass-bg:       oklch(15.5% 0.012 255 / 80%);
    --glass-bg-2:     oklch(13.5% 0.012 255 / 70%);
    --glass-border:   oklch(28% 0.010 255 / 55%);
    --glass-border-a: oklch(63% 0.185 260 / 20%);
    --glass-shadow:   0 4px 28px oklch(0% 0 0 / 40%), 0 1px 4px oklch(0% 0 0 / 25%);
    --glass-shadow-h: 0 8px 36px oklch(0% 0 0 / 50%), 0 2px 8px oklch(0% 0 0 / 30%);
    --row-hover:      oklch(20% 0.030 260 / 60%);
    --row-divider:    oklch(28% 0.010 255 / 40%);
}

/* ── Glass Card ── */
.glass-card {
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: var(--r-lg, 14px);
    box-shadow: var(--glass-shadow);
    backdrop-filter: var(--glass-blur);
    -webkit-backdrop-filter: var(--glass-blur);
    transition:
        box-shadow var(--dur-mid, 0.22s) var(--ease-out),
        border-color var(--dur-mid, 0.22s) var(--ease-out);
}
.glass-card:hover {
    box-shadow: var(--glass-shadow-h);
    border-color: var(--glass-border-a);
}

/* ── Page Header ── */
.page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: var(--sp-4, 12px);
    flex-wrap: wrap;
}
.page-title {
    font-size: var(--fs-lg, 18px);
    font-weight: 700;
    color: var(--text-1);
    letter-spacing: -0.03em;
    margin: 0 0 6px;
    line-height: 1.2;
}
.page-meta {
    display: flex;
    align-items: center;
    gap: var(--sp-2, 8px);
}

/* ── Badges header ── */
.badge-aktif {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: var(--fs-xs, 11px);
    font-weight: 700;
    letter-spacing: 0.01em;
    color: var(--accent);
    background: var(--accent-soft);
    border: 1px solid var(--accent-ring);
    padding: 3px 10px;
    border-radius: 20px;
}
.dark .badge-aktif {
    color: var(--accent);
    background: var(--accent-muted);
    border-color: var(--accent-ring);
}
.badge-aktif .dot {
    width: 5px; height: 5px;
    border-radius: 50%;
    background: var(--accent);
    animation: pulse-dot 2.4s ease-in-out infinite;
}
@keyframes pulse-dot {
    0%, 100% { opacity: 1; transform: scale(1); }
    50%       { opacity: 0.35; transform: scale(0.8); }
}
.badge-nonaktif {
    font-size: var(--fs-xs, 11px);
    color: var(--text-3);
    font-weight: 500;
}
.sep { color: var(--text-3); font-size: var(--fs-xs, 11px); }

/* ── Primary Button ── */
.btn-primary {
    display: inline-flex;
    align-items: center;
    gap: var(--sp-2, 8px);
    padding: 9px 18px;
    background: var(--accent);
    color: var(--text-inv);
    font-size: var(--fs-sm, 13px);
    font-weight: 600;
    border-radius: var(--r, 10px);
    text-decoration: none;
    transition:
        background var(--dur-fast) var(--ease-out),
        transform var(--dur-fast) var(--ease-out),
        box-shadow var(--dur-mid) var(--ease-out);
    box-shadow: 0 2px 12px oklch(52% 0.190 260 / 30%);
    white-space: nowrap;
    border: none;
    cursor: pointer;
    font-family: inherit;
}
.btn-primary:hover {
    background: var(--accent-h);
    transform: translateY(-1px);
    box-shadow: 0 6px 20px oklch(52% 0.190 260 / 40%);
}
.btn-primary:active { transform: translateY(0); }

/* ── Filter Bar ── */
.filter-bar {
    padding: var(--sp-4, 12px) var(--sp-6, 16px);
}
.filter-form {
    display: flex;
    align-items: center;
    gap: var(--sp-3, 10px);
    flex-wrap: wrap;
}

.search-wrap { position: relative; flex: 1; min-width: 200px; }
.search-icon {
    position: absolute; left: 11px; top: 50%;
    transform: translateY(-50%);
    color: var(--text-3);
    pointer-events: none;
}
.search-input {
    width: 100%;
    padding: 8px 34px 8px 35px;
    background: oklch(99.5% 0.003 250 / 55%);
    border: 1.5px solid var(--glass-border);
    border-radius: var(--r, 10px);
    font-size: var(--fs-sm, 13px);
    color: var(--text-1);
    outline: none;
    font-family: inherit;
    transition:
        border-color var(--dur-fast) var(--ease-out),
        box-shadow var(--dur-mid) var(--ease-out),
        background var(--dur-mid) var(--ease-out);
}
.search-input::placeholder { color: var(--text-3); }
.search-input:focus {
    background: oklch(99.5% 0.003 250 / 88%);
    border-color: var(--accent);
    box-shadow: 0 0 0 3px oklch(52% 0.190 260 / 14%);
}
.dark .search-input {
    background: oklch(18.5% 0.013 255 / 55%);
    border-color: var(--glass-border);
}
.dark .search-input:focus {
    background: oklch(18.5% 0.013 255 / 80%);
    border-color: var(--accent);
    box-shadow: 0 0 0 3px oklch(63% 0.185 260 / 16%);
}
.search-clear {
    position: absolute; right: 9px; top: 50%;
    transform: translateY(-50%);
    background: none; border: none;
    color: var(--text-3);
    cursor: pointer;
    display: flex; padding: 3px;
    border-radius: 4px;
    transition: color var(--dur-fast);
}
.search-clear:hover { color: var(--text-1); }

.filter-right {
    display: flex;
    align-items: center;
    gap: var(--sp-2, 8px);
    flex-wrap: wrap;
}

/* Status tabs */
.status-tabs {
    display: flex;
    gap: 2px;
    background: oklch(52% 0.190 260 / 6%);
    border: 1px solid var(--glass-border);
    border-radius: var(--r, 10px);
    padding: 3px;
}
.dark .status-tabs {
    background: oklch(63% 0.185 260 / 8%);
    border-color: var(--glass-border);
}
.status-tab {
    padding: 5px 14px;
    font-size: var(--fs-xs, 11px);
    font-weight: 500;
    color: var(--text-2);
    border-radius: calc(var(--r, 10px) - 3px);
    cursor: pointer;
    transition:
        background var(--dur-fast) var(--ease-out),
        color var(--dur-fast) var(--ease-out),
        box-shadow var(--dur-mid) var(--ease-out);
    user-select: none;
    white-space: nowrap;
}
.status-tab input { display: none; }
.status-tab:hover { color: var(--text-1); }
.status-tab.active {
    background: var(--glass-bg);
    color: var(--accent);
    font-weight: 700;
    box-shadow: 0 1px 5px oklch(52% 0.190 260 / 15%);
    border: 1px solid var(--glass-border-a);
}
.dark .status-tab.active {
    background: var(--glass-bg-2);
    color: var(--accent);
}

.btn-search {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 7px 15px;
    background: var(--text-1);
    color: var(--text-inv);
    font-size: var(--fs-xs, 11px); font-weight: 600;
    border: none; border-radius: var(--r, 10px);
    cursor: pointer;
    transition:
        opacity var(--dur-fast),
        transform var(--dur-fast) var(--ease-out);
    font-family: inherit; white-space: nowrap;
}
.btn-search:hover { opacity: 0.80; transform: translateY(-1px); }
.dark .btn-search {
    background: oklch(90% 0.012 260);
    color: oklch(14% 0.008 260);
}

.btn-reset {
    padding: 7px 13px;
    font-size: var(--fs-xs, 11px); font-weight: 500;
    color: var(--text-2);
    background: transparent;
    border: 1.5px solid var(--glass-border);
    border-radius: var(--r, 10px);
    text-decoration: none;
    transition:
        background var(--dur-fast) var(--ease-out),
        color var(--dur-fast) var(--ease-out),
        border-color var(--dur-fast) var(--ease-out);
    white-space: nowrap;
}
.btn-reset:hover {
    background: oklch(52% 0.190 260 / 6%);
    color: var(--accent);
    border-color: var(--accent-ring);
}

/* ── Result Meta ── */
.result-meta {
    display: flex;
    align-items: center;
    gap: var(--sp-2, 8px);
    padding: 0 2px;
}
.result-count { font-size: var(--fs-sm, 13px); font-weight: 600; color: var(--text-1); }
.result-sep   { color: var(--text-3); font-size: var(--fs-sm, 13px); }
.result-page  { font-size: var(--fs-xs, 11px); color: var(--text-2); }

/* =============================================
   DESKTOP LIST TABLE
   ============================================= */
.guru-list  { overflow: hidden; }
.guru-cards { display: none; }

.list-header {
    display: grid;
    grid-template-columns: 2.4fr 1.4fr 1.4fr 0.85fr 1fr;
    padding: 10px 20px;
    border-bottom: 1px solid var(--row-divider);
    background: oklch(52% 0.190 260 / 3.5%);
}
.dark .list-header { background: oklch(63% 0.185 260 / 5%); }
.list-header > div {
    font-size: var(--fs-2xs, 9px);
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--text-2);
}

.list-row {
    display: grid;
    grid-template-columns: 2.4fr 1.4fr 1.4fr 0.85fr 1fr;
    align-items: center;
    padding: 12px 20px;
    border-bottom: 1px solid var(--row-divider);
    transition: background var(--dur-fast) var(--ease-out);
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
.guru-info { display: flex; flex-direction: column; gap: 2px; min-width: 0; }
.guru-nama {
    font-size: var(--fs-sm, 13px);
    font-weight: 600;
    color: var(--text-1);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.guru-username {
    font-size: var(--fs-xs, 11px);
    color: var(--text-3);
    font-family: 'Geist Mono', 'SF Mono', monospace;
    letter-spacing: -0.01em;
}
.text-cell  { font-size: var(--fs-sm, 13px); color: var(--text-1); }
.text-empty { font-size: var(--fs-sm, 13px); color: var(--text-3); }

.lc-aksi { display: flex; align-items: center; gap: 6px; }

/* =============================================
   AVATARS
   ============================================= */
.avatar-sm {
    width: 36px; height: 36px;
    border-radius: var(--r, 10px);
    flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-size: var(--fs-xs, 11px); font-weight: 700; letter-spacing: 0.02em;
    color: oklch(99% 0.003 250);
    overflow: hidden;
    position: relative;
}
.avatar-md {
    width: 46px; height: 46px;
    border-radius: var(--r-lg, 14px);
    flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-size: var(--fs-sm, 13px); font-weight: 700;
    color: oklch(99% 0.003 250);
    overflow: hidden;
    position: relative;
}
/* Ring saat hover list row */
.list-row:hover .avatar-sm,
.guru-card:hover .avatar-md {
    box-shadow: 0 0 0 2px var(--accent-ring);
    transition: box-shadow var(--dur-mid) var(--ease-out);
}

/* Aktif: biru aksen (selaras --accent) */
.av-aktif {
    background: linear-gradient(135deg,
        oklch(52% 0.190 260) 0%,
        oklch(60% 0.175 255) 100%);
}
/* Nonaktif: netral redup */
.av-nonaktif {
    background: linear-gradient(135deg,
        oklch(58% 0.010 255) 0%,
        oklch(72% 0.007 250) 100%);
}
.dark .av-aktif {
    background: linear-gradient(135deg,
        oklch(48% 0.190 260) 0%,
        oklch(56% 0.175 255) 100%);
}
.dark .av-nonaktif {
    background: linear-gradient(135deg,
        oklch(32% 0.010 255) 0%,
        oklch(42% 0.008 250) 100%);
}
.avatar-img { width: 100%; height: 100%; object-fit: cover; }

/* =============================================
   CHIPS
   ============================================= */
.chip-aktif {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: var(--fs-2xs, 9px); font-weight: 700;
    letter-spacing: 0.04em;
    color: var(--accent);
    background: var(--accent-soft);
    border: 1px solid var(--accent-ring);
    padding: 2px 9px; border-radius: 20px; white-space: nowrap;
}
.dark .chip-aktif {
    color: var(--accent);
    background: var(--accent-muted);
    border-color: var(--accent-ring);
}
.chip-aktif .dot {
    width: 5px; height: 5px; border-radius: 50%;
    background: var(--accent);
    animation: pulse-dot 2.4s ease-in-out infinite;
}
.chip-nonaktif {
    display: inline-flex; align-items: center;
    font-size: var(--fs-2xs, 9px); font-weight: 600;
    letter-spacing: 0.03em;
    color: var(--text-2);
    background: oklch(68% 0.008 255 / 12%);
    border: 1px solid oklch(68% 0.008 255 / 22%);
    padding: 2px 9px; border-radius: 20px; white-space: nowrap;
}
.dark .chip-nonaktif {
    color: var(--text-3);
    background: oklch(40% 0.010 255 / 20%);
    border-color: oklch(40% 0.010 255 / 35%);
}
.chip-kepeg {
    display: inline-flex; align-items: center;
    font-size: var(--fs-2xs, 9px); font-weight: 600;
    color: var(--accent);
    background: oklch(52% 0.190 260 / 8%);
    border: 1px solid oklch(52% 0.190 260 / 18%);
    padding: 2px 9px; border-radius: var(--r-sm, 6px); white-space: nowrap;
}
.dark .chip-kepeg {
    color: var(--accent);
    background: oklch(63% 0.185 260 / 12%);
    border-color: oklch(63% 0.185 260 / 25%);
}
.chip-info {
    display: inline-flex; align-items: center;
    font-size: var(--fs-2xs, 9px); font-weight: 500;
    color: var(--text-2);
    background: oklch(68% 0.008 255 / 8%);
    border: 1px solid oklch(68% 0.008 255 / 14%);
    padding: 2px 9px; border-radius: var(--r-sm, 6px); white-space: nowrap;
}
.dark .chip-info {
    color: var(--text-3);
    background: oklch(40% 0.010 255 / 15%);
    border-color: oklch(40% 0.010 255 / 25%);
}

/* =============================================
   ROW BUTTONS
   ============================================= */
.btn-row-detail, .btn-row-edit {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 6px 12px;
    font-size: var(--fs-xs, 11px); font-weight: 600;
    border-radius: var(--r-sm, 6px);
    text-decoration: none;
    transition:
        background var(--dur-fast) var(--ease-out),
        transform var(--dur-fast) var(--ease-out),
        box-shadow var(--dur-mid) var(--ease-out);
    white-space: nowrap;
    border: 1px solid transparent;
}
.btn-row-detail {
    background: oklch(52% 0.190 260 / 7%);
    border-color: oklch(52% 0.190 260 / 16%);
    color: var(--accent);
}
.btn-row-detail:hover {
    background: oklch(52% 0.190 260 / 13%);
    border-color: oklch(52% 0.190 260 / 28%);
    transform: translateY(-1px);
}
.dark .btn-row-detail {
    background: oklch(63% 0.185 260 / 10%);
    border-color: oklch(63% 0.185 260 / 22%);
    color: var(--accent);
}
.dark .btn-row-detail:hover {
    background: oklch(63% 0.185 260 / 18%);
}
.btn-row-edit {
    background: var(--accent);
    border-color: transparent;
    color: var(--text-inv);
    box-shadow: 0 2px 8px oklch(52% 0.190 260 / 25%);
}
.btn-row-edit:hover {
    background: var(--accent-h);
    transform: translateY(-1px);
    box-shadow: 0 4px 16px oklch(52% 0.190 260 / 38%);
}

/* =============================================
   MOBILE CARD
   ============================================= */
.guru-card {
    transition:
        box-shadow var(--dur-mid) var(--ease-out),
        transform var(--dur-mid) var(--ease-out),
        border-color var(--dur-mid) var(--ease-out);
}
.guru-card:hover {
    transform: translateY(-2px);
}

.guru-card .card-top {
    padding: 15px 15px 9px;
    display: flex; gap: 13px; align-items: flex-start;
}
.card-info { flex: 1; min-width: 0; }
.card-name-row {
    display: flex; align-items: center;
    justify-content: space-between; gap: 6px;
    margin-bottom: 2px;
}
.card-nama {
    font-size: var(--fs-md, 15px); font-weight: 700;
    color: var(--text-1);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.card-username {
    font-size: var(--fs-xs, 11px); color: var(--text-3);
    font-family: 'Geist Mono', 'SF Mono', monospace;
    display: block; margin-bottom: 8px;
    letter-spacing: -0.01em;
}
.card-chips { display: flex; flex-wrap: wrap; gap: 5px; }
.guru-card .card-footer {
    padding: 0 12px 12px;
    display: flex; gap: 8px;
    border-top: 1px solid var(--row-divider);
    margin-top: 9px;
    padding-top: 10px;
}
.guru-card .btn-row-detail,
.guru-card .btn-row-edit {
    flex: 1;
    justify-content: center;
    padding: 9px;
}

/* =============================================
   EMPTY STATE
   ============================================= */
.empty-state {
    padding: 56px 24px;
    text-align: center;
    display: flex; flex-direction: column; align-items: center;
}
.empty-icon {
    width: 60px; height: 60px;
    border-radius: var(--r-lg, 14px);
    background: oklch(52% 0.190 260 / 6%);
    border: 1.5px solid var(--glass-border-a);
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 16px; color: var(--text-2);
}
.dark .empty-icon {
    background: oklch(63% 0.185 260 / 9%);
    border-color: oklch(63% 0.185 260 / 20%);
}
.empty-title { font-size: var(--fs-md, 15px); font-weight: 700; color: var(--text-1); margin: 0 0 5px; }
.empty-sub   { font-size: var(--fs-sm, 13px); color: var(--text-2); margin: 0; }

/* ── Pagination ── */
.pagination-wrap { display: flex; justify-content: center; }

/* =============================================
   RESPONSIVE
   ============================================= */
@media (max-width: 768px) {
    .guru-list  { display: none; }
    .guru-cards { display: flex; flex-direction: column; gap: 10px; }

    .filter-form  { flex-direction: column; align-items: stretch; }
    .filter-right { justify-content: space-between; }
    .page-header  { flex-direction: column; align-items: stretch; }
    .btn-primary  { justify-content: center; }

    .page-title { font-size: var(--fs-md, 15px); }
}

/* ── Reduced motion (WCAG) ── */
@media (prefers-reduced-motion: reduce) {
    .badge-aktif .dot,
    .chip-aktif .dot { animation: none; }
}
</style>
@endsection