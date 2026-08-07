@props(['studio' => null, 'alternateUrls' => [], 'socialLinks' => collect()])

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

<div
    x-data="{ open: false, scrolled: window.scrollY > 16 }"
    x-init="
        window.addEventListener('scroll', () => { scrolled = window.scrollY > 16 }, { passive: true });
        $watch('open', (value) => { document.body.classList.toggle('overflow-hidden', value) });
    "
    x-effect="if (open) { $nextTick(() => $refs.mobileMenuClose?.focus()) }"
    x-on:keydown.escape.window="open = false"
>
    <a href="#main" class="focus:bg-plum-600 sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[100] focus:rounded-full focus:px-5 focus:py-3 focus:text-sm focus:text-white">
        {{ __('site.nav.skip_to_content') }}
    </a>

    <header class="border-sand-100 fixed inset-x-0 top-0 z-50 border-b bg-cream/85 py-4 backdrop-blur-xl transition-all duration-500" :class="scrolled ? 'shadow-[0_8px_32px_-24px_rgb(42_31_40/0.5)]' : ''" :style="scrolled ? 'padding-top: 0.5rem; padding-bottom: 0.5rem;' : 'padding-top: 1rem; padding-bottom: 1rem;'">
        <div class="mx-auto w-full max-w-7xl px-5 sm:px-8">
            <div class="flex items-center justify-between gap-4">
                <a href="{{ route($locale.'.home') }}" class="group flex items-center gap-3" aria-label="Elegant Beauty Studio">
                    <img src="{{ asset('storage/site/logo.png') }}" alt="" width="48" height="48" class="h-12 w-auto mix-blend-multiply transition-all duration-500" :style="scrolled ? 'height: 2.5rem;' : 'height: 3rem;'">
                    <span class="hidden sm:block">
                        <span class="font-display text-plum-900 block text-lg leading-none font-medium tracking-wide">Elegant Beauty</span>
                        <span class="text-gold-600 text-[0.62rem] tracking-[0.32em] uppercase">Studio</span>
                    </span>
                </a>

                <nav aria-label="Main" class="hidden lg:block">
                    <ul class="flex items-center gap-1">
                        @foreach ($navItems as $item)
                            <li>
                                <a href="{{ $item['url'] }}" class="relative rounded-full px-4 py-2 text-md transition-colors duration-200 {{ $item['active'] ? 'font-semibold text-plum-700' : 'text-ink-soft hover:text-plum-700' }}">
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
                        <a href="{{ $studio->phone_href }}" class="text-ink-soft hover:text-plum-700 hidden items-center gap-2 text-md font-medium transition-colors xl:inline-flex">
                            <x-site.contact-icon name="phone" class="size-4" />
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
        </div>
    </header>

    <div x-cloak x-show="open" x-transition.opacity class="fixed inset-0 z-[60] bg-[#2f1833]/40 backdrop-blur-sm lg:hidden" x-on:click="open = false"></div>

    <aside
        x-cloak
        x-show="open"
        x-transition:enter="transition duration-300 ease-out"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition duration-200 ease-in"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        role="dialog"
        aria-modal="true"
        aria-label="{{ __('site.nav.open_menu') }}"
        class="fixed inset-y-0 right-0 z-[70] flex w-[min(22rem,90vw)] flex-col overflow-y-auto bg-cream p-6 shadow-2xl lg:hidden"
    >
        <div class="flex items-center justify-between">
            <img src="{{ asset('storage/site/logo.png') }}" alt="" class="h-10 w-auto mix-blend-multiply">
            <button x-ref="mobileMenuClose" type="button" x-on:click="open = false" aria-label="{{ __('site.nav.close_menu') }}" class="border-sand-200 text-plum-700 hover:border-plum-400 grid size-11 place-items-center rounded-full border bg-white transition-colors">
                <span class="text-xl leading-none" aria-hidden="true">&times;</span>
            </button>
        </div>

        <nav class="mt-10" aria-label="Mobile primary">
            <ul class="flex flex-col gap-1">
                @foreach ($navItems as $item)
                    <li>
                        <a href="{{ $item['url'] }}" x-on:click="open = false" class="font-display block rounded-2xl px-4 py-3 text-2xl transition-colors {{ $item['active'] ? 'bg-plum-50 text-plum-700' : 'text-plum-900 hover:bg-sand-50' }}">
                            {{ $item['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>

        <a href="{{ route($locale.'.contact') }}" x-on:click="open = false" class="btn-primary mt-6 w-full">{{ __('site.nav.book') }}</a>

        @if ($studio?->phone || $studio?->email || $studio?->address)
            <div class="border-sand-200 mt-8 flex flex-col gap-4 border-t pt-6 text-sm">
                @if ($studio?->phone)
                    <a href="{{ $studio->phone_href }}" class="text-ink-soft hover:text-plum-700 flex items-center gap-3 transition-colors">
                        <x-site.contact-icon name="phone" class="text-gold-500 size-4 shrink-0" />
                        {{ $studio->phone }}
                    </a>
                @endif
                @if ($studio?->email)
                    <a href="mailto:{{ $studio->email }}" class="text-ink-soft hover:text-plum-700 flex items-center gap-3 transition-colors">
                        <x-site.contact-icon name="mail" class="text-gold-500 size-4 shrink-0" />
                        <span class="break-all">{{ $studio->email }}</span>
                    </a>
                @endif
                @if ($studio?->address)
                    <a href="{{ $studio->maps_url }}" target="_blank" rel="noopener" class="text-ink-soft hover:text-plum-700 flex items-start gap-3 transition-colors">
                        <x-site.contact-icon name="map" class="text-gold-500 mt-0.5 size-4 shrink-0" />
                        <span>{{ $studio->address }}</span>
                    </a>
                @endif
            </div>
        @endif

        <div class="mt-auto flex flex-wrap items-center justify-between gap-4 pt-8">
            @if ($socialLinks->isNotEmpty())
                <div class="flex flex-wrap gap-2">
                    @foreach ($socialLinks as $socialLink)
                        <a href="{{ $socialLink->href }}" aria-label="{{ $socialLink->label }}" class="bg-sand-100 text-plum-700 hover:bg-plum-600 grid size-10 place-items-center rounded-full transition-all duration-300 hover:-translate-y-0.5 hover:text-white" target="_blank" rel="noopener">
                            <x-site.social-icon :name="$socialLink->key" />
                        </a>
                    @endforeach
                </div>
            @endif

            <div role="group" aria-label="{{ __('site.nav.change_language') }}" class="bg-sand-100 inline-flex items-center rounded-full p-1">
                @foreach (['en', 'ru'] as $switchLocale)
                    <a href="{{ $alternateUrls[$switchLocale] ?? route($switchLocale.'.home') }}" lang="{{ $switchLocale }}" aria-pressed="{{ $locale === $switchLocale ? 'true' : 'false' }}" class="rounded-full px-3 py-1.5 text-xs font-semibold tracking-widest transition-colors duration-200 {{ $locale === $switchLocale ? 'bg-plum-600 text-white shadow-sm' : 'text-ink-soft hover:text-plum-700' }}">
                        {{ strtoupper($switchLocale) }}
                    </a>
                @endforeach
            </div>
        </div>
    </aside>
</div>
