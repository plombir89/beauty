<?php

namespace App\Support;

use Laravel\Head\Enums\OgType;
use Laravel\Head\Facades\Head;

class Seo
{
    /**
     * @param  array<string, string>  $alternates
     */
    public static function set(string $title, ?string $description = null, array $alternates = [], ?string $image = null): void
    {
        $canonical = $alternates[app()->getLocale()] ?? url()->current();
        $imageUrl = self::imageUrl($image);

        view()->share('alternateUrls', $alternates);

        Head::title($title, suffix: ' - Elegant Beauty Studio')
            ->canonical($canonical)
            ->og(
                type: OgType::Website,
                title: $title,
                description: $description,
                url: $canonical,
                image: $imageUrl,
                siteName: 'Elegant Beauty Studio',
            )
            ->twitter(title: $title, description: $description, image: $imageUrl);

        if ($description !== null) {
            Head::description($description);
        }

        if ($alternates !== []) {
            Head::alternates($alternates);
            Head::link('alternate', $canonical, ['hreflang' => 'x-default']);
        }
    }

    private static function imageUrl(?string $image): ?string
    {
        if ($image === null || blank($image)) {
            return null;
        }

        if (filter_var($image, FILTER_VALIDATE_URL) !== false) {
            return $image;
        }

        return asset('storage/'.ltrim($image, '/'));
    }
}
