{{-- ======================================================================
     GURU CREATE  →  resources/views/guru/create.blade.php
     Impeccable craft · Glass purposeful · SIP-Pelangi design system
     ====================================================================== --}}
@extends('layouts.app')
@section('title', 'Tambah Guru — PAUD KB Pelangi')
@section('page-title', 'Tambah Guru')

@section('content')
<div class="gc-wrap" x-data="formGuru()">

    {{-- ── Top bar: breadcrumb + progress ── --}}
    <div class="gc-topbar">
        <nav class="gc-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('guru.index') }}" class="gc-bc-link">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                Data Guru
            </a>
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
            <span class="gc-bc-now" aria-current="page">Tambah Guru</span>
        </nav>

        {{-- Step indicator --}}
        <div class="gc-steps" aria-label="Langkah form">
            <div class="gc-step" :class="{ 'is-done': step > 1, 'is-active': step === 1 }">
                <span class="gc-step-dot">
                    <template x-if="step > 1">
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6L9 17l-5-5"/></svg>
                    </template>
                    <template x-if="step <= 1"><span>1</span></template>
                </span>
                <span class="gc-step-label">Identitas</span>
            </div>
            <div class="gc-step-line"></div>
            <div class="gc-step" :class="{ 'is-done': step > 2, 'is-active': step === 2 }">
                <span class="gc-step-dot">
                    <template x-if="step > 2">
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6L9 17l-5-5"/></svg>
                    </template>
                    <template x-if="step <= 2"><span>2</span></template>
                </span>
                <span class="gc-step-label">Kepegawaian</span>
            </div>
            <div class="gc-step-line"></div>
            <div class="gc-step" :class="{ 'is-active': step === 3 }">
                <span class="gc-step-dot"><span>3</span></span>
                <span class="gc-step-label">Selesai</span>
            </div>
        </div>
    </div>

    {{-- ── Validation errors ── --}}
    @if ($errors->any())
    <div class="gc-alert" role="alert" aria-live="assertive">
        <div class="gc-alert-badge">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
        </div>
        <div>
            <p class="gc-alert-title">{{ $errors->count() }} kesalahan perlu diperbaiki</p>
            <ul class="gc-alert-list" aria-label="Daftar kesalahan">
                @foreach ($errors->all() as $err)
                <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <form method="POST" action="{{ route('guru.store') }}" enctype="multipart/form-data" novalidate>
        @csrf

        {{-- ══════════════════════════════════════════════
             STEP 1  ·  IDENTITAS GURU
        ══════════════════════════════════════════════════ --}}
        <div class="gc-card" id="step-identitas">
            <div class="gc-card-head">
                <div class="gc-card-icon" aria-hidden="true">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
                <div>
                    <h2 class="gc-card-title">Identitas Guru</h2>
                    <p class="gc-card-sub">Informasi dasar dan akun login</p>
                </div>
                <div class="gc-card-step-badge" aria-hidden="true">1 / 2</div>
            </div>

            <div class="gc-card-body">

                {{-- ── Foto + Nama + Username (top row) ── --}}
                <div class="gc-identity-row">

                    {{-- Foto clickable zone --}}
                    <div class="gc-foto-zone">
                        <button type="button"
                                class="gc-foto-btn"
                                @click="$refs.fotoInput.click()"
                                :aria-label="fotoPreview ? 'Ganti foto guru' : 'Pilih foto guru'"
                                :class="{ 'has-photo': fotoPreview }">

                            {{-- State: no photo --}}
                            <template x-if="!fotoPreview">
                                <div class="gc-foto-empty">
                                    <div class="gc-foto-empty-avatar" aria-hidden="true">
                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    </div>
                                    <div class="gc-foto-empty-cta" aria-hidden="true">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                                        Foto
                                    </div>
                                </div>
                            </template>

                            {{-- State: photo selected --}}
                            <template x-if="fotoPreview">
                                <div class="gc-foto-preview-wrap">
                                    <img :src="fotoPreview" class="gc-foto-img" alt="Preview foto guru">
                                    <div class="gc-foto-overlay" aria-hidden="true">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                        Ganti
                                    </div>
                                </div>
                            </template>

                        </button>
                        <input type="file" name="foto" accept="image/*" class="gc-hidden" x-ref="fotoInput"
                               @change="handleFoto($event)" aria-label="Upload foto guru">
                        <p class="gc-foto-hint">JPG/PNG, maks 2MB</p>
                        @error('foto')<p class="gc-field-error">{{ $message }}</p>@enderror
                    </div>

                    {{-- Nama + Username col --}}
                    <div class="gc-identity-fields">
                        {{-- Nama Lengkap --}}
                        <div class="gc-field gc-field-full">
                            <label class="gc-label" for="nama_lengkap">
                                Nama Lengkap <span class="gc-req" aria-label="wajib">*</span>
                            </label>
                            <input type="text" id="nama_lengkap" name="nama_lengkap"
                                   value="{{ old('nama_lengkap') }}" required
                                   placeholder="Nama lengkap sesuai ijazah"
                                   class="gc-input @error('nama_lengkap') is-err @enderror"
                                   autocomplete="name" autocorrect="off">
                            @error('nama_lengkap')<p class="gc-field-error">{{ $message }}</p>@enderror
                        </div>

                        {{-- Username --}}
                        <div class="gc-field">
                            <label class="gc-label" for="username">
                                Username <span class="gc-req" aria-label="wajib">*</span>
                            </label>
                            <div class="gc-input-affix-wrap">
                                <span class="gc-input-prefix" aria-hidden="true">&#64;</span>
                                <input type="text" id="username" name="username"
                                       value="{{ old('username') }}" required
                                       placeholder="username_unik"
                                       class="gc-input has-prefix @error('username') is-err @enderror"
                                       autocomplete="username" autocorrect="off" spellcheck="false">
                            </div>
                            @error('username')<p class="gc-field-error">{{ $message }}</p>@enderror
                        </div>

                        {{-- Jenis Kelamin --}}
                        <div class="gc-field">
                            <label class="gc-label" for="jenis_kelamin">Jenis Kelamin</label>
                            <select id="jenis_kelamin" name="jenis_kelamin" class="gc-input gc-select">
                                <option value="">Pilih…</option>
                                <option value="L" {{ old('jenis_kelamin') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin') === 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- ── Divider ── --}}
                <div class="gc-section-divider">
                    <span>Kontak</span>
                </div>

                {{-- ── Contact row ── --}}
                <div class="gc-field-grid gc-cols-3">
                    <div class="gc-field">
                        <label class="gc-label" for="no_hp">No. HP</label>
                        <div class="gc-input-affix-wrap">
                            <span class="gc-input-prefix" aria-hidden="true">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.69 3.4 2 2 0 0 1 3.69 1.22h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.91a16 16 0 0 0 6 6l1.06-1.06a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21.73 16z"/></svg>
                            </span>
                            <input type="tel" id="no_hp" name="no_hp"
                                   value="{{ old('no_hp') }}"
                                   placeholder="0812-xxxx-xxxx"
                                   class="gc-input has-prefix"
                                   inputmode="tel">
                        </div>
                    </div>

                    <div class="gc-field gc-col-span-2">
                        <label class="gc-label" for="email">Email</label>
                        <div class="gc-input-affix-wrap">
                            <span class="gc-input-prefix" aria-hidden="true">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            </span>
                            <input type="email" id="email" name="email"
                                   value="{{ old('email') }}"
                                   placeholder="guru@contoh.com"
                                   class="gc-input has-prefix"
                                   inputmode="email" autocomplete="email">
                        </div>
                    </div>

                    <div class="gc-field gc-col-span-3">
                        <label class="gc-label" for="alamat">Alamat</label>
                        <textarea id="alamat" name="alamat" rows="2"
                                  class="gc-input gc-textarea"
                                  placeholder="Jl. nama jalan, no. rumah, kelurahan, kecamatan, kota…">{{ old('alamat') }}</textarea>
                    </div>
                </div>

                {{-- ── Divider ── --}}
                <div class="gc-section-divider">
                    <span>Tanggal &amp; Tempat Lahir</span>
                </div>

                <div class="gc-field-grid gc-cols-2">
                    <div class="gc-field">
                        <label class="gc-label" for="tempat_lahir">Tempat Lahir</label>
                        <input type="text" id="tempat_lahir" name="tempat_lahir"
                               value="{{ old('tempat_lahir') }}"
                               placeholder="Nama kota"
                               class="gc-input">
                    </div>
                    <div class="gc-field">
                        <label class="gc-label" for="tanggal_lahir">Tanggal Lahir</label>
                        <input type="date" id="tanggal_lahir" name="tanggal_lahir"
                               value="{{ old('tanggal_lahir') }}"
                               class="gc-input">
                    </div>
                </div>

                {{-- ── PIN Block ── --}}
                <div class="gc-pin-block">
                    <div class="gc-pin-head">
                        <div class="gc-pin-icon" aria-hidden="true">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        </div>
                        <div>
                            <p class="gc-pin-title">PIN Login</p>
                            <p class="gc-pin-sub">4 digit angka untuk masuk ke aplikasi</p>
                        </div>
                        <div class="gc-pin-security-badge" aria-label="Terenkripsi">
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            Aman
                        </div>
                    </div>
                    <div class="gc-pin-inputs">
                        <div class="gc-field">
                            <label class="gc-label" for="pin">
                                PIN Baru <span class="gc-req" aria-label="wajib">*</span>
                            </label>
                            <input type="password" id="pin" name="pin"
                                   placeholder="••••" maxlength="4" required
                                   class="gc-input gc-input-pin @error('pin') is-err @enderror"
                                   inputmode="numeric" pattern="[0-9]*"
                                   autocomplete="new-password">
                            @error('pin')<p class="gc-field-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="gc-field">
                            <label class="gc-label" for="pin_confirmation">
                                Konfirmasi PIN <span class="gc-req" aria-label="wajib">*</span>
                            </label>
                            <input type="password" id="pin_confirmation" name="pin_confirmation"
                                   placeholder="••••" maxlength="4" required
                                   class="gc-input gc-input-pin"
                                   inputmode="numeric" pattern="[0-9]*"
                                   autocomplete="new-password">
                        </div>
                    </div>
                </div>

            </div>{{-- /gc-card-body --}}
        </div>{{-- /step-identitas --}}

        {{-- ══════════════════════════════════════════════
             STEP 2  ·  DATA KEPEGAWAIAN
        ══════════════════════════════════════════════════ --}}
        <div class="gc-card" id="step-kepegawaian">
            <div class="gc-card-head">
                <div class="gc-card-icon gc-card-icon-alt" aria-hidden="true">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                </div>
                <div>
                    <h2 class="gc-card-title">Data Kepegawaian</h2>
                    <p class="gc-card-sub">Opsional — lengkapi jika tersedia</p>
                </div>
                <div class="gc-card-step-badge" aria-hidden="true">2 / 2</div>
            </div>

            <div class="gc-card-body">
                <div class="gc-field-grid gc-cols-2">

                    <div class="gc-field">
                        <label class="gc-label" for="nip">NIP</label>
                        <input type="text" id="nip" name="nip"
                               value="{{ old('nip') }}"
                               placeholder="18 digit"
                               class="gc-input gc-mono @error('nip') is-err @enderror"
                               inputmode="numeric">
                        @error('nip')<p class="gc-field-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="gc-field">
                        <label class="gc-label" for="nuptk">NUPTK</label>
                        <input type="text" id="nuptk" name="nuptk"
                               value="{{ old('nuptk') }}"
                               placeholder="16 digit"
                               class="gc-input gc-mono"
                               inputmode="numeric">
                    </div>

                    <div class="gc-field">
                        <label class="gc-label" for="status_kepegawaian">Status Kepegawaian</label>
                        <select id="status_kepegawaian" name="status_kepegawaian" class="gc-input gc-select">
                            <option value="">Pilih…</option>
                            <option value="pns"     {{ old('status_kepegawaian') === 'pns'     ? 'selected' : '' }}>PNS</option>
                            <option value="pppk"    {{ old('status_kepegawaian') === 'pppk'    ? 'selected' : '' }}>PPPK</option>
                            <option value="honorer" {{ old('status_kepegawaian') === 'honorer' ? 'selected' : '' }}>Honorer</option>
                            <option value="gtty"    {{ old('status_kepegawaian') === 'gtty'    ? 'selected' : '' }}>GTT / GTY</option>
                        </select>
                    </div>

                    <div class="gc-field">
                        <label class="gc-label" for="jabatan">Jabatan</label>
                        <input type="text" id="jabatan" name="jabatan"
                               value="{{ old('jabatan') }}"
                               placeholder="Guru Kelas, Guru Pendamping…"
                               class="gc-input">
                    </div>

                    <div class="gc-field">
                        <label class="gc-label" for="pendidikan_terakhir">Pendidikan Terakhir</label>
                        <select id="pendidikan_terakhir" name="pendidikan_terakhir" class="gc-input gc-select">
                            <option value="">Pilih…</option>
                            @foreach(['SMA/SMK','D1','D2','D3','S1','S2','S3'] as $pend)
                            <option value="{{ $pend }}" {{ old('pendidikan_terakhir') === $pend ? 'selected' : '' }}>{{ $pend }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="gc-field">
                        <label class="gc-label" for="jurusan">Jurusan / Program Studi</label>
                        <input type="text" id="jurusan" name="jurusan"
                               value="{{ old('jurusan') }}"
                               placeholder="Pendidikan Anak Usia Dini"
                               class="gc-input">
                    </div>

                    <div class="gc-field">
                        <label class="gc-label" for="tanggal_bergabung">Tanggal Bergabung</label>
                        <input type="date" id="tanggal_bergabung" name="tanggal_bergabung"
                               value="{{ old('tanggal_bergabung') }}"
                               class="gc-input">
                    </div>

                    {{-- Alamat (optional second entry point via kepegawaian - kept from original) --}}

                </div>
            </div>
        </div>

        {{-- ── Action bar ── --}}
        <div class="gc-actions">
            <a href="{{ route('guru.index') }}" class="gc-btn-cancel">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
                Batal
            </a>
            <div class="gc-actions-right">
                <span class="gc-fields-note">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
                    Field <span class="gc-req">*</span> wajib diisi
                </span>
                <button type="submit" class="gc-btn-submit">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Simpan Guru
                </button>
            </div>
        </div>

    </form>
</div>

@push('styles')
<style>
/* ============================================================
   GURU CREATE — Impeccable craft
   SIP-Pelangi design system · OKLCH tokens · Geist font
   Scene: Admin PAUD, siang hari, laptop 13", butuh cepat
   ============================================================ */

.gc-wrap {
    /* Local glass tokens — selaras app.blade.php :root */
    --gc-glass-bg:        oklch(99.5% 0.003 250 / 74%);
    --gc-glass-bg-alt:    oklch(98%   0.006 250 / 62%);
    --gc-glass-border:    oklch(90%   0.007 250 / 65%);
    --gc-glass-border-a:  oklch(52%   0.190 260 / 15%);
    --gc-glass-blur:      blur(22px) saturate(1.65);
    --gc-glass-shadow:    0 2px 16px oklch(52% 0.190 260 / 6%), 0 1px 3px oklch(0% 0 0 / 4%);
    --gc-glass-shadow-h:  0 6px 28px oklch(52% 0.190 260 / 11%), 0 2px 6px oklch(0% 0 0 / 5%);

    --gc-field-bg:        oklch(99%   0.002 250 / 82%);
    --gc-field-border:    oklch(87%   0.009 255);
    --gc-field-ring:      oklch(52%   0.190 260 / 13%);
    --gc-field-radius:    var(--r, 10px);

    --gc-pin-bg:          oklch(52%   0.190 260 /  5%);
    --gc-pin-border:      oklch(52%   0.190 260 / 15%);

    max-width: 720px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 20px;
    font-family: 'Geist', system-ui, sans-serif;
    font-size: var(--fs-base, 14px);
}

.dark .gc-wrap {
    --gc-glass-bg:       oklch(15.5% 0.012 255 / 82%);
    --gc-glass-bg-alt:   oklch(13%   0.012 255 / 72%);
    --gc-glass-border:   oklch(28%   0.010 255 / 55%);
    --gc-glass-border-a: oklch(63%   0.185 260 / 22%);
    --gc-glass-shadow:   0 4px 28px oklch(0% 0 0 / 42%), 0 1px 4px oklch(0% 0 0 / 28%);
    --gc-glass-shadow-h: 0 8px 36px oklch(0% 0 0 / 52%), 0 2px 8px oklch(0% 0 0 / 32%);
    --gc-field-bg:       oklch(18%   0.013 255 / 70%);
    --gc-field-border:   oklch(30%   0.010 255 / 70%);
    --gc-field-ring:     oklch(63%   0.185 260 / 17%);
    --gc-pin-bg:         oklch(63%   0.185 260 /  8%);
    --gc-pin-border:     oklch(63%   0.185 260 / 22%);
}

.gc-hidden { display: none !important; }

/* ── Topbar ── */
.gc-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
}

/* ── Breadcrumb ── */
.gc-breadcrumb {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: var(--fs-xs, 11px);
    color: var(--text-3);
}
.gc-bc-link {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    color: var(--accent);
    font-weight: 600;
    text-decoration: none;
    transition: opacity var(--dur-fast);
}
.gc-bc-link:hover { opacity: 0.72; text-decoration: underline; }
.gc-bc-now { font-weight: 700; color: var(--text-1); }

/* ── Step indicator ── */
.gc-steps {
    display: flex;
    align-items: center;
    gap: 0;
}
.gc-step {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: var(--fs-2xs, 9px);
    font-weight: 600;
    letter-spacing: 0.03em;
    color: var(--text-3);
    transition: color var(--dur-mid);
}
.gc-step.is-active { color: var(--accent); }
.gc-step.is-done   { color: var(--text-2); }

.gc-step-dot {
    width: 22px; height: 22px;
    border-radius: 50%;
    border: 1.5px solid var(--gc-field-border);
    display: flex; align-items: center; justify-content: center;
    font-size: 9px; font-weight: 700;
    color: var(--text-3);
    background: var(--gc-glass-bg);
    transition:
        border-color var(--dur-mid),
        background   var(--dur-mid),
        color        var(--dur-mid);
    flex-shrink: 0;
}
.gc-step.is-active .gc-step-dot {
    border-color: var(--accent);
    background: var(--accent);
    color: var(--text-inv);
    box-shadow: 0 2px 8px oklch(52% 0.190 260 / 30%);
}
.gc-step.is-done .gc-step-dot {
    border-color: oklch(52% 0.190 260 / 30%);
    background: oklch(52% 0.190 260 / 10%);
    color: var(--accent);
}
.gc-step-line {
    width: 28px; height: 1.5px;
    background: var(--gc-glass-border);
    margin: 0 4px;
    flex-shrink: 0;
}
.gc-step-label { white-space: nowrap; }
@media (max-width: 480px) { .gc-step-label { display: none; } }

/* ── Alert ── */
.gc-alert {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 14px 16px;
    background: oklch(50% 0.210 27 / 6%);
    border: 1px solid oklch(50% 0.210 27 / 22%);
    border-radius: var(--r-lg, 14px);
    backdrop-filter: var(--gc-glass-blur);
    -webkit-backdrop-filter: var(--gc-glass-blur);
}
.dark .gc-alert {
    background: oklch(18% 0.045 27 / 50%);
    border-color: oklch(32% 0.090 27 / 40%);
}
.gc-alert-badge {
    width: 30px; height: 30px;
    border-radius: 50%;
    background: oklch(50% 0.210 27 / 10%);
    border: 1px solid oklch(50% 0.210 27 / 22%);
    color: var(--danger);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.gc-alert-title {
    font-size: var(--fs-sm, 13px);
    font-weight: 700;
    color: var(--danger);
    margin: 0 0 5px;
}
.gc-alert-list {
    margin: 0; padding: 0 0 0 14px;
    display: flex; flex-direction: column; gap: 3px;
}
.gc-alert-list li {
    font-size: var(--fs-xs, 11px);
    color: var(--danger);
    line-height: 1.5;
}

/* ── Card ── */
.gc-card {
    background: var(--gc-glass-bg);
    border: 1px solid var(--gc-glass-border);
    border-radius: var(--r-lg, 14px);
    box-shadow: var(--gc-glass-shadow);
    backdrop-filter: var(--gc-glass-blur);
    -webkit-backdrop-filter: var(--gc-glass-blur);
    overflow: hidden;
    transition:
        box-shadow var(--dur-mid) var(--ease-out),
        border-color var(--dur-mid) var(--ease-out);
}
.gc-card:focus-within {
    border-color: var(--gc-glass-border-a);
    box-shadow: var(--gc-glass-shadow-h);
}

/* ── Card head ── */
.gc-card-head {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 20px;
    border-bottom: 1px solid var(--gc-glass-border);
    background: oklch(52% 0.190 260 / 3.5%);
}
.dark .gc-card-head {
    background: oklch(63% 0.185 260 / 5%);
}
.gc-card-icon {
    width: 32px; height: 32px;
    border-radius: var(--r, 10px);
    background: oklch(52% 0.190 260 / 10%);
    border: 1px solid oklch(52% 0.190 260 / 18%);
    color: var(--accent);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.gc-card-icon-alt {
    background: oklch(56% 0.160 285 / 10%);
    border-color: oklch(56% 0.160 285 / 18%);
    color: oklch(56% 0.160 285);
}
.dark .gc-card-icon     { background: oklch(63% 0.185 260 / 12%); border-color: oklch(63% 0.185 260 / 25%); }
.dark .gc-card-icon-alt { background: oklch(63% 0.160 285 / 12%); border-color: oklch(63% 0.160 285 / 25%); color: oklch(70% 0.140 285); }

.gc-card-title {
    font-size: var(--fs-sm, 13px);
    font-weight: 700;
    color: var(--text-1);
    letter-spacing: -0.02em;
    margin: 0 0 2px;
    line-height: 1.2;
}
.gc-card-sub {
    font-size: var(--fs-2xs, 9px);
    color: var(--text-3);
    margin: 0;
    font-weight: 500;
}
.gc-card-step-badge {
    margin-left: auto;
    font-size: var(--fs-2xs, 9px);
    font-weight: 700;
    letter-spacing: 0.05em;
    color: var(--text-3);
    background: oklch(52% 0.190 260 / 6%);
    border: 1px solid oklch(52% 0.190 260 / 14%);
    padding: 3px 9px;
    border-radius: 20px;
    white-space: nowrap;
}

/* ── Card body ── */
.gc-card-body {
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 18px;
}

/* ── Identity row (foto + fields) ── */
.gc-identity-row {
    display: flex;
    align-items: flex-start;
    gap: 18px;
}
.gc-identity-fields {
    flex: 1;
    min-width: 0;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}
.gc-field-full { grid-column: span 2; }

/* ── Foto zone ── */
.gc-foto-zone {
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
}
.gc-foto-btn {
    width: 88px; height: 88px;
    border-radius: var(--r-lg, 14px);
    border: 2px dashed oklch(52% 0.190 260 / 30%);
    background: oklch(52% 0.190 260 / 5%);
    cursor: pointer;
    overflow: hidden;
    display: flex; align-items: center; justify-content: center;
    position: relative;
    transition:
        border-color var(--dur-mid) var(--ease-out),
        background   var(--dur-mid) var(--ease-out),
        transform    var(--dur-fast) var(--ease-out);
    padding: 0;
}
.gc-foto-btn:hover {
    border-color: var(--accent);
    background: oklch(52% 0.190 260 / 9%);
    transform: scale(1.02);
}
.gc-foto-btn:focus-visible {
    outline: 2px solid var(--accent);
    outline-offset: 3px;
}
.gc-foto-btn.has-photo {
    border-style: solid;
    border-color: oklch(52% 0.190 260 / 25%);
}
.gc-foto-btn.has-photo:hover { border-color: var(--accent); }

.gc-foto-empty {
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    gap: 5px; width: 100%; height: 100%;
}
.gc-foto-empty-avatar { color: oklch(52% 0.190 260 / 50%); }
.gc-foto-empty-cta {
    display: flex; align-items: center; gap: 3px;
    font-size: 9px; font-weight: 700;
    color: var(--accent);
    letter-spacing: 0.04em;
    text-transform: uppercase;
}
.gc-foto-preview-wrap { width: 100%; height: 100%; position: relative; }
.gc-foto-img { width: 100%; height: 100%; object-fit: cover; }
.gc-foto-overlay {
    position: absolute; inset: 0;
    background: oklch(0% 0 0 / 42%);
    color: oklch(99% 0.003 250);
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    gap: 4px;
    font-size: 9px; font-weight: 700;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    opacity: 0;
    transition: opacity var(--dur-mid) var(--ease-out);
}
.gc-foto-btn:hover .gc-foto-overlay { opacity: 1; }

.gc-foto-hint {
    font-size: 9px;
    color: var(--text-3);
    text-align: center;
    line-height: 1.4;
    margin: 0;
}

/* ── Section divider ── */
.gc-section-divider {
    position: relative;
    display: flex;
    align-items: center;
}
.gc-section-divider::before,
.gc-section-divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--gc-glass-border);
}
.gc-section-divider span {
    padding: 0 10px;
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 0.07em;
    text-transform: uppercase;
    color: var(--text-3);
    white-space: nowrap;
    flex-shrink: 0;
}

/* ── Field grid ── */
.gc-field-grid {
    display: grid;
    gap: 12px 14px;
}
.gc-cols-2 { grid-template-columns: 1fr 1fr; }
.gc-cols-3 { grid-template-columns: 1fr 1fr 1fr; }
.gc-col-span-2 { grid-column: span 2; }
.gc-col-span-3 { grid-column: span 3; }

/* ── Field ── */
.gc-field { display: flex; flex-direction: column; gap: 5px; }

.gc-label {
    font-size: var(--fs-2xs, 9px);
    font-weight: 700;
    letter-spacing: 0.03em;
    color: var(--text-2);
    text-transform: uppercase;
}
.gc-req { color: var(--danger); font-weight: 800; margin-left: 1px; }

/* ── Input base ── */
.gc-input {
    width: 100%;
    padding: 9px 12px;
    background: var(--gc-field-bg);
    border: 1.5px solid var(--gc-field-border);
    border-radius: var(--gc-field-radius);
    font-size: var(--fs-sm, 13px);
    color: var(--text-1);
    font-family: 'Geist', system-ui, sans-serif;
    outline: none;
    -webkit-appearance: none;
    appearance: none;
    transition:
        border-color var(--dur-fast) var(--ease-out),
        box-shadow   var(--dur-mid)  var(--ease-out),
        background   var(--dur-mid)  var(--ease-out);
    line-height: 1.5;
}
.gc-input::placeholder { color: var(--text-3); }
.gc-input:hover:not(:focus) {
    border-color: oklch(52% 0.190 260 / 35%);
}
.gc-input:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px var(--gc-field-ring);
    background: oklch(99.5% 0.003 250 / 96%);
}
.dark .gc-input:focus {
    background: oklch(21% 0.015 255 / 90%);
}
.gc-input.is-err {
    border-color: var(--danger);
    background: oklch(50% 0.210 27 / 4%);
}
.gc-input.is-err:focus {
    box-shadow: 0 0 0 3px oklch(50% 0.210 27 / 13%);
}

/* Monospaced (NIP, NUPTK) */
.gc-mono {
    font-family: 'Geist Mono', 'SF Mono', monospace;
    font-size: var(--fs-xs, 11px);
    letter-spacing: 0.04em;
}

/* Select */
.gc-select {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    padding-right: 34px;
    cursor: pointer;
}

/* Textarea */
.gc-textarea {
    resize: vertical;
    min-height: 68px;
    line-height: 1.65;
}

/* PIN input */
.gc-input-pin {
    font-family: 'Geist Mono', monospace;
    font-size: 20px;
    letter-spacing: 0.3em;
    text-align: center;
    font-weight: 700;
}

/* ── Input with prefix icon/text ── */
.gc-input-affix-wrap { position: relative; }
.gc-input-prefix {
    position: absolute;
    left: 11px; top: 50%;
    transform: translateY(-50%);
    color: var(--text-3);
    pointer-events: none;
    display: flex; align-items: center;
    font-size: var(--fs-sm, 13px);
    font-weight: 700;
    font-family: 'Geist Mono', monospace;
    line-height: 1;
    transition: color var(--dur-fast);
}
.gc-input-affix-wrap:focus-within .gc-input-prefix { color: var(--accent); }
.gc-input.has-prefix { padding-left: 30px; }

.gc-field-error {
    font-size: 9px;
    font-weight: 600;
    color: var(--danger);
    margin: 0;
    line-height: 1.5;
}

/* ── PIN block ── */
.gc-pin-block {
    background: var(--gc-pin-bg);
    border: 1px solid var(--gc-pin-border);
    border-radius: var(--r-lg, 14px);
    padding: 14px 16px;
    display: flex;
    flex-direction: column;
    gap: 14px;
}
.gc-pin-head {
    display: flex;
    align-items: center;
    gap: 10px;
}
.gc-pin-icon {
    width: 28px; height: 28px;
    border-radius: 8px;
    background: oklch(52% 0.190 260 / 12%);
    border: 1px solid oklch(52% 0.190 260 / 20%);
    color: var(--accent);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.dark .gc-pin-icon {
    background: oklch(63% 0.185 260 / 14%);
    border-color: oklch(63% 0.185 260 / 28%);
}
.gc-pin-title {
    font-size: var(--fs-sm, 13px);
    font-weight: 700;
    color: var(--text-1);
    margin: 0 0 2px;
}
.gc-pin-sub {
    font-size: 9px;
    color: var(--text-3);
    margin: 0;
}
.gc-pin-security-badge {
    margin-left: auto;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 0.04em;
    color: oklch(52% 0.190 260);
    background: oklch(52% 0.190 260 / 7%);
    border: 1px solid oklch(52% 0.190 260 / 18%);
    padding: 3px 9px;
    border-radius: 20px;
    white-space: nowrap;
}
.dark .gc-pin-security-badge {
    color: var(--accent);
    background: oklch(63% 0.185 260 / 10%);
    border-color: oklch(63% 0.185 260 / 24%);
}
.gc-pin-inputs {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}

/* ── Actions bar ── */
.gc-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 4px 0;
    flex-wrap: wrap;
}
.gc-actions-right {
    display: flex;
    align-items: center;
    gap: 14px;
}
.gc-fields-note {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 9px;
    color: var(--text-3);
    font-weight: 500;
}
.gc-fields-note svg { flex-shrink: 0; }

.gc-btn-cancel {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 9px 18px;
    background: transparent;
    border: 1.5px solid var(--gc-glass-border);
    border-radius: var(--r, 10px);
    font-size: var(--fs-sm, 13px);
    font-weight: 600;
    color: var(--text-2);
    text-decoration: none;
    font-family: inherit;
    transition:
        background      var(--dur-fast) var(--ease-out),
        border-color    var(--dur-fast) var(--ease-out),
        color           var(--dur-fast) var(--ease-out),
        transform       var(--dur-fast) var(--ease-out);
}
.gc-btn-cancel:hover {
    background: oklch(68% 0.008 255 / 8%);
    border-color: oklch(68% 0.008 255 / 30%);
    color: var(--text-1);
    transform: translateX(-2px);
}

.gc-btn-submit {
    display: inline-flex;
    align-items: center;
    gap: 7px;
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
        background   var(--dur-fast) var(--ease-out),
        transform    var(--dur-fast) var(--ease-out),
        box-shadow   var(--dur-mid)  var(--ease-out);
    box-shadow: 0 2px 12px oklch(52% 0.190 260 / 32%);
}
.gc-btn-submit:hover {
    background: var(--accent-h);
    transform: translateY(-1px);
    box-shadow: 0 6px 22px oklch(52% 0.190 260 / 44%);
}
.gc-btn-submit:active { transform: translateY(0); }
.gc-btn-submit:focus-visible {
    outline: 2px solid var(--accent);
    outline-offset: 3px;
}

/* ── Responsive ── */
@media (max-width: 640px) {
    .gc-identity-row    { flex-direction: column; align-items: stretch; }
    .gc-foto-zone       { flex-direction: row; align-items: center; gap: 14px; }
    .gc-foto-btn        { width: 72px; height: 72px; }
    .gc-identity-fields { grid-template-columns: 1fr; }
    .gc-field-full      { grid-column: span 1; }
    .gc-cols-2,
    .gc-cols-3          { grid-template-columns: 1fr; }
    .gc-col-span-2,
    .gc-col-span-3      { grid-column: span 1; }
    .gc-pin-inputs      { grid-template-columns: 1fr; }
    .gc-actions         { flex-direction: column-reverse; align-items: stretch; }
    .gc-actions-right   { flex-direction: column; gap: 10px; }
    .gc-btn-cancel,
    .gc-btn-submit      { width: 100%; justify-content: center; }
    .gc-fields-note     { justify-content: center; }
    .gc-topbar          { flex-direction: column; align-items: flex-start; gap: 10px; }
}

/* ── Reduced motion ── */
@media (prefers-reduced-motion: reduce) {
    .gc-foto-btn,
    .gc-foto-overlay,
    .gc-btn-submit,
    .gc-btn-cancel,
    .gc-input,
    .gc-card           { transition: none; }
}
</style>
@endpush

@push('scripts')
<script>
function formGuru() {
    return {
        step: 1,
        fotoPreview: null,

        handleFoto(event) {
            const file = event.target.files[0];
            if (!file) return;
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran foto maksimal 2MB.');
                event.target.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = e => { this.fotoPreview = e.target.result; };
            reader.readAsDataURL(file);
        }
    }
}

// PIN: hanya angka, maxlength 4
document.querySelectorAll('input.gc-input-pin').forEach(el => {
    el.addEventListener('input', () => {
        el.value = el.value.replace(/\D/g, '').slice(0, 4);
    });
});

// Step tracking: update step indicator saat focus berpindah section
(function() {
    const s1 = document.getElementById('step-identitas');
    const s2 = document.getElementById('step-kepegawaian');
    const wrap = document.querySelector('[x-data]');
    if (!s1 || !s2 || !wrap) return;

    function updateStep(el) {
        const gc = wrap.__x;
        if (!gc) return;
        if (s2.contains(el)) { gc.$data.step = 2; }
        else if (s1.contains(el)) { gc.$data.step = 1; }
    }

    document.addEventListener('focusin', e => updateStep(e.target), true);
})();
</script>
@endpush
@endsection