<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\SystemAdministration\SystemAdministrationCluster;
use App\Filament\Resources\NotificationLogResource\Pages\ManageNotificationLogs;
use App\Models\NotificationLog;

class NotificationLogResource extends InstitutionalResource
{
    protected static ?string $model = NotificationLog::class;

    protected static ?string $cluster = SystemAdministrationCluster::class;

    protected static ?string $modelLabel = 'Registro de notificación';

    protected static ?string $pluralModelLabel = 'Registros de notificaciones';

    protected static ?string $recordTitleAttribute = 'subject';

    protected static array $formFields = [
        0 => [
            'name' => 'user_id',
            'label' => 'Usuario',
            'type' => 'relation',
            'relationship' => 'user',
        ],
        1 => [
            'name' => 'notification_type',
            'label' => 'Tipo',
            'required' => true,
        ],
        2 => [
            'name' => 'subject',
            'label' => 'Asunto',
        ],
        3 => [
            'name' => 'message',
            'label' => 'Mensaje',
            'type' => 'textarea',
        ],
        4 => [
            'name' => 'channel',
            'label' => 'Canal',
        ],
        5 => [
            'name' => 'status',
            'label' => 'Estado',
        ],
    ];

    protected static array $tableColumns = [
        0 => [
            'name' => 'notification_type',
            'label' => 'Tipo',
        ],
        1 => [
            'name' => 'user.name',
            'label' => 'Usuario',
        ],
        2 => [
            'name' => 'subject',
            'label' => 'Asunto',
            'searchable' => true,
        ],
        3 => [
            'name' => 'channel',
            'label' => 'Canal',
        ],
        4 => [
            'name' => 'status',
            'label' => 'Estado',
        ],
    ];

    protected static ?string $statusColumn = 'status';

    protected static bool $readOnly = false;

    public static function getPages(): array
    {
        return [
            'index' => ManageNotificationLogs::route('/'),
        ];
    }
}
