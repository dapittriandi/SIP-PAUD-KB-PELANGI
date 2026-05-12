{{-- =====================================================================
     GURU CREATE  →  resources/views/guru/create.blade.php
     Glass · Professional · Indigo — konsisten dengan index
     ===================================================================== --}}
@extends('layouts.app')
@section('title', 'Tambah Guru — PAUD KB Pelangi')
@section('page-title', 'Tambah Guru')

@section('content')
<div class="create-wrap" x-data="formGuru()">

    {{-- Breadcrumb --}}
    <nav class="breadcrumb">
        <a href="{{ route('guru.index') }}" class="bc-link">Data Guru</a>
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
        <span class="bc-current">Tambah Guru</span>
    </nav>

    <form method="POST" action="{{ route('guru.store') }}" enctype="multipart/form-data">
        @csrf

        {{-- ======================== SECTION: IDENTITAS ======================== --}}
        <div class="form-section glass-card">
            <div class="section-header">
                <div class="section-icon icon-blue">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
                <div>
                    <h3 class="section-title">Identitas Guru</h3>
                    <p class="section-sub">Informasi dasar & akun login</p>
                </div>
            </div>

            <div class="section-body">
                {{-- Foto + Fields --}}
                <div class="identity-layout">
                    {{-- Foto Upload --}}
                    <div class="foto-wrap">
                        <div class="foto-preview" @click="$refs.fotoInput.click()">
                            <template x-if="!fotoPreview">
                                <div class="foto-placeholder">
                                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
                                    <span>Pilih foto</span>
                                </div>
                            </template>
                            <template x-if="fotoPreview">
                                <img :src="fotoPreview" class="foto-img">
                            </template>
                        </div>
                        <input type="file" name="foto" accept="image/*" class="hidden" x-ref="fotoInput" @change="previewFoto($event)">
                        <p class="foto-hint">JPG, PNG, maks. 2MB</p>
                    </div>

                    {{-- Fields --}}
                    <div class="fields-grid">
                        <div class="field-group col-span-2">
                            <label class="field-label">Nama Lengkap <span class="req">*</span></label>
                            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required
                                   placeholder="Masukkan nama lengkap"
                                   class="field-input {{ $errors->has('nama_lengkap') ? 'is-error' : '' }}">
                            @error('nama_lengkap')<p class="field-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="field-group">
                            <label class="field-label">Username <span class="req">*</span></label>
                            <div class="input-prefix-wrap">
                                <span class="input-prefix">@</span>
                                <input type="text" name="username" value="{{ old('username') }}" required
                                       placeholder="username"
                                       class="field-input has-prefix {{ $errors->has('username') ? 'is-error' : '' }}">
                            </div>
                            @error('username')<p class="field-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="field-group">
                            <label class="field-label">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="field-input field-select">
                                <option value="">Pilih…</option>
                                <option value="L" {{ old('jenis_kelamin') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin') === 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="field-group">
                            <label class="field-label">No. HP</label>
                            <input type="text" name="no_hp" value="{{ old('no_hp') }}" placeholder="08xx-xxxx-xxxx" class="field-input">
                        </div>
                        <div class="field-group">
                            <label class="field-label">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="contoh@email.com" class="field-input">
                        </div>
                    </div>
                </div>

                {{-- PIN Block --}}
                <div class="pin-block">
                    <div class="pin-header">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        <span>PIN Login Guru</span>
                        <span class="pin-note">4 digit angka untuk login aplikasi</span>
                    </div>
                    <div class="pin-fields">
                        <div class="field-group">
                            <label class="field-label">PIN <span class="req">*</span></label>
                            <input type="password" name="pin" placeholder="••••" maxlength="4" required
                                   class="field-input field-pin {{ $errors->has('pin') ? 'is-error' : '' }}"
                                   inputmode="numeric" pattern="[0-9]*">
                            @error('pin')<p class="field-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="field-group">
                            <label class="field-label">Konfirmasi PIN <span class="req">*</span></label>
                            <input type="password" name="pin_confirmation" placeholder="••••" maxlength="4" required
                                   class="field-input field-pin"
                                   inputmode="numeric" pattern="[0-9]*">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ======================== SECTION: KEPEGAWAIAN ======================== --}}
        <div class="form-section glass-card">
            <div class="section-header">
                <div class="section-icon icon-violet">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                </div>
                <div>
                    <h3 class="section-title">Data Kepegawaian</h3>
                    <p class="section-sub">Opsional — lengkapi jika tersedia</p>
                </div>
            </div>

            <div class="section-body">
                <div class="fields-grid">
                    <div class="field-group">
                        <label class="field-label">NIP</label>
                        <input type="text" name="nip" value="{{ old('nip') }}"
                               placeholder="18 digit"
                               class="field-input {{ $errors->has('nip') ? 'is-error' : '' }}">
                        @error('nip')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="field-group">
                        <label class="field-label">NUPTK</label>
                        <input type="text" name="nuptk" value="{{ old('nuptk') }}" placeholder="16 digit" class="field-input">
                    </div>
                    <div class="field-group">
                        <label class="field-label">Status Kepegawaian</label>
                        <select name="status_kepegawaian" class="field-input field-select">
                            <option value="">Pilih…</option>
                            <option value="pns"     {{ old('status_kepegawaian') === 'pns'     ? 'selected' : '' }}>PNS</option>
                            <option value="pppk"    {{ old('status_kepegawaian') === 'pppk'    ? 'selected' : '' }}>P3K</option>
                            <option value="honorer" {{ old('status_kepegawaian') === 'honorer' ? 'selected' : '' }}>Honorer</option>
                            <option value="gtty"    {{ old('status_kepegawaian') === 'gtty'    ? 'selected' : '' }}>GTT Yayasan</option>
                        </select>
                    </div>
                    <div class="field-group">
                        <label class="field-label">Jabatan</label>
                        <input type="text" name="jabatan" value="{{ old('jabatan') }}" placeholder="Guru Kelas, Guru Pendamping…" class="field-input">
                    </div>
                    <div class="field-group">
                        <label class="field-label">Pendidikan Terakhir</label>
                        <select name="pendidikan_terakhir" class="field-input field-select">
                            <option value="">Pilih…</option>
                            @foreach(['SMA/SMK','D3','S1','S2','S3'] as $pend)
                            <option value="{{ $pend }}" {{ old('pendidikan_terakhir') === $pend ? 'selected' : '' }}>{{ $pend }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field-group">
                        <label class="field-label">Jurusan / Prodi</label>
                        <input type="text" name="jurusan" value="{{ old('jurusan') }}" placeholder="Mis. Pendidikan Anak Usia Dini" class="field-input">
                    </div>
                    <div class="field-group">
                        <label class="field-label">Tanggal Bergabung</label>
                        <input type="date" name="tanggal_bergabung" value="{{ old('tanggal_bergabung') }}" class="field-input field-date">
                    </div>
                    <div class="field-group col-span-2">
                        <label class="field-label">Alamat</label>
                        <textarea name="alamat" rows="2" placeholder="Jl. Contoh No. 1, Kota…" class="field-input field-textarea resize-none">{{ old('alamat') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="form-actions">
            <a href="{{ route('guru.index') }}" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-submit">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                Simpan Guru
            </button>
        </div>
    </form>
</div>

@push('styles')
<style>
/* ============================================
   GURU CREATE — Glass · Professional · Indigo
   ============================================ */

:root {
    --indigo:        #6366f1;
    --indigo-dark:   #4f46e5;
    --indigo-light:  #eef2ff;
    --indigo-mid:    #c7d2fe;
    --glass-bg:      rgba(255,255,255,0.72);
    --glass-border:  rgba(99,102,241,0.13);
    --glass-blur:    blur(18px);
    --glass-shadow:  0 4px 24px rgba(99,102,241,0.07), 0 1px 4px rgba(0,0,0,0.04);
    --tx-1:  #1e1b4b;
    --tx-2:  #4338ca;
    --tx-3:  #6b7280;
    --tx-4:  #9ca3af;
    --border-field:  rgba(99,102,241,0.18);
    --bg-field:      rgba(255,255,255,0.7);
    --bg-field-focus: rgba(255,255,255,0.95);
    --radius:  16px;
    --radius-s: 9px;
    --t: 0.16s ease;
    --font: 'Inter', 'Plus Jakarta Sans', system-ui, sans-serif;
}

@media (prefers-color-scheme: dark) {
    :root {
        --glass-bg:      rgba(18,20,35,0.84);
        --glass-border:  rgba(99,102,241,0.2);
        --glass-shadow:  0 4px 28px rgba(0,0,0,0.55), 0 1px 4px rgba(0,0,0,0.3);
        --indigo-light:  rgba(99,102,241,0.14);
        --indigo-mid:    rgba(99,102,241,0.28);
        --tx-1:  #e0e7ff;
        --tx-2:  #a5b4fc;
        --tx-3:  #6b7280;
        --tx-4:  #374151;
        --border-field:  rgba(99,102,241,0.22);
        --bg-field:      rgba(255,255,255,0.05);
        --bg-field-focus: rgba(255,255,255,0.09);
    }
}

*, *::before, *::after { box-sizing: border-box; }

.create-wrap {
    max-width: 760px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 16px;
    font-family: var(--font);
}

/* Glass */
.glass-card {
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius);
    box-shadow: var(--glass-shadow);
    backdrop-filter: var(--glass-blur);
    -webkit-backdrop-filter: var(--glass-blur);
    overflow: hidden;
}

/* ---- Breadcrumb ---- */
.breadcrumb {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.8rem;
    color: var(--tx-3);
}
.bc-link {
    color: var(--indigo);
    text-decoration: none;
    font-weight: 500;
    transition: color var(--t);
}
.bc-link:hover { color: var(--indigo-dark); text-decoration: underline; }
.bc-current { font-weight: 600; color: var(--tx-1); }
.breadcrumb svg { color: var(--tx-4); flex-shrink: 0; }

/* ---- Section Header ---- */
.section-header {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 16px 20px;
    border-bottom: 1px solid var(--glass-border);
    background: rgba(99,102,241,0.025);
}
.section-icon {
    width: 34px; height: 34px;
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.icon-blue   { background: rgba(99,102,241,0.12); color: var(--indigo); }
.icon-violet { background: rgba(139,92,246,0.12); color: #7c3aed; }
@media (prefers-color-scheme: dark) {
    .icon-blue   { background: rgba(99,102,241,0.18); }
    .icon-violet { background: rgba(139,92,246,0.18); }
}
.section-title {
    font-size: 0.9rem;
    font-weight: 700;
    color: var(--tx-1);
    margin: 0 0 2px;
    letter-spacing: -0.015em;
}
.section-sub {
    font-size: 0.73rem;
    color: var(--tx-3);
    margin: 0;
}

/* ---- Section Body ---- */
.section-body { padding: 20px; display: flex; flex-direction: column; gap: 18px; }

/* ---- Identity Layout ---- */
.identity-layout {
    display: flex;
    align-items: flex-start;
    gap: 20px;
}

/* ---- Foto Upload ---- */
.foto-wrap { flex-shrink: 0; display: flex; flex-direction: column; align-items: center; gap: 6px; }
.foto-preview {
    width: 92px; height: 92px;
    border-radius: 16px;
    border: 2px dashed var(--indigo-mid);
    background: var(--indigo-light);
    overflow: hidden;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: border-color var(--t), background var(--t);
    position: relative;
}
.foto-preview:hover {
    border-color: var(--indigo);
    background: rgba(99,102,241,0.1);
}
.foto-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 5px;
    color: var(--indigo);
    font-size: 0.65rem;
    font-weight: 600;
    text-align: center;
    padding: 8px;
    user-select: none;
}
.foto-img { width: 100%; height: 100%; object-fit: cover; }
.foto-hint { font-size: 0.65rem; color: var(--tx-4); text-align: center; line-height: 1.3; }

/* ---- Fields Grid ---- */
.fields-grid {
    flex: 1;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
    min-width: 0;
}
.col-span-2 { grid-column: span 2; }

/* ---- Field ---- */
.field-group { display: flex; flex-direction: column; gap: 5px; }
.field-label {
    font-size: 0.76rem;
    font-weight: 600;
    color: var(--tx-1);
    letter-spacing: 0.01em;
}
.req { color: #ef4444; }

.field-input {
    width: 100%;
    padding: 8px 12px;
    background: var(--bg-field);
    border: 1.5px solid var(--border-field);
    border-radius: var(--radius-s);
    font-size: 0.82rem;
    color: var(--tx-1);
    outline: none;
    font-family: var(--font);
    transition: border-color var(--t), box-shadow var(--t), background var(--t);
    line-height: 1.5;
}
.field-input::placeholder { color: var(--tx-4); }
.field-input:focus {
    background: var(--bg-field-focus);
    border-color: var(--indigo);
    box-shadow: 0 0 0 3px rgba(99,102,241,0.13);
}
.field-input.is-error {
    border-color: #f87171;
    box-shadow: 0 0 0 3px rgba(248,113,113,0.12);
}
.field-select { cursor: pointer; appearance: auto; }
.field-textarea { resize: vertical; min-height: 60px; }
.field-date { cursor: pointer; }
.field-error {
    font-size: 0.72rem;
    color: #ef4444;
    margin: 0;
}

/* ---- Username prefix ---- */
.input-prefix-wrap { position: relative; }
.input-prefix {
    position: absolute;
    left: 11px; top: 50%; transform: translateY(-50%);
    font-size: 0.82rem;
    color: var(--indigo);
    font-weight: 700;
    pointer-events: none;
    font-family: 'SF Mono', 'Fira Code', monospace;
    line-height: 1;
}
.field-input.has-prefix { padding-left: 26px; }

/* ---- PIN Block ---- */
.pin-block {
    background: rgba(99,102,241,0.05);
    border: 1px solid rgba(99,102,241,0.14);
    border-radius: 12px;
    padding: 14px 16px;
}
.pin-header {
    display: flex;
    align-items: center;
    gap: 7px;
    margin-bottom: 12px;
    color: var(--indigo-dark);
    flex-wrap: wrap;
}
.pin-header svg { flex-shrink: 0; }
.pin-header span:nth-child(2) { font-size: 0.82rem; font-weight: 700; }
.pin-note {
    font-size: 0.72rem;
    color: var(--tx-3);
    font-weight: 400;
}
.pin-fields {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}
.field-pin {
    letter-spacing: 0.25em;
    font-family: 'SF Mono', 'Fira Code', monospace;
    font-size: 1rem;
    text-align: center;
}

/* ---- Actions ---- */
.form-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 10px;
    padding: 4px 0 8px;
}
.btn-cancel {
    padding: 9px 20px;
    font-size: 0.82rem;
    font-weight: 600;
    color: var(--tx-3);
    background: var(--glass-bg);
    border: 1.5px solid var(--glass-border);
    border-radius: var(--radius-s);
    text-decoration: none;
    transition: background var(--t), color var(--t);
    backdrop-filter: var(--glass-blur);
}
.btn-cancel:hover { background: rgba(99,102,241,0.06); color: var(--tx-1); }

.btn-submit {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 9px 22px;
    background: var(--indigo);
    color: #fff;
    font-size: 0.83rem;
    font-weight: 700;
    border: none;
    border-radius: var(--radius-s);
    cursor: pointer;
    font-family: var(--font);
    transition: background var(--t), transform var(--t), box-shadow var(--t);
    box-shadow: 0 2px 10px rgba(99,102,241,0.32);
}
.btn-submit:hover {
    background: var(--indigo-dark);
    transform: translateY(-1px);
    box-shadow: 0 5px 18px rgba(99,102,241,0.42);
}
.btn-submit:active { transform: translateY(0); }

/* ---- Responsive ---- */
@media (max-width: 640px) {
    .identity-layout { flex-direction: column; align-items: stretch; }
    .foto-wrap { flex-direction: row; align-items: center; gap: 14px; }
    .foto-preview { width: 72px; height: 72px; }
    .foto-hint { text-align: left; }
    .fields-grid { grid-template-columns: 1fr; }
    .col-span-2 { grid-column: span 1; }
    .pin-fields { grid-template-columns: 1fr; }
    .form-actions { flex-direction: column-reverse; }
    .btn-cancel, .btn-submit { width: 100%; justify-content: center; }
}
</style>
@endpush

@push('scripts')
<script>
function formGuru() {
    return {
        fotoPreview: null,
        previewFoto(event) {
            const file = event.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = e => this.fotoPreview = e.target.result;
            reader.readAsDataURL(file);
        }
    }
}
</script>
@endpush
@endsection