@extends('layouts.app')

@section('title', 'Edit Data Guru — ' . $guru->nama_lengkap)

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">

    {{-- Header --}}
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('guru.show', $guru) }}"
           class="flex items-center justify-center w-10 h-10 rounded-xl bg-white border border-gray-200 hover:bg-gray-50 transition-colors shadow-sm">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Data Guru</h1>
            <p class="text-sm text-gray-500 mt-0.5">{{ $guru->nama_lengkap }} &mdash; {{ $guru->username }}</p>
        </div>
    </div>

    {{-- Alert Error --}}
    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
            <p class="text-sm font-semibold text-red-700 mb-1">Terdapat kesalahan input:</p>
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li class="text-sm text-red-600">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('guru.update', $guru) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- ── IDENTITAS DASAR ── --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Identitas Dasar</h2>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Nama --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $guru->nama_lengkap) }}"
                           class="w-full px-4 py-2.5 rounded-xl border @error('nama_lengkap') border-red-400 bg-red-50 @else border-gray-300 @enderror focus:outline-none focus:ring-2 focus:ring-pelangi-400 text-sm"
                           placeholder="Nama lengkap guru" required>
                    @error('nama_lengkap') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Username --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Username <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="username" value="{{ old('username', $guru->username) }}"
                           class="w-full px-4 py-2.5 rounded-xl border @error('username') border-red-400 bg-red-50 @else border-gray-300 @enderror focus:outline-none focus:ring-2 focus:ring-pelangi-400 text-sm"
                           placeholder="Username untuk login" required>
                    @error('username') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Jenis Kelamin --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Jenis Kelamin</label>
                    <select name="jenis_kelamin"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-pelangi-400 text-sm bg-white">
                        <option value="">— Pilih —</option>
                        <option value="L" @selected(old('jenis_kelamin', $guru->jenis_kelamin) === 'L')>Laki-laki</option>
                        <option value="P" @selected(old('jenis_kelamin', $guru->jenis_kelamin) === 'P')>Perempuan</option>
                    </select>
                </div>

                {{-- Tempat Lahir --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $guru->tempat_lahir) }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-pelangi-400 text-sm"
                           placeholder="Kota tempat lahir">
                </div>

                {{-- Tanggal Lahir --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir"
                           value="{{ old('tanggal_lahir', $guru->tanggal_lahir ? \Carbon\Carbon::parse($guru->tanggal_lahir)->format('Y-m-d') : '') }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-pelangi-400 text-sm">
                </div>


                {{-- No HP --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nomor HP</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp', $guru->no_hp) }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-pelangi-400 text-sm"
                           placeholder="08xx-xxxx-xxxx">
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email', $guru->email) }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-pelangi-400 text-sm"
                           placeholder="email@contoh.com">
                </div>

                {{-- Alamat --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Alamat</label>
                    <textarea name="alamat" rows="3"
                              class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-pelangi-400 text-sm resize-none"
                              placeholder="Alamat lengkap">{{ old('alamat', $guru->alamat) }}</textarea>
                </div>

                {{-- Foto --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Foto</label>
                    @if ($guru->foto)
                        <div class="mb-3 flex items-center gap-3">
                            <img src="{{ Storage::url($guru->foto) }}" alt="Foto {{ $guru->nama_lengkap }}"
                                 class="w-16 h-16 rounded-xl object-cover border border-gray-200">
                            <p class="text-xs text-gray-500">Foto saat ini. Upload baru untuk mengganti.</p>
                        </div>
                    @endif
                    <input type="file" name="foto" accept="image/*"
                           class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-pelangi-50 file:text-pelangi-700 hover:file:bg-pelangi-100">
                    <p class="mt-1 text-xs text-gray-400">Format: JPG, PNG. Maks 2MB.</p>
                    @error('foto') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

            </div>
        </div>

        {{-- ── DATA KEPEGAWAIAN ── --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Data Kepegawaian</h2>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- NIP --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">NIP</label>
                    <input type="text" name="nip" value="{{ old('nip', $guru->nip) }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-pelangi-400 text-sm"
                           placeholder="Nomor Induk Pegawai">
                </div>

                {{-- NUPTK --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">NUPTK</label>
                    <input type="text" name="nuptk" value="{{ old('nuptk', $guru->nuptk) }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-pelangi-400 text-sm"
                           placeholder="Nomor Unik PTK">
                </div>

                {{-- Status Kepegawaian --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Status Kepegawaian</label>
                    <select name="status_kepegawaian"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-pelangi-400 text-sm bg-white">
                        <option value="">— Pilih —</option>
                        <option value="pns"     @selected(old('status_kepegawaian', $guru->status_kepegawaian) === 'pns')>PNS</option>
                        <option value="pppk"    @selected(old('status_kepegawaian', $guru->status_kepegawaian) === 'pppk')>PPPK</option>
                        <option value="honorer" @selected(old('status_kepegawaian', $guru->status_kepegawaian) === 'honorer')>Honorer</option>
                        <option value="gtty"    @selected(old('status_kepegawaian', $guru->status_kepegawaian) === 'gtty')>GTT/GTY</option>
                    </select>
                </div>

                {{-- Jabatan --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Jabatan</label>
                    <input type="text" name="jabatan" value="{{ old('jabatan', $guru->jabatan) }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-pelangi-400 text-sm"
                           placeholder="Contoh: Guru Kelas, Wali Kelas KB">
                </div>

                {{-- Pendidikan Terakhir --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Pendidikan Terakhir</label>
                    <select name="pendidikan_terakhir"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-pelangi-400 text-sm bg-white">
                        <option value="">— Pilih —</option>
                        @foreach(['SMA/SMK','D1','D2','D3','S1','S2','S3'] as $pend)
                            <option value="{{ $pend }}" @selected(old('pendidikan_terakhir', $guru->pendidikan_terakhir) === $pend)>{{ $pend }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Jurusan --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Jurusan / Program Studi</label>
                    <input type="text" name="jurusan" value="{{ old('jurusan', $guru->jurusan) }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-pelangi-400 text-sm"
                           placeholder="Contoh: Pendidikan Anak Usia Dini">
                </div>

                {{-- Tanggal Bergabung --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Bergabung</label>
                    <input type="date" name="tanggal_bergabung"
                           value="{{ old('tanggal_bergabung', $guru->tanggal_bergabung ? \Carbon\Carbon::parse($guru->tanggal_bergabung)->format('Y-m-d') : '') }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-pelangi-400 text-sm">
                </div>

            </div>
        </div>

        {{-- ── GANTI PIN ── --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Ganti PIN Login</h2>
            </div>
            <div class="p-6">
                <p class="text-sm text-gray-500 mb-4">Kosongkan jika tidak ingin mengganti PIN.</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">PIN Baru</label>
                        <input type="password" name="pin" maxlength="4"
                               class="w-full px-4 py-2.5 rounded-xl border @error('pin') border-red-400 bg-red-50 @else border-gray-300 @enderror focus:outline-none focus:ring-2 focus:ring-pelangi-400 text-sm tracking-widest"
                               placeholder="4 digit angka">
                        @error('pin') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi PIN Baru</label>
                        <input type="password" name="pin_confirmation" maxlength="4"
                               class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-pelangi-400 text-sm tracking-widest"
                               placeholder="Ulangi PIN baru">
                    </div>
                </div>
            </div>
        </div>

        {{-- ── TOMBOL AKSI ── --}}
        <div class="flex items-center justify-between pt-2">
            <a href="{{ route('guru.show', $guru) }}"
               class="px-5 py-2.5 rounded-xl border border-gray-300 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
                Batal
            </a>
            <button type="submit"
                    class="px-6 py-2.5 rounded-xl bg-pelangi-500 hover:bg-pelangi-600 text-white text-sm font-semibold transition-colors shadow-sm">
                Simpan Perubahan
            </button>
        </div>

    </form>
</div>
@endsection