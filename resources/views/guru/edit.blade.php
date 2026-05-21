@extends('layouts.app')

@section('title', 'Edit Data Guru — ' . $guru->nama_lengkap)
@section('page-title', 'Edit Guru')

@section('content')
<div class="edit-page">

    {{-- Header --}}
    <div class="edit-header">
        <a href="{{ route('guru.show', $guru) }}" class="btn-back" aria-label="Kembali ke detail guru">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div class="edit-header-text">
            <h1 class="edit-title">Edit Data Guru</h1>
            <p class="edit-sub">{{ $guru->nama_lengkap }} <span class="edit-sub-sep">·</span> <span class="edit-sub-username">&#64;{{ $guru->username }}</span></p>
        </div>
    </div>

    {{-- Alert Error --}}
    @if ($errors->any())
    <div class="alert-error glass-card" role="alert" aria-live="polite">
        <div class="alert-icon" aria-hidden="true">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/>
            </svg>
        </div>
        <div>
            <p class="alert-title">Terdapat kesalahan input:</p>
            <ul class="alert-list">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <form method="POST" action="{{ route('guru.update', $guru) }}" enctype="multipart/form-data" class="edit-form">
        @csrf
        @method('PUT')

        {{-- ── IDENTITAS DASAR ── --}}
        <div class="form-section glass-card">
            <div class="section-head">
                <span class="section-icon" aria-hidden="true">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                    </svg>
                </span>
                <h2 class="section-title">Identitas Dasar</h2>
            </div>
            <div class="section-body">
                <div class="field-grid">

                    {{-- Nama Lengkap --}}
                    <div class="field col-span-2">
                        <label class="field-label" for="nama_lengkap">
                            Nama Lengkap <span class="required" aria-label="wajib diisi">*</span>
                        </label>
                        <input type="text" id="nama_lengkap" name="nama_lengkap"
                               value="{{ old('nama_lengkap', $guru->nama_lengkap) }}"
                               class="field-input @error('nama_lengkap') is-error @enderror"
                               placeholder="Nama lengkap guru" required
                               autocomplete="name">
                        @error('nama_lengkap')
                        <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Username --}}
                    <div class="field">
                        <label class="field-label" for="username">
                            Username <span class="required" aria-label="wajib diisi">*</span>
                        </label>
                        <input type="text" id="username" name="username"
                               value="{{ old('username', $guru->username) }}"
                               class="field-input mono @error('username') is-error @enderror"
                               placeholder="username_login" required
                               autocomplete="username" autocorrect="off" spellcheck="false">
                        @error('username')
                        <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Jenis Kelamin --}}
                    <div class="field">
                        <label class="field-label" for="jenis_kelamin">Jenis Kelamin</label>
                        <select id="jenis_kelamin" name="jenis_kelamin" class="field-input field-select">
                            <option value="">— Pilih —</option>
                            <option value="L" @selected(old('jenis_kelamin', $guru->jenis_kelamin) === 'L')>Laki-laki</option>
                            <option value="P" @selected(old('jenis_kelamin', $guru->jenis_kelamin) === 'P')>Perempuan</option>
                        </select>
                    </div>

                    {{-- Tempat Lahir --}}
                    <div class="field">
                        <label class="field-label" for="tempat_lahir">Tempat Lahir</label>
                        <input type="text" id="tempat_lahir" name="tempat_lahir"
                               value="{{ old('tempat_lahir', $guru->tempat_lahir) }}"
                               class="field-input"
                               placeholder="Kota tempat lahir">
                    </div>

                    {{-- Tanggal Lahir --}}
                    <div class="field">
                        <label class="field-label" for="tanggal_lahir">Tanggal Lahir</label>
                        <input type="date" id="tanggal_lahir" name="tanggal_lahir"
                               value="{{ old('tanggal_lahir', $guru->tanggal_lahir ? \Carbon\Carbon::parse($guru->tanggal_lahir)->format('Y-m-d') : '') }}"
                               class="field-input">
                    </div>

                    {{-- No HP --}}
                    <div class="field">
                        <label class="field-label" for="no_hp">Nomor HP</label>
                        <input type="text" id="no_hp" name="no_hp"
                               value="{{ old('no_hp', $guru->no_hp) }}"
                               class="field-input mono"
                               placeholder="08xx-xxxx-xxxx"
                               inputmode="tel">
                    </div>

                    {{-- Email --}}
                    <div class="field">
                        <label class="field-label" for="email">Email</label>
                        <input type="email" id="email" name="email"
                               value="{{ old('email', $guru->email) }}"
                               class="field-input"
                               placeholder="email@contoh.com"
                               autocomplete="email" inputmode="email">
                    </div>

                    {{-- Alamat --}}
                    <div class="field col-span-2">
                        <label class="field-label" for="alamat">Alamat</label>
                        <textarea id="alamat" name="alamat" rows="3"
                                  class="field-input field-textarea"
                                  placeholder="Alamat lengkap">{{ old('alamat', $guru->alamat) }}</textarea>
                    </div>

                    {{-- Foto --}}
                    <div class="field col-span-2">
                        <label class="field-label">Foto</label>
                        @if ($guru->foto)
                        <div class="foto-preview">
                            <img src="{{ Storage::url($guru->foto) }}"
                                 alt="Foto {{ $guru->nama_lengkap }}"
                                 class="foto-thumb">
                            <p class="foto-hint">Foto saat ini. Upload baru untuk mengganti.</p>
                        </div>
                        @endif
                        <div class="file-wrap">
                            <input type="file" id="foto" name="foto" accept="image/*" class="file-input">
                            <label for="foto" class="file-label">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
                                </svg>
                                <span class="file-label-text">Pilih foto baru</span>
                                <span class="file-label-name" id="file-name-display">Belum ada file dipilih</span>
                            </label>
                        </div>
                        <p class="field-hint">Format: JPG, PNG. Maks 2MB.</p>
                        @error('foto')
                        <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>
        </div>

        {{-- ── DATA KEPEGAWAIAN ── --}}
        <div class="form-section glass-card">
            <div class="section-head">
                <span class="section-icon" aria-hidden="true">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/>
                    </svg>
                </span>
                <h2 class="section-title">Data Kepegawaian</h2>
            </div>
            <div class="section-body">
                <div class="field-grid">

                    {{-- NIP --}}
                    <div class="field">
                        <label class="field-label" for="nip">NIP</label>
                        <input type="text" id="nip" name="nip"
                               value="{{ old('nip', $guru->nip) }}"
                               class="field-input mono"
                               placeholder="Nomor Induk Pegawai"
                               inputmode="numeric">
                    </div>

                    {{-- NUPTK --}}
                    <div class="field">
                        <label class="field-label" for="nuptk">NUPTK</label>
                        <input type="text" id="nuptk" name="nuptk"
                               value="{{ old('nuptk', $guru->nuptk) }}"
                               class="field-input mono"
                               placeholder="Nomor Unik PTK"
                               inputmode="numeric">
                    </div>

                    {{-- Status Kepegawaian --}}
                    <div class="field">
                        <label class="field-label" for="status_kepegawaian">Status Kepegawaian</label>
                        <select id="status_kepegawaian" name="status_kepegawaian" class="field-input field-select">
                            <option value="">— Pilih —</option>
                            <option value="pns"     @selected(old('status_kepegawaian', $guru->status_kepegawaian) === 'pns')>PNS</option>
                            <option value="pppk"    @selected(old('status_kepegawaian', $guru->status_kepegawaian) === 'pppk')>PPPK</option>
                            <option value="honorer" @selected(old('status_kepegawaian', $guru->status_kepegawaian) === 'honorer')>Honorer</option>
                            <option value="gtty"    @selected(old('status_kepegawaian', $guru->status_kepegawaian) === 'gtty')>GTT / GTY</option>
                        </select>
                    </div>

                    {{-- Jabatan --}}
                    <div class="field">
                        <label class="field-label" for="jabatan">Jabatan</label>
                        <input type="text" id="jabatan" name="jabatan"
                               value="{{ old('jabatan', $guru->jabatan) }}"
                               class="field-input"
                               placeholder="Guru Kelas, Wali Kelas KB…">
                    </div>

                    {{-- Pendidikan Terakhir --}}
                    <div class="field">
                        <label class="field-label" for="pendidikan_terakhir">Pendidikan Terakhir</label>
                        <select id="pendidikan_terakhir" name="pendidikan_terakhir" class="field-input field-select">
                            <option value="">— Pilih —</option>
                            @foreach(['SMA/SMK','D1','D2','D3','S1','S2','S3'] as $pend)
                            <option value="{{ $pend }}" @selected(old('pendidikan_terakhir', $guru->pendidikan_terakhir) === $pend)>{{ $pend }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Jurusan --}}
                    <div class="field">
                        <label class="field-label" for="jurusan">Jurusan / Program Studi</label>
                        <input type="text" id="jurusan" name="jurusan"
                               value="{{ old('jurusan', $guru->jurusan) }}"
                               class="field-input"
                               placeholder="Pendidikan Anak Usia Dini">
                    </div>

                    {{-- Tanggal Bergabung --}}
                    <div class="field">
                        <label class="field-label" for="tanggal_bergabung">Tanggal Bergabung</label>
                        <input type="date" id="tanggal_bergabung" name="tanggal_bergabung"
                               value="{{ old('tanggal_bergabung', $guru->tanggal_bergabung ? \Carbon\Carbon::parse($guru->tanggal_bergabung)->format('Y-m-d') : '') }}"
                               class="field-input">
                    </div>

                </div>
            </div>
        </div>

        {{-- ── GANTI PIN ── --}}
        <div class="form-section glass-card">
            <div class="section-head">
                <span class="section-icon" aria-hidden="true">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                </span>
                <h2 class="section-title">Ganti PIN Login</h2>
            </div>
            <div class="section-body">
                <p class="section-note">Kosongkan kedua field jika tidak ingin mengganti PIN.</p>
                <div class="field-grid">
                    <div class="field">
                        <label class="field-label" for="pin">PIN Baru</label>
                        <input type="password" id="pin" name="pin" maxlength="4"
                               class="field-input mono pin-input @error('pin') is-error @enderror"
                               placeholder="••••"
                               autocomplete="new-password"
                               inputmode="numeric">
                        @error('pin')
                        <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="pin_confirmation">Konfirmasi PIN Baru</label>
                        <input type="password" id="pin_confirmation" name="pin_confirmation" maxlength="4"
                               class="field-input mono pin-input"
                               placeholder="••••"
                               autocomplete="new-password"
                               inputmode="numeric">
                    </div>
                </div>
            </div>
        </div>

        {{-- ── ACTION BUTTONS ── --}}
        <div class="form-actions">
            <a href="{{ route('guru.show', $guru) }}" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-submit">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/>
                </svg>
                Simpan Perubahan
            </button>
        </div>

    </form>
