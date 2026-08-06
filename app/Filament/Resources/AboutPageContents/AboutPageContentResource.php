<?php

namespace App\Filament\Resources\AboutPageContents;

use App\Filament\Forms\Components\RichEditor\RichContentCustomBlocks\YouTubeVideoBlock;
use App\Filament\Resources\AboutPageContents\Pages\EditAboutPageContent;
use App\Filament\Resources\AboutPageContents\Pages\ListAboutPageContents;
use App\Filament\Support\Fields;
use App\Models\AboutPageContent;
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

class AboutPageContentResource extends Resource
{
    protected static ?string $model = AboutPageContent::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string|UnitEnum|null $navigationGroup = 'About';

    protected static ?int $navigationSort = 20;

    protected static ?string $modelLabel = 'about page content';

    protected static ?string $pluralModelLabel = 'about page content';

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
                    ->directory('about')
                    ->visibility('public')
                    ->imageEditor()
                    ->maxSize(4096)
                    ->columnSpanFull(),
                Fields::translations([
                    ['name' => 'eyebrow', 'label' => 'Eyebrow'],
                    ['name' => 'title', 'label' => 'Title', 'required' => true],
                    ['name' => 'lead', 'label' => 'Lead', 'type' => 'textarea', 'rows' => 3, 'full' => true],
                    ['name' => 'text', 'label' => 'Text', 'type' => 'rich-editor', 'fileAttachmentsDirectory' => 'about/content', 'customBlocks' => [YouTubeVideoBlock::class], 'full' => true],
                    ['name' => 'text2', 'label' => 'Second text', 'type' => 'textarea', 'rows' => 5, 'full' => true],
                ]),
            ])
            ->columns(2);
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
            'index' => ListAboutPageContents::route('/'),
            'edit' => EditAboutPageContent::route('/{record}/edit'),
        ];
    }
}
