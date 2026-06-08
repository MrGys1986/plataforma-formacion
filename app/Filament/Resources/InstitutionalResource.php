<?php

namespace App\Filament\Resources;

use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;

abstract class InstitutionalResource extends Resource
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static array $formFields = [];

    protected static array $tableColumns = [];

    protected static ?string $statusColumn = 'status';

    protected static bool $readOnly = false;

    public static function form(Schema $schema): Schema
    {
        return $schema->components(
            array_map(static::makeFormComponent(...), static::$formFields),
        );
    }

    public static function table(Table $table): Table
    {
        $filters = [];

        if (filled(static::$statusColumn)) {
            $statusColumn = static::$statusColumn;
            $filters[] = SelectFilter::make($statusColumn)
                ->label('Estado')
                ->options(fn (): array => static::getEloquentQuery()
                    ->whereNotNull($statusColumn)
                    ->distinct()
                    ->orderBy($statusColumn)
                    ->pluck($statusColumn, $statusColumn)
                    ->all());
        }

        return $table
            ->columns(array_map(static::makeTableColumn(...), static::$tableColumns))
            ->filters($filters)
            ->recordActions(static::$readOnly ? [] : [
                EditAction::make(),
            ])
            ->toolbarActions([]);
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        if (Gate::getPolicyFor(static::getModel()) === null) {
            return $user->hasRole('Superadministrador');
        }

        return parent::canAccess();
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if (! $user || ! method_exists($query->getModel(), 'scopeVisibleTo')) {
            return $query;
        }

        return $query->visibleTo($user);
    }

    protected static function makeFormComponent(array $definition): Component
    {
        $name = $definition['name'];
        $type = $definition['type'] ?? 'text';

        $component = match ($type) {
            'date' => DatePicker::make($name),
            'datetime' => DateTimePicker::make($name),
            'json' => KeyValue::make($name),
            'number' => TextInput::make($name)->numeric(),
            'password' => TextInput::make($name)
                ->password()
                ->revealable()
                ->dehydrated(fn (?string $state): bool => filled($state))
                ->required(fn (string $operation): bool => $operation === 'create'),
            'relation' => Select::make($name)
                ->relationship(
                    $definition['relationship'],
                    $definition['title'] ?? 'name',
                    modifyQueryUsing: function (Builder $query) use ($definition): Builder {
                        $user = auth()->user();

                        if ($user && method_exists($query->getModel(), 'scopeVisibleTo')) {
                            $query->visibleTo($user);
                        }

                        if (filled($definition['role'] ?? null) && method_exists($query->getModel(), 'scopeRole')) {
                            $query->role($definition['role']);
                        }

                        return $query;
                    },
                )
                ->searchable()
                ->preload(),
            'select' => Select::make($name)->options($definition['options'] ?? []),
            'textarea' => Textarea::make($name)->columnSpanFull(),
            'toggle' => Toggle::make($name),
            default => TextInput::make($name)->maxLength(255),
        };

        return $component
            ->label($definition['label'])
            ->required($definition['required'] ?? false);
    }

    protected static function makeTableColumn(array $definition): TextColumn|IconColumn
    {
        if (($definition['type'] ?? null) === 'boolean') {
            return IconColumn::make($definition['name'])
                ->label($definition['label'])
                ->boolean();
        }

        $column = TextColumn::make($definition['name'])
            ->label($definition['label'])
            ->searchable($definition['searchable'] ?? false)
            ->sortable($definition['sortable'] ?? true)
            ->limit(50);

        if (($definition['type'] ?? null) === 'date') {
            $column->date('d/m/Y');
        }

        if (($definition['type'] ?? null) === 'datetime') {
            $column->dateTime('d/m/Y H:i');
        }

        return $column;
    }
}