</div>

<style>
/* ============================================================
   GURU EDIT — Glassmorphism · SIP-Pelangi v2
   Selaras penuh dengan design system app.blade.php
   ============================================================ */

.edit-page {
    --glass-bg:       oklch(99.5% 0.003 250 / 72%);
    --glass-bg-2:     oklch(98% 0.005 250 / 60%);
    --glass-border:   oklch(90% 0.007 250 / 65%);
    --glass-border-a: oklch(52% 0.190 260 / 14%);
    --glass-blur:     blur(20px) saturate(1.6);
    --glass-shadow:   0 4px 24px oklch(52% 0.190 260 / 7%), 0 1px 3px oklch(0% 0 0 / 5%);

    --field-bg:       oklch(99% 0.002 250 / 80%);
    --field-border:   oklch(88% 0.008 250);
    --field-focus-ring: oklch(52% 0.190 260 / 14%);

    max-width: 56rem;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: var(--sp-9, 24px);
    font-family: 'Geist', system-ui, sans-serif;
    font-size: var(--fs-base, 14px);
}

.dark .edit-page {
    --glass-bg:         oklch(15.5% 0.012 255 / 80%);
    --glass-bg-2:       oklch(13.5% 0.012 255 / 70%);
    --glass-border:     oklch(28% 0.010 255 / 55%);
    --glass-border-a:   oklch(63% 0.185 260 / 20%);
    --glass-shadow:     0 4px 28px oklch(0% 0 0 / 40%), 0 1px 4px oklch(0% 0 0 / 25%);
    --field-bg:         oklch(18.5% 0.013 255 / 70%);
    --field-border:     oklch(30% 0.010 255 / 70%);
    --field-focus-ring: oklch(63% 0.185 260 / 16%);
}

