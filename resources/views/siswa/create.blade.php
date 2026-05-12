@extends('layouts.app')

@section('title', 'Tambah Siswa — PAUD KB Pelangi')
@section('page-title', 'Tambah Siswa')

@push('styles')
<style>
/* ── Tokens (sama dengan index) ── */
:root {
    --blue:     #3B82F6;
    --blue-d:   #1D4ED8;
    --blue-l:   rgba(59,130,246,0.08);
    --surface:  rgba(255,255,255,0.72);
    --surface2: rgba(255,255,255,0.50);
    --border:   rgba(59,130,246,0.13);
    --border2:  rgba(0,0,0,0.07);
    --t1:       #0F172A;
    --t2:       #475569;
    --t3:       #94A3B8;
    --danger:   #EF4444;
    --sh:       0 2px 16px rgba(59,130,246,0.07);
    --r-sm:     8px;
    --r-md:     12px;
    --r-lg:     16px;
}
@media (prefers-color-scheme: dark) {
    :root {
        --surface:  rgba(15,23,42,0.78);
        --surface2: rgba(15,23,42,0.55);
        --border:   rgba(59,130,246,0.18);
        --border2:  rgba(255,255,255,0.06);
        --t1:       #F1F5F9;
        --t2:       #94A3B8;
        --t3:       #475569;
        --sh:       0 2px 16px rgba(0,0,0,0.40);
    }
}

/* ── Glass card ── */
.glass-card {
    background: var(--surface);
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);
    border: 0.5px solid var(--border);
    border-radius: var(--r-lg);
    overflow: hidden;
    box-shadow: var(--sh);
}

