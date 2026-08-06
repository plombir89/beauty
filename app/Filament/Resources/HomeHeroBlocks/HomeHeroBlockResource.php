<?php

namespace App\Filament\Resources\HomeHeroBlocks;

use App\Filament\Resources\HomeHeroBlocks\Pages\EditHomeHeroBlock;
use App\Filament\Resources\HomeHeroBlocks\Pages\ListHomeHeroBlocks;
use App\Filament\Support\Fields;
use App\Models\HomeHeroBlock;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class HomeHeroBlockResource extends Resource
{
    protected static ?string $model = HomeHeroBlock::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    protected static string|UnitEnum|null $navigationGroup = 'Home';

    protected static ?int $navigationSort = 10;

    protected static ?string $modelLabel = 'home hero';

    protected static ?string $pluralModelLabel = 'home heroes';

    protected static ?string $recordTitleAttribute = 'title';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('key')
                    ->required()
                    ->maxLength(255),
                Toggle::make('is_active')
                    ->default(true)
                    ->required(),
                FileUpload::make('image')
                    ->image()
                    ->disk('public')
                    ->directory('home')
                    ->visibility('public')
                    ->imageEditor()
                    ->maxSize(4096),
                Fields::translations([
                    ['name' => 'eyebrow', 'label' => 'Eyebrow'],
                    ['name' => 'title', 'label' => 'Title', 'required' => true],
                    ['name' => 'lead', 'label' => 'Lead', 'type' => 'textarea', 'rows' => 3, 'full' => true],
                    ['name' => 'text', 'label' => 'Text', 'type' => 'textarea', 'rows' => 4, 'full' => true],
                    ['name' => 'primary_label', 'label' => 'Primary button'],
                    ['name' => 'secondary_label', 'label' => 'Secondary button'],
                    ['name' => 'stats', 'label' => 'Stats', 'type' => 'pairs', 'rows' => 4],
                ]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->disk('public')
                    ->visibility('public')
                    ->imageHeight(56)
                    ->square(),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('key')
                    ->searchable(),
                ToggleColumn::make('is_active'),
            ])
            ->filters([
                TernaryFilter::make('is_active'),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListHomeHeroBlocks::route('/'),
            'edit' => EditHomeHeroBlock::route('/{record}/edit'),
        ];
    }
}