/* ── Glass Card ── */
.glass-card {
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: var(--r-lg, 14px);
    box-shadow: var(--glass-shadow);
    backdrop-filter: var(--glass-blur);
    -webkit-backdrop-filter: var(--glass-blur);
    overflow: hidden;
}

/* ── Edit Header ── */
.edit-header {
    display: flex;
    align-items: center;
    gap: var(--sp-4, 12px);
}

.btn-back {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 38px; height: 38px;
    border-radius: var(--r, 10px);
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    backdrop-filter: var(--glass-blur);
    -webkit-backdrop-filter: var(--glass-blur);
    box-shadow: var(--glass-shadow);
    color: var(--text-2);
    text-decoration: none;
    flex-shrink: 0;
    transition:
        background var(--dur-fast) var(--ease-out),
        border-color var(--dur-fast) var(--ease-out),
        color var(--dur-fast) var(--ease-out),
        transform var(--dur-fast) var(--ease-out);
}
.btn-back:hover {
    background: var(--glass-bg-2);
    border-color: var(--glass-border-a);
    color: var(--accent);
    transform: translateX(-2px);
}

.edit-title {
    font-size: var(--fs-lg, 18px);
    font-weight: 700;
    color: var(--text-1);
    letter-spacing: -0.03em;
    margin: 0 0 3px;
    line-height: 1.2;
}
.edit-sub {
    font-size: var(--fs-sm, 13px);
    color: var(--text-2);
    margin: 0;
}
.edit-sub-sep { color: var(--text-3); margin: 0 2px; }
.edit-sub-username {
    font-family: 'Geist Mono', monospace;
    font-size: var(--fs-xs, 11px);
    color: var(--text-3);
}

