<?php

namespace App\Filament\Resources\DepositSettings;

use App\Filament\Resources\DepositSettings\Pages\CreateDepositSetting;
use App\Filament\Resources\DepositSettings\Pages\EditDepositSetting;
use App\Filament\Resources\DepositSettings\Pages\ListDepositSettings;
use App\Filament\Support\Fields;
use App\Models\DepositSetting;
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

class DepositSettingResource extends Resource
{
    protected static ?string $model = DepositSetting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static string|UnitEnum|null $navigationGroup = 'Contact';

    protected static ?int $navigationSort = 60;

    protected static ?string $modelLabel = 'deposit setting';

    protected static ?string $pluralModelLabel = 'deposit settings';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->prefix('$')
                    ->default(25),
                TextInput::make('currency')
                    ->required()
                    ->maxLength(3)
                    ->default('USD'),
                Toggle::make('is_active')
                    ->default(true)
                    ->required(),
                Fields::linesTextarea('payment_methods', 'In-studio payment methods'),
                Fields::linesTextarea('offsite_payment_methods', 'Offsite payment methods'),
                Fields::translations([
                    ['name' => 'eyebrow', 'label' => 'Eyebrow'],
                    ['name' => 'title', 'label' => 'Title'],
                    ['name' => 'text', 'label' => 'Text', 'type' => 'textarea', 'rows' => 4, 'full' => true],
                    ['name' => 'note', 'label' => 'Note', 'type' => 'textarea', 'rows' => 3, 'full' => true],
                ]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('amount')
                    ->money('USD')
                    ->sortable(),
                TextColumn::make('currency')
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
            'index' => ListDepositSettings::route('/'),
            'create' => CreateDepositSetting::route('/create'),
            'edit' => EditDepositSetting::route('/{record}/edit'),
        ];
    }
}
