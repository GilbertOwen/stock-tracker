@extends('layouts.app')

@section('title', 'Tambah Barang')
@section('page-title', 'Tambah Barang')
@section('page-subtitle', 'Isi detail barang yang ingin ditambahkan ke inventori')

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

    {{-- Card --}}
    <div class="bg-white rounded-xl border border-ink-300 overflow-hidden">

        {{-- Card header --}}
        <div class="px-6 py-4 bg-ink-900 flex items-center gap-3">
            <div class="w-8 h-8 rounded-md bg-brand-500/20 border border-brand-500/30 flex items-center justify-center">
                <svg class="w-4 h-4 text-brand-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
            </div>
            <div>
                <h2 class="text-sm font-bold text-white">Barang Baru</h2>
                <p class="text-xs text-slate-400">Lengkapi semua field yang diperlukan</p>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('barangs.store') }}" method="POST" class="px-6 py-6 space-y-5">
            @csrf

            {{-- Nama --}}
            <div>
                <label for="nama" class="block text-xs font-semibold text-ink-700 mb-1.5 uppercase tracking-wide">
                    Nama Barang <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    id="nama"
                    name="nama"
                    value="{{ old('nama') }}"
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
                        <option value="" disabled {{ old('id_satuan') ? '' : 'selected' }}>
                            — Pilih satuan —
                        </option>
                        @foreach ($satuans as $satuan)
                            <option value="{{ $satuan->id_satuan }}"
                                    {{ old('id_satuan') == $satuan->id_satuan ? 'selected' : '' }}>
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
                        value="{{ old('harga') }}"
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

            {{-- Divider --}}
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
                    Simpan Barang
                </button>
            </div>

        </form>
    </div>

</div>

@endsection