/* ── Alert Error ── */
.alert-error {
    display: flex;
    align-items: flex-start;
    gap: var(--sp-3, 10px);
    padding: var(--sp-5, 14px) var(--sp-6, 16px);
    background: oklch(50% 0.210 27 / 6%) !important;
    border-color: oklch(50% 0.210 27 / 22%) !important;
    backdrop-filter: var(--glass-blur);
    -webkit-backdrop-filter: var(--glass-blur);
}
.dark .alert-error {
    background: oklch(18% 0.045 27 / 50%) !important;
    border-color: oklch(32% 0.090 27 / 40%) !important;
}
.alert-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 30px; height: 30px;
    border-radius: 50%;
    background: oklch(50% 0.210 27 / 10%);
    border: 1px solid oklch(50% 0.210 27 / 22%);
    color: var(--danger);
    flex-shrink: 0;
    margin-top: 1px;
}
.alert-title {
    font-size: var(--fs-sm, 13px);
    font-weight: 700;
    color: var(--danger);
    margin: 0 0 6px;
}
.alert-list {
    margin: 0;
    padding: 0 0 0 16px;
    display: flex;
    flex-direction: column;
    gap: 3px;
}
.alert-list li {
    font-size: var(--fs-xs, 11px);
    color: var(--danger);
    line-height: 1.5;
}

/* ── Form Layout ── */
.edit-form {
    display: flex;
    flex-direction: column;
    gap: var(--sp-9, 24px);
}

/* ── Section ── */
.section-head {
    display: flex;
    align-items: center;
    gap: var(--sp-3, 10px);
    padding: var(--sp-4, 12px) var(--sp-7, 18px);
    border-bottom: 1px solid var(--glass-border);
    background: oklch(52% 0.190 260 / 3.5%);
}
.dark .section-head {
    background: oklch(63% 0.185 260 / 5%);
}
.section-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 26px; height: 26px;
    border-radius: var(--r-sm, 6px);
    background: oklch(52% 0.190 260 / 10%);
    border: 1px solid oklch(52% 0.190 260 / 18%);
    color: var(--accent);
    flex-shrink: 0;
}
.dark .section-icon {
    background: oklch(63% 0.185 260 / 12%);
    border-color: oklch(63% 0.185 260 / 25%);
}
.section-title {
    font-size: var(--fs-xs, 11px);
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--text-2);
    margin: 0;
}
.section-note {
    font-size: var(--fs-xs, 11px);
    color: var(--text-3);
    margin: 0 0 var(--sp-6, 16px);
    line-height: 1.5;
}
.section-body {
    padding: var(--sp-7, 18px) var(--sp-7, 18px);
}

