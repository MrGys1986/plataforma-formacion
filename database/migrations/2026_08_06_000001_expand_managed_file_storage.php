<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('file_uploads', function (Blueprint $table): void {
            $table->string('asset_id')->nullable()->after('path')->index();
            $table->string('resource_type')->default('raw')->after('asset_id');
            $table->string('delivery_type')->default('private')->after('resource_type');
            $table->unsignedBigInteger('version')->nullable()->after('delivery_type');
            $table->timestamp('delete_after')->nullable()->after('hash')->index();
            $table->text('last_error')->nullable()->after('delete_after');
            $table->softDeletes();
        });

        Schema::table('training_programs', function (Blueprint $table): void {
            $table->foreignId('cover_file_id')->nullable()->after('created_by')->constrained('file_uploads')->nullOnDelete();
        });

        Schema::table('users', function (Blueprint $table): void {
            $table->foreignId('avatar_file_id')->nullable()->after('area_id')->constrained('file_uploads')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', fn (Blueprint $table) => $table->dropConstrainedForeignId('avatar_file_id'));
        Schema::table('training_programs', fn (Blueprint $table) => $table->dropConstrainedForeignId('cover_file_id'));
        Schema::table('file_uploads', function (Blueprint $table): void {
            $table->dropSoftDeletes();
            $table->dropColumn(['asset_id', 'resource_type', 'delivery_type', 'version', 'delete_after', 'last_error']);
        });
    }
};
