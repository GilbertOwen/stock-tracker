<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\TransaksiStok;
use Illuminate\Http\Request;

class HistoriStokController extends Controller
{
    public function index(Request $request)
    {
        $query = TransaksiStok::with(['stok.barang.satuan'])
            ->orderBy('created_at', 'desc');

        // Filter tanggal
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('created_at', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_sampai);
        }

        // Filter tipe (jenis)
        if ($request->filled('tipe') && in_array($request->tipe, ['masuk', 'keluar'])) {
            $query->where('jenis', $request->tipe);
        }

        // Filter barang → join via stok
        if ($request->filled('id_barang')) {
            $query->whereHas('stok', function ($q) use ($request) {
                $q->where('id_barang', $request->id_barang);
            });
        }

        $historis = $query->get();

        $summary = [
            'total_penjualan' => $historis->where('jenis', 'keluar')->sum('total_cash'),
            'total_pembelian' => $historis->where('jenis', 'masuk')->sum('total_cash'),
            'total_profit'    => $historis->where('jenis', 'keluar')->sum('profit'),
        ];

        $barangs = Barang::with('satuan')->orderBy('nama')->get();

        return view('histori-stok.index', compact('historis', 'summary', 'barangs'));
    }
}
