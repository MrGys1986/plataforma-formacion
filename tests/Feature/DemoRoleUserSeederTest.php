<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AreaSeeder;
use Database\Seeders\DemoRoleUserSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DemoRoleUserSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_each_role_has_an_active_account_that_can_log_in(): void
    {
        $this->seed([
            RoleSeeder::class,
            AreaSeeder::class,
            DemoRoleUserSeeder::class,
        ]);

        foreach ($this->roleAccounts() as [$role, $email, $dashboardRoute]) {
            $user = User::query()->where('email', $email)->firstOrFail();

            $this->assertSame('activo', $user->status);
            $this->assertTrue($user->hasRole($role));
            $this->assertCount(1, $user->roles);
            $this->assertTrue(Hash::check(DemoRoleUserSeeder::DEFAULT_PASSWORD, $user->password));

            $this->post(route('login.store'), [
                'email' => $email,
                'password' => DemoRoleUserSeeder::DEFAULT_PASSWORD,
            ])->assertRedirect(route($dashboardRoute));

            $this->assertAuthenticatedAs($user);
            $this->post(route('logout'));
            $this->assertGuest();
        }
    }

    /**
     * @return array<string, array{string, string, string}>
     */
    private function roleAccounts(): array
    {
        return [
            'superadministrador' => ['Superadministrador', 'admin@formacion.test', 'filament.admin.pages.dashboard'],
            'recursos humanos' => ['Recursos Humanos', 'rh@formacion.test', 'filament.admin.pages.dashboard'],
            'calidad académica' => ['Calidad Academica', 'calidad@formacion.test', 'filament.admin.pages.dashboard'],
            'educación continua' => ['Educacion Continua', 'educacion.continua@formacion.test', 'filament.admin.pages.dashboard'],
            'personal' => ['Personal', 'personal@formacion.test', 'filament.admin.pages.dashboard'],
            'evaluador' => ['Evaluador', 'evaluador@formacion.test', 'filament.admin.pages.dashboard'],
            'profesor' => ['Profesor', 'profesor@formacion.test', 'dashboard'],
            'alumno' => ['Alumno', 'alumno@formacion.test', 'dashboard'],
            'externo' => ['Externo', 'externo@formacion.test', 'dashboard'],
            'responsable de área' => ['Responsable Area', 'responsable.area@formacion.test', 'filament.admin.pages.dashboard'],
        ];
    }
}
