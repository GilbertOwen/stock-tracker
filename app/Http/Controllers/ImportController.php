<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Satuan;
use App\Models\Stok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportController extends Controller
{
    public function index()
    {
        return view('import.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:5120',
        ]);

        $path = $request->file('file')->store('imports');
        $fullPath = Storage::path($path);

        $spreadsheet = IOFactory::load($fullPath);
        try {

            // Cari sheet yang punya header kolom yang dikenali
            $worksheet = null;
            foreach ($spreadsheet->getAllSheets() as $sheet) {
                foreach ($sheet->getRowIterator(1, 10) as $row) {
                    $cells = [];
                    foreach ($row->getCellIterator() as $cell) {
                        $cells[] = strtolower(trim((string) $cell->getValue()));
                    }
                    // Deteksi baris header: ada "nama barang" atau "satuan"
                    if (in_array('nama barang', $cells) || in_array('satuan', $cells)) {
                        $worksheet   = $sheet;
                        $headerRow   = $row->getRowIndex();
                        break 2;
                    }
                }
            }

            if (!$worksheet) {
                return back()->with('error', 'Format Excel tidak dikenali. Pastikan ada kolom: Nama Barang, Satuan, Stok, Harga.');
            }

            // Baca header untuk mapping kolom
            $headers = [];
            foreach ($worksheet->getRowIterator($headerRow, $headerRow) as $row) {
                foreach ($row->getCellIterator() as $cell) {
                    $headers[$cell->getColumn()] = strtolower(trim((string) $cell->getValue()));
                }
            }

            // Balik mapping: nama_kolom => huruf_kolom
            $colMap = array_flip($headers); // ['nama barang' => 'B', 'satuan' => 'C', ...]

            $colNama   = $colMap['nama barang'] ?? null;
            $colSatuan = $colMap['satuan']       ?? null;
            $colStok   = $colMap['stok']         ?? null;
            $colHarga  = $colMap['harga']         ?? null;

            if (!$colNama || !$colSatuan) {
                return back()->with('error', 'Kolom "Nama Barang" atau "Satuan" tidak ditemukan di Excel.');
            }

            // Proses data mulai baris setelah header
            $dataStartRow = $headerRow + 1;
            $highestRow   = $worksheet->getHighestDataRow();

            $stats = ['satuan' => 0, 'barang' => 0, 'stok' => 0, 'skip' => 0];

            for ($rowIdx = $dataStartRow; $rowIdx <= $highestRow; $rowIdx++) {

                $nama   = trim((string) ($worksheet->getCell($colNama . $rowIdx)->getValue() ?? ''));
                $satuanNama = trim((string) ($colSatuan ? $worksheet->getCell($colSatuan . $rowIdx)->getValue() : ''));
                $jumlah = $colStok  ? $worksheet->getCell($colStok  . $rowIdx)->getValue() : null;
                $harga  = $colHarga ? $worksheet->getCell($colHarga  . $rowIdx)->getValue() : null;

                // Skip baris kosong
                if ($nama === '' || $nama === null) {
                    $stats['skip']++;
                    continue;
                }

                // 1. Cari atau buat Satuan
                $satuan = null;
                if ($satuanNama !== '') {
                    $satuan = Satuan::firstOrCreate(['nama' => $satuanNama]);
                    if ($satuan->wasRecentlyCreated) {
                        $stats['satuan']++;
                    }
                }

                // 2. Cari atau buat Barang
                // Gunakan updateOrCreate agar import bisa dijalankan ulang tanpa duplikat
                $barang = Barang::updateOrCreate(
                    ['nama' => $nama],
                    [
                        'id_satuan' => $satuan?->id_satuan,
                        'harga'     => is_numeric($harga) ? (int) ($harga * 1000) : null,
                        // Harga di Excel tampaknya dalam ribuan (contoh: 80 = Rp 80.000)
                        // Hapus * 1000 jika harga di Excel sudah dalam rupiah penuh
                    ]
                );

                if ($barang->wasRecentlyCreated) {
                    $stats['barang']++;
                }

                // 3. Cari atau update Stok
                if (is_numeric($jumlah)) {
                    Stok::updateOrCreate(
                        ['id_barang' => $barang->id_barang],
                        ['jumlah'    => (int) $jumlah]
                    );
                    $stats['stok']++;
                }
            }

            // Hapus file temp
            Storage::delete($path);

            $msg = "Import selesai: {$stats['barang']} barang baru, {$stats['satuan']} satuan baru, {$stats['stok']} stok diperbarui.";
            if ($stats['skip'] > 0) {
                $msg .= " ({$stats['skip']} baris dilewati karena kosong)";
            }

            return redirect()->route('barangs.index')->with('success', $msg);
        } catch (\Throwable $e) {
            Storage::delete($path);
            return back()->with('error', 'Gagal membaca file: ' . $e->getMessage());
        }
    }
}
