<?php

namespace App\Filament\Resources\ServiceCategories;

use App\Filament\Resources\ServiceCategories\Pages\CreateServiceCategory;
use App\Filament\Resources\ServiceCategories\Pages\EditServiceCategory;
use App\Filament\Resources\ServiceCategories\Pages\ListServiceCategories;
use App\Filament\Support\Fields;
use App\Models\ServiceCategory;
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

class ServiceCategoryResource extends Resource
{
    protected static ?string $model = ServiceCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static string|UnitEnum|null $navigationGroup = 'Services';

    protected static ?int $navigationSort = 10;

    protected static ?string $modelLabel = 'service category';

    protected static ?string $pluralModelLabel = 'service categories';

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
                    ['name' => 'title', 'label' => 'Title', 'required' => true, 'slugTarget' => 'slug'],
                    ['name' => 'slug', 'label' => 'Slug', 'required' => true],
                    ['name' => 'description', 'label' => 'Description', 'type' => 'textarea', 'rows' => 3, 'full' => true],
                ]),
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
            'index' => ListServiceCategories::route('/'),
            'create' => CreateServiceCategory::route('/create'),
            'edit' => EditServiceCategory::route('/{record}/edit'),
        ];
    }
}
