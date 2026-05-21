{{-- resources/views/profile/edit.blade.php --}}
@extends('layouts.app')
@section('title', 'Profil Saya — PAUD KB Pelangi')
@section('page-title', 'Profil Saya')

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════════
   PROFILE PAGE — Glassmorphism purposeful, token dari app.blade
   Scene: Guru / Admin membuka profil di siang hari,
          sidebar biru terbuka, perlu feel "premium tapi ringan"
   Strategy: Surface utama solid, elemen aksen pakai glass layer
═══════════════════════════════════════════════════════════ */

/* ── Hero background ambient ── */
.prof-ambient {
    position: fixed;
    inset: 0;
    pointer-events: none;
    z-index: 0;
    overflow: hidden;
}
.prof-ambient::before {
    content: '';
    position: absolute;
    top: -120px; right: -80px;
    width: 560px; height: 560px;
    border-radius: 50%;
    background: radial-gradient(circle, oklch(52% 0.190 260 / 7%) 0%, transparent 70%);
}
.prof-ambient::after {
    content: '';
    position: absolute;
    bottom: -80px; left: -100px;
    width: 420px; height: 420px;
    border-radius: 50%;
    background: radial-gradient(circle, oklch(62% 0.200 290 / 5%) 0%, transparent 70%);
}
.dark .prof-ambient::before {
    background: radial-gradient(circle, oklch(63% 0.185 260 / 10%) 0%, transparent 70%);
}
.dark .prof-ambient::after {
    background: radial-gradient(circle, oklch(63% 0.185 290 / 8%) 0%, transparent 70%);
}

/* ── Layout ── */
.prof-wrap {
    position: relative;
    z-index: 1;
    max-width: 780px;
    margin: 0 auto;
    padding: 1.75rem 1rem 4rem;
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

/* ── Hero card — glass layer ini purposeful: identitas user ── */
.prof-hero {
    position: relative;
    border-radius: var(--r-xl);
    overflow: hidden;
    /* Glass effect */
    background: oklch(99% 0.004 260 / 72%);
    backdrop-filter: blur(20px) saturate(180%);
    -webkit-backdrop-filter: blur(20px) saturate(180%);
    border: 1px solid oklch(92% 0.012 260 / 70%);
    box-shadow:
        0 1px 0 oklch(100% 0 0 / 60%) inset,
        var(--shadow-md);
    padding: 1.75rem 1.75rem 1.5rem;
}
.dark .prof-hero {
    background: oklch(17% 0.014 260 / 75%);
    border-color: oklch(30% 0.015 260 / 50%);
    box-shadow:
        0 1px 0 oklch(100% 0 0 / 8%) inset,
        var(--shadow-md);
}

/* Stripe aksen tipis di atas hero */
.prof-hero::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg,
        oklch(52% 0.190 260),
        oklch(58% 0.200 290),
        oklch(52% 0.190 260)
    );
    background-size: 200% 100%;
    animation: shimmer-stripe 4s linear infinite;
}
@keyframes shimmer-stripe {
    0%   { background-position: 200% center; }
    100% { background-position: -200% center; }
}

/* ── Hero inner layout ── */
.hero-inner {
    display: flex;
    align-items: flex-start;
    gap: 1.5rem;
}

/* ── Avatar ── */
.avatar-ring {
    position: relative;
    flex-shrink: 0;
}
.avatar-ring::before {
    content: '';
    position: absolute;
    inset: -3px;
    border-radius: 50%;
    background: conic-gradient(
        oklch(52% 0.190 260),
        oklch(62% 0.200 290),
        oklch(52% 0.190 260)
    );
    animation: spin-ring 6s linear infinite;
}
@keyframes spin-ring {
    to { transform: rotate(360deg); }
}
@media (prefers-reduced-motion: reduce) {
    .avatar-ring::before { animation: none; }
    .prof-hero::before   { animation: none; background-position: 0 center; }
}

