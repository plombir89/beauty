<?php

namespace App\Filament\Resources\WhyChooseUsItems;

use App\Filament\Resources\WhyChooseUsItems\Pages\CreateWhyChooseUsItem;
use App\Filament\Resources\WhyChooseUsItems\Pages\EditWhyChooseUsItem;
use App\Filament\Resources\WhyChooseUsItems\Pages\ListWhyChooseUsItems;
use App\Filament\Support\Fields;
use App\Models\WhyChooseUsItem;
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

class WhyChooseUsItemResource extends Resource
{
    protected static ?string $model = WhyChooseUsItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static string|UnitEnum|null $navigationGroup = 'Home';

    protected static ?int $navigationSort = 30;

    protected static ?string $modelLabel = 'why choose us item';

    protected static ?string $pluralModelLabel = 'why choose us items';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('key')
                    ->required()
                    ->maxLength(255),
                TextInput::make('icon')
                    ->maxLength(255),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->default(true)
                    ->required(),
                Fields::imageUpload('image', 'img/uploads/why-choose-us'),
                Fields::translations([
                    ['name' => 'slug', 'label' => 'Slug', 'required' => true],
                    ['name' => 'title', 'label' => 'Title', 'required' => true],
                    ['name' => 'summary', 'label' => 'Summary', 'type' => 'textarea', 'rows' => 3, 'full' => true],
                    ['name' => 'body', 'label' => 'Body paragraphs', 'type' => 'lines', 'rows' => 10],
                ]),
            ])
            ->columns(4);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Fields::imageColumn(),
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
            'index' => ListWhyChooseUsItems::route('/'),
            'create' => CreateWhyChooseUsItem::route('/create'),
            'edit' => EditWhyChooseUsItem::route('/{record}/edit'),
        ];
    }
}
