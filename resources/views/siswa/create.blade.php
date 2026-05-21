@extends('layouts.app')

@section('title', 'Tambah Siswa — PAUD KB Pelangi')
@section('page-title', 'Tambah Siswa')

@push('styles')
<style>
/* ══════════════════════════════════════════════════════════
   CREATE SISWA — impeccable pass
   Konsisten dengan edit_blade_impeccable.php.
   Semua token dari app.blade.php (OKLCH).
   Glass: HANYA pada foto-upload overlay.
   Perbedaan dari edit: badge "Baru", no @method('PUT'),
   old() tanpa fallback $siswa, action bar note berbeda.
══════════════════════════════════════════════════════════ */

/* ─── Breadcrumb ─────────────────────────────── */
.bc { display:flex; align-items:center; gap:5px; font-size:var(--fs-xs); color:var(--text-3); }
.bc a { color:var(--text-3); text-decoration:none; transition:color var(--dur-fast) ease; }
.bc a:hover { color:var(--accent); }
.bc svg { width:12px; height:12px; opacity:.4; flex-shrink:0; }
.bc-now { color:var(--text-2); font-weight:500; }

/* ─── Form shell ─────────────────────────────── */
.form-shell {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--r-xl);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}

/* ─── Sections ───────────────────────────────── */
.form-sec {
    padding: 22px 24px;
    border-bottom: 1px solid var(--border);
}
.form-sec:last-child { border-bottom: none; }
@media (max-width:600px) { .form-sec { padding:18px 16px; } }

.sec-label {
    display:flex; align-items:center; gap:9px;
    margin-bottom:18px;
}
.sec-label-ico {
    width:28px; height:28px; border-radius:var(--r-sm);
    display:flex; align-items:center; justify-content:center;
    flex-shrink:0;
    background:var(--accent-muted);
    border:1px solid var(--accent-ring);
}
.sec-label-ico svg { width:13px; height:13px; color:var(--accent); }
.sec-label-ico.green { background:var(--success-bg); border-color:var(--success-border); }
.sec-label-ico.green svg { color:var(--success); }
.sec-label-ico.amber {
    background:var(--warning-bg);
    border-color: color-mix(in oklch, var(--warning), transparent 55%);
}
.sec-label-ico.amber svg { color:var(--warning); }

.sec-label-title { font-size:var(--fs-sm); font-weight:600; color:var(--text-1); letter-spacing:-0.01em; }
.sec-label-sub { font-size:var(--fs-xs); color:var(--text-3); margin-top:1px; }

/* ─── "Baru" badge ───────────────────────────── */
.mode-badge {
    margin-left:auto;
    display:inline-flex; align-items:center; gap:4px;
    padding:2px 9px;
    background:var(--success-bg);
    border:1px solid var(--success-border);
    border-radius:9999px;
    font-size:var(--fs-2xs); font-weight:600;
    color:var(--success);
    font-family:'Geist Mono', monospace;
    letter-spacing:.04em;
    text-transform:uppercase;
}
.mode-badge svg { width:10px; height:10px; }

/* ─── Grid helpers ───────────────────────────── */
.g2 { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
.g3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:14px; }
@media (max-width:560px) { .g2,.g3 { grid-template-columns:1fr; } }
.span2 { grid-column:1/-1; }

/* ─── Field label ────────────────────────────── */
.fld-lbl {
    display:flex; align-items:baseline; gap:4px;
    font-size:var(--fs-xs); font-weight:500;
    color:var(--text-2); margin-bottom:5px;
    font-family:'Geist', sans-serif;
}
.fld-lbl .req { color:var(--danger); font-size:var(--fs-xs); }
.fld-lbl .opt { color:var(--text-3); font-weight:400; font-size:var(--fs-2xs); font-family:'Geist Mono',monospace; }

