<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Plataforma Institucional de Formación')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <header class="bg-slate-900 px-6 py-4 text-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between">
            <div>
                <p class="text-xs uppercase tracking-widest text-slate-300">Universidad</p>
                <p class="font-semibold">@yield('portal-name', 'Plataforma de Formación')</p>
            </div>
            @auth
                <span class="text-sm text-slate-200">{{ auth()->user()->name }}</span>
            @endauth
        </div>
    </header>

    <div class="mx-auto grid min-h-[calc(100vh-132px)] max-w-7xl gap-6 px-6 py-6 md:grid-cols-[240px_1fr]">
        <aside class="rounded-xl bg-white p-4 shadow-sm">
            <nav class="space-y-2 text-sm">
                @yield('navigation')
            </nav>
        </aside>

        <main>
            @yield('content')
        </main>
    </div>

    <footer class="border-t bg-white px-6 py-4 text-center text-sm text-slate-500">
        Plataforma Institucional de Formación, Evidencias y Microcredenciales
    </footer>
</body>
</html>
