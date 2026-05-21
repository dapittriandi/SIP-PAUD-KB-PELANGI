<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>
    @if(($mode ?? 'bulanan') === 'tahunan')
        Laporan Absensi Tahunan - {{ $tahun }}
    @else
        Laporan Absensi - {{ \Carbon\Carbon::create(null, $bulan)->translatedFormat('F') }} {{ $tahun }}
    @endif
</title>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 8.5px;
        color: #1a1a1a;
        background: #fff;
    }

    /* ── Kop Surat ── */
    .kop {
        text-align: center;
        border-bottom: 2.5px solid #1a1a1a;
        padding-bottom: 8px;
        margin-bottom: 10px;
    }
    .kop .nama-sekolah {
        font-size: 14px;
        font-weight: bold;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }
    .kop .sub {
        font-size: 8.5px;
        color: #555;
        margin-top: 2px;
    }
    .kop .judul-laporan {
        font-size: 10.5px;
        font-weight: bold;
        margin-top: 6px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .kop .periode {
        font-size: 8.5px;
        color: #444;
        margin-top: 2px;
    }

    /* ── Ringkasan ── */
    .ringkasan {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 10px;
    }
    .ringkasan td {
        width: 25%;
        padding: 5px 8px;
        border: 0.5px solid #d1d5db;
        vertical-align: top;
    }
    .ringkasan .r-label { font-size: 7.5px; color: #6b7280; margin-bottom: 2px; }
    .ringkasan .r-value { font-size: 13px; font-weight: bold; color: #111827; }
    .ringkasan .r-unit  { font-size: 7.5px; color: #6b7280; margin-left: 1px; }

    /* ── Tabel Utama (Bulanan) ── */
    table.main {
        width: 100%;
        border-collapse: collapse;
        font-size: 8px;
    }

    table.main thead tr.row-group th {
        background-color: #1e3a5f;
        color: #ffffff;
        padding: 5px 3px;
        text-align: center;
        border: 0.5px solid #1e3a5f;
        white-space: nowrap;
        font-size: 8px;
    }
    table.main thead tr.row-group th.th-left {
        text-align: left;
        padding-left: 5px;
    }
    table.main thead tr.row-tanggal th {
        background-color: #2d4f7c;
        color: #e0e9f5;
        padding: 3px 1px;
        text-align: center;
        border: 0.5px solid #3b5f8e;
        font-size: 7px;
        white-space: nowrap;
    }
    table.main thead tr.row-tanggal th.th-hari {
        font-size: 6.5px;
        color: #cbd5e1;
    }

    table.main tbody tr:nth-child(even) { background-color: #f3f6fb; }
    table.main tbody td {
        padding: 4px 2px;
        border: 0.5px solid #d1d5db;
        text-align: center;
        vertical-align: middle;
        white-space: nowrap;
    }
    table.main tbody td.td-no   { width: 14px; font-size: 8px; }
    table.main tbody td.td-nama {
        text-align: left;
        padding-left: 5px;
        font-weight: 500;
        min-width: 80px;
        white-space: normal;
    }
    table.main tbody td.td-hari { width: 14px; font-size: 8px; }

    .s-H  { color: #065f46; font-weight: 700; font-size: 8px; }
    .s-T  { color: #92400e; font-weight: 700; font-size: 8px; }
    .s-I  { color: #1d4ed8; font-weight: 700; font-size: 8px; }
    .s-S  { color: #c2410c; font-weight: 700; font-size: 8px; }
    .s-TL { color: #6d28d9; font-weight: 700; font-size: 7.5px; }
    .s-A  { color: #991b1b; font-weight: 700; font-size: 8px; }
    .s-LB { color: #9ca3af; font-size: 7px; }
    .s--  { color: #d1d5db; font-size: 8px; }

    td.rek { width: 14px; font-weight: 700; }
    td.rek-H  { color: #065f46; }
    td.rek-T  { color: #92400e; }
    td.rek-I  { color: #1d4ed8; }
    td.rek-S  { color: #c2410c; }
    td.rek-TL { color: #6d28d9; font-size: 7.5px; }
    td.rek-A  { color: #991b1b; }

    .badge {
        display: inline-block;
        padding: 1px 5px;
        border-radius: 3px;
        font-size: 7.5px;
        font-weight: bold;
    }
    .badge-hijau  { background: #d1fae5; color: #065f46; }
    .badge-kuning { background: #fef3c7; color: #92400e; }
    .badge-merah  { background: #fee2e2; color: #991b1b; }

    td.td-ket {
        text-align: left;
        padding-left: 4px;
        min-width: 80px;
        white-space: normal;
        font-size: 7.5px;
        color: #374151;
    }

    table.main tfoot td {
        padding: 4px 3px;
        border: 0.5px solid #94a3b8;
        font-weight: bold;
        text-align: center;
        background-color: #e2e8f0;
        font-size: 8px;
    }
    table.main tfoot td.left { text-align: left; padding-left: 5px; }

    /* ── Tabel Tahunan ── */
    table.tahunan {
        width: 100%;
        border-collapse: collapse;
        font-size: 8px;
    }
    table.tahunan thead th {
        background-color: #1e3a5f;
        color: #fff;
        padding: 5px 4px;
        text-align: center;
        border: 0.5px solid #2d4f7c;
        white-space: nowrap;
        font-size: 8px;
    }
    table.tahunan thead th.th-left {
        text-align: left;
        padding-left: 6px;
    }
    table.tahunan thead th.th-bulan {
        width: 38px;
    }
    table.tahunan thead th.th-sub {
        font-size: 7px;
        color: #cbd5e1;
        font-weight: normal;
    }

    table.tahunan tbody tr:nth-child(even) { background-color: #f3f6fb; }
    table.tahunan tbody td {
        padding: 5px 3px;
        border: 0.5px solid #d1d5db;
        text-align: center;
        vertical-align: middle;
    }
    table.tahunan tbody td.td-no {
        width: 16px;
        font-size: 8px;
        color: #6b7280;
    }
    table.tahunan tbody td.td-nama {
        text-align: left;
        padding-left: 6px;
        font-weight: 600;
        min-width: 90px;
        white-space: normal;
        font-size: 8px;
    }
    table.tahunan tbody td.td-jabatan {
        text-align: left;
        padding-left: 6px;
        font-size: 7px;
        color: #6b7280;
        min-width: 70px;
    }

    /* Badge per bulan */
    .bbl {
        display: inline-block;
        padding: 1.5px 4px;
        border-radius: 2px;
        font-size: 7.5px;
        font-weight: bold;
        min-width: 30px;
        text-align: center;
    }
    .bbl-hijau  { background: #d1fae5; color: #065f46; }
    .bbl-kuning { background: #fef3c7; color: #92400e; }
    .bbl-merah  { background: #fee2e2; color: #991b1b; }
    .bbl-kosong { color: #d1d5db; font-size: 7px; font-weight: normal; }

    /* Sub-row detail (H/A) tahunan */
    .sub-detail { font-size: 6.5px; color: #9ca3af; margin-top: 1px; }

    /* Kolom rata */
    td.td-rata {
        font-weight: bold;
        font-size: 9px;
        min-width: 36px;
    }
    td.td-rata-hijau  { color: #065f46; background: #ecfdf5; }
    td.td-rata-kuning { color: #92400e; background: #fffbeb; }
    td.td-rata-merah  { color: #991b1b; background: #fef2f2; }

    /* Tfoot tahunan */
    table.tahunan tfoot td {
        padding: 4px 3px;
        border: 0.5px solid #94a3b8;
        font-weight: bold;
        text-align: center;
        background-color: #e2e8f0;
        font-size: 8px;
    }
    table.tahunan tfoot td.left {
        text-align: left;
        padding-left: 6px;
    }

    /* ── Footer TTD ── */
    .footer-ttd {
        margin-top: 16px;
        font-size: 8.5px;
    }
    .footer-ttd .tempat-tgl {
        margin-bottom: 14px;
        font-size: 8.5px;
        color: #374151;
    }
    .ttd-table { width: 100%; border-collapse: collapse; }
    .ttd-col {
        width: 33.33%;
        text-align: center;
        vertical-align: top;
        padding: 0 8px;
    }
    .ttd-jabatan {
        font-size: 8.5px;
        color: #374151;
        margin-bottom: 44px;
        line-height: 1.4;
    }
    .ttd-garis {
        border-top: 1px solid #374151;
        padding-top: 3px;
        font-size: 8.5px;
        font-weight: bold;
        color: #111827;
    }
    .ttd-nip { font-size: 7.5px; color: #6b7280; margin-top: 1px; }

    .dicetak {
        margin-top: 10px;
        font-size: 7.5px;
        color: #9ca3af;
        text-align: right;
    }

    .legenda {
        margin-top: 8px;
        margin-bottom: 6px;
        font-size: 7.5px;
        color: #6b7280;
    }
    .legenda span { margin-right: 10px; }
</style>
</head>
<body>

{{-- ── KOP ── --}}
<div class="kop">
    <div class="nama-sekolah">{{ config('sekolah.nama', 'PAUD KB Pelangi') }}</div>
    <div class="sub">Jl. [Alamat Sekolah] &nbsp;|&nbsp; Telp. [No. Telp]</div>

    @if(($mode ?? 'bulanan') === 'tahunan')
    <div class="judul-laporan">Laporan Rekap Absensi Tutor Tahunan</div>
    <div class="periode">
        Tahun Ajaran: {{ $tahun }}
        &nbsp;&nbsp;|&nbsp;&nbsp;
        Total Tutor Aktif: {{ $ringkasan['total_guru'] }} orang
    </div>
    @else
    <div class="judul-laporan">Laporan Rekap Absensi Tutor</div>
    <div class="periode">
        Periode: {{ \Carbon\Carbon::create(null, $bulan)->translatedFormat('F') }} {{ $tahun }}
        &nbsp;&nbsp;|&nbsp;&nbsp;
        Hari Kerja Efektif: {{ $hariKerja }} hari
    </div>
    @endif
</div>

{{-- ── RINGKASAN ── --}}
<table class="ringkasan">
    <tr>
        <td>
            <div class="r-label">Total Tutor</div>
            <div class="r-value">{{ $ringkasan['total_guru'] }}<span class="r-unit"> orang</span></div>
        </td>
        <td>
            <div class="r-label">Rata-rata Hadir</div>
            <div class="r-value">{{ number_format($ringkasan['rata_hadir'], 1) }}<span class="r-unit"> hari</span></div>
        </td>
        <td>
            <div class="r-label">Rata-rata Alpha</div>
            <div class="r-value">{{ number_format($ringkasan['rata_alpha'], 1) }}<span class="r-unit"> hari</span></div>
        </td>
        <td>
            <div class="r-label">Rata-rata Kehadiran</div>
            <div class="r-value">{{ number_format($ringkasan['rata_persentase'], 1) }}<span class="r-unit"> %</span></div>
        </td>
    </tr>
</table>

{{-- ── LEGENDA ── --}}
<table style="width:100%; border-collapse:collapse; margin-top:8px; margin-bottom:6px; font-size:7.5px; color:#374151;">
    <tr>
        <td style="padding:4px 8px; background:#f8fafc; border:0.5px solid #e2e8f0; white-space:nowrap;">
            <b>Keterangan Status:</b>
        </td>
        <td style="padding:4px 6px; background:#f8fafc; border:0.5px solid #e2e8f0; white-space:nowrap;">
            <b style="color:#065f46;">H</b> = Hadir
        </td>
        <td style="padding:4px 6px; background:#f8fafc; border:0.5px solid #e2e8f0; white-space:nowrap;">
            <b style="color:#92400e;">T</b> = Terlambat
        </td>
        <td style="padding:4px 6px; background:#f8fafc; border:0.5px solid #e2e8f0; white-space:nowrap;">
            <b style="color:#1d4ed8;">I</b> = Izin
        </td>
        <td style="padding:4px 6px; background:#f8fafc; border:0.5px solid #e2e8f0; white-space:nowrap;">
            <b style="color:#c2410c;">S</b> = Sakit
        </td>
        <td style="padding:4px 6px; background:#f8fafc; border:0.5px solid #e2e8f0; white-space:nowrap;">
            <b style="color:#6d28d9;">TL</b> = Tugas Luar
        </td>
        <td style="padding:4px 6px; background:#f8fafc; border:0.5px solid #e2e8f0; white-space:nowrap;">
            <b style="color:#991b1b;">A</b> = Alpha
        </td>
        @if(($mode ?? 'bulanan') === 'bulanan')
        <td style="padding:4px 6px; background:#f8fafc; border:0.5px solid #e2e8f0; white-space:nowrap;">
            <b style="color:#9ca3af;">L</b> = Libur
        </td>
        @endif
        <td style="padding:4px 8px; background:#f8fafc; border:0.5px solid #e2e8f0; white-space:nowrap;">
            <b>Kehadiran:</b>
        </td>
        <td style="padding:4px 6px; background:#d1fae5; border:0.5px solid #a7f3d0; color:#065f46; font-weight:700; white-space:nowrap;">
            &gt;= 90%
        </td>
        <td style="padding:4px 6px; background:#fef3c7; border:0.5px solid #fde68a; color:#92400e; font-weight:700; white-space:nowrap;">
            75 - 89%
        </td>
        <td style="padding:4px 6px; background:#fee2e2; border:0.5px solid #fca5a5; color:#991b1b; font-weight:700; white-space:nowrap;">
            &lt; 75%
        </td>
    </tr>
</table>


@if(($mode ?? 'bulanan') === 'tahunan')
{{-- ════════════════════════════════════════
     TABEL TAHUNAN
     Kolom: No | Nama | Jabatan | Jan-Des (pct) | Rata %
════════════════════════════════════════ --}}

@php
    $namaBulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

    // Hitung rata per bulan untuk baris footer
    $totalPerBulan = [];
    for ($b = 1; $b <= 12; $b++) {
        $vals = collect($rekapTahunan)->map(fn($rt) => $rt['per_bulan'][$b - 1]['persentase'] ?? 0)->filter(fn($v) => $v > 0);
        $totalPerBulan[$b] = $vals->isNotEmpty() ? round($vals->avg(), 1) : 0;
    }
    $rataRataGlobal = collect($rekapTahunan)->avg('rata_persentase');
@endphp

<table class="tahunan">
    <thead>
        <tr>
            <th style="width:16px;">No</th>
            <th class="th-left" style="min-width:90px;">Nama Tutor</th>
            <th class="th-left" style="min-width:70px;">Jabatan</th>
            @foreach($namaBulan as $nb)
            <th class="th-bulan">{{ $nb }}</th>
            @endforeach
            <th style="width:40px;">Rata %</th>
        </tr>
    </thead>
    <tbody>
        @forelse($rekapTahunan as $i => $rt)
        @php
            $avgPct = round($rt['rata_persentase'], 1);
            $rataClass = $avgPct >= 90 ? 'td-rata-hijau' : ($avgPct >= 75 ? 'td-rata-kuning' : 'td-rata-merah');
        @endphp
        <tr>
            <td class="td-no">{{ $i + 1 }}</td>
            <td class="td-nama">{{ $rt['guru']->nama_lengkap }}</td>
            <td class="td-jabatan">{{ $rt['guru']->jabatan ?? '-' }}</td>

            @foreach($rt['per_bulan'] as $pb)
            @php
                $pct = $pb['persentase'] ?? 0;
                if ($pct <= 0) {
                    $bblClass = 'bbl-kosong';
                    $label = '-';
                } elseif ($pct >= 90) {
                    $bblClass = 'bbl-hijau';
                    $label = $pct . '%';
                } elseif ($pct >= 75) {
                    $bblClass = 'bbl-kuning';
                    $label = $pct . '%';
                } else {
                    $bblClass = 'bbl-merah';
                    $label = $pct . '%';
                }
            @endphp
            <td>
                <span class="bbl {{ $bblClass }}">{{ $label }}</span>
                @if(isset($pb['hadir']) && $pct > 0)
                <div class="sub-detail">H:{{ $pb['hadir'] }} A:{{ $pb['alpha'] ?? 0 }}</div>
                @endif
            </td>
            @endforeach

            <td class="td-rata {{ $rataClass }}">{{ $avgPct }}%</td>
        </tr>
        @empty
        <tr>
            <td colspan="16" style="text-align:center; color:#9ca3af; padding:16px;">
                Tidak ada data tutor aktif untuk tahun {{ $tahun }}.
            </td>
        </tr>
        @endforelse
    </tbody>

    @if(count($rekapTahunan) > 0)
    <tfoot>
        <tr>
            <td colspan="3" class="left">Rata-rata per Bulan</td>
            @for($b = 1; $b <= 12; $b++)
            @php
                $avg = $totalPerBulan[$b];
                $fStyle = $avg >= 90 ? 'color:#065f46' : ($avg >= 75 ? 'color:#92400e' : ($avg > 0 ? 'color:#991b1b' : 'color:#9ca3af'));
            @endphp
            <td style="{{ $fStyle }}">{{ $avg > 0 ? $avg . '%' : '-' }}</td>
            @endfor
            @php
                $grataClass = $rataRataGlobal >= 90 ? 'color:#065f46' : ($rataRataGlobal >= 75 ? 'color:#92400e' : 'color:#991b1b');
            @endphp
            <td style="{{ $grataClass }}">{{ number_format($rataRataGlobal, 1) }}%</td>
        </tr>
    </tfoot>
    @endif
</table>


@else
{{-- ════════════════════════════════════════
     TABEL BULANAN (per tanggal)
════════════════════════════════════════ --}}

@php
    $jumlahHari = \Carbon\Carbon::create($tahun, $bulan)->daysInMonth;

    $kodeStatus = [
        'hadir'      => 'H',
        'terlambat'  => 'T',
        'izin'       => 'I',
        'sakit'      => 'S',
        'tugas_luar' => 'TL',
        'alpha'      => 'A',
    ];
    $cssStatus = [
        'H' => 's-H', 'T' => 's-T', 'I' => 's-I',
        'S' => 's-S', 'TL' => 's-TL', 'A' => 's-A',
    ];

    $namaHari = ['Min','Sen','Sel','Rab','Kam','Jum','Sab'];
@endphp

<table class="main">
    <thead>
        <tr class="row-group">
            <th rowspan="2" style="width:14px;">No</th>
            <th rowspan="2" class="th-left" style="min-width:80px;">Nama Tutor</th>
            @for($d = 1; $d <= $jumlahHari; $d++)
            @php $tgl = \Carbon\Carbon::create($tahun, $bulan, $d); @endphp
            <th style="width:14px; {{ $tgl->isWeekend() ? 'background:#2a4a2a;' : '' }}">{{ $d }}</th>
            @endfor
            <th style="width:16px; background:#14532d;">H</th>
            <th style="width:16px; background:#78350f;">T</th>
            <th style="width:16px; background:#1e3a8a;">I</th>
            <th style="width:16px; background:#7c2d12;">S</th>
            <th style="width:20px; background:#4c1d95;">TL</th>
            <th style="width:16px; background:#7f1d1d;">A</th>
            <th style="width:36px;">%</th>
            <th style="min-width:80px; text-align:left; padding-left:4px;">Keterangan</th>
        </tr>
        <tr class="row-tanggal">
            @for($d = 1; $d <= $jumlahHari; $d++)
            @php $tgl = \Carbon\Carbon::create($tahun, $bulan, $d); @endphp
            <th class="th-hari" style="{{ $tgl->isWeekend() ? 'background:#2a4a2a;color:#86efac;' : '' }}">
                {{ $namaHari[$tgl->dayOfWeek] }}
            </th>
            @endfor
            <th colspan="8" style="background:#1e3a5f; font-size:7px; color:#cbd5e1; letter-spacing:.04em;">REKAP &amp; KETERANGAN</th>
        </tr>
    </thead>

    <tbody>
        @forelse($rekap as $i => $r)
        @php
            $guru = $r['guru'];
            $absensiPerTgl = collect($r['absensi_harian'] ?? [])->keyBy(fn($a) => \Carbon\Carbon::parse($a['tanggal'])->day);

            $keteranganList = collect($r['absensi_harian'] ?? [])
                ->filter(fn($a) => !in_array($a['status'], ['hadir']) && !empty($a['keterangan']))
                ->map(fn($a) => \Carbon\Carbon::parse($a['tanggal'])->format('d') . ': ' . $a['keterangan'])
                ->values();
        @endphp
        <tr>
            <td class="td-no">{{ $i + 1 }}</td>
            <td class="td-nama">{{ $guru->nama_lengkap }}</td>

            @for($d = 1; $d <= $jumlahHari; $d++)
            @php
                $tgl     = \Carbon\Carbon::create($tahun, $bulan, $d);
                $absen   = $absensiPerTgl->get($d);
                $isLibur = $tgl->isWeekend();

                if ($isLibur) {
                    $kode = 'L';
                    $css  = 's-LB';
                } elseif ($absen) {
                    $kode = $kodeStatus[$absen['status']] ?? '-';
                    $css  = $cssStatus[$kode] ?? 's--';
                } else {
                    $kode = '-';
                    $css  = 's--';
                }
            @endphp
            <td class="td-hari" style="{{ $isLibur ? 'background:#f0fdf4;' : '' }}">
                <span class="{{ $css }}">{{ $kode }}</span>
            </td>
            @endfor

            <td class="rek rek-H">{{ $r['hadir'] }}</td>
            <td class="rek rek-T">{{ $r['terlambat'] }}</td>
            <td class="rek rek-I">{{ $r['izin'] }}</td>
            <td class="rek rek-S">{{ $r['sakit'] }}</td>
            <td class="rek rek-TL">{{ $r['tugas_luar'] }}</td>
            <td class="rek rek-A">{{ $r['alpha'] }}</td>

            <td>
                @php $pct = $r['persentase']; @endphp
                <span class="badge {{ $pct >= 90 ? 'badge-hijau' : ($pct >= 75 ? 'badge-kuning' : 'badge-merah') }}">
                    {{ $pct }}%
                </span>
            </td>

            <td class="td-ket">
                @if($keteranganList->isEmpty())
                    <span style="color:#d1d5db;">-</span>
                @else
                    @foreach($keteranganList as $ket)
                    <div style="border-bottom:0.5px solid #f3f6fb; padding:1.5px 0; line-height:1.4;">
                        <span style="font-weight:700; color:#1e3a5f; font-size:7px;">Tgl {{ explode(': ', $ket)[0] ?? '' }}:</span>
                        <span style="color:#374151; font-size:7px;">{{ implode(': ', array_slice(explode(': ', $ket), 1)) }}</span>
                    </div>
                    @endforeach
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="{{ $jumlahHari + 10 }}" style="text-align:center; color:#9ca3af; padding:12px;">
                Tidak ada data tutor aktif pada periode ini.
            </td>
        </tr>
        @endforelse
    </tbody>

    <tfoot>
        <tr>
            <td colspan="2" class="left">Total</td>
            @for($d = 1; $d <= $jumlahHari; $d++)
            <td>-</td>
            @endfor
            <td>{{ $rekap->sum('hadir') }}</td>
            <td>{{ $rekap->sum('terlambat') }}</td>
            <td>{{ $rekap->sum('izin') }}</td>
            <td>{{ $rekap->sum('sakit') }}</td>
            <td>{{ $rekap->sum('tugas_luar') }}</td>
            <td>{{ $rekap->sum('alpha') }}</td>
            <td>{{ number_format($ringkasan['rata_persentase'], 1) }}%</td>
            <td>-</td>
        </tr>
    </tfoot>
</table>

@endif


{{-- ── FOOTER TTD ── --}}
<div class="footer-ttd">
    <div class="tempat-tgl">
        [Nama Kota], {{ now()->translatedFormat('d F Y') }}
    </div>

    <table class="ttd-table">
        <tr>
            <td class="ttd-col">
                <div class="ttd-jabatan">
                    Mengetahui,<br>
                    <b>Kepala Sekolah</b>
                </div>
                <div class="ttd-garis">(__________________________)</div>
                <div class="ttd-nip">NIP. ____________________</div>
            </td>
            <td class="ttd-col">
                <div class="ttd-jabatan">
                    Menyetujui,<br>
                    <b>Pengawas / Koordinator</b>
                </div>
                <div class="ttd-garis">(__________________________)</div>
                <div class="ttd-nip">NIP. ____________________</div>
            </td>
            <td class="ttd-col">
                <div class="ttd-jabatan">
                    Dibuat oleh,<br>
                    <b>Administrator</b>
                </div>
                <div class="ttd-garis">(__________________________)</div>
                <div class="ttd-nip">NIP. ____________________</div>
            </td>
        </tr>
    </table>

    <div class="dicetak">
        Dicetak: {{ now()->translatedFormat('d F Y, H:i') }} WIB &nbsp;|&nbsp; Sistem Informasi PAUD KB Pelangi
    </div>
</div>

</body>
</html>