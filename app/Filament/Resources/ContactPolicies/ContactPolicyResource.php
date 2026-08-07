<?php

namespace App\Filament\Resources\ContactPolicies;

use App\Filament\Forms\Components\RichEditor\RichContentCustomBlocks\YouTubeVideoBlock;
use App\Filament\Resources\ContactPolicies\Pages\CreateContactPolicy;
use App\Filament\Resources\ContactPolicies\Pages\EditContactPolicy;
use App\Filament\Resources\ContactPolicies\Pages\ListContactPolicies;
use App\Filament\Support\Fields;
use App\Models\ContactPolicy;
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

class ContactPolicyResource extends Resource
{
    protected static ?string $model = ContactPolicy::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string|UnitEnum|null $navigationGroup = 'Contact';

    protected static ?int $navigationSort = 40;

    protected static ?string $modelLabel = 'contact policy';

    protected static ?string $pluralModelLabel = 'contact policies';

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
                Fields::translations([
                    ['name' => 'title', 'label' => 'Title', 'required' => true],
                    ['name' => 'intro', 'label' => 'Intro', 'type' => 'rich-editor', 'fileAttachmentsDirectory' => 'contact/content', 'customBlocks' => [YouTubeVideoBlock::class], 'full' => true],
                    ['name' => 'kids_note', 'label' => 'Kids note', 'type' => 'textarea', 'rows' => 3, 'full' => true],
                    ['name' => 'thanks_note', 'label' => 'Thanks note', 'type' => 'textarea', 'rows' => 3, 'full' => true],
                ]),
                Repeater::make('items')
                    ->relationship()
                    ->schema([
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
                        Fields::translations([
                            ['name' => 'title', 'label' => 'Title', 'required' => true],
                            ['name' => 'text', 'label' => 'Text', 'type' => 'rich-editor', 'fileAttachmentsDirectory' => 'contact/content', 'customBlocks' => [YouTubeVideoBlock::class], 'full' => true],
                        ]),
                    ])
                    ->columns(4)
                    ->orderColumn('sort_order')
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['title']['en'] ?? null)
                    ->addActionLabel('Add policy item')
                    ->columnSpanFull(),
            ])
            ->columns(2);
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
            'index' => ListContactPolicies::route('/'),
            'create' => CreateContactPolicy::route('/create'),
            'edit' => EditContactPolicy::route('/{record}/edit'),
        ];
    }
}
