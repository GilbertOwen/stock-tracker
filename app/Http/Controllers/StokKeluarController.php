<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Stok;
use App\Models\TransaksiStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StokKeluarController extends Controller
{
    public function index()
    {
        $historis = TransaksiStok::with(['stok.barang.satuan'])
            ->where('jenis', 'keluar')
            ->orderBy('created_at', 'desc')
            ->get();

        $summary = [
            'total_transaksi' => $historis->count(),
            'total_penjualan' => $historis->sum('total_cash'),
            'total_profit'    => $historis->sum('profit'),
        ];

        return view('stok-keluar.index', compact('historis', 'summary'));
    }

    public function create()
    {
        $barangs = Barang::with(['satuan', 'stok'])->orderBy('nama')->get();
        return view('stok-keluar.create', compact('barangs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_barang'      => 'required|exists:barangs,id_barang',
            'jumlah'         => 'required|integer|min:1',
            'harga_jual'     => 'required|integer|min:0',
            'force_negative' => 'nullable|boolean',
            'deskripsi'      => 'nullable|string|max:500',
        ]);

        $barang = Barang::findOrFail($validated['id_barang']);
        $stok   = Stok::firstOrCreate(
            ['id_barang' => $barang->id_barang],
            ['jumlah' => 0]
        );

        $stokAwal  = (int) ($stok->jumlah ?? 0);
        $stokAkhir = $stokAwal - (int) $validated['jumlah'];

        // Jika stok akan negatif dan belum dikonfirmasi → tampilkan warning
        if ($stokAkhir < 0 && empty($validated['force_negative'])) {
            return back()
                ->withErrors([
                    'stok_warning' => "Stok akan menjadi negatif ($stokAkhir). Centang konfirmasi untuk tetap lanjutkan.",
                ])
                ->withInput();
        }

        $hargaBeliBarang = (int) ($barang->harga_beli ?? 0);
        $jumlah          = (int) $validated['jumlah'];
        $hargaJual       = (int) $validated['harga_jual'];
        $profit          = ($hargaJual - $hargaBeliBarang) * $jumlah;
        $totalCash       = $hargaJual * $jumlah;

        DB::transaction(function () use (
            $stok, $jumlah, $hargaBeliBarang, $hargaJual, $stokAwal, $stokAkhir, $totalCash, $profit, $validated
        ) {
            TransaksiStok::create([
                'stok_id'    => $stok->id_stok,
                'jenis'      => 'keluar',
                'jumlah'     => $jumlah,
                'harga_beli' => $hargaBeliBarang,
                'harga_jual' => $hargaJual,
                'stok_awal'  => $stokAwal,
                'stok_akhir' => $stokAkhir,
                'total_cash' => $totalCash,
                'profit'     => $profit,
                'is_bonus'   => false,
                'deskripsi'  => $validated['deskripsi'] ?? null,
            ]);

            $stok->update(['jumlah' => $stokAkhir]);
        });

        return redirect()
            ->route('stok-keluar.index')
            ->with('success', "Stok keluar untuk {$barang->nama} berhasil dicatat.");
    }
}
