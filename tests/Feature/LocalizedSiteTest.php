<?php

use App\Models\AboutPageContent;
use App\Models\AboutTeaser;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Certificate;
use App\Models\ContactPolicy;
use App\Models\CtaBlock;
use App\Models\ExpertisePillar;
use App\Models\HomeHeroBlock;
use App\Models\Page;
use App\Models\Service;
use App\Models\Specialist;
use App\Models\StudioProfile;
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

test('service and certificate images render from public storage', function (): void {
    $service = Service::query()
        ->active()
        ->whereNotNull('image')
        ->firstOrFail();
    $certificate = Certificate::query()
        ->active()
        ->firstOrFail();

    expect($service->image)->toStartWith('services/');
    expect($certificate->image)->toStartWith('certificates/');

    $service->update([
        'image' => 'services/list-page-test.jpg',
        'detail_image' => 'services/detail-page-test.jpg',
    ]);

    $this->get(route('en.services.index'))
        ->assertOk()
        ->assertSee('storage/services/list-page-test.jpg', false);

    $this->get(route('en.services.show', ['serviceSlug' => $service->getTranslation('slug', 'en')]))
        ->assertOk()
        ->assertSee('storage/services/detail-page-test.jpg', false)
        ->assertDontSee('storage/services/list-page-test.jpg', false);

    $service->update(['detail_image' => null]);

    $this->get(route('en.services.show', ['serviceSlug' => $service->getTranslation('slug', 'en')]))
        ->assertOk()
        ->assertSee('storage/services/list-page-test.jpg', false);

    $this->get('/en')
        ->assertOk()
        ->assertSee('storage/'.ltrim((string) $certificate->image, '/'), false);
});

test('home hero image renders from public storage', function (): void {
    $hero = HomeHeroBlock::query()
        ->active()
        ->where('key', 'home')
        ->firstOrFail();

    expect($hero->image)->toStartWith('home/');

    $this->get('/en')
        ->assertOk()
        ->assertSee('storage/'.ltrim((string) $hero->image, '/'), false);
});

test('expertise pillar images render from public storage', function (): void {
    $pillar = ExpertisePillar::query()
        ->active()
        ->whereNotNull('image')
        ->firstOrFail();

    expect($pillar->image)->toStartWith('pillars/');

    $this->get('/en')
        ->assertOk()
        ->assertSee('storage/'.ltrim((string) $pillar->image, '/'), false);
});

test('why choose images render from public storage', function (): void {
    $item = WhyChooseUsItem::query()
        ->active()
        ->whereNotNull('image')
        ->firstOrFail();

    expect($item->image)->toStartWith('choose/');

    $this->get('/en')
        ->assertOk()
        ->assertSee('storage/'.ltrim((string) $item->image, '/'), false);

    $this->get(route('en.why.show', ['itemSlug' => $item->getTranslation('slug', 'en')]))
        ->assertOk()
        ->assertSee('storage/'.ltrim((string) $item->image, '/'), false);
});

test('why choose detail rich text renders as html', function (): void {
    $item = WhyChooseUsItem::query()
        ->active()
        ->firstOrFail();

    $item->setTranslations('body', [
        'en' => '<p><strong>Rich reason copy</strong></p>',
        'ru' => '<p><strong>Расширенный текст причины</strong></p>',
    ]);
    $item->save();

    $this->get(route('en.why.show', ['itemSlug' => $item->getTranslation('slug', 'en')]))
        ->assertOk()
        ->assertSee('blog-rich-content', false)
        ->assertSee('<strong>Rich reason copy</strong>', false)
        ->assertDontSee(e('<strong>Rich reason copy</strong>'), false);
});

test('about page content image renders from public storage', function (): void {
    $content = AboutPageContent::query()
        ->active()
        ->where('key', 'main')
        ->firstOrFail();

    expect($content->image)->toStartWith('about/');

    $this->get('/en/about')
        ->assertOk()
        ->assertSee('storage/'.ltrim((string) $content->image, '/'), false);
});

test('about page content rich text renders as html', function (): void {
    $content = AboutPageContent::query()
        ->active()
        ->where('key', 'main')
        ->firstOrFail();

    $content->setTranslations('text', [
        'en' => '<p><strong>Rich care copy</strong></p>',
        'ru' => '<p><strong>Расширенный текст</strong></p>',
    ]);
    $content->setTranslations('text2', [
        'en' => '<p><em>Second rich care copy</em></p>',
        'ru' => '<p><em>Второй расширенный текст</em></p>',
    ]);
    $content->save();

    $this->get('/en/about')
        ->assertOk()
        ->assertSee('about-rich-content', false)
        ->assertSee('<strong>Rich care copy</strong>', false)
        ->assertSee('<em>Second rich care copy</em>', false)
        ->assertDontSee(e('<strong>Rich care copy</strong>'), false)
        ->assertDontSee(e('<em>Second rich care copy</em>'), false);
});

test('home about teaser image renders from public storage', function (): void {
    $teaser = AboutTeaser::query()
        ->active()
        ->where('key', 'home')
        ->firstOrFail();

    expect($teaser->image)->toStartWith('about/');

    $this->get('/en')
        ->assertOk()
        ->assertSee('storage/'.ltrim((string) $teaser->image, '/'), false);
});

test('blog images render from public storage', function (): void {
    $post = BlogPost::query()
        ->published()
        ->whereNotNull('image')
        ->firstOrFail();

    expect($post->image)->toStartWith('blog/');

    $this->get('/en/blog')
        ->assertOk()
        ->assertSee('storage/'.ltrim((string) $post->image, '/'), false);

    $this->get(route('en.blog.show', ['postSlug' => $post->getTranslation('slug', 'en')]))
        ->assertOk()
        ->assertSee('storage/'.ltrim((string) $post->image, '/'), false);
});

