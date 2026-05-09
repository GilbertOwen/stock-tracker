<?php

namespace App\Http\Controllers;

use App\Models\Barang;

class StokController extends Controller
{
    /**
     * Tampilkan semua barang beserta stok saat ini (READ-ONLY).
     * Perubahan stok dilakukan via menu Stok Masuk / Stok Keluar.
     */
    public function index()
    {
        $barangs = Barang::with(['satuan', 'stok'])->latest()->get();
        return view('stok.index', compact('barangs'));
    }
}