/* ─── Form controls ──────────────────────────── */
.fc {
    width:100%; padding:8px 11px;
    background:var(--bg-2);
    border:1px solid var(--border);
    border-radius:var(--r-sm);
    font-size:var(--fs-sm); font-family:'Geist',sans-serif;
    color:var(--text-1); outline:none;
    transition:border-color var(--dur-fast) ease, box-shadow var(--dur-fast) ease;
    appearance:none; -webkit-appearance:none;
}
.fc::placeholder { color:var(--text-3); }
.fc:focus {
    border-color:var(--accent-ring);
    box-shadow:0 0 0 3px color-mix(in oklch, var(--accent), transparent 84%);
}
.fc.err {
    border-color:var(--danger);
    background:color-mix(in oklch, var(--danger-bg), var(--bg-2) 50%);
}
.fc.err:focus { box-shadow:0 0 0 3px color-mix(in oklch, var(--danger), transparent 86%); }
select.fc {
    cursor:pointer;
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' viewBox='0 0 24 24'%3E%3Cpath stroke='%2394A3B8' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
    background-repeat:no-repeat; background-position:right 10px center;
    padding-right:30px;
}
textarea.fc { resize:none; line-height:1.6; }

.fld-err {
    display:flex; align-items:flex-start; gap:4px;
    font-size:var(--fs-xs); color:var(--danger);
    margin-top:4px; line-height:1.4;
}
.fld-err svg { width:12px; height:12px; flex-shrink:0; margin-top:1px; }

/* ─── Foto upload ────────────────────────────── */
.foto-zone { display:flex; align-items:flex-start; gap:16px; flex-wrap:wrap; }
.foto-btn {
    position:relative;
    width:88px; height:88px;
    border-radius:var(--r-lg); overflow:hidden;
    background:var(--bg-2);
    border:1px dashed var(--border);
    cursor:pointer; flex-shrink:0;
    transition:border-color var(--dur-fast) ease;
}
.foto-btn:hover { border-color:var(--accent-ring); }
.foto-btn:hover .foto-hover { opacity:1; }

.foto-placeholder {
    width:100%; height:100%;
    display:flex; align-items:center; justify-content:center;
}
.foto-placeholder svg { width:26px; height:26px; color:var(--text-3); }
.foto-img { width:100%; height:100%; object-fit:cover; }

