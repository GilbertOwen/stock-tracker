@extends('layouts.app')

@section('title', 'Import Excel')
@section('page-title', 'Import dari Excel')
@section('page-subtitle', 'Upload file Excel untuk mengisi data barang, satuan, dan stok sekaligus')

@section('content')

<div class="max-w-xl">

    {{-- Info box --}}
    <div class="bg-sky-50 border border-sky-200 rounded-xl px-5 py-4 mb-5 flex gap-3">
        <svg class="w-5 h-5 text-sky-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
        </svg>
        <div class="text-sm text-sky-800">
            <p class="font-semibold mb-1">Format Excel yang didukung</p>
            <ul class="space-y-0.5 text-sky-700 text-xs">
                <li>✓ File <span class="font-mono bg-sky-100 px-1 rounded">.xlsx</span> atau <span class="font-mono bg-sky-100 px-1 rounded">.xls</span></li>
                <li>✓ Header kolom harus ada: <span class="font-semibold">Nama Barang, Satuan, Stok, Harga</span></li>
                <li>✓ Data boleh ada di sheet mana saja — sistem akan mendeteksi otomatis</li>
                <li>✓ Jika barang sudah ada, data akan diperbarui (tidak duplikat)</li>
                <li>✓ Harga di Excel dianggap dalam ribuan rupiah (80 = Rp 80.000)</li>
            </ul>
        </div>
    </div>

    {{-- Upload card --}}
    <div class="bg-white rounded-xl border border-ink-300 overflow-hidden">

        <div class="px-6 py-4 bg-ink-900 flex items-center gap-3">
            <div class="w-8 h-8 rounded-md bg-emerald-500/20 border border-emerald-500/30 flex items-center justify-center">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                </svg>
            </div>
            <div>
                <h2 class="text-sm font-bold text-white">Upload File Excel</h2>
                <p class="text-xs text-slate-400">Maks. 5MB · Format .xlsx atau .xls</p>
            </div>
        </div>

        <form action="{{ route('import.store') }}" method="POST" enctype="multipart/form-data" class="px-6 py-6 space-y-5">
            @csrf

            {{-- Drop zone --}}
            <div>
                <label for="file" class="block text-xs font-semibold text-ink-700 mb-2 uppercase tracking-wide">
                    File Excel <span class="text-red-500">*</span>
                </label>

                <label for="file"
                       id="drop-zone"
                       class="flex flex-col items-center justify-center w-full h-36 border-2 border-dashed rounded-xl cursor-pointer transition-colors
                              {{ $errors->has('file') ? 'border-red-400 bg-red-50' : 'border-ink-300 bg-ink-100 hover:border-brand-400 hover:bg-brand-50' }}">
                    <div class="flex flex-col items-center gap-2 text-center px-4" id="drop-label">
                        <svg class="w-8 h-8 text-ink-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                        </svg>
                        <p class="text-sm font-medium text-ink-600">Klik untuk pilih file</p>
                        <p class="text-xs text-ink-400">.xlsx atau .xls — maks 5MB</p>
                    </div>
                    <input id="file" name="file" type="file" accept=".xlsx,.xls" class="hidden" />
                </label>

                @error('file')
                    <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Preview nama file --}}
            <div id="file-preview" class="hidden items-center gap-3 bg-emerald-50 border border-emerald-200 rounded-lg px-4 py-3">
                <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                </svg>
                <span id="file-name" class="text-sm font-semibold text-emerald-800 truncate flex-1">—</span>
                <span id="file-size" class="text-xs text-emerald-600 flex-shrink-0">—</span>
            </div>

            {{-- Warning konversi harga --}}
            <div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 flex gap-2.5 items-start">
                <svg class="w-4 h-4 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                </svg>
                <p class="text-xs text-amber-800">
                    <span class="font-semibold">Perhatian konversi harga:</span>
                    Nilai harga di Excel (contoh: <span class="font-mono">80</span>) akan disimpan sebagai
                    <span class="font-mono">Rp 80.000</span> (dikali 1.000).
                    Jika harga di Excel sudah dalam rupiah penuh, beri tahu saya agar saya sesuaikan kodenya.
                </p>
            </div>

            <div class="border-t border-ink-200 pt-4 flex items-center justify-end gap-3">
                <a href="{{ route('barangs.index') }}"
                   class="px-5 py-2.5 text-sm font-semibold text-ink-600 hover:text-ink-900 border border-ink-300 hover:border-ink-400 rounded-lg transition-colors">
                    Batal
                </a>
                <button type="submit" id="btn-submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                    </svg>
                    <span id="btn-label">Proses Import</span>
                </button>
            </div>
        </form>
    </div>

    {{-- Contoh format tabel --}}
    <div class="mt-5 bg-white rounded-xl border border-ink-300 overflow-hidden">
        <div class="px-6 py-3 border-b border-ink-200">
            <h3 class="text-xs font-semibold text-ink-600 uppercase tracking-wide">Contoh format Excel yang diterima</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="bg-ink-100 text-ink-500 font-semibold uppercase tracking-wider">
                        <th class="px-4 py-2 text-left">No</th>
                        <th class="px-4 py-2 text-left">Nama Barang</th>
                        <th class="px-4 py-2 text-left">Satuan</th>
                        <th class="px-4 py-2 text-left">Stok</th>
                        <th class="px-4 py-2 text-left">Harga</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-200 text-ink-700">
                    <tr><td class="px-4 py-2">1</td><td class="px-4 py-2">Beras 5 kg merk Sawah</td><td class="px-4 py-2">sak</td><td class="px-4 py-2">24</td><td class="px-4 py-2">80</td></tr>
                    <tr class="bg-ink-50"><td class="px-4 py-2">2</td><td class="px-4 py-2">Gula merk Rosebrand</td><td class="px-4 py-2">kg</td><td class="px-4 py-2">25</td><td class="px-4 py-2">18</td></tr>
                    <tr><td class="px-4 py-2">3</td><td class="px-4 py-2">Minyak goreng Barco 2 L</td><td class="px-4 py-2">pcs</td><td class="px-4 py-2">4</td><td class="px-4 py-2">115</td></tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
const fileInput   = document.getElementById('file');
const preview     = document.getElementById('file-preview');
const fileName    = document.getElementById('file-name');
const fileSize    = document.getElementById('file-size');
const btnSubmit   = document.getElementById('btn-submit');
const btnLabel    = document.getElementById('btn-label');
const dropZone    = document.getElementById('drop-zone');

fileInput.addEventListener('change', function () {
    if (this.files.length > 0) {
        const f = this.files[0];
        fileName.textContent = f.name;
        fileSize.textContent = (f.size / 1024).toFixed(1) + ' KB';
        preview.classList.remove('hidden');
        preview.classList.add('flex');
        dropZone.classList.add('border-brand-400', 'bg-brand-50');
        dropZone.classList.remove('border-ink-300', 'bg-ink-100');
    }
});

document.querySelector('form').addEventListener('submit', function () {
    btnSubmit.disabled = true;
    btnLabel.textContent = 'Memproses...';
});
</script>

@endsection