{{-- resources/views/auth/lupa-password.blade.php --}}
<!DOCTYPE html>
<html lang="id" x-data="{ loading: false }" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password — PAUD KB Pelangi</title>

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
            --accent-soft: oklch(96% 0.025 260);
            --bg:          oklch(96.0% 0.006 250);
            --surface:     oklch(99.5% 0.003 250);
            --border:      oklch(90.0% 0.007 250);
            --text-1:      oklch(14% 0.008 260);
            --text-2:      oklch(46% 0.012 255);
            --text-3:      oklch(68% 0.008 255);
            --danger:      oklch(50% 0.210 27);
            --danger-bg:   oklch(98% 0.025 27);
            --success:     oklch(46% 0.150 155);
            --success-bg:  oklch(98% 0.025 155);
            --success-bd:  oklch(88% 0.060 155);
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

        .alert-success {
            display: flex; align-items: flex-start; gap: 10px;
            padding: 12px 14px;
            background: var(--success-bg);
            border: 1px solid var(--success-bd);
            border-radius: var(--r);
            color: var(--success);
            font-size: .82rem; font-weight: 600;
            margin-bottom: 1.2rem;
            line-height: 1.45;
        }
        .alert-success svg { flex-shrink: 0; margin-top: 1px; }

        .field { display: flex; flex-direction: column; gap: 5px; margin-bottom: 1rem; }
        .field label { font-size: .8rem; font-weight: 600; color: var(--text-1); }
        .input-wrap { position: relative; }
        .input-wrap svg.ico {
            position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
            color: var(--text-3); pointer-events: none;
        }
        .field input {
            width: 100%;
            padding: 10px 13px 10px 38px;
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
    </style>
</head>
<body>
    <div class="card">

        {{-- Logo --}}
        <div class="logo-wrap">
            <div class="logo-icon">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
            </div>
            <span class="logo-title">PAUD KB Pelangi</span>
            <span class="logo-sub">Sistem Informasi Sekolah</span>
        </div>

        <h1>Lupa Password?</h1>
        <p class="desc">Masukkan email akun Anda. Kami akan mengirimkan link untuk mereset password Anda.</p>

        {{-- Alert sukses --}}
        @if(session('success'))
            <div class="alert-success">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                    <polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('password.email') }}" @submit="loading = true">
            @csrf

            <div class="field">
                <label for="email">Alamat Email</label>
                <div class="input-wrap">
                    <svg class="ico" width="15" height="15" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        <polyline points="22,6 12,13 2,6"/>
                    </svg>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="contoh@email.com"
                        autocomplete="email"
                        autofocus
                        class="{{ $errors->has('email') ? 'is-error' : '' }}"
                        required
                    >
                </div>
                @error('email')
                    <span class="err">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn" :disabled="loading">
                <template x-if="!loading">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="22" y1="2" x2="11" y2="13"/>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                    </svg>
                </template>
                <template x-if="loading">
                    <div class="spinner"></div>
                </template>
                <span x-text="loading ? 'Mengirim...' : 'Kirim Link Reset'"></span>
            </button>
        </form>

        <p class="back-link">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15 18 9 12 15 6"/>
            </svg>
            Ingat password? <a href="{{ route('login') }}">Kembali Login</a>
        </p>

    </div>
</body>
</html>