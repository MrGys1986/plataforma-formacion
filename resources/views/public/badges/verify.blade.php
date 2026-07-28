<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verificación de insignia</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 px-4 py-12 text-slate-900">
    <main class="mx-auto max-w-2xl rounded-3xl border border-slate-200 bg-white p-8 text-center shadow-xl">
        <div class="mx-auto flex h-32 w-32 items-center justify-center rounded-full bg-gradient-to-br from-violet-600 to-blue-600 p-3 shadow-xl">
            <div class="flex h-full w-full items-center justify-center rounded-full border-4 border-white/80 text-5xl text-white">★</div>
        </div>
        <p class="mt-6 text-xs font-bold uppercase tracking-[0.2em] text-emerald-600">Insignia válida y verificada</p>
        <h1 class="mt-3 text-3xl font-bold">{{ $microcredential->name }}</h1>
        <p class="mt-3 text-slate-600">{{ $microcredential->description }}</p>
        <div class="mt-8 grid gap-5 border-t border-slate-200 pt-6 text-left sm:grid-cols-2">
            <div>
                <p class="text-xs font-bold uppercase text-slate-400">Titular</p>
                <p class="mt-1 font-semibold">{{ $microcredential->user?->name }}</p>
            </div>
            <div>
                <p class="text-xs font-bold uppercase text-slate-400">Emisión</p>
                <p class="mt-1 font-semibold">{{ $microcredential->issued_at?->format('d/m/Y') }}</p>
            </div>
            <div class="sm:col-span-2">
                <p class="text-xs font-bold uppercase text-slate-400">Identificador</p>
                <p class="mt-1 break-all font-mono text-sm">{{ $microcredential->public_id }}</p>
            </div>
        </div>
    </main>
</body>
</html>
