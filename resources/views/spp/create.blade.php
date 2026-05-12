@extends('layouts.app')

@section('title', 'Input Pembayaran SPP — PAUD KB Pelangi')
@section('page-title', 'Input Pembayaran SPP')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=DM+Serif+Display&display=swap');

    :root {
        --navy: #0f1e3c;
        --navy-mid: #1a3260;
        --blue: #2563eb;
        --blue-light: #3b82f6;
        --blue-pale: #eff6ff;
        --glass-bg: rgba(255,255,255,0.82);
        --glass-border: rgba(37,99,235,0.10);
        --shadow-soft: 0 4px 24px rgba(15,30,60,0.07);
        --shadow-md: 0 8px 40px rgba(15,30,60,0.11);
        --radius: 20px;
        --radius-sm: 12px;
    }

    body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f0f4fb; }

    .create-wrap { max-width: 680px; margin: 0 auto; padding-bottom: 60px; }

    /* ── BREADCRUMB ── */
    .breadcrumb {
        display: flex; align-items: center; gap: 8px;
        font-size: 13px; color: #94a3b8; margin-bottom: 24px;
    }
    .breadcrumb a { color: #64748b; text-decoration: none; transition: color .15s; }
    .breadcrumb a:hover { color: var(--blue); }
    .breadcrumb .sep { color: #cbd5e1; }

    /* ── ERROR ALERT ── */
    .alert-error {
        display: flex; align-items: center; gap: 12px;
        padding: 14px 18px;
        background: #fef2f2; border: 1px solid #fecaca;
        border-radius: var(--radius-sm);
        font-size: 13.5px; color: #b91c1c;
        margin-bottom: 20px;
    }

    /* ── FORM CARD ── */
    .form-card {
        background: var(--glass-bg);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius);
        overflow: hidden;
        box-shadow: var(--shadow-md);
    }
    .form-card-header {
        padding: 22px 28px;
        border-bottom: 1px solid rgba(37,99,235,0.08);
        display: flex; align-items: center; gap: 14px;
        background: linear-gradient(90deg, #f8faff 0%, #f1f5ff 100%);
    }
    .card-icon {
        width: 40px; height: 40px;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        border-radius: 11px;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 4px 12px rgba(37,99,235,0.25);
        flex-shrink: 0;
    }
    .card-icon svg { color: white; }
    .card-title { font-size: 15px; font-weight: 700; color: var(--navy); }
    .card-sub { font-size: 12px; color: #94a3b8; margin-top: 1px; }

    .form-body { padding: 28px; }

    /* ── FORM ELEMENTS ── */
    .field { margin-bottom: 20px; }
    .field-label {
        display: block; font-size: 12.5px; font-weight: 600;
        color: #374151; margin-bottom: 7px; letter-spacing: 0.02em;
    }
    .field-label .req { color: #ef4444; margin-left: 2px; }
    .field-input {
        width: 100%; padding: 11px 16px;
        border: 1.5px solid #e2e8f0;
        border-radius: var(--radius-sm);
        font-size: 13.5px; font-family: inherit;
        color: var(--navy); background: #fff;
        transition: all .15s; outline: none; box-sizing: border-box;
    }
    .field-input:focus {
        border-color: var(--blue-light);
        box-shadow: 0 0 0 4px rgba(59,130,246,0.12);
    }
    .field-input.error { border-color: #f87171; }
    .field-error { font-size: 11.5px; color: #ef4444; margin-top: 5px; }

    /* prefix input */
    .input-prefix-wrap { position: relative; }
    .input-prefix {
        position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
        font-size: 13px; font-weight: 600; color: #94a3b8; pointer-events: none;
    }
    .input-prefix-wrap .field-input { padding-left: 46px; }

    /* grid */
    .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    @media(max-width:480px) { .grid-2 { grid-template-columns: 1fr; } }

    /* ── TOTAL PREVIEW ── */
    .total-preview {
        background: linear-gradient(135deg, var(--navy) 0%, #1a3a6e 100%);
        border-radius: var(--radius-sm);
        padding: 18px 22px;
        display: flex; align-items: center; justify-content: space-between;
        margin: 24px 0;
        position: relative; overflow: hidden;
    }
    .total-preview::after {
        content: '';
        position: absolute; right: -30px; top: -30px;
        width: 120px; height: 120px; border-radius: 50%;
        background: rgba(59,130,246,0.10);
    }
    .total-preview .total-lbl {
        font-size: 13px; font-weight: 500; color: rgba(255,255,255,0.65);
    }
    .total-preview .total-val {
        font-size: 24px; font-weight: 700; color: #fff;
        letter-spacing: -0.5px; position: relative; z-index: 1;
    }

    /* ── ACTIONS ── */
    .form-actions { display: flex; gap: 12px; justify-content: flex-end; margin-top: 8px; }
    .btn-cancel {
        padding: 11px 22px;
        border: 1.5px solid #e2e8f0;
        background: transparent; color: #64748b;
        font-size: 13.5px; font-weight: 600;
        border-radius: var(--radius-sm); cursor: pointer;
        text-decoration: none; font-family: inherit;
        transition: all .15s;
    }
    .btn-cancel:hover { background: #f8fafc; border-color: #cbd5e1; }
    .btn-submit {
        padding: 11px 26px;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #fff; font-size: 13.5px; font-weight: 700;
        border-radius: var(--radius-sm); border: none; cursor: pointer;
        font-family: inherit;
        box-shadow: 0 4px 16px rgba(37,99,235,0.35);
        transition: all .2s;
    }
    .btn-submit:hover { transform: translateY(-1px); box-shadow: 0 6px 22px rgba(37,99,235,0.45); }
</style>
@endpush

@section('content')
<div class="create-wrap">

    {{-- Breadcrumb --}}
    <div class="breadcrumb">
        <a href="{{ route('spp.index') }}">Pembayaran SPP</a>
        <span class="sep">›</span>
        <span style="color:#1e293b; font-weight:600;">Input Pembayaran</span>
    </div>

    {{-- Error --}}
    @if($errors->has('msg'))
    <div class="alert-error">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink:0">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ $errors->first('msg') }}
    </div>
    @endif

    <form method="POST" action="{{ route('spp.store') }}" x-data="formSpp()">
        @csrf

        <div class="form-card">
            <div class="form-card-header">
                <div class="card-icon">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <div class="card-title">Data Pembayaran</div>
                    <div class="card-sub">Isi semua field bertanda bintang</div>
                </div>
            </div>

            <div class="form-body">

                {{-- Pilih Siswa --}}
                <div class="field">
                    <label class="field-label">Siswa <span class="req">*</span></label>
                    <select name="siswa_id" x-model="siswaId" required
                            class="field-input @error('siswa_id') error @enderror">
                        <option value="">— Pilih Siswa —</option>
                        @foreach($daftarSiswa as $s)
                        <option value="{{ $s->id }}"
                                {{ old('siswa_id', $siswa?->id) == $s->id ? 'selected' : '' }}>
                            {{ $s->nama_lengkap }}
                            @if($s->nis) ({{ $s->nis }}) @endif
                            — {{ $s->kelompok }}
                        </option>
                        @endforeach
                    </select>
                    @error('siswa_id')<p class="field-error">{{ $message }}</p>@enderror
                </div>

                {{-- Periode --}}
                <div class="grid-2">
                    <div class="field">
                        <label class="field-label">Bulan <span class="req">*</span></label>
                        <select name="bulan" required class="field-input @error('bulan') error @enderror">
                            @foreach($bulanOptions as $b)
                            <option value="{{ $b['value'] }}"
                                    {{ old('bulan', now()->month) == $b['value'] ? 'selected' : '' }}>
                                {{ $b['label'] }}
                            </option>
                            @endforeach
                        </select>
                        @error('bulan')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="field">
                        <label class="field-label">Tahun <span class="req">*</span></label>
                        <select name="tahun" required class="field-input @error('tahun') error @enderror">
                            @foreach(range(now()->year, now()->year - 2) as $t)
                            <option value="{{ $t }}" {{ old('tahun', now()->year) == $t ? 'selected' : '' }}>{{ $t }}</option>
                            @endforeach
                        </select>
                        @error('tahun')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Nominal --}}
                <div class="grid-2">
                    <div class="field">
                        <label class="field-label">Nominal SPP <span class="req">*</span></label>
                        <div class="input-prefix-wrap">
                            <span class="input-prefix">Rp</span>
                            <input type="number" name="nominal_spp"
                                   x-model="nominalSpp" @input="hitungTotal()"
                                   value="{{ old('nominal_spp', 150000) }}"
                                   placeholder="0" min="0" required
                                   class="field-input @error('nominal_spp') error @enderror">
                        </div>
                        @error('nominal_spp')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="field">
                        <label class="field-label">Biaya Kebersihan <span class="req">*</span></label>
                        <div class="input-prefix-wrap">
                            <span class="input-prefix">Rp</span>
                            <input type="number" name="nominal_kebersihan"
                                   x-model="nominalKebersihan" @input="hitungTotal()"
                                   value="{{ old('nominal_kebersihan', 25000) }}"
                                   placeholder="0" min="0" required
                                   class="field-input @error('nominal_kebersihan') error @enderror">
                        </div>
                        @error('nominal_kebersihan')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Total Preview --}}
                <div class="total-preview">
                    <span class="total-lbl">Total Pembayaran</span>
                    <span class="total-val" x-text="formatRupiah(total)"></span>
                </div>

                {{-- Tanggal Bayar --}}
                <div class="field" style="margin-bottom:0">
                    <label class="field-label">Tanggal Bayar <span class="req">*</span></label>
                    <input type="date" name="tanggal_bayar"
                           value="{{ old('tanggal_bayar', today()->format('Y-m-d')) }}"
                           max="{{ today()->format('Y-m-d') }}"
                           required
                           class="field-input @error('tanggal_bayar') error @enderror">
                    @error('tanggal_bayar')<p class="field-error">{{ $message }}</p>@enderror
                </div>

            </div>
        </div>

        <div class="form-actions" style="margin-top:20px;">
            <a href="{{ route('spp.index') }}" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-submit">💾 &nbsp;Simpan & Cetak Kwitansi</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function formSpp() {
    return {
        siswaId: '{{ old('siswa_id', $siswa?->id ?? '') }}',
        nominalSpp: {{ old('nominal_spp', 150000) }},
        nominalKebersihan: {{ old('nominal_kebersihan', 25000) }},
        total: {{ old('nominal_spp', 150000) + old('nominal_kebersihan', 25000) }},
        hitungTotal() {
            this.total = (parseFloat(this.nominalSpp) || 0) + (parseFloat(this.nominalKebersihan) || 0);
        },
        formatRupiah(angka) {
            return 'Rp ' + parseInt(angka || 0).toLocaleString('id-ID');
        }
    }
}
</script>
@endpush
@endsection