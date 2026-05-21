{{-- resources/views/absensi/izin.blade.php --}}
@extends('layouts.app')

@section('title', 'Lapor Izin / Sakit — PAUD KB Pelangi')
@section('page-title', 'Lapor Izin / Sakit')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Satoshi:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
<style>
    :root {
        --brand: #10b981;
        --brand-dark: #059669;
        --brand-muted: #d1fae5;
        --surface: #ffffff;
        --surface-2: #f8fafc;
        --border: #e2e8f0;
        --text-primary: #0f172a;
        --text-secondary: #64748b;
        --text-tertiary: #94a3b8;
        --radius-card: 1.25rem;
        --radius-sm: 0.75rem;
        --easing: cubic-bezier(0.16, 1, 0.3, 1);
    }
    .dark {
        --surface: #0f172a;
        --surface-2: #1e293b;
        --border: #1e293b;
        --text-primary: #f1f5f9;
        --text-secondary: #94a3b8;
        --text-tertiary: #475569;
        --brand-muted: #064e3b;
    }
    body, * { font-family: 'Satoshi', system-ui, sans-serif; }
    .mono { font-family: 'JetBrains Mono', monospace; }

    /* Stagger fade-up on mount */
    .stagger > * { opacity: 0; transform: translateY(10px); animation: fadeUp 0.4s var(--easing) forwards; }
    .stagger > *:nth-child(1) { animation-delay: 0.05s; }
    .stagger > *:nth-child(2) { animation-delay: 0.12s; }
    .stagger > *:nth-child(3) { animation-delay: 0.19s; }
    .stagger > *:nth-child(4) { animation-delay: 0.26s; }
    @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }

    /* Card */
    .card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-card);
        overflow: hidden;
    }

    /* Status bar gradient */
    .status-bar { height: 3px; width: 100%; }
    .status-bar-hadir { background: linear-gradient(90deg, #10b981, #34d399); }
    .status-bar-terlambat { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
    .status-bar-izin { background: linear-gradient(90deg, #3b82f6, #60a5fa); }
    .status-bar-alpha { background: linear-gradient(90deg, #ef4444, #f87171); }

    /* Mode chip selector */
    .chip {
        border: 2px solid var(--border);
        border-radius: var(--radius-sm);
        background: var(--surface);
        cursor: pointer;
        transition: border-color 0.2s var(--easing), background 0.2s ease, transform 0.2s var(--easing);
        padding: 0.75rem 0.5rem;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
    }
    .chip:hover { transform: translateY(-2px); }
    .chip:active { transform: scale(0.96); }
    .chip.selected-izin       { border-color: #3b82f6; background: #eff6ff; }
    .chip.selected-sakit      { border-color: #0ea5e9; background: #f0f9ff; }
    .chip.selected-tugas_luar { border-color: #8b5cf6; background: #f5f3ff; }

    /* Textarea */
    .field-textarea {
        width: 100%;
        border: 1px solid var(--border);
        background: var(--surface-2);
        border-radius: var(--radius-sm);
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
        color: var(--text-primary);
        resize: none;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        outline: none;
        font-family: 'Satoshi', system-ui, sans-serif;
    }
    .field-textarea::placeholder { color: var(--text-tertiary); }
    .field-textarea:focus { border-color: var(--brand); box-shadow: 0 0 0 3px rgba(16,185,129,0.12); }
    .dark .field-textarea:focus { box-shadow: 0 0 0 3px rgba(16,185,129,0.15); }

    /* Primary button */
    .btn-primary {
        background: var(--brand);
        color: #fff;
        transition: background 0.2s ease, transform 0.15s var(--easing), box-shadow 0.2s ease;
        box-shadow: 0 2px 12px -2px rgba(16,185,129,0.35);
    }
    .btn-primary:hover:not(:disabled) { background: var(--brand-dark); box-shadow: 0 4px 18px -3px rgba(16,185,129,0.45); }
    .btn-primary:active:not(:disabled) { transform: translateY(1px) scale(0.98); }
    .btn-primary:disabled { background: var(--border); box-shadow: none; color: var(--text-tertiary); cursor: not-allowed; }

    /* Back button */
    .btn-back {
        width: 2.25rem;
        height: 2.25rem;
        border-radius: 0.625rem;
        border: 1px solid var(--border);
        background: var(--surface);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-secondary);
        transition: all 0.2s var(--easing);
        flex-shrink: 0;
    }
    .btn-back:hover { border-color: var(--brand); color: var(--brand); transform: translateX(-2px); }

    /* Alert banners */
    .alert-error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: var(--radius-sm);
        color: #b91c1c;
        padding: 0.75rem 1rem;
        font-size: 0.8125rem;
    }
    .alert-warning {
        background: #fefce8;
        border: 1px solid #fde68a;
        border-radius: var(--radius-sm);
        color: #92400e;
        padding: 0.625rem 0.875rem;
    }
    .alert-info-blue {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: var(--radius-sm);
        color: #1d4ed8;
        padding: 0.625rem 0.875rem;
    }
    .alert-info-purple {
        background: #f5f3ff;
        border: 1px solid #ddd6fe;
        border-radius: var(--radius-sm);
        color: #5b21b6;
        padding: 0.625rem 0.875rem;
    }

    /* Info note slide reveal */
    [x-show] { transition: opacity 0.25s ease, transform 0.25s var(--easing); }

    /* Field label */
    .field-label {
        display: block;
        font-size: 0.6875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--text-tertiary);
        margin-bottom: 0.5rem;
    }
</style>
@endpush

@section('content')
<div class="max-w-sm mx-auto space-y-3 pb-6 stagger">

    {{-- Back + Header --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('absensi.index') }}" class="btn-back">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <p class="text-base font-bold" style="color:var(--text-primary)">Lapor Izin / Sakit</p>
            <p class="text-xs mt-0.5" style="color:var(--text-tertiary)">{{ \Carbon\Carbon::today()->translatedFormat('l, d F Y') }}</p>
        </div>
    </div>

    {{-- ── Sudah ada absensi hari ini ── --}}
    @if ($absenHariIni)
    @php
        $st = $absenHariIni->status;
        $barKey = match($st) {
            'hadir','terlambat' => 'hadir',
            'izin','sakit','tugas_luar' => 'izin',
            'alpha' => 'alpha',
            default => 'hadir',
        };
        $iconBg = match($st) {
            'hadir','terlambat' => 'background:#d1fae5;',
            'izin','sakit','tugas_luar' => 'background:#dbeafe;',
            default => 'background:#f1f5f9;',
        };
        $iconColor = match($st) {
            'hadir','terlambat' => '#059669',
            'izin','sakit','tugas_luar' => '#2563eb',
            default => '#64748b',
        };
        $badge = match($st) {
            'hadir'      => 'background:#d1fae5;color:#065f46;',
            'terlambat'  => 'background:#fef3c7;color:#92400e;',
            'izin'       => 'background:#dbeafe;color:#1e40af;',
            'sakit'      => 'background:#e0f2fe;color:#0369a1;',
            'tugas_luar' => 'background:#ede9fe;color:#5b21b6;',
            'alpha'      => 'background:#fee2e2;color:#991b1b;',
            default      => 'background:#f1f5f9;color:#475569;',
        };
    @endphp
    <div class="card">
        <div class="status-bar status-bar-{{ $barKey }}"></div>
        <div class="px-5 pt-5 pb-3 flex items-center gap-3">
            <div class="w-11 h-11 rounded-2xl flex items-center justify-center flex-shrink-0" style="{{ $iconBg }}">
                @if(in_array($st, ['hadir','terlambat']))
                    <svg width="22" height="22" fill="none" stroke="{{ $iconColor }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                @else
                    <svg width="22" height="22" fill="none" stroke="{{ $iconColor }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                @endif
            </div>
            <div>
                <p class="text-xs font-medium" style="color:var(--text-tertiary)">Absensi Hari Ini</p>
                <div class="flex items-center gap-2 mt-0.5">
                    <p class="text-sm font-bold" style="color:var(--text-primary)">Sudah Tercatat</p>
                    <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-semibold tracking-wide" style="{{ $badge }}">
                        {{ $absenHariIni->label_status }}
                    </span>
                </div>
            </div>
        </div>

        @if ($absenHariIni->jam_masuk)
        @php
            $cfgJamMasuk   = config('sekolah.jam_masuk',     '08:00');
            $cfgJamToleran = config('sekolah.jam_toleransi', '08:15');
        @endphp
        <div class="mx-5 mb-3">
            <div style="background:var(--surface-2);border:1px solid var(--border);border-radius:var(--radius-sm);padding:0.75rem 1rem;display:flex;align-items:center;justify-content:space-between;">
                <div class="flex items-center gap-2">
                    <svg width="14" height="14" fill="none" stroke="#059669" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-wider" style="color:var(--text-tertiary)">Jam Masuk</p>
                        <p class="mono text-base font-bold" style="color:var(--text-primary)">{{ \Carbon\Carbon::parse($absenHariIni->jam_masuk)->format('H:i') }} <span class="text-xs font-medium" style="color:var(--text-tertiary)">WIB</span></p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-semibold uppercase tracking-wider" style="color:var(--text-tertiary)">Toleransi s/d</p>
                    <p class="mono text-sm font-bold" style="color:#059669;">{{ $cfgJamToleran }} WIB</p>
                </div>
            </div>
        </div>
        @endif

        <div class="px-5 pb-5">
            @if ($absenHariIni->keterangan)
            <p class="text-xs italic mb-3" style="color:var(--text-tertiary)">"{{ $absenHariIni->keterangan }}"</p>
            @endif
            <div class="alert-warning flex items-start gap-2 text-left">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0 mt-0.5" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                <p class="text-xs font-medium">Absensi hari ini sudah tercatat. Jika ada kesalahan, hubungi admin.</p>
            </div>
        </div>
    </div>

    {{-- ── Form izin mandiri ── --}}
    @else

        {{-- Error alerts --}}
        @if (session('error'))
            <div class="alert-error flex items-start gap-2.5">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0 mt-0.5" viewBox="0 0 24 24"><path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert-error">
                <ul class="space-y-1">
                    @foreach ($errors->all() as $error)
                        <li class="flex items-start gap-2">
                            <span class="mt-1.5 w-1.5 h-1.5 rounded-full flex-shrink-0" style="background:#dc2626;"></span>
                            {{ $error }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card"
             x-data="{
                 status: '{{ old('status', '') }}',
                 keterangan: '{{ old('keterangan', '') }}',
                 get labelTombol() {
                     const map = { izin: 'Izin', sakit: 'Sakit', tugas_luar: 'Tugas Luar' };
                     return this.status ? 'Kirim Laporan ' + (map[this.status] ?? '') : 'Pilih jenis keterangan dulu';
                 },
                 get bolehKirim() {
                     return this.status !== '' && this.keterangan.trim().length >= 5;
                 }
             }">

            {{-- Cutoff izin info banner --}}
            @php
                $cfgCutoffIzin  = config('sekolah.jam_cutoff_izin',  '09:00');
                $cfgMulaiIzin   = config('sekolah.jam_mulai_izin',   '00:01');
            @endphp
            <div class="mx-5 mt-5 mb-1 flex items-center gap-2 rounded-xl px-3.5 py-2.5 text-xs font-medium" style="background:#fffbeb;border:1px solid #fde68a;color:#92400e;">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                Laporan izin dapat dikirim mulai <span class="mono font-bold mx-0.5">{{ $cfgMulaiIzin }}</span>
                hingga <span class="mono font-bold mx-0.5">{{ $cfgCutoffIzin }} WIB</span>
            </div>

            <form action="{{ route('absensi.izin.store') }}" method="POST" novalidate class="p-5 space-y-5">
                @csrf

                {{-- Jenis keterangan --}}
                <div>
                    <span class="field-label">Jenis Keterangan <span style="color:#ef4444">*</span></span>

                    <div class="grid grid-cols-3 gap-2">
                        @foreach ([
                            ['value' => 'izin',       'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'label' => 'Izin',  'bg' => '#dbeafe', 'stroke' => '#2563eb'],
                            ['value' => 'sakit',      'icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z', 'label' => 'Sakit', 'bg' => '#e0f2fe', 'stroke' => '#0ea5e9'],
                            ['value' => 'tugas_luar', 'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'label' => 'Tugas', 'bg' => '#ede9fe', 'stroke' => '#7c3aed'],
                        ] as $opt)
                            <label class="block cursor-pointer select-none">
                                <input type="radio" name="status" value="{{ $opt['value'] }}" x-model="status" class="sr-only">
                                <div class="chip" :class="status === '{{ $opt['value'] }}' ? 'selected-{{ $opt['value'] }}' : ''">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background:{{ $opt['bg'] }}">
                                        <svg width="16" height="16" fill="none" stroke="{{ $opt['stroke'] }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="{{ $opt['icon'] }}"/></svg>
                                    </div>
                                    <span class="text-xs font-semibold" style="color:var(--text-primary)">{{ $opt['label'] }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    @error('status')
                        <p class="text-xs mt-1.5 font-medium" style="color:#dc2626;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Keterangan (slides in after type selected) --}}
                <div x-show="status !== ''"
                     x-transition:enter="transition duration-250"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0">

                    <label for="keterangan" class="field-label">
                        Keterangan <span style="color:#ef4444">*</span>
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
                              class="field-textarea"
                    >{{ old('keterangan') }}</textarea>

                    <div class="flex items-center justify-between mt-1.5">
                        @error('keterangan')
                            <p class="text-xs font-medium" style="color:#dc2626;">{{ $message }}</p>
                        @else
                            <p class="text-xs" style="color:var(--text-tertiary)">Minimal 5 karakter</p>
                        @enderror
                        <p class="mono text-xs ml-auto" style="color:var(--text-tertiary)" x-text="keterangan.length + '/500'"></p>
                    </div>
                </div>

                {{-- Contextual info notes --}}
                <div x-show="status === 'sakit'" x-transition
                     class="alert-info-blue flex items-start gap-2 text-xs font-medium">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0 mt-0.5" viewBox="0 0 24 24"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Sakit lebih dari 2 hari? Siapkan surat keterangan dokter untuk diserahkan ke admin.</span>
                </div>
                <div x-show="status === 'tugas_luar'" x-transition
                     class="alert-info-purple flex items-start gap-2 text-xs font-medium">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0 mt-0.5" viewBox="0 0 24 24"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Pastikan tugas luar sudah dikomunikasikan ke kepala sekolah sebelumnya.</span>
                </div>

                {{-- Submit --}}
                <button type="submit"
                        :disabled="!bolehKirim"
                        class="btn-primary w-full py-3 rounded-xl font-bold text-sm flex items-center justify-center gap-2">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    <span x-text="labelTombol"></span>
                </button>

            </form>
        </div>

        <p class="text-xs text-center pb-2" style="color:var(--text-tertiary)">
            Laporan berlaku untuk hari ini. Kesalahan? Hubungi admin.
        </p>

    @endif
</div>
@endsection