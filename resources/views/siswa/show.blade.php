@extends('layouts.app')

@section('title', $siswa->nama_lengkap . ' — PAUD KB Pelangi')
@section('page-title', 'Detail Siswa')

@section('content')
<div class="max-w-3xl mx-auto space-y-5">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('siswa.index') }}" class="hover:text-pelangi-600 transition-colors">Data Siswa</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-gray-800 font-medium">{{ $siswa->nama_lengkap }}</span>
    </div>

    {{-- Header kartu --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-6 flex items-center gap-5">
        <div class="w-20 h-20 rounded-2xl overflow-hidden flex-shrink-0 bg-pelangi-100">
            @if($siswa->foto)
                <img src="{{ Storage::url($siswa->foto) }}" alt="{{ $siswa->nama_lengkap }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full flex items-center justify-center text-pelangi-600 font-bold text-2xl">
                    {{ strtoupper(substr($siswa->nama_lengkap, 0, 1)) }}
                </div>
            @endif
        </div>
        <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between gap-3 flex-wrap">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">{{ $siswa->nama_lengkap }}</h2>
                    <p class="text-sm text-gray-500 mt-0.5">
                        {{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                        @if($siswa->umur) · {{ $siswa->umur }} @endif
                    </p>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="px-2.5 py-1 bg-blue-50 text-blue-700 rounded-lg text-xs font-semibold">{{ $siswa->kelompok }}</span>
                        @if($siswa->aktif)
                            <span class="px-2.5 py-1 bg-green-50 text-green-700 rounded-lg text-xs font-semibold">Aktif</span>
                        @else
                            <span class="px-2.5 py-1 bg-red-50 text-red-600 rounded-lg text-xs font-semibold">Nonaktif</span>
                        @endif
                    </div>
                </div>
                @if(Auth::user()->isAdmin())
                <a href="{{ route('siswa.edit', $siswa) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-pelangi-500 hover:bg-pelangi-600 text-white text-sm font-semibold rounded-xl transition-colors flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Grid detail --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        {{-- Identitas --}}
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800 text-sm">Identitas</h3>
            </div>
            <div class="p-5 space-y-3">
                @php
                $items = [
                    'NIS'           => $siswa->nis ?? '-',
                    'Tempat, Tgl Lahir' => collect([$siswa->tempat_lahir, $siswa->tanggal_lahir?->translatedFormat('d F Y')])->filter()->join(', ') ?: '-',
                    'Agama'         => $siswa->agama ?? '-',
                    'Alamat'        => $siswa->alamat ?? '-',
                    'Tahun Ajaran'  => $siswa->tahun_ajaran ?? '-',
                    'Tgl Masuk'     => $siswa->tanggal_masuk?->translatedFormat('d F Y') ?? '-',
                ];
                @endphp
                @foreach($items as $label => $value)
                <div class="flex gap-3">
                    <span class="text-xs text-gray-500 w-32 flex-shrink-0 pt-0.5">{{ $label }}</span>
                    <span class="text-sm text-gray-800 font-medium">{{ $value }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Orang Tua --}}
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800 text-sm">Orang Tua / Wali</h3>
            </div>
            <div class="p-5 space-y-4">
                @if($siswa->nama_ayah)
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Ayah</p>
                    <p class="text-sm font-medium text-gray-800">{{ $siswa->nama_ayah }}</p>
                    @if($siswa->pekerjaan_ayah)<p class="text-xs text-gray-500">{{ $siswa->pekerjaan_ayah }}</p>@endif
                    @if($siswa->no_hp_ayah)<p class="text-xs text-pelangi-600">📞 {{ $siswa->no_hp_ayah }}</p>@endif
                </div>
                @endif
                @if($siswa->nama_ibu)
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Ibu</p>
                    <p class="text-sm font-medium text-gray-800">{{ $siswa->nama_ibu }}</p>
                    @if($siswa->pekerjaan_ibu)<p class="text-xs text-gray-500">{{ $siswa->pekerjaan_ibu }}</p>@endif
                    @if($siswa->no_hp_ibu)<p class="text-xs text-pelangi-600">📞 {{ $siswa->no_hp_ibu }}</p>@endif
                </div>
                @endif
                @if($siswa->nama_wali)
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Wali ({{ $siswa->hubungan_wali ?? '-' }})</p>
                    <p class="text-sm font-medium text-gray-800">{{ $siswa->nama_wali }}</p>
                    @if($siswa->no_hp_wali)<p class="text-xs text-pelangi-600">📞 {{ $siswa->no_hp_wali }}</p>@endif
                </div>
                @endif
                @if(!$siswa->nama_ayah && !$siswa->nama_ibu && !$siswa->nama_wali)
                    <p class="text-sm text-gray-400">Belum ada data orang tua / wali.</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Riwayat SPP --}}
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800 text-sm">Riwayat Pembayaran SPP</h3>
            @if(in_array(Auth::user()->role, ['admin', 'bendahara']))
            <a href="{{ route('spp.create', ['siswa_id' => $siswa->id]) }}"
               class="text-xs text-pelangi-600 font-semibold hover:underline">+ Input SPP</a>
            @endif
        </div>
        @if($siswa->pembayaranSpp->isEmpty())
            <div class="py-8 text-center text-sm text-gray-400">Belum ada pembayaran SPP tercatat.</div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Periode</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">No. Kwitansi</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Total</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Tgl Bayar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($siswa->pembayaranSpp->sortByDesc('tahun')->sortByDesc('bulan') as $spp)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3 font-medium text-gray-800">{{ $spp->nama_bulan }} {{ $spp->tahun }}</td>
                        <td class="px-4 py-3 text-gray-500 font-mono text-xs">{{ $spp->no_kwitansi }}</td>
                        <td class="px-4 py-3 text-right text-gray-800 font-semibold">{{ $spp->total_rupiah }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $spp->tanggal_bayar->translatedFormat('d M Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

</div>
@endsection