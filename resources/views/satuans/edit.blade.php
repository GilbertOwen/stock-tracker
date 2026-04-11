@extends('layouts.app')

@section('title', 'Edit Satuan')
@section('page-title', 'Edit Satuan')
@section('page-subtitle', 'Perbarui nama satuan yang sudah ada')

@section('header-action')
    <a href="{{ route('satuans.index') }}"
       class="inline-flex items-center gap-2 text-sm font-medium text-ink-600 hover:text-ink-900 border border-ink-300 hover:border-ink-400 px-4 py-2.5 rounded-lg transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
        </svg>
        Kembali
    </a>
@endsection

@section('content')

<div class="max-w-md">

    <div class="flex items-center gap-2 text-xs text-ink-500 mb-5">
        <a href="{{ route('satuans.index') }}" class="hover:text-ink-700 font-medium transition-colors">Data Satuan</a>
        <svg class="w-3.5 h-3.5 text-ink-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
        </svg>
        <span class="text-ink-700 font-semibold">{{ $satuan->nama }}</span>
    </div>

    <div class="bg-white rounded-xl border border-ink-300 overflow-hidden">

        <div class="px-6 py-4 bg-ink-900 flex items-center gap-3">
            <div class="w-8 h-8 rounded-md bg-amber-400/20 border border-amber-400/30 flex items-center justify-center">
                <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-sm font-bold text-white">Edit Satuan</h2>
                <p class="text-xs text-slate-400">ID: <span class="font-mono text-slate-300">{{ $satuan->id_satuan }}</span></p>
            </div>
        </div>

        <form action="{{ route('satuans.update', $satuan->id_satuan) }}" method="POST" class="px-6 py-6 space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="nama" class="block text-xs font-semibold text-ink-700 mb-1.5 uppercase tracking-wide">
                    Nama Satuan <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    id="nama"
                    name="nama"
                    value="{{ old('nama', $satuan->nama) }}"
                    placeholder="Contoh: pcs, kg, dus..."
                    autocomplete="off"
                    autofocus
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

            <div class="border-t border-ink-200 pt-4 flex items-center justify-end gap-3">
                <a href="{{ route('satuans.index') }}"
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

</div>

@endsection