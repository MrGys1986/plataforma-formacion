<?php

namespace App\Console\Commands;

use App\Models\FileUpload;
use App\Services\Files\ManagedFileService;
use Illuminate\Console\Command;

class CleanupOrphanFiles extends Command
{
    protected $signature = 'files:cleanup-orphans {--execute : Programa los huérfanos para eliminación} {--days=30 : Antigüedad mínima en días}';
    protected $description = 'Detecta archivos sin relación y opcionalmente programa su eliminación';

    public function handle(ManagedFileService $files): int
    {
        $orphans = FileUpload::query()
            ->where('created_at', '<=', now()->subDays(max(1, (int) $this->option('days'))))
            ->get()
            ->filter->isOrphan();
        $this->info("Archivos huérfanos: {$orphans->count()}");
        if (! $this->option('execute')) return self::SUCCESS;
        $orphans->each(fn (FileUpload $file) => $files->scheduleDeletion($file));
        $this->info('Los archivos fueron enviados a retención por 30 días.');
        return self::SUCCESS;
    }
}