/* glass hanya di sini: justified overlay di atas foto */
.foto-hover {
    position:absolute; inset:0;
    background:oklch(10% 0.01 265 / 50%);
    backdrop-filter:blur(2px);
    display:flex; align-items:center; justify-content:center;
    opacity:0; transition:opacity var(--dur-fast) ease;
}
.foto-hover svg { width:18px; height:18px; color:#fff; }

.foto-meta { flex:1; min-width:0; }
.foto-meta-name { font-size:var(--fs-sm); font-weight:500; color:var(--text-1); margin-bottom:3px; }
.foto-meta-hint { font-size:var(--fs-xs); color:var(--text-3); line-height:1.5; }
.foto-link {
    display:inline-block; margin-top:8px;
    font-size:var(--fs-xs); font-weight:500;
    color:var(--accent); cursor:pointer; text-decoration:none;
    transition:opacity var(--dur-fast) ease;
}
.foto-link:hover { opacity:.7; }

/* ─── Tab strip (orang tua) ──────────────────── */
.tab-strip {
    display:flex; gap:0;
    border-bottom:1px solid var(--border);
    margin:0 -24px 20px; padding:0 24px;
    overflow-x:auto;
}
@media (max-width:600px) { .tab-strip { margin:0 -16px 18px; padding:0 16px; } }
.tab-strip::-webkit-scrollbar { display:none; }

.tab-btn {
    padding:10px 14px;
    font-size:var(--fs-sm); font-weight:400;
    font-family:'Geist',sans-serif;
    color:var(--text-3);
    background:transparent; border:none;
    position:relative; cursor:pointer; white-space:nowrap;
    transition:color var(--dur-fast) ease;
}
.tab-btn::after {
    content:''; position:absolute; left:0; right:0; bottom:-1px;
    height:2px; border-radius:2px 2px 0 0;
    background:var(--accent);
    transform:scaleX(0);
    transition:transform var(--dur-mid) var(--ease-out);
}
.tab-btn:hover { color:var(--text-2); }
.tab-btn.on { color:var(--accent); font-weight:500; }
.tab-btn.on::after { transform:scaleX(1); }

.tab-dot {
    display:inline-block; width:5px; height:5px; border-radius:50%;
    background:var(--success); margin-left:5px; vertical-align:middle;
    margin-bottom:1px; opacity:0; transition:opacity var(--dur-fast) ease;
}
.tab-dot.show { opacity:1; }

.tab-panel { display:none; }
.tab-panel.on { display:block; }

/* ─── Phone input wrapper ────────────────────── */
.input-wrap { position:relative; }
.input-wrap .input-prefix {
    position:absolute; left:10px; top:50%;
    transform:translateY(-50%);
    width:14px; height:14px; color:var(--text-3); pointer-events:none;
}
.input-wrap .fc { padding-left:32px; }

/* ─── Action bar ─────────────────────────────── */
.action-bar {
    display:flex; align-items:center; justify-content:space-between;
    padding:16px 24px;
    background:var(--bg);
    border-top:1px solid var(--border);
}
@media (max-width:600px) { .action-bar { padding:14px 16px; flex-wrap:wrap; gap:10px; } }

.action-bar-hint { font-size:var(--fs-xs); color:var(--text-3); font-family:'Geist Mono',monospace; }
.action-bar-right { display:flex; align-items:center; gap:8px; }

.btn-primary {
    display:inline-flex; align-items:center; gap:6px;
    padding:9px 18px;
    background:var(--accent); color:var(--text-inv);
    font-size:var(--fs-sm); font-weight:500;
    font-family:'Geist',sans-serif;
    border-radius:var(--r-sm); border:none; cursor:pointer;
    transition:background var(--dur-fast) ease, transform var(--dur-micro) ease;
    box-shadow:0 1px 4px color-mix(in oklch, var(--accent), transparent 60%);
}
.btn-primary svg { width:14px; height:14px; }
.btn-primary:hover { background:var(--accent-h); }
.btn-primary:active { transform:scale(0.97); }

.btn-ghost {
    display:inline-flex; align-items:center;
    padding:9px 16px;
    background:transparent;
    border:1px solid var(--border); color:var(--text-2);
    font-size:var(--fs-sm); font-weight:500;
    font-family:'Geist',sans-serif;
    border-radius:var(--r-sm); text-decoration:none;
    transition:background var(--dur-fast) ease, border-color var(--dur-fast) ease;
}
.btn-ghost:hover { background:var(--bg-2); border-color:var(--border-2); color:var(--text-1); }

/* ─── Entrance animation ─────────────────────── */
@media (prefers-reduced-motion:no-preference) {
    .au { animation:fadeUp var(--dur-page) var(--ease-out) both; }
    .d0 { animation-delay:0ms; }
    .d1 { animation-delay:45ms; }
    @keyframes fadeUp {
        from { opacity:0; transform:translateY(7px); }
        to   { opacity:1; transform:translateY(0); }
    }
}
</style>
@endpush

@section('content')
<div style="max-width:720px;margin:0 auto">

    {{-- Breadcrumb --}}
    <div class="bc au d0" style="margin-bottom:16px">
        <a href="{{ route('siswa.index') }}">Data Siswa</a>
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="bc-now">Tambah Siswa</span>
    </div>

    <form method="POST" action="{{ route('siswa.store') }}"
          enctype="multipart/form-data"
          x-data="formSiswa()">
        @csrf

        <div class="form-shell au d1">

            {{-- ══ SEKSI 1: IDENTITAS ══ --}}
            <div class="form-sec">
                <div class="sec-label">
                    <div class="sec-label-ico">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="sec-label-title">Identitas Siswa</div>
                        <div class="sec-label-sub">Nama, foto, dan data pribadi</div>
                    </div>
                    <div class="mode-badge">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Baru
                    </div>
                </div>

                {{-- Foto --}}
                <div style="margin-bottom:20px">
                    <label class="fld-lbl">Foto <span class="opt">opsional</span></label>
                    <div class="foto-zone">
                        <label class="foto-btn" for="fotoInput">
                            <template x-if="!fotoPreview">
                                <div class="foto-placeholder">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            </template>
                            <template x-if="fotoPreview">
                                <img :src="fotoPreview" class="foto-img" alt="Preview foto">
                            </template>
                            <div class="foto-hover">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                        </label>
                        <input id="fotoInput" type="file" name="foto"
                               accept="image/*" class="hidden"
                               @change="previewFoto($event)">

                        <div class="foto-meta">
                            <div class="foto-meta-name" x-text="fotoPreview ? 'Foto dipilih' : 'Belum ada foto'"></div>
                            <div class="foto-meta-hint">
                                JPG, PNG, atau WebP. Maks 2 MB.<br>
                                Klik area foto atau tombol di bawah.
                            </div>
                            <label for="fotoInput" class="foto-link">Pilih file</label>
                            @error('foto')
                            <div class="fld-err" style="margin-top:6px">
                                <svg fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- NIS + Nama --}}
                <div class="g2" style="margin-bottom:14px">
                    <div>
                        <label class="fld-lbl" for="nis">NIS <span class="opt">opsional</span></label>
                        <input id="nis" type="text" name="nis"
                               value="{{ old('nis') }}"
                               placeholder="Nomor Induk Siswa"
                               class="fc @error('nis') err @enderror">
                        @error('nis')
                        <div class="fld-err">
                            <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div>
                        <label class="fld-lbl" for="nama_lengkap">Nama Lengkap <span class="req">*</span></label>
                        <input id="nama_lengkap" type="text" name="nama_lengkap"
                               value="{{ old('nama_lengkap') }}"
                               placeholder="Nama lengkap siswa"
                               required autocomplete="off"
                               class="fc @error('nama_lengkap') err @enderror">
                        @error('nama_lengkap')
                        <div class="fld-err">
                            <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>

                {{-- JK + Agama + Tempat + Tgl Lahir --}}
                <div class="g2" style="margin-bottom:14px">
                    <div>
                        <label class="fld-lbl" for="jenis_kelamin">Jenis Kelamin <span class="req">*</span></label>
                        <select id="jenis_kelamin" name="jenis_kelamin" required
                                class="fc @error('jenis_kelamin') err @enderror">
                            <option value="">Pilih...</option>
                            <option value="L" {{ old('jenis_kelamin') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin') === 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                        <div class="fld-err">
                            <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div>
                        <label class="fld-lbl" for="agama">Agama</label>
                        <select id="agama" name="agama" class="fc">
                            <option value="">Pilih...</option>
                            @foreach(['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'] as $agama)
                            <option value="{{ $agama }}" {{ old('agama') === $agama ? 'selected' : '' }}>{{ $agama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="fld-lbl" for="tempat_lahir">Tempat Lahir</label>
                        <input id="tempat_lahir" type="text" name="tempat_lahir"
                               value="{{ old('tempat_lahir') }}"
                               placeholder="Kota kelahiran" class="fc">
                    </div>
                    <div>
                        <label class="fld-lbl" for="tanggal_lahir">Tanggal Lahir</label>
                        <input id="tanggal_lahir" type="date" name="tanggal_lahir"
                               value="{{ old('tanggal_lahir') }}" class="fc">
                    </div>
                </div>

                {{-- Alamat --}}
                <div>
                    <label class="fld-lbl" for="alamat">Alamat</label>
                    <textarea id="alamat" name="alamat" rows="2"
                              placeholder="Alamat lengkap siswa"
                              class="fc">{{ old('alamat') }}</textarea>
                </div>
            </div>

            {{-- ══ SEKSI 2: AKADEMIK ══ --}}
            <div class="form-sec">
                <div class="sec-label">
                    <div class="sec-label-ico green">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div>
                        <div class="sec-label-title">Data Akademik</div>
                        <div class="sec-label-sub">Kelompok, tahun ajaran, dan tanggal masuk</div>
                    </div>
                </div>

                <div class="g3">
                    <div>
                        <label class="fld-lbl" for="kelompok">Kelompok <span class="req">*</span></label>
                        <select id="kelompok" name="kelompok" required
                                class="fc @error('kelompok') err @enderror">
                            <option value="">Pilih...</option>
                            <option value="KB" {{ old('kelompok', 'KB') === 'KB' ? 'selected' : '' }}>KB</option>
                        </select>
                        @error('kelompok')
                        <div class="fld-err">
                            <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div>
                        <label class="fld-lbl" for="tanggal_masuk">Tanggal Masuk</label>
                        <input id="tanggal_masuk" type="date" name="tanggal_masuk"
                               value="{{ old('tanggal_masuk', now()->format('Y-m-d')) }}"
                               class="fc">
                    </div>
                    <div>
                        <label class="fld-lbl" for="tahun_ajaran">Tahun Ajaran</label>
                        <input id="tahun_ajaran" type="text" name="tahun_ajaran"
                               value="{{ old('tahun_ajaran', date('Y') . '/' . (date('Y') + 1)) }}"
                               placeholder="2025/2026" class="fc">
                    </div>
                </div>
            </div>

            {{-- ══ SEKSI 3: ORANG TUA / WALI ══ --}}
            <div class="form-sec">
                <div class="sec-label">
                    <div class="sec-label-ico amber">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="sec-label-title">Orang Tua / Wali</div>
                        <div class="sec-label-sub">Nama, kontak, dan pekerjaan <span style="color:var(--text-3);font-size:var(--fs-2xs);font-family:'Geist Mono',monospace">(opsional)</span></div>
                    </div>
                </div>

                {{-- Tab strip --}}
                <div class="tab-strip" role="tablist">
                    <button type="button" class="tab-btn on" data-tab="ayah"
                            role="tab" aria-selected="true" aria-controls="tab-ayah">
                        Ayah
                        <span class="tab-dot @if(old('nama_ayah')) show @endif" id="dot-ayah"></span>
                    </button>
                    <button type="button" class="tab-btn" data-tab="ibu"
                            role="tab" aria-selected="false" aria-controls="tab-ibu">
                        Ibu
                        <span class="tab-dot @if(old('nama_ibu')) show @endif" id="dot-ibu"></span>
                    </button>
                    <button type="button" class="tab-btn" data-tab="wali"
                            role="tab" aria-selected="false" aria-controls="tab-wali">
                        Wali
                        <span class="tab-dot @if(old('nama_wali')) show @endif" id="dot-wali"></span>
                    </button>
                </div>

                {{-- Tab: Ayah --}}
                <div id="tab-ayah" class="tab-panel on" role="tabpanel">
                    <div class="g2">
                        <div>
                            <label class="fld-lbl" for="nama_ayah">Nama Ayah</label>
                            <input id="nama_ayah" type="text" name="nama_ayah"
                                   value="{{ old('nama_ayah') }}"
                                   placeholder="Nama lengkap ayah"
                                   class="fc" oninput="updateDot('ayah', this.value)">
                        </div>
                        <div>
                            <label class="fld-lbl" for="no_hp_ayah">No. HP Ayah</label>
                            <div class="input-wrap">
                                <svg class="input-prefix" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <input id="no_hp_ayah" type="tel" name="no_hp_ayah"
                                       value="{{ old('no_hp_ayah') }}"
                                       placeholder="08xx-xxxx-xxxx" class="fc">
                            </div>
                        </div>
                        <div class="span2">
                            <label class="fld-lbl" for="pekerjaan_ayah">Pekerjaan Ayah</label>
                            <input id="pekerjaan_ayah" type="text" name="pekerjaan_ayah"
                                   value="{{ old('pekerjaan_ayah') }}"
                                   placeholder="Pekerjaan ayah" class="fc">
                        </div>
                    </div>
                </div>

                {{-- Tab: Ibu --}}
                <div id="tab-ibu" class="tab-panel" role="tabpanel">
                    <div class="g2">
                        <div>
                            <label class="fld-lbl" for="nama_ibu">Nama Ibu</label>
                            <input id="nama_ibu" type="text" name="nama_ibu"
                                   value="{{ old('nama_ibu') }}"
                                   placeholder="Nama lengkap ibu"
                                   class="fc" oninput="updateDot('ibu', this.value)">
                        </div>
                        <div>
                            <label class="fld-lbl" for="no_hp_ibu">No. HP Ibu</label>
                            <div class="input-wrap">
                                <svg class="input-prefix" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <input id="no_hp_ibu" type="tel" name="no_hp_ibu"
                                       value="{{ old('no_hp_ibu') }}"
                                       placeholder="08xx-xxxx-xxxx" class="fc">
                            </div>
                        </div>
                        <div class="span2">
                            <label class="fld-lbl" for="pekerjaan_ibu">Pekerjaan Ibu</label>
                            <input id="pekerjaan_ibu" type="text" name="pekerjaan_ibu"
                                   value="{{ old('pekerjaan_ibu') }}"
                                   placeholder="Pekerjaan ibu" class="fc">
                        </div>
                    </div>
                </div>

                {{-- Tab: Wali --}}
                <div id="tab-wali" class="tab-panel" role="tabpanel">
                    <div class="g2">
                        <div>
                            <label class="fld-lbl" for="nama_wali">Nama Wali</label>
                            <input id="nama_wali" type="text" name="nama_wali"
                                   value="{{ old('nama_wali') }}"
                                   placeholder="Isi jika wali bukan ayah/ibu"
                                   class="fc" oninput="updateDot('wali', this.value)">
                        </div>
                        <div>
                            <label class="fld-lbl" for="no_hp_wali">No. HP Wali</label>
                            <div class="input-wrap">
                                <svg class="input-prefix" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <input id="no_hp_wali" type="tel" name="no_hp_wali"
                                       value="{{ old('no_hp_wali') }}"
                                       placeholder="08xx-xxxx-xxxx" class="fc">
                            </div>
                        </div>
                        <div>
                            <label class="fld-lbl" for="hubungan_wali">Hubungan dengan Siswa</label>
                            <input id="hubungan_wali" type="text" name="hubungan_wali"
                                   value="{{ old('hubungan_wali') }}"
                                   placeholder="Kakek, Nenek, Paman..." class="fc">
                        </div>
                    </div>
                </div>
            </div>

            {{-- ══ ACTION BAR ══ --}}
            <div class="action-bar">
                <span class="action-bar-hint">* wajib diisi</span>
                <div class="action-bar-right">
                    <a href="{{ route('siswa.index') }}" class="btn-ghost">Batal</a>
                    <button type="submit" class="btn-primary">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 4v16m8-8H4"/>
                        </svg>
                        Simpan Siswa
                    </button>
                </div>
            </div>

        </div>{{-- /form-shell --}}
    </form>

</div>
@endsection

@push('scripts')
<script>
/* ── Alpine: foto preview ── */
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
    };
}

/* ── Tab switch ── */
function switchTab(name) {
    document.querySelectorAll('.tab-btn').forEach(btn => {
        const active = btn.dataset.tab === name;
        btn.classList.toggle('on', active);
        btn.setAttribute('aria-selected', active ? 'true' : 'false');
    });
    document.querySelectorAll('.tab-panel').forEach(panel => {
        panel.classList.toggle('on', panel.id === 'tab-' + name);
    });
}

/* ── Dot indicator ── */
function updateDot(tabName, value) {
    const dot = document.getElementById('dot-' + tabName);
    if (dot) dot.classList.toggle('show', value.trim().length > 0);
}

/* ── Delegate click ── */
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => switchTab(btn.dataset.tab));
});

/* ── Restore tab aktif jika ada old() error ── */
document.addEventListener('DOMContentLoaded', function () {
    @if(old('nama_ibu') || old('no_hp_ibu') || old('pekerjaan_ibu'))
        switchTab('ibu');
    @elseif(old('nama_wali') || old('no_hp_wali') || old('hubungan_wali'))
        switchTab('wali');
    @endif
});
</script>
@endpush