<?php

namespace App\Filament\Resources\DiplomaProgramResource\Pages;

use App\Filament\Resources\DiplomaProgramResource;
use App\Models\LearningPath;
use App\Services\LearningPaths\LearningPathProgressService;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Builder;

class EditDiplomaProgram extends EditRecord
{
    protected static string $resource = DiplomaProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }

    protected function afterSave(): void
    {
        LearningPath::query()
            ->whereHas('diplomaPrograms', fn (Builder $query) => $query->whereKey($this->getRecord()->id))
            ->orWhereHas('certificationPrograms.diplomaPrograms', fn (Builder $query) => $query->whereKey($this->getRecord()->id))
            ->each(fn (LearningPath $path) => $path->userLearningPaths()
                ->each(fn ($assignment) => app(LearningPathProgressService::class)->synchronizeAssignment($assignment)));
    }
}
