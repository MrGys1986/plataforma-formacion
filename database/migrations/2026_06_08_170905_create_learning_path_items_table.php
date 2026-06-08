<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('learning_path_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('learning_path_id')->constrained()->cascadeOnDelete();
            $table->foreignId('activity_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('order_number')->default(1);
            $table->boolean('is_required')->default(true);
            $table->decimal('minimum_score', 8, 2)->nullable();
            $table->timestamps();
            $table->unique(['learning_path_id', 'activity_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('learning_path_items');
    }
};
