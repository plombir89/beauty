<?php

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Service;
use App\Models\WhyChooseUsItem;
use Database\Seeders\ElegantBeautySeeder;

beforeEach(function (): void {
    $this->seed(ElegantBeautySeeder::class);
});

test('localized static pages render on approved routes', function (): void {
    $this->get('/')->assertRedirect('/en');

    foreach (['/en', '/ru', '/en/services', '/ru/uslugy', '/en/about', '/ru/o-nas', '/en/contact', '/ru/kontakty', '/en/blog', '/ru/novosty'] as $path) {
        $this->get($path)->assertOk();
    }
});

test('localized detail pages use language specific slugs', function (): void {
    $service = Service::query()->active()->firstOrFail();
    $whyChooseUsItem = WhyChooseUsItem::query()->active()->firstOrFail();
    $blogCategory = BlogCategory::query()->active()->firstOrFail();
    $blogPost = BlogPost::query()->published()->firstOrFail();

    $this->get(route('en.services.show', ['serviceSlug' => $service->getTranslation('slug', 'en')]))->assertOk();
    $this->get(route('ru.services.show', ['serviceSlug' => $service->getTranslation('slug', 'ru')]))->assertOk();
    $this->get(route('en.why.show', ['itemSlug' => $whyChooseUsItem->getTranslation('slug', 'en')]))->assertOk();
    $this->get(route('ru.why.show', ['itemSlug' => $whyChooseUsItem->getTranslation('slug', 'ru')]))->assertOk();
    $this->get(route('en.blog.category', ['categorySlug' => $blogCategory->getTranslation('slug', 'en')]))->assertOk();
    $this->get(route('ru.blog.category', ['categorySlug' => $blogCategory->getTranslation('slug', 'ru')]))->assertOk();
    $this->get(route('en.blog.show', ['postSlug' => $blogPost->getTranslation('slug', 'en')]))->assertOk();
    $this->get(route('ru.blog.show', ['postSlug' => $blogPost->getTranslation('slug', 'ru')]))->assertOk();
});

test('home page shows six top services', function (): void {
    $topServices = Service::query()
        ->active()
        ->featured()
        ->orderBy('featured_sort_order')
        ->ordered()
        ->limit(6)
        ->get();

    expect($topServices)->toHaveCount(6);

    $response = $this->get('/en')->assertOk();

    foreach ($topServices as $service) {
        $response->assertSee($service->getTranslation('title', 'en'));
    }
});

test('service detail does not duplicate pricing blocks or render booking form', function (): void {
    $service = Service::query()
        ->with('prices')
        ->whereHas('prices')
        ->active()
        ->firstOrFail();

    $response = $this->get(route('en.services.show', ['serviceSlug' => $service->getTranslation('slug', 'en')]))
        ->assertOk();

    $response
        ->assertDontSee(__('site.booking.title'))
        ->assertDontSee(__('site.booking.intro'))
        ->assertDontSee('<h2 class="text-2xl font-medium">'.__('site.common.price').'</h2>', false);
});
