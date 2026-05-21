<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — PAUD KB Pelangi</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            height: 100%;
            font-family: 'Inter', sans-serif;
            background: #fff;
            color: #111;
            -webkit-font-smoothing: antialiased;
        }

        .page {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        /* ── Left ── */
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

        .left-body { flex: 1; display: flex; flex-direction: column; justify-content: center; padding: 48px 0; }

        .left-headline {
            font-size: 38px;
            font-weight: 600;
            line-height: 1.15;
            letter-spacing: -.04em;
            color: #111;
            margin-bottom: 14px;
        }
        .left-headline span {
            background: linear-gradient(135deg, #2563eb, #60a5fa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .left-sub {
            font-size: 14.5px;
            color: #6b7280;
            line-height: 1.7;
            font-weight: 400;
            max-width: 300px;
            margin-bottom: 48px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1px;
            background: #e5e7eb;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            overflow: hidden;
            margin-bottom: 36px;
        }
        .stat {
            background: #fff;
            padding: 20px 22px;
        }
        .stat-n {
            font-size: 26px;
            font-weight: 600;
            letter-spacing: -.03em;
            color: #111;
            margin-bottom: 4px;
        }
        .stat:first-child .stat-n { color: #2563eb; }
        .stat-l { font-size: 11.5px; color: #9ca3af; }

        .info-list { display: flex; flex-direction: column; gap: 12px; }
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
        }
        .info-key { color: #9ca3af; }
        .info-val { color: #374151; font-weight: 500; text-align: right; }

        /* ── Right ── */
        .right {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 40px;
            background: #fff;
            box-shadow: -1px 0 40px rgba(59,130,246,.04);
        }

        .form-wrap { width: 100%; max-width: 340px; }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 44px;
        }
        .logo-mark {
            width: 32px; height: 32px;
            border-radius: 8px;
            background: linear-gradient(135deg, #2563eb 0%, #60a5fa 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 4px 10px rgba(59,130,246,.25);
        }
        .logo-name { font-size: 14px; font-weight: 600; color: #111; }
        .logo-loc  { font-size: 11.5px; color: #9ca3af; margin-top: 1px; }

        .form-heading {
            font-size: 22px;
            font-weight: 600;
            color: #111;
            letter-spacing: -.025em;
            margin-bottom: 6px;
        }
        .form-sub {
            font-size: 13.5px;
            color: #9ca3af;
            margin-bottom: 32px;
        }

        .alert {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 12px 14px;
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 9px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #b91c1c;
        }

        .field { margin-bottom: 16px; }
        .field-label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 7px;
        }
        .field-wrap { position: relative; }
        .field-input {
            width: 100%;
            padding: 11px 14px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            color: #111;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 9px;
            outline: none;
            transition: border-color .15s, box-shadow .15s;
        }
        .field-input::placeholder { color: #d1d5db; }
        .field-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,.10);
        }
        .field-input.has-r { padding-right: 42px; }
        .field-input.err   { border-color: #fca5a5; }

        .eye-btn {
            position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
            background: none; border: none; cursor: pointer;
            color: #9ca3af; display: flex; align-items: center;
            transition: color .13s;
        }
        .eye-btn:hover { color: #374151; }

        .field-err { font-size: 12px; color: #ef4444; margin-top: 5px; }

        .row-mid {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .chk-label {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: 13px;
            color: #6b7280;
            cursor: pointer;
            user-select: none;
        }
        .chk-label input { accent-color: #3b82f6; cursor: pointer; }

        .forgot-link {
            font-size: 13px;
            color: #3b82f6;
            text-decoration: none;
            font-weight: 500;
            transition: color .13s;
        }
        .forgot-link:hover { color: #2563eb; text-decoration: underline; }

        .alert-success {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 12px 14px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 9px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #15803d;
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
            color: #fff;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            font-weight: 500;
            border: none;
            border-radius: 9px;
            cursor: pointer;
            position: relative;
            transition: opacity .15s, transform .1s, box-shadow .15s;
            margin-bottom: 12px;
            box-shadow: 0 4px 14px rgba(59,130,246,.30), 0 1px 3px rgba(59,130,246,.20);
        }
        .btn-submit:hover  { opacity: .92; box-shadow: 0 6px 20px rgba(59,130,246,.38); }
        .btn-submit:active { transform: scale(0.985); }

        .btn-lbl  { display: flex; align-items: center; justify-content: center; gap: 8px; }
        .btn-spin { opacity: 0; position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; }
        .is-loading .btn-lbl  { opacity: 0; }
        .is-loading .btn-spin { opacity: 1; }
        .is-loading { pointer-events: none; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .spin-svg { animation: spin .8s linear infinite; }

        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 12px 0;
        }
        .div-line { flex: 1; height: 1px; background: #f3f4f6; }
        .div-txt  { font-size: 11.5px; color: #d1d5db; }

        .guru-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            width: 100%;
            padding: 11px 14px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 9px;
            font-size: 13.5px;
            font-weight: 500;
            color: #374151;
            text-decoration: none;
            transition: background .13s, border-color .13s;
        }
        .guru-link:hover { background: #f3f4f6; border-color: #d1d5db; }

        .form-footer {
            margin-top: 36px;
            font-size: 11.5px;
            color: #d1d5db;
            text-align: center;
        }

        @media (max-width: 800px) {
            .page  { grid-template-columns: 1fr; }
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
            Sistem Aktif
        </div>

        <div class="left-body">

            <h1 class="left-headline">PAUD<br><span>KB Pelangi</span></h1>
            <p class="left-sub">
                Sistem informasi manajemen sekolah, Desa Pulau Pauh, Kecamatan Mestong, Muaro Jambi.
            </p>

            <div class="stats">
                <div class="stat">
                    <div class="stat-n">32</div>
                    <div class="stat-l">Siswa Aktif</div>
                </div>
                <div class="stat">
                    <div class="stat-n">8</div>
                    <div class="stat-l">Pendidik</div>
                </div>
                <div class="stat">
                    <div class="stat-n">87%</div>
                    <div class="stat-l">SPP Lunas</div>
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
                    <span class="info-key">NPSN</span>
                    <span class="info-val">69854321</span>
                </div>
                <div class="info-row">
                    <span class="info-key">Izin Operasional</span>
                    <span class="info-val">420/PAUD/2019/0042</span>
                </div>
            </div>

        </div>

        <div style="font-size: 12px; color: #d1d5db;">© {{ date('Y') }} PAUD KB Pelangi</div>

    </div>

    {{-- ── Right ── --}}
    <div class="right">
        <div class="form-wrap" x-data="{ showPass: false, loading: false }">

            <div class="logo">
                <div class="logo-mark">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round">
                        <path d="M22 9V7h-2V5a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-2h2v-2h-2v-2h2v-2h-2V9z"/>
                    </svg>
                </div>
                <div>
                    <div class="logo-name">KB Pelangi</div>
                    <div class="logo-loc">Desa Pulau Pauh</div>
                </div>
            </div>

            <h2 class="form-heading">Selamat datang</h2>
            <p class="form-sub">Masuk untuk mengakses sistem</p>

            @if($errors->has('msg'))
            <div class="alert" role="alert">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" style="flex-shrink:0;margin-top:1px">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                {{ $errors->first('msg') }}
            </div>
            @endif

            @if(session('status'))
            <div class="alert-success" role="alert">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" style="flex-shrink:0;margin-top:1px">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
                {{ session('status') }}
            </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" @submit="loading = true">
                @csrf

                <div class="field">
                    <label class="field-label" for="username">Username</label>
                    <input
                        id="username" type="text" name="username"
                        value="{{ old('username') }}"
                        placeholder="Masukkan username"
                        autocomplete="username" required
                        class="field-input @error('username') err @enderror"
                    >
                    @error('username')
                    <div class="field-err">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label class="field-label" for="password">Password</label>
                    <div class="field-wrap">
                        <input
                            id="password"
                            :type="showPass ? 'text' : 'password'"
                            name="password"
                            placeholder="Masukkan password"
                            autocomplete="current-password" required
                            class="field-input has-r"
                        >
                        <button type="button" class="eye-btn" @click="showPass = !showPass">
                            <svg x-show="!showPass" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg x-show="showPass" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                                <line x1="1" y1="1" x2="23" y2="23"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="row-mid">
                    <label class="chk-label">
                        <input type="checkbox" name="remember">
                        Ingat saya
                    </label>
                    <a href="{{ route('password.request') }}" class="forgot-link">
                        Lupa password?
                    </a>
                </div>

                <button type="submit" class="btn-submit" :class="{ 'is-loading': loading }">
                    <span class="btn-lbl">Masuk</span>
                    <span class="btn-spin" aria-hidden="true">
                        <svg class="spin-svg" width="18" height="18" viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="10" stroke="rgba(255,255,255,0.25)" stroke-width="3"/>
                            <path d="M12 2a10 10 0 0 1 10 10" stroke="#fff" stroke-width="3" stroke-linecap="round"/>
                        </svg>
                    </span>
                </button>
            </form>

            <div class="divider">
                <div class="div-line"></div>
                <span class="div-txt">atau</span>
                <div class="div-line"></div>
            </div>

            <a href="{{ route('login.guru') }}" class="guru-link">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round">
                    <rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
                </svg>
                Login Guru dengan PIN
            </a>

            <div class="form-footer">© {{ date('Y') }} PAUD KB Pelangi · Desa Pulau Pauh</div>

        </div>
    </div>

</div>
</body>
</html>