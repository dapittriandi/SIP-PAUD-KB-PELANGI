{{-- resources/views/absensi/izin.blade.php --}}
@extends('layouts.app')

@section('title', 'Lapor Izin / Sakit — PAUD KB Pelangi')
@section('page-title', 'Lapor Izin / Sakit')

@section('content')
<div class="max-w-sm mx-auto space-y-3 pb-4">

    {{-- Back + Header --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('absensi.index') }}"
           class="w-9 h-9 flex items-center justify-center rounded-xl bg-white dark:bg-gray-900
                  border border-gray-100 dark:border-gray-800
                  text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <p class="text-base font-semibold text-gray-800 dark:text-gray-100">Lapor Izin / Sakit</p>
            <p class="text-xs text-gray-400 dark:text-gray-500">{{ \Carbon\Carbon::today()->translatedFormat('l, d F Y') }}</p>
        </div>
    </div>

    {{-- ── Sudah ada absensi hari ini ── --}}
    @if ($absenHariIni)
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 overflow-hidden">
            <div class="@php
                $bar = match($absenHariIni->status) {
                    'hadir'      => 'bg-emerald-500',
                    'terlambat'  => 'bg-amber-400',
                    'izin','sakit','tugas_luar' => 'bg-blue-500',
                    'alpha'      => 'bg-red-500',
                    default      => 'bg-gray-400',
                };
            @endphp {{ $bar }} h-1"></div>
            <div class="p-6 text-center">
                <div class="w-14 h-14 rounded-full mx-auto mb-3 flex items-center justify-center
                    @php
                        $bg = match($absenHariIni->status) {
                            'hadir','terlambat' => 'bg-emerald-100 dark:bg-emerald-900/30',
                            'izin','sakit','tugas_luar' => 'bg-blue-100 dark:bg-blue-900/30',
                            default => 'bg-gray-100 dark:bg-gray-800',
                        };
                    @endphp {{ $bg }}">
                    @switch($absenHariIni->status)
                        @case('hadir') @case('terlambat')
                            <svg class="w-7 h-7 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            @break
                        @default
                            <svg class="w-7 h-7 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                    @endswitch
                </div>
                <p class="text-sm font-bold text-gray-800 dark:text-gray-100 mb-1">Absensi Sudah Tercatat</p>
                @php
                    $badgeStyle = match($absenHariIni->status) {
                        'hadir'      => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300',
                        'terlambat'  => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300',
                        'izin'       => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300',
                        'sakit'      => 'bg-sky-100 text-sky-700 dark:bg-sky-900/40 dark:text-sky-300',
                        'tugas_luar' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300',
                        'alpha'      => 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300',
                        default      => 'bg-gray-100 text-gray-600',
                    };
                @endphp
                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $badgeStyle }}">
                    {{ $absenHariIni->label_status }}
                </span>
                @if ($absenHariIni->jam_masuk)
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-3">
                        Masuk pukul <span class="font-semibold">{{ \Carbon\Carbon::parse($absenHariIni->jam_masuk)->format('H:i') }} WIB</span>
                    </p>
                @endif
                @if ($absenHariIni->keterangan)
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1 italic">"{{ $absenHariIni->keterangan }}"</p>
                @endif
                <div class="mt-4 bg-amber-50 dark:bg-amber-900/20 rounded-xl px-4 py-3">
                    <p class="text-xs text-amber-700 dark:text-amber-300 font-medium">
                        Absensi hari ini sudah tercatat. Jika ada kesalahan, hubungi admin.
                    </p>
                </div>
            </div>
        </div>

    {{-- ── Form izin mandiri ── --}}
    @else

        {{-- Error alerts --}}
        @if (session('error'))
            <div class="flex items-start gap-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 rounded-xl px-4 py-3 text-sm">
                <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 rounded-xl px-4 py-3 text-sm">
                <ul class="space-y-1">
                    @foreach ($errors->all() as $error)
                        <li class="flex items-start gap-2">
                            <span class="mt-2 w-1.5 h-1.5 rounded-full bg-red-400 flex-shrink-0"></span>
                            {{ $error }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 overflow-hidden"
             x-data="{
                 status: '{{ old('status', '') }}',
                 keterangan: '{{ old('keterangan', '') }}',
                 get labelTombol() {
                     const map = { izin: 'Izin', sakit: 'Sakit', tugas_luar: 'Tugas Luar' }
                     return this.status ? 'Kirim Laporan ' + (map[this.status] ?? '') : 'Pilih jenis keterangan dulu'
                 },
                 get bolehKirim() {
                     return this.status !== '' && this.keterangan.trim().length >= 5
                 }
             }">

            <form action="{{ route('absensi.izin.store') }}" method="POST" novalidate class="p-5 space-y-5">
                @csrf

                {{-- Pilihan status --}}
                <div>
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">
                        Jenis Keterangan <span class="text-red-400">*</span>
                    </p>

                    <div class="grid grid-cols-3 gap-2">
                        @foreach ([
                            ['value' => 'izin',       'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'label' => 'Izin',       'ring' => 'border-blue-500 bg-blue-50 dark:bg-blue-900/30',   'icon_color' => 'text-blue-600 dark:text-blue-400',   'text_color' => 'text-blue-700 dark:text-blue-300'],
                            ['value' => 'sakit',      'icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z', 'label' => 'Sakit',      'ring' => 'border-sky-500 bg-sky-50 dark:bg-sky-900/30',     'icon_color' => 'text-sky-600 dark:text-sky-400',     'text_color' => 'text-sky-700 dark:text-sky-300'],
                            ['value' => 'tugas_luar', 'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'label' => 'Tugas',      'ring' => 'border-purple-500 bg-purple-50 dark:bg-purple-900/30', 'icon_color' => 'text-purple-600 dark:text-purple-400', 'text_color' => 'text-purple-700 dark:text-purple-300'],
                        ] as $opt)
                            <label class="cursor-pointer select-none">
                                <input type="radio"
                                       name="status"
                                       value="{{ $opt['value'] }}"
                                       x-model="status"
                                       class="sr-only">
                                <div :class="status === '{{ $opt['value'] }}'
                                        ? '{{ $opt['ring'] }} border-2'
                                        : 'border border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600'"
                                     class="rounded-xl p-3 text-center transition-all duration-150">
                                    <div class="w-8 h-8 rounded-lg mx-auto mb-2 flex items-center justify-center
                                        @if($opt['value'] === 'izin') bg-blue-100 dark:bg-blue-900/40
                                        @elseif($opt['value'] === 'sakit') bg-sky-100 dark:bg-sky-900/40
                                        @else bg-purple-100 dark:bg-purple-900/40
                                        @endif">
                                        <svg class="w-4 h-4 {{ $opt['icon_color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $opt['icon'] }}"/>
                                        </svg>
                                    </div>
                                    <div class="text-xs font-semibold
                                        @if($opt['value'] === 'izin') text-gray-600 dark:text-gray-300
                                        @elseif($opt['value'] === 'sakit') text-gray-600 dark:text-gray-300
                                        @else text-gray-600 dark:text-gray-300
                                        @endif"
                                        :class="status === '{{ $opt['value'] }}' ? '{{ $opt['text_color'] }}' : ''">
                                        {{ $opt['label'] }}
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('status')
                        <p class="text-red-500 dark:text-red-400 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Keterangan — muncul setelah status dipilih --}}
                <div x-show="status !== ''"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0">

                    <label for="keterangan" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                        Keterangan <span class="text-red-400">*</span>
                    </label>

                    <textarea id="keterangan"
                              name="keterangan"
                              rows="3"
                              maxlength="500"
                              x-model="keterangan"
                              :placeholder="status === 'sakit'
                                  ? 'Mis: demam sejak semalam, tidak bisa masuk...'
                                  : status === 'tugas_luar'
                                      ? 'Mis: pelatihan di Dinas Pendidikan...'
                                      : 'Mis: keperluan keluarga mendesak...'"
                              class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 text-sm text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent resize-none transition"
                    >{{ old('keterangan') }}</textarea>

                    <div class="flex justify-between mt-1.5">
                        @error('keterangan')
                            <p class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</p>
                        @else
                            <p class="text-gray-400 dark:text-gray-500 text-xs">Minimal 5 karakter</p>
                        @enderror
                        <p class="text-gray-400 dark:text-gray-500 text-xs ml-auto"
                           x-text="keterangan.length + '/500'"></p>
                    </div>
                </div>

                {{-- Info kontekstual --}}
                <div x-show="status === 'sakit'" x-transition
                     class="bg-sky-50 dark:bg-sky-900/20 border border-sky-200 dark:border-sky-800 rounded-xl px-4 py-3 text-xs text-sky-700 dark:text-sky-300 flex items-start gap-2">
                    <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Sakit lebih dari 2 hari? Siapkan surat keterangan dokter untuk diserahkan ke admin.
                </div>
                <div x-show="status === 'tugas_luar'" x-transition
                     class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-xl px-4 py-3 text-xs text-purple-700 dark:text-purple-300 flex items-start gap-2">
                    <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Pastikan tugas luar sudah dikomunikasikan ke kepala sekolah sebelumnya.
                </div>

                {{-- Tombol submit --}}
                <button type="submit"
                        :disabled="!bolehKirim"
                        :class="bolehKirim
                            ? 'bg-brand-500 hover:bg-brand-600 text-white active:scale-[0.98] shadow-sm'
                            : 'bg-gray-100 dark:bg-gray-800 text-gray-400 dark:text-gray-600 cursor-not-allowed'"
                        class="w-full py-3 rounded-xl font-semibold text-sm transition-all duration-150 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    <span x-text="labelTombol"></span>
                </button>

            </form>
        </div>

        <p class="text-xs text-gray-400 dark:text-gray-500 text-center pb-2">
            Laporan berlaku untuk hari ini. Kesalahan? Hubungi admin.
        </p>

    @endif
</div>
@endsection