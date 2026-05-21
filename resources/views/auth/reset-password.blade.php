{{-- resources/views/auth/reset-password.blade.php --}}
<!DOCTYPE html>
<html lang="id" x-data="resetForm()" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password — PAUD KB Pelangi</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { fontFamily: { sans: ['Geist','system-ui','sans-serif'] } } }
        }
    </script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        :root {
            --accent:      oklch(52% 0.190 260);
            --accent-h:    oklch(46% 0.200 262);
            --bg:          oklch(96.0% 0.006 250);
            --surface:     oklch(99.5% 0.003 250);
            --border:      oklch(90.0% 0.007 250);
            --text-1:      oklch(14% 0.008 260);
            --text-2:      oklch(46% 0.012 255);
            --text-3:      oklch(68% 0.008 255);
            --danger:      oklch(50% 0.210 27);
            --danger-bg:   oklch(98% 0.025 27);
            --danger-bd:   oklch(88% 0.075 27);
            --r: 10px; --r-lg: 14px;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Geist', system-ui, sans-serif;
            background: var(--bg);
            color: var(--text-1);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--r-lg);
            box-shadow: 0 4px 24px oklch(0% 0 0 / 7%), 0 1px 4px oklch(0% 0 0 / 4%);
            width: 100%;
            max-width: 420px;
            padding: 2.5rem 2rem;
        }

        .logo-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: .6rem;
            margin-bottom: 2rem;
        }
        .logo-icon {
            width: 52px; height: 52px;
            background: linear-gradient(135deg, oklch(52% 0.190 260), oklch(62% 0.200 290));
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
        }
        .logo-icon svg { color: #fff; }
        .logo-title { font-size: 1.1rem; font-weight: 700; color: var(--text-1); }
        .logo-sub   { font-size: .8rem; color: var(--text-3); }

        h1 { font-size: 1rem; font-weight: 700; color: var(--text-1); margin-bottom: .35rem; }
        .desc { font-size: .83rem; color: var(--text-2); line-height: 1.5; margin-bottom: 1.5rem; }

        /* Alert error global */
        .alert-error {
            display: flex; align-items: flex-start; gap: 10px;
            padding: 12px 14px;
            background: var(--danger-bg);
            border: 1px solid var(--danger-bd);
            border-radius: var(--r);
            color: var(--danger);
            font-size: .82rem; font-weight: 600;
            margin-bottom: 1.2rem;
            line-height: 1.45;
        }
        .alert-error svg { flex-shrink: 0; margin-top: 1px; }

        .field { display: flex; flex-direction: column; gap: 5px; margin-bottom: 1rem; }
        .field label { font-size: .8rem; font-weight: 600; color: var(--text-1); }
        .input-wrap { position: relative; }
        .input-wrap svg.ico-left {
            position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
            color: var(--text-3); pointer-events: none;
        }
        .toggle-pw {
            position: absolute; right: 11px; top: 50%; transform: translateY(-50%);
            background: none; border: none; cursor: pointer;
            color: var(--text-3); padding: 2px;
            display: flex; align-items: center;
            transition: color .15s;
        }
        .toggle-pw:hover { color: var(--accent); }

        .field input {
            width: 100%;
            padding: 10px 40px 10px 38px;
            border: 1.5px solid var(--border);
            border-radius: var(--r);
            font-size: .87rem; color: var(--text-1);
            background: var(--bg);
            font-family: inherit;
            outline: none;
            transition: border-color .15s, box-shadow .15s;
        }
        .field input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px oklch(52% 0.190 260 / 12%);
            background: var(--surface);
        }
        .field input.is-error { border-color: var(--danger); }
        .err { font-size: .74rem; color: var(--danger); }

        /* Password strength */
        .pw-strength { margin-top: 6px; }
        .pw-bars { display: flex; gap: 4px; margin-bottom: 4px; }
        .pw-bar {
            flex: 1; height: 4px; border-radius: 99px;
            background: var(--border);
            transition: background .25s;
        }
        .pw-bar.weak   { background: oklch(50% 0.210 27); }
        .pw-bar.medium { background: oklch(60% 0.180 70); }
        .pw-bar.strong { background: oklch(46% 0.150 155); }
        .pw-hint { font-size: .72rem; color: var(--text-3); }

        .btn {
            width: 100%;
            padding: 10.5px;
            background: var(--accent);
            color: #fff;
            border: none; border-radius: var(--r);
            font-size: .88rem; font-weight: 700;
            font-family: inherit; cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 7px;
            transition: background .15s, transform .15s, opacity .15s;
            margin-top: .25rem;
        }
        .btn:hover:not(:disabled) { background: var(--accent-h); transform: translateY(-1px); }
        .btn:disabled { opacity: .65; cursor: not-allowed; transform: none; }

        .back-link {
            display: flex; align-items: center; justify-content: center; gap: 5px;
            margin-top: 1.25rem;
            font-size: .81rem; color: var(--text-3);
            text-decoration: none;
        }
        .back-link a { color: var(--accent); font-weight: 600; text-decoration: none; }
        .back-link a:hover { text-decoration: underline; }

        @keyframes spin { to { transform: rotate(360deg); } }
        .spinner {
            width: 15px; height: 15px;
            border: 2px solid rgba(255,255,255,.35);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin .6s linear infinite;
        }

        .divider {
            border: none;
            border-top: 1px solid var(--border);
            margin: 1.25rem 0;
        }
    </style>