.avatar-img {
    position: relative;
    z-index: 1;
    width: 82px; height: 82px;
    border-radius: 50%;
    background: linear-gradient(135deg,
        oklch(52% 0.190 260),
        oklch(62% 0.200 290)
    );
    color: var(--text-inv);
    font-size: 1.7rem; font-weight: 800;
    letter-spacing: -.02em;
    display: flex; align-items: center; justify-content: center;
    overflow: hidden;
    border: 3px solid var(--surface);
}
.avatar-img img {
    width: 100%; height: 100%;
    object-fit: cover;
    border-radius: 50%;
}

/* Upload overlay */
.avatar-overlay {
    position: absolute;
    inset: 0; z-index: 2;
    border-radius: 50%;
    background: oklch(0% 0 0 / 50%);
    display: flex; align-items: center; justify-content: center;
    opacity: 0;
    transition: opacity var(--dur-fast) var(--ease-out);
    cursor: pointer;
}
.avatar-ring:hover .avatar-overlay { opacity: 1; }
.avatar-overlay svg { color: #fff; }

/* ── Hero meta ── */
.hero-meta {
    flex: 1;
    min-width: 0;
    padding-top: .25rem;
}
.hero-name {
    font-size: var(--fs-lg);
    font-weight: 700;
    color: var(--text-1);
    letter-spacing: -.02em;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-bottom: .35rem;
}
.hero-sub {
    font-size: var(--fs-sm);
    color: var(--text-3);
    margin-bottom: .75rem;
    display: flex;
    align-items: center;
    gap: 5px;
    font-weight: 500;
    letter-spacing: .01em;
}

.hero-tags {
    display: flex;
    flex-wrap: wrap;
    gap: .4rem;
}
.tag {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 3px 10px;
    border-radius: 99px;
    font-size: var(--fs-xs);
    font-weight: 600;
    border: 1px solid;
}
.tag-role {
    background: var(--accent-soft);
    color: var(--accent);
    border-color: var(--accent-ring);
}
.tag-active {
    background: var(--success-bg);
    color: var(--success);
    border-color: var(--success-border);
}
.tag-dot {
    width: 6px; height: 6px;
    border-radius: 50%;
    background: currentColor;
    animation: pulse-dot 2s ease-in-out infinite;
}
@keyframes pulse-dot {
    0%, 100% { opacity: 1; transform: scale(1); }
    50%       { opacity: .5; transform: scale(.75); }
}

/* Avatar action buttons di bawah */
.hero-foto-actions {
    display: flex;
    flex-wrap: wrap;
    gap: .5rem;
    margin-top: .75rem;
    padding-top: .75rem;
    border-top: 1px solid var(--border);
}

/* ── Section cards — solid, bukan glass ── */
.pcard {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--r-lg);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
    transition: box-shadow var(--dur-mid) var(--ease-out);
}
.pcard:focus-within {
    box-shadow: var(--shadow-md);
}

.pcard-header {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 14px 20px;
    border-bottom: 1px solid var(--border);
    background: var(--surface-2);
}
.pcard-icon {
    width: 30px; height: 30px;
    border-radius: var(--r-sm);
    background: var(--accent-soft);
    color: var(--accent);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.pcard-title {
    font-size: var(--fs-sm);
    font-weight: 700;
    color: var(--text-1);
    margin: 0;
}
.pcard-desc {
    font-size: var(--fs-xs);
    color: var(--text-3);
    margin: 0 0 0 auto;
}
.pcard-body { padding: 22px 20px; }

/* ── Alert / Flash ── */
.flash {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 11px 14px;
    border-radius: var(--r);
    font-size: var(--fs-sm);
    font-weight: 600;
    margin-bottom: 1.1rem;
    line-height: 1.45;
    animation: slide-in var(--dur-mid) var(--ease-out);
}
@keyframes slide-in {
    from { opacity: 0; transform: translateY(-6px); }
    to   { opacity: 1; transform: translateY(0); }
}
.flash svg { flex-shrink: 0; margin-top: 1px; }
.flash-success {
    background: var(--success-bg);
    border: 1px solid var(--success-border);
    color: var(--success);
}
.flash-error {
    background: var(--danger-bg);
    border: 1px solid var(--danger-border);
    color: var(--danger);
}

/* ── Field grid ── */
.field-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
}
.field-group {
    display: flex;
    flex-direction: column;
    gap: 5px;
}
.field-group.full { grid-column: 1 / -1; }

