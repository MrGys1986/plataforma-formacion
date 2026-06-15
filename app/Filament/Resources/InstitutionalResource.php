<?php

namespace App\Filament\Resources;

use BackedEnum;
use Closure;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
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
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Gate;

abstract class InstitutionalResource extends Resource
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static array $formFields = [];

    protected static array $tableColumns = [];

    protected static ?string $statusColumn = 'status';

    protected static bool $readOnly = false;

    protected static bool $softDeletes = false;

    public static function form(Schema $schema): Schema
    {
        return $schema->components(
            array_map(static::makeFormComponent(...), static::getFormFields()),
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

        $recordActions = [
            ...static::getTableRecordActions(),
            ...(static::$readOnly ? [] : [EditAction::make()]),
        ];

        if (static::$softDeletes && ! static::$readOnly) {
            $filters[] = TrashedFilter::make();
            $recordActions[] = DeleteAction::make();
            $recordActions[] = RestoreAction::make();
        }

        return static::modifyTable(
            $table
                ->columns(array_map(static::makeTableColumn(...), static::getTableColumns()))
                ->filters($filters)
                ->recordActions($recordActions)
                ->toolbarActions([]),
        );
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

        if (static::$softDeletes) {
            $query->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
        }

        if (! $user || ! method_exists($query->getModel(), 'scopeVisibleTo')) {
            return static::applyContextToQuery($query);
        }

        return static::applyContextToQuery($query->visibleTo($user));
    }

    protected static function makeFormComponent(array $definition): Component
    {
        $name = $definition['name'];
        $type = $definition['type'] ?? 'text';
        $modifyRelationQueryUsing = $definition['modify_query_using'] ?? null;

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
                    modifyQueryUsing: function (Builder $query) use ($definition, $modifyRelationQueryUsing): Builder {
                        $user = auth()->user();

                        if ($user && method_exists($query->getModel(), 'scopeVisibleTo')) {
                            $query->visibleTo($user);
                        }

                        if (filled($definition['role'] ?? null) && method_exists($query->getModel(), 'scopeRole')) {
                            $query->role($definition['role']);
                        }

                        if ($modifyRelationQueryUsing instanceof Closure) {
                            $query = $modifyRelationQueryUsing($query) ?? $query;
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

        if ($component instanceof Select && ($definition['multiple'] ?? false)) {
            $component->multiple();
        }

        if (isset($definition['visible'])) {
            $component->visible($definition['visible']);
        }

        if (isset($definition['visible_for_role'])) {
            $component->visible(
                fn (): bool => auth()->user()?->hasRole($definition['visible_for_role']) ?? false,
            );
        }

        if (array_key_exists('default', $definition)) {
            $component->default($definition['default']);
        }

        if (array_key_exists('disabled', $definition)) {
            $component->disabled($definition['disabled']);
        }

        if (array_key_exists('dehydrated', $definition)) {
            $component->dehydrated($definition['dehydrated']);
        }

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

    /**
     * @return array<Action | ActionGroup>
     */
    protected static function getTableRecordActions(): array
    {
        return [];
    }

    protected static function getFormFields(): array
    {
        return static::$formFields;
    }

    protected static function getTableColumns(): array
    {
        return static::$tableColumns;
    }

    protected static function applyContextToQuery(Builder $query): Builder
    {
        return $query;
    }

    protected static function modifyTable(Table $table): Table
    {
        return $table;
    }
}
