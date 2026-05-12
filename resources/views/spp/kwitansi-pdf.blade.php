<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Kwitansi {{ $spp->no_kwitansi }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #0f1e3c;
            background: #fff;
        }

        .page {
            width: 100%;
            padding: 28px 32px;
        }

        /* ── HEADER ── */
        .header {
            background: linear-gradient(135deg, #0f1e3c 0%, #1a3260 55%, #1e40af 100%);
            padding: 18px 22px;
            border-radius: 10px 10px 0 0;
        }
        .header-row {
            display: table;
            width: 100%;
        }
        .header-left { display: table-cell; vertical-align: middle; }
        .header-right { display: table-cell; vertical-align: middle; text-align: right; }
        .school-name {
            font-size: 18px;
            font-weight: bold;
            color: #ffffff;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }
        .school-sub {
            font-size: 10px;
            color: rgba(255,255,255,0.55);
            font-weight: normal;
        }
        .no-kwit-lbl {
            font-size: 9px;
            color: rgba(255,255,255,0.5);
            text-transform: uppercase;
            letter-spacing: 0.07em;
            margin-bottom: 4px;
        }
        .no-kwit-val {
            font-family: 'Courier New', monospace;
            font-size: 13px;
            font-weight: bold;
            color: #fff;
            background: rgba(255,255,255,0.12);
            padding: 3px 10px;
            border-radius: 6px;
            border: 1px solid rgba(255,255,255,0.15);
            display: inline-block;
        }

        /* ── BODY ── */
        .body {
            border: 1px solid #dbeafe;
            border-top: none;
            border-radius: 0 0 10px 10px;
            padding: 20px 22px;
            background: #fff;
        }

        /* ── INFO GRID ── */
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        .info-table td { padding: 5px 0; vertical-align: top; width: 50%; }
        .info-lbl {
            font-size: 9px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 2px;
        }
        .info-val {
            font-size: 11.5px;
            font-weight: 600;
            color: #0f1e3c;
        }

        /* ── DIVIDER ── */
        .divider {
            border: none;
            border-top: 1.5px dashed #e2e8f0;
            margin: 14px 0;
        }

        /* ── SECTION TITLE ── */
        .section-ttl {
            font-size: 9px;
            font-weight: bold;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 10px;
        }

        /* ── RINCIAN ── */
        .rincian-table { width: 100%; border-collapse: collapse; }
        .rincian-table tr { border-bottom: 1px solid #f1f5ff; }
        .rincian-table tr:last-child { border-bottom: none; }
        .rincian-table td { padding: 7px 4px; font-size: 11px; }
        .rincian-table td:last-child {
            text-align: right;
            font-weight: 600;
            color: #0f1e3c;
        }

        /* ── TOTAL ── */
        .total-block {
            background: linear-gradient(135deg, #0f1e3c 0%, #1a3a6e 100%);
            border-radius: 8px;
            padding: 11px 14px;
            margin-top: 10px;
        }
        .total-inner { display: table; width: 100%; }
        .total-lbl {
            display: table-cell;
            font-size: 11px;
            font-weight: bold;
            color: rgba(255,255,255,0.65);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            vertical-align: middle;
        }
        .total-val {
            display: table-cell;
            text-align: right;
            font-size: 17px;
            font-weight: bold;
            color: #ffffff;
            vertical-align: middle;
        }

        /* ── TERBILANG ── */
        .terbilang {
            font-size: 10px;
            color: #374151;
            font-style: italic;
            margin-top: 8px;
            padding: 6px 10px 6px 14px;
            background: #f8faff;
            border-radius: 6px;
            border-left: 3px solid #2563eb;
        }

        /* ── FOOTER ── */
        .footer-table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        .footer-table td { vertical-align: top; width: 50%; }

        .badge-lunas {
            display: inline-block;
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
            border-radius: 20px;
            padding: 3px 12px;
            font-size: 9px;
            font-weight: bold;
            letter-spacing: 0.07em;
            text-transform: uppercase;
            margin-top: 8px;
        }

        .ttd-box {
            border: 1px dashed #cbd5e1;
            border-radius: 8px;
            padding: 10px 14px;
            text-align: center;
            margin-left: 12px;
        }
        .ttd-label {
            font-size: 9px;
            color: #94a3b8;
            margin-bottom: 30px;
        }
        .ttd-name {
            font-size: 10px;
            font-weight: bold;
            color: #0f1e3c;
            border-top: 1px solid #94a3b8;
            padding-top: 5px;
        }

        /* ── FOOTER STAMP ── */
        .doc-footer {
            text-align: center;
            font-size: 8px;
            color: #cbd5e1;
            margin-top: 12px;
            border-top: 1px solid #f1f5f9;
            padding-top: 8px;
        }
    </style>
</head>
<body>
<div class="page">

    {{-- HEADER --}}
    <div class="header">
        <div class="header-row">
            <div class="header-left">
                <div class="school-name">PAUD KB PELANGI</div>
                <div class="school-sub">Bukti Pembayaran SPP</div>
            </div>
            <div class="header-right">
                <div class="no-kwit-lbl">No. Kwitansi</div>
                <div class="no-kwit-val">{{ $spp->no_kwitansi }}</div>
            </div>
        </div>
    </div>

    {{-- BODY --}}
    <div class="body">

        {{-- Info Siswa --}}
        <table class="info-table">
            <tr>
                <td>
                    <div class="info-lbl">Nama Siswa</div>
                    <div class="info-val">{{ $spp->siswa->nama_lengkap }}</div>
                </td>
                <td>
                    <div class="info-lbl">NIS</div>
                    <div class="info-val">{{ $spp->siswa->nis ?? '—' }}</div>
                </td>
            </tr>
            <tr>
                <td style="padding-top:10px">
                    <div class="info-lbl">Kelompok</div>
                    <div class="info-val">{{ $spp->siswa->kelompok }}</div>
                </td>
                <td style="padding-top:10px">
                    <div class="info-lbl">Periode</div>
                    <div class="info-val">{{ $spp->nama_bulan }} {{ $spp->tahun }}</div>
                </td>
            </tr>
        </table>

        <hr class="divider">

        {{-- Rincian --}}
        <p class="section-ttl">Rincian Pembayaran</p>

        <table class="rincian-table">
            <tr>
                <td>SPP Bulanan</td>
                <td>Rp {{ number_format($spp->nominal_spp, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Biaya Kebersihan</td>
                <td>Rp {{ number_format($spp->nominal_kebersihan, 0, ',', '.') }}</td>
            </tr>
        </table>

        {{-- Total --}}
        <div class="total-block">
            <div class="total-inner">
                <span class="total-lbl">Total Pembayaran</span>
                <span class="total-val">{{ $spp->total_rupiah }}</span>
            </div>
        </div>

        {{-- Terbilang --}}
        <div class="terbilang">
            Terbilang: <strong>{{ terbilang($spp->total) }} Rupiah</strong>
        </div>

        <hr class="divider">

        {{-- Footer --}}
        <table class="footer-table">
            <tr>
                <td>
                    <div class="info-lbl">Tanggal Bayar</div>
                    <div class="info-val">{{ $spp->tanggal_bayar->translatedFormat('d F Y') }}</div>
                    <div class="badge-lunas">✓ &nbsp;Lunas</div>
                </td>
                <td>
                    <div class="ttd-box">
                        <div class="ttd-label">Bendahara / Admin</div>
                        <div class="ttd-name">{{ $spp->dicatatOleh?->nama_lengkap ?? 'Admin' }}</div>
                    </div>
                </td>
            </tr>
        </table>

        <p class="doc-footer">
            Dokumen digenerate otomatis · Sistem Informasi PAUD KB Pelangi · {{ now()->translatedFormat('d F Y H:i') }}
        </p>

    </div>
</div>
</body>
</html>