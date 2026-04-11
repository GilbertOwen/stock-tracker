@extends('layouts.app')

@section('title', 'Data Satuan')
@section('page-title', 'Data Satuan')
@section('page-subtitle', 'Kelola satuan yang digunakan untuk barang')

@section('header-action')
    <a href="{{ route('satuans.create') }}"
       class="inline-flex items-center gap-2 bg-brand-500 hover:bg-brand-600 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition-colors shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
        </svg>
        Tambah Satuan
    </a>
@endsection

@section('content')

<div class="max-w-2xl">

    {{-- Stats --}}
    <div class="grid grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-ink-300 px-5 py-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-lg bg-brand-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-ink-900">{{ $satuans->count() }}</p>
                <p class="text-xs text-ink-500 font-medium">Total Satuan</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-ink-300 px-5 py-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-lg bg-sky-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-sky-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 10V11"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-ink-900">{{ $satuans->sum('barangs_count') }}</p>
                <p class="text-xs text-ink-500 font-medium">Total Barang Terdaftar</p>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-ink-300 overflow-hidden">
        <div class="px-6 py-4 border-b border-ink-300 flex items-center justify-between">
            <h2 class="text-sm font-semibold text-ink-700">Daftar Satuan</h2>
            <span class="text-xs text-ink-500 bg-ink-100 px-2.5 py-1 rounded-full font-medium">
                {{ $satuans->count() }} satuan
            </span>
        </div>

        @if ($satuans->isEmpty())
            <div class="py-16 text-center">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-ink-100 mb-3">
                    <svg class="w-6 h-6 text-ink-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75m-7.5 5.25h13.5A2.25 2.25 0 0021 20.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664"/>
                    </svg>
                </div>
                <p class="text-ink-700 font-semibold text-sm">Belum ada satuan</p>
                <p class="text-xs text-ink-500 mt-1">Tambah satuan seperti pcs, kg, dus, dll.</p>
            </div>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-ink-100 text-xs font-semibold text-ink-500 uppercase tracking-wider">
                        <th class="text-left px-6 py-3 w-10">#</th>
                        <th class="text-left px-4 py-3">Nama Satuan</th>
                        <th class="text-center px-4 py-3">Jumlah Barang</th>
                        <th class="text-center px-4 py-3 w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-300">
                    @foreach ($satuans as $i => $satuan)
                        <tr class="trow">
                            <td class="px-6 py-3.5 text-ink-500 text-xs font-medium">{{ $i + 1 }}</td>
                            <td class="px-4 py-3.5">
                                <span class="font-semibold text-ink-900">{{ $satuan->nama }}</span>
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                @if ($satuan->barangs_count > 0)
                                    <span class="inline-flex items-center bg-sky-50 text-sky-700 text-xs font-semibold px-2.5 py-1 rounded-full border border-sky-200">
                                        {{ $satuan->barangs_count }} barang
                                    </span>
                                @else
                                    <span class="text-ink-400 text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('satuans.edit', $satuan->id_satuan) }}"
                                       class="inline-flex items-center gap-1.5 text-xs font-semibold text-ink-700 hover:text-brand-600 border border-ink-300 hover:border-brand-400 px-3 py-1.5 rounded-lg transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                                        </svg>
                                        Edit
                                    </a>

                                    <form action="{{ route('satuans.destroy', $satuan->id_satuan) }}"
                                          method="POST"
                                          onsubmit="return confirm('Hapus satuan {{ addslashes($satuan->nama) }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center gap-1.5 text-xs font-semibold text-red-600 hover:text-white hover:bg-red-500 border border-red-200 hover:border-red-500 px-3 py-1.5 rounded-lg transition-colors
                                                       {{ $satuan->barangs_count > 0 ? 'opacity-40 cursor-not-allowed pointer-events-none' : '' }}"
                                                {{ $satuan->barangs_count > 0 ? 'disabled' : '' }}
                                                title="{{ $satuan->barangs_count > 0 ? 'Tidak bisa dihapus, masih dipakai' : 'Hapus satuan' }}">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916"/>
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

</div>

@endsection