</head>
<body>
    <div class="card">

        {{-- Logo --}}
        <div class="logo-wrap">
            <div class="logo-icon">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
            </div>
            <span class="logo-title">PAUD KB Pelangi</span>
            <span class="logo-sub">Sistem Informasi Sekolah</span>
        </div>

        <h1>Buat Password Baru</h1>
        <p class="desc">Masukkan password baru untuk akun <strong>{{ $email }}</strong>.</p>

        {{-- Error global --}}
        @if($errors->has('email'))
            <div class="alert-error">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <span>{{ $errors->first('email') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}"
              @submit="loading = true">
            @csrf

            {{-- Hidden fields --}}
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            {{-- Password Baru --}}
            <div class="field">
                <label for="password">Password Baru <span style="color:oklch(50% 0.210 27)">*</span></label>
                <div class="input-wrap">
                    <svg class="ico-left" width="15" height="15" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                    <input
                        id="password"
                        :type="showPw ? 'text' : 'password'"
                        name="password"
                        placeholder="Min. 8 karakter + angka"
                        autocomplete="new-password"
                        class="{{ $errors->has('password') ? 'is-error' : '' }}"
                        @input="checkStrength($event.target.value)"
                        required
                    >
                    <button type="button" class="toggle-pw" @click="showPw = !showPw"
                            :aria-label="showPw ? 'Sembunyikan password' : 'Tampilkan password'">
                        <svg x-show="!showPw" width="15" height="15" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                        <svg x-show="showPw" width="15" height="15" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/>
                            <line x1="1" y1="1" x2="23" y2="23"/>
                        </svg>
                    </button>
                </div>

                {{-- Strength meter --}}
                <div class="pw-strength">
                    <div class="pw-bars">
                        <div class="pw-bar" :class="score >= 1 ? strengthClass : ''"></div>
                        <div class="pw-bar" :class="score >= 2 ? strengthClass : ''"></div>
                        <div class="pw-bar" :class="score >= 3 ? strengthClass : ''"></div>
                        <div class="pw-bar" :class="score >= 4 ? strengthClass : ''"></div>
                    </div>
                    <p class="pw-hint" x-text="strengthLabel"></p>
                </div>
                @error('password')
                    <span class="err">{{ $message }}</span>
                @enderror
            </div>

            {{-- Konfirmasi Password --}}
            <div class="field">
                <label for="password_confirmation">Konfirmasi Password Baru <span style="color:oklch(50% 0.210 27)">*</span></label>
                <div class="input-wrap">
                    <svg class="ico-left" width="15" height="15" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                    <input
                        id="password_confirmation"
                        :type="showPwConf ? 'text' : 'password'"
                        name="password_confirmation"
                        placeholder="Ulangi password baru"
                        autocomplete="new-password"
                        required
                    >
                    <button type="button" class="toggle-pw" @click="showPwConf = !showPwConf"
                            :aria-label="showPwConf ? 'Sembunyikan' : 'Tampilkan'">
                        <svg x-show="!showPwConf" width="15" height="15" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                        <svg x-show="showPwConf" width="15" height="15" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/>
                            <line x1="1" y1="1" x2="23" y2="23"/>
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn" :disabled="loading">
                <template x-if="!loading">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                </template>
                <template x-if="loading">
                    <div class="spinner"></div>
                </template>
                <span x-text="loading ? 'Menyimpan...' : 'Simpan Password Baru'"></span>
            </button>
        </form>

        <p class="back-link">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15 18 9 12 15 6"/>
            </svg>
            <a href="{{ route('login') }}">Kembali ke halaman login</a>
        </p>

    </div>

    <script>
    function resetForm() {
        return {
            loading:    false,
            showPw:     false,
            showPwConf: false,
            score:      0,
            strengthClass: '',
            strengthLabel: 'Minimal 8 karakter, huruf & angka',

            checkStrength(val) {
                let s = 0;
                if (val.length >= 8)          s++;
                if (/[A-Z]/.test(val))         s++;
                if (/[0-9]/.test(val))         s++;
                if (/[^A-Za-z0-9]/.test(val)) s++;
                this.score = s;

                if (!val.length) {
                    this.strengthClass = '';
                    this.strengthLabel = 'Minimal 8 karakter, huruf & angka';
                } else if (s <= 1) {
                    this.strengthClass = 'weak';
                    this.strengthLabel = 'Lemah — tambahkan angka atau huruf besar';
                } else if (s === 2) {
                    this.strengthClass = 'medium';
                    this.strengthLabel = 'Sedang — hampir kuat';
                } else if (s === 3) {
                    this.strengthClass = 'strong';
                    this.strengthLabel = 'Kuat';
                } else {
                    this.strengthClass = 'strong';
                    this.strengthLabel = 'Sangat Kuat';
                }
            }
        }
    }
    </script>
</body>
</html>