@extends('layouts.participant')

@section('content')
    <x-portal-page
        title="Bienvenido"
        :description="auth()->user()->hasRole('Profesor') ? 'Consulta tus cursos asignados y el seguimiento de tus participantes.' : 'Consulta tu formación, evidencias, evaluaciones y constancias desde un solo lugar.'"
    >
        @if(auth()->user()->hasRole('Profesor'))
            <div class="grid gap-4 sm:grid-cols-3">
                <div class="rounded-xl border border-blue-100 bg-blue-50 p-5">
                    <p class="text-sm font-medium text-blue-700">Cursos asignados</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ $assignedCourses }}</p>
                </div>
                <div class="rounded-xl border border-emerald-100 bg-emerald-50 p-5">
                    <p class="text-sm font-medium text-emerald-700">Cursos activos</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ $activeCourses }}</p>
                </div>
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-5">
                    <p class="text-sm font-medium text-slate-600">Participantes</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ $participants }}</p>
                </div>
            </div>
            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                <a class="rounded-xl border border-blue-100 bg-white p-5 transition hover:border-blue-300 hover:shadow-md" href="{{ route('participant.my-courses.index') }}">
                    <p class="text-sm font-medium text-blue-700">Mi formación</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ $learningCourses }}</p>
                    <p class="mt-2 text-sm text-slate-500">Cursos en los que participas como estudiante.</p>
                </a>
                <a class="rounded-xl border border-violet-100 bg-white p-5 transition hover:border-violet-300 hover:shadow-md" href="{{ route('participant.professor.profile') }}">
                    <p class="text-sm font-medium text-violet-700">Mi perfil docente</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ $ownCertificates }}</p>
                    <p class="mt-2 text-sm text-slate-500">Constancias emitidas a tu nombre.</p>
                </a>
            </div>
        @elseif(auth()->user()->hasRole('Alumno'))
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-xl border border-blue-100 bg-blue-50 p-5">
                    <p class="text-sm font-medium text-blue-700">Cursos inscritos</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ $enrolledCourses }}</p>
                </div>
                <div class="rounded-xl border border-cyan-100 bg-cyan-50 p-5">
                    <p class="text-sm font-medium text-cyan-700">En progreso</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ $activeCourses }}</p>
                </div>
                <div class="rounded-xl border border-emerald-100 bg-emerald-50 p-5">
                    <p class="text-sm font-medium text-emerald-700">Completados</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ $completedCourses }}</p>
                </div>
                <div class="rounded-xl border border-violet-100 bg-violet-50 p-5">
                    <p class="text-sm font-medium text-violet-700">Constancias</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ $issuedCertificates }}</p>
                </div>
            </div>

            <div class="mt-6 rounded-xl border border-slate-200 bg-slate-50/70 p-5">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h2 class="font-semibold text-slate-900">Actividad reciente</h2>
                        <p class="mt-1 text-sm text-slate-500">Tus inscripciones más recientes.</p>
                    </div>
                    <a class="text-sm font-semibold text-blue-700 hover:text-blue-600" href="{{ route('participant.my-courses.index') }}">Ver mis cursos</a>
                </div>

                <div class="mt-4 space-y-3">
                    @forelse($recentEnrollments as $enrollment)
                        <div class="flex flex-wrap items-center justify-between gap-3 rounded-lg border border-slate-200 bg-white px-4 py-3">
                            <div>
                                <p class="font-medium text-slate-900">{{ $enrollment->activity?->name ?? 'Curso no disponible' }}</p>
                                <p class="mt-1 text-xs text-slate-500">{{ $enrollment->created_at?->format('d/m/Y') }}</p>
                            </div>
                            <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                                {{ ucfirst(str_replace('_', ' ', $enrollment->status)) }}
                            </span>
                        </div>
                    @empty
                        <div class="rounded-lg border border-dashed border-slate-300 bg-white px-4 py-8 text-center">
                            <p class="font-medium text-slate-700">Todavía no tienes cursos inscritos.</p>
                            <a class="mt-2 inline-flex text-sm font-semibold text-blue-700 hover:text-blue-600" href="{{ route('participant.catalog.index') }}">Explorar catálogo</a>
                        </div>
                    @endforelse
                </div>
            </div>
        @else
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-xl border border-blue-100 bg-blue-50 p-5">
                    <p class="text-sm font-medium text-blue-700">Inscripciones</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ $enrolledCourses }}</p>
                </div>
                <div class="rounded-xl border border-cyan-100 bg-cyan-50 p-5">
                    <p class="text-sm font-medium text-cyan-700">Cursos activos</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ $activeCourses }}</p>
                </div>
                <div class="rounded-xl border border-amber-100 bg-amber-50 p-5">
                    <p class="text-sm font-medium text-amber-700">Pagos pendientes</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ $pendingPayments }}</p>
                </div>
                <div class="rounded-xl border border-violet-100 bg-violet-50 p-5">
                    <p class="text-sm font-medium text-violet-700">Constancias</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ $issuedCertificates }}</p>
                </div>
            </div>

            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                <a class="rounded-xl border border-blue-100 bg-white p-5 transition hover:border-blue-300 hover:shadow-md" href="{{ route('participant.catalog.index') }}">
                    <p class="font-semibold text-blue-700">Explorar oferta externa</p>
                    <p class="mt-2 text-sm text-slate-500">Consulta cursos, talleres y actividades disponibles.</p>
                </a>
                <a class="rounded-xl border border-violet-100 bg-white p-5 transition hover:border-violet-300 hover:shadow-md" href="{{ route('participant.my-courses.index') }}">
                    <p class="font-semibold text-violet-700">Mi formación</p>
                    <p class="mt-2 text-sm text-slate-500">Revisa tus inscripciones, avance y entregas.</p>
                </a>
            </div>

            <div class="mt-6 rounded-xl border border-slate-200 bg-slate-50/70 p-5">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h2 class="font-semibold text-slate-900">Actividad reciente</h2>
                        <p class="mt-1 text-sm text-slate-500">Tus inscripciones más recientes.</p>
                    </div>
                    <a class="text-sm font-semibold text-blue-700 hover:text-blue-600" href="{{ route('participant.my-courses.index') }}">Ver formación</a>
                </div>
                <div class="mt-4 space-y-3">
                    @forelse($recentEnrollments as $enrollment)
                        <div class="flex flex-wrap items-center justify-between gap-3 rounded-lg border border-slate-200 bg-white px-4 py-3">
                            <p class="font-medium text-slate-900">{{ $enrollment->activity?->name ?? 'Curso no disponible' }}</p>
                            <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                                {{ ucfirst(str_replace('_', ' ', $enrollment->status)) }}
                            </span>
                        </div>
                    @empty
                        <p class="rounded-lg border border-dashed border-slate-300 bg-white px-4 py-8 text-center text-sm text-slate-500">Todavía no tienes inscripciones.</p>
                    @endforelse
                </div>
            </div>
        @endif
    </x-portal-page>
@endsection
