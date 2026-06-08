<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\ContinuingEducation\ContinuingEducationCluster;
use App\Filament\Resources\PaymentResource\Pages\ManagePayments;
use App\Models\Payment;

class PaymentResource extends InstitutionalResource
{
    protected static ?string $model = Payment::class;

    protected static ?string $cluster = ContinuingEducationCluster::class;

    protected static ?string $modelLabel = 'Pago';

    protected static ?string $pluralModelLabel = 'Pagos';

    protected static ?string $recordTitleAttribute = 'payment_reference';

    protected static array $formFields = [
        0 => [
            'name' => 'user_id',
            'label' => 'Participante',
            'type' => 'relation',
            'relationship' => 'user',
            'required' => true,
        ],
        1 => [
            'name' => 'activity_id',
            'label' => 'Actividad',
            'type' => 'relation',
            'relationship' => 'activity',
        ],
        2 => [
            'name' => 'amount',
            'label' => 'Importe',
            'type' => 'number',
            'required' => true,
        ],
        3 => [
            'name' => 'currency',
            'label' => 'Moneda',
            'required' => true,
        ],
        4 => [
            'name' => 'payment_method',
            'label' => 'Método de pago',
        ],
        5 => [
            'name' => 'payment_reference',
            'label' => 'Referencia',
        ],
        6 => [
            'name' => 'status',
            'label' => 'Estado',
        ],
    ];

    protected static array $tableColumns = [
        0 => [
            'name' => 'payment_reference',
            'label' => 'Referencia',
            'searchable' => true,
        ],
        1 => [
            'name' => 'user.name',
            'label' => 'Participante',
        ],
        2 => [
            'name' => 'activity.name',
            'label' => 'Actividad',
        ],
        3 => [
            'name' => 'amount',
            'label' => 'Importe',
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
            'index' => ManagePayments::route('/'),
        ];
    }
}
