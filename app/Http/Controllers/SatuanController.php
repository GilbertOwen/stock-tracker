<?php

namespace App\Http\Controllers;

use App\Models\Satuan;
use Illuminate\Http\Request;

class SatuanController extends Controller
{
    public function index()
    {
        $satuans = Satuan::withCount('barangs')->latest()->get();
        return view('satuans.index', compact('satuans'));
    }

    public function create()
    {
        return view('satuans.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100|unique:satuans,nama',
        ]);

        Satuan::create(['nama' => $request->nama]);

        return redirect()->route('satuans.index')->with('success', 'Satuan berhasil ditambahkan.');
    }

    public function edit(Satuan $satuan)
    {
        return view('satuans.edit', compact('satuan'));
    }

    public function update(Request $request, Satuan $satuan)
    {
        $request->validate([
            'nama' => 'required|string|max:100|unique:satuans,nama,' . $satuan->id_satuan . ',id_satuan',
        ]);

        $satuan->update(['nama' => $request->nama]);

        return redirect()->route('satuans.index')->with('success', 'Satuan berhasil diperbarui.');
    }

    public function destroy(Satuan $satuan)
    {
        if ($satuan->barangs()->count() > 0) {
            return redirect()->route('satuans.index')
                ->with('error', 'Satuan tidak bisa dihapus karena masih dipakai oleh ' . $satuan->barangs()->count() . ' barang.');
        }

        $satuan->delete();

        return redirect()->route('satuans.index')->with('success', 'Satuan berhasil dihapus.');
    }
}