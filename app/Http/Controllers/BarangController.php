<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Satuan;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    /**
     * Tampilkan daftar semua barang beserta satuannya.
     */
    public function index()
    {
        $barangs = Barang::with('satuan')->latest()->get();

        return view('barangs.index', compact('barangs'));
    }

    /**
     * Tampilkan form tambah barang baru.
     */
    public function create()
    {
        $satuans = Satuan::all();

        return view('barangs.create', compact('satuans'));
    }

    /**
     * Simpan barang baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'      => 'required|string|max:255',
            'id_satuan' => 'required|exists:satuans,id_satuan',
            'Harga'     => 'nullable|integer|min:0',
        ]);

        Barang::create($validated);

        return redirect()
            ->route('barangs.index')
            ->with('success', 'Barang berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail barang (opsional, bisa dihilangkan jika tidak dipakai).
     */
    public function show(Barang $barang)
    {
        return view('barangs.show', compact('barang'));
    }

    /**
     * Tampilkan form edit barang.
     */
    public function edit(Barang $barang)
    {
        $satuans = Satuan::all();

        return view('barangs.edit', compact('barang', 'satuans'));
    }

    /**
     * Update data barang di database.
     */
    public function update(Request $request, Barang $barang)
    {
        $validated = $request->validate([
            'nama'      => 'required|string|max:255',
            'id_satuan' => 'required|exists:satuans,id_satuan',
            'Harga'     => 'nullable|integer|min:0',
        ]);

        $barang->update($validated);

        return redirect()
            ->route('barangs.index')
            ->with('success', 'Barang berhasil diperbarui.');
    }

    /**
     * Hapus barang dari database.
     */
    public function destroy(Barang $barang)
    {
        $barang->delete();

        return redirect()
            ->route('barangs.index')
            ->with('success', 'Barang berhasil dihapus.');
    }
}