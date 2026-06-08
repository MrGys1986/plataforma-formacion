<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Plataforma Institucional de Formación</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-white">
    <main class="mx-auto flex min-h-screen max-w-6xl items-center px-6 py-16">
        <div class="max-w-3xl">
            <p class="mb-4 text-sm font-semibold uppercase tracking-[0.25em] text-blue-300">Universidad</p>
            <h1 class="text-4xl font-bold leading-tight md:text-6xl">
                Formación, evidencias y microcredenciales en una sola plataforma
            </h1>
            <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-300">
                Administra actividades académicas, rutas de aprendizaje, inscripciones, evaluaciones,
                constancias y trazabilidad institucional.
            </p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a class="rounded-lg bg-blue-500 px-5 py-3 font-semibold text-white hover:bg-blue-400" href="{{ route('login') }}">
                    Acceso institucional
                </a>
                <a class="rounded-lg border border-slate-600 px-5 py-3 font-semibold hover:border-slate-400" href="{{ route('filament.admin.auth.login') }}">
                    Administración
                </a>
            </div>
        </div>
    </main>
</body>
</html>
