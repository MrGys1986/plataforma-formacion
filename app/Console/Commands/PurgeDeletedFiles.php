<?php

namespace App\Console\Commands;

use App\Models\FileUpload;
use App\Services\Files\ManagedFileService;
use Illuminate\Console\Command;
use Throwable;

class PurgeDeletedFiles extends Command
{
    protected $signature = 'files:purge-deleted {--dry-run : Solo muestra los archivos elegibles}';
    protected $description = 'Elimina físicamente archivos cuya retención ya venció';

    public function handle(ManagedFileService $files): int
    {
        $records = FileUpload::onlyTrashed()->where('delete_after', '<=', now())->get();
        $this->info("Archivos elegibles: {$records->count()}");
        if ($this->option('dry-run')) return self::SUCCESS;

        $failed = 0;
        foreach ($records as $record) {
            try { $files->purge($record); } catch (Throwable $e) {
                $failed++;
                $record->update(['last_error' => $e->getMessage()]);
                $this->error("No se pudo eliminar {$record->original_name}.");
            }
        }
        $this->info('Eliminados: '.($records->count() - $failed));
        return $failed ? self::FAILURE : self::SUCCESS;
    }
}
