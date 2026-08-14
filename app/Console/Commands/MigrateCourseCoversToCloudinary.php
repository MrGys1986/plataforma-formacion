<?php

namespace App\Console\Commands;

use App\Models\TrainingProgram;
use App\Models\Activity;
use App\Services\Files\ManagedFileService;
use Illuminate\Console\Command;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Throwable;

class MigrateCourseCoversToCloudinary extends Command
{
    protected $signature = 'courses:migrate-covers {--execute : Sube y relaciona las portadas} {--replace : Sustituye portadas existentes}';

    protected $description = 'Migra las imágenes predeterminadas del catálogo a Cloudinary';

    /** @var array<string, string> */
    private array $covers = [
        'liderazgo' => 'course-leadership.png',
        'protección' => 'course-data-protection.png',
        'datos personales' => 'course-data-protection.png',
        'diseño de experiencias' => 'course-learning-design.png',
        'evaluación' => 'course-assessment.png',
        'herramientas digitales' => 'course-digital-tools.png',
        'inducción' => 'course-induction.png',
    ];

    public function handle(ManagedFileService $files): int
    {
        $programs = TrainingProgram::query()->with('coverFile')->get();
        $matched = 0;
        $failed = 0;

        foreach ($programs as $program) {
            $fileName = $this->coverFor($program->name);
            if (! $fileName || ($program->cover_file_id && ! $this->option('replace'))) continue;
            $path = public_path('img/courses/'.$fileName);
            if (! is_file($path)) { $this->warn("No existe {$fileName} para {$program->name}."); continue; }
            $matched++;
            if (! $this->option('execute')) { $this->line("Preparada: {$program->name} → {$fileName}"); continue; }

            try {
                $upload = new UploadedFile($path, $fileName, mime_content_type($path) ?: 'image/png', null, true);
                $cover = $files->store($upload, 'course-covers/'.$program->public_id, auth()->id(), true);
                $previous = $program->coverFile;
                $program->update(['cover_file_id' => $cover->id]);
                if ($previous && $previous->id !== $cover->id) $files->scheduleDeletion($previous);
                $this->info("Migrada: {$program->name}");
            } catch (Throwable $exception) {
                $failed++;
                $this->error("Falló {$program->name}: {$exception->getMessage()}");
            }
        }

        $activities = Activity::query()->with('coverFile')->get();
        foreach ($activities as $activity) {
            $fileName = $this->coverFor($activity->name);
            if (! $fileName || ($activity->cover_file_id && ! $this->option('replace'))) continue;
            $path = public_path('img/courses/'.$fileName);
            if (! is_file($path)) { $this->warn("No existe {$fileName} para {$activity->name}."); continue; }
            $matched++;
            if (! $this->option('execute')) { $this->line("Preparada: {$activity->name} → {$fileName}"); continue; }

            try {
                $upload = new UploadedFile($path, $fileName, mime_content_type($path) ?: 'image/png', null, true);
                $cover = $files->store($upload, 'course-covers/'.$activity->public_id, null, true);
                $previous = $activity->coverFile;
                $activity->update(['cover_file_id' => $cover->id]);
                if ($previous && $previous->id !== $cover->id) $files->scheduleDeletion($previous);
                $this->info("Migrada: {$activity->name}");
            } catch (Throwable $exception) {
                $failed++;
                $this->error("Falló {$activity->name}: {$exception->getMessage()}");
            }
        }

        $this->newLine();
        $this->info("Programas identificados: {$matched}. Fallos: {$failed}.");
        return $failed ? self::FAILURE : self::SUCCESS;
    }

    private function coverFor(string $name): ?string
    {
        $normalized = Str::lower($name);
        foreach ($this->covers as $term => $file) {
            if (str_contains($normalized, $term)) return $file;
        }
        return null;
    }
}
