<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Guru — PAUD KB Pelangi</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<style>
/* ═══════════════════════════════════════════════════════════
   LOGIN GURU — Redesign total
   Scene: Guru membuka portal absensi pagi hari dari HP/laptop
   Palette: OKLCH biru hangat, token konsisten dengan app.blade
   Layout: Panel kiri — solid biru gelap + branding bersih
           Panel kanan — form PIN dengan numpad
═══════════════════════════════════════════════════════════ */

:root {
    /* ── Token dari app.blade ── */
    --a:        oklch(52% 0.190 260);
    --a-h:      oklch(46% 0.200 262);
    --a-soft:   oklch(96% 0.025 260);
    --a-ring:   oklch(88% 0.055 260);

    --bg:       oklch(96.0% 0.006 250);
    --surface:  oklch(99.5% 0.003 250);
    --border:   oklch(90.0% 0.007 250);
    --border-2: oklch(85.0% 0.009 250);

    --t1: oklch(14% 0.008 260);
    --t2: oklch(46% 0.012 255);
    --t3: oklch(68% 0.008 255);
    --ti: oklch(99% 0.003 250);

    --danger:    oklch(50% 0.210 27);
    --danger-bg: oklch(98% 0.025 27);
    --danger-bd: oklch(88% 0.075 27);
    --success:   oklch(46% 0.150 155);

    --sh-sm: 0 1px 3px oklch(0% 0 0 / 8%), 0 1px 2px oklch(0% 0 0 / 4%);
    --sh-md: 0 4px 12px oklch(0% 0 0 / 10%), 0 2px 4px oklch(0% 0 0 / 5%);
    --sh-lg: 0 12px 32px oklch(0% 0 0 / 14%), 0 4px 10px oklch(0% 0 0 / 6%);

    --r:    10px;
    --r-lg: 14px;
    --r-xl: 20px;
    --ease: cubic-bezier(0.22, 1, 0.36, 1);

    /* ── Panel kiri warna ── */
    --left-bg:   oklch(20% 0.040 260);
    --left-bg2:  oklch(16% 0.035 260);
    --left-glow: oklch(52% 0.190 260 / 22%);
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html, body { height: 100%; }

body {
    font-family: 'Geist', system-ui, sans-serif;
    background: var(--bg);
    color: var(--t1);
    -webkit-font-smoothing: antialiased;
    min-height: 100vh;
}

/* ══════════════════════════════════
   PAGE GRID — true 50/50 split
══════════════════════════════════ */
.page {
    min-height: 100vh;
    display: grid;
    grid-template-columns: 42% 1fr;
}

/* ══════════════════════════════════
   LEFT — Branding panel
══════════════════════════════════ */
.left {
    position: relative;
    background: var(--left-bg);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 48px 52px;
    overflow: hidden;
}

/* ── Ambient glow mesh ── */
.left::before {
    content: '';
    position: absolute;
    top: -160px; right: -160px;
    width: 480px; height: 480px;
    border-radius: 50%;
    background: radial-gradient(circle, var(--left-glow) 0%, transparent 68%);
    pointer-events: none;
}
.left::after {
    content: '';
    position: absolute;
    bottom: -100px; left: -80px;
    width: 340px; height: 340px;
    border-radius: 50%;
    background: radial-gradient(circle, oklch(62% 0.200 290 / 12%) 0%, transparent 68%);
    pointer-events: none;
}

/* ── Dot grid texture ── */
.left-dots {
    position: absolute;
    inset: 0;
    pointer-events: none;
    background-image: radial-gradient(
        oklch(100% 0 0 / 5%) 1px,
        transparent 1px
    );
    background-size: 24px 24px;
}

/* ── Logo area ── */
.left-logo {
    position: relative;
    z-index: 1;
    display: flex;
    align-items: center;
    gap: 12px;
}
.logo-mark {
    width: 42px; height: 42px;
    border-radius: 12px;
    background: oklch(100% 0 0 / 12%);
    border: 1px solid oklch(100% 0 0 / 16%);
    backdrop-filter: blur(8px);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    transition: background 0.2s;
}
.logo-mark svg { color: oklch(88% 0.060 260); }
.logo-text { display: flex; flex-direction: column; gap: 1px; }
.logo-name {
    font-size: 15px; font-weight: 700;
    color: oklch(95% 0.012 260);
    letter-spacing: -.02em;
    line-height: 1;
}
.logo-sub {
    font-size: 11px; font-weight: 500;
    color: oklch(60% 0.025 260);
    letter-spacing: .03em;
}

/* ── Main hero area ── */
.left-body {
    position: relative;
    z-index: 1;
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 40px 0;
}

/* Portal badge */
.portal-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 14px 6px 8px;
    background: oklch(100% 0 0 / 8%);
    border: 1px solid oklch(100% 0 0 / 14%);
    border-radius: 99px;
    width: fit-content;
    margin-bottom: 28px;
    backdrop-filter: blur(8px);
}
.badge-dot-wrap {
    width: 24px; height: 24px;
    border-radius: 50%;
    background: var(--a);
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 0 0 4px oklch(52% 0.190 260 / 25%);
    flex-shrink: 0;
}
.badge-dot-wrap svg { color: #fff; }
.badge-label {
    font-size: 12px; font-weight: 700;
    color: oklch(78% 0.060 260);
    letter-spacing: .06em;
    text-transform: uppercase;
}

/* Headline */
.left-headline {
    font-size: clamp(2rem, 3.5vw, 2.8rem);
    font-weight: 900;
    line-height: 1.1;
    letter-spacing: -.04em;
    color: oklch(96% 0.010 260);
    margin-bottom: 16px;
}
.left-headline em {
    font-style: normal;
    color: var(--a);
    /* No gradient-text per design rules */
}

.left-desc {
    font-size: 14.5px;
    color: oklch(58% 0.022 260);
    line-height: 1.7;
    max-width: 290px;
    margin-bottom: 40px;
}

/* Feature pills */
.feat-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.feat-item {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 13px;
    color: oklch(65% 0.025 260);
}
.feat-icon {
    width: 28px; height: 28px;
    border-radius: 8px;
    background: oklch(100% 0 0 / 7%);
    border: 1px solid oklch(100% 0 0 / 10%);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.feat-icon svg { color: oklch(70% 0.100 260); }
.feat-item strong {
    color: oklch(80% 0.030 260);
    font-weight: 600;
}

/* ── Left footer ── */
.left-foot {
    position: relative;
    z-index: 1;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.left-foot-copy {
    font-size: 11.5px;
    color: oklch(38% 0.018 260);
}
.status-pill {
    display: flex; align-items: center; gap: 5px;
    padding: 4px 10px;
    background: oklch(100% 0 0 / 6%);
    border: 1px solid oklch(100% 0 0 / 10%);
    border-radius: 99px;
}
.status-dot {
    width: 6px; height: 6px;
    border-radius: 50%;
    background: var(--success);
    animation: pulse-dot 2.4s ease-in-out infinite;
}
@keyframes pulse-dot {
    0%, 100% { opacity: 1; transform: scale(1); }
    50%       { opacity: .5; transform: scale(.75); }
}
.status-txt {
    font-size: 10.5px; font-weight: 600;
    color: oklch(60% 0.025 260);
}

/* ══════════════════════════════════
   RIGHT — Form panel
══════════════════════════════════ */
.right {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 48px 40px;
    background: var(--surface);
    position: relative;
}

/* Subtle inner shadow from left */
.right::before {
    content: '';
    position: absolute;
    top: 0; left: 0;
    width: 1px; height: 100%;
    background: linear-gradient(
        to bottom,
        transparent,
        var(--border) 20%,
        var(--border) 80%,
        transparent
    );
}

.form-wrap {
    width: 100%;
    max-width: 360px;
    animation: fade-up 0.4s var(--ease) both;
}
@keyframes fade-up {
    from { opacity: 0; transform: translateY(12px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ── Form header ── */
.form-header { margin-bottom: 28px; }

.form-eyebrow {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 11px; font-weight: 700;
    color: var(--a);
    text-transform: uppercase; letter-spacing: .08em;
    margin-bottom: 8px;
}
.form-eyebrow::before {
    content: '';
    width: 16px; height: 2px;
    background: var(--a);
    border-radius: 2px;
    display: block;
}
.form-title {
    font-size: 22px; font-weight: 800;
    color: var(--t1);
    letter-spacing: -.03em;
    margin-bottom: 5px;
    line-height: 1.2;
}
.form-sub {
    font-size: 13px; color: var(--t3);
    line-height: 1.5;
}

/* ── Error alert ── */
.alert {
    display: flex; align-items: flex-start; gap: 9px;
    padding: 11px 13px;
    background: var(--danger-bg);
    border: 1px solid var(--danger-bd);
    border-radius: var(--r);
    color: var(--danger);
    font-size: 13px; font-weight: 600;
    margin-bottom: 18px;
    line-height: 1.45;
    animation: slide-in 0.22s var(--ease);
}
@keyframes slide-in {
    from { opacity: 0; transform: translateY(-6px); }
    to   { opacity: 1; transform: translateY(0); }
}
.alert svg { flex-shrink: 0; margin-top: 1px; }

/* ── Username field ── */
.field { margin-bottom: 20px; }
.field-label {
    display: block;
    font-size: 12px; font-weight: 700;
    color: var(--t2);
    text-transform: uppercase; letter-spacing: .05em;
    margin-bottom: 6px;
}
.field-wrap { position: relative; }
.field-ico {
    position: absolute; left: 11px; top: 50%; transform: translateY(-50%);
    color: var(--t3); pointer-events: none;
    transition: color 0.14s;
}
.field-wrap:focus-within .field-ico { color: var(--a); }
.field-input {
    width: 100%;
    padding: 10px 13px 10px 36px;
    font-family: 'Geist', sans-serif;
    font-size: 14px; font-weight: 500;
    color: var(--t1);
    background: var(--bg);
    border: 1.5px solid var(--border);
    border-radius: var(--r);
    outline: none;
    transition: border-color 0.14s, box-shadow 0.14s, background 0.14s;
}
.field-input::placeholder { color: oklch(76% 0.006 250); }
.field-input:focus {
    border-color: var(--a);
    background: var(--surface);
    box-shadow: 0 0 0 3px oklch(52% 0.190 260 / 12%);
}

/* ── PIN section ── */
.pin-section { margin-bottom: 16px; }
.pin-label-row {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 14px;
}
.pin-label {
    font-size: 12px; font-weight: 700;
    color: var(--t2);
    text-transform: uppercase; letter-spacing: .05em;
}
.pin-clear {
    font-size: 11.5px; font-weight: 600;
    color: var(--t3); background: none; border: none;
    cursor: pointer; padding: 0; font-family: inherit;
    display: flex; align-items: center; gap: 4px;
    transition: color 0.12s;
}
.pin-clear:hover { color: var(--danger); }

/* PIN dots */
.pin-dots {
    display: flex; justify-content: center;
    gap: 10px; margin-bottom: 20px;
}
.pin-dot-box {
    width: 56px; height: 60px;
    border-radius: var(--r-lg);
    border: 1.5px solid var(--border);
    background: var(--bg);
    display: flex; align-items: center; justify-content: center;
    transition:
        border-color 0.18s var(--ease),
        background   0.18s var(--ease),
        box-shadow   0.18s var(--ease),
        transform    0.15s var(--ease);
    position: relative;
    overflow: hidden;
}
/* Shimmer on filled */
.pin-dot-box::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg,
        oklch(52% 0.190 260 / 0%) 0%,
        oklch(52% 0.190 260 / 6%) 50%,
        oklch(52% 0.190 260 / 0%) 100%
    );
    opacity: 0;
    transition: opacity 0.2s;
}
.pin-dot-box.filled {
    border-color: var(--a);
    background: var(--a-soft);
    box-shadow: 0 0 0 3px oklch(52% 0.190 260 / 11%);
    transform: scale(1.05);
}
.pin-dot-box.filled::after { opacity: 1; }
.pin-dot-box.shake { animation: shake 0.38s var(--ease); }
@keyframes shake {
    0%,100% { transform: translateX(0) scale(1.05); }
    20%      { transform: translateX(-6px) scale(1.05); }
    40%      { transform: translateX(6px) scale(1.05); }
    60%      { transform: translateX(-3px) scale(1.05); }
    80%      { transform: translateX(3px) scale(1.05); }
}
.pin-dot {
    width: 11px; height: 11px;
    border-radius: 50%;
    background: transparent;
    transition: background 0.18s var(--ease), transform 0.2s var(--ease);
    transform: scale(0);
}
.pin-dot.visible {
    background: var(--a);
    transform: scale(1);
    box-shadow: 0 2px 8px oklch(52% 0.190 260 / 40%);
}

/* ── Numpad ── */
.numpad {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 8px;
    margin-bottom: 18px;
}
.numpad-key {
    position: relative; overflow: hidden;
    height: 58px;
    border-radius: var(--r-lg);
    border: 1.5px solid var(--border);
    background: var(--surface);
    font-family: 'Geist', sans-serif;
    font-weight: 600; font-size: 20px;
    color: var(--t1); cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition:
        background    0.12s var(--ease),
        border-color  0.12s var(--ease),
        color         0.12s var(--ease),
        transform     0.10s var(--ease),
        box-shadow    0.12s var(--ease);
    user-select: none;
    -webkit-tap-highlight-color: transparent;
    box-shadow: var(--sh-sm);
}
.numpad-key:hover:not(:disabled) {
    background: var(--a-soft);
    border-color: var(--a-ring);
    color: var(--a);
    box-shadow: 0 4px 14px oklch(52% 0.190 260 / 12%);
}
.numpad-key:active:not(:disabled) {
    transform: scale(0.91);
    background: oklch(92% 0.040 260);
    box-shadow: 0 0 0 3px oklch(52% 0.190 260 / 14%);
}
.numpad-key:disabled {
    opacity: .22; cursor: not-allowed;
}

/* Delete key */
.numpad-key.key-del {
    color: var(--t3);
    font-size: 16px;
}
.numpad-key.key-del:hover:not(:disabled) {
    background: var(--danger-bg);
    border-color: var(--danger-bd);
    color: var(--danger);
    box-shadow: none;
}

/* Empty slot */
.numpad-key.key-empty {
    background: transparent;
    border-color: transparent;
    box-shadow: none;
    cursor: default; pointer-events: none;
}

/* Ripple */
.numpad-key::after {
    content: '';
    position: absolute; inset: 0;
    border-radius: inherit;
    background: oklch(52% 0.190 260 / 14%);
    opacity: 0; transform: scale(0.4);
    transition: opacity 0.28s, transform 0.3s;
}
.numpad-key.ripple::after {
    opacity: 1; transform: scale(1);
}

/* ── Submit button ── */
.btn-submit {
    position: relative; overflow: hidden;
    width: 100%;
    padding: 12.5px;
    background: var(--a);
    border: none;
    border-radius: var(--r-lg);
    font-family: 'Geist', sans-serif;
    font-size: 14px; font-weight: 800;
    color: #fff; cursor: pointer;
    letter-spacing: -.01em;
    transition:
        background  0.14s var(--ease),
        opacity     0.14s,
        transform   0.12s var(--ease),
        box-shadow  0.18s var(--ease);
    box-shadow:
        0 4px 20px oklch(52% 0.190 260 / 35%),
        0 1px 0 oklch(100% 0 0 / 18%) inset;
    margin-bottom: 10px;
}
.btn-submit:hover:not(:disabled) {
    background: var(--a-h);
    transform: translateY(-1.5px);
    box-shadow:
        0 8px 28px oklch(52% 0.190 260 / 40%),
        0 1px 0 oklch(100% 0 0 / 18%) inset;
}
.btn-submit:active:not(:disabled) { transform: scale(0.98); }
.btn-submit:disabled {
    opacity: .30; cursor: not-allowed; transform: none;
    box-shadow: none;
}

/* Shimmer sweep */
.btn-submit::before {
    content: '';
    position: absolute; top: 0; left: -100%;
    width: 50%; height: 100%;
    background: linear-gradient(90deg,
        transparent,
        oklch(100% 0 0 / 18%),
        transparent
    );
    transform: skewX(-18deg);
    transition: left 0.55s ease;
}
.btn-submit:hover:not(:disabled)::before { left: 160%; }

.btn-lbl {
    display: flex; align-items: center;
    justify-content: center; gap: 8px;
    transition: opacity 0.12s;
}
.btn-spin {
    opacity: 0; position: absolute; inset: 0;
    display: flex; align-items: center; justify-content: center;
    transition: opacity 0.12s;
}
.is-loading .btn-lbl  { opacity: 0; }
.is-loading .btn-spin { opacity: 1; }
.is-loading { pointer-events: none; }
@keyframes spin-anim { to { transform: rotate(360deg); } }
.spin-svg { animation: spin-anim 0.75s linear infinite; }

/* ── Back link ── */
.back-link {
    display: flex; align-items: center;
    justify-content: center; gap: 6px;
    padding: 10px 14px;
    background: var(--bg);
    border: 1.5px solid var(--border);
    border-radius: var(--r-lg);
    font-size: 13px; font-weight: 600;
    color: var(--t2); text-decoration: none;
    font-family: 'Geist', sans-serif;
    transition:
        background    0.13s,
        border-color  0.13s,
        color         0.13s;
}
.back-link:hover {
    background: var(--a-soft);
    border-color: var(--a-ring);
    color: var(--a);
}

/* ── Form footer ── */
.form-footer {
    margin-top: 28px;
    padding-top: 18px;
    border-top: 1px solid var(--border);
    display: flex; align-items: center;
    justify-content: space-between;
}
.foot-copy {
    font-size: 11.5px; color: oklch(78% 0.006 250);
}

/* ══════════════════════════════════
   RESPONSIVE
══════════════════════════════════ */
@media (max-width: 820px) {
    .page { grid-template-columns: 1fr; }
    .left { display: none; }
    .right {
        min-height: 100vh;
        padding: 40px 24px;
        background: var(--bg);
    }
    .right::before { display: none; }
}

@media (max-width: 400px) {
    .pin-dot-box { width: 48px; height: 52px; }
    .numpad-key  { height: 52px; font-size: 18px; }
}

/* Reduced motion */
@media (prefers-reduced-motion: reduce) {
    .form-wrap        { animation: none; }
    .pin-dot-box      { transition: none; }
    .numpad-key       { transition: none; }
    .btn-submit       { transition: none; }
    .btn-submit::before { display: none; }
    .status-dot       { animation: none; }
    .pin-dot-box.shake { animation: none; }
}
</style>
</head>

<body>
<div class="page">

    {{-- ════════════════════════════════
         LEFT — Branding panel
    ════════════════════════════════ --}}
    <div class="left" aria-hidden="true">
        <div class="left-dots"></div>

        {{-- Logo --}}
        <div class="left-logo">
            <div class="logo-mark">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
            </div>
            <div class="logo-text">
                <span class="logo-name">PAUD KB Pelangi</span>
                <span class="logo-sub">Sistem Informasi Sekolah</span>
            </div>
        </div>

        {{-- Hero --}}
        <div class="left-body">

            <div class="portal-badge">
                <div class="badge-dot-wrap">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                </div>
                <span class="badge-label">Portal Tenaga Pengajar</span>
            </div>

            <h1 class="left-headline">
                Selamat<br>
                Datang,<br>
                <em>Guru.</em>
            </h1>

            <p class="left-desc">
                Masuk dengan username dan PIN untuk mengakses portal absensi dan laporan kehadiran Anda.
            </p>

            <div class="feat-list">
                <div class="feat-item">
                    <div class="feat-icon">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                    </div>
                    <span><strong>Absensi</strong> harian guru</span>
                </div>
                <div class="feat-item">
                    <div class="feat-icon">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                    </div>
                    <span><strong>Riwayat</strong> kehadiran bulanan</span>
                </div>
                <div class="feat-item">
                    <div class="feat-icon">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                            <line x1="16" y1="13" x2="8" y2="13"/>
                            <line x1="16" y1="17" x2="8" y2="17"/>
                        </svg>
                    </div>
                    <span><strong>Pengajuan</strong> izin dan cuti</span>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="left-foot">
            <span class="left-foot-copy">© {{ date('Y') }} PAUD KB Pelangi</span>
            <div class="status-pill">
                <span class="status-dot"></span>
                <span class="status-txt">Sistem Aktif</span>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════
         RIGHT — Form panel
    ════════════════════════════════ --}}
    <div class="right">
        <div class="form-wrap" x-data="pinLogin()">

            {{-- Header --}}
            <div class="form-header">
                <div class="form-eyebrow">Akses Guru</div>
                <h2 class="form-title">Masuk dengan PIN</h2>
                <p class="form-sub">Masukkan username dan PIN 4 digit Anda.</p>
            </div>

            {{-- Error alert --}}
            @if($errors->has('msg'))
            <div class="alert" role="alert">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     style="flex-shrink:0;margin-top:1px">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                {{ $errors->first('msg') }}
            </div>
            @endif

            <form method="POST" action="{{ route('login.guru.post') }}" @submit="loading = true">
                @csrf

                {{-- Username --}}
                <div class="field">
                    <label class="field-label" for="username">Username</label>
                    <div class="field-wrap">
                        <span class="field-ico">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.75" stroke-linecap="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                        </span>
                        <input id="username" type="text" name="username"
                               value="{{ old('username') }}"
                               placeholder="Masukkan username"
                               autocomplete="username" required
                               class="field-input"
                               autofocus>
                    </div>
                </div>

                {{-- PIN label row --}}
                <div class="pin-section">
                    <div class="pin-label-row">
                        <span class="pin-label">PIN 4 Digit</span>
                        <button type="button" class="pin-clear"
                                x-show="pin.length > 0"
                                x-transition.opacity
                                @click="clearPin()">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                                <line x1="18" y1="6" x2="6" y2="18"/>
                                <line x1="6" y1="6" x2="18" y2="18"/>
                            </svg>
                            Hapus
                        </button>
                    </div>

                    {{-- PIN Dots --}}
                    <div class="pin-dots" role="group" aria-label="PIN indicator">
                        <template x-for="i in 4" :key="i">
                            <div class="pin-dot-box"
                                 :class="{ filled: pin.length >= i, shake: shaking }"
                                 :aria-label="`Digit ${i}: ${pin.length >= i ? 'terisi' : 'kosong'}`">
                                <div class="pin-dot" :class="{ visible: pin.length >= i }"></div>
                            </div>
                        </template>
                    </div>

                    <input type="hidden" name="pin" :value="pin">

                    {{-- Numpad --}}
                    <div class="numpad" role="group" aria-label="Numpad PIN">
                        <template x-for="n in [1,2,3,4,5,6,7,8,9]" :key="n">
                            <button type="button" class="numpad-key"
                                    :aria-label="`Digit ${n}`"
                                    @click="addDigit(n); ripple($event)"
                                    :disabled="pin.length >= 4">
                                <span x-text="n"></span>
                            </button>
                        </template>

                        {{-- Slot kosong --}}
                        <button type="button" class="numpad-key key-empty"
                                disabled aria-hidden="true"></button>

                        {{-- 0 --}}
                        <button type="button" class="numpad-key"
                                aria-label="Digit 0"
                                @click="addDigit(0); ripple($event)"
                                :disabled="pin.length >= 4">
                            0
                        </button>

                        {{-- Backspace --}}
                        <button type="button" class="numpad-key key-del"
                                aria-label="Hapus digit terakhir"
                                @click="removeDigit()"
                                :disabled="pin.length === 0">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.75" stroke-linecap="round">
                                <path d="M21 4H8l-7 8 7 8h13a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2z"/>
                                <line x1="18" y1="9" x2="12" y2="15"/>
                                <line x1="12" y1="9" x2="18" y2="15"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn-submit"
                        :class="{ 'is-loading': loading }"
                        :disabled="pin.length < 4 || loading">
                    <span class="btn-lbl">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                            <polyline points="10 17 15 12 10 7"/>
                            <line x1="15" y1="12" x2="3" y2="12"/>
                        </svg>
                        Masuk
                    </span>
                    <span class="btn-spin" aria-hidden="true">
                        <svg class="spin-svg" width="18" height="18" viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="10"
                                    stroke="rgba(255,255,255,.25)" stroke-width="3"/>
                            <path d="M12 2a10 10 0 0 1 10 10"
                                  stroke="#fff" stroke-width="3" stroke-linecap="round"/>
                        </svg>
                    </span>
                </button>
            </form>

            {{-- Kembali ke login admin --}}
            <a href="{{ route('login') }}" class="back-link">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                    <line x1="19" y1="12" x2="5" y2="12"/>
                    <polyline points="12 19 5 12 12 5"/>
                </svg>
                Login Admin / Staf
            </a>

            {{-- Footer --}}
            <div class="form-footer">
                <span class="foot-copy">© {{ date('Y') }} PAUD KB Pelangi</span>
                <div class="status-pill" style="background:oklch(98% 0.025 155);border:1px solid oklch(88% 0.060 155);">
                    <span class="status-dot" style="background:oklch(46% 0.150 155)"></span>
                    <span class="status-txt" style="color:oklch(46% 0.150 155)">Online</span>
                </div>
            </div>

        </div>
    </div>

</div>

<script>
function pinLogin() {
    return {
        pin:     '',
        loading: false,
        shaking: false,

        addDigit(d) {
            if (this.pin.length < 4) {
                this.pin += d;
                if (this.pin.length === 4) {
                    navigator.vibrate && navigator.vibrate(40);
                }
            }
        },
        removeDigit() {
            if (this.pin.length > 0) this.pin = this.pin.slice(0, -1);
        },
        clearPin() { this.pin = ''; },
        triggerShake() {
            this.shaking = true;
            setTimeout(() => this.shaking = false, 420);
        },
        ripple(e) {
            const btn = e.currentTarget;
            btn.classList.remove('ripple');
            void btn.offsetWidth;
            btn.classList.add('ripple');
            setTimeout(() => btn.classList.remove('ripple'), 340);
        }
    }
}

/* Keyboard support */
document.addEventListener('keydown', (e) => {
    const comp = document.querySelector('[x-data]')?._x_dataStack?.[0];
    if (!comp) return;
    /* Abaikan jika focus di username field */
    if (document.activeElement?.id === 'username') return;
    if (e.key >= '0' && e.key <= '9') comp.addDigit(parseInt(e.key));
    if (e.key === 'Backspace') comp.removeDigit();
    if (e.key === 'Escape') comp.clearPin();
});
</script>
</body>
</html>