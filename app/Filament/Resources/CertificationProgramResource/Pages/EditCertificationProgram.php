<?php

namespace App\Filament\Resources\CertificationProgramResource\Pages;

use App\Filament\Resources\CertificationProgramResource;
use App\Models\LearningPath;
use App\Services\LearningPaths\LearningPathProgressService;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Builder;

class EditCertificationProgram extends EditRecord
{
    protected static string $resource = CertificationProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }

    protected function afterSave(): void
    {
        LearningPath::query()
            ->whereHas('certificationPrograms', fn (Builder $query) => $query->whereKey($this->getRecord()->id))
            ->each(fn (LearningPath $path) => $path->userLearningPaths()
                ->each(fn ($assignment) => app(LearningPathProgressService::class)->synchronizeAssignment($assignment)));
    }
}
