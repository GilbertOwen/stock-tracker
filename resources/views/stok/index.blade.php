@extends('layouts.app')

@section('title', 'Stok Barang')
@section('page-title', 'Stok Barang')
@section('page-subtitle', 'Pantau jumlah stok setiap barang')

@section('header-action')
    <div class="flex items-center gap-2">
        <a href="{{ route('stok-masuk.create') }}"
           class="inline-flex items-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            Catat Masuk
        </a>
        <a href="{{ route('stok-keluar.create') }}"
           class="inline-flex items-center gap-2 bg-brand-500 hover:bg-brand-600 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15"/>
            </svg>
            Catat Keluar
        </a>
    </div>
@endsection

@section('content')

<div class="mb-4 flex items-start gap-3 px-4 py-3 bg-sky-50 border border-sky-200 text-sky-800 text-xs font-medium rounded-lg">
    <svg class="w-4 h-4 text-sky-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
    </svg>
    <span>Halaman ini bersifat <strong>read-only</strong>. Untuk mengubah stok, gunakan menu <strong>Stok Masuk</strong> atau <strong>Stok Keluar</strong>.</span>
</div>

{{-- Summary cards --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-ink-300 px-5 py-4 flex items-center gap-4">
        <div class="w-10 h-10 rounded-lg bg-brand-100 flex items-center justify-center">
            <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 10V11"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-ink-900">{{ $barangs->count() }}</p>
            <p class="text-xs text-ink-500 font-medium">Total Barang</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-ink-300 px-5 py-4 flex items-center gap-4">
        <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center">
            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-ink-900">
                {{ $barangs->filter(fn($b) => $b->stok && $b->stok->jumlah > 0)->count() }}
            </p>
            <p class="text-xs text-ink-500 font-medium">Stok Tersedia</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-ink-300 px-5 py-4 flex items-center gap-4">
        <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center">
            <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-ink-900">
                {{ $barangs->filter(fn($b) => !$b->stok || $b->stok->jumlah == 0)->count() }}
            </p>
            <p class="text-xs text-ink-500 font-medium">Stok Habis / Belum Diisi</p>
        </div>
    </div>
</div>

{{-- Table --}}
<div class="bg-white rounded-xl border border-ink-300 overflow-hidden">
    <div class="px-6 py-4 border-b border-ink-300 flex items-center justify-between">
        <h2 class="text-sm font-semibold text-ink-700">Daftar Stok Barang</h2>
        <span class="text-xs text-ink-500 bg-ink-100 px-2.5 py-1 rounded-full font-medium">
            {{ $barangs->count() }} barang
        </span>
    </div>

    @if ($barangs->isEmpty())
        <div class="py-16 text-center">
            <p class="text-ink-700 font-semibold text-sm">Belum ada barang</p>
            <p class="text-xs text-ink-500 mt-1">Tambahkan barang terlebih dahulu.</p>
            <a href="{{ route('barangs.create') }}"
               class="mt-4 inline-flex items-center gap-2 bg-brand-500 hover:bg-brand-600 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition-colors">
                Tambah Barang
            </a>
        </div>
    @else
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-ink-100 text-xs font-semibold text-ink-500 uppercase tracking-wider">
                    <th class="text-left px-6 py-3 w-10">#</th>
                    <th class="text-left px-4 py-3">Nama Barang</th>
                    <th class="text-left px-4 py-3">Satuan</th>
                    <th class="text-center px-4 py-3">Jumlah Stok</th>
                    <th class="text-center px-4 py-3">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink-300">
                @foreach ($barangs as $i => $barang)
                    @php
                        $jumlah = $barang->stok?->jumlah ?? null;
                        $belumDiisi = $jumlah === null;
                        $habis = !$belumDiisi && $jumlah == 0;
                        $menipis = !$belumDiisi && $jumlah > 0 && $jumlah <= 5;
                        $aman = !$belumDiisi && $jumlah > 5;
                    @endphp
                    <tr class="trow">
                        <td class="px-6 py-3.5 text-ink-500 text-xs font-medium">{{ $i + 1 }}</td>
                        <td class="px-4 py-3.5">
                            <span class="font-semibold text-ink-900">{{ $barang->nama }}</span>
                        </td>
                        <td class="px-4 py-3.5">
                            @if ($barang->satuan)
                                <span class="inline-flex items-center bg-sky-50 text-sky-700 text-xs font-semibold px-2.5 py-1 rounded-full border border-sky-200">
                                    {{ $barang->satuan->nama }}
                                </span>
                            @else
                                <span class="text-ink-400 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3.5 text-center">
                            @if ($belumDiisi)
                                <span class="text-ink-400 text-xs italic">Belum diisi</span>
                            @else
                                <span class="text-lg font-bold
                                    {{ $habis ? 'text-red-500' : ($menipis ? 'text-amber-500' : 'text-ink-900') }}">
                                    {{ number_format($jumlah) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3.5 text-center">
                            @if ($belumDiisi)
                                <span class="inline-flex items-center gap-1 bg-ink-100 text-ink-500 text-xs font-semibold px-2.5 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-ink-400"></span>
                                    Belum diisi
                                </span>
                            @elseif ($habis)
                                <span class="inline-flex items-center gap-1 bg-red-50 text-red-600 text-xs font-semibold px-2.5 py-1 rounded-full border border-red-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                    Habis
                                </span>
                            @elseif ($menipis)
                                <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 text-xs font-semibold px-2.5 py-1 rounded-full border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                    Menipis
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 text-xs font-semibold px-2.5 py-1 rounded-full border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Aman
                                </span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

@endsection