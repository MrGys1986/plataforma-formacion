<?php

namespace App\Filament\Resources\LearningPathResource\Pages;

use App\Filament\Pages\LearningPathControlPage;
use App\Filament\Resources\LearningPathResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

class EditLearningPath extends EditRecord
{
    protected static string $resource = LearningPathResource::class;

    public function getHeading(): string|Htmlable|null
    {
        return view('filament.partials.resource-heading', [
            'icon' => Heroicon::OutlinedMap,
            'label' => 'Administrar ruta',
        ]);
    }

    public function getSubheading(): string|Htmlable|null
    {
        return $this->getRecord()->name;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('volver_control')
                ->label('Volver al control')
                ->icon(Heroicon::OutlinedArrowLeft)
                ->color('gray')
                ->url(LearningPathControlPage::getUrl(['record' => $this->getRecord()->getKey()])),
            DeleteAction::make(),
        ];
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Ruta actualizada correctamente';
    }
}
