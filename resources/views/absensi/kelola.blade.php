@extends('layouts.app')

@section('title', 'Kelola Absensi — PAUD KB Pelangi')
@section('page-title', 'Kelola Absensi')

@section('content')
<div class="space-y-5" x-data="kelolaAbsensi()">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Kelola Absensi Guru</h2>
            <p class="text-sm text-gray-500 mt-0.5">{{ $tanggal->translatedFormat('l, d F Y') }}</p>
        </div>

        {{-- Pilih Tanggal --}}
        <div class="flex items-center gap-3">
            <form method="GET" action="{{ route('absensi.kelola') }}" class="flex items-center gap-2">
                <input type="date" name="tanggal"
                       value="{{ $tanggal->format('Y-m-d') }}"
                       max="{{ today()->format('Y-m-d') }}"
                       onchange="this.form.submit()"
                       class="px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-pelangi-400 bg-white">
            </form>

            {{-- Auto Alpha --}}
            <form method="POST" action="{{ route('absensi.auto-alpha') }}"
                  onsubmit="return confirm('Tandai semua guru yang belum absen sebagai ALPHA pada tanggal {{ $tanggal->format('d/m/Y') }}?')">
                @csrf
                <input type="hidden" name="tanggal" value="{{ $tanggal->format('Y-m-d') }}">
                <button type="submit"
                        class="px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 text-sm font-semibold rounded-xl border border-red-200 transition-colors">
                    ⚡ Auto Alpha
                </button>
            </form>
        </div>
    </div>

    {{-- Statistik hari --}}
    @php
        $totalGuru = $guruAktif->count();
        $sudahAbsen = $absensiHari->count();
        $hadir   = $absensiHari->whereIn('status', ['hadir','terlambat'])->count();
        $tidakHadir = $absensiHari->whereIn('status', ['izin','sakit','tugas_luar','alpha'])->count();
        $belum   = $totalGuru - $sudahAbsen;
    @endphp
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <div class="bg-white rounded-2xl border border-gray-100 p-4 text-center">
            <p class="text-2xl font-bold text-gray-800">{{ $totalGuru }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Total Guru</p>
        </div>
        <div class="bg-green-50 rounded-2xl border border-green-100 p-4 text-center">
            <p class="text-2xl font-bold text-green-700">{{ $hadir }}</p>
            <p class="text-xs text-green-600 mt-0.5">Hadir</p>
        </div>
        <div class="bg-yellow-50 rounded-2xl border border-yellow-100 p-4 text-center">
            <p class="text-2xl font-bold text-yellow-700">{{ $tidakHadir }}</p>
            <p class="text-xs text-yellow-600 mt-0.5">Tidak Hadir</p>
        </div>
        <div class="bg-red-50 rounded-2xl border border-red-100 p-4 text-center">
            <p class="text-2xl font-bold text-red-700">{{ $belum }}</p>
            <p class="text-xs text-red-600 mt-0.5">Belum Absen</p>
        </div>
    </div>

    {{-- Tabel Absensi --}}
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800 text-sm">Daftar Absensi</h3>
            <span class="text-xs text-gray-400">{{ $sudahAbsen }}/{{ $totalGuru }} guru tercatat</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Guru</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide hidden sm:table-cell">Jam Masuk</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide hidden md:table-cell">Keterangan</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide hidden lg:table-cell">Sumber</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($guruAktif as $guru)
                    @php $absen = $absensiHari->get($guru->id); @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        {{-- Guru --}}
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl flex-shrink-0 bg-pelangi-100 flex items-center justify-center text-pelangi-600 font-bold text-xs">
                                    {{ strtoupper(substr($guru->nama_lengkap, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800 text-sm">{{ $guru->nama_lengkap }}</p>
                                    <p class="text-xs text-gray-400">{{ $guru->jabatan ?? '' }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Status --}}
                        <td class="px-4 py-3">
                            @if($absen)
                            @php
                            $badge = match($absen->status) {
                                'hadir'      => 'bg-green-100 text-green-700',
                                'terlambat'  => 'bg-yellow-100 text-yellow-700',
                                'izin'       => 'bg-blue-100 text-blue-700',
                                'sakit'      => 'bg-blue-100 text-blue-700',
                                'tugas_luar' => 'bg-purple-100 text-purple-700',
                                'alpha'      => 'bg-red-100 text-red-700',
                                default      => 'bg-gray-100 text-gray-700',
                            };
                            @endphp
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $badge }}">
                                {{ $absen->label_status }}
                            </span>
                            @if($absen->terlambat_menit > 0)
                            <span class="text-xs text-gray-400 ml-1">+{{ $absen->terlambat_menit }}mnt</span>
                            @endif
                            @else
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-500">
                                Belum
                            </span>
                            @endif
                        </td>

                        {{-- Jam --}}
                        <td class="px-4 py-3 text-gray-600 hidden sm:table-cell">
                            {{ $absen?->jam_masuk ? substr($absen->jam_masuk, 0, 5) : '-' }}
                        </td>

                        {{-- Keterangan --}}
                        <td class="px-4 py-3 text-gray-500 text-xs hidden md:table-cell max-w-xs truncate">
                            {{ $absen?->keterangan ?? '-' }}
                        </td>

                        {{-- Sumber --}}
                        <td class="px-4 py-3 hidden lg:table-cell">
                            @if($absen)
                            @if($absen->isSelfCheckIn())
                                <span class="flex items-center gap-1 text-xs text-green-600">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                    GPS
                                    @if($absen->jarak_meter) ({{ $absen->jarak_meter }}m) @endif
                                </span>
                            @else
                                <span class="text-xs text-gray-400">Manual / Admin</span>
                            @endif
                            @else
                            <span class="text-xs text-gray-300">-</span>
                            @endif
                        </td>

                        {{-- Aksi --}}
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-1 justify-end">
                                {{-- Tombol input/edit --}}
                                <button type="button"
                                        @click="bukaModal({{ $guru->id }}, '{{ $guru->nama_lengkap }}', {{ $absen ? "'" . $absen->status . "'" : 'null' }}, {{ $absen ? "'" . substr($absen->jam_masuk ?? '', 0, 5) . "'" : "''" }}, '{{ $absen?->keterangan ?? '' }}')"
                                        class="p-1.5 rounded-lg text-gray-400 hover:text-pelangi-600 hover:bg-pelangi-50 transition-colors"
                                        title="{{ $absen ? 'Edit' : 'Input' }} Absensi">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>

                                {{-- Hapus --}}
                                @if($absen)
                                <form method="POST" action="{{ route('absensi.hapus', $absen->id) }}"
                                      onsubmit="return confirm('Hapus absensi {{ $guru->nama_lengkap }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- ── MODAL INPUT ABSENSI ── --}}
    <div x-show="modal.open" x-transition.opacity
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40">
        <div @click.outside="modal.open = false"
             x-transition
             class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">

            <div class="flex items-center justify-between mb-5">
                <h3 class="font-bold text-gray-800">Input Absensi</h3>
                <button @click="modal.open = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <p class="text-sm text-gray-600 mb-5">
                <span class="font-semibold text-gray-800" x-text="modal.namaGuru"></span>
                · {{ $tanggal->translatedFormat('d F Y') }}
            </p>

            <form method="POST" action="{{ route('absensi.manual') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="guru_id" :value="modal.guruId">
                <input type="hidden" name="tanggal" value="{{ $tanggal->format('Y-m-d') }}">

                {{-- Status --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach([
                            ['hadir',      'Hadir',      'bg-green-100 text-green-700 border-green-200'],
                            ['terlambat',  'Terlambat',  'bg-yellow-100 text-yellow-700 border-yellow-200'],
                            ['izin',       'Izin',       'bg-blue-100 text-blue-700 border-blue-200'],
                            ['sakit',      'Sakit',      'bg-blue-100 text-blue-700 border-blue-200'],
                            ['tugas_luar', 'Tugas Luar', 'bg-purple-100 text-purple-700 border-purple-200'],
                            ['alpha',      'Alpha',      'bg-red-100 text-red-700 border-red-200'],
                        ] as [$val, $label, $cls])
                        <label class="cursor-pointer">
                            <input type="radio" name="status" value="{{ $val }}" x-model="modal.status" class="sr-only">
                            <div class="text-center py-2 rounded-xl text-xs font-semibold border transition-all"
                                 :class="modal.status === '{{ $val }}' ? '{{ $cls }} ring-2 ring-offset-1 ring-current' : 'border-gray-200 text-gray-500 hover:border-gray-300'">
                                {{ $label }}
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Jam Masuk --}}
                <div x-show="['hadir','terlambat'].includes(modal.status)">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Jam Masuk</label>
                    <input type="time" name="jam_masuk" x-model="modal.jamMasuk"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-pelangi-400">
                </div>

                {{-- Keterangan --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Keterangan <span class="text-gray-400 font-normal">(opsional)</span></label>
                    <input type="text" name="keterangan" x-model="modal.keterangan"
                           placeholder="Misal: izin keperluan keluarga"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-pelangi-400">
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" @click="modal.open = false"
                            class="flex-1 py-2.5 border border-gray-200 text-sm text-gray-600 rounded-xl hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 py-2.5 bg-pelangi-500 hover:bg-pelangi-600 text-white text-sm font-semibold rounded-xl transition-colors">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

@push('scripts')
<script>
function kelolaAbsensi() {
    return {
        modal: {
            open: false,
            guruId: null,
            namaGuru: '',
            status: 'hadir',
            jamMasuk: '',
            keterangan: '',
        },

        bukaModal(guruId, namaGuru, status, jamMasuk, keterangan) {
            this.modal = {
                open: true,
                guruId,
                namaGuru,
                status: status ?? 'hadir',
                jamMasuk: jamMasuk ?? '',
                keterangan: keterangan ?? '',
            };
        }
    }
}
</script>
@endpush
@endsection