.field-label {
    font-size: var(--fs-xs);
    font-weight: 600;
    color: var(--text-2);
    letter-spacing: .03em;
    text-transform: uppercase;
}
.req { color: var(--danger); }

/* ── Input ── */
.input-wrap { position: relative; }
.input-wrap .ico {
    position: absolute;
    left: 11px; top: 50%; transform: translateY(-50%);
    color: var(--text-3);
    pointer-events: none;
    transition: color var(--dur-fast);
}
.input-wrap:focus-within .ico { color: var(--accent); }

.field-input {
    width: 100%;
    padding: 9.5px 12px 9.5px 36px;
    border: 1.5px solid var(--border);
    border-radius: var(--r);
    font-size: var(--fs-sm);
    color: var(--text-1);
    background: var(--bg);
    outline: none;
    font-family: inherit;
    transition:
        border-color var(--dur-fast) var(--ease-out),
        box-shadow   var(--dur-fast) var(--ease-out),
        background   var(--dur-fast);
    box-sizing: border-box;
}
.field-input:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px oklch(52% 0.190 260 / 12%);
    background: var(--surface);
}
.dark .field-input:focus {
    box-shadow: 0 0 0 3px oklch(63% 0.185 260 / 15%);
}
.field-input.is-error {
    border-color: var(--danger);
    box-shadow: 0 0 0 3px oklch(50% 0.210 27 / 10%);
}
.field-input:disabled {
    opacity: .55;
    cursor: not-allowed;
    background: var(--bg-2);
}
.field-input.no-icon { padding-left: 12px; }

.field-hint  { font-size: var(--fs-2xs); color: var(--text-3); margin: 0; }
.field-error { font-size: var(--fs-2xs); color: var(--danger); margin: 0; }

/* ── Select ── */
.field-select {
    width: 100%;
    padding: 9.5px 32px 9.5px 36px;
    border: 1.5px solid var(--border);
    border-radius: var(--r);
    font-size: var(--fs-sm);
    color: var(--text-1);
    background: var(--bg);
    outline: none;
    font-family: inherit;
    appearance: none;
    -webkit-appearance: none;
    transition: border-color var(--dur-fast), box-shadow var(--dur-fast);
    box-sizing: border-box;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 10px center;
}
.field-select:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px oklch(52% 0.190 260 / 12%);
    background-color: var(--surface);
}

/* Password input — toggle button */
.pw-toggle {
    position: absolute;
    right: 10px; top: 50%; transform: translateY(-50%);
    background: none; border: none; cursor: pointer;
    color: var(--text-3); padding: 3px;
    display: flex; align-items: center;
    transition: color var(--dur-fast);
}
.pw-toggle:hover { color: var(--accent); }
.field-input.has-toggle { padding-right: 36px; }

/* ── Password strength bar ── */
.pw-strength { margin-top: 6px; }
.pw-bars {
    display: flex; gap: 4px; margin-bottom: 5px;
}
.pw-bar {
    flex: 1; height: 3px; border-radius: 99px;
    background: var(--border-2);
    transition: background var(--dur-mid) var(--ease-out);
}
.pw-bar.weak   { background: var(--danger); }
.pw-bar.medium { background: var(--warning); }
.pw-bar.strong { background: var(--success); }
.pw-label {
    font-size: var(--fs-2xs);
    color: var(--text-3);
    transition: color var(--dur-fast);
}
.pw-label.weak   { color: var(--danger); }
.pw-label.medium { color: var(--warning); }
.pw-label.strong { color: var(--success); }

/* ── Form footer ── */
.form-footer {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 8px;
    margin-top: 20px;
    padding-top: 16px;
    border-top: 1px solid var(--border);
}

/* ── Buttons ── */
.btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8.5px 18px;
    border-radius: var(--r);
    font-size: var(--fs-sm); font-weight: 700;
    font-family: inherit; cursor: pointer;
    border: none;
    transition:
        background  var(--dur-fast) var(--ease-out),
        box-shadow  var(--dur-fast) var(--ease-out),
        transform   var(--dur-fast) var(--ease-out),
        opacity     var(--dur-fast);
}
.btn:disabled { opacity: .6; cursor: not-allowed; transform: none !important; }
.btn:active:not(:disabled) { transform: scale(.97); }

