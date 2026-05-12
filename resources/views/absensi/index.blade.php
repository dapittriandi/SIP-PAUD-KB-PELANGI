@extends('layouts.app')

@section('title', 'Absensi — PAUD KB Pelangi')
@section('page-title', 'Absensi Saya')

@section('content')
<div class="max-w-sm mx-auto space-y-3 pb-4" x-data="absensiGPS()">

    {{-- ── Header: Tanggal & Jam ── --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 px-5 py-4 flex items-center justify-between">
        <div>
            <p class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wide">
                {{ now()->translatedFormat('l') }}
            </p>
            <p class="text-sm font-semibold text-gray-700 dark:text-gray-200 mt-0.5">
                {{ now()->translatedFormat('d F Y') }}
            </p>
        </div>
        <div class="text-right">
            <p class="text-2xl font-bold text-gray-800 dark:text-white tabular-nums tracking-tight" x-text="jamSekarang"></p>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">WIB</p>
        </div>
    </div>

    {{-- ── SUDAH ABSEN ── --}}
    @if($absenHariIni)
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 overflow-hidden">
        {{-- Status bar --}}
        <div class="@php
            $barClass = match($absenHariIni->status) {
                'hadir'      => 'bg-emerald-500',
                'terlambat'  => 'bg-amber-400',
                'izin','sakit','tugas_luar' => 'bg-blue-500',
                'alpha'      => 'bg-red-500',
                default      => 'bg-gray-400',
            };
        @endphp {{ $barClass }} h-1 w-full"></div>

        <div class="p-5 text-center">
            {{-- Icon --}}
            <div class="w-14 h-14 rounded-full mx-auto mb-3 flex items-center justify-center
                @php
                    $iconBg = match($absenHariIni->status) {
                        'hadir'      => 'bg-emerald-100 dark:bg-emerald-900/30',
                        'terlambat'  => 'bg-amber-100 dark:bg-amber-900/30',
                        'izin','sakit','tugas_luar' => 'bg-blue-100 dark:bg-blue-900/30',
                        'alpha'      => 'bg-red-100 dark:bg-red-900/30',
                        default      => 'bg-gray-100 dark:bg-gray-800',
                    };
                @endphp {{ $iconBg }}">
                @if(in_array($absenHariIni->status, ['hadir','terlambat']))
                    <svg class="w-7 h-7 {{ $absenHariIni->status === 'terlambat' ? 'text-amber-600 dark:text-amber-400' : 'text-emerald-600 dark:text-emerald-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                @elseif(in_array($absenHariIni->status, ['izin','sakit','tugas_luar']))
                    <svg class="w-7 h-7 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                @else
                    <svg class="w-7 h-7 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                @endif
            </div>

            <p class="text-base font-bold text-gray-800 dark:text-gray-100">Absensi Tercatat</p>
            <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-semibold
                @php
                    $badge = match($absenHariIni->status) {
                        'hadir'      => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300',
                        'terlambat'  => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300',
                        'izin'       => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300',
                        'sakit'      => 'bg-sky-100 text-sky-700 dark:bg-sky-900/40 dark:text-sky-300',
                        'tugas_luar' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300',
                        'alpha'      => 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300',
                        default      => 'bg-gray-100 text-gray-700',
                    };
                @endphp {{ $badge }}">
                {{ $absenHariIni->label_status }}
            </span>

            {{-- Detail info --}}
            <div class="mt-4 space-y-1.5">
                @if($absenHariIni->jam_masuk)
                <div class="flex items-center justify-center gap-1.5 text-sm text-gray-500 dark:text-gray-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Jam masuk: <span class="font-semibold text-gray-700 dark:text-gray-200">{{ substr($absenHariIni->jam_masuk, 0, 5) }} WIB</span>
                </div>
                @endif
                @if($absenHariIni->terlambat_menit > 0)
                <p class="text-xs font-medium text-amber-600 dark:text-amber-400">
                    ⚠ Terlambat {{ $absenHariIni->terlambat_menit }} menit
                </p>
                @endif
                @if($absenHariIni->keterangan)
                <p class="text-xs text-gray-400 dark:text-gray-500 italic">"{{ $absenHariIni->keterangan }}"</p>
                @endif
                @if($absenHariIni->isSelfCheckIn())
                <div class="flex items-center justify-center gap-1 text-xs text-gray-400 dark:text-gray-500 mt-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    GPS · {{ $absenHariIni->jarak_meter }}m dari sekolah
                </div>
                @else
                <p class="text-xs text-gray-400 dark:text-gray-500">Dicatat oleh admin</p>
                @endif
            </div>
        </div>
    </div>

    {{-- ── BELUM ABSEN ── --}}
    @else

    {{-- Pilihan mode absensi --}}
    <div class="grid grid-cols-2 gap-2.5">
        {{-- Card: Absen Hadir (GPS) --}}
        <button type="button" @click="pilihan = 'hadir'"
                class="text-left p-4 rounded-2xl border-2 transition-all"
                :class="pilihan === 'hadir'
                    ? 'border-brand-500 bg-orange-50 dark:bg-orange-900/20'
                    : 'border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 hover:border-gray-200 dark:hover:border-gray-700'">
            <div class="w-9 h-9 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center mb-2.5">
                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">Absen Hadir</p>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Check-in via GPS</p>
        </button>

        {{-- Card: Lapor Izin/Sakit --}}
        <a href="{{ route('absensi.izin.form') }}"
           class="text-left p-4 rounded-2xl border-2 border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 hover:border-blue-200 dark:hover:border-blue-800 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all">
            <div class="w-9 h-9 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center mb-2.5">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">Lapor Izin</p>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Izin · Sakit · Tugas</p>
        </a>
    </div>

    {{-- Panel Check-in GPS (hanya muncul saat pilihan hadir) --}}
    <div x-show="pilihan === 'hadir'"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 overflow-hidden">

        <div class="px-5 pt-5 pb-1">
            <p class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-4">Absensi Kehadiran</p>

            {{-- Status GPS --}}
            <div class="rounded-xl px-4 py-3 mb-4 flex items-start gap-3 text-sm transition-all"
                 :class="{
                     'bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400': status === 'idle',
                     'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800': status === 'loading',
                     'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800': status === 'ready',
                     'bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-300 border border-red-200 dark:border-red-800': status === 'error',
                     'bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800': status === 'jauh',
                 }">
                <div class="flex-shrink-0 mt-0.5">
                    <template x-if="status === 'idle'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </template>
                    <template x-if="status === 'loading'">
                        <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </template>
                    <template x-if="status === 'ready'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </template>
                    <template x-if="status === 'error' || status === 'jauh'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </template>
                </div>
                <span x-text="pesanGPS" class="leading-snug text-xs"></span>
            </div>

            {{-- Tombol Ambil Lokasi --}}
            <button type="button"
                    @click="ambilLokasi()"
                    :disabled="status === 'loading' || status === 'submitting'"
                    class="w-full py-2.5 rounded-xl text-sm font-medium transition-all mb-2.5 border"
                    :class="status === 'ready'
                        ? 'bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400 border-gray-200 dark:border-gray-700'
                        : 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-750'">
                <span x-show="status !== 'loading'" class="flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span x-text="status === 'ready' ? 'Lokasi Terdeteksi ✓' : 'Ambil Lokasi Saya'"></span>
                </span>
                <span x-show="status === 'loading'" class="flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Mendeteksi lokasi...
                </span>
            </button>

            {{-- Tombol Absen Sekarang --}}
            <button type="button"
                    @click="kirimAbsensi()"
                    :disabled="status !== 'ready' || submitting"
                    class="w-full py-3 rounded-xl text-sm font-bold transition-all"
                    :class="status === 'ready' && !submitting
                        ? 'bg-brand-500 hover:bg-brand-600 text-white shadow-sm active:scale-[0.98]'
                        : 'bg-gray-100 dark:bg-gray-800 text-gray-400 dark:text-gray-600 cursor-not-allowed'">
                <span x-show="!submitting" class="flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Absen Sekarang
                </span>
                <span x-show="submitting" class="flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Menyimpan...
                </span>
            </button>
        </div>

        {{-- Toast Result --}}
        <div x-show="toast.show" x-transition
             class="mx-5 mb-5 mt-3 px-4 py-3 rounded-xl text-xs font-medium text-center"
             :class="toast.success
                ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300'
                : 'bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300'"
             x-text="toast.message">
        </div>
    </div>

    {{-- Info default saat belum pilih --}}
    <div x-show="pilihan === null"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="text-center py-4">
        <p class="text-xs text-gray-400 dark:text-gray-500">Pilih jenis absensi di atas</p>
    </div>

    @endif

    {{-- ── REKAP BULAN INI ── --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 overflow-hidden">
        <div class="px-5 py-3.5 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200">Rekap {{ now()->translatedFormat('F Y') }}</h3>
        </div>
        @php
            $hadir     = $absenBulanIni->whereIn('status', ['hadir','terlambat'])->count();
            $terlambat = $absenBulanIni->where('status', 'terlambat')->count();
            $izin      = $absenBulanIni->whereIn('status', ['izin','sakit','tugas_luar'])->count();
            $alpha     = $absenBulanIni->where('status', 'alpha')->count();
        @endphp
        <div class="p-4 grid grid-cols-4 gap-2">
            <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-xl p-3 text-center">
                <p class="text-xl font-bold text-emerald-700 dark:text-emerald-400">{{ $hadir }}</p>
                <p class="text-xs text-emerald-600 dark:text-emerald-500 mt-0.5 font-medium">Hadir</p>
            </div>
            <div class="bg-amber-50 dark:bg-amber-900/20 rounded-xl p-3 text-center">
                <p class="text-xl font-bold text-amber-700 dark:text-amber-400">{{ $terlambat }}</p>
                <p class="text-xs text-amber-600 dark:text-amber-500 mt-0.5 font-medium">Lambat</p>
            </div>
            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-3 text-center">
                <p class="text-xl font-bold text-blue-700 dark:text-blue-400">{{ $izin }}</p>
                <p class="text-xs text-blue-600 dark:text-blue-500 mt-0.5 font-medium">Izin</p>
            </div>
            <div class="bg-red-50 dark:bg-red-900/20 rounded-xl p-3 text-center">
                <p class="text-xl font-bold text-red-700 dark:text-red-400">{{ $alpha }}</p>
                <p class="text-xs text-red-600 dark:text-red-500 mt-0.5 font-medium">Alpha</p>
            </div>
        </div>

        {{-- Riwayat --}}
        @if($absenBulanIni->isNotEmpty())
        <div class="border-t border-gray-100 dark:border-gray-800">
            <div class="px-5 py-2.5 bg-gray-50 dark:bg-gray-800/50">
                <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Riwayat</p>
            </div>
            <div class="divide-y divide-gray-50 dark:divide-gray-800 max-h-72 overflow-y-auto">
                @foreach($absenBulanIni as $a)
                <div class="flex items-center px-5 py-3 gap-3">
                    <div class="text-center w-10 flex-shrink-0">
                        <p class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ $a->tanggal->format('d') }}</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500">{{ $a->tanggal->translatedFormat('D') }}</p>
                    </div>
                    <div class="flex-1 min-w-0">
                        @php
                        $badgeClass = match($a->status) {
                            'hadir'      => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300',
                            'terlambat'  => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300',
                            'izin'       => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300',
                            'sakit'      => 'bg-sky-100 text-sky-700 dark:bg-sky-900/40 dark:text-sky-300',
                            'tugas_luar' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300',
                            'alpha'      => 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300',
                            default      => 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400',
                        };
                        @endphp
                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $badgeClass }}">
                            {{ $a->label_status }}
                        </span>
                        @if($a->terlambat_menit > 0)
                            <span class="text-xs text-gray-400 dark:text-gray-500 ml-1">+{{ $a->terlambat_menit }}m</span>
                        @endif
                        @if($a->keterangan)
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5 truncate italic">{{ $a->keterangan }}</p>
                        @endif
                    </div>
                    <p class="text-xs font-mono text-gray-400 dark:text-gray-500 flex-shrink-0">
                        {{ $a->jam_masuk ? substr($a->jam_masuk, 0, 5) : '—' }}
                    </p>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="px-5 py-8 text-center">
            <p class="text-sm text-gray-400 dark:text-gray-500">Belum ada riwayat bulan ini</p>
        </div>
        @endif
    </div>

