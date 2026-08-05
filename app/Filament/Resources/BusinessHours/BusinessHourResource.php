<?php

namespace App\Filament\Resources\BusinessHours;

use App\Filament\Resources\BusinessHours\Pages\CreateBusinessHour;
use App\Filament\Resources\BusinessHours\Pages\EditBusinessHour;
use App\Filament\Resources\BusinessHours\Pages\ListBusinessHours;
use App\Filament\Support\Fields;
use App\Models\BusinessHour;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use UnitEnum;

class BusinessHourResource extends Resource
{
    protected static ?string $model = BusinessHour::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static string|UnitEnum|null $navigationGroup = 'Contact';

    protected static ?int $navigationSort = 30;

    protected static ?string $modelLabel = 'business hour';

    protected static ?string $pluralModelLabel = 'business hours';

    protected static ?string $recordTitleAttribute = 'day_label';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('key')
                    ->required()
                    ->maxLength(255),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->default(true)
                    ->required(),
                Fields::translations([
                    ['name' => 'day_label', 'label' => 'Day', 'required' => true],
                    ['name' => 'time_label', 'label' => 'Time', 'required' => true],
                ]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('day_label')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('time_label')
                    ->searchable(),
                TextColumn::make('sort_order')
                    ->numeric()
                    ->sortable(),
                ToggleColumn::make('is_active'),
            ])
            ->filters([
                TernaryFilter::make('is_active'),
            ])
            ->defaultSort('sort_order')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBusinessHours::route('/'),
            'create' => CreateBusinessHour::route('/create'),
            'edit' => EditBusinessHour::route('/{record}/edit'),
        ];
    }
}
