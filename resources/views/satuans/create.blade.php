@extends('layouts.app')

@section('title', 'Tambah Satuan')
@section('page-title', 'Tambah Satuan')
@section('page-subtitle', 'Tambahkan satuan baru seperti pcs, kg, dus, lusin, dll.')

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
    <div class="bg-white rounded-xl border border-ink-300 overflow-hidden">

        <div class="px-6 py-4 bg-ink-900 flex items-center gap-3">
            <div class="w-8 h-8 rounded-md bg-brand-500/20 border border-brand-500/30 flex items-center justify-center">
                <svg class="w-4 h-4 text-brand-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
            </div>
            <div>
                <h2 class="text-sm font-bold text-white">Satuan Baru</h2>
                <p class="text-xs text-slate-400">Contoh: pcs, kg, dus, lusin, meter</p>
            </div>
        </div>

        <form action="{{ route('satuans.store') }}" method="POST" class="px-6 py-6 space-y-5">
            @csrf

            <div>
                <label for="nama" class="block text-xs font-semibold text-ink-700 mb-1.5 uppercase tracking-wide">
                    Nama Satuan <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    id="nama"
                    name="nama"
                    value="{{ old('nama') }}"
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
                <p class="mt-1.5 text-xs text-ink-400">Nama satuan harus unik dan belum pernah ditambahkan.</p>
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
                    Simpan Satuan
                </button>
            </div>
        </form>
    </div>
</div>

@endsection