@extends('layouts.app')

@section('title', 'Set Stok — ' . $barang->nama)
@section('page-title', 'Set Stok Barang')
@section('page-subtitle', 'Atur jumlah stok untuk barang ini')

@section('header-action')
    <a href="{{ route('stok.index') }}"
       class="inline-flex items-center gap-2 text-sm font-medium text-ink-600 hover:text-ink-900 border border-ink-300 hover:border-ink-400 px-4 py-2.5 rounded-lg transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
        </svg>
        Kembali
    </a>
@endsection

@section('content')

<div class="max-w-md">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-xs text-ink-500 mb-5">
        <a href="{{ route('stok.index') }}" class="hover:text-ink-700 font-medium transition-colors">Stok Barang</a>
        <svg class="w-3.5 h-3.5 text-ink-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
        </svg>
        <span class="text-ink-700 font-semibold">{{ $barang->nama }}</span>
    </div>

    {{-- Info barang --}}
    <div class="bg-ink-900 rounded-xl px-5 py-4 mb-4 flex items-center justify-between">
        <div>
            <p class="text-xs text-slate-400 font-medium mb-0.5">Barang</p>
            <p class="text-white font-bold text-base">{{ $barang->nama }}</p>
            @if ($barang->satuan)
                <span class="inline-flex items-center mt-1 bg-white/10 text-slate-300 text-xs font-semibold px-2 py-0.5 rounded-full">
                    {{ $barang->satuan->nama }}
                </span>
            @endif
        </div>
        <div class="text-right">
            <p class="text-xs text-slate-400 font-medium mb-0.5">Stok Sekarang</p>
            @if ($stok)
                <p class="text-3xl font-bold {{ $stok->jumlah == 0 ? 'text-red-400' : ($stok->jumlah <= 5 ? 'text-amber-400' : 'text-emerald-400') }}">
                    {{ number_format($stok->jumlah) }}
                </p>
            @else
                <p class="text-slate-500 text-sm font-medium italic">Belum diisi</p>
            @endif
        </div>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-xl border border-ink-300 overflow-hidden">

        <div class="px-6 py-4 border-b border-ink-200">
            <h2 class="text-sm font-bold text-ink-800">Update Jumlah Stok</h2>
            <p class="text-xs text-ink-500 mt-0.5">
                Masukkan jumlah stok aktual yang ada saat ini.
                @if (!$stok)
                    Record stok akan dibuat otomatis.
                @endif
            </p>
        </div>

        <form action="{{ route('stok.update', $barang->id_barang) }}" method="POST" class="px-6 py-6 space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="jumlah" class="block text-xs font-semibold text-ink-700 mb-1.5 uppercase tracking-wide">
                    Jumlah Stok <span class="text-red-500">*</span>
                </label>
                <div class="flex items-center gap-3">
                    {{-- Tombol kurang --}}
                    <button type="button" id="btn-minus"
                            class="w-10 h-10 rounded-lg border border-ink-300 hover:border-red-300 hover:bg-red-50 flex items-center justify-center text-ink-600 hover:text-red-600 transition-colors flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 12H6"/>
                        </svg>
                    </button>

                    <input
                        type="number"
                        id="jumlah"
                        name="jumlah"
                        value="{{ old('jumlah', $stok?->jumlah ?? 0) }}"
                        min="0"
                        class="flex-1 text-center px-4 py-2.5 rounded-lg border text-lg font-bold text-ink-900
                               focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-brand-400 transition
                               {{ $errors->has('jumlah') ? 'border-red-400 bg-red-50' : 'border-ink-300 bg-white hover:border-ink-400' }}"
                    />

                    {{-- Tombol tambah --}}
                    <button type="button" id="btn-plus"
                            class="w-10 h-10 rounded-lg border border-ink-300 hover:border-emerald-300 hover:bg-emerald-50 flex items-center justify-center text-ink-600 hover:text-emerald-600 transition-colors flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6"/>
                        </svg>
                    </button>
                </div>
                @error('jumlah')
                    <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror

                {{-- Quick set buttons --}}
                <div class="mt-3 flex items-center gap-2 flex-wrap">
                    <span class="text-xs text-ink-400">Set cepat:</span>
                    @foreach ([0, 10, 25, 50, 100] as $val)
                        <button type="button"
                                onclick="document.getElementById('jumlah').value = {{ $val }}"
                                class="text-xs font-semibold text-ink-600 hover:text-brand-600 border border-ink-300 hover:border-brand-400 px-2.5 py-1 rounded-md transition-colors">
                            {{ $val }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="border-t border-ink-200 pt-4 flex items-center justify-end gap-3">
                <a href="{{ route('stok.index') }}"
                   class="px-5 py-2.5 text-sm font-semibold text-ink-600 hover:text-ink-900 border border-ink-300 hover:border-ink-400 rounded-lg transition-colors">
                    Batal
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-500 hover:bg-brand-600 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                    </svg>
                    Simpan Stok
                </button>
            </div>
        </form>
    </div>

</div>

<script>
    const input = document.getElementById('jumlah');
    document.getElementById('btn-plus').addEventListener('click', () => {
        input.value = parseInt(input.value || 0) + 1;
    });
    document.getElementById('btn-minus').addEventListener('click', () => {
        const val = parseInt(input.value || 0);
        if (val > 0) input.value = val - 1;
    });
</script>

@endsection