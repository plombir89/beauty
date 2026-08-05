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

        view()->share('alternateUrls', $alternates);

        Head::title($title, suffix: ' - Elegant Beauty Studio')
            ->canonical($canonical)
            ->og(
                type: OgType::Website,
                title: $title,
                description: $description,
                url: $canonical,
                image: $image,
                siteName: 'Elegant Beauty Studio',
            )
            ->twitter(title: $title, description: $description, image: $image);

        if ($description !== null) {
            Head::description($description);
        }

        if ($alternates !== []) {
            Head::alternates($alternates);
            Head::link('alternate', $canonical, ['hreflang' => 'x-default']);
        }
    }
}
