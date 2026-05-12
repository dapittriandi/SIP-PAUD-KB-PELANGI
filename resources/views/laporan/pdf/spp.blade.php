{{-- resources/views/laporan/pdf/spp.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Laporan SPP — {{ \Carbon\Carbon::create(null, $bulan)->translatedFormat('F') }} {{ $tahun }}</title>
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
        border-bottom: 2.5px solid #0f1f35;
        margin-bottom: 14px;
    }
    .kop td {
        padding-bottom: 10px;
        vertical-align: middle;
    }
    .kop-logo {
        width: 56px;
        padding-right: 10px;
    }
    .logo-circle {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: #1e3a5f;
        text-align: center;
        vertical-align: middle;
        font-size: 18px;
        font-weight: 700;
        color: #fff;
        letter-spacing: -1px;
        line-height: 48px;
        /* Dompdf: border-radius on block, not table-cell */
        display: block;
    }
    .kop-teks { }
    .kop-nama {
        font-size: 15px;
        font-weight: 700;
        color: #0f1f35;
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
        font-size: 13px;
        font-weight: 700;
        color: #0f1f35;
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
    .stats-table td:first-child { border-left: 3px solid #1e3a5f; }
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
    .s-val.navy  { color: #1e3a5f; }
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
        background: #1e3a5f;
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
        background: #0f1f35;
        color: #94a3b8;
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
    .tbl tbody tr:nth-child(even) { background: #f8fafc; }
    .tbl tbody td { padding: 5.5px 8px; vertical-align: middle; }
    .tbl tbody td.no    { text-align: center; color: #94a3b8; font-size: 8px; }
    .tbl tbody td.nama  { font-weight: 600; color: #0f1f35; }
    .tbl tbody td.mono  { font-family: 'Courier New', monospace; font-size: 7.5px; color: #64748b; }
    .tbl tbody td.center { text-align: center; }
    .tbl tbody td.right  { text-align: right; }
    .tbl tbody td.nominal { text-align: right; color: #1e3a5f; font-weight: 500; }
    .tbl tbody td.total   { text-align: right; font-weight: 700; color: #0f1f35; }
    .tbl tbody td.tagihan { text-align: right; font-weight: 700; color: #991b1b; }
    .tbl tbody td.muted   { color: #64748b; font-size: 8px; }
    .tbl tbody td.empty   { text-align: center; padding: 16px; color: #94a3b8; }

    .tbl tfoot tr { background: #f1f5f9; border-top: 1.5px solid #cbd5e1; }
    .tbl tfoot td { padding: 6px 8px; font-weight: 700; font-size: 8.5px; }
    .tbl tfoot td.right { text-align: right; }
    .tbl tfoot td.total-sum { color: #1e3a5f; text-align: right; }
    .tbl tfoot td.tunggakan-sum { color: #991b1b; text-align: right; }

    /* ── BADGE KELOMPOK ── */
    .kel-badge {
        display: inline-block;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #faeeda;
        color: #633806;
        font-size: 9px;
        font-weight: 700;
        text-align: center;
        line-height: 20px;
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
        margin-top: 24px;
    }
    .ttd-table td {
        vertical-align: bottom;
    }
    .ttd-info {
        font-size: 8px;
        color: #94a3b8;
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
        color: #0f1f35;
    }

    /* ── FOOTER PAGE ── */
    .page-footer {
        width: 100%;
        border-collapse: collapse;
        margin-top: 16px;
        border-top: 0.5px solid #e2e8f0;
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
</style>
</head>
<body>

{{-- ══════════════════════════════════════
     KOP SURAT
══════════════════════════════════════ --}}
{{-- FIXED: Pakai <table> HTML asli, bukan div display:table (penyebab Dompdf cellmap error) --}}
<table class="kop">
    <tr>
        <td class="kop-logo">
            <div class="logo-circle">{{ strtoupper(substr(config('sekolah.nama', 'PK'), 0, 2)) }}</div>
        </td>
        <td class="kop-teks">
            <div class="kop-nama">{{ config('sekolah.nama', 'PAUD KB Pelangi') }}</div>
            <div class="kop-alamat">
                {{ config('sekolah.alamat', 'Jl. [Alamat Sekolah]') }}
                &nbsp;·&nbsp; Telp. {{ config('sekolah.telp', '[No. Telp]') }}
                &nbsp;·&nbsp; {{ config('sekolah.email', '') }}
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
    &#x2022; Daftar Pembayaran &mdash; {{ $pembayaran->count() }} transaksi
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
            <td class="nama">{{ $p->siswa->nama_lengkap ?? $p->siswa->nama ?? '—' }}</td>
            <td class="mono">{{ $p->no_kwitansi }}</td>
            <td class="center muted">{{ \Carbon\Carbon::parse($p->tanggal_bayar)->format('d/m/Y') }}</td>
            <td class="nominal">Rp {{ number_format($p->nominal_spp, 0, ',', '.') }}</td>
            <td class="nominal">Rp {{ number_format($p->nominal_kebersihan, 0, ',', '.') }}</td>
            <td class="total">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
            <td class="muted">{{ $p->dicatatOleh->nama_lengkap ?? $p->dicatatOleh->nama ?? '—' }}</td>
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
            <td>—</td>
        </tr>
    </tfoot>
    @endif
</table>

{{-- ══════════════════════════════════════
     TABEL TUNGGAKAN
══════════════════════════════════════ --}}
@if ($tunggakan->count() > 0)

<div class="sec-label red-bg">
    &#x2022; Daftar Tunggakan &mdash; {{ $tunggakan->count() }} siswa belum bayar
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
            <td class="nama">{{ $s->nama_lengkap ?? $s->nama }}</td>
            <td class="center">
                <span class="kel-badge">{{ strtoupper($s->kelompok) }}</span>
            </td>
            <td>{{ $s->nama_wali ?? '—' }}</td>
            <td class="mono">{{ $s->no_hp_wali ?? '—' }}</td>
            <td class="tagihan">Rp {{ number_format(55000, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5">Total Potensi Tunggakan ({{ $tunggakan->count() }} siswa)</td>
            <td class="tunggakan-sum">
                Rp {{ number_format($tunggakan->count() * 55000, 0, ',', '.') }}
            </td>
        </tr>
    </tfoot>
</table>

@else

<div class="all-lunas">
    &#x2713; &nbsp; Semua siswa aktif telah melunasi SPP
    {{ \Carbon\Carbon::create(null, $bulan)->translatedFormat('F') }} {{ $tahun }}.
</div>

@endif

{{-- ══════════════════════════════════════
     TANDA TANGAN
══════════════════════════════════════ --}}
{{-- FIXED: Ganti div display:table/table-cell → <table> HTML native --}}
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
{{-- FIXED: Ganti div display:table → <table> HTML native --}}
<table class="page-footer">
    <tr>
        <td class="pf-left">
            {{ config('sekolah.nama', 'PAUD KB Pelangi') }}
            &nbsp;·&nbsp; Laporan SPP {{ \Carbon\Carbon::create(null, $bulan)->translatedFormat('F') }} {{ $tahun }}
        </td>
        <td class="pf-right">
            Halaman 1 dari 1
        </td>
    </tr>
</table>

</body>
</html>