.btn-primary {
    background: var(--accent);
    color: var(--text-inv);
    box-shadow: 0 1px 3px oklch(52% 0.190 260 / 30%);
}
.btn-primary:hover:not(:disabled) {
    background: var(--accent-h);
    box-shadow: 0 3px 10px oklch(52% 0.190 260 / 30%);
    transform: translateY(-1px);
}

.btn-ghost {
    background: transparent;
    color: var(--text-2);
    border: 1.5px solid var(--border);
}
.btn-ghost:hover:not(:disabled) {
    border-color: var(--accent);
    color: var(--accent);
    background: var(--accent-soft);
}

.btn-danger {
    background: transparent;
    color: var(--danger);
    border: 1.5px solid oklch(50% 0.210 27 / 30%);
}
.btn-danger:hover:not(:disabled) {
    background: var(--danger-bg);
    border-color: var(--danger-border);
}

/* Loading spinner inside btn */
@keyframes spin { to { transform: rotate(360deg); } }
.btn-spinner {
    width: 13px; height: 13px;
    border: 2px solid currentColor;
    border-top-color: transparent;
    border-radius: 50%;
    animation: spin .6s linear infinite;
    opacity: .7;
}

/* ── Info baris (read-only detail) ── */
.info-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px 16px;
    padding: 14px 16px;
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: var(--r);
    margin-bottom: 14px;
}
@media (max-width: 700px) { .info-grid { grid-template-columns: 1fr 1fr; } }
@media (max-width: 440px) { .info-grid { grid-template-columns: 1fr; } }
.info-item { display: flex; flex-direction: column; gap: 3px; }
.info-key {
    font-size: var(--fs-2xs);
    font-weight: 600;
    color: var(--text-3);
    text-transform: uppercase;
    letter-spacing: .04em;
}
.info-val {
    font-size: var(--fs-sm);
    color: var(--text-1);
    font-weight: 500;
}
.info-val.mono { font-family: var(--font-mono, monospace); font-size: var(--fs-xs); }

/* ── Divider label ── */
.section-divider {
    display: flex; align-items: center; gap: 10px;
    margin: 18px 0 14px;
}
.section-divider span {
    font-size: var(--fs-xs);
    font-weight: 700;
    color: var(--text-3);
    text-transform: uppercase;
    letter-spacing: .06em;
    white-space: nowrap;
}
.section-divider::before,
.section-divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--border);
}

/* ── Responsive ── */
@media (max-width: 640px) {
    .field-grid { grid-template-columns: 1fr; }
    .hero-inner  { flex-direction: column; align-items: center; text-align: center; }
    .hero-tags   { justify-content: center; }
    .hero-foto-actions { justify-content: center; }
    .info-grid   { grid-template-columns: 1fr; }
    .pcard-body  { padding: 16px 14px; }
    .pcard-header { padding: 12px 14px; }
    .prof-wrap   { padding: 1rem .75rem 3rem; }
}
</style>
@endpush

@section('content')
{{-- Ambient background orbs --}}
<div class="prof-ambient" aria-hidden="true"></div>

