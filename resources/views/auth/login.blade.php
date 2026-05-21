<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — PAUD KB Pelangi</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<style>
/* ═══════════════════════════════════════════════════════════
   LOGIN ADMIN — Profesional & formal
   Karakter berbeda dari login guru:
   · Panel kiri: ilustrasi geometrik minimal, bukan headline besar
   · Palette: biru tua + putih — trustworthy, enterprise-feel
   · Form: compact & crisp, tanpa numpad
   Token: 100% dari app.blade.php (OKLCH)
═══════════════════════════════════════════════════════════ */

:root {
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
    --success-bg: oklch(98% 0.025 155);
    --success-bd: oklch(88% 0.060 155);

    --sh-sm: 0 1px 3px oklch(0% 0 0 / 8%), 0 1px 2px oklch(0% 0 0 / 4%);
    --sh-md: 0 4px 12px oklch(0% 0 0 / 10%), 0 2px 4px oklch(0% 0 0 / 5%);
    --sh-lg: 0 16px 40px oklch(0% 0 0 / 13%), 0 4px 10px oklch(0% 0 0 / 5%);

    --r:    10px;
    --r-lg: 14px;
    --r-xl: 20px;
    --ease: cubic-bezier(0.22, 1, 0.36, 1);

    /* Panel kiri */
    --left-bg:  oklch(14% 0.022 265);
    --left-bg2: oklch(18% 0.028 263);
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html, body { height: 100%; }

body {
    font-family: 'Geist', system-ui, sans-serif;
    background: var(--surface);
    color: var(--t1);
    -webkit-font-smoothing: antialiased;
    min-height: 100vh;
}

/* ══════════════════════════════════════
   LAYOUT
══════════════════════════════════════ */
.page {
    min-height: 100vh;
    display: grid;
    grid-template-columns: 46% 1fr;
}

/* ══════════════════════════════════════
   LEFT — Branding bersih
══════════════════════════════════════ */
.left {
    position: relative;
    background: var(--left-bg);
    display: flex;
    flex-direction: column;
    padding: 52px 56px;
    overflow: hidden;
}

/* ── Geometric background ── */
/* Grid garis tipis */
.left-grid {
    position: absolute;
    inset: 0;
    pointer-events: none;
    background-image:
        linear-gradient(oklch(100% 0 0 / 3.5%) 1px, transparent 1px),
        linear-gradient(90deg, oklch(100% 0 0 / 3.5%) 1px, transparent 1px);
    background-size: 40px 40px;
}

/* Orb utama — aksen biru */
.left-orb-main {
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    width: 520px; height: 520px;
    border-radius: 50%;
    background: radial-gradient(
        circle,
        oklch(52% 0.190 260 / 14%) 0%,
        oklch(52% 0.190 260 / 4%)  45%,
        transparent 70%
    );
    pointer-events: none;
}

/* Orb kecil — aksent biru muda pojok kanan atas */
.left-orb-top {
    position: absolute;
    top: -80px; right: -80px;
    width: 320px; height: 320px;
    border-radius: 50%;
    background: radial-gradient(
        circle,
        oklch(65% 0.180 245 / 10%) 0%,
        transparent 68%
    );
    pointer-events: none;
}

/* Orb bawah kiri */
.left-orb-bot {
    position: absolute;
    bottom: -60px; left: -60px;
    width: 260px; height: 260px;
    border-radius: 50%;
    background: radial-gradient(
        circle,
        oklch(58% 0.200 280 / 9%) 0%,
        transparent 68%
    );
    pointer-events: none;
}

/* ── Shapes geometrik ── */
.left-shapes {
    position: absolute;
    inset: 0;
    pointer-events: none;
    overflow: hidden;
}
.shape {
    position: absolute;
    border-radius: 20%;
    border: 1px solid oklch(100% 0 0 / 5%);
}
.shape-1 {
    width: 200px; height: 200px;
    top: 28%; left: 58%;
    transform: rotate(18deg);
    background: oklch(52% 0.190 260 / 4%);
    border-color: oklch(52% 0.190 260 / 12%);
    animation: float-slow 9s ease-in-out infinite;
}
.shape-2 {
    width: 120px; height: 120px;
    top: 52%; left: 22%;
    transform: rotate(-12deg);
    background: oklch(65% 0.180 245 / 3%);
    border-color: oklch(65% 0.180 245 / 10%);
    animation: float-slow 12s ease-in-out infinite reverse;
}
.shape-3 {
    width: 72px; height: 72px;
    top: 18%; left: 36%;
    transform: rotate(34deg);
    background: transparent;
    border-color: oklch(100% 0 0 / 7%);
    animation: float-slow 7s ease-in-out infinite;
    animation-delay: -3s;
}
@keyframes float-slow {
    0%, 100% { transform: rotate(var(--r0, 18deg)) translateY(0); }
    50%       { transform: rotate(var(--r0, 18deg)) translateY(-10px); }
}
.shape-1 { --r0: 18deg; }
.shape-2 { --r0: -12deg; }
.shape-3 { --r0: 34deg; }

/* ── Logo ── */
.left-logo {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: center;
    gap: 12px;
}
.logo-mark {
    width: 38px; height: 38px;
    border-radius: 10px;
    background: oklch(52% 0.190 260 / 20%);
    border: 1px solid oklch(52% 0.190 260 / 35%);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.logo-mark svg { color: oklch(75% 0.120 260); }
.logo-text { display: flex; flex-direction: column; gap: 2px; }
.logo-name {
    font-size: 14px; font-weight: 700;
    color: oklch(92% 0.010 260);
    letter-spacing: -.02em;
    line-height: 1;
}
.logo-loc {
    font-size: 11px; font-weight: 500;
    color: oklch(42% 0.020 260);
    letter-spacing: .02em;
}

/* ── Tengah — konten utama ── */
.left-body {
    position: relative;
    z-index: 2;
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 48px 0 36px;
}

/* Vertikal teks "SISTEM INFORMASI" */
.left-eyebrow {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 28px;
}
.eyebrow-line {
    width: 28px; height: 1.5px;
    background: oklch(52% 0.190 260 / 60%);
    border-radius: 2px;
}
.eyebrow-txt {
    font-size: 10.5px; font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .14em;
    color: oklch(52% 0.190 260);
}

/* Nama sekolah besar */
.school-name {
    font-size: clamp(2.2rem, 3.8vw, 3.2rem);
    font-weight: 900;
    line-height: 1.08;
    letter-spacing: -.045em;
    color: oklch(94% 0.010 260);
    margin-bottom: 8px;
}
.school-name-accent {
    display: block;
    color: oklch(55% 0.190 260);
}

.school-place {
    font-size: 13px; font-weight: 500;
    color: oklch(40% 0.018 260);
    letter-spacing: .01em;
    margin-bottom: 48px;
}

/* Tiga mini stat — horizontal, tipis */
.left-stats {
    display: flex;
    gap: 0;
    border: 1px solid oklch(100% 0 0 / 8%);
    border-radius: var(--r-lg);
    overflow: hidden;
    width: fit-content;
}
.lstat {
    padding: 14px 22px;
    border-right: 1px solid oklch(100% 0 0 / 8%);
    display: flex;
    flex-direction: column;
    gap: 3px;
}
.lstat:last-child { border-right: none; }
.lstat-n {
    font-size: 20px; font-weight: 800;
    letter-spacing: -.04em;
    color: oklch(92% 0.010 260);
    line-height: 1;
    font-variant-numeric: tabular-nums;
}
.lstat-n.accent { color: oklch(62% 0.180 260); }
.lstat-l {
    font-size: 10.5px; font-weight: 500;
    color: oklch(40% 0.018 260);
    text-transform: uppercase;
    letter-spacing: .07em;
    white-space: nowrap;
}

/* ── Akses role chips ── */
.role-chips {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
    margin-top: 32px;
}
.role-chip {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 11px;
    border-radius: 99px;
    border: 1px solid oklch(100% 0 0 / 10%);
    background: oklch(100% 0 0 / 5%);
    font-size: 11px; font-weight: 600;
    color: oklch(55% 0.025 260);
    letter-spacing: .02em;
}
.role-chip.active-chip {
    background: oklch(52% 0.190 260 / 15%);
    border-color: oklch(52% 0.190 260 / 30%);
    color: oklch(70% 0.100 260);
}
.chip-dot {
    width: 5px; height: 5px;
    border-radius: 50%;
    background: currentColor;
    opacity: .7;
}

/* ── Footer kiri ── */
.left-foot {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.left-copy {
    font-size: 11px;
    color: oklch(30% 0.015 260);
}
.left-version {
    font-size: 10.5px; font-weight: 600;
    padding: 3px 9px;
    background: oklch(100% 0 0 / 5%);
    border: 1px solid oklch(100% 0 0 / 8%);
    border-radius: 99px;
    color: oklch(35% 0.018 260);
    letter-spacing: .04em;
}

/* ══════════════════════════════════════
   RIGHT — Form login
══════════════════════════════════════ */
.right {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 48px 40px;
    background: var(--surface);
    position: relative;
}

/* Divider kiri */
.right::before {
    content: '';
    position: absolute;
    top: 0; left: 0;
    width: 1px; height: 100%;
    background: linear-gradient(
        to bottom,
        transparent,
        var(--border) 18%,
        var(--border) 82%,
        transparent
    );
}

.form-wrap {
    width: 100%;
    max-width: 348px;
    animation: fade-up .38s var(--ease) both;
}
@keyframes fade-up {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ── Form header ── */
.form-header { margin-bottom: 30px; }

.form-logo-row {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 28px;
}
.form-logo-mark {
    width: 34px; height: 34px;
    border-radius: 9px;
    background: var(--a-soft);
    border: 1px solid var(--a-ring);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.form-logo-mark svg { color: var(--a); }
.form-logo-name {
    font-size: 13px; font-weight: 700;
    color: var(--t1); letter-spacing: -.02em;
}
.form-logo-sub {
    font-size: 11px; color: var(--t3);
    margin-top: 1px;
}

/* Secure badge */
.secure-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 10.5px; font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: var(--a);
    margin-bottom: 10px;
}
.secure-badge-dot {
    width: 5px; height: 5px;
    border-radius: 50%;
    background: var(--a);
    box-shadow: 0 0 0 3px oklch(52% 0.190 260 / 18%);
    animation: pulse-s 2.4s ease-in-out infinite;
}
@keyframes pulse-s {
    0%,100% { box-shadow: 0 0 0 3px oklch(52% 0.190 260 / 18%); }
    50%      { box-shadow: 0 0 0 6px oklch(52% 0.190 260 / 06%); }
}

.form-title {
    font-size: 24px; font-weight: 800;
    color: var(--t1);
    letter-spacing: -.04em;
    line-height: 1.15;
    margin-bottom: 5px;
}
.form-sub {
    font-size: 13px;
    color: var(--t3);
    line-height: 1.5;
}

/* ── Alert error ── */
.alert-err {
    display: flex; align-items: flex-start; gap: 9px;
    padding: 11px 13px;
    background: var(--danger-bg);
    border: 1px solid var(--danger-bd);
    border-radius: var(--r);
    color: var(--danger);
    font-size: 13px; font-weight: 600;
    margin-bottom: 18px;
    line-height: 1.45;
    animation: slide-in .2s var(--ease);
}
.alert-err svg { flex-shrink: 0; margin-top: 1px; }

/* Alert sukses (dari reset password) */
.alert-ok {
    display: flex; align-items: flex-start; gap: 9px;
    padding: 11px 13px;
    background: var(--success-bg);
    border: 1px solid var(--success-bd);
    border-radius: var(--r);
    color: var(--success);
    font-size: 13px; font-weight: 600;
    margin-bottom: 18px;
    line-height: 1.45;
    animation: slide-in .2s var(--ease);
}
.alert-ok svg { flex-shrink: 0; margin-top: 1px; }

@keyframes slide-in {
    from { opacity: 0; transform: translateY(-5px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ── Fields ── */
.field { margin-bottom: 14px; }
.field-label {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 12px; font-weight: 700;
    color: var(--t2);
    text-transform: uppercase; letter-spacing: .05em;
    margin-bottom: 6px;
}
.field-label a {
    font-size: 11.5px; font-weight: 600;
    color: var(--a); text-decoration: none;
    text-transform: none; letter-spacing: 0;
    transition: opacity .13s;
}
.field-label a:hover { opacity: .75; }

.field-wrap { position: relative; }
.field-ico {
    position: absolute;
    left: 11px; top: 50%; transform: translateY(-50%);
    color: var(--t3); pointer-events: none;
    transition: color .13s;
}
.field-wrap:focus-within .field-ico { color: var(--a); }

.field-input {
    width: 100%;
    padding: 10.5px 13px 10.5px 36px;
    font-family: 'Geist', sans-serif;
    font-size: 14px; font-weight: 500;
    color: var(--t1);
    background: var(--bg);
    border: 1.5px solid var(--border);
    border-radius: var(--r);
    outline: none;
    transition: border-color .13s, box-shadow .13s, background .13s;
}
.field-input::placeholder { color: oklch(80% 0.004 250); }
.field-input:focus {
    border-color: var(--a);
    background: var(--surface);
    box-shadow: 0 0 0 3px oklch(52% 0.190 260 / 11%);
}
.field-input.is-err {
    border-color: var(--danger);
    box-shadow: 0 0 0 3px oklch(50% 0.210 27 / 8%);
}
.field-input.has-r { padding-right: 40px; }
.field-err-msg {
    font-size: 11.5px; color: var(--danger);
    margin-top: 4px; font-weight: 500;
}

.eye-btn {
    position: absolute; right: 11px; top: 50%; transform: translateY(-50%);
    background: none; border: none; cursor: pointer;
    color: var(--t3); display: flex; align-items: center;
    padding: 3px;
    transition: color .12s;
}
.eye-btn:hover { color: var(--a); }

/* ── Remember + Submit row ── */
.remember-row {
    display: flex;
    align-items: center;
    gap: 7px;
    margin-bottom: 18px;
}
.chk-custom {
    display: flex; align-items: center; gap: 7px;
    cursor: pointer;
    user-select: none;
}
.chk-custom input[type="checkbox"] {
    appearance: none; -webkit-appearance: none;
    width: 15px; height: 15px;
    border: 1.5px solid var(--border-2);
    border-radius: 4px;
    background: var(--bg);
    cursor: pointer;
    flex-shrink: 0;
    transition: border-color .12s, background .12s, box-shadow .12s;
    display: flex; align-items: center; justify-content: center;
}
.chk-custom input[type="checkbox"]:checked {
    background: var(--a);
    border-color: var(--a);
    box-shadow: 0 0 0 2px oklch(52% 0.190 260 / 15%);
}
.chk-custom input[type="checkbox"]:checked::after {
    content: '';
    display: block;
    width: 8px; height: 8px;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12'%3E%3Cpolyline points='2,6 5,9 10,3' fill='none' stroke='white' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;
    margin: auto;
}
.chk-label-txt {
    font-size: 12.5px; font-weight: 500;
    color: var(--t2);
}

/* ── Submit ── */
.btn-submit {
    position: relative; overflow: hidden;
    width: 100%;
    padding: 12px;
    background: var(--a);
    border: none;
    border-radius: var(--r-lg);
    font-family: 'Geist', sans-serif;
    font-size: 14px; font-weight: 800;
    color: #fff; cursor: pointer;
    letter-spacing: -.01em;
    transition:
        background  .13s var(--ease),
        opacity     .13s,
        transform   .11s var(--ease),
        box-shadow  .18s var(--ease);
    box-shadow:
        0 4px 20px oklch(52% 0.190 260 / 32%),
        0 1px 0 oklch(100% 0 0 / 18%) inset;
    margin-bottom: 14px;
}
.btn-submit:hover:not(:disabled) {
    background: var(--a-h);
    transform: translateY(-1.5px);
    box-shadow:
        0 8px 28px oklch(52% 0.190 260 / 38%),
        0 1px 0 oklch(100% 0 0 / 18%) inset;
}
.btn-submit:active:not(:disabled) { transform: scale(.98); }
.btn-submit:disabled { opacity: .35; cursor: not-allowed; transform: none; box-shadow: none; }

/* Shimmer sweep */
.btn-submit::before {
    content: '';
    position: absolute; top: 0; left: -100%;
    width: 50%; height: 100%;
    background: linear-gradient(90deg,
        transparent,
        oklch(100% 0 0 / 16%),
        transparent
    );
    transform: skewX(-18deg);
    transition: left .55s ease;
}
.btn-submit:hover:not(:disabled)::before { left: 160%; }

.btn-lbl {
    display: flex; align-items: center;
    justify-content: center; gap: 8px;
    transition: opacity .12s;
}
.btn-spin {
    opacity: 0; position: absolute; inset: 0;
    display: flex; align-items: center; justify-content: center;
    transition: opacity .12s;
}
.is-loading .btn-lbl  { opacity: 0; }
.is-loading .btn-spin { opacity: 1; }
.is-loading { pointer-events: none; }
@keyframes spin-anim { to { transform: rotate(360deg); } }
.spin-svg { animation: spin-anim .72s linear infinite; }

/* ── Divider ── */
.divider {
    display: flex; align-items: center; gap: 10px;
    margin-bottom: 14px;
}
.div-line { flex: 1; height: 1px; background: var(--border); }
.div-txt  { font-size: 11px; color: var(--t3); font-weight: 500; }

/* ── Guru link ── */
.guru-link {
    display: flex; align-items: center;
    justify-content: space-between;
    padding: 12px 14px;
    background: var(--bg);
    border: 1.5px solid var(--border);
    border-radius: var(--r-lg);
    text-decoration: none;
    transition: border-color .13s, background .13s, box-shadow .13s;
    gap: 10px;
}
.guru-link:hover {
    background: var(--a-soft);
    border-color: var(--a-ring);
    box-shadow: 0 2px 10px oklch(52% 0.190 260 / 8%);
}
.guru-link-left {
    display: flex; align-items: center; gap: 10px;
}
.guru-link-icon {
    width: 30px; height: 30px;
    border-radius: 8px;
    background: var(--a-soft);
    border: 1px solid var(--a-ring);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    transition: background .13s;
}
.guru-link:hover .guru-link-icon {
    background: oklch(92% 0.040 260);
}
.guru-link-icon svg { color: var(--a); }
.guru-link-text { display: flex; flex-direction: column; gap: 1px; }
.guru-link-title {
    font-size: 13px; font-weight: 700;
    color: var(--t1);
}
.guru-link-sub {
    font-size: 11px; color: var(--t3);
}
.guru-link-arrow {
    color: var(--t3);
    transition: transform .13s, color .13s;
    flex-shrink: 0;
}
.guru-link:hover .guru-link-arrow {
    transform: translateX(3px);
    color: var(--a);
}

/* ── Footer form ── */
.form-footer {
    margin-top: 24px;
    padding-top: 16px;
    border-top: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.foot-copy { font-size: 11px; color: oklch(80% 0.004 250); }
.foot-secure {
    display: flex; align-items: center; gap: 5px;
    font-size: 10.5px; font-weight: 600;
    color: var(--t3);
}
.foot-secure svg { color: var(--success); }

/* ══════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════ */
@media (max-width: 840px) {
    .page { grid-template-columns: 1fr; }
    .left { display: none; }
    .right {
        min-height: 100vh;
        padding: 40px 24px;
        background: var(--bg);
    }
    .right::before { display: none; }
}

@media (prefers-reduced-motion: reduce) {
    .form-wrap   { animation: none; }
    .shape-1, .shape-2, .shape-3 { animation: none; }
    .secure-badge-dot { animation: none; }
    .btn-submit::before { display: none; }
}
</style>
</head>

<body>
<div class="page">

    {{-- ════════════════════════════════════════
         LEFT — Branding bersih
    ════════════════════════════════════════ --}}
    <div class="left" aria-hidden="true">
        <div class="left-grid"></div>
        <div class="left-orb-main"></div>
        <div class="left-orb-top"></div>
        <div class="left-orb-bot"></div>
        <div class="left-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
        </div>

        {{-- Logo --}}
        <div class="left-logo">
            <div class="logo-mark">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
            </div>
            <div class="logo-text">
                <span class="logo-name">PAUD KB Pelangi</span>
                <span class="logo-loc">Desa Pulau Pauh, Muaro Jambi</span>
            </div>
        </div>

        {{-- Tengah --}}
        <div class="left-body">
            <div class="left-eyebrow">
                <div class="eyebrow-line"></div>
                <span class="eyebrow-txt">Sistem Informasi Sekolah</span>
            </div>

            <div class="school-name">
                PAUD
                <span class="school-name-accent">KB Pelangi</span>
            </div>
            <div class="school-place">Desa Pulau Pauh · Kec. Mestong · Muaro Jambi</div>

            {{-- Role access chips --}}
            <div class="role-chips">
                <span class="role-chip active-chip">
                    <span class="chip-dot"></span>Admin
                </span>
                <span class="role-chip active-chip">
                    <span class="chip-dot"></span>Bendahara
                </span>
                <span class="role-chip active-chip">
                    <span class="chip-dot"></span>Kepala Sekolah
                </span>
                <span class="role-chip">
                    <span class="chip-dot"></span>Guru → PIN
                </span>
            </div>
        </div>

        {{-- Footer --}}
        <div class="left-foot">
            <span class="left-copy">© {{ date('Y') }} PAUD KB Pelangi</span>
            <span class="left-version">v2.0</span>
        </div>
    </div>

    {{-- ════════════════════════════════════════
         RIGHT — Form
    ════════════════════════════════════════ --}}
    <div class="right">
        <div class="form-wrap" x-data="{ showPass: false, loading: false }">

            {{-- Logo kecil di form --}}
            <div class="form-logo-row">
                <div class="form-logo-mark">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        <polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                </div>
                <div>
                    <div class="form-logo-name">PAUD KB Pelangi</div>
                    <div class="form-logo-sub">Portal Admin & Staf</div>
                </div>
            </div>

            {{-- Header --}}
            <div class="form-header">
                <div class="secure-badge">
                    <span class="secure-badge-dot"></span>
                    Akses Aman
                </div>
                <h1 class="form-title">Masuk ke Sistem</h1>
                <p class="form-sub">Untuk admin, bendahara, dan kepala sekolah.</p>
            </div>

            {{-- Alerts --}}
            @if($errors->has('msg'))
            <div class="alert-err" role="alert">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     style="flex-shrink:0;margin-top:1px">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8"  x2="12"    y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                {{ $errors->first('msg') }}
            </div>
            @endif

            @if(session('success'))
            <div class="alert-ok" role="alert">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     style="flex-shrink:0;margin-top:1px">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                    <polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
                {{ session('success') }}
            </div>
            @endif

            @if(session('status'))
            <div class="alert-ok" role="alert">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     style="flex-shrink:0;margin-top:1px">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                    <polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
                {{ session('status') }}
            </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" @submit="loading = true">
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
                        <input
                            id="username"
                            type="text"
                            name="username"
                            value="{{ old('username') }}"
                            placeholder="Masukkan username"
                            autocomplete="username"
                            required
                            autofocus
                            class="field-input @error('username') is-err @enderror"
                        >
                    </div>
                    @error('username')
                        <div class="field-err-msg">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="field">
                    <label class="field-label" for="password">
                        Password
                        <a href="{{ route('password.request') }}">Lupa password?</a>
                    </label>
                    <div class="field-wrap">
                        <span class="field-ico">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.75" stroke-linecap="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                        </span>
                        <input
                            id="password"
                            :type="showPass ? 'text' : 'password'"
                            name="password"
                            placeholder="Masukkan password"
                            autocomplete="current-password"
                            required
                            class="field-input has-r"
                        >
                        <button type="button" class="eye-btn"
                                @click="showPass = !showPass"
                                :aria-label="showPass ? 'Sembunyikan password' : 'Tampilkan password'">
                            <svg x-show="!showPass" width="15" height="15" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg x-show="showPass" width="15" height="15" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                                <line x1="1" y1="1" x2="23" y2="23"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Remember --}}
                <div class="remember-row">
                    <label class="chk-custom">
                        <input type="checkbox" name="remember">
                        <span class="chk-label-txt">Ingat saya</span>
                    </label>
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn-submit"
                        :class="{ 'is-loading': loading }"
                        :disabled="loading">
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

            {{-- Guru link --}}
            <div class="divider">
                <div class="div-line"></div>
                <span class="div-txt">atau</span>
                <div class="div-line"></div>
            </div>

            <a href="{{ route('login.guru') }}" class="guru-link">
                <div class="guru-link-left">
                    <div class="guru-link-icon">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                    </div>
                    <div class="guru-link-text">
                        <span class="guru-link-title">Login Guru</span>
                        <span class="guru-link-sub">Masuk menggunakan PIN 4 digit</span>
                    </div>
                </div>
                <svg class="guru-link-arrow" width="14" height="14" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <line x1="5" y1="12" x2="19" y2="12"/>
                    <polyline points="12 5 19 12 12 19"/>
                </svg>
            </a>

            {{-- Footer --}}
            <div class="form-footer">
                <span class="foot-copy">© {{ date('Y') }} PAUD KB Pelangi</span>
                <span class="foot-secure">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                    Koneksi Aman
                </span>
            </div>

        </div>
    </div>

</div>
</body>
</html>