/* ── Field Grid ── */
.field-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--sp-5, 14px) var(--sp-6, 16px);
}
.field { display: flex; flex-direction: column; gap: 5px; }
.col-span-2 { grid-column: span 2; }

/* ── Labels ── */
.field-label {
    font-size: var(--fs-xs, 11px);
    font-weight: 600;
    color: var(--text-2);
    letter-spacing: 0.01em;
}
.required {
    color: var(--danger);
    font-weight: 700;
    margin-left: 1px;
}

/* ── Inputs ── */
.field-input {
    width: 100%;
    padding: 9px 13px;
    background: var(--field-bg);
    border: 1.5px solid var(--field-border);
    border-radius: var(--r, 10px);
    font-size: var(--fs-sm, 13px);
    color: var(--text-1);
    font-family: 'Geist', system-ui, sans-serif;
    outline: none;
    transition:
        border-color var(--dur-fast) var(--ease-out),
        box-shadow var(--dur-mid) var(--ease-out),
        background var(--dur-mid) var(--ease-out);
    -webkit-appearance: none;
    appearance: none;
}
.field-input::placeholder { color: var(--text-3); }
.field-input:hover {
    border-color: oklch(52% 0.190 260 / 35%);
}
.field-input:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px var(--field-focus-ring);
    background: oklch(99.5% 0.003 250 / 95%);
}
.dark .field-input:focus {
    background: oklch(20% 0.015 255 / 90%);
}
.field-input.is-error {
    border-color: var(--danger);
    background: oklch(50% 0.210 27 / 5%);
}
.field-input.is-error:focus {
    box-shadow: 0 0 0 3px oklch(50% 0.210 27 / 14%);
}

/* Mono font fields (username, NIP, NUPTK, HP, PIN) */
.field-input.mono {
    font-family: 'Geist Mono', 'SF Mono', monospace;
    font-size: var(--fs-xs, 11px);
    letter-spacing: 0.02em;
}

/* Select */
.field-select {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    padding-right: 36px;
    cursor: pointer;
}

/* Textarea */
.field-textarea {
    resize: vertical;
    min-height: 80px;
    line-height: 1.6;
}

/* PIN Input */
.pin-input {
    letter-spacing: 0.3em;
    text-align: center;
    font-size: var(--fs-lg, 18px) !important;
    font-weight: 600;
}

/* Hint & Error text */
.field-hint  { font-size: var(--fs-2xs, 9px); color: var(--text-3); line-height: 1.5; }
.field-error { font-size: var(--fs-2xs, 9px); color: var(--danger); font-weight: 600; line-height: 1.5; }

/* ── Foto upload ── */
.foto-preview {
    display: flex;
    align-items: center;
    gap: var(--sp-4, 12px);
    margin-bottom: var(--sp-3, 10px);
    padding: var(--sp-3, 10px) var(--sp-4, 12px);
    background: oklch(52% 0.190 260 / 4%);
    border: 1px solid var(--glass-border-a);
    border-radius: var(--r, 10px);
}
.dark .foto-preview {
    background: oklch(63% 0.185 260 / 7%);
}
.foto-thumb {
    width: 52px; height: 52px;
    border-radius: var(--r, 10px);
    object-fit: cover;
    border: 1.5px solid var(--glass-border-a);
    flex-shrink: 0;
}
.foto-hint {
    font-size: var(--fs-xs, 11px);
    color: var(--text-2);
    margin: 0;
    line-height: 1.5;
}

