<?php

namespace App\Filament\Resources\ExpertisePillars;

use App\Filament\Resources\ExpertisePillars\Pages\CreateExpertisePillar;
use App\Filament\Resources\ExpertisePillars\Pages\EditExpertisePillar;
use App\Filament\Resources\ExpertisePillars\Pages\ListExpertisePillars;
use App\Filament\Support\Fields;
use App\Models\ExpertisePillar;
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

class ExpertisePillarResource extends Resource
{
    protected static ?string $model = ExpertisePillar::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static string|UnitEnum|null $navigationGroup = 'Home';

    protected static ?int $navigationSort = 20;

    protected static ?string $modelLabel = 'expertise pillar';

    protected static ?string $pluralModelLabel = 'expertise pillars';

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
                Fields::imageUpload('image', 'img/uploads/home'),
                Fields::translations([
                    ['name' => 'title', 'label' => 'Title', 'required' => true],
                    ['name' => 'intro', 'label' => 'Intro', 'type' => 'textarea', 'rows' => 3, 'full' => true],
                    ['name' => 'items', 'label' => 'Items', 'type' => 'lines', 'rows' => 8],
                    ['name' => 'note', 'label' => 'Note', 'type' => 'textarea', 'rows' => 3, 'full' => true],
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
            'index' => ListExpertisePillars::route('/'),
            'create' => CreateExpertisePillar::route('/create'),
            'edit' => EditExpertisePillar::route('/{record}/edit'),
        ];
    }
}
