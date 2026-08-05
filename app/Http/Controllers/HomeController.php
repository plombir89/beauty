<?php

namespace App\Http\Controllers;

use App\Models\AboutTeaser;
use App\Models\Certificate;
use App\Models\CtaBlock;
use App\Models\ExpertisePillar;
use App\Models\HomeHeroBlock;
use App\Models\Page;
use App\Models\Service;
use App\Models\StudioProfile;
use App\Models\WhyChooseUsItem;
use App\Support\LocalizedRoutes;
use App\Support\Seo;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $page = Page::published()->where('key', 'home')->firstOrFail();

        Seo::set(
            title: $page->seo_title,
            description: $page->seo_description,
            alternates: LocalizedRoutes::pageAlternates('home'),
            image: $page->og_image,
        );

        return view('pages.home', [
            'page' => $page,
            'hero' => HomeHeroBlock::active()->where('key', 'home')->first(),
            'expertisePillars' => ExpertisePillar::active()->ordered()->get(),
            'whyChooseUsItems' => WhyChooseUsItem::active()->ordered()->get(),
            'topServices' => Service::query()
                ->with(['category', 'prices'])
                ->active()
                ->featured()
                ->orderBy('featured_sort_order')
                ->ordered()
                ->limit(6)
                ->get(),
            'aboutTeaser' => AboutTeaser::active()->where('key', 'home')->first(),
            'certificates' => Certificate::active()->ordered()->get(),
            'ctaBlock' => CtaBlock::active()->where('key', 'main')->first(),
            'studio' => StudioProfile::active()->first(),
        ]);
    }
}