/* ── Card header ── */
.card-header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 20px;
    border-bottom: 0.5px solid var(--border2);
}
.header-icon {
    width: 34px; height: 34px;
    border-radius: var(--r-sm);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.header-icon svg { width: 16px; height: 16px; }
.card-header h3 {
    font-size: 14px;
    font-weight: 500;
    color: var(--t1);
}

/* ── Form field ── */
.field-label {
    display: block;
    font-size: 12px;
    font-weight: 500;
    color: var(--t2);
    margin-bottom: 6px;
    letter-spacing: .02em;
}
.field-label .req { color: var(--danger); margin-left: 2px; }
.field-label .opt { color: var(--t3); font-weight: 400; margin-left: 3px; }

.f-input, .f-select, .f-textarea {
    width: 100%;
    padding: 9px 12px;
    background: var(--surface2);
    border: 0.5px solid var(--border);
    border-radius: var(--r-sm);
    font-size: 13px;
    color: var(--t1);
    outline: none;
    transition: border-color .15s, box-shadow .15s;
    font-family: inherit;
}
.f-input::placeholder,
.f-textarea::placeholder { color: var(--t3); }
.f-input:focus,
.f-select:focus,
.f-textarea:focus {
    border-color: var(--blue);
    box-shadow: 0 0 0 3px rgba(59,130,246,0.10);
}
.f-input.err, .f-select.err, .f-textarea.err {
    border-color: var(--danger);
}
.f-input.err:focus, .f-select.err:focus, .f-textarea.err:focus {
    box-shadow: 0 0 0 3px rgba(239,68,68,0.10);
}
.f-select { cursor: pointer; }
.f-textarea { resize: none; line-height: 1.6; }

.field-error {
    font-size: 11px;
    color: var(--danger);
    margin-top: 4px;
    display: flex;
    align-items: center;
    gap: 4px;
}
.field-error svg { width: 11px; height: 11px; flex-shrink: 0; }

/* ── Foto upload ── */
.foto-wrap {
    width: 96px; height: 96px;
    border-radius: var(--r-md);
    border: 1.5px dashed var(--border);
    overflow: hidden;
    display: flex; align-items: center; justify-content: center;
    background: var(--surface2);
    cursor: pointer;
    transition: border-color .15s;
    flex-shrink: 0;
    position: relative;
}
.foto-wrap:hover { border-color: var(--blue); }
.foto-wrap img { width: 100%; height: 100%; object-fit: cover; }
.foto-wrap svg { width: 28px; height: 28px; color: var(--t3); }
.foto-overlay {
    position: absolute; inset: 0;
    background: rgba(0,0,0,0.4);
    display: flex; align-items: center; justify-content: center;
    opacity: 0; transition: opacity .15s;
}
.foto-wrap:hover .foto-overlay { opacity: 1; }
.foto-overlay svg { width: 20px; height: 20px; color: #fff; }
.foto-label {
    display: block;
    text-align: center;
    font-size: 11px;
    font-weight: 500;
    color: var(--blue);
    margin-top: 6px;
    cursor: pointer;
}
.foto-label:hover { text-decoration: underline; }

/* ── Tab orang tua ── */
.tab-nav {
    display: flex;
    border-bottom: 0.5px solid var(--border2);
    padding: 0 20px;
    gap: 4px;
}
.tab-btn {
    padding: 11px 14px;
    font-size: 13px;
    font-weight: 400;
    color: var(--t3);
    background: transparent;
    border: none;
    border-bottom: 2px solid transparent;
    cursor: pointer;
    transition: all .15s;
    margin-bottom: -0.5px;
}
.tab-btn:hover { color: var(--t2); }
.tab-btn.active {
    color: var(--blue);
    font-weight: 500;
    border-bottom-color: var(--blue);
}
.tab-panel { display: none; padding: 20px; }
.tab-panel.active { display: block; }

/* ── Grid helpers ── */
.g2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.g3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 14px; }
.g1 { display: grid; grid-template-columns: 1fr; gap: 14px; }
@media (max-width: 640px) { .g2, .g3 { grid-template-columns: 1fr; } }
.col-span-2 { grid-column: span 2; }
@media (max-width: 640px) { .col-span-2 { grid-column: span 1; } }

/* ── Action buttons ── */
.btn-submit {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 10px 22px;
    background: var(--blue);
    color: #fff;
    font-size: 13px; font-weight: 500;
    border-radius: var(--r-sm);
    border: none; cursor: pointer;
    transition: background .15s;
}
.btn-submit:hover { background: var(--blue-d); }
.btn-submit svg { width: 15px; height: 15px; }
.btn-cancel {
    display: inline-flex; align-items: center;
    padding: 10px 18px;
    background: transparent;
    border: 0.5px solid var(--border);
    color: var(--t2);
    font-size: 13px;
    border-radius: var(--r-sm);
    text-decoration: none;
    transition: background .15s;
}
.btn-cancel:hover { background: var(--blue-l); }

/* ── Breadcrumb ── */
.breadcrumb {
    display: flex; align-items: center; gap: 6px;
    font-size: 13px; color: var(--t3);
}
.breadcrumb a { color: var(--t2); text-decoration: none; transition: color .15s; }
.breadcrumb a:hover { color: var(--blue); }
.breadcrumb svg { width: 13px; height: 13px; flex-shrink: 0; }
.breadcrumb span { color: var(--t1); font-weight: 500; }
</style>
@endpush

@section('content')
<div style="max-width:760px;margin:0 auto" class="space-y-4">

    {{-- Breadcrumb --}}
    <div class="breadcrumb">
        <a href="{{ route('siswa.index') }}">Data Siswa</a>
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span>Tambah Siswa</span>
    </div>

    <form method="POST" action="{{ route('siswa.store') }}" enctype="multipart/form-data" x-data="formSiswa()">
        @csrf

        {{-- ── IDENTITAS SISWA ── --}}
        <div class="glass-card">
            <div class="card-header">
                <div class="header-icon" style="background:rgba(59,130,246,0.10)">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--blue)">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <h3>Identitas Siswa</h3>
            </div>

            <div style="padding:20px;display:flex;flex-direction:column;gap:18px">

                {{-- Foto + field baris pertama --}}
                <div style="display:flex;gap:20px;align-items:flex-start;flex-wrap:wrap">

                    {{-- Foto upload --}}
                    <div style="flex-shrink:0">
                        <label>
                            <div class="foto-wrap">
                                <template x-if="!fotoPreview">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </template>
                                <template x-if="fotoPreview">
                                    <img :src="fotoPreview" alt="Preview foto">
                                </template>
                                <div class="foto-overlay">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <span class="foto-label">Pilih foto</span>
                            <input type="file" name="foto" accept="image/*" class="hidden"
                                   @change="previewFoto($event)">
                        </label>
                        @error('foto')
                        <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- NIS + Nama --}}
                    <div style="flex:1;min-width:220px" class="g2">
                        <div>
                            <label class="field-label">NIS <span class="opt">(opsional)</span></label>
                            <input type="text" name="nis" value="{{ old('nis') }}"
                                   placeholder="Nomor Induk Siswa"
                                   class="f-input @error('nis') err @enderror">
                            @error('nis')
                            <p class="field-error">
                                <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>
                        <div>
                            <label class="field-label">Nama Lengkap <span class="req">*</span></label>
                            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}"
                                   placeholder="Nama lengkap siswa" required
                                   class="f-input @error('nama_lengkap') err @enderror">
                            @error('nama_lengkap')
                            <p class="field-error">
                                <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Baris 2: JK, Agama, Tempat Lahir, Tanggal Lahir --}}
                <div class="g2">
                    <div>
                        <label class="field-label">Jenis Kelamin <span class="req">*</span></label>
                        <select name="jenis_kelamin" required
                                class="f-select @error('jenis_kelamin') err @enderror">
                            <option value="">Pilih...</option>
                            <option value="L" {{ old('jenis_kelamin') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin') === 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                        <p class="field-error">
                            <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>
                    <div>
                        <label class="field-label">Agama</label>
                        <select name="agama" class="f-select">
                            <option value="">Pilih...</option>
                            @foreach(['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'] as $agama)
                            <option value="{{ $agama }}" {{ old('agama') === $agama ? 'selected' : '' }}>{{ $agama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="field-label">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}"
                               placeholder="Kota kelahiran" class="f-input">
                    </div>
                    <div>
                        <label class="field-label">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                               class="f-input">
                    </div>
                </div>

                {{-- Alamat --}}
                <div>
                    <label class="field-label">Alamat</label>
                    <textarea name="alamat" rows="2" placeholder="Alamat lengkap siswa"
                              class="f-textarea">{{ old('alamat') }}</textarea>
                </div>
            </div>
        </div>

        {{-- ── DATA AKADEMIK ── --}}
        <div class="glass-card" style="margin-top:14px">
            <div class="card-header">
                <div class="header-icon" style="background:rgba(16,185,129,0.10)">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#10B981">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <h3>Data Akademik</h3>
            </div>
            <div style="padding:20px" class="g3">
                <div>
                    <label class="field-label">Kelompok <span class="req">*</span></label>
                    <select name="kelompok" required
                            class="f-select @error('kelompok') err @enderror">
                        <option value="">Pilih...</option>
                        <option value="KB" {{ old('kelompok', 'KB') === 'KB' ? 'selected' : '' }}>KB</option>
                    </select>
                    @error('kelompok')
                    <p class="field-error">
                        <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>
                <div>
                    <label class="field-label">Tanggal Masuk</label>
                    <input type="date" name="tanggal_masuk" value="{{ old('tanggal_masuk') }}"
                           class="f-input">
                </div>
                <div>
                    <label class="field-label">Tahun Ajaran</label>
                    <input type="text" name="tahun_ajaran"
                           value="{{ old('tahun_ajaran', date('Y') . '/' . (date('Y')+1)) }}"
                           placeholder="2025/2026" class="f-input">
                </div>
            </div>
        </div>

        {{-- ── DATA ORANG TUA / WALI ── --}}
        <div class="glass-card" style="margin-top:14px" id="parentCard">
            <div class="card-header">
                <div class="header-icon" style="background:rgba(245,158,11,0.10)">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#F59E0B">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h3>Data Orang Tua / Wali</h3>
            </div>

            {{-- Tab nav --}}
            <div class="tab-nav">
                <button type="button" class="tab-btn active" data-tab="ayah" onclick="switchTab('ayah')">Ayah</button>
                <button type="button" class="tab-btn" data-tab="ibu" onclick="switchTab('ibu')">Ibu</button>
                <button type="button" class="tab-btn" data-tab="wali" onclick="switchTab('wali')">Wali</button>
            </div>

            {{-- Tab: Ayah --}}
            <div class="tab-panel active" id="tab-ayah">
                <div class="g2">
                    <div>
                        <label class="field-label">Nama Ayah</label>
                        <input type="text" name="nama_ayah" value="{{ old('nama_ayah') }}"
                               placeholder="Nama lengkap ayah" class="f-input">
                    </div>
                    <div>
                        <label class="field-label">No. HP Ayah</label>
                        <input type="text" name="no_hp_ayah" value="{{ old('no_hp_ayah') }}"
                               placeholder="08xx-xxxx-xxxx" class="f-input">
                    </div>
                    <div class="col-span-2">
                        <label class="field-label">Pekerjaan Ayah</label>
                        <input type="text" name="pekerjaan_ayah" value="{{ old('pekerjaan_ayah') }}"
                               placeholder="Pekerjaan ayah" class="f-input">
                    </div>
                </div>
            </div>

            {{-- Tab: Ibu --}}
            <div class="tab-panel" id="tab-ibu">
                <div class="g2">
                    <div>
                        <label class="field-label">Nama Ibu</label>
                        <input type="text" name="nama_ibu" value="{{ old('nama_ibu') }}"
                               placeholder="Nama lengkap ibu" class="f-input">
                    </div>
                    <div>
                        <label class="field-label">No. HP Ibu</label>
                        <input type="text" name="no_hp_ibu" value="{{ old('no_hp_ibu') }}"
                               placeholder="08xx-xxxx-xxxx" class="f-input">
                    </div>
                    <div class="col-span-2">
                        <label class="field-label">Pekerjaan Ibu</label>
                        <input type="text" name="pekerjaan_ibu" value="{{ old('pekerjaan_ibu') }}"
                               placeholder="Pekerjaan ibu" class="f-input">
                    </div>
                </div>
            </div>

            {{-- Tab: Wali --}}
            <div class="tab-panel" id="tab-wali">
                <div class="g2">
                    <div>
                        <label class="field-label">Nama Wali</label>
                        <input type="text" name="nama_wali" value="{{ old('nama_wali') }}"
                               placeholder="Isi jika wali bukan ayah/ibu" class="f-input">
                    </div>
                    <div>
                        <label class="field-label">No. HP Wali</label>
                        <input type="text" name="no_hp_wali" value="{{ old('no_hp_wali') }}"
                               placeholder="08xx-xxxx-xxxx" class="f-input">
                    </div>
                    <div>
                        <label class="field-label">Hubungan dengan Siswa</label>
                        <input type="text" name="hubungan_wali" value="{{ old('hubungan_wali') }}"
                               placeholder="Kakek, Nenek, Paman, dll" class="f-input">
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Tombol Aksi ── --}}
        <div style="display:flex;align-items:center;justify-content:flex-end;gap:10px;margin-top:14px;padding-bottom:8px">
            <a href="{{ route('siswa.index') }}" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-submit">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M5 13l4 4L19 7"/>
                </svg>
                Simpan Siswa
            </button>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
/* ── AlpineJS: foto preview ── */
function formSiswa() {
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

/* ── Tab orang tua (vanilla JS, no Alpine dep) ── */
function switchTab(name) {
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.toggle('active', btn.dataset.tab === name);
    });
    document.querySelectorAll('.tab-panel').forEach(panel => {
        panel.classList.toggle('active', panel.id === 'tab-' + name);
    });
}

/* ── Restore tab jika ada old() error di salah satu tab ── */
document.addEventListener('DOMContentLoaded', function () {
    @if(old('nama_ibu') || old('no_hp_ibu') || old('pekerjaan_ibu'))
        switchTab('ibu');
    @elseif(old('nama_wali') || old('no_hp_wali') || old('hubungan_wali'))
        switchTab('wali');
    @endif
});
</script>
@endpush