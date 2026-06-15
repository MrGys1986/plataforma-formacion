<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\TrainingManagement\TrainingManagementCluster;
use App\Filament\Resources\FileUploadResource\Pages\ManageFileUploads;
use App\Models\FileUpload;

class FileUploadResource extends InstitutionalResource
{
    protected static bool $shouldRegisterNavigation = false;

    protected static ?int $navigationSort = 10;

    protected static ?string $model = FileUpload::class;

    protected static ?string $cluster = TrainingManagementCluster::class;

    protected static ?string $modelLabel = 'Archivo';

    protected static ?string $pluralModelLabel = 'Archivos';

    protected static ?string $recordTitleAttribute = 'original_name';

    protected static array $formFields = [
        0 => [
            'name' => 'original_name',
            'label' => 'Nombre original',
            'required' => true,
        ],
        1 => [
            'name' => 'stored_name',
            'label' => 'Nombre almacenado',
            'required' => true,
        ],
        2 => [
            'name' => 'disk',
            'label' => 'Disco',
            'required' => true,
        ],
        3 => [
            'name' => 'path',
            'label' => 'Ruta',
            'required' => true,
        ],
        4 => [
            'name' => 'mime_type',
            'label' => 'Tipo MIME',
        ],
    ];

    protected static array $tableColumns = [
        0 => [
            'name' => 'original_name',
            'label' => 'Archivo',
            'searchable' => true,
        ],
        1 => [
            'name' => 'disk',
            'label' => 'Disco',
        ],
        2 => [
            'name' => 'mime_type',
            'label' => 'Tipo MIME',
        ],
        3 => [
            'name' => 'size',
            'label' => 'Tamaño',
        ],
    ];

    protected static ?string $statusColumn = null;

    protected static bool $readOnly = true;

    public static function getPages(): array
    {
        return [
            'index' => ManageFileUploads::route('/'),
        ];
    }
}
