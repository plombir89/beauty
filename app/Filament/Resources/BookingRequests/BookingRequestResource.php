<?php

namespace App\Filament\Resources\BookingRequests;

use App\Filament\Resources\BookingRequests\Pages\CreateBookingRequest;
use App\Filament\Resources\BookingRequests\Pages\EditBookingRequest;
use App\Filament\Resources\BookingRequests\Pages\ListBookingRequests;
use App\Filament\Support\Fields;
use App\Models\BookingRequest;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class BookingRequestResource extends Resource
{
    protected static ?string $model = BookingRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendar;

    protected static string|UnitEnum|null $navigationGroup = 'Bookings';

    protected static ?int $navigationSort = 10;

    protected static ?string $modelLabel = 'booking request';

    protected static ?string $pluralModelLabel = 'booking requests';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fields::relationshipSelect('specialist_id', 'specialist', 'name', 'Specialist')
                    ->required(),
                Fields::relationshipSelect('service_id', 'service', 'title', 'Service')
                    ->required(),
                Select::make('locale')
                    ->options([
                        'en' => 'English',
                        'ru' => 'Russian',
                    ])
                    ->required()
                    ->default('en'),
                Select::make('status')
                    ->options(self::statusOptions())
                    ->required()
                    ->default(BookingRequest::StatusPending),
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('phone')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Textarea::make('message')
                    ->rows(4)
                    ->columnSpanFull(),
                Textarea::make('source_url')
                    ->rows(2)
                    ->columnSpanFull(),
                TextInput::make('ip_address')
                    ->maxLength(45),
                Textarea::make('user_agent')
                    ->rows(3)
                    ->columnSpanFull(),
            ])
            ->columns(4);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('phone')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('specialist.name')
                    ->label('Specialist')
                    ->searchable(),
                TextColumn::make('service.title')
                    ->label('Service')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->searchable(),
                TextColumn::make('locale')
                    ->badge(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(self::statusOptions()),
                SelectFilter::make('locale')
                    ->options([
                        'en' => 'English',
                        'ru' => 'Russian',
                    ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * @return array<string, string>
     */
    private static function statusOptions(): array
    {
        return [
            'pending' => 'Pending',
            'confirmed' => 'Confirmed',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBookingRequests::route('/'),
            'create' => CreateBookingRequest::route('/create'),
            'edit' => EditBookingRequest::route('/{record}/edit'),
        ];
    }
}
