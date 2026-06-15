<?php

namespace App\Filament\Clusters\SurveyManagement;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;

class SurveyManagementCluster extends Cluster
{
    protected static ?int $navigationSort = 4;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $navigationLabel = 'Encuestas';

    protected static ?string $title = 'Encuestas';

    protected static ?string $slug = 'encuestas';
}
