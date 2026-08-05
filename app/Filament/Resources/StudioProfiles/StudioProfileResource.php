<?php

namespace App\Filament\Resources\StudioProfiles;

use App\Filament\Resources\StudioProfiles\Pages\CreateStudioProfile;
use App\Filament\Resources\StudioProfiles\Pages\EditStudioProfile;
use App\Filament\Resources\StudioProfiles\Pages\ListStudioProfiles;
use App\Filament\Support\Fields;
use App\Models\StudioProfile;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
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

class StudioProfileResource extends Resource
{
    protected static ?string $model = StudioProfile::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHomeModern;

    protected static string|UnitEnum|null $navigationGroup = 'Contact';

    protected static ?int $navigationSort = 10;

    protected static ?string $modelLabel = 'studio profile';

    protected static ?string $pluralModelLabel = 'studio profiles';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('phone')
                    ->tel(),
                TextInput::make('phone_href')
                    ->tel()
                    ->helperText('Example: tel:2538445804'),
                TextInput::make('email')
                    ->email(),
                TextInput::make('address')
                    ->maxLength(255)
                    ->columnSpanFull(),
                Textarea::make('maps_url')
                    ->rows(2)
                    ->columnSpanFull(),
                Textarea::make('map_embed_url')
                    ->rows(3)
                    ->columnSpanFull(),
                Fields::linesTextarea('languages', 'Languages', 3),
                Toggle::make('is_active')
                    ->default(true)
                    ->required(),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('phone')
                    ->searchable(),
                TextColumn::make('email')
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
            'index' => ListStudioProfiles::route('/'),
            'create' => CreateStudioProfile::route('/create'),
            'edit' => EditStudioProfile::route('/{record}/edit'),
        ];
    }
}
