<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // MySQL DDL is not transactional. A failed attempt may have already added
        // columns and created part of a table, so every step must be resumable.
        if (! Schema::hasColumn('diploma_programs', 'generates_microcredential')) {
            Schema::table('diploma_programs', fn (Blueprint $table) => $table
                ->boolean('generates_microcredential')->default(false));
        }

        if (! Schema::hasColumn('certification_programs', 'generates_microcredential')) {
            Schema::table('certification_programs', fn (Blueprint $table) => $table
                ->boolean('generates_microcredential')->default(false));
        }

        if (! Schema::hasTable('learning_path_diploma_program')) {
            Schema::create('learning_path_diploma_program', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('learning_path_id');
                $table->foreignId('diploma_program_id');
                $table->unsignedInteger('order_number')->default(1);
                $table->boolean('is_required')->default(true);
                $table->timestamps();
            });
        }

        if (! Schema::hasForeignKey('learning_path_diploma_program', 'lp_diploma_path_fk')
            && ! Schema::hasForeignKey('learning_path_diploma_program', 'learning_path_diploma_program_learning_path_id_foreign')) {
            Schema::table('learning_path_diploma_program', fn (Blueprint $table) => $table
                ->foreign('learning_path_id', 'lp_diploma_path_fk')
                ->references('id')->on('learning_paths')->cascadeOnDelete());
        }

        if (! Schema::hasForeignKey('learning_path_diploma_program', 'lp_diploma_program_fk')
            && ! Schema::hasForeignKey('learning_path_diploma_program', 'learning_path_diploma_program_diploma_program_id_foreign')) {
            Schema::table('learning_path_diploma_program', fn (Blueprint $table) => $table
                ->foreign('diploma_program_id', 'lp_diploma_program_fk')
                ->references('id')->on('diploma_programs')->cascadeOnDelete());
        }

        if (! Schema::hasIndex('learning_path_diploma_program', 'learning_path_diploma_unique')) {
            Schema::table('learning_path_diploma_program', fn (Blueprint $table) => $table
                ->unique(['learning_path_id', 'diploma_program_id'], 'learning_path_diploma_unique'));
        }

        if (! Schema::hasTable('learning_path_certification_program')) {
            Schema::create('learning_path_certification_program', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('learning_path_id');
                $table->foreignId('certification_program_id');
                $table->unsignedInteger('order_number')->default(1);
                $table->boolean('is_required')->default(true);
                $table->timestamps();
            });
        }

        if (! Schema::hasForeignKey('learning_path_certification_program', 'lp_cert_path_fk')
            && ! Schema::hasForeignKey('learning_path_certification_program', 'learning_path_certification_program_learning_path_id_foreign')) {
            Schema::table('learning_path_certification_program', fn (Blueprint $table) => $table
                ->foreign('learning_path_id', 'lp_cert_path_fk')
                ->references('id')->on('learning_paths')->cascadeOnDelete());
        }

        if (! Schema::hasForeignKey('learning_path_certification_program', 'lp_cert_program_fk')) {
            Schema::table('learning_path_certification_program', fn (Blueprint $table) => $table
                ->foreign('certification_program_id', 'lp_cert_program_fk')
                ->references('id')->on('certification_programs')->cascadeOnDelete());
        }

        if (! Schema::hasIndex('learning_path_certification_program', 'learning_path_certification_unique')) {
            Schema::table('learning_path_certification_program', fn (Blueprint $table) => $table
                ->unique(['learning_path_id', 'certification_program_id'], 'learning_path_certification_unique'));
        }

        if (! Schema::hasTable('certification_training_program')) {
            Schema::create('certification_training_program', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('certification_program_id');
                $table->foreignId('training_program_id');
                $table->unsignedInteger('order_number')->default(1);
                $table->boolean('is_required')->default(true);
                $table->decimal('minimum_score', 8, 2)->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasForeignKey('certification_training_program', 'cert_training_cert_fk')) {
            Schema::table('certification_training_program', fn (Blueprint $table) => $table
                ->foreign('certification_program_id', 'cert_training_cert_fk')
                ->references('id')->on('certification_programs')->cascadeOnDelete());
        }

        if (! Schema::hasForeignKey('certification_training_program', 'cert_training_program_fk')) {
            Schema::table('certification_training_program', fn (Blueprint $table) => $table
                ->foreign('training_program_id', 'cert_training_program_fk')
                ->references('id')->on('training_programs')->cascadeOnDelete());
        }

        if (! Schema::hasIndex('certification_training_program', 'certification_training_unique')) {
            Schema::table('certification_training_program', fn (Blueprint $table) => $table
                ->unique(['certification_program_id', 'training_program_id'], 'certification_training_unique'));
        }

        if (! Schema::hasColumn('microcredentials', 'diploma_program_id')) {
            Schema::table('microcredentials', fn (Blueprint $table) => $table
                ->foreignId('diploma_program_id')->nullable()->constrained()->nullOnDelete());
        }

        if (! Schema::hasColumn('microcredentials', 'certification_program_id')) {
            Schema::table('microcredentials', fn (Blueprint $table) => $table
                ->foreignId('certification_program_id')->nullable()->constrained()->nullOnDelete());
        }

        if (! Schema::hasIndex('microcredentials', 'microcredentials_user_diploma_unique')) {
            Schema::table('microcredentials', fn (Blueprint $table) => $table
                ->unique(['user_id', 'diploma_program_id'], 'microcredentials_user_diploma_unique'));
        }

        if (! Schema::hasIndex('microcredentials', 'microcredentials_user_certification_unique')) {
            Schema::table('microcredentials', fn (Blueprint $table) => $table
                ->unique(['user_id', 'certification_program_id'], 'microcredentials_user_certification_unique'));
        }

        if (! Schema::hasIndex('microcredentials', 'microcredentials_user_activity_unique')) {
            Schema::table('microcredentials', fn (Blueprint $table) => $table
                ->unique(['user_id', 'activity_id'], 'microcredentials_user_activity_unique'));
        }
    }

    public function down(): void
    {
        Schema::table('microcredentials', function (Blueprint $table): void {
            $table->dropUnique('microcredentials_user_activity_unique');
            $table->dropUnique('microcredentials_user_certification_unique');
            $table->dropUnique('microcredentials_user_diploma_unique');
            $table->dropConstrainedForeignId('certification_program_id');
            $table->dropConstrainedForeignId('diploma_program_id');
        });
        Schema::dropIfExists('certification_training_program');
        Schema::dropIfExists('learning_path_certification_program');
        Schema::dropIfExists('learning_path_diploma_program');
        Schema::table('certification_programs', fn (Blueprint $table) => $table->dropColumn('generates_microcredential'));
        Schema::table('diploma_programs', fn (Blueprint $table) => $table->dropColumn('generates_microcredential'));
    }
};
