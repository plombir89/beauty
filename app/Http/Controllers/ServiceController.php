<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\WaxingGroup;
use App\Support\LocalizedRoutes;
use App\Support\Seo;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index(Request $request): View
    {
        $page = Page::published()->where('key', 'services')->firstOrFail();
        $locale = app()->getLocale();
        $categoryKey = $request->string('category')->toString();
        $query = $request->string('q')->trim()->toString();
        $categories = ServiceCategory::active()->ordered()->get();
        $services = Service::query()
            ->with(['category', 'prices'])
            ->active()
            ->ordered()
            ->get();

        $filteredServices = $services
            ->when($categoryKey !== '', fn ($services) => $services->where('category.key', $categoryKey))
            ->when($query !== '', function ($services) use ($locale, $query) {
                $needle = Str::lower($query);

                return $services->filter(function (Service $service) use ($locale, $needle): bool {
                    $haystack = collect([
                        $service->getTranslation('title', $locale),
                        $service->getTranslation('summary', $locale),
                    ])
                        ->merge((array) $service->getTranslation('benefits', $locale))
                        ->implode(' ');

                    return Str::contains(Str::lower($haystack), $needle);
                });
            })
            ->values();

        Seo::set(
            title: $page->seo_title,
            description: $page->seo_description,
            alternates: LocalizedRoutes::pageAlternates('services.index'),
            image: $page->og_image,
        );

        return view('pages.services.index', [
            'page' => $page,
            'categories' => $categories,
            'services' => $filteredServices,
            'allServices' => $services,
            'activeCategoryKey' => $categoryKey,
            'query' => $query,
            'waxingGroups' => WaxingGroup::query()
                ->with('items')
                ->active()
                ->ordered()
                ->get(),
        ]);
    }

    public function show(string $serviceSlug): View
    {
        $locale = app()->getLocale();

        $service = Service::query()
            ->with(['category', 'prices', 'specialists'])
            ->active()
            ->whereJsonContainsLocale('slug', $locale, $serviceSlug)
            ->firstOrFail();

        Seo::set(
            title: $service->title,
            description: $service->summary,
            alternates: LocalizedRoutes::serviceAlternates($service),
            image: $service->image ? asset('storage/'.ltrim($service->image, '/')) : null,
        );

        return view('pages.services.show', [
            'service' => $service,
            'relatedServices' => Service::query()
                ->with(['category', 'prices'])
                ->active()
                ->whereBelongsTo($service->category, 'category')
                ->whereKeyNot($service->id)
                ->ordered()
                ->limit(3)
                ->get(),
        ]);
    }
}
