<?php

namespace App\Filament\Resources\LearningPathResource\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UserLearningPathsRelationManager extends RelationManager
{
    protected static string $relationship = 'userLearningPaths';

    protected static ?string $title = 'Participantes';

    protected static string|\BackedEnum|null $icon = Heroicon::OutlinedUsers;

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('user_id')
                ->label('Participante')
                ->relationship('user', 'name')
                ->searchable(['name', 'email'])
                ->preload()
                ->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('user.name')
            ->columns([
                TextColumn::make('user.name')
                    ->label('Participante')
                    ->icon(Heroicon::OutlinedUser)
                    ->weight('semibold')
                    ->searchable(),
                TextColumn::make('user.email')
                    ->label('Correo')
                    ->searchable(),
                TextColumn::make('progress_percentage')
                    ->label('Avance')
                    ->suffix('%')
                    ->numeric(decimalPlaces: 0),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'no_iniciada' => 'No iniciada',
                        'en_progreso' => 'En progreso',
                        'completada' => 'Completada',
                        default => ucfirst($state ?: 'Sin definir'),
                    })
                    ->color(fn (?string $state): string => match ($state) {
                        'completada' => 'success',
                        'en_progreso' => 'info',
                        default => 'gray',
                    }),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Asignar participante')
                    ->icon(Heroicon::OutlinedUserPlus)
                    ->mutateDataUsing(fn (array $data): array => [
                        ...$data,
                        'status' => 'no_iniciada',
                        'progress_percentage' => 0,
                    ])
                    ->successNotificationTitle('Participante asignado correctamente'),
            ])
            ->recordActions([
                DeleteAction::make()
                    ->label('Quitar de la ruta'),
            ])
            ->toolbarActions([])
            ->emptyStateIcon(Heroicon::OutlinedUsers)
            ->emptyStateHeading('No hay participantes asignados')
            ->emptyStateDescription('Asigna participantes para generar sus inscripciones y comenzar el seguimiento.')
            ->extraAttributes(['class' => 'pf-management-table']);
    }
}
