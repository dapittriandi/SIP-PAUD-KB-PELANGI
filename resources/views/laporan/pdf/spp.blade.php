{{-- resources/views/laporan/pdf/spp.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Laporan SPP &mdash; {{ \Carbon\Carbon::create(null, $bulan)->translatedFormat('F') }} {{ $tahun }}</title>
<style>
    /* ── Reset ── */
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
        font-family: 'DejaVu Sans', sans-serif;
        font-size: 9.5px;
        color: #1a2332;
        background: #fff;
        line-height: 1.5;
    }

    /* ── KOP SURAT ── */
    .kop {
        width: 100%;
        border-collapse: collapse;
        border-bottom: 3px solid #1565C0;
        margin-bottom: 14px;
        padding-bottom: 2px;
    }
    .kop td {
        padding-bottom: 10px;
        vertical-align: middle;
    }
    .kop-logo {
        width: 72px;
        padding-right: 14px;
        vertical-align: middle;
    }
    .kop-teks { }
    .kop-nama {
        font-size: 14px;
        font-weight: 700;
        color: #0D47A1;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .kop-alamat {
        font-size: 8px;
        color: #64748b;
        margin-top: 2px;
    }
    .kop-judul-wrap {
        width: 200px;
        text-align: right;
        vertical-align: middle;
    }
    .kop-judul {
        font-size: 12px;
        font-weight: 700;
        color: #0D47A1;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .kop-periode {
        font-size: 9px;
        color: #475569;
        margin-top: 3px;
    }
    .kop-nomor {
        font-size: 8px;
        color: #94a3b8;
        margin-top: 2px;
    }

    /* ── RINGKASAN STATS ── */
    .stats-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 14px;
    }
    .stats-table td {
        width: 25%;
        padding: 9px 12px;
        border: 0.5px solid #e2e8f0;
        vertical-align: top;
    }
    .stats-table td:first-child { border-left: 3px solid #1565C0; }
    .stats-table td:nth-child(2) { border-left: 3px solid #0f6e56; }
    .stats-table td:nth-child(3) { border-left: 3px solid #991b1b; }
    .stats-table td:last-child    { border-left: 3px solid #854f0b; }

    .s-label {
        font-size: 7.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #94a3b8;
        margin-bottom: 3px;
    }
    .s-val { font-size: 20px; font-weight: 700; line-height: 1; }
    .s-val.navy  { color: #1565C0; }
    .s-val.green { color: #0f6e56; }
    .s-val.red   { color: #991b1b; }
    .s-val.amber { color: #854f0b; font-size: 13px; }
    .s-sub { font-size: 7.5px; color: #94a3b8; margin-top: 3px; }

    /* ── SECTION LABEL ── */
    .sec-label {
        font-size: 8px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.7px;
        color: #fff;
        background: #1565C0;
        padding: 5px 10px;
        margin-bottom: 0;
    }
    .sec-label.red-bg { background: #450a0a; }

    /* ── TABEL UTAMA ── */
    .tbl {
        width: 100%;
        border-collapse: collapse;
        font-size: 8.5px;
        margin-bottom: 16px;
    }
    .tbl thead th {
        background: #0D47A1;
        color: #BBDEFB;
        padding: 6px 8px;
        font-size: 7.5px;
        font-weight: 700;
        letter-spacing: 0.4px;
        text-transform: uppercase;
        text-align: left;
        border: none;
    }
    .tbl thead th.right  { text-align: right; }
    .tbl thead th.center { text-align: center; }

    .tbl.red-head thead th { background: #450a0a; }

    .tbl tbody tr { border-bottom: 0.5px solid #f1f5f9; }
    .tbl tbody tr:nth-child(even) { background: #f0f7ff; }
    .tbl tbody td { padding: 5.5px 8px; vertical-align: middle; }
    .tbl tbody td.no    { text-align: center; color: #94a3b8; font-size: 8px; }
    .tbl tbody td.nama  { font-weight: 600; color: #0D47A1; }
    .tbl tbody td.mono  { font-family: 'Courier New', monospace; font-size: 7.5px; color: #64748b; }
    .tbl tbody td.center { text-align: center; }
    .tbl tbody td.right  { text-align: right; }
    .tbl tbody td.nominal { text-align: right; color: #1565C0; font-weight: 500; }
    .tbl tbody td.total   { text-align: right; font-weight: 700; color: #0D47A1; }
    .tbl tbody td.tagihan { text-align: right; font-weight: 700; color: #991b1b; }
    .tbl tbody td.muted   { color: #64748b; font-size: 8px; }
    .tbl tbody td.empty   { text-align: center; padding: 16px; color: #94a3b8; }

    .tbl tfoot tr { background: #E3F2FD; border-top: 1.5px solid #90CAF9; }
    .tbl tfoot td { padding: 6px 8px; font-weight: 700; font-size: 8.5px; }
    .tbl tfoot td.right { text-align: right; }
    .tbl tfoot td.total-sum { color: #1565C0; text-align: right; }
    .tbl tfoot td.tunggakan-sum { color: #991b1b; text-align: right; }

    /* ── BADGE KELOMPOK ── */
    .kel-badge {
        display: inline-block;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #BBDEFB;
        color: #0D47A1;
        font-size: 8px;
        font-weight: 700;
        text-align: center;
        padding-top: 6px;
        vertical-align: middle;
    }

    /* ── ALL LUNAS ── */
    .all-lunas {
        background: #e1f5ee;
        border: 0.5px solid #5dcaa5;
        border-left: 3px solid #0f6e56;
        border-radius: 4px;
        padding: 10px 14px;
        font-size: 9px;
        color: #085041;
        font-weight: 600;
        margin-top: 4px;
        margin-bottom: 16px;
    }

    /* ── TANDA TANGAN ── */
    .ttd-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 28px;
    }
    .ttd-table td {
        vertical-align: bottom;
    }
    .ttd-info {
        font-size: 8px;
        color: #94a3b8;
        padding-right: 10px;
    }
    .ttd-right-cell {
        width: 320px;
        text-align: right;
    }
    .ttd-boxes {
        width: 100%;
        border-collapse: collapse;
    }
    .ttd-box {
        width: 140px;
        text-align: center;
        padding: 0 10px;
    }
    .ttd-title { font-size: 8px; color: #334155; margin-bottom: 44px; }
    .ttd-line {
        border-top: 1px solid #334155;
        padding-top: 4px;
        font-size: 8px;
        font-weight: 700;
        color: #0D47A1;
    }

    /* ── FOOTER PAGE ── */
    .page-footer {
        width: 100%;
        border-collapse: collapse;
        margin-top: 16px;
        border-top: 0.5px solid #BBDEFB;
    }
    .page-footer td {
        padding-top: 8px;
        font-size: 7.5px;
        color: #94a3b8;
        vertical-align: middle;
    }
    .pf-right {
        text-align: right;
    }

    /* Logo: embedded base64 img, no extra CSS needed */

    /* ── LUNAS BADGE ── */
    .lunas-icon {
        display: inline-block;
        width: 14px;
        height: 14px;
        background: #0f6e56;
        border-radius: 50%;
        text-align: center;
        line-height: 14px;
        font-size: 9px;
        font-weight: 700;
        color: #ffffff;
        vertical-align: middle;
        margin-right: 4px;
    }

    /* ── GARIS AKSEN HEADER ── */
    .accent-line {
        width: 100%;
        height: 4px;
        background: #1565C0;
        margin-bottom: 0;
        font-size: 0;
        line-height: 0;
    }
    .accent-line-sub {
        width: 100%;
        height: 2px;
        background: #FDD835;
        margin-bottom: 14px;
        font-size: 0;
        line-height: 0;
    }
</style>
</head>
<body>

{{-- ══════════════════════════════════════
     ACCENT TOP BAR
══════════════════════════════════════ --}}
<div class="accent-line"></div>
<div class="accent-line-sub"></div>

{{-- ══════════════════════════════════════
     KOP SURAT
══════════════════════════════════════ --}}
<table class="kop">
    <tr>
        <td class="kop-logo">
            {{-- Logo asli PAUD KB Pelangi - embedded base64 JPEG, Dompdf-safe --}}
            <img src="data:image/jpeg;base64,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" width="60" height="60" style="border-radius:50%; border:2px solid #1565C0; display:block;" />
        </td>
        <td class="kop-teks">
            <div class="kop-nama">{{ config('sekolah.nama', 'PAUD KB Pelangi Desa Pulau Pauh') }}</div>
            <div class="kop-alamat">
                {{ config('sekolah.alamat', 'Jl. [Alamat Sekolah]') }}
                &nbsp;-&nbsp; Telp. {{ config('sekolah.telp', '[No. Telp]') }}
                &nbsp;-&nbsp; {{ config('sekolah.email', '') }}
            </div>
        </td>
        <td class="kop-judul-wrap">
            <div class="kop-judul">Laporan Pembayaran SPP</div>
            <div class="kop-periode">
                Periode: {{ \Carbon\Carbon::create(null, $bulan)->translatedFormat('F') }} {{ $tahun }}
            </div>
            <div class="kop-nomor">
                Dicetak: {{ now()->translatedFormat('d F Y, H:i') }} WIB
            </div>
        </td>
    </tr>
</table>

{{-- ══════════════════════════════════════
     RINGKASAN STATISTIK
══════════════════════════════════════ --}}
<table class="stats-table">
    <tr>
        <td>
            <div class="s-label">Total Siswa Aktif</div>
            <div class="s-val navy">{{ $ringkasan['total_siswa'] }}</div>
            <div class="s-sub">siswa terdaftar</div>
        </td>
        <td>
            <div class="s-label">Sudah Lunas</div>
            <div class="s-val green">{{ $ringkasan['sudah_bayar'] }}</div>
            <div class="s-sub">{{ $ringkasan['persentase_lunas'] }}% dari total</div>
        </td>
        <td>
            <div class="s-label">Masih Tunggakan</div>
            <div class="s-val red">{{ $ringkasan['tunggakan'] }}</div>
            <div class="s-sub">{{ 100 - $ringkasan['persentase_lunas'] }}% belum bayar</div>
        </td>
        <td>
            <div class="s-label">Total Pemasukan</div>
            <div class="s-val amber">Rp {{ number_format($ringkasan['total_pemasukan'], 0, ',', '.') }}</div>
            <div class="s-sub">
                SPP {{ number_format($ringkasan['total_spp'], 0, ',', '.') }}
                + Kebersihan {{ number_format($ringkasan['total_kebersihan'], 0, ',', '.') }}
            </div>
        </td>
    </tr>
</table>

{{-- ══════════════════════════════════════
     TABEL PEMBAYARAN
══════════════════════════════════════ --}}
<div class="sec-label">
    &#9632; Daftar Pembayaran - {{ $pembayaran->count() }} transaksi
</div>

<table class="tbl">
    <thead>
        <tr>
            <th class="center" style="width:24px">#</th>
            <th style="min-width:110px">Nama Siswa</th>
            <th style="width:100px">No. Kwitansi</th>
            <th class="center" style="width:66px">Tgl. Bayar</th>
            <th class="right"  style="width:72px">SPP</th>
            <th class="right"  style="width:72px">Kebersihan</th>
            <th class="right"  style="width:80px">Total</th>
            <th style="width:80px">Dicatat Oleh</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($pembayaran as $i => $p)
        <tr>
            <td class="no">{{ $i + 1 }}</td>
            <td class="nama">{{ $p->siswa->nama_lengkap ?? $p->siswa->nama ?? '-' }}</td>
            <td class="mono">{{ $p->no_kwitansi }}</td>
            <td class="center muted">{{ \Carbon\Carbon::parse($p->tanggal_bayar)->format('d/m/Y') }}</td>
            <td class="nominal">Rp {{ number_format($p->nominal_spp, 0, ',', '.') }}</td>
            <td class="nominal">Rp {{ number_format($p->nominal_kebersihan, 0, ',', '.') }}</td>
            <td class="total">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
            <td class="muted">{{ $p->dicatatOleh->nama_lengkap ?? $p->dicatatOleh->nama ?? '-' }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="8" class="empty">Belum ada pembayaran pada periode ini.</td>
        </tr>
        @endforelse
    </tbody>
    @if ($pembayaran->count() > 0)
    <tfoot>
        <tr>
            <td colspan="4">Total Keseluruhan ({{ $pembayaran->count() }} transaksi)</td>
            <td class="right">Rp {{ number_format($ringkasan['total_spp'], 0, ',', '.') }}</td>
            <td class="right">Rp {{ number_format($ringkasan['total_kebersihan'], 0, ',', '.') }}</td>
            <td class="total-sum">Rp {{ number_format($ringkasan['total_pemasukan'], 0, ',', '.') }}</td>
            <td>-</td>
        </tr>
    </tfoot>
    @endif
</table>

{{-- ══════════════════════════════════════
     TABEL TUNGGAKAN
══════════════════════════════════════ --}}
@php
    // Jika controller kirim 0 atau tidak kirim, query langsung ke DB
    if (empty($tarifTotal)) {
        $tarifRow = \App\Models\TarifSpp::where('tahun_berlaku', $tahun)->first()
                 ?? \App\Models\TarifSpp::where('tahun_berlaku', '<', $tahun)->orderByDesc('tahun_berlaku')->first()
                 ?? \App\Models\TarifSpp::orderByDesc('tahun_berlaku')->first();
        $tarifTotal = $tarifRow ? ($tarifRow->nominal_spp + $tarifRow->nominal_kebersihan) : 0;
    }
@endphp

@if ($tunggakan->count() > 0)

<div class="sec-label red-bg">
    &#9632; Daftar Tunggakan - {{ $tunggakan->count() }} siswa belum bayar
</div>

<table class="tbl red-head">
    <thead>
        <tr>
            <th class="center" style="width:24px">#</th>
            <th style="min-width:110px">Nama Siswa</th>
            <th class="center" style="width:60px">Kelompok</th>
            <th style="width:110px">Nama Wali</th>
            <th style="width:90px">No. HP Wali</th>
            <th class="right" style="width:80px">Est. Tagihan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($tunggakan as $i => $s)
        <tr>
            <td class="no">{{ $i + 1 }}</td>
            <td class="nama">{{ $s->nama_lengkap ?? $s->nama ?? '-' }}</td>
            <td class="center">
                <span class="kel-badge">{{ strtoupper($s->kelompok ?? '?') }}</span>
            </td>
            <td>{{ $s->nama_wali ?? $s->nama_ayah ?? $s->nama_ibu ?? '-' }}</td>
            <td class="mono">{{ $s->no_hp_wali ?? $s->no_hp_ayah ?? $s->no_hp_ibu ?? '-' }}</td>
            <td class="tagihan">Rp {{ number_format($tarifTotal, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5">Total Potensi Tunggakan ({{ $tunggakan->count() }} siswa)</td>
            <td class="tunggakan-sum">
                Rp {{ number_format($tunggakan->count() * $tarifTotal, 0, ',', '.') }}
            </td>
        </tr>
    </tfoot>
</table>

@else

<div class="all-lunas">
    <span class="lunas-icon">OK</span> Semua siswa aktif telah melunasi SPP
    {{ \Carbon\Carbon::create(null, $bulan)->translatedFormat('F') }} {{ $tahun }}.
</div>

@endif

{{-- ══════════════════════════════════════
     TANDA TANGAN
══════════════════════════════════════ --}}
<table class="ttd-table">
    <tr>
        <td class="ttd-info">
            Dokumen ini dicetak secara otomatis oleh sistem.<br>
            Sah tanpa tanda tangan basah jika dikeluarkan secara resmi.
        </td>
        <td class="ttd-right-cell">
            <table class="ttd-boxes">
                <tr>
                    <td class="ttd-box">
                        <div class="ttd-title">
                            {{ \Carbon\Carbon::create(null, $bulan)->translatedFormat('F') }} {{ $tahun }},<br>
                            Mengetahui,<br>Kepala Sekolah
                        </div>
                        <div class="ttd-line">( ________________________ )</div>
                    </td>
                    <td class="ttd-box">
                        <div class="ttd-title">
                            {{ \Carbon\Carbon::create(null, $bulan)->translatedFormat('F') }} {{ $tahun }},<br>
                            Bendahara
                        </div>
                        <div class="ttd-line">( ________________________ )</div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

{{-- ══════════════════════════════════════
     FOOTER
══════════════════════════════════════ --}}
<table class="page-footer">
    <tr>
        <td class="pf-left">
            {{ config('sekolah.nama', 'PAUD KB Pelangi') }}
            &nbsp;-&nbsp; Laporan SPP {{ \Carbon\Carbon::create(null, $bulan)->translatedFormat('F') }} {{ $tahun }}
        </td>
        <td class="pf-right">
            Halaman 1 dari 1
        </td>
    </tr>
</table>

</body>
</html>