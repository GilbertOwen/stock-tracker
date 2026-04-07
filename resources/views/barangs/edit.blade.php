@extends('layouts.app')

@section('title', 'Edit Barang')
@section('page-title', 'Edit Barang')
@section('page-subtitle', 'Perbarui informasi barang yang sudah ada')

@section('header-action')
    <a href="{{ route('barangs.index') }}"
       class="inline-flex items-center gap-2 text-sm font-medium text-ink-600 hover:text-ink-900 border border-ink-300 hover:border-ink-400 px-4 py-2.5 rounded-lg transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
        </svg>
        Kembali
    </a>
@endsection

@section('content')

<div class="max-w-xl">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-xs text-ink-500 mb-5">
        <a href="{{ route('barangs.index') }}" class="hover:text-ink-700 font-medium transition-colors">Data Barang</a>
        <svg class="w-3.5 h-3.5 text-ink-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
        </svg>
        <span class="text-ink-700 font-semibold">{{ $barang->nama }}</span>
    </div>

    {{-- ══════════════════════════════════════
         FORM UPDATE — berdiri sendiri
         Tidak ada <form> lain di dalamnya
    ══════════════════════════════════════ --}}
    <div class="bg-white rounded-xl border border-ink-300 overflow-hidden mb-4">

        <div class="px-6 py-4 bg-ink-900 flex items-center gap-3">
            <div class="w-8 h-8 rounded-md bg-amber-400/20 border border-amber-400/30 flex items-center justify-center">
                <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-sm font-bold text-white">Edit Barang</h2>
                <p class="text-xs text-slate-400">ID: <span class="font-mono text-slate-300">{{ $barang->id_barang }}</span></p>
            </div>
        </div>

        <form action="{{ route('barangs.update', $barang->id_barang) }}" method="POST" class="px-6 py-6 space-y-5">
            @csrf
            @method('PUT')

            {{-- Nama --}}
            <div>
                <label for="nama" class="block text-xs font-semibold text-ink-700 mb-1.5 uppercase tracking-wide">
                    Nama Barang <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    id="nama"
                    name="nama"
                    value="{{ old('nama', $barang->nama) }}"
                    placeholder="Contoh: Baut M8 x 30mm"
                    autocomplete="off"
                    class="w-full px-4 py-2.5 rounded-lg border text-sm font-medium text-ink-900 placeholder-ink-300
                           focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-brand-400 transition
                           {{ $errors->has('nama') ? 'border-red-400 bg-red-50' : 'border-ink-300 bg-white hover:border-ink-400' }}"
                />
                @error('nama')
                    <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Satuan --}}
            <div>
                <label for="id_satuan" class="block text-xs font-semibold text-ink-700 mb-1.5 uppercase tracking-wide">
                    Satuan <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <select
                        id="id_satuan"
                        name="id_satuan"
                        class="w-full px-4 py-2.5 rounded-lg border text-sm font-medium text-ink-900 appearance-none
                               focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-brand-400 transition
                               {{ $errors->has('id_satuan') ? 'border-red-400 bg-red-50' : 'border-ink-300 bg-white hover:border-ink-400' }}"
                    >
                        <option value="" disabled>— Pilih satuan —</option>
                        @foreach ($satuans as $satuan)
                            <option value="{{ $satuan->id_satuan }}"
                                    {{ old('id_satuan', $barang->id_satuan) == $satuan->id_satuan ? 'selected' : '' }}>
                                {{ $satuan->nama }}
                            </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                        <svg class="w-4 h-4 text-ink-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                        </svg>
                    </div>
                </div>
                @error('id_satuan')
                    <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Harga --}}
            <div>
                <label for="harga" class="block text-xs font-semibold text-ink-700 mb-1.5 uppercase tracking-wide">
                    Harga
                    <span class="normal-case text-ink-400 font-normal ml-1">(opsional)</span>
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-4 flex items-center text-sm font-semibold text-ink-400 pointer-events-none">
                        Rp
                    </span>
                    <input
                        type="number"
                        id="harga"
                        name="harga"
                        value="{{ old('harga', $barang->harga) }}"
                        placeholder="0"
                        min="0"
                        class="w-full pl-10 pr-4 py-2.5 rounded-lg border text-sm font-medium text-ink-900 placeholder-ink-300
                               focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-brand-400 transition
                               {{ $errors->has('harga') ? 'border-red-400 bg-red-50' : 'border-ink-300 bg-white hover:border-ink-400' }}"
                    />
                </div>
                @error('harga')
                    <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Tombol aksi --}}
            <div class="border-t border-ink-200 pt-4 flex items-center justify-end gap-3">
                <a href="{{ route('barangs.index') }}"
                   class="px-5 py-2.5 text-sm font-semibold text-ink-600 hover:text-ink-900 border border-ink-300 hover:border-ink-400 rounded-lg transition-colors">
                    Batal
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-500 hover:bg-brand-600 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </div>
    {{-- ══ END FORM UPDATE ══ --}}


    {{-- ══════════════════════════════════════
         DANGER ZONE — form TERPISAH di luar
         Letaknya setelah </div> card di atas
    ══════════════════════════════════════ --}}
    <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 flex items-center justify-between gap-4">
        <div>
            <p class="text-sm font-semibold text-red-700">Hapus Barang</p>
            <p class="text-xs text-red-500 mt-0.5">Tindakan ini permanen dan tidak bisa dibatalkan.</p>
        </div>

        <form action="{{ route('barangs.destroy', $barang->id_barang) }}"
              method="POST"
              onsubmit="return confirm('Yakin ingin menghapus barang \'{{ addslashes($barang->nama) }}\'?')">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="inline-flex items-center gap-1.5 text-xs font-semibold text-red-600 hover:text-white hover:bg-red-500 border border-red-300 hover:border-red-500 px-3 py-1.5 rounded-lg transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916"/>
                </svg>
                Hapus Barang
            </button>
        </form>
    </div>
    {{-- ══ END DANGER ZONE ══ --}}

</div>

@endsection