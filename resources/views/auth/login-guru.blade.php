<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Guru — PAUD KB Pelangi</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: #fff;
            color: #111;
            -webkit-font-smoothing: antialiased;
        }

        .page {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        /* ── Left panel ── */
        .left {
            background: #f9fafb;
            border-right: 1px solid #f0f0f0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 56px 64px;
        }

        .school-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            font-weight: 500;
            color: #6b7280;
            letter-spacing: .04em;
            text-transform: uppercase;
        }
        .school-dot {
            width: 7px; height: 7px;
            border-radius: 50%;
            background: #22c55e;
            box-shadow: 0 0 0 3px rgba(34,197,94,.15);
            animation: pulse 2.4s ease infinite;
        }
        @keyframes pulse {
            0%,100% { box-shadow: 0 0 0 3px rgba(34,197,94,.15); }
            50%      { box-shadow: 0 0 0 6px rgba(34,197,94,.06); }
        }

        .left-body {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 48px 0;
        }

        /* Blue chip */
        .guru-chip {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: linear-gradient(135deg, rgba(37,99,235,.08), rgba(96,165,250,.12));
            border: 1px solid rgba(59,130,246,.18);
            border-radius: 100px;
            padding: 5px 14px 5px 8px;
            width: fit-content;
            margin-bottom: 28px;
        }
        .chip-icon {
            width: 22px; height: 22px; border-radius: 50%;
            background: linear-gradient(135deg, #2563eb, #60a5fa);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .chip-label {
            font-size: 11.5px;
            font-weight: 600;
            color: #2563eb;
            letter-spacing: .03em;
        }

        .left-headline {
            font-size: 38px;
            font-weight: 600;
            line-height: 1.15;
            letter-spacing: -.04em;
            color: #111;
            margin-bottom: 14px;
        }
        .left-headline span {
            background: linear-gradient(135deg, #1d4ed8 0%, #60a5fa 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .left-sub {
            font-size: 14.5px;
            color: #6b7280;
            line-height: 1.7;
            max-width: 300px;
            margin-bottom: 44px;
        }

        /* Teacher card */
        .teacher-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 28px;
            box-shadow: 0 2px 16px rgba(59,130,246,.06);
        }
        .tc-head {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }
        .tc-avatar {
            width: 44px; height: 44px; border-radius: 50%;
            background: linear-gradient(135deg, #1d4ed8, #60a5fa);
            display: flex; align-items: center; justify-content: center;
            font-size: 15px; font-weight: 600; color: #fff;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(37,99,235,.25);
        }
        .tc-lines { display: flex; flex-direction: column; gap: 6px; }
        .tc-line  { height: 8px; border-radius: 4px; background: #f3f4f6; }
        .tc-stats {
            display: grid;
            grid-template-columns: repeat(3,1fr);
            gap: 1px;
            background: #f3f4f6;
            border-radius: 10px;
            overflow: hidden;
        }
        .tc-stat {
            background: #fff;
            padding: 12px 10px;
            text-align: center;
        }
        .tc-val {
            font-size: 20px; font-weight: 600;
            letter-spacing: -.03em; color: #111;
            margin-bottom: 2px;
        }
        .tc-val.blue { color: #2563eb; }
        .tc-lbl { font-size: 10.5px; color: #9ca3af; }

        /* Info list */
        .info-list { display: flex; flex-direction: column; gap: 10px; }
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
        }
        .info-key { color: #9ca3af; }
        .info-val { color: #374151; font-weight: 500; }

        /* ── Right panel ── */
        .right {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 40px;
            background: #fff;
            box-shadow: -1px 0 40px rgba(59,130,246,.04);
        }

        .form-wrap { width: 100%; max-width: 340px; }

        /* Logo */
        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 44px;
        }
        .logo-mark {
            width: 32px; height: 32px; border-radius: 8px;
            background: linear-gradient(135deg, #1d4ed8, #60a5fa);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 4px 10px rgba(37,99,235,.25);
        }
        .logo-name { font-size: 14px; font-weight: 600; color: #111; }
        .logo-loc  { font-size: 11.5px; color: #9ca3af; margin-top: 1px; }

        /* Eyebrow */
        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: 11px;
            font-weight: 600;
            color: #2563eb;
            letter-spacing: .06em;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        .eyebrow::before {
            content: '';
            width: 18px; height: 2px; border-radius: 2px;
            background: linear-gradient(90deg, #1d4ed8, #60a5fa);
            display: inline-block;
        }

        .form-heading {
            font-size: 22px; font-weight: 600;
            color: #111; letter-spacing: -.025em;
            margin-bottom: 6px;
        }
        .form-sub {
            font-size: 13.5px; color: #9ca3af;
            margin-bottom: 28px;
        }

        /* Error */
        .alert {
            display: flex; align-items: flex-start; gap: 10px;
            padding: 12px 14px;
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 9px;
            margin-bottom: 20px;
            font-size: 13px; color: #b91c1c;
        }

        /* Username field */
        .field { margin-bottom: 18px; }
        .field-label {
            display: block;
            font-size: 13px; font-weight: 500;
            color: #374151; margin-bottom: 7px;
        }
        .field-wrap { position: relative; }
        .field-icon {
            position: absolute; left: 13px; top: 50%; transform: translateY(-50%);
            color: #d1d5db; pointer-events: none; display: flex; align-items: center;
            transition: color .15s;
        }
        .field-wrap:focus-within .field-icon { color: #3b82f6; }
        .field-input {
            width: 100%; padding: 11px 14px 11px 38px;
            font-size: 14px; font-family: 'Inter', sans-serif;
            color: #111; background: #fff;
            border: 1px solid #e5e7eb; border-radius: 9px;
            outline: none;
            transition: border-color .15s, box-shadow .15s;
        }
        .field-input::placeholder { color: #d1d5db; }
        .field-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,.10);
        }

        /* ── PIN section ── */
        .pin-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 12px;
        }
        .clear-btn {
            font-size: 11.5px; font-weight: 500;
            color: #9ca3af; background: none; border: none;
            cursor: pointer; padding: 0;
            transition: color .13s;
        }
        .clear-btn:hover { color: #ef4444; }

        /* PIN dots */
        .pin-dots {
            display: flex; justify-content: center; gap: 10px;
            margin-bottom: 20px;
        }
        .pin-dot-box {
            width: 52px; height: 56px; border-radius: 12px;
            border: 1px solid #e5e7eb;
            background: #f9fafb;
            display: flex; align-items: center; justify-content: center;
            transition: border-color .18s, background .18s, box-shadow .18s, transform .15s;
        }
        .pin-dot-box.filled {
            border-color: #3b82f6;
            background: linear-gradient(135deg, rgba(37,99,235,.06), rgba(96,165,250,.08));
            box-shadow: 0 0 0 3px rgba(59,130,246,.10);
            transform: scale(1.04);
        }
        .pin-dot-box.shake { animation: shake .38s ease; }
        @keyframes shake {
            0%,100%{transform:translateX(0) scale(1.04)}
            20%{transform:translateX(-5px) scale(1.04)}
            40%{transform:translateX(5px) scale(1.04)}
            60%{transform:translateX(-3px) scale(1.04)}
            80%{transform:translateX(3px) scale(1.04)}
        }
        .pin-dot {
            width: 10px; height: 10px; border-radius: 50%;
            background: transparent;
            transition: background .18s, transform .18s;
            transform: scale(0);
        }
        .pin-dot.visible {
            background: linear-gradient(135deg, #1d4ed8, #60a5fa);
            transform: scale(1);
            box-shadow: 0 2px 6px rgba(37,99,235,.35);
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
            height: 56px; border-radius: 12px;
            border: 1px solid #e5e7eb;
            background: #fff;
            font-family: 'Inter', sans-serif;
            font-weight: 500; font-size: 20px; color: #111;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            transition: background .12s, border-color .12s, color .12s,
                        transform .1s, box-shadow .12s;
            user-select: none;
            -webkit-tap-highlight-color: transparent;
        }
        .numpad-key:hover:not(:disabled) {
            background: linear-gradient(135deg, rgba(37,99,235,.06), rgba(96,165,250,.08));
            border-color: rgba(59,130,246,.35);
            color: #1d4ed8;
            box-shadow: 0 2px 10px rgba(59,130,246,.08);
        }
        .numpad-key:active:not(:disabled) {
            transform: scale(0.92);
            background: linear-gradient(135deg, rgba(37,99,235,.12), rgba(96,165,250,.14));
            box-shadow: 0 0 0 3px rgba(59,130,246,.12);
        }
        .numpad-key:disabled { opacity: .25; cursor: not-allowed; }

        .numpad-key.key-del {
            color: #9ca3af;
        }
        .numpad-key.key-del:hover:not(:disabled) {
            background: #fef2f2; border-color: #fecaca; color: #ef4444;
            box-shadow: none;
        }
        .numpad-key.key-empty {
            background: transparent; border-color: transparent;
            cursor: default; pointer-events: none;
        }

        /* Ripple */
        .numpad-key::after {
            content: ''; position: absolute; inset: 0; border-radius: inherit;
            background: rgba(59,130,246,.15);
            opacity: 0; transform: scale(.4);
            transition: opacity .28s, transform .3s;
        }
        .numpad-key.ripple::after { opacity: 1; transform: scale(1); }

        /* ── Submit ── */
        .btn-submit {
            position: relative; overflow: hidden;
            width: 100%; padding: 13px;
            background: linear-gradient(135deg, #1d4ed8 0%, #3b82f6 60%, #60a5fa 100%);
            border: none; border-radius: 10px;
            font-family: 'Inter', sans-serif;
            font-size: 14px; font-weight: 600; color: #fff;
            cursor: pointer; letter-spacing: .01em;
            transition: opacity .15s, transform .12s, box-shadow .18s;
            box-shadow: 0 4px 18px rgba(37,99,235,.30), 0 1px 0 rgba(255,255,255,.15) inset;
            margin-bottom: 10px;
        }
        .btn-submit:hover:not(:disabled) {
            opacity: .93;
            transform: translateY(-1px);
            box-shadow: 0 8px 28px rgba(37,99,235,.38), 0 1px 0 rgba(255,255,255,.15) inset;
        }
        .btn-submit:active:not(:disabled) { transform: scale(0.985); }
        .btn-submit:disabled { opacity: .30; cursor: not-allowed; transform: none; }

        /* Shimmer */
        .btn-submit::after {
            content: ''; position: absolute; top: 0; left: -100%;
            width: 55%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,.2), transparent);
            transform: skewX(-20deg); transition: left .6s ease;
        }
        .btn-submit:hover:not(:disabled)::after { left: 170%; }

        .btn-lbl  { display:flex; align-items:center; justify-content:center; gap:8px; }
        .btn-spin { opacity:0; position:absolute; inset:0; display:flex; align-items:center; justify-content:center; }
        .is-loading .btn-lbl  { opacity:0; }
        .is-loading .btn-spin { opacity:1; }
        .is-loading { pointer-events:none; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .spin-svg { animation: spin .8s linear infinite; }

        /* Back link */
        .back-link {
            display: flex; align-items: center; justify-content: center; gap: 7px;
            padding: 11px 14px;
            background: #f9fafb;
            border: 1px solid #e5e7eb; border-radius: 9px;
            font-size: 13.5px; font-weight: 500; color: #374151;
            text-decoration: none;
            transition: background .13s, border-color .13s, color .13s;
        }
        .back-link:hover {
            background: linear-gradient(135deg, rgba(37,99,235,.05), rgba(96,165,250,.07));
            border-color: rgba(59,130,246,.25);
            color: #2563eb;
        }

        /* Footer */
        .form-footer {
            margin-top: 32px; padding-top: 20px;
            border-top: 1px solid #f3f4f6;
            display: flex; align-items: center; justify-content: space-between;
        }
        .foot-copy { font-size: 11.5px; color: #d1d5db; }
        .online-pill {
            display: flex; align-items: center; gap: 5px;
            background: rgba(34,197,94,.06);
            border: 1px solid rgba(34,197,94,.18);
            border-radius: 100px; padding: 3px 10px;
        }
        .online-dot { width: 6px; height: 6px; border-radius: 50%; background: #22c55e; animation: pulse 2.4s ease infinite; }
        .online-txt { font-size: 11px; font-weight: 600; color: #16a34a; }

        /* Responsive */
        @media (max-width: 800px) {
            .page { grid-template-columns: 1fr; }
            .left  { display: none; }
            .right { min-height: 100vh; padding: 48px 24px; }
        }
    </style>
</head>

<body>
<div class="page">

    {{-- ── Left ── --}}
    <div class="left" aria-hidden="true">

        <div class="school-tag">
            <span class="school-dot"></span>
            Portal Guru
        </div>

        <div class="left-body">

            <div class="guru-chip">
                <div class="chip-icon">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round">
                        <rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
                    </svg>
                </div>
                <span class="chip-label">Akses Khusus Guru</span>
            </div>

            <h1 class="left-headline">Login<br><span>Tenaga Pengajar</span></h1>
            <p class="left-sub">
                Masuk dengan PIN 4 digit yang telah diberikan oleh administrator sekolah.
            </p>

            {{-- Teacher preview card --}}
            <div class="teacher-card">
                <div class="tc-head">
                    <div class="tc-avatar">SR</div>
                    <div class="tc-lines">
                        <div class="tc-line" style="width:110px;"></div>
                        <div class="tc-line" style="width:70px;opacity:.5;margin-top:4px;"></div>
                    </div>
                </div>
                <div class="tc-stats">
                    <div class="tc-stat">
                        <div class="tc-val blue">32</div>
                        <div class="tc-lbl">Siswa</div>
                    </div>
                    <div class="tc-stat">
                        <div class="tc-val">8</div>
                        <div class="tc-lbl">Guru</div>
                    </div>
                    <div class="tc-stat">
                        <div class="tc-val">96%</div>
                        <div class="tc-lbl">Hadir</div>
                    </div>
                </div>
            </div>

            <div class="info-list">
                <div class="info-row">
                    <span class="info-key">Kepala Sekolah</span>
                    <span class="info-val">Hj. Rosmawati, S.Pd</span>
                </div>
                <div class="info-row">
                    <span class="info-key">Tahun Ajaran</span>
                    <span class="info-val">2025 / 2026</span>
                </div>
                <div class="info-row">
                    <span class="info-key">Kelompok</span>
                    <span class="info-val">KB (Kelompok Bermain)</span>
                </div>
            </div>

        </div>

        <div style="font-size:12px;color:#d1d5db;">© {{ date('Y') }} PAUD KB Pelangi</div>

    </div>

    {{-- ── Right ── --}}
    <div class="right">
        <div class="form-wrap" x-data="pinLogin()">

            <div class="logo">
                <div class="logo-mark">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round">
                        <rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
                    </svg>
                </div>
                <div>
                    <div class="logo-name">KB Pelangi</div>
                    <div class="logo-loc">Portal Guru</div>
                </div>
            </div>

            <div class="eyebrow">Akses Guru</div>
            <h2 class="form-heading">Masuk dengan PIN</h2>
            <p class="form-sub">Masukkan username dan PIN 4 digit Anda</p>

            {{-- Error --}}
            @if($errors->has('msg'))
            <div class="alert" role="alert">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" style="flex-shrink:0;margin-top:1px">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
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
                        <span class="field-icon">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                            </svg>
                        </span>
                        <input id="username" type="text" name="username"
                               value="{{ old('username') }}"
                               placeholder="Username guru"
                               autocomplete="username" required
                               class="field-input">
                    </div>
                </div>

                {{-- PIN Header --}}
                <div class="pin-header">
                    <label class="field-label" style="margin:0">PIN 4 Digit</label>
                    <button type="button" class="clear-btn"
                            x-show="pin.length > 0"
                            @click="clearPin()">
                        Hapus ✕
                    </button>
                </div>

                {{-- PIN Dots --}}
                <div class="pin-dots">
                    <template x-for="i in 4" :key="i">
                        <div class="pin-dot-box"
                             :class="{ filled: pin.length >= i, shake: shaking }">
                            <div class="pin-dot" :class="{ visible: pin.length >= i }"></div>
                        </div>
                    </template>
                </div>

                <input type="hidden" name="pin" :value="pin">

                {{-- Numpad --}}
                <div class="numpad">
                    <template x-for="n in [1,2,3,4,5,6,7,8,9]" :key="n">
                        <button type="button" class="numpad-key"
                                @click="addDigit(n); ripple($event)"
                                :disabled="pin.length >= 4">
                            <span x-text="n"></span>
                        </button>
                    </template>

                    <button type="button" class="numpad-key key-empty" disabled aria-hidden="true"></button>

                    <button type="button" class="numpad-key"
                            @click="addDigit(0); ripple($event)"
                            :disabled="pin.length >= 4">
                        0
                    </button>

                    <button type="button" class="numpad-key key-del"
                            @click="removeDigit()"
                            :disabled="pin.length === 0">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round">
                            <path d="M21 4H8l-7 8 7 8h13a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2z"/>
                            <line x1="18" y1="9" x2="12" y2="15"/><line x1="12" y1="9" x2="18" y2="15"/>
                        </svg>
                    </button>
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn-submit"
                        :class="{ 'is-loading': loading }"
                        :disabled="pin.length < 4 || loading">
                    <span class="btn-lbl">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                            <polyline points="10 17 15 12 10 7"/>
                            <line x1="15" y1="12" x2="3" y2="12"/>
                        </svg>
                        Masuk dengan PIN
                    </span>
                    <span class="btn-spin" aria-hidden="true">
                        <svg class="spin-svg" width="18" height="18" viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="10" stroke="rgba(255,255,255,.25)" stroke-width="3"/>
                            <path d="M12 2a10 10 0 0 1 10 10" stroke="#fff" stroke-width="3" stroke-linecap="round"/>
                        </svg>
                    </span>
                </button>
            </form>

            <a href="{{ route('login') }}" class="back-link">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                    <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
                </svg>
                Login Admin / Staf
            </a>

            <div class="form-footer">
                <span class="foot-copy">© {{ date('Y') }} PAUD KB Pelangi</span>
                <div class="online-pill">
                    <span class="online-dot"></span>
                    <span class="online-txt">Online</span>
                </div>
            </div>

        </div>
    </div>

</div>

<script>
function pinLogin() {
    return {
        pin: '',
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

document.addEventListener('keydown', (e) => {
    const comp = document.querySelector('[x-data]')?._x_dataStack?.[0];
    if (!comp) return;
    if (e.key >= '0' && e.key <= '9') comp.addDigit(parseInt(e.key));
    if (e.key === 'Backspace') comp.removeDigit();
    if (e.key === 'Escape') comp.clearPin();
});
</script>
</body>
</html>