<div class="prof-wrap" x-data="profilePage()">

    {{-- ═══════════════════════════════════════════════════════
         HERO CARD — Glass purposeful: identitas + foto profil
    ═══════════════════════════════════════════════════════════ --}}
    <div class="prof-hero">
        {{-- Form foto — submit otomatis saat file dipilih --}}
        <form id="foto-form" method="POST" action="{{ route('profile.update') }}"
              enctype="multipart/form-data" style="display:none;">
            @csrf
            <input type="file" id="foto-input" name="foto" accept="image/jpg,image/jpeg,image/png,image/webp"
                   @change="submitFotoForm">
        </form>

        <div class="hero-inner">
            {{-- Avatar --}}
            <div class="avatar-ring">
                <div class="avatar-img">
                    @if($user->foto)
                        <img src="{{ Storage::url($user->foto) }}"
                             alt="Foto {{ $user->nama_lengkap }}"
                             id="avatar-preview">
                    @else
                        <span id="avatar-initials">{{ strtoupper(substr($user->nama_lengkap, 0, 2)) }}</span>
                    @endif
                </div>
                {{-- Overlay upload saat hover --}}
                <label class="avatar-overlay" for="foto-input"
                       role="button" aria-label="Ganti foto profil"
                       title="Ganti foto profil">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="17 8 12 3 7 8"/>
                        <line x1="12" y1="3" x2="12" y2="15"/>
                    </svg>
                </label>
            </div>

            {{-- Meta --}}
            <div class="hero-meta">
                <div class="hero-name">{{ $user->nama_lengkap }}</div>
                <div class="hero-sub">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="opacity:.55;flex-shrink:0"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                    {{ $user->username }}
                </div>
                <div class="hero-tags">
                    @php
                        $roleLabel = match($user->role) {
                            'admin'          => 'Admin',
                            'guru'           => 'Guru',
                            'bendahara'      => 'Bendahara',
                            'kepala_sekolah' => 'Kepala Sekolah',
                            default          => ucwords(str_replace('_', ' ', $user->role)),
                        };
                    @endphp
                    <span class="tag tag-role">
                        <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        {{ $roleLabel }}
                    </span>
                    @if($user->aktif)
                        <span class="tag tag-active">
                            <span class="tag-dot"></span>Aktif
                        </span>
                    @endif
                    @if($user->label_status_kepegawaian !== '-')
                        <span class="tag tag-role" style="background:var(--accent-muted)">
                            {{ $user->label_status_kepegawaian }}
                        </span>
                    @endif
                    @if($user->jabatan)
                        <span class="tag" style="background:var(--bg-2);color:var(--text-2);border-color:var(--border-2);">
                            {{ $user->jabatan }}
                        </span>
                    @endif
                </div>

                {{-- Tombol aksi foto --}}
                <div class="hero-foto-actions">
                    <label for="foto-input" class="btn btn-ghost" style="cursor:pointer; padding:7px 14px;">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                        Ganti Foto
                    </label>
                    @if($user->foto)
                    <form method="POST" action="{{ route('profile.foto.hapus') }}"
                          onsubmit="return confirm('Hapus foto profil?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger" style="padding:7px 14px;">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                            Hapus Foto
                        </button>
                    </form>
                    @endif
                    <span style="font-size:var(--fs-2xs);color:var(--text-3);align-self:center;">
                        JPG, PNG, WEBP · maks 2 MB
                    </span>
                </div>

                @error('foto')
                    <p class="field-error" style="margin-top:6px;">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         KARTU 1 — Informasi Akun (read-only)
    ═══════════════════════════════════════════════════════════ --}}
    <div class="pcard">
        <div class="pcard-header">
            <div class="pcard-icon">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
            </div>
            <h2 class="pcard-title">Informasi Akun</h2>
            <span class="pcard-desc">Dikelola oleh admin</span>
        </div>
        <div class="pcard-body">
            @php
                $roleLabel = match($user->role) {
                    'admin'          => 'Admin',
                    'guru'           => 'Guru',
                    'bendahara'      => 'Bendahara',
                    'kepala_sekolah' => 'Kepala Sekolah',
                    default          => ucwords(str_replace('_', ' ', $user->role)),
                };
            @endphp
            <div class="info-grid">

                {{-- Selalu tampil --}}
                <div class="info-item">
                    <span class="info-key">Username</span>
                    <span class="info-val mono">{{ $user->username }}</span>
                </div>
                <div class="info-item">
                    <span class="info-key">Role</span>
                    <span class="info-val">
                        <span class="tag tag-role" style="font-size:var(--fs-2xs);padding:2px 9px;">
                            {{ $roleLabel }}
                        </span>
                    </span>
                </div>

                @if($user->nik)
                <div class="info-item">
                    <span class="info-key">NIK</span>
                    <span class="info-val mono">{{ $user->nik }}</span>
                </div>
                @endif

                @if($user->nip)
                <div class="info-item">
                    <span class="info-key">NIP</span>
                    <span class="info-val mono">{{ $user->nip }}</span>
                </div>
                @endif

                @if($user->nuptk)
                <div class="info-item">
                    <span class="info-key">NUPTK</span>
                    <span class="info-val mono">{{ $user->nuptk }}</span>
                </div>
                @endif

                @if($user->jabatan)
                <div class="info-item">
                    <span class="info-key">Jabatan</span>
                    <span class="info-val">{{ $user->jabatan }}</span>
                </div>
                @endif

                @if($user->status_kepegawaian)
                <div class="info-item">
                    <span class="info-key">Status Kepegawaian</span>
                    <span class="info-val">{{ $user->label_status_kepegawaian }}</span>
                </div>
                @endif

                @if($user->pendidikan_terakhir)
                <div class="info-item">
                    <span class="info-key">Pendidikan Terakhir</span>
                    <span class="info-val">
                        {{ strtoupper($user->pendidikan_terakhir) }}
                        @if($user->jurusan)
                            <span style="color:var(--text-3);font-weight:400"> — {{ $user->jurusan }}</span>
                        @endif
                    </span>
                </div>
                @endif

                @if($user->jenis_kelamin)
                <div class="info-item">
                    <span class="info-key">Jenis Kelamin</span>
                    <span class="info-val">
                        {{ $user->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                    </span>
                </div>
                @endif

                @if($user->tanggal_lahir)
                <div class="info-item">
                    <span class="info-key">Tempat / Tgl Lahir</span>
                    <span class="info-val">
                        @if($user->tempat_lahir){{ $user->tempat_lahir }}, @endif
                        {{ $user->tanggal_lahir->translatedFormat('d F Y') }}
                        <span style="color:var(--text-3);font-weight:400">({{ $user->umur }} tahun)</span>
                    </span>
                </div>
                @endif

                @if($user->agama)
                <div class="info-item">
                    <span class="info-key">Agama</span>
                    <span class="info-val">{{ ucfirst($user->agama) }}</span>
                </div>
                @endif

                @if($user->tanggal_bergabung)
                <div class="info-item">
                    <span class="info-key">Bergabung Sejak</span>
                    <span class="info-val">{{ $user->tanggal_bergabung->translatedFormat('d F Y') }}</span>
                </div>
                @endif

                <div class="info-item">
                    <span class="info-key">Status Akun</span>
                    <span class="info-val">
                        @if($user->aktif)
                            <span class="tag tag-active" style="font-size:var(--fs-2xs);padding:2px 9px;">
                                <span class="tag-dot"></span>Aktif
                            </span>
                        @else
                            <span class="tag" style="font-size:var(--fs-2xs);padding:2px 9px;background:var(--danger-bg);color:var(--danger);border:1px solid var(--danger-border);">
                                Nonaktif
                            </span>
                        @endif
                    </span>
                </div>

            </div>

            @if($user->alamat)
            <div class="info-item" style="margin-top:10px;padding:11px 14px;background:var(--bg);border:1px solid var(--border);border-radius:var(--r);">
                <span class="info-key" style="display:block;margin-bottom:4px;">Alamat</span>
                <span class="info-val" style="font-size:var(--fs-sm);line-height:1.5;font-weight:400;color:var(--text-2);">{{ $user->alamat }}</span>
            </div>
            @endif
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         KARTU 2 — Edit Profil
    ═══════════════════════════════════════════════════════════ --}}
    <div class="pcard">
        <div class="pcard-header">
            <div class="pcard-icon">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
            <h2 class="pcard-title">Informasi Profil</h2>
        </div>
        <div class="pcard-body">

            @if(session('success'))
                <div class="flash flash-success" role="alert">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}"
                  enctype="multipart/form-data"
                  x-data="{ dirty: false }"
                  @change="dirty = true"
                  @input="dirty = true">
                @csrf

                <div class="field-grid">
                    {{-- Nama Lengkap --}}
                    <div class="field-group full">
                        <label class="field-label" for="nama_lengkap">
                            Nama Lengkap <span class="req">*</span>
                        </label>
                        <div class="input-wrap">
                            <svg class="ico" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            <input type="text" id="nama_lengkap" name="nama_lengkap"
                                   class="field-input @error('nama_lengkap') is-error @enderror"
                                   value="{{ old('nama_lengkap', $user->nama_lengkap) }}"
                                   placeholder="Nama lengkap Anda"
                                   required>
                        </div>
                        @error('nama_lengkap')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="field-group">
                        <label class="field-label" for="email">Email</label>
                        <div class="input-wrap">
                            <svg class="ico" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            <input type="email" id="email" name="email"
                                   class="field-input @error('email') is-error @enderror"
                                   value="{{ old('email', $user->email) }}"
                                   placeholder="email@contoh.com"
                                   autocomplete="email">
                        </div>
                        @error('email')
                            <p class="field-error">{{ $message }}</p>
                        @else
                            <p class="field-hint">Digunakan untuk reset password.</p>
                        @enderror
                    </div>

                    {{-- No HP --}}
                    <div class="field-group">
                        <label class="field-label" for="no_hp">No. HP / WhatsApp</label>
                        <div class="input-wrap">
                            <svg class="ico" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 10a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.62 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            <input type="text" id="no_hp" name="no_hp"
                                   class="field-input @error('no_hp') is-error @enderror"
                                   value="{{ old('no_hp', $user->no_hp) }}"
                                   placeholder="08xx-xxxx-xxxx"
                                   inputmode="tel">
                        </div>
                        @error('no_hp')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="form-footer">
                    <button type="reset" class="btn btn-ghost"
                            @click="dirty = false"
                            x-show="dirty"
                            x-transition.opacity>
                        Batalkan
                    </button>
                    <button type="submit" class="btn btn-primary"
                            x-ref="saveBtn"
                            :disabled="!dirty"
                            :class="{ 'btn-primary': dirty }"
                            style="min-width:130px;">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         KARTU 3 — Ganti Password
    ═══════════════════════════════════════════════════════════ --}}
    <div class="pcard" x-data="passwordForm()">
        <div class="pcard-header">
            <div class="pcard-icon">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            </div>
            <h2 class="pcard-title">Ganti Password</h2>
        </div>
        <div class="pcard-body">

            @if(session('success_password'))
                <div class="flash flash-success" role="alert">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    {{ session('success_password') }}
                </div>
            @endif

            <form method="POST" action="{{ route('profile.password') }}" @submit="loading = true">
                @csrf

                <div class="field-grid">
                    {{-- Password Lama --}}
                    <div class="field-group full">
                        <label class="field-label" for="password_lama">
                            Password Saat Ini <span class="req">*</span>
                        </label>
                        <div class="input-wrap">
                            <svg class="ico" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            <input :type="showOld ? 'text' : 'password'"
                                   id="password_lama" name="password_lama"
                                   class="field-input has-toggle @error('password_lama') is-error @enderror"
                                   placeholder="Masukkan password saat ini"
                                   autocomplete="current-password">
                            <button type="button" class="pw-toggle"
                                    @click="showOld = !showOld"
                                    :aria-label="showOld ? 'Sembunyikan' : 'Tampilkan'">
                                <svg x-show="!showOld" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                <svg x-show="showOld" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                            </button>
                        </div>
                        @error('password_lama')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="section-divider" style="grid-column:1/-1;">
                        <span>Password Baru</span>
                    </div>

                    {{-- Password Baru --}}
                    <div class="field-group">
                        <label class="field-label" for="pw-new">
                            Password Baru <span class="req">*</span>
                        </label>
                        <div class="input-wrap">
                            <svg class="ico" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            <input :type="showNew ? 'text' : 'password'"
                                   id="pw-new" name="password"
                                   class="field-input has-toggle @error('password') is-error @enderror"
                                   placeholder="Min. 8 karakter + angka"
                                   autocomplete="new-password"
                                   @input="checkStrength($event.target.value)">
                            <button type="button" class="pw-toggle"
                                    @click="showNew = !showNew"
                                    :aria-label="showNew ? 'Sembunyikan' : 'Tampilkan'">
                                <svg x-show="!showNew" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                <svg x-show="showNew" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                            </button>
                        </div>
                        <div class="pw-strength">
                            <div class="pw-bars">
                                <div class="pw-bar" :class="score >= 1 ? strengthCls : ''"></div>
                                <div class="pw-bar" :class="score >= 2 ? strengthCls : ''"></div>
                                <div class="pw-bar" :class="score >= 3 ? strengthCls : ''"></div>
                                <div class="pw-bar" :class="score >= 4 ? strengthCls : ''"></div>
                            </div>
                            <p class="pw-label" :class="strengthCls" x-text="strengthLabel"></p>
                        </div>
                        @error('password')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Konfirmasi --}}
                    <div class="field-group">
                        <label class="field-label" for="pw-conf">
                            Konfirmasi Password <span class="req">*</span>
                        </label>
                        <div class="input-wrap">
                            <svg class="ico" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            <input :type="showConf ? 'text' : 'password'"
                                   id="pw-conf" name="password_confirmation"
                                   class="field-input has-toggle"
                                   :class="confMismatch ? 'is-error' : ''"
                                   placeholder="Ulangi password baru"
                                   autocomplete="new-password"
                                   @input="checkMatch($event.target.value)">
                            <button type="button" class="pw-toggle"
                                    @click="showConf = !showConf"
                                    :aria-label="showConf ? 'Sembunyikan' : 'Tampilkan'">
                                <svg x-show="!showConf" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                <svg x-show="showConf" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                            </button>
                        </div>
                        <p class="field-error" x-show="confMismatch" style="display:none">
                            Password tidak cocok.
                        </p>
                    </div>
                </div>

                <div class="form-footer">
                    <button type="submit" class="btn btn-primary"
                            :disabled="loading || confMismatch || score < 2"
                            style="min-width:140px;">
                        <template x-if="!loading">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        </template>
                        <template x-if="loading">
                            <div class="btn-spinner"></div>
                        </template>
                        <span x-text="loading ? 'Menyimpan...' : 'Ubah Password'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function profilePage() {
    return {
        submitFotoForm() {
            // Preview instan sebelum submit
            const input = document.getElementById('foto-input');
            if (!input.files || !input.files[0]) return;
            const file = input.files[0];
            if (!['image/jpeg','image/png','image/webp'].includes(file.type)) {
                alert('Format tidak didukung. Gunakan JPG, PNG, atau WEBP.');
                input.value = '';
                return;
            }
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file melebihi 2 MB.');
                input.value = '';
                return;
            }
            // Preview sementara
            const reader = new FileReader();
            reader.onload = (e) => {
                const el = document.querySelector('.avatar-img');
                el.innerHTML = `<img src="${e.target.result}" alt="Preview" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">`;
            };
            reader.readAsDataURL(file);
            // Submit form
            document.getElementById('foto-form').submit();
        }
    }
}

