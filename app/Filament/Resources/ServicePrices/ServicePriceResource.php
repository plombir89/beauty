<?php

namespace App\Filament\Resources\ServicePrices;

use App\Filament\Resources\ServicePrices\Pages\CreateServicePrice;
use App\Filament\Resources\ServicePrices\Pages\EditServicePrice;
use App\Filament\Resources\ServicePrices\Pages\ListServicePrices;
use App\Filament\Support\Fields;
use App\Models\ServicePrice;
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
use Filament\Tables\Table;
use UnitEnum;

class ServicePriceResource extends Resource
{
    protected static ?string $model = ServicePrice::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCurrencyDollar;

    protected static string|UnitEnum|null $navigationGroup = 'Services';

    protected static ?int $navigationSort = 30;

    protected static ?string $modelLabel = 'service price';

    protected static ?string $pluralModelLabel = 'service prices';

    protected static ?string $recordTitleAttribute = 'label';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fields::relationshipSelect('service_id', 'service', 'title', 'Service')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('amount_max')
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('currency')
                    ->required()
                    ->maxLength(3)
                    ->default('USD'),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_primary')
                    ->default(false),
                Fields::translations([
                    ['name' => 'label', 'label' => 'Label', 'required' => true],
                    ['name' => 'display_price', 'label' => 'Display price'],
                    ['name' => 'duration', 'label' => 'Duration'],
                    ['name' => 'note', 'label' => 'Note', 'type' => 'textarea', 'rows' => 3, 'full' => true],
                ]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('service.title')
                    ->label('Service')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('label')
                    ->searchable(),
                TextColumn::make('amount')
                    ->money('USD')
                    ->sortable(),
                TextColumn::make('amount_max')
                    ->money('USD')
                    ->sortable(),
                ToggleColumn::make('is_primary'),
                TextColumn::make('sort_order')
                    ->numeric()
                    ->sortable(),
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
            'index' => ListServicePrices::route('/'),
            'create' => CreateServicePrice::route('/create'),
            'edit' => EditServicePrice::route('/{record}/edit'),
        ];
    }
}
