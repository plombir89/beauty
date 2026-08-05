<?php

namespace App\Support;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Service;
use App\Models\WhyChooseUsItem;

class LocalizedRoutes
{
    public const Locales = ['en', 'ru'];

    public static function locale(?string $locale = null): string
    {
        return in_array($locale, self::Locales, true) ? $locale : app()->getLocale();
    }

    /**
     * @return array<string, string>
     */
    public static function pageAlternates(string $routeName): array
    {
        return collect(self::Locales)
            ->mapWithKeys(fn (string $locale): array => [$locale => route($locale.'.'.$routeName)])
            ->all();
    }

    /**
     * @return array<string, string>
     */
    public static function serviceAlternates(Service $service): array
    {
        return collect(self::Locales)
            ->mapWithKeys(fn (string $locale): array => [
                $locale => route($locale.'.services.show', [
                    'serviceSlug' => $service->getTranslation('slug', $locale),
                ]),
            ])
            ->all();
    }

    /**
     * @return array<string, string>
     */
    public static function whyChooseUsAlternates(WhyChooseUsItem $item): array
    {
        return collect(self::Locales)
            ->mapWithKeys(fn (string $locale): array => [
                $locale => route($locale.'.why.show', [
                    'itemSlug' => $item->getTranslation('slug', $locale),
                ]),
            ])
            ->all();
    }

    /**
     * @return array<string, string>
     */
    public static function blogPostAlternates(BlogPost $post): array
    {
        return collect(self::Locales)
            ->mapWithKeys(fn (string $locale): array => [
                $locale => route($locale.'.blog.show', [
                    'postSlug' => $post->getTranslation('slug', $locale),
                ]),
            ])
            ->all();
    }

    /**
     * @return array<string, string>
     */
    public static function blogCategoryAlternates(BlogCategory $category): array
    {
        return collect(self::Locales)
            ->mapWithKeys(fn (string $locale): array => [
                $locale => route($locale.'.blog.category', [
                    'categorySlug' => $category->getTranslation('slug', $locale),
                ]),
            ])
            ->all();
    }
}
