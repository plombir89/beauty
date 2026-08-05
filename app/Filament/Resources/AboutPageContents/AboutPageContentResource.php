<?php

namespace App\Filament\Resources\AboutPageContents;

use App\Filament\Resources\AboutPageContents\Pages\CreateAboutPageContent;
use App\Filament\Resources\AboutPageContents\Pages\EditAboutPageContent;
use App\Filament\Resources\AboutPageContents\Pages\ListAboutPageContents;
use App\Filament\Support\Fields;
use App\Models\AboutPageContent;
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

class AboutPageContentResource extends Resource
{
    protected static ?string $model = AboutPageContent::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string|UnitEnum|null $navigationGroup = 'About';

    protected static ?int $navigationSort = 20;

    protected static ?string $modelLabel = 'about page content';

    protected static ?string $pluralModelLabel = 'about page content';

    protected static ?string $recordTitleAttribute = 'title';

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
                Fields::imageUpload('image', 'img/uploads/about'),
                Fields::translations([
                    ['name' => 'eyebrow', 'label' => 'Eyebrow'],
                    ['name' => 'title', 'label' => 'Title', 'required' => true],
                    ['name' => 'lead', 'label' => 'Lead', 'type' => 'textarea', 'rows' => 3, 'full' => true],
                    ['name' => 'text', 'label' => 'Text', 'type' => 'textarea', 'rows' => 5, 'full' => true],
                    ['name' => 'text2', 'label' => 'Second text', 'type' => 'textarea', 'rows' => 5, 'full' => true],
                ]),
            ])
            ->columns(2);
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
                ToggleColumn::make('is_active'),
            ])
            ->filters([
                TernaryFilter::make('is_active'),
            ])
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
            'index' => ListAboutPageContents::route('/'),
            'create' => CreateAboutPageContent::route('/create'),
            'edit' => EditAboutPageContent::route('/{record}/edit'),
        ];
    }
}