</div>

@push('scripts')
<script>
function absensiGPS() {
    return {
        pilihan: null,
        status: 'idle',
        submitting: false,
        pesanGPS: 'Tekan "Ambil Lokasi" untuk mendeteksi posisi Anda.',
        latitude: null,
        longitude: null,
        toast: { show: false, success: false, message: '' },
        jamSekarang: '',

        init() {
            this.updateJam();
            setInterval(() => this.updateJam(), 1000);
        },

        updateJam() {
            const now = new Date();
            this.jamSekarang = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        },

        ambilLokasi() {
            if (!navigator.geolocation) {
                this.status = 'error';
                this.pesanGPS = 'Browser Anda tidak mendukung GPS.';
                return;
            }
            this.status = 'loading';
            this.pesanGPS = 'Mendeteksi lokasi Anda...';

            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    this.latitude  = pos.coords.latitude;
                    this.longitude = pos.coords.longitude;
                    this.status    = 'ready';
                    this.pesanGPS  = `Lokasi terdeteksi. Akurasi ±${Math.round(pos.coords.accuracy)}m.`;
                },
                (err) => {
                    this.status = 'error';
                    this.pesanGPS = err.code === 1
                        ? 'Izin lokasi ditolak. Aktifkan GPS dan izinkan akses lokasi.'
                        : 'Gagal mendapatkan lokasi. Coba lagi.';
                },
                { enableHighAccuracy: true, timeout: 10000 }
            );
        },

        async kirimAbsensi() {
            if (this.status !== 'ready' || this.submitting) return;
            this.submitting = true;
            this.status = 'submitting';

            try {
                const res = await fetch('{{ route('absensi.checkin') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ latitude: this.latitude, longitude: this.longitude }),
                });

                const data = await res.json();

                if (data.success) {
                    this.showToast(true, data.message);
                    setTimeout(() => location.reload(), 1500);
                } else {
                    this.status = res.status === 422 && data.message?.includes('jauh') ? 'jauh' : 'error';
                    this.pesanGPS = data.message;
                    this.submitting = false;
                    this.showToast(false, data.message);
                }
            } catch (e) {
                this.status = 'ready';
                this.submitting = false;
                this.showToast(false, 'Terjadi kesalahan. Coba lagi.');
            }
        },

        showToast(success, message) {
            this.toast = { show: true, success, message };
            setTimeout(() => this.toast.show = false, 4000);
        }
    }
}
</script>
@endpush
@endsection