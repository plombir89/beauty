<?php

namespace App\Filament\Resources\ContactPolicyItems;

use App\Filament\Resources\ContactPolicyItems\Pages\CreateContactPolicyItem;
use App\Filament\Resources\ContactPolicyItems\Pages\EditContactPolicyItem;
use App\Filament\Resources\ContactPolicyItems\Pages\ListContactPolicyItems;
use App\Filament\Support\Fields;
use App\Models\ContactPolicyItem;
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

class ContactPolicyItemResource extends Resource
{
    protected static ?string $model = ContactPolicyItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string|UnitEnum|null $navigationGroup = 'Contact';

    protected static ?int $navigationSort = 50;

    protected static ?string $modelLabel = 'contact policy item';

    protected static ?string $pluralModelLabel = 'contact policy items';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fields::relationshipSelect('contact_policy_id', 'policy', 'title', 'Policy')
                    ->required()
                    ->columnSpanFull(),
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
                    ['name' => 'text', 'label' => 'Text', 'type' => 'textarea', 'rows' => 4, 'full' => true],
                ]),
            ])
            ->columns(4);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('policy.title')
                    ->label('Policy')
                    ->sortable(),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
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
            'index' => ListContactPolicyItems::route('/'),
            'create' => CreateContactPolicyItem::route('/create'),
            'edit' => EditContactPolicyItem::route('/{record}/edit'),
        ];
    }
}
