<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('learning_paths', function (Blueprint $table): void {
            $table->string('path_type')->default('certificacion')->after('area_id');
        });

        Schema::create('learning_path_training_program', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('learning_path_id')->constrained()->cascadeOnDelete();
            $table->foreignId('training_program_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('order_number')->default(1);
            $table->boolean('is_required')->default(true);
            $table->decimal('minimum_score', 8, 2)->nullable();
            $table->timestamps();
            $table->unique(
                ['learning_path_id', 'training_program_id'],
                'learning_path_training_program_unique',
            );
        });

        DB::table('learning_paths')
            ->where('generates_microcredential', true)
            ->update(['path_type' => 'microcredencial']);

        DB::table('learning_paths')
            ->where('generates_microcredential', false)
            ->where('generates_diploma', true)
            ->update(['path_type' => 'diplomado']);

        DB::table('learning_path_items')
            ->join('activities', 'activities.id', '=', 'learning_path_items.activity_id')
            ->whereNotNull('activities.training_program_id')
            ->select([
                'learning_path_items.learning_path_id',
                'activities.training_program_id',
                'learning_path_items.order_number',
                'learning_path_items.is_required',
                'learning_path_items.minimum_score',
            ])
            ->orderBy('learning_path_items.id')
            ->get()
            ->each(function (object $item): void {
                DB::table('learning_path_training_program')->updateOrInsert(
                    [
                        'learning_path_id' => $item->learning_path_id,
                        'training_program_id' => $item->training_program_id,
                    ],
                    [
                        'order_number' => $item->order_number,
                        'is_required' => $item->is_required,
                        'minimum_score' => $item->minimum_score,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                );
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('learning_path_training_program');

        Schema::table('learning_paths', function (Blueprint $table): void {
            $table->dropColumn('path_type');
        });
    }
};
