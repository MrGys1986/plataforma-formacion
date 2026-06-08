<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('curp', 18)->nullable()->after('email');
            $table->string('user_type')->nullable()->after('curp');
            $table->string('profile_type')->nullable()->after('user_type');
            $table->foreignId('area_id')->nullable()->after('profile_type')->constrained('areas')->nullOnDelete();
            $table->string('external_institution')->nullable()->after('area_id');
            $table->string('phone')->nullable()->after('external_institution');
            $table->string('status')->default('activo')->after('phone');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('area_id');
            $table->dropColumn([
                'curp',
                'user_type',
                'profile_type',
                'external_institution',
                'phone',
                'status',
            ]);
        });
    }
};
