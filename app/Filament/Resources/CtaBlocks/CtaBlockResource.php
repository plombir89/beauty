<?php

namespace App\Filament\Resources\CtaBlocks;

use App\Filament\Resources\CtaBlocks\Pages\CreateCtaBlock;
use App\Filament\Resources\CtaBlocks\Pages\EditCtaBlock;
use App\Filament\Resources\CtaBlocks\Pages\ListCtaBlocks;
use App\Filament\Support\Fields;
use App\Models\CtaBlock;
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

class CtaBlockResource extends Resource
{
    protected static ?string $model = CtaBlock::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static string|UnitEnum|null $navigationGroup = 'Home';

    protected static ?int $navigationSort = 50;

    protected static ?string $modelLabel = 'CTA block';

    protected static ?string $pluralModelLabel = 'CTA blocks';

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
                    ['name' => 'text', 'label' => 'Text', 'type' => 'textarea', 'rows' => 4, 'required' => true, 'full' => true],
                    ['name' => 'primary_label', 'label' => 'Primary button'],
                    ['name' => 'secondary_label', 'label' => 'Secondary button'],
                ]),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('text')
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
            'index' => ListCtaBlocks::route('/'),
            'create' => CreateCtaBlock::route('/create'),
            'edit' => EditCtaBlock::route('/{record}/edit'),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public static function normalizeFormData(array $data): array
    {
        $data['title'] = [
            'en' => data_get($data, 'text.en', ''),
            'ru' => data_get($data, 'text.ru', ''),
        ];

        return $data;
    }
}
