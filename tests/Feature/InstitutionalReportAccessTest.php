<?php

namespace Tests\Feature;

use App\Filament\Pages\InstitutionalReports;
use App\Models\ActivityType;
use App\Models\Area;
use App\Models\Enrollment;
use App\Models\Evidence;
use App\Models\LearningPath;
use App\Models\Microcredential;
use App\Models\Payment;
use App\Models\Survey;
use App\Models\SurveyResponse;
use App\Models\TrainingProgram;
use App\Models\User;
use App\Services\Reports\InstitutionalReportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class InstitutionalReportAccessTest extends TestCase
{
    use RefreshDatabase;

    private ActivityType $type;

    private Survey $survey;

    private int $sequence = 0;

    protected function setUp(): void
    {
        parent::setUp();

        foreach ([...InstitutionalReportService::OVERVIEW_ROLES, 'Profesor', 'Alumno', 'Externo', 'Personal', 'Evaluador'] as $role) {
            Role::findOrCreate($role);
        }

        $this->type = ActivityType::create(['name' => 'Curso', 'status' => 'activo']);
        $this->survey = Survey::create(['name' => 'Encuesta de prueba', 'is_general' => true]);
    }

    #[DataProvider('reportRoles')]
    public function test_report_totals_and_programs_respect_the_readers_scope(string $role, array $visibleNames, int $activeUsers, ?int $payments): void
    {
        $areaA = $this->area('Área A');
        $areaB = $this->area('Área B');
        $this->training($areaA, false, 'Interno A');
        $this->training($areaB, false, 'Interno B');
        $this->training($areaA, true, 'Externo A');
        $this->training($areaB, true, 'Externo B');
        $this->training(null, false, 'Sin asignar');
        $reader = $this->reader($role, $role === 'Responsable Area' ? $areaA : null);

        $data = app(InstitutionalReportService::class)->institutionalOverview($reader);

        $this->assertSame($activeUsers, $data['metrics']['Usuarios activos']);
        foreach ($data['metrics'] as $label => $count) {
            if ($label !== 'Usuarios activos') {
                $this->assertSame(count($visibleNames), $count, $label);
            }
        }
        $this->assertEqualsCanonicalizing($visibleNames, $data['completionByProgram']->pluck('name')->all());
        foreach ($data['completionByProgram'] as $program) {
            $this->assertSame(1, $program->editions_count);
            $this->assertSame(1, $program->completed_enrollments_count);
        }
        $payments === null
            ? $this->assertNull($data['validatedPayments'])
            : $this->assertEquals($payments, $data['validatedPayments']);

        $response = $this->actingAs($reader)->get(InstitutionalReports::getUrl())->assertOk();
        foreach (['Interno A', 'Interno B', 'Externo A', 'Externo B', 'Sin asignar'] as $name) {
            in_array($name, $visibleNames, true) ? $response->assertSee($name) : $response->assertDontSee($name);
        }
        $payments === null ? $response->assertDontSee('Pagos validados') : $response->assertSee('Pagos validados');
    }

    public function test_a_programs_relation_counts_do_not_include_editions_from_another_area_or_offer(): void
    {
        $areaA = $this->area('Área A');
        $areaB = $this->area('Área B');
        [$program, $activity, $participant] = $this->training($areaA, false, 'Programa compartido');
        foreach ([['area_id' => $areaB->id], ['area_id' => $areaA->id, 'is_external' => true]] as $index => $attributes) {
            $edition = $program->activities()->create([
                ...$attributes, 'name' => 'Otra edición '.$index, 'slug' => 'otra-edicion-'.$index, 'status' => 'publicado',
            ]);
            Enrollment::create([
                'activity_id' => $edition->id, 'user_id' => $participant->id, 'status' => 'aprobada',
                'completion_status' => 'completado', 'final_score' => 90,
            ]);
        }

        $areaData = app(InstitutionalReportService::class)->institutionalOverview($this->reader('Responsable Area', $areaA));
        $this->assertSame(2, $areaData['completionByProgram']->sole()->editions_count);
        $this->assertSame(2, $areaData['completionByProgram']->sole()->completed_enrollments_count);

        $rhData = app(InstitutionalReportService::class)->institutionalOverview($this->reader('Recursos Humanos'));
        $this->assertSame(2, $rhData['completionByProgram']->sole()->editions_count);
        $this->assertSame(2, $rhData['completionByProgram']->sole()->completed_enrollments_count);
    }

    public function test_an_area_manager_without_an_area_cannot_see_unassigned_records(): void
    {
        $this->training(null, false, 'Programa sin responsable');
        $reader = $this->reader('Responsable Area');
        $data = app(InstitutionalReportService::class)->institutionalOverview($reader);

        foreach ($data['metrics'] as $count) {
            $this->assertSame(0, $count);
        }
        $this->assertTrue($data['completionByProgram']->isEmpty());
        $this->assertNull($data['validatedPayments']);
        $this->actingAs($reader)->get(InstitutionalReports::getUrl())->assertOk()->assertDontSee('Programa sin responsable');
        $this->get(route('area-manager.reports.index'))->assertForbidden();
    }

    public function test_a_participant_role_does_not_grant_financial_reporting_to_an_area_manager(): void
    {
        $areaA = $this->area('Área A');
        $areaB = $this->area('Área B');
        [$program, $activity] = $this->training($areaB, true, 'Curso externo del área B');
        $reader = $this->reader('Responsable Area', $areaA);
        $reader->assignRole('Externo');
        Payment::create(['user_id' => $reader->id, 'activity_id' => $activity->id, 'amount' => 1000, 'status' => 'validado']);

        $data = app(InstitutionalReportService::class)->institutionalOverview($reader);
        $this->assertNull($data['validatedPayments']);
        $this->actingAs($reader)->get(InstitutionalReports::getUrl())->assertOk()->assertDontSee('Pagos validados');
    }

    public function test_an_administrators_assigned_area_does_not_restrict_their_institutional_access(): void
    {
        $areaA = $this->area('Área A');
        $areaB = $this->area('Área B');
        $this->training($areaB, false, 'Programa de otra área');
        $reader = $this->reader('Responsable Area', $areaA);
        $reader->assignRole('Superadministrador');

        $data = app(InstitutionalReportService::class)->institutionalOverview($reader);
        $this->assertSame(1, $data['metrics']['Programas formativos']);
        $this->assertSame('Programa de otra área', $data['completionByProgram']->sole()->name);
        $this->assertEquals(100, $data['validatedPayments']);
    }

    public function test_area_badges_are_scoped_by_the_source_area_instead_of_the_owners_area(): void
    {
        $areaA = $this->area('Área A');
        $areaB = $this->area('Área B');
        [$program, $activity, $participant] = $this->training($areaA, false, 'Curso del área A');
        $participant->update(['area_id' => $areaB->id]);
        foreach ([$areaA, $areaB] as $area) {
            $path = LearningPath::create(['area_id' => $area->id, 'name' => $area->name, 'slug' => 'ruta-'.$area->id]);
            Microcredential::create([
                'user_id' => $participant->id, 'learning_path_id' => $path->id,
                'name' => 'Insignia '.$area->name, 'status' => 'validada',
            ]);
        }

        $data = app(InstitutionalReportService::class)->institutionalOverview($this->reader('Responsable Area', $areaA));
        $this->assertSame(2, $data['metrics']['Microcredenciales emitidas']);
    }

    #[DataProvider('nonReportRoles')]
    public function test_a_role_without_report_access_cannot_open_the_page_or_read_the_service(string $role): void
    {
        $reader = $this->reader($role);
        $this->actingAs($reader)->get(InstitutionalReports::getUrl())->assertForbidden();
        $this->expectException(HttpException::class);
        app(InstitutionalReportService::class)->institutionalOverview($reader);
    }

    public static function reportRoles(): array
    {
        $all = ['Interno A', 'Interno B', 'Externo A', 'Externo B', 'Sin asignar'];

        return [
            'administrator' => ['Superadministrador', $all, 6, 1500],
            'academic quality' => ['Calidad Academica', $all, 6, null],
            'human resources' => ['Recursos Humanos', ['Interno A', 'Interno B', 'Sin asignar'], 4, null],
            'continuing education' => ['Educacion Continua', ['Externo A', 'Externo B'], 2, 700],
            'area manager' => ['Responsable Area', ['Interno A', 'Externo A'], 3, null],
        ];
    }

    public static function nonReportRoles(): array
    {
        return array_map(fn (string $role): array => [$role], ['Profesor', 'Alumno', 'Externo', 'Personal', 'Evaluador']);
    }

    private function reader(string $role, ?Area $area = null): User
    {
        $user = User::factory()->create(['status' => 'activo', 'user_type' => 'interno', 'area_id' => $area?->id]);
        $user->assignRole($role);

        return $user;
    }

    private function area(string $name): Area
    {
        return Area::create(['name' => $name, 'area_type' => 'academica']);
    }

    private function training(?Area $area, bool $external, string $name): array
    {
        $index = ++$this->sequence;
        $participant = User::factory()->create([
            'status' => 'activo', 'area_id' => $area?->id, 'user_type' => $external ? 'externo' : 'interno',
        ]);
        $program = TrainingProgram::create([
            'activity_type_id' => $this->type->id, 'area_id' => $area?->id, 'name' => $name,
            'slug' => 'programa-'.$index, 'is_external' => $external,
        ]);
        $activity = $program->activities()->create(['name' => $name, 'slug' => 'actividad-'.$index, 'status' => 'publicado']);
        $enrollment = Enrollment::create([
            'user_id' => $participant->id, 'activity_id' => $activity->id, 'status' => 'aprobada',
            'completion_status' => 'completado', 'final_score' => 90,
        ]);
        Evidence::create(['user_id' => $participant->id, 'activity_id' => $activity->id, 'title' => 'Evidencia '.$index]);
        SurveyResponse::create([
            'survey_id' => $this->survey->id, 'user_id' => $participant->id,
            'activity_id' => $activity->id, 'enrollment_id' => $enrollment->id, 'answers' => [],
        ]);
        Microcredential::create([
            'user_id' => $participant->id, 'activity_id' => $activity->id, 'name' => 'Insignia '.$index, 'status' => 'validada',
        ]);
        Payment::create([
            'user_id' => $participant->id, 'activity_id' => $activity->id,
            'enrollment_id' => $enrollment->id, 'amount' => $index * 100, 'status' => 'validado',
        ]);

        return [$program, $activity, $participant];
    }
}