test('seo and site logo images render from public storage', function (): void {
    $page = Page::query()
        ->published()
        ->where('key', 'home')
        ->firstOrFail();

    expect($page->og_image)->toStartWith('seo/');

    $this->get('/en')
        ->assertOk()
        ->assertSee('storage/'.ltrim((string) $page->og_image, '/'), false)
        ->assertSee('storage/site/logo.png', false)
        ->assertSee('storage/site/logo-white.png', false);
});

test('about page renders active team specialists with storage images', function (): void {
    $specialist = Specialist::query()
        ->with('services')
        ->active()
        ->whereHas('services')
        ->firstOrFail();

    $specialist->update(['image' => 'specialists/team-member.jpg']);
    $specialist->setTranslations('bio', [
        'en' => '<p><strong>Specialist rich bio</strong></p>',
        'ru' => '<p><strong>Описание специалиста</strong></p>',
    ]);
    $specialist->save();

    $service = $specialist->services->firstOrFail();

    $this->get('/en/about')
        ->assertOk()
        ->assertSee(__('site.about.team_title'))
        ->assertSee($specialist->getTranslation('name', 'en'))
        ->assertSee($specialist->getTranslation('title', 'en'))
        ->assertSee($service->getTranslation('title', 'en'))
        ->assertSee('<strong>Specialist rich bio</strong>', false)
        ->assertDontSee(e('<strong>Specialist rich bio</strong>'), false)
        ->assertSee('storage/specialists/team-member.jpg', false);
});

test('contact policy rich text renders as html', function (): void {
    $policy = ContactPolicy::query()
        ->with('items')
        ->active()
        ->where('key', 'main')
        ->firstOrFail();
    $item = $policy->items->firstOrFail();

    $policy->setTranslations('intro', [
        'en' => '<p><strong>Policy rich intro</strong></p>',
        'ru' => '<p><strong>Текст политики</strong></p>',
    ]);
    $policy->save();

    $item->setTranslations('text', [
        'en' => '<p><em>Policy item rich text</em></p>',
        'ru' => '<p><em>Пункт политики</em></p>',
    ]);
    $item->save();

    $this->get('/en/contact')
        ->assertOk()
        ->assertSee('<strong>Policy rich intro</strong>', false)
        ->assertSee('<em>Policy item rich text</em>', false)
        ->assertDontSee(e('<strong>Policy rich intro</strong>'), false)
        ->assertDontSee(e('<em>Policy item rich text</em>'), false);
});

test('home page shows the main CTA block', function (): void {
    $ctaBlock = CtaBlock::query()->where('key', 'main')->firstOrFail();
    $studio = StudioProfile::query()->active()->firstOrFail();

    $this->get('/en')
        ->assertOk()
        ->assertSee($ctaBlock->getTranslation('text', 'en'))
        ->assertSee($ctaBlock->getTranslation('primary_label', 'en'))
        ->assertSeeInOrder([
            $ctaBlock->getTranslation('text', 'en'),
            'href="'.$studio->phone_href.'"',
            'Call '.$studio->phone,
        ], false);
});

test('home page hides expertise block when no expertise pillars are visible', function (): void {
    ExpertisePillar::query()->update(['is_active' => false]);

    $this->get('/en')
        ->assertOk()
        ->assertDontSee(__('site.common.expertise'))
        ->assertDontSee(__('site.home.expertise_title'));
});

test('scheduled blog posts stay hidden until their publish date', function (): void {
    $category = BlogCategory::query()->create([
        'key' => 'scheduled-news',
        'slug' => ['en' => 'scheduled-news', 'ru' => 'zaplanovannye-novosti'],
        'title' => ['en' => 'Scheduled news', 'ru' => 'Запланированные новости'],
        'description' => ['en' => 'Scheduled posts', 'ru' => 'Запланированные публикации'],
        'sort_order' => 99,
        'is_active' => true,
    ]);

    $publishedAt = now()->addDays(3);

    $post = BlogPost::query()->create([
        'blog_category_id' => $category->id,
        'slug' => ['en' => 'future-skin-care-news', 'ru' => 'budushchaya-novost-ob-ukhode'],
        'title' => ['en' => 'Future skin care news', 'ru' => 'Будущая новость об уходе'],
        'excerpt' => ['en' => 'This post is scheduled.', 'ru' => 'Эта публикация запланирована.'],
        'body' => ['en' => 'Scheduled body.', 'ru' => 'Запланированный текст.'],
        'published_at' => $publishedAt,
        'is_published' => true,
    ]);

    expect(BlogPost::query()->published()->whereKey($post->id)->exists())->toBeFalse();

    $this->get(route('en.blog.index'))
        ->assertOk()
        ->assertDontSee($post->getTranslation('title', 'en'));

    $this->get(route('en.blog.category', ['categorySlug' => $category->getTranslation('slug', 'en')]))
        ->assertOk()
        ->assertDontSee($post->getTranslation('title', 'en'));

    $this->get(route('en.blog.show', ['postSlug' => $post->getTranslation('slug', 'en')]))
        ->assertNotFound();

    $this->travelTo($publishedAt->addMinute());

    $this->get(route('en.blog.category', ['categorySlug' => $category->getTranslation('slug', 'en')]))
        ->assertOk()
        ->assertSee($post->getTranslation('title', 'en'));

    $this->get(route('en.blog.show', ['postSlug' => $post->getTranslation('slug', 'en')]))
        ->assertOk();

    $this->travelBack();
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
