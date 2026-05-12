<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Laporan Absensi Guru — {{ \Carbon\Carbon::create(null, $bulan)->translatedFormat('F') }} {{ $tahun }}</title>
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

    /* ── Tabel Utama ── */
    table.main {
        width: 100%;
        border-collapse: collapse;
        font-size: 8px;
    }

    /* Header baris 1 & 2 */
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

    /* Body */
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

    /* Status per-tanggal */
    .s-H  { color: #065f46; font-weight: 700; font-size: 8px; }   /* Hadir */
    .s-T  { color: #92400e; font-weight: 700; font-size: 8px; }   /* Terlambat */
    .s-I  { color: #1d4ed8; font-weight: 700; font-size: 8px; }   /* Izin */
    .s-S  { color: #c2410c; font-weight: 700; font-size: 8px; }   /* Sakit */
    .s-TL { color: #6d28d9; font-weight: 700; font-size: 7.5px; } /* Tugas Luar */
    .s-A  { color: #991b1b; font-weight: 700; font-size: 8px; }   /* Alpha */
    .s-LB { color: #9ca3af; font-size: 7px; }                     /* Libur */
    .s--  { color: #d1d5db; font-size: 8px; }                     /* kosong */

    /* Kolom rekap (I,S,A,H,T,TL) */
    td.rek { width: 14px; font-weight: 700; }
    td.rek-H  { color: #065f46; }
    td.rek-T  { color: #92400e; }
    td.rek-I  { color: #1d4ed8; }
    td.rek-S  { color: #c2410c; }
    td.rek-TL { color: #6d28d9; font-size: 7.5px; }
    td.rek-A  { color: #991b1b; }

    /* Badge kehadiran */
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

    /* Keterangan */
    td.td-ket {
        text-align: left;
        padding-left: 4px;
        min-width: 80px;
        white-space: normal;
        font-size: 7.5px;
        color: #374151;
    }

    /* Footer tfoot */
    table.main tfoot td {
        padding: 4px 3px;
        border: 0.5px solid #94a3b8;
        font-weight: bold;
        text-align: center;
        background-color: #e2e8f0;
        font-size: 8px;
    }
    table.main tfoot td.left { text-align: left; padding-left: 5px; }

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
    .ttd-table {
        width: 100%;
        border-collapse: collapse;
    }
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
    .ttd-nip {
        font-size: 7.5px;
        color: #6b7280;
        margin-top: 1px;
    }

    /* Dicetak */
    .dicetak {
        margin-top: 10px;
        font-size: 7.5px;
        color: #9ca3af;
        text-align: right;
    }

    /* Legenda */
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
    <div class="judul-laporan">Laporan Rekap Absensi Tutor</div>
    <div class="periode">
        Periode: {{ \Carbon\Carbon::create(null, $bulan)->translatedFormat('F') }} {{ $tahun }}
        &nbsp;&nbsp;|&nbsp;&nbsp;
        Hari Kerja Efektif: {{ $hariKerja }} hari
    </div>
</div>

{{-- ── RINGKASAN ── --}}
<table class="ringkasan">
    <tr>
        <td>
            <div class="r-label">Total Tutor</div>
            <div class="r-value">{{ $ringkasan['total_guru'] }}<span class="r-unit">orang</span></div>
        </td>
        <td>
            <div class="r-label">Rata-rata Hadir</div>
            <div class="r-value">{{ number_format($ringkasan['rata_hadir'], 1) }}<span class="r-unit">hari</span></div>
        </td>
        <td>
            <div class="r-label">Rata-rata Alpha</div>
            <div class="r-value">{{ number_format($ringkasan['rata_alpha'], 1) }}<span class="r-unit">hari</span></div>
        </td>
        <td>
            <div class="r-label">Rata-rata Kehadiran</div>
            <div class="r-value">{{ number_format($ringkasan['rata_persentase'], 1) }}<span class="r-unit">%</span></div>
        </td>
    </tr>
</table>

{{-- ── LEGENDA ── --}}
<div class="legenda">
    Keterangan:
    <span><b style="color:#065f46">H</b> = Hadir</span>
    <span><b style="color:#92400e">T</b> = Terlambat</span>
    <span><b style="color:#1d4ed8">I</b> = Izin</span>
    <span><b style="color:#c2410c">S</b> = Sakit</span>
    <span><b style="color:#6d28d9">TL</b> = Tugas Luar</span>
    <span><b style="color:#991b1b">A</b> = Alpha</span>
    <span style="color:#9ca3af">L = Libur</span>
</div>

{{-- ── TABEL REKAP ── --}}
@php
    // Hitung jumlah hari dalam bulan
    $jumlahHari = \Carbon\Carbon::create($tahun, $bulan)->daysInMonth;

    // Mapping status → kode singkat
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

    // Nama hari singkat (Sen, Sel, Rab, ..., Min)
    $namaHari = ['Min','Sen','Sel','Rab','Kam','Jum','Sab'];
@endphp

<table class="main">
    <thead>
        {{-- Baris 1: No | Nama Tutor | Tgl 1–N | H | T | I | S | TL | A | % | Keterangan --}}
        <tr class="row-group">
            <th rowspan="2" style="width:14px;">No</th>
            <th rowspan="2" class="th-left" style="min-width:80px;">Nama Tutor</th>
            {{-- Kolom tanggal --}}
            @for($d = 1; $d <= $jumlahHari; $d++)
            @php $tgl = \Carbon\Carbon::create($tahun, $bulan, $d); @endphp
            <th style="width:14px; {{ $tgl->isWeekend() ? 'background:#2a4a2a;' : '' }}">{{ $d }}</th>
            @endfor
            {{-- Kolom rekap --}}
            <th style="width:16px; background:#14532d;">H</th>
            <th style="width:16px; background:#78350f;">T</th>
            <th style="width:16px; background:#1e3a8a;">I</th>
            <th style="width:16px; background:#7c2d12;">S</th>
            <th style="width:20px; background:#4c1d95;">TL</th>
            <th style="width:16px; background:#7f1d1d;">A</th>
            <th style="width:36px;">%</th>
            <th style="min-width:80px; text-align:left; padding-left:4px;">Keterangan</th>
        </tr>
        {{-- Baris 2: nama hari --}}
        <tr class="row-tanggal">
            @for($d = 1; $d <= $jumlahHari; $d++)
            @php $tgl = \Carbon\Carbon::create($tahun, $bulan, $d); @endphp
            <th class="th-hari" style="{{ $tgl->isWeekend() ? 'background:#2a4a2a;color:#86efac;' : '' }}">
                {{ $namaHari[$tgl->dayOfWeek] }}
            </th>
            @endfor
            {{-- colspan untuk kolom rekap & keterangan --}}
            <th colspan="8" style="background:#1e3a5f; font-size:7px; color:#cbd5e1; letter-spacing:.04em;">REKAP &amp; KETERANGAN</th>
        </tr>
    </thead>

    <tbody>
        @forelse($rekap as $i => $r)
        @php
            $guru = $r['guru'];
            // Index absensi per tanggal untuk guru ini
            $absensiPerTgl = collect($r['absensi_harian'] ?? [])->keyBy(fn($a) => \Carbon\Carbon::parse($a['tanggal'])->day);

            // Kumpulkan keterangan (status non-hadir yang ada keterangannya)
            $keteranganList = collect($r['absensi_harian'] ?? [])
                ->filter(fn($a) => !in_array($a['status'], ['hadir']) && !empty($a['keterangan']))
                ->map(fn($a) => \Carbon\Carbon::parse($a['tanggal'])->format('d') . ': ' . $a['keterangan'])
                ->values();
        @endphp
        <tr>
            <td class="td-no">{{ $i + 1 }}</td>
            <td class="td-nama">{{ $guru->nama_lengkap }}</td>

            {{-- Kolom per tanggal --}}
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

            {{-- Rekap --}}
            <td class="rek rek-H">{{ $r['hadir'] }}</td>
            <td class="rek rek-T">{{ $r['terlambat'] }}</td>
            <td class="rek rek-I">{{ $r['izin'] }}</td>
            <td class="rek rek-S">{{ $r['sakit'] }}</td>
            <td class="rek rek-TL">{{ $r['tugas_luar'] }}</td>
            <td class="rek rek-A">{{ $r['alpha'] }}</td>

            {{-- % Kehadiran --}}
            <td>
                @php $pct = $r['persentase']; @endphp
                <span class="badge {{ $pct >= 90 ? 'badge-hijau' : ($pct >= 75 ? 'badge-kuning' : 'badge-merah') }}">
                    {{ $pct }}%
                </span>
            </td>

            {{-- Keterangan --}}
            <td class="td-ket">
                {{ $keteranganList->implode('; ') ?: '-' }}
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
            <td>—</td>
            @endfor
            <td>{{ $rekap->sum('hadir') }}</td>
            <td>{{ $rekap->sum('terlambat') }}</td>
            <td>{{ $rekap->sum('izin') }}</td>
            <td>{{ $rekap->sum('sakit') }}</td>
            <td>{{ $rekap->sum('tugas_luar') }}</td>
            <td>{{ $rekap->sum('alpha') }}</td>
            <td>{{ number_format($ringkasan['rata_persentase'], 1) }}%</td>
            <td>—</td>
        </tr>
    </tfoot>
</table>

{{-- ── FOOTER TTD ── --}}
<div class="footer-ttd">
    <div class="tempat-tgl">
        [Nama Kota], {{ now()->translatedFormat('d F Y') }}
    </div>

    <table class="ttd-table">
        <tr>
            {{-- Mengetahui: Kepala Sekolah --}}
            <td class="ttd-col">
                <div class="ttd-jabatan">
                    Mengetahui,<br>
                    <b>Kepala Sekolah</b>
                </div>
                <div class="ttd-garis">(__________________________)</div>
                <div class="ttd-nip">NIP. ____________________</div>
            </td>

            {{-- Tengah kosong / bisa diisi nama lain --}}
            <td class="ttd-col">
                <div class="ttd-jabatan">
                    Menyetujui,<br>
                    <b>Pengawas / Koordinator</b>
                </div>
                <div class="ttd-garis">(__________________________)</div>
                <div class="ttd-nip">NIP. ____________________</div>
            </td>

            {{-- Dibuat oleh: Admin --}}
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