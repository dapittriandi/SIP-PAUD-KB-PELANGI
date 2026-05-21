<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class SiswaController extends Controller
{
    // ────────────────────────────────────────────────────────────────
    // CRUD
    // ────────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $query = Siswa::query();

        if ($request->filled('status')) {
            $query->where('aktif', $request->status === 'aktif');
        } else {
            $query->where('aktif', true);
        }

        if ($request->filled('kelompok')) {
            $query->where('kelompok', $request->kelompok);
        }

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('nama_lengkap', 'like', "%{$cari}%")
                  ->orWhere('nis', 'like', "%{$cari}%");
            });
        }

        $siswa = $query->orderBy('nama_lengkap')->paginate(15)->withQueryString();

        $totalAktif    = Siswa::where('aktif', true)->count();
        $totalNonAktif = Siswa::where('aktif', false)->count();

        return view('siswa.index', compact('siswa', 'totalAktif', 'totalNonAktif'));
    }

    public function create()
    {
        return view('siswa.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules(), $this->messages());

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('foto-siswa', 'public');
        }

        $validated['aktif'] = true;
        Siswa::create($validated);

        return redirect()->route('siswa.index')
            ->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function show(Siswa $siswa)
    {
        $siswa->load('pembayaranSpp');
        return view('siswa.show', compact('siswa'));
    }

    public function edit(Siswa $siswa)
    {
        return view('siswa.edit', compact('siswa'));
    }

    /**
     * FIX FOTO:
     *   - Hapus foto lama dari storage sebelum simpan yang baru.
     *   - Jika tidak ada file baru, kolom foto TIDAK ditimpa (unset dari validated).
     */
    public function update(Request $request, Siswa $siswa)
    {
        $validated = $request->validate($this->rules($siswa->id), $this->messages());

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada dan file-nya masih eksis di storage
            if ($siswa->foto && Storage::disk('public')->exists($siswa->foto)) {
                Storage::disk('public')->delete($siswa->foto);
            }
            // Simpan foto baru → hasilnya path relatif, mis: "foto-siswa/abc.jpg"
            $validated['foto'] = $request->file('foto')->store('foto-siswa', 'public');
        } else {
            // Tidak ada file baru → jangan timpa kolom foto di DB
            unset($validated['foto']);
        }

        $siswa->update($validated);

        return redirect()->route('siswa.show', $siswa)
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    /**
     * Nonaktifkan cepat dari tabel (tanpa modal).
     * Untuk keluarkan resmi dengan tanggal & keterangan → gunakan method keluarkan().
     */
    public function destroy(Siswa $siswa)
    {
        $siswa->update([
            'aktif'             => false,
            'tanggal_keluar'    => now()->toDateString(),
            'keterangan_keluar' => 'Dinonaktifkan oleh admin',
        ]);

        return redirect()->route('siswa.index')
            ->with('success', 'Siswa berhasil dinonaktifkan.');
    }

    /**
     * Keluarkan siswa secara resmi (lulus / pindah / keluar).
     * Mengisi tanggal_keluar dan keterangan_keluar dari form modal.
     *
     * Route: POST /siswa/{siswa}/keluarkan  →  name: siswa.keluarkan
     */
    public function keluarkan(Request $request, Siswa $siswa)
    {
        $request->validate([
            'tanggal_keluar'    => 'required|date',
            'keterangan_keluar' => 'required|string|max:255',
        ], [
            'tanggal_keluar.required'    => 'Tanggal keluar wajib diisi.',
            'tanggal_keluar.date'        => 'Format tanggal tidak valid.',
            'keterangan_keluar.required' => 'Keterangan keluar wajib diisi.',
        ]);

        $siswa->update([
            'aktif'             => false,
            'tanggal_keluar'    => $request->tanggal_keluar,
            'keterangan_keluar' => $request->keterangan_keluar,
        ]);

        // Redirect ke halaman show atau index tergantung hidden field redirect_to
        if ($request->input('redirect_to') === 'index') {
            return redirect()->route('siswa.index')
                ->with('success', "Siswa {$siswa->nama_lengkap} berhasil dikeluarkan.");
        }

        return redirect()->route('siswa.show', $siswa)
            ->with('success', "Siswa {$siswa->nama_lengkap} berhasil dikeluarkan.");
    }

    /**
     * Aktifkan kembali siswa yang sudah nonaktif/dikeluarkan.
     *
     * Route: POST /siswa/{siswa}/aktifkan  →  name: siswa.aktifkan
     */
    public function aktifkan(Siswa $siswa)
    {
        $siswa->update([
            'aktif'             => true,
            'tanggal_keluar'    => null,
            'keterangan_keluar' => null,
        ]);

        return back()->with('success', "Siswa {$siswa->nama_lengkap} berhasil diaktifkan kembali.");
    }

    // ────────────────────────────────────────────────────────────────
    // IMPORT EXCEL
    // ────────────────────────────────────────────────────────────────

    public function importForm()
    {
        return view('siswa.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls', 'max:5120'],
        ], [
            'file.required' => 'File Excel wajib dipilih.',
            'file.mimes'    => 'Format file harus .xlsx atau .xls.',
            'file.max'      => 'Ukuran file maksimal 5 MB.',
        ]);

        try {
            $spreadsheet = IOFactory::load($request->file('file')->getRealPath());
        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'File tidak dapat dibaca. Pastikan format file benar.']);
        }

        $sheet = $spreadsheet->getActiveSheet();
        $rows  = $sheet->toArray(null, true, true, true);

        // Header ada di baris ke-3 (index 3 karena array mulai dari 1)
        if (count($rows) < 3) {
            return back()->withErrors(['file' => 'File kosong atau tidak sesuai template.']);
        }

        // Bersihkan suffix ' *' lalu buat peta kolom
        $header = array_map(function ($val) {
            return trim(strtolower(str_replace(' *', '', $val ?? '')));
        }, $rows[3]);
        $colMap = array_flip($header);

        $allowedCols = [
            'nis', 'nama_lengkap', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir',
            'agama', 'alamat', 'kelompok', 'tanggal_masuk', 'tahun_ajaran',
            'nama_ayah', 'no_hp_ayah', 'pekerjaan_ayah',
            'nama_ibu',  'no_hp_ibu',  'pekerjaan_ibu',
            'nama_wali', 'hubungan_wali', 'no_hp_wali',
        ];

        $imported = 0;
        $errors   = [];
        $maxRows  = 500;

        // Data mulai dari baris ke-4 (offset 3 dari array berbasis-1)
        foreach (array_slice($rows, 3, $maxRows, true) as $rowNum => $row) {

            // Skip baris contoh
            $firstColKey = array_key_first($colMap);
            $firstVal    = ($firstColKey !== null && isset($colMap[$firstColKey]))
                ? trim($row[$colMap[$firstColKey]] ?? '')
                : '';
            if (str_starts_with(strtolower($firstVal), '[contoh]')) {
                continue;
            }

            // Skip baris kosong
            $allEmpty = true;
            foreach ($colMap as $col => $cellKey) {
                if (!empty(trim($row[$cellKey] ?? ''))) { $allEmpty = false; break; }
            }
            if ($allEmpty) continue;

            // Petakan ke data array
            $data = [];
            foreach ($allowedCols as $col) {
                if (isset($colMap[$col])) {
                    $cellKey    = $colMap[$col];
                    $data[$col] = trim($row[$cellKey] ?? '');
                    if ($data[$col] === '') $data[$col] = null;
                }
            }

            // Normalisasi tanggal
            foreach (['tanggal_lahir', 'tanggal_masuk'] as $dateCol) {
                if (!empty($data[$dateCol])) {
                    $parsed = $this->parseDate((string) $data[$dateCol]);
                    if ($parsed === null) {
                        $errors[] = "Baris {$rowNum}: Format tanggal '{$data[$dateCol]}' pada kolom {$dateCol} tidak valid. Gunakan YYYY-MM-DD.";
                        continue 2;
                    }
                    $data[$dateCol] = $parsed;
                }
            }

            // Normalisasi jenis_kelamin
            if (!empty($data['jenis_kelamin'])) {
                $data['jenis_kelamin'] = strtoupper($data['jenis_kelamin']);
            }

            // Validasi per baris (null-safe untuk nis)
            $nisValue = $data['nis'] ?? '-';

            $validator = Validator::make($data, [
                'nis'           => 'nullable|string|max:20|unique:siswa,nis',
                'nama_lengkap'  => 'required|string|max:100',
                'jenis_kelamin' => 'required|in:L,P',
                'kelompok'      => 'required|in:KB',
                'tanggal_lahir' => 'nullable|date',
                'tanggal_masuk' => 'nullable|date',
            ], [
                'nama_lengkap.required'  => "Baris {$rowNum}: Nama lengkap wajib diisi.",
                'jenis_kelamin.required' => "Baris {$rowNum}: Jenis kelamin wajib diisi (L/P).",
                'jenis_kelamin.in'       => "Baris {$rowNum}: Jenis kelamin harus L atau P.",
                'kelompok.required'      => "Baris {$rowNum}: Kelompok wajib diisi.",
                'kelompok.in'            => "Baris {$rowNum}: Kelompok tidak valid (hanya KB).",
                'nis.unique'             => "Baris {$rowNum}: NIS '{$nisValue}' sudah terdaftar.",
            ]);

            if ($validator->fails()) {
                foreach ($validator->errors()->all() as $msg) {
                    $errors[] = $msg;
                }
                continue;
            }

            $data['aktif'] = true;
            Siswa::create($data);
            $imported++;
        }

        if ($imported === 0 && !empty($errors)) {
            return back()
                ->with('import_errors', $errors)
                ->withErrors(['file' => 'Tidak ada data yang berhasil diimport. Periksa error di bawah.']);
        }

        $successMsg = "{$imported} data siswa berhasil diimport.";
        if (!empty($errors)) {
            $successMsg .= ' ' . count($errors) . ' baris dilewati karena error.';
        }

        return redirect()->route('siswa.import.form')
            ->with('success', $successMsg)
            ->with('import_errors', !empty($errors) ? $errors : null);
    }

    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Siswa');

        $columns = [
            ['nama_lengkap',   '[CONTOH] Ahmad Fauzi',     'Nama lengkap siswa (WAJIB)',                   true],
            ['jenis_kelamin',  'L',                         'L = Laki-laki, P = Perempuan (WAJIB)',        true],
            ['kelompok',       'KB',                         'Hanya: KB (WAJIB)',                           true],
            ['nis',            '1234567890',                 'Nomor Induk Siswa dari Dapodik (opsional)',   false],
            ['tempat_lahir',   'Jambi',                      'Kota/kabupaten tempat lahir',                 false],
            ['tanggal_lahir',  '2020-05-14',                 'Format: YYYY-MM-DD',                         false],
            ['agama',          'Islam',                      'Islam/Kristen/Katolik/Hindu/Buddha/Konghucu', false],
            ['alamat',         'Jl. Mawar No.5 Jambi',       'Alamat lengkap tempat tinggal',               false],
            ['tanggal_masuk',  '2024-07-15',                 'Tanggal masuk sekolah. Format: YYYY-MM-DD',  false],
            ['tahun_ajaran',   '2025/2026',                  'Format: YYYY/YYYY',                           false],
            ['nama_ayah',      'Budi Santoso',               'Nama lengkap ayah kandung',                   false],
            ['no_hp_ayah',     '081234567890',               'No HP ayah (untuk notif WA)',                 false],
            ['pekerjaan_ayah', 'Wiraswasta',                 'Pekerjaan/profesi ayah',                      false],
            ['nama_ibu',       'Sari Dewi',                  'Nama lengkap ibu kandung',                    false],
            ['no_hp_ibu',      '082345678901',               'No HP ibu (untuk notif WA)',                  false],
            ['pekerjaan_ibu',  'Ibu Rumah Tangga',           'Pekerjaan/profesi ibu',                       false],
            ['nama_wali',      '',                           'Isi jika wali bukan ayah/ibu',                false],
            ['hubungan_wali',  '',                           'Mis: Kakek, Paman, Bibi',                     false],
            ['no_hp_wali',     '',                           'No HP wali untuk notifikasi WA',              false],
        ];

        $lastCol = chr(64 + count($columns));
        $sheet->mergeCells("A1:{$lastCol}1");
        $sheet->setCellValue('A1', 'TEMPLATE IMPORT DATA SISWA — PAUD KB Pelangi');
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1D4ED8']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(28);

        $sheet->mergeCells("A2:{$lastCol}2");
        $sheet->setCellValue('A2', 'Petunjuk: Jangan ubah nama kolom di baris ke-3. Hapus baris contoh (baris 4) sebelum diisi. Kolom bertanda (*) wajib diisi.');
        $sheet->getStyle('A2')->applyFromArray([
            'font'      => ['italic' => true, 'size' => 9, 'color' => ['rgb' => '6B7280']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F8FAFC']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'wrapText' => true],
        ]);
        $sheet->getRowDimension(2)->setRowHeight(22);

        foreach ($columns as $i => [$colName, $example, $note, $required]) {
            $cell  = chr(65 + $i) . '3';
            $label = $required ? $colName . ' *' : $colName;
            $sheet->setCellValue($cell, $label);
            $sheet->getComment($cell)->getText()->createTextRun($note);
            $sheet->getComment($cell)->setWidth('200pt');
            $bgColor = $required ? 'DC2626' : '2563EB';
            $sheet->getStyle($cell)->applyFromArray([
                'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgColor]],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'FFFFFF']]],
            ]);
        }
        $sheet->getRowDimension(3)->setRowHeight(22);

        foreach ($columns as $i => [$colName, $example, $note, $required]) {
            $cell = chr(65 + $i) . '4';
            $sheet->setCellValue($cell, $example);
            $sheet->getStyle($cell)->applyFromArray([
                'font'      => ['italic' => true, 'color' => ['rgb' => '6B7280'], 'size' => 10],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F1F5F9']],
                'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E2E8F0']]],
            ]);
        }
        $sheet->getRowDimension(4)->setRowHeight(18);

        for ($r = 5; $r <= 54; $r++) {
            for ($c = 0; $c < count($columns); $c++) {
                $sheet->getStyle(chr(65 + $c) . $r)->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E2E8F0']]],
                ]);
            }
        }

        $colWidths = [20, 12, 10, 16, 16, 14, 12, 30, 14, 12, 20, 16, 20, 20, 16, 20, 20, 16, 16];
        foreach ($colWidths as $i => $w) {
            $sheet->getColumnDimension(chr(65 + $i))->setWidth($w);
        }

        $sheet->freezePane('A4');

        $filename = 'template_import_siswa.xlsx';
        $writer   = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control'       => 'max-age=0',
        ]);
    }

    // ────────────────────────────────────────────────────────────────
    // HELPER
    // ────────────────────────────────────────────────────────────────

    private function parseDate(?string $value): ?string
    {
        if (empty($value)) return null;

        if (is_numeric($value)) {
            try {
                $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float) $value);
                return $date->format('Y-m-d');
            } catch (\Exception $e) {
                return null;
            }
        }

        $formats = ['Y-m-d', 'd/m/Y', 'd-m-Y', 'm/d/Y', 'd/m/y', 'Y/m/d'];
        foreach ($formats as $fmt) {
            $date = \DateTime::createFromFormat($fmt, $value);
            if ($date && $date->format($fmt) === $value) {
                return $date->format('Y-m-d');
            }
        }

        $ts = strtotime($value);
        if ($ts !== false) {
            return date('Y-m-d', $ts);
        }

        return null;
    }

    private function rules(?int $ignoreId = null): array
    {
        return [
            'nis'              => 'nullable|string|max:20|unique:siswa,nis' . ($ignoreId ? ",{$ignoreId}" : ''),
            'nama_lengkap'     => 'required|string|max:100',
            'jenis_kelamin'    => 'required|in:L,P',
            'tempat_lahir'     => 'nullable|string|max:100',
            'tanggal_lahir'    => 'nullable|date',
            'agama'            => 'nullable|string|max:20',
            'alamat'           => 'nullable|string|max:500',
            'foto'             => 'nullable|image|max:2048',
            'kelompok'         => 'required|string|max:10',
            'tanggal_masuk'    => 'nullable|date',
            'tahun_ajaran'     => 'nullable|string|max:10',
            'nama_ayah'        => 'nullable|string|max:100',
            'no_hp_ayah'       => 'nullable|string|max:20',
            'pekerjaan_ayah'   => 'nullable|string|max:100',
            'nama_ibu'         => 'nullable|string|max:100',
            'no_hp_ibu'        => 'nullable|string|max:20',
            'pekerjaan_ibu'    => 'nullable|string|max:100',
            'nama_wali'        => 'nullable|string|max:100',
            'hubungan_wali'    => 'nullable|string|max:50',
            'no_hp_wali'       => 'nullable|string|max:20',
        ];
    }

    private function messages(): array
    {
        return [
            'nama_lengkap.required'  => 'Nama lengkap wajib diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'kelompok.required'      => 'Kelompok wajib dipilih.',
            'foto.image'             => 'File foto harus berupa gambar.',
            'foto.max'               => 'Ukuran foto maksimal 2MB.',
            'nis.unique'             => 'NIS sudah digunakan siswa lain.',
        ];
    }
}