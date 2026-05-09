@extends('layouts.app')

@section('title', 'Stok Keluar')
@section('page-title', 'Stok Keluar')
@section('page-subtitle', 'Riwayat barang yang keluar dari inventori')

@section('header-action')
    <a href="{{ route('stok-keluar.create') }}"
       class="inline-flex items-center gap-2 bg-brand-500 hover:bg-brand-600 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition-colors shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
        </svg>
        Catat Keluar
    </a>
@endsection

@section('content')

<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-ink-300 px-5 py-4">
        <p class="text-xs text-ink-500 font-medium">Jumlah Transaksi</p>
        <p class="text-2xl font-bold text-ink-900 mt-1">{{ $summary['total_transaksi'] }}</p>
    </div>
    <div class="bg-white rounded-xl border border-ink-300 px-5 py-4">
        <p class="text-xs text-ink-500 font-medium">Total Penjualan</p>
        <p class="text-2xl font-bold text-ink-900 mt-1">Rp {{ number_format($summary['total_penjualan'], 0, ',', '.') }}</p>
    </div>
    <div class="bg-white rounded-xl border border-ink-300 px-5 py-4">
        <p class="text-xs text-ink-500 font-medium">Total Profit</p>
        <p class="text-2xl font-bold {{ $summary['total_profit'] < 0 ? 'text-red-500' : 'text-emerald-600' }} mt-1">
            Rp {{ number_format($summary['total_profit'], 0, ',', '.') }}
        </p>
    </div>
</div>

<div class="bg-white rounded-xl border border-ink-300 overflow-hidden">
    <div class="px-6 py-4 border-b border-ink-300 flex items-center justify-between">
        <h2 class="text-sm font-semibold text-ink-700">Riwayat Stok Keluar</h2>
        <span class="text-xs text-ink-500 bg-ink-100 px-2.5 py-1 rounded-full font-medium">
            {{ $historis->count() }} transaksi
        </span>
    </div>

    @if ($historis->isEmpty())
        <div class="py-16 text-center">
            <p class="text-ink-700 font-semibold text-sm">Belum ada transaksi stok keluar</p>
            <p class="text-xs text-ink-500 mt-1">Catat barang keluar pertama kamu.</p>
            <a href="{{ route('stok-keluar.create') }}"
               class="mt-4 inline-flex items-center gap-2 bg-brand-500 hover:bg-brand-600 text-white text-sm font-semibold px-4 py-2.5 rounded-lg">Catat Keluar</a>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-ink-100 text-xs font-semibold text-ink-500 uppercase tracking-wider">
                        <th class="text-left px-4 py-3 w-10">#</th>
                        <th class="text-left px-4 py-3">Tanggal</th>
                        <th class="text-left px-4 py-3">Barang</th>
                        <th class="text-left px-4 py-3">Satuan</th>
                        <th class="text-right px-4 py-3">Stok Awal</th>
                        <th class="text-right px-4 py-3">Keluar</th>
                        <th class="text-right px-4 py-3">Stok Akhir</th>
                        <th class="text-right px-4 py-3">Harga Jual</th>
                        <th class="text-right px-4 py-3">Total Penjualan</th>
                        <th class="text-right px-4 py-3">Profit</th>
                        <th class="text-left px-4 py-3">Deskripsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-300">
                    @foreach ($historis as $i => $h)
                        @php
                            $barang = $h->stok?->barang;
                            $satuan = $barang?->satuan;
                            $negatif = ($h->stok_akhir ?? 0) < 0;
                            $rugi = ($h->profit ?? 0) < 0;
                        @endphp
                        <tr class="trow">
                            <td class="px-4 py-3 text-ink-500 text-xs">{{ $i + 1 }}</td>
                            <td class="px-4 py-3 text-xs text-ink-700">{{ $h->created_at?->format('d M Y H:i') }}</td>
                            <td class="px-4 py-3 font-semibold text-ink-900">{{ $barang->nama ?? '—' }}</td>
                            <td class="px-4 py-3 text-xs">
                                @if ($satuan)
                                    <span class="inline-flex bg-sky-50 text-sky-700 font-semibold px-2 py-0.5 rounded-full border border-sky-200">{{ $satuan->nama }}</span>
                                @else <span class="text-ink-400">—</span> @endif
                            </td>
                            <td class="px-4 py-3 text-right text-ink-700">{{ number_format($h->stok_awal ?? 0) }}</td>
                            <td class="px-4 py-3 text-right font-semibold text-red-600">-{{ number_format($h->jumlah) }}</td>
                            <td class="px-4 py-3 text-right font-bold {{ $negatif ? 'text-red-600' : 'text-ink-900' }}">{{ number_format($h->stok_akhir ?? 0) }}</td>
                            <td class="px-4 py-3 text-right text-ink-700">Rp {{ number_format($h->harga_jual ?? 0, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-right font-semibold text-ink-900">Rp {{ number_format($h->total_cash ?? 0, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-right font-semibold {{ $rugi ? 'text-red-600' : 'text-emerald-600' }}">Rp {{ number_format($h->profit ?? 0, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-xs text-ink-600 max-w-xs truncate">{{ $h->deskripsi ?: '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@endsection
