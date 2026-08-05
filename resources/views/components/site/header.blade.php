@props(['studio' => null, 'alternateUrls' => []])

@php
    $locale = app()->getLocale();
    $otherLocale = $locale === 'en' ? 'ru' : 'en';
    $otherUrl = $alternateUrls[$otherLocale] ?? route($otherLocale.'.home');
    $navItems = [
        ['label' => __('site.nav.home'), 'url' => route($locale.'.home'), 'active' => request()->routeIs($locale.'.home')],
        ['label' => __('site.nav.services'), 'url' => route($locale.'.services.index'), 'active' => request()->routeIs($locale.'.services.*')],
        ['label' => __('site.nav.about'), 'url' => route($locale.'.about'), 'active' => request()->routeIs($locale.'.about')],
        ['label' => __('site.nav.blog'), 'url' => route($locale.'.blog.index'), 'active' => request()->routeIs($locale.'.blog.*')],
        ['label' => __('site.nav.contact'), 'url' => route($locale.'.contact'), 'active' => request()->routeIs($locale.'.contact')],
    ];
@endphp

<a href="#main" class="focus:bg-plum-600 sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-50 focus:rounded-full focus:px-5 focus:py-3 focus:text-sm focus:text-white">
    {{ __('site.nav.skip_to_content') }}
</a>

<header x-data="{ open: false, scrolled: window.scrollY > 16 }" x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 16 }, { passive: true })" class="fixed inset-x-0 top-0 z-50 transition-all duration-500" :class="scrolled ? 'border-sand-100 border-b bg-cream/85 py-2 shadow-[0_8px_32px_-24px_rgb(42_31_40/0.5)] backdrop-blur-xl' : 'border-b border-transparent py-4'">
    <div class="mx-auto w-full max-w-7xl px-5 sm:px-8">
        <div class="flex items-center justify-between gap-4">
            <a href="{{ route($locale.'.home') }}" class="group flex items-center gap-3" aria-label="Elegant Beauty Studio">
                <img src="{{ asset('img/logo.png') }}" alt="" class="w-auto mix-blend-multiply transition-all duration-500" :class="scrolled ? 'h-10' : 'h-12'">
                <span class="hidden sm:block">
                    <span class="font-display text-plum-900 block text-lg leading-none font-medium tracking-wide">Elegant Beauty</span>
                    <span class="text-gold-600 text-[0.62rem] tracking-[0.32em] uppercase">Studio</span>
                </span>
            </a>

            <nav aria-label="Main" class="hidden lg:block">
                <ul class="flex items-center gap-1">
                    @foreach ($navItems as $item)
                        <li>
                            <a href="{{ $item['url'] }}" class="relative rounded-full px-4 py-2 text-sm transition-colors duration-200 {{ $item['active'] ? 'font-semibold text-plum-700' : 'text-ink-soft hover:text-plum-700' }}">
                                {{ $item['label'] }}
                                @if ($item['active'])
                                    <span aria-hidden="true" class="absolute inset-x-4 -bottom-0.5 h-px bg-gold-400"></span>
                                @endif
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>

            <div class="flex items-center gap-2 sm:gap-3">
                @if ($studio?->phone)
                    <a href="{{ $studio->phone_href }}" class="text-ink-soft hover:text-plum-700 hidden items-center gap-2 text-sm font-medium transition-colors xl:inline-flex">
                        {{ $studio->phone }}
                    </a>
                @endif

                <div role="group" aria-label="{{ __('site.nav.change_language') }}" class="bg-sand-100 hidden items-center rounded-full p-1 sm:inline-flex">
                    @foreach (['en', 'ru'] as $switchLocale)
                        <a href="{{ $alternateUrls[$switchLocale] ?? route($switchLocale.'.home') }}" lang="{{ $switchLocale }}" aria-pressed="{{ $locale === $switchLocale ? 'true' : 'false' }}" class="rounded-full px-3 py-1.5 text-xs font-semibold tracking-widest transition-colors duration-200 {{ $locale === $switchLocale ? 'bg-plum-600 text-white shadow-sm' : 'text-ink-soft hover:text-plum-700' }}">
                            {{ strtoupper($switchLocale) }}
                        </a>
                    @endforeach
                </div>

                <a href="{{ route($locale.'.contact') }}" class="btn-primary hidden px-4 py-2 md:inline-flex">
                    {{ __('site.nav.book') }}
                </a>

                <button type="button" x-on:click="open = true" aria-label="{{ __('site.nav.open_menu') }}" :aria-expanded="open.toString()" class="border-sand-200 text-plum-700 hover:border-plum-400 grid size-11 place-items-center rounded-full border bg-white/70 transition-colors lg:hidden">
                    <span class="h-0.5 w-5 bg-current before:block before:h-0.5 before:w-5 before:-translate-y-1.5 before:bg-current before:content-[''] after:block after:h-0.5 after:w-5 after:translate-y-1 after:bg-current after:content-['']"></span>
                </button>
            </div>
        </div>

        <div x-cloak x-show="open" x-transition.opacity class="fixed inset-0 z-50 bg-plum-900/30 backdrop-blur-sm lg:hidden" x-on:click="open = false"></div>
        <aside x-cloak x-show="open" x-transition class="fixed bottom-0 right-0 top-0 z-50 flex w-full max-w-sm flex-col bg-cream p-5 shadow-lift lg:hidden">
            <div class="flex items-center justify-between">
                <span class="font-display text-plum-900 text-xl">Elegant Beauty</span>
                <button type="button" x-on:click="open = false" class="border-sand-200 text-plum-700 grid size-10 place-items-center rounded-full border bg-white">
                    <span class="text-xl leading-none">&times;</span>
                </button>
            </div>

            <nav class="mt-10" aria-label="Mobile primary">
                <ul class="flex flex-col gap-1">
                @foreach ($navItems as $item)
                    <li>
                        <a href="{{ $item['url'] }}" class="font-display block rounded-2xl px-4 py-3 text-2xl transition-colors {{ $item['active'] ? 'bg-plum-50 text-plum-700' : 'text-plum-900 hover:bg-sand-50' }}">
                            {{ $item['label'] }}
                        </a>
                    </li>
                @endforeach
                </ul>
            </nav>

            <div class="mt-auto grid gap-3">
                <div role="group" aria-label="{{ __('site.nav.change_language') }}" class="bg-sand-100 inline-flex items-center justify-center rounded-full p-1">
                    @foreach (['en', 'ru'] as $switchLocale)
                        <a href="{{ $alternateUrls[$switchLocale] ?? route($switchLocale.'.home') }}" lang="{{ $switchLocale }}" aria-pressed="{{ $locale === $switchLocale ? 'true' : 'false' }}" class="rounded-full px-3 py-1.5 text-xs font-semibold tracking-widest transition-colors duration-200 {{ $locale === $switchLocale ? 'bg-plum-600 text-white shadow-sm' : 'text-ink-soft hover:text-plum-700' }}">
                            {{ strtoupper($switchLocale) }}
                        </a>
                    @endforeach
                </div>
                <a href="{{ route($locale.'.contact') }}" class="btn-primary w-full">{{ __('site.nav.book') }}</a>
            </div>
        </aside>
    </div>
</header>
