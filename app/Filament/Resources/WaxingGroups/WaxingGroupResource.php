<?php

namespace App\Filament\Resources\WaxingGroups;

use App\Filament\Resources\WaxingGroups\Pages\CreateWaxingGroup;
use App\Filament\Resources\WaxingGroups\Pages\EditWaxingGroup;
use App\Filament\Resources\WaxingGroups\Pages\ListWaxingGroups;
use App\Filament\Support\Fields;
use App\Models\WaxingGroup;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Repeater;
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

class WaxingGroupResource extends Resource
{
    protected static ?string $model = WaxingGroup::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Services';

    protected static ?int $navigationSort = 50;

    protected static ?string $modelLabel = 'waxing group';

    protected static ?string $pluralModelLabel = 'waxing groups';

    protected static ?string $recordTitleAttribute = 'title';

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
                    ['name' => 'title', 'label' => 'Title', 'required' => true],
                ]),
                Repeater::make('items')
                    ->relationship()
                    ->schema([
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
                    ->columns(3)
                    ->orderColumn('sort_order')
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['title']['en'] ?? null)
                    ->addActionLabel('Add waxing item')
                    ->columnSpanFull(),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('key')
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
            'index' => ListWaxingGroups::route('/'),
            'create' => CreateWaxingGroup::route('/create'),
            'edit' => EditWaxingGroup::route('/{record}/edit'),
        ];
    }
}
