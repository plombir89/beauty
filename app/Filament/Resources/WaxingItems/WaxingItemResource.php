<?php

namespace App\Filament\Resources\WaxingItems;

use App\Filament\Resources\WaxingItems\Pages\CreateWaxingItem;
use App\Filament\Resources\WaxingItems\Pages\EditWaxingItem;
use App\Filament\Resources\WaxingItems\Pages\ListWaxingItems;
use App\Filament\Support\Fields;
use App\Models\WaxingItem;
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

class WaxingItemResource extends Resource
{
    protected static ?string $model = WaxingItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCurrencyDollar;

    protected static string|UnitEnum|null $navigationGroup = 'Services';

    protected static ?int $navigationSort = 60;

    protected static ?string $modelLabel = 'waxing item';

    protected static ?string $pluralModelLabel = 'waxing items';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fields::relationshipSelect('waxing_group_id', 'group', 'title', 'Group')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('key')
                    ->required()
                    ->maxLength(255),
                TextInput::make('amount')
                    ->required()
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
                Toggle::make('is_active')
                    ->default(true)
                    ->required(),
                Fields::translations([
                    ['name' => 'title', 'label' => 'Title', 'required' => true],
                    ['name' => 'display_price', 'label' => 'Display price'],
                ]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('group.title')
                    ->label('Group')
                    ->sortable(),
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('amount')
                    ->money('USD')
                    ->sortable(),
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
            'index' => ListWaxingItems::route('/'),
            'create' => CreateWaxingItem::route('/create'),
            'edit' => EditWaxingItem::route('/{record}/edit'),
        ];
    }
}
