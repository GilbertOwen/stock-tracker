<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'StokApp') — Manajemen Stok</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        brand: { 50:'#fff8ed', 100:'#ffedd5', 400:'#fb923c', 500:'#f97316', 600:'#ea580c', 700:'#c2410c' },
                        ink:   { 900:'#0f172a', 800:'#1e293b', 700:'#334155', 500:'#64748b', 300:'#cbd5e1', 100:'#f1f5f9' }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .nav-link.active { background: rgba(249,115,22,0.15); color: #f97316; border-left: 3px solid #f97316; }
        .nav-link { border-left: 3px solid transparent; transition: all .18s ease; }
        .nav-link:hover { background: rgba(255,255,255,0.06); color: #fb923c; }
        @keyframes slideDown { from { transform: translateY(-12px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        .flash { animation: slideDown .3s ease forwards; }
        .trow { transition: background .12s; }
        .trow:hover { background: #f8fafc; }
    </style>
</head>
<body class="h-full bg-ink-100 text-ink-900 antialiased">
<div class="flex h-screen overflow-hidden">

    <aside class="w-60 flex-shrink-0 bg-ink-900 flex flex-col">
        <div class="px-6 py-5 border-b border-white/10 flex items-center gap-3">
            <div class="w-8 h-8 rounded-md bg-brand-500 flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 10V11"/>
                </svg>
            </div>
            <span class="text-white font-bold text-lg tracking-tight leading-none">StokApp</span>
        </div>

        <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">

            <p class="px-3 pt-2 pb-1 text-xs font-semibold text-ink-500 uppercase tracking-widest">Inventori</p>

            <a href="{{ route('barangs.index') }}"
               class="nav-link {{ request()->routeIs('barangs.*') ? 'active' : 'text-slate-400' }} flex items-center gap-3 px-3 py-2.5 rounded-sm text-sm font-medium">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zm0 9.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 018.25 20.25H6A2.25 2.25 0 013.75 18v-2.25zm9.75-9.75A2.25 2.25 0 0115.75 3.75H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zm0 9.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
                </svg>
                Data Barang
            </a>

            <a href="{{ route('satuans.index') }}"
               class="nav-link {{ request()->routeIs('satuans.*') ? 'active' : 'text-slate-400' }} flex items-center gap-3 px-3 py-2.5 rounded-sm text-sm font-medium">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/>
                </svg>
                Data Satuan
            </a>

            <a href="{{ route('stok.index') }}"
               class="nav-link {{ request()->routeIs('stok.*') ? 'active' : 'text-slate-400' }} flex items-center gap-3 px-3 py-2.5 rounded-sm text-sm font-medium">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 10V11"/>
                </svg>
                Stok Barang
            </a>

            <p class="px-3 pt-4 pb-1 text-xs font-semibold text-ink-500 uppercase tracking-widest">Transaksi</p>

            <a href="#" class="nav-link text-slate-600 flex items-center gap-3 px-3 py-2.5 rounded-sm text-sm font-medium cursor-not-allowed">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Stok Masuk
                <span class="ml-auto text-[10px] bg-white/10 text-slate-500 px-1.5 py-0.5 rounded font-medium">Soon</span>
            </a>

            <a href="#" class="nav-link text-slate-600 flex items-center gap-3 px-3 py-2.5 rounded-sm text-sm font-medium cursor-not-allowed">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Stok Keluar
                <span class="ml-auto text-[10px] bg-white/10 text-slate-500 px-1.5 py-0.5 rounded font-medium">Soon</span>
            </a>

            <a href="#" class="nav-link text-slate-600 flex items-center gap-3 px-3 py-2.5 rounded-sm text-sm font-medium cursor-not-allowed">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Histori Stok
                <span class="ml-auto text-[10px] bg-white/10 text-slate-500 px-1.5 py-0.5 rounded font-medium">Soon</span>
            </a>

            <p class="px-3 pt-4 pb-1 text-xs font-semibold text-ink-500 uppercase tracking-widest">Tools</p>

            <a href="{{ route('import.index') }}"
               class="nav-link {{ request()->routeIs('import.*') ? 'active' : 'text-slate-400' }} flex items-center gap-3 px-3 py-2.5 rounded-sm text-sm font-medium">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                </svg>
                Import Excel
            </a>

        </nav>

        <div class="px-5 py-4 border-t border-white/10">
            <p class="text-xs text-ink-500">v1.0.0 · Stok Barang</p>
        </div>
    </aside>

    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white border-b border-ink-300 px-8 py-4 flex items-center justify-between flex-shrink-0">
            <div>
                <h1 class="text-lg font-bold text-ink-900 leading-tight">@yield('page-title', 'Dashboard')</h1>
                <p class="text-xs text-ink-500 mt-0.5">@yield('page-subtitle', 'Manajemen stok barang')</p>
            </div>
            <div class="flex items-center gap-3">@yield('header-action')</div>
        </header>

        @if (session('success'))
            <div class="flash mx-8 mt-5 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium px-4 py-3 rounded-lg" id="flash-msg">
                <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                </svg>
                {{ session('success') }}
                <button onclick="document.getElementById('flash-msg').remove()" class="ml-auto text-emerald-400 hover:text-emerald-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="flash mx-8 mt-5 flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 text-sm font-medium px-4 py-3 rounded-lg" id="flash-msg-error">
                <svg class="w-4 h-4 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                </svg>
                {{ session('error') }}
                <button onclick="document.getElementById('flash-msg-error').remove()" class="ml-auto text-red-400 hover:text-red-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        <main class="flex-1 overflow-y-auto px-8 py-6">@yield('content')</main>
    </div>
</div>
</body>
</html>