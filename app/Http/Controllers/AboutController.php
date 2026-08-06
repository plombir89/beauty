<?php

namespace App\Http\Controllers;

use App\Models\AboutPageContent;
use App\Models\AboutValue;
use App\Models\CtaBlock;
use App\Models\Page;
use App\Models\Specialist;
use App\Models\StudioProfile;
use App\Support\LocalizedRoutes;
use App\Support\Seo;
use Illuminate\Contracts\View\View;

class AboutController extends Controller
{
    public function __invoke(): View
    {
        $page = Page::published()->where('key', 'about')->firstOrFail();

        Seo::set(
            title: $page->seo_title,
            description: $page->seo_description,
            alternates: LocalizedRoutes::pageAlternates('about'),
            image: $page->og_image,
        );

        return view('pages.about', [
            'page' => $page,
            'content' => AboutPageContent::active()->where('key', 'main')->first(),
            'values' => AboutValue::active()->ordered()->get(),
            'specialists' => Specialist::query()
                ->with(['services' => fn ($query) => $query->active()->ordered()])
                ->active()
                ->ordered()
                ->get(),
            'ctaBlock' => CtaBlock::active()->where('key', 'about')->first(),
            'studio' => StudioProfile::active()->first(),
        ]);
    }
}
