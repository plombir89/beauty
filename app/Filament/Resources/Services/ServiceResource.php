<?php

namespace App\Filament\Resources\Services;

use App\Filament\Forms\Components\RichEditor\RichContentCustomBlocks\YouTubeVideoBlock;
use App\Filament\Resources\Services\Pages\CreateService;
use App\Filament\Resources\Services\Pages\EditService;
use App\Filament\Resources\Services\Pages\ListServices;
use App\Filament\Support\Fields;
use App\Models\Service;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use UnitEnum;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static string|UnitEnum|null $navigationGroup = 'Services';

    protected static ?int $navigationSort = 20;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Service')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Fields::relationshipSelect('service_category_id', 'category', 'title', 'Category')
                                    ->required(),
                                TextInput::make('key')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('price_from')
                                    ->numeric()
                                    ->prefix('$'),
                                TextInput::make('sort_order')
                                    ->required()
                                    ->numeric()
                                    ->default(0),
                                Toggle::make('is_featured')
                                    ->label('Top service')
                                    ->default(false),
                                TextInput::make('featured_sort_order')
                                    ->numeric(),
                                Toggle::make('is_active')
                                    ->default(true)
                                    ->required(),
                                Select::make('specialists')
                                    ->multiple()
                                    ->relationship(titleAttribute: 'name')
                                    ->getOptionLabelFromRecordUsing(fn ($record): string => (string) $record->name)
                                    ->searchable()
                                    ->preload()
                                    ->columnSpanFull(),
                            ]),
                        FileUpload::make('image')
                            ->image()
                            ->disk('public')
                            ->directory('services')
                            ->visibility('public')
                            ->imageEditor()
                            ->maxSize(4096)
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Fields::translations([
                    ['name' => 'title', 'label' => 'Title', 'required' => true, 'slugTarget' => 'slug'],
                    ['name' => 'slug', 'label' => 'Slug', 'required' => true],
                    ['name' => 'display_price', 'label' => 'Display price', 'type' => 'textarea', 'rows' => 3, 'full' => true],
                    ['name' => 'duration', 'label' => 'Duration'],
                    ['name' => 'skin_type', 'label' => 'Skin type'],
                    ['name' => 'summary', 'label' => 'Summary', 'type' => 'textarea', 'rows' => 3, 'required' => true, 'full' => true],
                    ['name' => 'benefits', 'label' => 'Benefits', 'type' => 'lines', 'rows' => 6],
                    ['name' => 'details', 'label' => 'Details', 'type' => 'rich-editor', 'full' => true, 'fileAttachmentsDirectory' => 'img/uploads/services/content', 'customBlocks' => [YouTubeVideoBlock::class]],
                    ['name' => 'note', 'label' => 'Note', 'type' => 'textarea', 'rows' => 3, 'full' => true],
                ]),
                Repeater::make('prices')
                    ->relationship()
                    ->schema([
                        Fields::translations([
                            ['name' => 'label', 'label' => 'Label', 'required' => true],
                            ['name' => 'display_price', 'label' => 'Display price'],
                            ['name' => 'duration', 'label' => 'Duration'],
                            ['name' => 'note', 'label' => 'Note', 'type' => 'textarea', 'rows' => 2, 'full' => true],
                        ]),
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
                    ])
                    ->columns(3)
                    ->orderColumn('sort_order')
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['label']['en'] ?? null)
                    ->addActionLabel('Add price')
                    ->columnSpanFull(),
            ]);
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
                TextColumn::make('category.title')
                    ->label('Category')
                    ->sortable(),
                TextColumn::make('price_from')
                    ->money('USD')
                    ->sortable(),
                ToggleColumn::make('is_featured')
                    ->label('Top'),
                TextColumn::make('featured_sort_order')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('sort_order')
                    ->numeric()
                    ->sortable(),
                ToggleColumn::make('is_active'),
            ])
            ->filters([
                TernaryFilter::make('is_featured')
                    ->label('Top service'),
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
            'index' => ListServices::route('/'),
            'create' => CreateService::route('/create'),
            'edit' => EditService::route('/{record}/edit'),
        ];
    }
}
