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
            $headerRow = null;
            foreach ($spreadsheet->getAllSheets() as $sheet) {
                foreach ($sheet->getRowIterator(1, 10) as $row) {
                    $cells = [];
                    foreach ($row->getCellIterator() as $cell) {
                        $cells[] = strtolower(trim((string) $cell->getValue()));
                    }
                    if (in_array('nama barang', $cells) || in_array('satuan', $cells)) {
                        $worksheet = $sheet;
                        $headerRow = $row->getRowIndex();
                        break 2;
                    }
                }
            }

            if (!$worksheet) {
                Storage::delete($path);
                return back()->with('error', 'Format Excel tidak dikenali. Pastikan ada kolom: Nama Barang, Satuan, Stok, Harga Beli, Harga Jual.');
            }

            // Baca header untuk mapping kolom
            $headers = [];
            foreach ($worksheet->getRowIterator($headerRow, $headerRow) as $row) {
                foreach ($row->getCellIterator() as $cell) {
                    $headers[$cell->getColumn()] = strtolower(trim((string) $cell->getValue()));
                }
            }

            $colMap = array_flip($headers);

            $colNama   = $colMap['nama barang'] ?? null;
            $colSatuan = $colMap['satuan']      ?? null;
            $colStok   = $colMap['stok']        ?? null;

            // Mapping harga beli & harga jual (multiple alias)
            $colHargaBeli = $colMap['harga beli']
                ?? $colMap['harga pokok']
                ?? null;

            $colHargaJual = $colMap['harga jual']
                ?? $colMap['harga']
                ?? null;

            if (!$colNama || !$colSatuan) {
                Storage::delete($path);
                return back()->with('error', 'Kolom "Nama Barang" atau "Satuan" tidak ditemukan di Excel.');
            }

            $dataStartRow = $headerRow + 1;
            $highestRow   = $worksheet->getHighestDataRow();

            $stats = ['satuan' => 0, 'barang' => 0, 'stok' => 0, 'skip' => 0];

            for ($rowIdx = $dataStartRow; $rowIdx <= $highestRow; $rowIdx++) {
                $nama       = trim((string) ($worksheet->getCell($colNama . $rowIdx)->getValue() ?? ''));
                $satuanNama = trim((string) ($colSatuan ? $worksheet->getCell($colSatuan . $rowIdx)->getValue() : ''));
                $jumlah     = $colStok      ? $worksheet->getCell($colStok      . $rowIdx)->getValue() : null;
                $hargaBeli  = $colHargaBeli ? $worksheet->getCell($colHargaBeli . $rowIdx)->getValue() : null;
                $hargaJual  = $colHargaJual ? $worksheet->getCell($colHargaJual . $rowIdx)->getValue() : null;

                if ($nama === '' || $nama === null) {
                    $stats['skip']++;
                    continue;
                }

                // 1. Satuan
                $satuan = null;
                if ($satuanNama !== '') {
                    $satuan = Satuan::firstOrCreate(['nama' => $satuanNama]);
                    if ($satuan->wasRecentlyCreated) {
                        $stats['satuan']++;
                    }
                }

                // Harga Excel dalam ribuan → DB dalam rupiah penuh.
                // Jika nilai bukan angka (misal teks "untuk bonus") → 0
                $hargaBeliFinal = is_numeric($hargaBeli) ? (int) ($hargaBeli * 1000) : 0;
                $hargaJualFinal = is_numeric($hargaJual) ? (int) ($hargaJual * 1000) : null;

                // 2. Barang
                $barang = Barang::updateOrCreate(
                    ['nama' => $nama],
                    [
                        'id_satuan'  => $satuan?->id_satuan,
                        'harga_beli' => $hargaBeliFinal,
                        'harga_jual' => $hargaJualFinal,
                    ]
                );

                if ($barang->wasRecentlyCreated) {
                    $stats['barang']++;
                }

                // 3. Stok
                if (is_numeric($jumlah)) {
                    Stok::updateOrCreate(
                        ['id_barang' => $barang->id_barang],
                        ['jumlah'    => (int) $jumlah]
                    );
                    $stats['stok']++;
                }
            }

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
