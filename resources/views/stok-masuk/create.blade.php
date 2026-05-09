@extends('layouts.app')

@section('title', 'Catat Stok Masuk')
@section('page-title', 'Catat Stok Masuk')
@section('page-subtitle', 'Tambah stok untuk barang yang baru masuk')

@section('header-action')
    <a href="{{ route('stok-masuk.index') }}"
       class="inline-flex items-center gap-2 text-sm font-medium text-ink-600 hover:text-ink-900 border border-ink-300 hover:border-ink-400 px-4 py-2.5 rounded-lg transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
        </svg>
        Kembali
    </a>
@endsection

@section('content')

@php
    $barangData = $barangs->mapWithKeys(fn($b) => [$b->id_barang => [
        'nama' => $b->nama,
        'satuan' => $b->satuan->nama ?? '-',
        'stok' => $b->stok?->jumlah ?? 0,
        'harga_beli' => $b->harga_beli ?? 0,
        'harga_jual' => $b->harga_jual ?? 0,
    ]])->toJson();
@endphp

<div class="max-w-2xl">
    <form action="{{ route('stok-masuk.store') }}" method="POST" class="bg-white rounded-xl border border-ink-300 overflow-hidden">
        @csrf

        <div class="px-6 py-4 bg-ink-900 flex items-center gap-3">
            <div class="w-8 h-8 rounded-md bg-emerald-500/20 border border-emerald-500/30 flex items-center justify-center">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
            </div>
            <div>
                <h2 class="text-sm font-bold text-white">Stok Masuk Baru</h2>
                <p class="text-xs text-slate-400">Pilih barang dan masukkan jumlah yang masuk</p>
            </div>
        </div>

        <div class="px-6 py-6 space-y-5">

            {{-- Barang --}}
            <div>
                <label class="block text-xs font-semibold text-ink-700 mb-1.5 uppercase tracking-wide">
                    Barang <span class="text-red-500">*</span>
                </label>
                <select id="id_barang" name="id_barang"
                    class="w-full px-4 py-2.5 rounded-lg border text-sm font-medium text-ink-900 appearance-none
                           focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-brand-400
                           {{ $errors->has('id_barang') ? 'border-red-400 bg-red-50' : 'border-ink-300 bg-white' }}">
                    <option value="" disabled {{ old('id_barang') ? '' : 'selected' }}>— Pilih barang —</option>
                    @foreach ($barangs as $b)
                        <option value="{{ $b->id_barang }}" {{ old('id_barang') == $b->id_barang ? 'selected' : '' }}>
                            {{ $b->nama }} ({{ $b->satuan->nama ?? '-' }}) — Stok: {{ $b->stok?->jumlah ?? 0 }}
                        </option>
                    @endforeach
                </select>
                @error('id_barang') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            {{-- Bonus checkbox --}}
            <div class="flex items-center gap-2 px-4 py-3 bg-amber-50 border border-amber-200 rounded-lg">
                <input type="hidden" name="is_bonus" value="0">
                <input type="checkbox" id="is_bonus" name="is_bonus" value="1" {{ old('is_bonus') ? 'checked' : '' }}
                    class="w-4 h-4 rounded border-amber-400 text-amber-500 focus:ring-amber-400">
                <label for="is_bonus" class="text-sm font-semibold text-amber-800 cursor-pointer">
                    Barang Bonus / Gratis
                    <span class="font-normal text-amber-700">(harga beli otomatis 0)</span>
                </label>
            </div>

            {{-- Jumlah & Harga --}}
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-ink-700 mb-1.5 uppercase tracking-wide">
                        Jumlah <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="jumlah" name="jumlah" value="{{ old('jumlah') }}" min="1" placeholder="0"
                        class="w-full px-4 py-2.5 rounded-lg border text-sm font-semibold text-ink-900
                               focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-brand-400
                               {{ $errors->has('jumlah') ? 'border-red-400 bg-red-50' : 'border-ink-300 bg-white' }}">
                    @error('jumlah') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-ink-700 mb-1.5 uppercase tracking-wide">Harga Beli</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-xs font-semibold text-ink-400">Rp</span>
                        <input type="number" id="harga_beli" name="harga_beli" value="{{ old('harga_beli') }}" min="0" placeholder="0"
                            class="w-full pl-9 pr-3 py-2.5 rounded-lg border text-sm font-medium text-ink-900
                                   focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-brand-400
                                   {{ $errors->has('harga_beli') ? 'border-red-400 bg-red-50' : 'border-ink-300 bg-white' }}">
                    </div>
                    @error('harga_beli') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-ink-700 mb-1.5 uppercase tracking-wide">Harga Jual</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-xs font-semibold text-ink-400">Rp</span>
                        <input type="number" id="harga_jual" name="harga_jual" value="{{ old('harga_jual') }}" min="0" placeholder="0"
                            class="w-full pl-9 pr-3 py-2.5 rounded-lg border text-sm font-medium text-ink-900
                                   focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-brand-400
                                   {{ $errors->has('harga_jual') ? 'border-red-400 bg-red-50' : 'border-ink-300 bg-white' }}">
                    </div>
                </div>
            </div>

            {{-- Preview --}}
            <div class="grid grid-cols-3 gap-4 px-4 py-3 bg-ink-100 rounded-lg">
                <div>
                    <p class="text-[10px] text-ink-500 uppercase font-semibold tracking-wide">Stok Awal</p>
                    <p id="prev-awal" class="text-lg font-bold text-ink-700">0</p>
                </div>
                <div>
                    <p class="text-[10px] text-ink-500 uppercase font-semibold tracking-wide">Stok Akhir</p>
                    <p id="prev-akhir" class="text-lg font-bold text-emerald-600">0</p>
                </div>
                <div>
                    <p class="text-[10px] text-ink-500 uppercase font-semibold tracking-wide">Total Pembelian</p>
                    <p id="prev-total" class="text-lg font-bold text-ink-900">Rp 0</p>
                </div>
            </div>

            {{-- Deskripsi --}}
            <div>
                <label class="block text-xs font-semibold text-ink-700 mb-1.5 uppercase tracking-wide">Deskripsi</label>
                <textarea name="deskripsi" rows="2" placeholder="Catatan tambahan (opsional)"
                    class="w-full px-4 py-2.5 rounded-lg border border-ink-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand-400">{{ old('deskripsi') }}</textarea>
            </div>

            <div class="border-t border-ink-200 pt-4 flex items-center justify-end gap-3">
                <a href="{{ route('stok-masuk.index') }}" class="px-5 py-2.5 text-sm font-semibold text-ink-600 hover:text-ink-900 border border-ink-300 rounded-lg">Batal</a>
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-500 hover:bg-brand-600 text-white text-sm font-semibold rounded-lg shadow-sm">
                    Simpan Transaksi
                </button>
            </div>
        </div>
    </form>
