<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            // Some existing installations created this column as an enum.
            // Keep it flexible so every profile exposed by the user form is valid.
            $table->string('profile_type')->nullable()->change();
        });
    }

    public function down(): void
    {
        // The previous enum values differ between existing installations, so
        // restoring an enum here could make valid user records impossible to save.
    }
};
