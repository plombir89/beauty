<?php

namespace App\Support;

use App\Filament\Forms\Components\RichEditor\RichContentCustomBlocks\YouTubeVideoBlock;
use DOMDocument;
use DOMElement;
use Filament\Forms\Components\RichEditor\RichContentRenderer;
use Illuminate\Support\HtmlString;

class BlogRichContentRenderer
{
    public static function render(mixed $content): HtmlString
    {
        $html = RichContentRenderer::make(self::normalizeContent($content))
            ->customBlocks([YouTubeVideoBlock::class])
            ->fileAttachmentsDisk('public_uploads')
            ->fileAttachmentsVisibility('public')
            ->toHtml();

        return new HtmlString(self::replaceYouTubeMarkers($html));
    }

    /**
     * @return string|array<string, mixed>|null
     */
    private static function normalizeContent(mixed $content): string|array|null
    {
        if (is_array($content) && array_is_list($content)) {
            return collect($content)
                ->map(fn (mixed $paragraph): string => '<p>'.e((string) $paragraph).'</p>')
                ->implode('');
        }

        if (is_array($content) || is_string($content) || $content === null) {
            return $content;
        }

        return (string) $content;
    }

    private static function replaceYouTubeMarkers(string $html): string
    {
        if (! str_contains($html, YouTubeVideoBlock::LINK_CLASS)) {
            return $html;
        }

        $document = new DOMDocument('1.0', 'UTF-8');
        $previousErrors = libxml_use_internal_errors(true);

        $document->loadHTML(
            '<?xml encoding="UTF-8"><div id="blog-rich-content-root">'.$html.'</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD,
        );

        libxml_clear_errors();
        libxml_use_internal_errors($previousErrors);

        $links = (new \DOMXPath($document))->query(
            '//a[contains(concat(" ", normalize-space(@class), " "), " '.YouTubeVideoBlock::LINK_CLASS.' ")]',
        );

        if ($links === false) {
            return $html;
        }

        foreach ($links as $link) {
            if (! $link instanceof DOMElement || $link->parentNode === null) {
                continue;
            }

            $videoId = YouTubeVideoBlock::extractVideoId(
                html_entity_decode($link->getAttribute('href'), ENT_QUOTES | ENT_HTML5, 'UTF-8'),
            );

            if ($videoId === null) {
                continue;
            }

            $link->parentNode->replaceChild(self::createYouTubeEmbed($document, $videoId), $link);
        }

        $root = $document->getElementById('blog-rich-content-root');

        return $root instanceof DOMElement ? self::innerHtml($root) : $html;
    }

    private static function createYouTubeEmbed(DOMDocument $document, string $videoId): DOMElement
    {
        $wrapper = $document->createElement('div');
        $wrapper->setAttribute('class', 'blog-youtube-video');

        $iframe = $document->createElement('iframe');
        $iframe->setAttribute('src', YouTubeVideoBlock::embedUrl($videoId));
        $iframe->setAttribute('title', 'YouTube video');
        $iframe->setAttribute('loading', 'lazy');
        $iframe->setAttribute('allow', 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share');
        $iframe->setAttribute('allowfullscreen', 'allowfullscreen');
        $iframe->setAttribute('referrerpolicy', 'strict-origin-when-cross-origin');

        $wrapper->appendChild($iframe);

        return $wrapper;
    }

    private static function innerHtml(DOMElement $element): string
    {
        $html = '';

        foreach ($element->childNodes as $childNode) {
            $childHtml = $element->ownerDocument->saveHTML($childNode);

            if ($childHtml !== false) {
                $html .= $childHtml;
            }
        }

        return $html;
    }
}
