<?php

namespace App\Filament\Support;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Fields
{
    /**
     * @var array<string, string>
     */
    private const LOCALES = [
        'en' => 'English',
        'ru' => 'Russian',
    ];

    /**
     * @param  array<int, array<string, mixed>>  $fields
     */
    public static function translations(array $fields): Tabs
    {
        return Tabs::make('Translations')
            ->tabs(collect(self::LOCALES)
                ->map(fn (string $label, string $locale): Tab => Tab::make($label)
                    ->schema(self::localizedFields($fields, $locale))
                    ->columns(2))
                ->values()
                ->all())
            ->columnSpanFull();
    }

    public static function imageUpload(string $name = 'image', string $directory = 'img/uploads'): FileUpload
    {
        return FileUpload::make($name)
            ->image()
            ->disk('public_uploads')
            ->directory($directory)
            ->visibility('public')
            ->imageEditor()
            ->maxSize(4096)
            ->columnSpanFull();
    }

    public static function imageColumn(string $name = 'image'): ImageColumn
    {
        return ImageColumn::make($name)
            ->getStateUsing(fn (Model $record): ?string => self::publicAsset($record->getAttribute($name)))
            ->imageHeight(56)
            ->square();
    }

    public static function publicAsset(mixed $path): ?string
    {
        if (! is_string($path) || blank($path)) {
            return null;
        }

        if (filter_var($path, FILTER_VALIDATE_URL) !== false) {
            return $path;
        }

        return asset(ltrim($path, '/'));
    }

    public static function linesTextarea(string $name, string $label, int $rows = 6): Textarea
    {
        return Textarea::make($name)
            ->label($label)
            ->rows($rows)
            ->autosize()
            ->helperText('One item per line.')
            ->afterStateHydrated(fn (Textarea $component, mixed $state): Textarea => $component->state(self::linesToText($state)))
            ->dehydrateStateUsing(fn (mixed $state): array => self::textToLines($state))
            ->columnSpanFull();
    }

    public static function pairsTextarea(string $name, string $label, int $rows = 4): Textarea
    {
        return Textarea::make($name)
            ->label($label)
            ->rows($rows)
            ->autosize()
            ->helperText('One item per line, formatted as: value | label.')
            ->afterStateHydrated(fn (Textarea $component, mixed $state): Textarea => $component->state(self::pairsToText($state)))
            ->dehydrateStateUsing(fn (mixed $state): array => self::textToPairs($state))
            ->columnSpanFull();
    }

    public static function relationshipSelect(
        string $name,
        string $relationship,
        string $titleAttribute,
        ?string $label = null,
    ): Select {
        return Select::make($name)
            ->label($label)
            ->relationship(name: $relationship, titleAttribute: $titleAttribute)
            ->getOptionLabelFromRecordUsing(fn (Model $record): string => (string) $record->getAttribute($titleAttribute))
            ->searchable()
            ->preload();
    }

    /**
     * @param  array<int, array<string, mixed>>  $fields
     * @return array<int, Component>
     */
    private static function localizedFields(array $fields, string $locale): array
    {
        return collect($fields)
            ->map(fn (array $field): Component => self::localizedField($field, $locale))
            ->all();
    }

    /**
     * @param  array<string, mixed>  $field
     */
    private static function localizedField(array $field, string $locale): Component
    {
        $name = "{$field['name']}.{$locale}";
        $label = ($field['label'] ?? Str::headline((string) $field['name'])).' ('.strtoupper($locale).')';
        $type = $field['type'] ?? 'text';

        $component = match ($type) {
            'lines' => self::linesTextarea($name, $label, (int) ($field['rows'] ?? 6)),
            'pairs' => self::pairsTextarea($name, $label, (int) ($field['rows'] ?? 4)),
            'textarea' => Textarea::make($name)
                ->label($label)
                ->rows((int) ($field['rows'] ?? 4))
                ->autosize(),
            default => TextInput::make($name)->label($label),
        };

        if (($field['required'] ?? false) === true) {
            $component->required();
        }

        if (($field['full'] ?? false) === true) {
            $component->columnSpanFull();
        }

        if (isset($field['helper'])) {
            $component->helperText((string) $field['helper']);
        }

        if ($component instanceof TextInput && isset($field['maxLength'])) {
            $component->maxLength((int) $field['maxLength']);
        }

        return $component;
    }

    private static function linesToText(mixed $state): ?string
    {
        if ($state === null || $state === '') {
            return null;
        }

        if (! is_array($state)) {
            return (string) $state;
        }

        return collect($state)
            ->map(fn (mixed $line): string => is_scalar($line) ? trim((string) $line) : '')
            ->filter()
            ->implode(PHP_EOL);
    }

    /**
     * @return array<int, string>
     */
    private static function textToLines(mixed $state): array
    {
        if (! is_string($state)) {
            return [];
        }

        return collect(preg_split('/\r\n|\r|\n/', $state) ?: [])
            ->map(fn (string $line): string => trim($line))
            ->filter()
            ->values()
            ->all();
    }

    private static function pairsToText(mixed $state): ?string
    {
        if ($state === null || $state === '') {
            return null;
        }

        if (! is_array($state)) {
            return (string) $state;
        }

        return collect($state)
            ->map(function (mixed $row): ?string {
                if (! is_array($row)) {
                    return null;
                }

                $value = trim((string) ($row['value'] ?? ''));
                $label = trim((string) ($row['label'] ?? ''));

                return trim($value.' | '.$label, " \t\n\r\0\x0B|");
            })
            ->filter()
            ->implode(PHP_EOL);
    }

    /**
     * @return array<int, array{value: string, label: string}>
     */
    private static function textToPairs(mixed $state): array
    {
        if (! is_string($state)) {
            return [];
        }

        return collect(preg_split('/\r\n|\r|\n/', $state) ?: [])
            ->map(fn (string $line): string => trim($line))
            ->filter()
            ->map(function (string $line): array {
                [$value, $label] = array_pad(array_map('trim', explode('|', $line, 2)), 2, null);

                return [
                    'value' => $value,
                    'label' => $label ?: $value,
                ];
            })
            ->values()
            ->all();
    }
}
