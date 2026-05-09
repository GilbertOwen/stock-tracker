<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Satuan;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        $barangs = Barang::with('satuan')->latest()->get();
        return view('barangs.index', compact('barangs'));
    }

    public function create()
    {
        $satuans = Satuan::all();
        return view('barangs.create', compact('satuans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'       => 'required|string|max:255',
            'id_satuan'  => 'required|exists:satuans,id_satuan',
            'harga_beli' => 'nullable|integer|min:0',
            'harga_jual' => 'nullable|integer|min:0',
        ]);

        Barang::create($validated);

        return redirect()
            ->route('barangs.index')
            ->with('success', 'Barang berhasil ditambahkan.');
    }

    public function show(Barang $barang)
    {
        return view('barangs.show', compact('barang'));
    }

    public function edit(Barang $barang)
    {
        $satuans = Satuan::all();
        return view('barangs.edit', compact('barang', 'satuans'));
    }

    public function update(Request $request, Barang $barang)
    {
        $validated = $request->validate([
            'nama'       => 'required|string|max:255',
            'id_satuan'  => 'required|exists:satuans,id_satuan',
            'harga_beli' => 'nullable|integer|min:0',
            'harga_jual' => 'nullable|integer|min:0',
        ]);

        $barang->update($validated);

        return redirect()
            ->route('barangs.index')
            ->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Barang $barang)
    {
        $barang->delete();

        return redirect()
            ->route('barangs.index')
            ->with('success', 'Barang berhasil dihapus.');
    }
}
