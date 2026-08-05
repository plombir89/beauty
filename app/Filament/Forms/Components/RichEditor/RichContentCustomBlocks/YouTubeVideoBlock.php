<?php

namespace App\Filament\Forms\Components\RichEditor\RichContentCustomBlocks;

use Closure;
use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor\RichContentCustomBlock;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;

class YouTubeVideoBlock extends RichContentCustomBlock
{
    public const LINK_CLASS = 'youtube-video-block';

    public static function getId(): string
    {
        return 'youtube_video';
    }

    public static function getLabel(): string
    {
        return 'YouTube video';
    }

    public static function configureEditorAction(Action $action): Action
    {
        return $action
            ->modalDescription('Paste a YouTube link or video ID.')
            ->schema([
                TextInput::make('url')
                    ->label('YouTube URL or video ID')
                    ->required()
                    ->helperText('Supports youtube.com, youtu.be, Shorts, Live and embed URLs.')
                    ->rules([fn (): Closure => self::youtubeVideoRule()]),
                TextInput::make('title')
                    ->label('Title')
                    ->maxLength(120),
            ]);
    }

    /**
     * @param  array<string, mixed>  $config
     */
    public static function getPreviewLabel(array $config): string
    {
        $title = trim((string) ($config['title'] ?? ''));

        return $title === '' ? self::getLabel() : self::getLabel().': '.$title;
    }

    /**
     * @param  array<string, mixed>  $config
     */
    public static function toPreviewHtml(array $config): string
    {
        $videoId = self::extractVideoId((string) ($config['url'] ?? ''));

        return view('filament.forms.components.rich-editor.rich-content-custom-blocks.you-tube-video.preview', [
            'title' => trim((string) ($config['title'] ?? '')) ?: self::getLabel(),
            'watchUrl' => $videoId ? self::watchUrl($videoId) : null,
        ])->render();
    }

    /**
     * @param  array<string, mixed>  $config
     * @param  array<string, mixed>  $data
     */
    public static function toHtml(array $config, array $data): string
    {
        $videoId = self::extractVideoId((string) ($config['url'] ?? ''));

        if ($videoId === null) {
            return '';
        }

        return view('filament.forms.components.rich-editor.rich-content-custom-blocks.you-tube-video.index', [
            'linkClass' => self::LINK_CLASS,
            'title' => trim((string) ($config['title'] ?? '')) ?: self::getLabel(),
            'watchUrl' => self::watchUrl($videoId),
        ])->render();
    }

    public static function embedUrl(string $videoId): string
    {
        return "https://www.youtube.com/embed/{$videoId}";
    }

    public static function extractVideoId(string $value): ?string
    {
        $value = trim($value);

        if ($value === '') {
            return null;
        }

        if ($videoId = self::normalizeVideoId($value)) {
            return $videoId;
        }

        $url = Str::startsWith($value, ['http://', 'https://']) ? $value : "https://{$value}";
        $host = Str::lower((string) parse_url($url, PHP_URL_HOST));
        $path = trim((string) parse_url($url, PHP_URL_PATH), '/');

        if (in_array($host, ['youtu.be', 'www.youtu.be'], true)) {
            return self::normalizeVideoId(Str::before($path, '/'));
        }

        if (! in_array($host, ['youtube.com', 'www.youtube.com', 'm.youtube.com', 'music.youtube.com', 'youtube-nocookie.com', 'www.youtube-nocookie.com'], true)) {
            return null;
        }

        if ($path === 'watch') {
            parse_str((string) parse_url($url, PHP_URL_QUERY), $query);

            return self::normalizeVideoId($query['v'] ?? null);
        }

        $segments = explode('/', $path);

        if (in_array($segments[0], ['embed', 'shorts', 'live'], true)) {
            return self::normalizeVideoId($segments[1] ?? null);
        }

        return null;
    }

    private static function watchUrl(string $videoId): string
    {
        return "https://www.youtube.com/watch?v={$videoId}";
    }

    private static function youtubeVideoRule(): Closure
    {
        return function (string $attribute, mixed $value, Closure $fail): void {
            if (self::extractVideoId((string) $value) === null) {
                $fail('Enter a valid YouTube video URL or ID.');
            }
        };
    }

    private static function normalizeVideoId(mixed $value): ?string
    {
        $videoId = trim((string) $value);

        return preg_match('/^[A-Za-z0-9_-]{11}$/', $videoId) === 1 ? $videoId : null;
    }
}
