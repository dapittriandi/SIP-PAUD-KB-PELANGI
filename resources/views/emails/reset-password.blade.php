<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password — PAUD KB Pelangi</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f3f4f6;
            color: #111827;
            padding: 40px 16px;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper {
            max-width: 520px;
            margin: 0 auto;
        }
        .card {
            background: #ffffff;
            border-radius: 14px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        }
        /* Header */
        .header {
            background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
            padding: 32px 36px;
            text-align: center;
        }
        .header-icon {
            width: 52px; height: 52px;
            background: rgba(255,255,255,0.18);
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 14px;
        }
        .header h1 {
            font-size: 20px;
            font-weight: 700;
            color: #fff;
            letter-spacing: -0.02em;
        }
        .header p {
            font-size: 13px;
            color: rgba(255,255,255,0.75);
            margin-top: 4px;
        }
        /* Body */
        .body {
            padding: 32px 36px;
        }
        .greeting {
            font-size: 15px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 12px;
        }
        .text {
            font-size: 14px;
            color: #6b7280;
            line-height: 1.7;
            margin-bottom: 28px;
        }
        /* CTA Button */
        .btn-wrap {
            text-align: center;
            margin-bottom: 28px;
        }
        .btn {
            display: inline-block;
            padding: 13px 32px;
            background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
            color: #ffffff !important;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            border-radius: 9px;
            letter-spacing: 0.01em;
            box-shadow: 0 4px 14px rgba(59,130,246,0.35);
        }
        /* URL fallback */
        .url-box {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 12px 14px;
            margin-bottom: 24px;
        }
        .url-box p {
            font-size: 12px;
            color: #9ca3af;
            margin-bottom: 6px;
        }
        .url-box a {
            font-size: 12px;
            color: #2563eb;
            word-break: break-all;
        }
        /* Warning */
        .warning {
            display: flex;
            gap: 10px;
            align-items: flex-start;
            background: #fff7ed;
            border: 1px solid #fed7aa;
            border-radius: 8px;
            padding: 12px 14px;
            margin-bottom: 24px;
            font-size: 13px;
            color: #9a3412;
            line-height: 1.5;
        }
        .warning svg { flex-shrink: 0; margin-top: 1px; }
        /* Footer */
        .footer {
            border-top: 1px solid #f3f4f6;
            padding: 20px 36px;
            text-align: center;
        }
        .footer p {
            font-size: 12px;
            color: #d1d5db;
            line-height: 1.6;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="card">

        {{-- Header --}}
        <div class="header">
            <div class="header-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
            </div>
            <h1>Reset Password</h1>
            <p>PAUD KB Pelangi — Sistem Informasi Sekolah</p>
        </div>

        {{-- Body --}}
        <div class="body">

            <p class="greeting">Halo, {{ $user->nama_lengkap }} 👋</p>

            <p class="text">
                Kami menerima permintaan untuk mereset password akun Anda di sistem PAUD KB Pelangi.
                Klik tombol di bawah ini untuk membuat password baru. Link ini hanya berlaku selama
                <strong>60 menit</strong>.
            </p>

            <div class="btn-wrap">
                <a href="{{ $resetUrl }}" class="btn">
                    🔑 &nbsp; Reset Password Saya
                </a>
            </div>

            <div class="url-box">
                <p>Jika tombol tidak berfungsi, salin link berikut ke browser Anda:</p>
                <a href="{{ $resetUrl }}">{{ $resetUrl }}</a>
            </div>

            <div class="warning">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    <line x1="12" y1="9" x2="12" y2="13"/>
                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
                <span>
                    Jika Anda tidak meminta reset password, abaikan email ini. Password Anda tidak akan
                    berubah tanpa tindakan dari Anda.
                </span>
            </div>

        </div>

        {{-- Footer --}}
        <div class="footer">
            <p>
                Email ini dikirim secara otomatis oleh sistem PAUD KB Pelangi.<br>
                Desa Pulau Pauh, Kec. Mestong, Muaro Jambi &nbsp;·&nbsp; © {{ date('Y') }}
            </p>
        </div>

    </div>
</div>
</body>
</html>