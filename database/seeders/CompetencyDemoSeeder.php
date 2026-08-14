<?php
namespace Database\Seeders;
use App\Models\Area;
use App\Models\Competency;
use App\Models\User;
use Illuminate\Database\Seeder;

class CompetencyDemoSeeder extends Seeder
{
    public function run(): void
    {
        $area = Area::query()->where('name', 'Recursos Humanos')->first() ?? Area::query()->first();
        $creator = User::query()->role('Recursos Humanos')->first();
        $definitions = [
            ['name' => 'Gestión efectiva de procesos', 'slug' => 'gestion-efectiva-procesos', 'description' => 'Analiza, documenta y mejora procesos institucionales.', 'objective' => 'Optimizar procesos con enfoque en resultados y servicio.', 'completion_criteria' => 'Acreditar el proyecto de mejora con calificación mínima de 70.'],
            ['name' => 'Comunicación y colaboración', 'slug' => 'comunicacion-colaboracion', 'description' => 'Comunica información con claridad y colabora entre áreas.', 'objective' => 'Fortalecer la coordinación y el trabajo institucional.', 'completion_criteria' => 'Completar las actividades y demostrar aplicación en un caso práctico.'],
            ['name' => 'Cultura de seguridad y calidad', 'slug' => 'cultura-seguridad-calidad', 'description' => 'Aplica principios de seguridad, calidad y mejora continua.', 'objective' => 'Integrar prácticas preventivas y criterios de calidad en el trabajo.', 'completion_criteria' => 'Obtener al menos 80 puntos en la evaluación integradora.'],
        ];
        $users = User::query()->whereIn('email', ['ana.participante@formacion.test','carlos.participante@formacion.test','personal@formacion.test'])->get();
        foreach ($definitions as $index => $definition) {
            $competency = Competency::query()->updateOrCreate(['slug' => $definition['slug']], $definition + ['area_id' => $area?->id, 'created_by' => $creator?->id, 'status' => 'activo']);
            foreach ($users as $userIndex => $user) {
                $completed = ($index + $userIndex) % 3 === 0;
                $competency->users()->syncWithoutDetaching([$user->id => ['status' => $completed ? 'completado' : 'en_progreso', 'progress_percentage' => $completed ? 100 : 50, 'unlocked_at' => now()->subWeeks(3), 'completed_at' => $completed ? now()->subDays(2) : null]]);
            }
        }
    }
}