.file-input { display: none; }
.file-wrap { display: flex; }
.file-label {
    display: inline-flex;
    align-items: center;
    gap: var(--sp-2, 8px);
    padding: 9px 16px;
    background: var(--field-bg);
    border: 1.5px dashed var(--field-border);
    border-radius: var(--r, 10px);
    font-size: var(--fs-sm, 13px);
    color: var(--text-2);
    cursor: pointer;
    width: 100%;
    transition:
        border-color var(--dur-fast) var(--ease-out),
        background var(--dur-fast) var(--ease-out),
        color var(--dur-fast) var(--ease-out);
}
.file-label:hover {
    border-color: var(--accent);
    background: oklch(52% 0.190 260 / 5%);
    color: var(--accent);
}
.dark .file-label:hover {
    background: oklch(63% 0.185 260 / 8%);
}
.file-label svg { flex-shrink: 0; color: var(--text-3); }
.file-label:hover svg { color: var(--accent); }
.file-label-text { font-weight: 600; }
.file-label-name {
    font-size: var(--fs-xs, 11px);
    color: var(--text-3);
    font-family: 'Geist Mono', monospace;
    margin-left: auto;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    max-width: 160px;
}

/* ── Form Actions ── */
.form-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: var(--sp-4, 12px);
    padding: var(--sp-2, 8px) 0;
}

.btn-cancel {
    display: inline-flex;
    align-items: center;
    padding: 9px 20px;
    background: transparent;
    border: 1.5px solid var(--glass-border);
    border-radius: var(--r, 10px);
    font-size: var(--fs-sm, 13px);
    font-weight: 600;
    color: var(--text-2);
    text-decoration: none;
    font-family: inherit;
    transition:
        background var(--dur-fast) var(--ease-out),
        border-color var(--dur-fast) var(--ease-out),
        color var(--dur-fast) var(--ease-out);
}
.btn-cancel:hover {
    background: oklch(68% 0.008 255 / 8%);
    border-color: oklch(68% 0.008 255 / 30%);
    color: var(--text-1);
}

.btn-submit {
    display: inline-flex;
    align-items: center;
    gap: var(--sp-2, 8px);
    padding: 9px 22px;
    background: var(--accent);
    color: var(--text-inv);
    font-size: var(--fs-sm, 13px);
    font-weight: 700;
    border: none;
    border-radius: var(--r, 10px);
    cursor: pointer;
    font-family: inherit;
    transition:
        background var(--dur-fast) var(--ease-out),
        transform var(--dur-fast) var(--ease-out),
        box-shadow var(--dur-mid) var(--ease-out);
    box-shadow: 0 2px 12px oklch(52% 0.190 260 / 30%);
}
.btn-submit:hover {
    background: var(--accent-h);
    transform: translateY(-1px);
    box-shadow: 0 6px 20px oklch(52% 0.190 260 / 42%);
}
.btn-submit:active { transform: translateY(0); }

/* ── Responsive ── */
@media (max-width: 640px) {
    .field-grid { grid-template-columns: 1fr; }
    .col-span-2 { grid-column: span 1; }
    .edit-title { font-size: var(--fs-md, 15px); }
    .section-body { padding: var(--sp-5, 14px); }
    .form-actions { flex-direction: column-reverse; }
    .btn-cancel, .btn-submit { width: 100%; justify-content: center; }
    .file-label-name { display: none; }
}

/* ── Reduced motion (WCAG) ── */
@media (prefers-reduced-motion: reduce) {
    .field-input, .btn-back, .btn-submit, .btn-cancel,
    .file-label, .glass-card { transition: none; }
}
</style>

@push('scripts')
<script>
    // File name display
    const fileInput = document.getElementById('foto');
    const fileNameDisplay = document.getElementById('file-name-display');
    if (fileInput && fileNameDisplay) {
        fileInput.addEventListener('change', () => {
            const name = fileInput.files[0]?.name ?? 'Belum ada file dipilih';
            fileNameDisplay.textContent = name;
        });
    }

    // PIN: hanya angka
    document.querySelectorAll('.pin-input').forEach(el => {
        el.addEventListener('input', () => {
            el.value = el.value.replace(/\D/g, '').slice(0, 4);
        });
    });
</script>
@endpush
@endsection