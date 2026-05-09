<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Stok;
use App\Models\TransaksiStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StokMasukController extends Controller
{
    public function index()
    {
        // Ambil transaksi tipe masuk + relasi via stok→barang→satuan
        $historis = TransaksiStok::with(['stok.barang.satuan'])
            ->where('jenis', 'masuk')
            ->orderBy('created_at', 'desc')
            ->get();

        $summary = [
            'total_transaksi' => $historis->count(),
            'total_pembelian' => $historis->sum('total_cash'),
            'total_bonus'     => $historis->where('is_bonus', true)->count(),
        ];

        return view('stok-masuk.index', compact('historis', 'summary'));
    }

    public function create()
    {
        $barangs = Barang::with(['satuan', 'stok'])->orderBy('nama')->get();
        return view('stok-masuk.create', compact('barangs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_barang'  => 'required|exists:barangs,id_barang',
            'jumlah'     => 'required|integer|min:1',
            'harga_beli' => 'nullable|integer|min:0',
            'harga_jual' => 'nullable|integer|min:0',
            'is_bonus'   => 'nullable|boolean',
            'deskripsi'  => 'nullable|string|max:500',
        ]);

        $isBonus = (bool) ($validated['is_bonus'] ?? false);
        $barang  = Barang::findOrFail($validated['id_barang']);

        DB::transaction(function () use ($validated, $isBonus, $barang) {
            // 1. Pastikan ada record stok untuk barang ini
            $stok = Stok::firstOrCreate(
                ['id_barang' => $barang->id_barang],
                ['jumlah' => 0]
            );

            $stokAwal  = (int) ($stok->jumlah ?? 0);
            $stokAkhir = $stokAwal + (int) $validated['jumlah'];

            $hargaBeli = $isBonus ? 0 : (int) ($validated['harga_beli'] ?? 0);
            $hargaJual = (int) ($validated['harga_jual'] ?? $barang->harga_jual ?? 0);
            $totalCash = $hargaBeli * (int) $validated['jumlah'];

            // 2. Catat transaksi
            TransaksiStok::create([
                'stok_id'    => $stok->id_stok,
                'jenis'      => 'masuk',
                'jumlah'     => $validated['jumlah'],
                'harga_beli' => $hargaBeli,
                'harga_jual' => $hargaJual,
                'stok_awal'  => $stokAwal,
                'stok_akhir' => $stokAkhir,
                'total_cash' => $totalCash,
                'profit'     => 0,
                'is_bonus'   => $isBonus,
                'deskripsi'  => $validated['deskripsi'] ?? null,
            ]);

            // 3. Update stok
            $stok->update(['jumlah' => $stokAkhir]);
        });

        return redirect()
            ->route('stok-masuk.index')
            ->with('success', "Stok masuk untuk {$barang->nama} berhasil dicatat.");
    }
}
