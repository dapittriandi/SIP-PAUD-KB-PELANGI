<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Kwitansi {{ $spp->no_kwitansi }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            color: #0d1b2e;
            background: #ffffff;
        }

        /* ── PAGE SHELL ── */
        .page {
            width: 148mm;
            min-height: 210mm;
            padding: 0;
            margin: 0 auto;
        }

        /* ── TOP STRIPE ── */
        .edge-stripe {
            height: 5px;
            background: #0d1b2e;
            font-size: 0; line-height: 0;
        }
        .edge-stripe-teal {
            height: 2px;
            background: #14b8a6;
            font-size: 0; line-height: 0;
        }

        /* ── HEADER ── */
        .header {
            background: #0d1b2e;
            padding: 16px 22px 14px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-table td {
            vertical-align: middle;
            padding: 0;
        }
        .header-logo-cell {
            width: 50px;
            padding-right: 12px;
        }
        .header-name-cell {
            vertical-align: middle;
        }
        .header-right-cell {
            width: 42%;
            text-align: right;
            vertical-align: middle;
        }

        .school-name {
            font-size: 15px;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: 0.3px;
            line-height: 1.2;
        }
        .school-sub {
            font-size: 8.5px;
            color: rgba(255,255,255,.5);
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-top: 2px;
        }

        /* no kwitansi */
        .no-kwit-lbl {
            font-size: 7.5px;
            color: rgba(255,255,255,.4);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 5px;
        }
        .no-kwit-val {
            font-family: 'DejaVu Sans Mono', 'Courier New', monospace;
            font-size: 11px;
            font-weight: 700;
            color: #ffffff;
            background: rgba(255,255,255,.1);
            border: 1px solid rgba(255,255,255,.2);
            border-radius: 5px;
            padding: 4px 10px;
            display: inline-block;
            letter-spacing: 0.04em;
        }

        /* teal accent under header */
        .header-accent {
            height: 3px;
            background: #14b8a6;
            font-size: 0; line-height: 0;
        }
        .header-accent-sub {
            height: 1px;
            background: #0c9488;
            font-size: 0; line-height: 0;
        }

        /* ── BODY ── */
        .body {
            padding: 16px 22px 18px;
        }

        /* ── SISWA INFO - native table ── */
        .siswa-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .siswa-table td {
            vertical-align: top;
            padding: 0;
        }
        .siswa-col-left  { width: 60%; padding-right: 12px; }
        .siswa-col-right { width: 40%; }

        .info-lbl {
            font-size: 8px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 700;
            margin-bottom: 2px;
        }
        .info-val {
            font-size: 12px;
            font-weight: 700;
            color: #0d1b2e;
            line-height: 1.3;
        }
        .info-val-mono {
            font-family: 'DejaVu Sans Mono', 'Courier New', monospace;
            font-size: 11px;
            font-weight: 500;
            color: #0d1b2e;
        }
        .info-row { margin-bottom: 10px; }
        .info-row-last { margin-bottom: 0; }

        /* periode pill */
        .periode-pill {
            display: inline-block;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 20px;
            padding: 3px 10px;
            font-size: 10px;
            font-weight: 700;
            color: #1d4ed8;
        }

        /* ── DIVIDERS ── */
        .divider {
            border: none;
            border-top: 1px dashed #dde4ef;
            margin: 11px 0;
        }

        /* ── RINCIAN ── */
        .section-lbl {
            font-size: 7.5px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 7px;
        }
        .rincian-table {
            width: 100%;
            border-collapse: collapse;
        }
        .rincian-table td {
            padding: 6px 0;
            font-size: 11px;
            border-bottom: 1px solid #f3f6fb;
            vertical-align: middle;
        }
        .rincian-table tr:last-child td { border-bottom: none; }
        .rincian-td-left  { color: #374860; }
        .rincian-td-right { text-align: right; font-weight: 700; color: #0d1b2e; }
        .rincian-dot {
            display: inline-block;
            width: 6px; height: 6px;
            border-radius: 50%;
            background: #dde4ef;
            margin-right: 7px;
            vertical-align: middle;
        }

        /* ── TOTAL BOX - native table ── */
        .total-box {
            background: #0d1b2e;
            border-radius: 8px;
            padding: 0;
            margin-top: 10px;
            width: 100%;
            border-collapse: collapse;
            border-left: 3px solid #14b8a6;
        }
        .total-box td {
            padding: 12px 16px;
            vertical-align: middle;
        }
        .total-td-right { text-align: right; }
        .total-lbl {
            font-size: 8px;
            font-weight: 700;
            color: rgba(255,255,255,.5);
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }
        .total-val {
            font-size: 20px;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: -0.3px;
        }
        .total-val-teal { color: #14b8a6; }

        /* ── TERBILANG ── */
        .terbilang-wrap {
            background: #f7f9fc;
            border-left: 3px solid #14b8a6;
            padding: 6px 10px;
            margin-top: 8px;
        }
        .terbilang-lbl { font-size: 7.5px; color: #94a3b8; text-transform: uppercase; letter-spacing: .08em; font-weight: 700; }
        .terbilang-val { font-size: 10px; color: #374860; font-style: italic; margin-top: 2px; }

        /* ── FOOTER - native table ── */
        .footer-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 14px;
        }
        .footer-table td { vertical-align: top; padding: 0; }
        .footer-col-left  { width: 55%; }
        .footer-col-right { padding-left: 14px; }

        /* status badge */
        .status-badge {
            display: inline-block;
            background: #f0fdf4;
            border: 1px solid #86efac;
            color: #15803d;
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 9px;
            font-weight: 700;
            margin-top: 6px;
        }

        /* ttd box */
        .ttd-box {
            border: 1px dashed #cbd5e1;
            border-radius: 7px;
            padding: 8px 10px;
            text-align: center;
        }
        .ttd-label {
            font-size: 8px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 24px;
        }
        .ttd-line {
            border-top: 1px solid #cbd5e1;
            padding-top: 4px;
        }
        .ttd-name {
            font-size: 9px;
            font-weight: 700;
            color: #0d1b2e;
        }

        /* ── STAMP FOOTER - native table ── */
        .stamp-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
            border-top: 1px solid #f1f5f9;
            padding-top: 0;
        }
        .stamp-table td {
            padding-top: 10px;
            vertical-align: middle;
        }
        .stamp-right-td { text-align: right; }
        .stamp-text {
            font-size: 7.5px;
            color: #c8d0db;
            letter-spacing: 0.04em;
        }
        .stamp-dot {
            display: inline-block;
            width: 5px; height: 5px;
            border-radius: 50%;
            vertical-align: middle;
            margin-right: 2px;
        }

        /* ── BOTTOM STRIPE ── */
        .edge-stripe-bottom {
            height: 5px;
            background: #14b8a6;
            margin-top: 20px;
            font-size: 0; line-height: 0;
        }
        .edge-stripe-bottom-dark {
            height: 2px;
            background: #0d1b2e;
            font-size: 0; line-height: 0;
        }
    </style>
</head>
<body>
<div class="page">

    {{-- Top stripe --}}
    <div class="edge-stripe"></div>
    <div class="edge-stripe-teal"></div>

    {{-- HEADER --}}
    <div class="header">
        <table class="header-table">
            <tr>
                <td class="header-logo-cell">
                    <img src="data:image/jpeg;base64,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"
                         width="44" height="44"
                         style="border-radius:50%; border:2px solid #14b8a6; display:block;" />
                </td>
                <td class="header-name-cell">
                    <div class="school-name">PAUD KB PELANGI</div>
                    <div class="school-sub">Desa Pulau Pauh &nbsp;|&nbsp; Bukti Pembayaran SPP</div>
                </td>
                <td class="header-right-cell">
                    <div class="no-kwit-lbl">No. Kwitansi</div>
                    <div class="no-kwit-val">{{ $spp->no_kwitansi }}</div>
                </td>
            </tr>
        </table>
    </div>
    <div class="header-accent"></div>
    <div class="header-accent-sub"></div>

    {{-- BODY --}}
    <div class="body">

        {{-- Info Siswa --}}
        <table class="siswa-table">
            <tr>
                <td class="siswa-col-left">
                    <div class="info-row">
                        <div class="info-lbl">Nama Siswa</div>
                        <div class="info-val">{{ $spp->siswa->nama_lengkap }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-lbl">Kelompok</div>
                        <div class="info-val">{{ $spp->siswa->kelompok }}</div>
                    </div>
                </td>
                <td class="siswa-col-right">
                    <div class="info-row">
                        <div class="info-lbl">NIS</div>
                        <div class="info-val-mono">{{ $spp->siswa->nis ?? '-' }}</div>
                    </div>
                    <div class="info-row-last">
                        <div class="info-lbl">Periode</div>
                        <div class="periode-pill">{{ $spp->nama_bulan }} {{ $spp->tahun }}</div>
                    </div>
                </td>
            </tr>
        </table>

        <hr class="divider">

        {{-- Rincian --}}
        <div class="section-lbl">Rincian Pembayaran</div>

        <table class="rincian-table">
            <tr>
                <td class="rincian-td-left">
                    <span class="rincian-dot"></span>SPP Bulanan
                </td>
                <td class="rincian-td-right">Rp {{ number_format($spp->nominal_spp, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="rincian-td-left">
                    <span class="rincian-dot"></span>Biaya Kebersihan
                </td>
                <td class="rincian-td-right">Rp {{ number_format($spp->nominal_kebersihan, 0, ',', '.') }}</td>
            </tr>
        </table>

        {{-- Total --}}
        <table class="total-box">
            <tr>
                <td>
                    <div class="total-lbl">Total Pembayaran</div>
                </td>
                <td class="total-td-right">
                    <div class="total-val">{{ $spp->total_rupiah }}</div>
                </td>
            </tr>
        </table>

        {{-- Terbilang --}}
        <div class="terbilang-wrap">
            <div class="terbilang-lbl">Terbilang</div>
            <div class="terbilang-val">{{ terbilang($spp->total) }} Rupiah</div>
        </div>

        <hr class="divider">

        {{-- Footer info --}}
        <table class="footer-table">
            <tr>
                <td class="footer-col-left">
                    <div class="info-row">
                        <div class="info-lbl">Tanggal Pembayaran</div>
                        <div class="info-val">{{ $spp->tanggal_bayar->translatedFormat('d F Y') }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-lbl">Dicatat Oleh</div>
                        <div class="info-val">{{ $spp->dicatatOleh?->nama_lengkap ?? 'Admin' }}</div>
                    </div>
                    <div class="status-badge">LUNAS</div>
                </td>
                <td class="footer-col-right">
                    <div class="ttd-box">
                        <div class="ttd-label">Bendahara / Admin</div>
                        <div class="ttd-line">
                            <div class="ttd-name">{{ $spp->dicatatOleh?->nama_lengkap ?? 'Admin' }}</div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>

        {{-- Stamp footer --}}
        <table class="stamp-table">
            <tr>
                <td>
                    <span class="stamp-dot" style="background:#14b8a6;"></span>
                    <span class="stamp-dot" style="background:#1b3a6b;"></span>
                    <span class="stamp-dot" style="background:#dde4ef;"></span>
                    <span class="stamp-text" style="margin-left:4px;">Sistem Informasi PAUD KB Pelangi</span>
                </td>
                <td class="stamp-right-td">
                    <span class="stamp-text">{{ now()->translatedFormat('d F Y, H:i') }} WIB</span>
                </td>
            </tr>
        </table>

    </div>{{-- end body --}}

    {{-- Bottom stripe --}}
    <div class="edge-stripe-bottom"></div>
    <div class="edge-stripe-bottom-dark"></div>

</div>
</body>
</html>