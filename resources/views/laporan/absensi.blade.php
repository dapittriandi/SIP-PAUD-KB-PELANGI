@extends('layouts.app')

@section('title', 'Laporan Absensi — PAUD KB Pelangi')
@section('page-title', 'Laporan Absensi')

@section('content')
<div class="space-y-5">

    {{-- Filter Periode + Tombol Cetak --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-4">
        <form method="GET" action="{{ route('absensi.laporan') }}" class="flex flex-wrap items-end gap-3">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Bulan</label>
                <select name="bulan" class="px-3 py-2 border border-gray-200 rounded-xl text-sm bg-white focus:outline-none focus:ring-2 focus:ring-pelangi-400">
                    @foreach(range(1,12) as $b)
                    <option value="{{ $b }}" {{ $bulan == $b ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create(null, $b)->translatedFormat('F') }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Tahun</label>
                <select name="tahun" class="px-3 py-2 border border-gray-200 rounded-xl text-sm bg-white focus:outline-none focus:ring-2 focus:ring-pelangi-400">
                    @foreach(range(now()->year, now()->year - 3) as $t)
                    <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit"
                    class="px-5 py-2 bg-pelangi-500 hover:bg-pelangi-600 text-white text-sm font-semibold rounded-xl transition-colors">
                Tampilkan
            </button>
            <a href="{{ route('laporan.absensi', ['bulan' => $bulan, 'tahun' => $tahun, 'format' => 'pdf']) }}"
               target="_blank"
               class="inline-flex items-center gap-2 px-5 py-2 bg-gray-800 hover:bg-gray-700 text-white text-sm font-semibold rounded-xl transition-colors ml-auto">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Cetak / Download PDF
            </a>
        </form>
    </div>

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Rekap Absensi Tutor</h2>
            <p class="text-sm text-gray-500 mt-0.5">
                {{ \Carbon\Carbon::create($tahun, $bulan)->translatedFormat('F Y') }}
                · {{ $hariKerja }} hari kerja efektif
            </p>
        </div>
        <div class="hidden sm:flex items-center gap-4 text-center">
            <div>
                <p class="text-lg font-bold text-gray-800">{{ $ringkasan['total_guru'] }}</p>
                <p class="text-xs text-gray-500">Total Tutor</p>
            </div>
            <div class="w-px h-8 bg-gray-200"></div>
            <div>
                <p class="text-lg font-bold text-green-700">{{ number_format($ringkasan['rata_hadir'], 1) }}</p>
                <p class="text-xs text-gray-500">Rata Hadir</p>
            </div>
            <div class="w-px h-8 bg-gray-200"></div>
            <div>
                <p class="text-lg font-bold text-pelangi-600">{{ number_format($ringkasan['rata_persentase'], 1) }}%</p>
                <p class="text-xs text-gray-500">Rata % Hadir</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Nama Tutor</th>
                        <th class="text-center px-3 py-3 text-xs font-semibold text-green-600 uppercase bg-green-50">H</th>
                        <th class="text-center px-3 py-3 text-xs font-semibold text-yellow-600 uppercase bg-yellow-50">T</th>
                        <th class="text-center px-3 py-3 text-xs font-semibold text-blue-600 uppercase bg-blue-50">I</th>
                        <th class="text-center px-3 py-3 text-xs font-semibold text-blue-600 uppercase bg-blue-50">S</th>
                        <th class="text-center px-3 py-3 text-xs font-semibold text-purple-600 uppercase bg-purple-50">TL</th>
                        <th class="text-center px-3 py-3 text-xs font-semibold text-red-600 uppercase bg-red-50">A</th>
                        <th class="text-center px-3 py-3 text-xs font-semibold text-gray-500 uppercase">% Hadir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($rekap as $r)
                    @php
                        $pct = $r['persentase'];
                        $warnaBar  = $pct >= 90 ? 'bg-green-500'  : ($pct >= 75 ? 'bg-yellow-500' : 'bg-red-500');
                        $warnaTeks = $pct >= 90 ? 'text-green-700' : ($pct >= 75 ? 'text-yellow-700' : 'text-red-700');
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl flex-shrink-0 bg-pelangi-100 flex items-center justify-center text-pelangi-600 font-bold text-xs">
                                    {{ strtoupper(substr($r['guru']->nama_lengkap, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $r['guru']->nama_lengkap }}</p>
                                    <p class="text-xs text-gray-400">{{ $r['guru']->jabatan ?? '' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-3 py-3 text-center font-bold text-green-700">{{ $r['hadir'] }}</td>
                        <td class="px-3 py-3 text-center {{ $r['terlambat'] > 0 ? 'font-bold text-yellow-700' : 'text-gray-300' }}">{{ $r['terlambat'] }}</td>
                        <td class="px-3 py-3 text-center {{ $r['izin'] > 0 ? 'font-bold text-blue-700' : 'text-gray-300' }}">{{ $r['izin'] }}</td>
                        <td class="px-3 py-3 text-center {{ $r['sakit'] > 0 ? 'font-bold text-blue-700' : 'text-gray-300' }}">{{ $r['sakit'] }}</td>
                        <td class="px-3 py-3 text-center {{ $r['tugas_luar'] > 0 ? 'font-bold text-purple-700' : 'text-gray-300' }}">{{ $r['tugas_luar'] }}</td>
                        <td class="px-3 py-3 text-center {{ $r['alpha'] > 0 ? 'font-bold text-red-700' : 'text-gray-300' }}">{{ $r['alpha'] }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <div class="flex-1 bg-gray-100 rounded-full h-1.5 min-w-[50px]">
                                    <div class="{{ $warnaBar }} h-1.5 rounded-full" style="width: {{ $pct }}%"></div>
                                </div>
                                <span class="text-xs font-semibold {{ $warnaTeks }} w-9 text-right">{{ $pct }}%</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-16 text-center text-gray-400 text-sm">
                            Belum ada data absensi untuk periode ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if($rekap->isNotEmpty())
                <tfoot>
                    <tr class="bg-gray-50 border-t-2 border-gray-200 font-semibold text-sm">
                        <td class="px-5 py-3 text-xs text-gray-500 uppercase">Total</td>
                        <td class="px-3 py-3 text-center text-green-700">{{ $rekap->sum('hadir') }}</td>
                        <td class="px-3 py-3 text-center text-yellow-700">{{ $rekap->sum('terlambat') }}</td>
                        <td class="px-3 py-3 text-center text-blue-700">{{ $rekap->sum('izin') }}</td>
                        <td class="px-3 py-3 text-center text-blue-700">{{ $rekap->sum('sakit') }}</td>
                        <td class="px-3 py-3 text-center text-purple-700">{{ $rekap->sum('tugas_luar') }}</td>
                        <td class="px-3 py-3 text-center text-red-700">{{ $rekap->sum('alpha') }}</td>
                        <td class="px-4 py-3 text-center font-bold text-gray-700">{{ number_format($ringkasan['rata_persentase'], 1) }}%</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

    <div class="flex flex-wrap gap-3 text-xs text-gray-500 pb-2">
        <span><span class="font-bold text-green-700">H</span> = Hadir</span>
        <span><span class="font-bold text-yellow-700">T</span> = Terlambat</span>
        <span><span class="font-bold text-blue-700">I</span> = Izin</span>
        <span><span class="font-bold text-blue-700">S</span> = Sakit</span>
        <span><span class="font-bold text-purple-700">TL</span> = Tugas Luar</span>
        <span><span class="font-bold text-red-700">A</span> = Alpha</span>
    </div>

</div>
@endsection