<?php

namespace App\Http\Controllers;

use App\Models\BusinessHour;
use App\Models\ContactPolicy;
use App\Models\DepositSetting;
use App\Models\Page;
use App\Models\Service;
use App\Models\SocialLink;
use App\Models\StudioProfile;
use App\Support\LocalizedRoutes;
use App\Support\Seo;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function __invoke(Request $request): View
    {
        $page = Page::published()->where('key', 'contact')->firstOrFail();
        $requestedServiceSlug = $request->string('service')->toString();
        $initialServiceId = null;

        if ($requestedServiceSlug !== '') {
            $initialServiceId = Service::query()
                ->active()
                ->whereJsonContainsLocale('slug', app()->getLocale(), $requestedServiceSlug)
                ->value('id');
        }

        Seo::set(
            title: $page->seo_title,
            description: $page->seo_description,
            alternates: LocalizedRoutes::pageAlternates('contact'),
            image: $page->og_image,
        );

        return view('pages.contact', [
            'page' => $page,
            'studio' => StudioProfile::active()->first(),
            'socialLinks' => SocialLink::active()->ordered()->get(),
            'businessHours' => BusinessHour::active()->ordered()->get(),
            'depositSetting' => DepositSetting::active()->first(),
            'policy' => ContactPolicy::query()
                ->with(['items' => fn ($query) => $query->active()])
                ->active()
                ->where('key', 'main')
                ->first(),
            'initialServiceId' => $initialServiceId,
        ]);
    }
}