function passwordForm() {
    return {
        loading:       false,
        showOld:       false,
        showNew:       false,
        showConf:      false,
        score:         0,
        strengthCls:   '',
        strengthLabel: 'Minimal 8 karakter, mengandung huruf & angka',
        confMismatch:  false,
        _newVal:       '',

        checkStrength(val) {
            this._newVal = val;
            let s = 0;
            if (val.length >= 8)          s++;
            if (/[A-Z]/.test(val))         s++;
            if (/[0-9]/.test(val))         s++;
            if (/[^A-Za-z0-9]/.test(val)) s++;
            this.score = s;

            if (!val) {
                this.strengthCls   = '';
                this.strengthLabel = 'Minimal 8 karakter, mengandung huruf & angka';
            } else if (s <= 1) {
                this.strengthCls   = 'weak';
                this.strengthLabel = 'Lemah — tambahkan angka atau huruf besar';
            } else if (s === 2) {
                this.strengthCls   = 'medium';
                this.strengthLabel = 'Sedang — bisa lebih kuat';
            } else {
                this.strengthCls   = 'strong';
                this.strengthLabel = s === 3 ? 'Kuat' : 'Sangat Kuat';
            }
        },

        checkMatch(val) {
            this.confMismatch = val.length > 0 && val !== this._newVal;
        }
    }
}
</script>
@endpush