</div>

<script>
const barangData = {!! $barangData !!};

const $sel = document.getElementById('id_barang');
const $jumlah = document.getElementById('jumlah');
const $hargaBeli = document.getElementById('harga_beli');
const $hargaJual = document.getElementById('harga_jual');
const $bonus = document.getElementById('is_bonus');
const $awal = document.getElementById('prev-awal');
const $akhir = document.getElementById('prev-akhir');
const $total = document.getElementById('prev-total');

function fmt(n) { return 'Rp ' + Number(n||0).toLocaleString('id-ID'); }

function refresh() {
    const id = $sel.value;
    const data = barangData[id] || { stok: 0, harga_beli: 0, harga_jual: 0 };
    const jumlah = parseInt($jumlah.value) || 0;
    const stokAwal = parseInt(data.stok) || 0;
    const stokAkhir = stokAwal + jumlah;

    let hargaBeli = 0;
    if ($bonus.checked) {
        hargaBeli = 0;
        $hargaBeli.value = 0;
        $hargaBeli.disabled = true;
    } else {
        $hargaBeli.disabled = false;
        hargaBeli = parseInt($hargaBeli.value) || 0;
    }

    $awal.textContent = stokAwal.toLocaleString('id-ID');
    $akhir.textContent = stokAkhir.toLocaleString('id-ID');
    $total.textContent = fmt(hargaBeli * jumlah);
}

$sel.addEventListener('change', () => {
    const data = barangData[$sel.value];
    if (data && !$hargaBeli.value) $hargaBeli.value = data.harga_beli || '';
    if (data && !$hargaJual.value) $hargaJual.value = data.harga_jual || '';
    refresh();
});
[$jumlah, $hargaBeli, $hargaJual, $bonus].forEach(el => el.addEventListener('input', refresh));
$bonus.addEventListener('change', refresh);
refresh();
</script>

@endsection
