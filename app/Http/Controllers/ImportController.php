<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Satuan;
use App\Models\Stok;
use App\Models\TransaksiStok;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportController extends Controller
{
    /** Mapping nama bulan Indonesia → nomor */
    private const BULAN_MAP = [
        'januari'   => 1,
        'februari'  => 2,
        'maret'     => 3,
        'april'     => 4,
        'mei'       => 5,
        'juni'      => 6,
        'juli'      => 7,
        'agustus'   => 8,
        'september' => 9,
        'oktober'   => 10,
        'november'  => 11,
        'desember'  => 12,
    ];

    public function index()
    {
        return view('import.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:5120',
        ]);

        $path     = $request->file('file')->store('imports');
        $fullPath = Storage::path($path);

        try {
            $spreadsheet = IOFactory::load($fullPath);

            // Cari sheet yang punya header dikenali
            $worksheet = null;
            $headerRow = null;
            foreach ($spreadsheet->getAllSheets() as $sheet) {
                foreach ($sheet->getRowIterator(1, 10) as $row) {
                    $cells = [];
                    foreach ($row->getCellIterator() as $cell) {
                        $cells[] = strtolower(trim((string) $cell->getValue()));
                    }
                    // Header valid kalau ada salah satu kombinasi
                    if (in_array('jenis barang', $cells)
                        || in_array('nama barang', $cells)
                        || in_array('stok akhir', $cells)) {
                        $worksheet = $sheet;
                        $headerRow = $row->getRowIndex();
                        break 2;
                    }
                }
            }

            if (!$worksheet) {
                Storage::delete($path);
                return back()->with('error', 'Format Excel tidak dikenali. Pastikan ada kolom: Jenis Barang / Nama Barang dan Stok Akhir.');
            }

            // Mapping header → huruf kolom
            $headers = [];
            foreach ($worksheet->getRowIterator($headerRow, $headerRow) as $row) {
                foreach ($row->getCellIterator() as $cell) {
                    $headers[$cell->getColumn()] = strtolower(trim((string) $cell->getValue()));
                }
            }
            $colMap = array_flip($headers);

            $col = [
                'bulan'       => $colMap['bulan']                                           ?? null,
                'tanggal'     => $colMap['tanggal']                                         ?? null,
                'nama'        => $colMap['jenis barang']  ?? $colMap['nama barang']         ?? $colMap['nama'] ?? null,
                'satuan'      => $colMap['satuan']                                          ?? null,
                'stok_awal'   => $colMap['stok awal']                                       ?? null,
                'stok_masuk'  => $colMap['stok masuk']                                      ?? null,
                'stok_keluar' => $colMap['stok keluar']                                     ?? null,
                'stok_akhir'  => $colMap['stok akhir']    ?? $colMap['stok']                ?? null,
                'harga_beli'  => $colMap['harga beli']    ?? $colMap['harga pokok']         ?? null,
                'harga_jual'  => $colMap['harga jual']    ?? $colMap['harga']               ?? null,
            ];

            if (!$col['nama']) {
                Storage::delete($path);
                return back()->with('error', 'Kolom "Jenis Barang" / "Nama Barang" tidak ditemukan di Excel.');
            }

            // Iterasi data
            $dataStartRow = $headerRow + 1;
            $highestRow   = $worksheet->getHighestDataRow();

            // Untuk forward-fill bulan & tanggal
            $lastBulan   = null;
            $lastTanggal = null;

            // Untuk konsolidasi per barang (snapshot terakhir)
            // ['nama' => ['nama'=>..., 'satuan'=>..., 'harga_beli'=>..., 'harga_jual'=>..., 'stok_akhir'=>..., 'is_bonus'=>...]]
            $latestPerBarang = [];

            // Untuk menampung kandidat transaksi (akan diproses setelah barang/stok dibuat)
            // [['nama'=>..., 'jenis'=>..., 'jumlah'=>..., 'harga_beli'=>..., 'harga_jual'=>...,
            //   'stok_awal'=>..., 'stok_akhir'=>..., 'is_bonus'=>..., 'tanggal'=>Carbon|null]]
            $transactionsPending = [];

            $stats = [
                'barang_baru'  => 0,
                'barang_total' => 0,
                'satuan_baru'  => 0,
                'transaksi'    => 0,
                'skip'         => 0,
            ];

            $tahun = (int) now()->year;

            for ($rowIdx = $dataStartRow; $rowIdx <= $highestRow; $rowIdx++) {
                $get = fn($key) => $col[$key]
                    ? $worksheet->getCell($col[$key] . $rowIdx)->getValue()
                    : null;

                $namaRaw = trim((string) ($get('nama') ?? ''));

                // Skip baris kosong
                if ($namaRaw === '') {
                    $stats['skip']++;
                    continue;
                }

                // Skip baris "Total" / "Grand Total"
                if (preg_match('/^(grand\s*)?total\s*$/i', $namaRaw)) {
                    $stats['skip']++;
                    continue;
                }

                // Forward-fill bulan & tanggal
                $bulanCell   = $get('bulan');
                $tanggalCell = $get('tanggal');
                if ($bulanCell !== null && trim((string) $bulanCell) !== '') {
                    $lastBulan = strtolower(trim((string) $bulanCell));
                }
                if ($tanggalCell !== null && $tanggalCell !== '') {
                    $lastTanggal = is_numeric($tanggalCell) ? (int) $tanggalCell : $lastTanggal;
                }

                // Parse harga beli — bisa teks "untuk bonus..."
                $hargaBeliRaw = $get('harga_beli');
                $isBonus      = false;
                if ($hargaBeliRaw !== null && !is_numeric($hargaBeliRaw) && trim((string) $hargaBeliRaw) !== '') {
                    $isBonus   = true;
                    $hargaBeli = 0;
                } else {
                    $hargaBeli = is_numeric($hargaBeliRaw) ? (int) $hargaBeliRaw : 0;
                }

                $hargaJualRaw = $get('harga_jual');
                $hargaJual    = is_numeric($hargaJualRaw) ? (int) $hargaJualRaw : null;

                $stokAwal   = is_numeric($get('stok_awal'))   ? (int) $get('stok_awal')   : null;
                $stokMasuk  = is_numeric($get('stok_masuk'))  ? (int) $get('stok_masuk')  : 0;
                $stokKeluar = is_numeric($get('stok_keluar')) ? (int) $get('stok_keluar') : 0;
                $stokAkhir  = is_numeric($get('stok_akhir'))  ? (int) $get('stok_akhir')  : null;

                $satuanNama = $col['satuan']
                    ? trim((string) ($get('satuan') ?? ''))
                    : '';

                // Simpan/update snapshot terakhir per barang
                $key = mb_strtolower($namaRaw);
                $latestPerBarang[$key] = [
                    'nama'       => $namaRaw,
                    'satuan'     => $satuanNama !== '' ? $satuanNama : ($latestPerBarang[$key]['satuan'] ?? ''),
                    'harga_beli' => $hargaBeli,
                    'harga_jual' => $hargaJual ?? ($latestPerBarang[$key]['harga_jual'] ?? null),
                    'stok_akhir' => $stokAkhir ?? ($latestPerBarang[$key]['stok_akhir'] ?? 0),
                    'is_bonus'   => $isBonus,
                ];

                // Tampung transaksi jika ada gerakan stok
                if ($stokMasuk > 0 || $stokKeluar > 0) {
                    // Konstruksi tanggal dari bulan + tanggal (jika ada)
                    $transaksiDate = null;
                    if ($lastBulan && isset(self::BULAN_MAP[$lastBulan]) && $lastTanggal) {
                        try {
                            $transaksiDate = Carbon::create($tahun, self::BULAN_MAP[$lastBulan], $lastTanggal, 0, 0, 0);
                        } catch (\Throwable $e) {
                            $transaksiDate = null;
                        }
                    }

                    if ($stokMasuk > 0) {
                        $transactionsPending[] = [
                            'nama'       => $namaRaw,
                            'jenis'      => 'masuk',
                            'jumlah'     => $stokMasuk,
                            'harga_beli' => $hargaBeli,
                            'harga_jual' => $hargaJual ?? 0,
                            'stok_awal'  => $stokAwal ?? 0,
                            'stok_akhir' => ($stokAwal ?? 0) + $stokMasuk,
                            'is_bonus'   => $isBonus,
                            'tanggal'    => $transaksiDate,
                        ];
                    }
                    if ($stokKeluar > 0) {
                        $stokAwalKeluar = $stokAwal !== null
                            ? $stokAwal + $stokMasuk
                            : 0;
                        $transactionsPending[] = [
                            'nama'       => $namaRaw,
                            'jenis'      => 'keluar',
                            'jumlah'     => $stokKeluar,
                            'harga_beli' => $hargaBeli,
                            'harga_jual' => $hargaJual ?? 0,
                            'stok_awal'  => $stokAwalKeluar,
                            'stok_akhir' => $stokAwalKeluar - $stokKeluar,
                            'is_bonus'   => false,
                            'tanggal'    => $transaksiDate,
                        ];
                    }
                }
            }

            // Eksekusi semua perubahan dalam transaction
            DB::transaction(function () use (&$stats, $latestPerBarang, $transactionsPending) {
                // Default satuan kalau Excel tidak punya kolom Satuan
                $defaultSatuan = Satuan::firstOrCreate(['nama' => 'pcs']);
                if ($defaultSatuan->wasRecentlyCreated) {
                    $stats['satuan_baru']++;
                }

                // 1. Upsert semua barang + stok
                foreach ($latestPerBarang as $data) {
                    $satuan = $defaultSatuan;
                    if (!empty($data['satuan'])) {
                        $satuan = Satuan::firstOrCreate(['nama' => $data['satuan']]);
                        if ($satuan->wasRecentlyCreated) {
                            $stats['satuan_baru']++;
                        }
                    }

                    $barang = Barang::updateOrCreate(
                        ['nama' => $data['nama']],
                        [
                            'id_satuan'  => $satuan->id_satuan,
                            'harga_beli' => $data['harga_beli'],
                            'harga_jual' => $data['harga_jual'],
                        ]
                    );
                    if ($barang->wasRecentlyCreated) {
                        $stats['barang_baru']++;
                    }
                    $stats['barang_total']++;

                    Stok::updateOrCreate(
                        ['id_barang' => $barang->id_barang],
                        ['jumlah'    => $data['stok_akhir']]
                    );
                }

                // 2. Insert transaksi (urut sesuai parse Excel)
                foreach ($transactionsPending as $t) {
                    $barang = Barang::where('nama', $t['nama'])->first();
                    if (!$barang) continue;

                    $stok = Stok::firstOrCreate(
                        ['id_barang' => $barang->id_barang],
                        ['jumlah' => 0]
                    );

                    $totalCash = $t['jenis'] === 'masuk'
                        ? $t['harga_beli'] * $t['jumlah']
                        : $t['harga_jual'] * $t['jumlah'];

                    $profit = $t['jenis'] === 'keluar'
                        ? ($t['harga_jual'] - $t['harga_beli']) * $t['jumlah']
                        : 0;

                    $attrs = [
                        'stok_id'    => $stok->id_stok,
                        'jenis'      => $t['jenis'],
                        'jumlah'     => $t['jumlah'],
                        'harga_beli' => $t['harga_beli'],
                        'harga_jual' => $t['harga_jual'],
                        'stok_awal'  => $t['stok_awal'],
                        'stok_akhir' => $t['stok_akhir'],
                        'total_cash' => $totalCash,
                        'profit'     => $profit,
                        'is_bonus'   => $t['is_bonus'],
                        'deskripsi'  => 'Import Excel',
                    ];

                    // Override timestamp jika ada tanggal valid dari Excel
                    if ($t['tanggal'] instanceof Carbon) {
                        $attrs['created_at'] = $t['tanggal'];
                        $attrs['updated_at'] = $t['tanggal'];
                    }

                    TransaksiStok::create($attrs);
                    $stats['transaksi']++;
                }
            });

            Storage::delete($path);

            $msg = "Import selesai: {$stats['barang_total']} barang diproses ({$stats['barang_baru']} baru), "
                 . "{$stats['transaksi']} transaksi dicatat, {$stats['satuan_baru']} satuan baru.";
            if ($stats['skip'] > 0) {
                $msg .= " ({$stats['skip']} baris dilewati)";
            }

            return redirect()->route('barangs.index')->with('success', $msg);
        } catch (\Throwable $e) {
            Storage::delete($path);
            return back()->with('error', 'Gagal membaca file: ' . $e->getMessage());
        }
    }
}
