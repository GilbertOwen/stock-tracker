@extends('layouts.app')

@section('title', 'Catat Stok Keluar')
@section('page-title', 'Catat Stok Keluar')
@section('page-subtitle', 'Kurangi stok untuk barang yang keluar / terjual')

@section('header-action')
    <a href="{{ route('stok-keluar.index') }}"
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

    @if ($errors->has('stok_warning'))
        <div class="mb-5 px-4 py-3 bg-amber-50 border border-amber-300 text-amber-800 rounded-lg flex items-start gap-3">
            <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
            </svg>
            <div class="flex-1 text-sm">
                <p class="font-semibold">Perhatian — stok akan negatif</p>
                <p class="text-xs mt-1">{{ $errors->first('stok_warning') }}</p>
            </div>
        </div>
    @endif

    <form action="{{ route('stok-keluar.store') }}" method="POST" class="bg-white rounded-xl border border-ink-300 overflow-hidden">
        @csrf

        <div class="px-6 py-4 bg-ink-900 flex items-center gap-3">
            <div class="w-8 h-8 rounded-md bg-brand-500/20 border border-brand-500/30 flex items-center justify-center">
                <svg class="w-4 h-4 text-brand-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15"/>
                </svg>
            </div>
            <div>
                <h2 class="text-sm font-bold text-white">Stok Keluar Baru</h2>
                <p class="text-xs text-slate-400">Pilih barang dan masukkan jumlah yang keluar</p>
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
                        @php $stokKini = $b->stok?->jumlah ?? 0; @endphp
                        <option value="{{ $b->id_barang }}" {{ old('id_barang') == $b->id_barang ? 'selected' : '' }}>
                            {{ $b->nama }} ({{ $b->satuan->nama ?? '-' }}) — Stok: {{ $stokKini }}{{ $stokKini <= 5 ? ' ⚠' : '' }}
                        </option>
                    @endforeach
                </select>
                @error('id_barang') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            {{-- Jumlah & Harga Jual --}}
            <div class="grid grid-cols-2 gap-4">
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
                    <label class="block text-xs font-semibold text-ink-700 mb-1.5 uppercase tracking-wide">
                        Harga Jual <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-xs font-semibold text-ink-400">Rp</span>
                        <input type="number" id="harga_jual" name="harga_jual" value="{{ old('harga_jual') }}" min="0" placeholder="0"
                            class="w-full pl-9 pr-3 py-2.5 rounded-lg border text-sm font-medium text-ink-900
                                   focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-brand-400
                                   {{ $errors->has('harga_jual') ? 'border-red-400 bg-red-50' : 'border-ink-300 bg-white' }}">
                    </div>
                    @error('harga_jual') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Preview --}}
            <div class="grid grid-cols-4 gap-4 px-4 py-3 bg-ink-100 rounded-lg">
                <div>
                    <p class="text-[10px] text-ink-500 uppercase font-semibold tracking-wide">Stok Awal</p>
                    <p id="prev-awal" class="text-lg font-bold text-ink-700">0</p>
                </div>
                <div>
                    <p class="text-[10px] text-ink-500 uppercase font-semibold tracking-wide">Stok Akhir</p>
                    <p id="prev-akhir" class="text-lg font-bold text-ink-900">0</p>
                </div>
                <div>
                    <p class="text-[10px] text-ink-500 uppercase font-semibold tracking-wide">Total Penjualan</p>
                    <p id="prev-total" class="text-lg font-bold text-ink-900">Rp 0</p>
                </div>
                <div>
                    <p class="text-[10px] text-ink-500 uppercase font-semibold tracking-wide">Profit</p>
                    <p id="prev-profit" class="text-lg font-bold text-emerald-600">Rp 0</p>
                </div>
            </div>

            {{-- Konfirmasi negatif --}}
            @if ($errors->has('stok_warning'))
                <div class="flex items-center gap-2 px-4 py-3 bg-amber-50 border border-amber-300 rounded-lg">
                    <input type="checkbox" id="force_negative" name="force_negative" value="1" required
                        class="w-4 h-4 rounded border-amber-400 text-amber-500 focus:ring-amber-400">
                    <label for="force_negative" class="text-sm font-semibold text-amber-800 cursor-pointer">
                        Saya mengerti, tetap lanjutkan meskipun stok akan negatif
                    </label>
                </div>
            @endif

            {{-- Deskripsi --}}
            <div>
                <label class="block text-xs font-semibold text-ink-700 mb-1.5 uppercase tracking-wide">Deskripsi</label>
                <textarea name="deskripsi" rows="2" placeholder="Catatan tambahan (opsional)"
                    class="w-full px-4 py-2.5 rounded-lg border border-ink-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand-400">{{ old('deskripsi') }}</textarea>
            </div>

            <div class="border-t border-ink-200 pt-4 flex items-center justify-end gap-3">
                <a href="{{ route('stok-keluar.index') }}" class="px-5 py-2.5 text-sm font-semibold text-ink-600 hover:text-ink-900 border border-ink-300 rounded-lg">Batal</a>
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
const $hargaJual = document.getElementById('harga_jual');
const $awal = document.getElementById('prev-awal');
const $akhir = document.getElementById('prev-akhir');
const $total = document.getElementById('prev-total');
const $profit = document.getElementById('prev-profit');

function fmt(n) { return 'Rp ' + Number(n||0).toLocaleString('id-ID'); }

function refresh() {
    const id = $sel.value;
    const data = barangData[id] || { stok: 0, harga_beli: 0, harga_jual: 0 };
    const jumlah = parseInt($jumlah.value) || 0;
    const stokAwal = parseInt(data.stok) || 0;
    const stokAkhir = stokAwal - jumlah;
    const hargaJual = parseInt($hargaJual.value) || 0;
    const hargaBeli = parseInt(data.harga_beli) || 0;
    const total = hargaJual * jumlah;
    const profit = (hargaJual - hargaBeli) * jumlah;

    $awal.textContent = stokAwal.toLocaleString('id-ID');
    $akhir.textContent = stokAkhir.toLocaleString('id-ID');
    $akhir.className = 'text-lg font-bold ' + (stokAkhir < 0 ? 'text-red-600' : 'text-ink-900');
    $total.textContent = fmt(total);
    $profit.textContent = fmt(profit);
    $profit.className = 'text-lg font-bold ' + (profit < 0 ? 'text-red-600' : 'text-emerald-600');
}

$sel.addEventListener('change', () => {
    const data = barangData[$sel.value];
    if (data && !$hargaJual.value) $hargaJual.value = data.harga_jual || '';
    refresh();
});
[$jumlah, $hargaJual].forEach(el => el.addEventListener('input', refresh));
refresh();
</script>

@endsection
