<?php

namespace App\Filament\Resources\AttendanceRecordResource\Pages;

use App\Filament\Concerns\HasEditionPageContext;
use App\Filament\Resources\AttendanceRecordResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageAttendanceRecords extends ManageRecords
{
    use HasEditionPageContext;

    protected static string $resource = AttendanceRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ...$this->getEditionContextHeaderActions(),
            CreateAction::make(),
        ];
    }
}
