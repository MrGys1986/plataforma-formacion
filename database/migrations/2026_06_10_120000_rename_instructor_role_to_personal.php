<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    public function up(): void
    {
        $this->renameRole('Instructor', 'Personal');
    }

    public function down(): void
    {
        $this->renameRole('Personal', 'Instructor');
    }

    private function renameRole(string $currentName, string $newName): void
    {
        $tables = config('permission.table_names');
        $columns = config('permission.column_names');
        $roleKey = $columns['role_pivot_key'] ?? 'role_id';

        DB::transaction(function () use ($tables, $roleKey, $currentName, $newName): void {
            $currentRole = DB::table($tables['roles'])
                ->where('name', $currentName)
                ->where('guard_name', 'web')
                ->first();

            if (! $currentRole) {
                DB::table($tables['roles'])->updateOrInsert(
                    [
                        'name' => $newName,
                        'guard_name' => 'web',
                    ],
                    [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                );

                return;
            }

            $newRole = DB::table($tables['roles'])
                ->where('name', $newName)
                ->where('guard_name', 'web')
                ->first();

            if (! $newRole) {
                DB::table($tables['roles'])
                    ->where('id', $currentRole->id)
                    ->update([
                        'name' => $newName,
                        'updated_at' => now(),
                    ]);

                return;
            }

            foreach (DB::table($tables['model_has_roles'])->where($roleKey, $currentRole->id)->get() as $assignment) {
                $values = (array) $assignment;
                $values[$roleKey] = $newRole->id;
                DB::table($tables['model_has_roles'])->updateOrInsert($values);
            }

            foreach (DB::table($tables['role_has_permissions'])->where($roleKey, $currentRole->id)->get() as $permission) {
                $values = (array) $permission;
                $values[$roleKey] = $newRole->id;
                DB::table($tables['role_has_permissions'])->updateOrInsert($values);
            }

            DB::table($tables['model_has_roles'])->where($roleKey, $currentRole->id)->delete();
            DB::table($tables['role_has_permissions'])->where($roleKey, $currentRole->id)->delete();
            DB::table($tables['roles'])->where('id', $currentRole->id)->delete();
        });

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
};
