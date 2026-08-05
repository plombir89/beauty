<?php

namespace App\Http\Controllers;

use App\Models\WhyChooseUsItem;
use App\Support\LocalizedRoutes;
use App\Support\Seo;
use Illuminate\Contracts\View\View;

class WhyChooseUsController extends Controller
{
    public function __invoke(string $itemSlug): View
    {
        $locale = app()->getLocale();

        $item = WhyChooseUsItem::query()
            ->active()
            ->whereJsonContainsLocale('slug', $locale, $itemSlug)
            ->firstOrFail();

        Seo::set(
            title: $item->title,
            description: $item->summary,
            alternates: LocalizedRoutes::whyChooseUsAlternates($item),
            image: $item->image,
        );

        return view('pages.why-choose-us.show', [
            'item' => $item,
            'items' => WhyChooseUsItem::active()->ordered()->get(),
        ]);
    }
}
