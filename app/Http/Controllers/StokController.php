<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Stok;
use Illuminate\Http\Request;

class StokController extends Controller
{
    /**
     * Tampilkan semua barang beserta stok saat ini.
     */
    public function index()
    {
        $barangs = Barang::with(['satuan', 'stok'])->latest()->get();
        return view('stok.index', compact('barangs'));
    }

    /**
     * Form set/edit stok untuk barang tertentu.
     */
    public function edit(Barang $barang)
    {
        $stok = $barang->stok; // bisa null kalau belum pernah diisi
        return view('stok.edit', compact('barang', 'stok'));
    }

    /**
     * Simpan atau update jumlah stok barang.
     */
    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:0',
        ]);

        // Gunakan updateOrCreate agar aman meski record belum ada
        Stok::updateOrCreate(
            ['id_barang' => $barang->id_barang],
            ['jumlah'    => $request->jumlah]
        );

        return redirect()->route('stok.index')->with('success', "Stok {$barang->nama} berhasil diperbarui.");
    }
}