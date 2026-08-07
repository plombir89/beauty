<?php

namespace App\Filament\Resources\Specialists;

use App\Filament\Forms\Components\RichEditor\RichContentCustomBlocks\YouTubeVideoBlock;
use App\Filament\Resources\Specialists\Pages\CreateSpecialist;
use App\Filament\Resources\Specialists\Pages\EditSpecialist;
use App\Filament\Resources\Specialists\Pages\ListSpecialists;
use App\Filament\Support\Fields;
use App\Models\Specialist;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
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
use UnitEnum;

class SpecialistResource extends Resource
{
    protected static ?string $model = Specialist::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static string|UnitEnum|null $navigationGroup = 'Services';

    protected static ?int $navigationSort = 40;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->default(true)
                    ->required(),
                Select::make('services')
                    ->multiple()
                    ->relationship(titleAttribute: 'title')
                    ->getOptionLabelFromRecordUsing(fn ($record): string => (string) $record->title)
                    ->searchable()
                    ->preload()
                    ->columnSpanFull(),
                FileUpload::make('image')
                    ->image()
                    ->disk('public')
                    ->directory('specialists')
                    ->visibility('public')
                    ->imageEditor()
                    ->maxSize(4096),
                Fields::translations([
                    ['name' => 'slug', 'label' => 'Slug', 'required' => true],
                    ['name' => 'name', 'label' => 'Name', 'required' => true],
                    ['name' => 'title', 'label' => 'Title'],
                    ['name' => 'bio', 'label' => 'Bio', 'type' => 'rich-editor', 'fileAttachmentsDirectory' => 'specialists/content', 'customBlocks' => [YouTubeVideoBlock::class], 'full' => true],
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
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('title')
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
            'index' => ListSpecialists::route('/'),
            'create' => CreateSpecialist::route('/create'),
            'edit' => EditSpecialist::route('/{record}/edit'),
        ];
    }
}
