@extends('layouts.app')

@section('title', 'Histori Stok')
@section('page-title', 'Histori Stok')
@section('page-subtitle', 'Riwayat lengkap semua transaksi stok')

@section('content')

{{-- Filter --}}
<form method="GET" action="{{ route('histori-stok.index') }}" class="bg-white rounded-xl border border-ink-300 p-5 mb-6">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-3">
        <div>
            <label class="block text-[10px] font-semibold text-ink-500 mb-1 uppercase tracking-wide">Tanggal Dari</label>
            <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}"
                class="w-full px-3 py-2 rounded-lg border border-ink-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand-400">
        </div>
        <div>
            <label class="block text-[10px] font-semibold text-ink-500 mb-1 uppercase tracking-wide">Tanggal Sampai</label>
            <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}"
                class="w-full px-3 py-2 rounded-lg border border-ink-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand-400">
        </div>
        <div>
            <label class="block text-[10px] font-semibold text-ink-500 mb-1 uppercase tracking-wide">Barang</label>
            <select name="id_barang" class="w-full px-3 py-2 rounded-lg border border-ink-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand-400">
                <option value="">— Semua —</option>
                @foreach ($barangs as $b)
                    <option value="{{ $b->id_barang }}" {{ request('id_barang') == $b->id_barang ? 'selected' : '' }}>{{ $b->nama }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-[10px] font-semibold text-ink-500 mb-1 uppercase tracking-wide">Tipe</label>
            <select name="tipe" class="w-full px-3 py-2 rounded-lg border border-ink-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand-400">
                <option value="">— Semua —</option>
                <option value="masuk" {{ request('tipe') == 'masuk' ? 'selected' : '' }}>Masuk</option>
                <option value="keluar" {{ request('tipe') == 'keluar' ? 'selected' : '' }}>Keluar</option>
            </select>
        </div>
    </div>
    <div class="flex items-center gap-2">
        <button type="submit" class="px-4 py-2 bg-brand-500 hover:bg-brand-600 text-white text-xs font-semibold rounded-lg">Terapkan</button>
        <a href="{{ route('histori-stok.index') }}" class="px-4 py-2 border border-ink-300 text-ink-600 hover:bg-ink-100 text-xs font-semibold rounded-lg">Reset</a>
    </div>
</form>

{{-- Summary --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-ink-300 px-5 py-4">
        <p class="text-xs text-ink-500 font-medium">Total Penjualan</p>
        <p class="text-xl font-bold text-ink-900 mt-1">Rp {{ number_format($summary['total_penjualan'], 0, ',', '.') }}</p>
    </div>
    <div class="bg-white rounded-xl border border-ink-300 px-5 py-4">
        <p class="text-xs text-ink-500 font-medium">Total Pembelian</p>
        <p class="text-xl font-bold text-ink-900 mt-1">Rp {{ number_format($summary['total_pembelian'], 0, ',', '.') }}</p>
    </div>
    <div class="bg-white rounded-xl border border-ink-300 px-5 py-4">
        <p class="text-xs text-ink-500 font-medium">Total Profit</p>
        <p class="text-xl font-bold {{ $summary['total_profit'] < 0 ? 'text-red-500' : 'text-emerald-600' }} mt-1">
            Rp {{ number_format($summary['total_profit'], 0, ',', '.') }}
        </p>
    </div>
</div>

<div class="bg-white rounded-xl border border-ink-300 overflow-hidden">
    <div class="px-6 py-4 border-b border-ink-300 flex items-center justify-between">
        <h2 class="text-sm font-semibold text-ink-700">Riwayat Transaksi</h2>
        <span class="text-xs text-ink-500 bg-ink-100 px-2.5 py-1 rounded-full font-medium">
            {{ $historis->count() }} transaksi
        </span>
    </div>

    @if ($historis->isEmpty())
        <div class="py-16 text-center">
            <p class="text-ink-700 font-semibold text-sm">Tidak ada transaksi sesuai filter</p>
            <p class="text-xs text-ink-500 mt-1">Coba ubah atau reset filter di atas.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-ink-100 text-xs font-semibold text-ink-500 uppercase tracking-wider">
                        <th class="text-left px-3 py-3 w-10">#</th>
                        <th class="text-left px-3 py-3">Tanggal</th>
                        <th class="text-left px-3 py-3">Barang</th>
                        <th class="text-left px-3 py-3">Satuan</th>
                        <th class="text-center px-3 py-3">Tipe</th>
                        <th class="text-right px-3 py-3">Stok Awal</th>
                        <th class="text-right px-3 py-3">Jumlah</th>
                        <th class="text-right px-3 py-3">Stok Akhir</th>
                        <th class="text-right px-3 py-3">Harga Beli</th>
                        <th class="text-right px-3 py-3">Harga Jual</th>
                        <th class="text-right px-3 py-3">Total Cash</th>
                        <th class="text-right px-3 py-3">Profit</th>
                        <th class="text-center px-3 py-3">Bonus</th>
                        <th class="text-left px-3 py-3">Deskripsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-300">
                    @foreach ($historis as $i => $h)
                        @php
                            $barang = $h->stok?->barang;
                            $satuan = $barang?->satuan;
                            $isMasuk = $h->jenis === 'masuk';
                            $negatif = ($h->stok_akhir ?? 0) < 0;
                            $rugi = ($h->profit ?? 0) < 0;
                        @endphp
                        <tr class="trow text-xs">
                            <td class="px-3 py-2.5 text-ink-500">{{ $i + 1 }}</td>
                            <td class="px-3 py-2.5 text-ink-700">{{ $h->created_at?->format('d M Y H:i') }}</td>
                            <td class="px-3 py-2.5 font-semibold text-ink-900">{{ $barang->nama ?? '—' }}</td>
                            <td class="px-3 py-2.5">
                                @if ($satuan)
                                    <span class="inline-flex bg-sky-50 text-sky-700 font-semibold px-2 py-0.5 rounded-full border border-sky-200">{{ $satuan->nama }}</span>
                                @else <span class="text-ink-400">—</span> @endif
                            </td>
                            <td class="px-3 py-2.5 text-center">
                                @if ($isMasuk)
                                    <span class="inline-flex bg-sky-50 text-sky-700 font-semibold px-2 py-0.5 rounded-full border border-sky-200">Masuk</span>
                                @else
                                    <span class="inline-flex bg-orange-50 text-orange-700 font-semibold px-2 py-0.5 rounded-full border border-orange-200">Keluar</span>
                                @endif
                            </td>
                            <td class="px-3 py-2.5 text-right text-ink-700">{{ number_format($h->stok_awal ?? 0) }}</td>
                            <td class="px-3 py-2.5 text-right font-semibold {{ $isMasuk ? 'text-emerald-600' : 'text-red-600' }}">
                                {{ $isMasuk ? '+' : '-' }}{{ number_format($h->jumlah) }}
                            </td>
                            <td class="px-3 py-2.5 text-right font-bold {{ $negatif ? 'text-red-600' : 'text-ink-900' }}">{{ number_format($h->stok_akhir ?? 0) }}</td>
                            <td class="px-3 py-2.5 text-right text-ink-700">Rp {{ number_format($h->harga_beli ?? 0, 0, ',', '.') }}</td>
                            <td class="px-3 py-2.5 text-right text-ink-700">Rp {{ number_format($h->harga_jual ?? 0, 0, ',', '.') }}</td>
                            <td class="px-3 py-2.5 text-right font-semibold text-ink-900">Rp {{ number_format($h->total_cash ?? 0, 0, ',', '.') }}</td>
                            <td class="px-3 py-2.5 text-right font-semibold {{ $isMasuk ? 'text-ink-400' : ($rugi ? 'text-red-600' : 'text-emerald-600') }}">
                                {{ $isMasuk ? '—' : 'Rp ' . number_format($h->profit ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="px-3 py-2.5 text-center">
                                @if ($h->is_bonus)
                                    <span class="inline-flex bg-amber-50 text-amber-700 font-semibold px-2 py-0.5 rounded-full border border-amber-200">Bonus</span>
                                @else <span class="text-ink-400">—</span> @endif
                            </td>
                            <td class="px-3 py-2.5 text-ink-600 max-w-xs truncate">{{ $h->deskripsi ?: '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@endsection
