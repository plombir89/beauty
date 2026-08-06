@props(['studio' => null, 'socialLinks' => collect(), 'businessHours' => collect()])

@php
    $locale = app()->getLocale();
    $navItems = [
        ['label' => __('site.nav.home'), 'url' => route($locale.'.home')],
        ['label' => __('site.nav.services'), 'url' => route($locale.'.services.index')],
        ['label' => __('site.nav.about'), 'url' => route($locale.'.about')],
        ['label' => __('site.nav.blog'), 'url' => route($locale.'.blog.index')],
        ['label' => __('site.nav.contact'), 'url' => route($locale.'.contact')],
    ];
@endphp

<footer class="bg-plum-900 relative overflow-hidden text-white/80">
    <div aria-hidden="true" class="grain absolute inset-0 opacity-40"></div>

    <div class="relative mx-auto w-full max-w-7xl px-5 py-16 sm:px-8 sm:py-20">
        <div class="grid gap-12 md:grid-cols-2 lg:grid-cols-[1.4fr_1fr_1.1fr_1fr]">
            <div>
                <img src="{{ asset('storage/site/logo-white.png') }}" alt="Elegant Beauty Studio" class="h-24 w-auto" loading="lazy">
                <p class="mt-4 max-w-xs text-sm leading-relaxed text-white/60">
                    {{ __('site.footer.tagline') }}
                </p>
                <div class="mt-6 flex flex-wrap gap-2">
                    @foreach ($socialLinks as $socialLink)
                        <a href="{{ $socialLink->href }}" aria-label="{{ $socialLink->label }}" class="grid size-10 place-items-center rounded-full bg-white/10 text-white/80 transition-all duration-300 hover:-translate-y-0.5 hover:bg-white hover:text-plum-700" target="_blank" rel="noopener">
                            <x-site.social-icon :name="$socialLink->key" />
                        </a>
                    @endforeach
                </div>
            </div>

            <nav aria-label="Footer">
                <h3 class="text-gold-300 text-xs font-semibold tracking-[0.22em] uppercase">{{ __('site.footer.navigation') }}</h3>
                <ul class="mt-5 flex flex-col gap-3 text-sm">
                    @foreach ($navItems as $item)
                        <li>
                            <a href="{{ $item['url'] }}" class="text-white/70 transition-colors hover:text-white">{{ $item['label'] }}</a>
                        </li>
                    @endforeach
                </ul>
            </nav>

            <div>
                <h3 class="text-gold-300 text-xs font-semibold tracking-[0.22em] uppercase">{{ __('site.footer.contact') }}</h3>
                <ul class="mt-5 flex flex-col gap-4 text-sm">
                    @if ($studio?->phone)
                        <li><a href="{{ $studio->phone_href }}" class="text-white/70 transition-colors hover:text-white">{{ $studio->phone }}</a></li>
                    @endif
                    @if ($studio?->email)
                        <li><a href="mailto:{{ $studio->email }}" class="break-all text-white/70 transition-colors hover:text-white">{{ $studio->email }}</a></li>
                    @endif
                    @if ($studio?->address)
                        <li><a href="{{ $studio->maps_url }}" target="_blank" rel="noopener" class="text-white/70 transition-colors hover:text-white">{{ $studio->address }}</a></li>
                    @endif
                </ul>
            </div>

            <div>
                <h3 class="text-gold-300 text-xs font-semibold tracking-[0.22em] uppercase">{{ __('site.footer.hours') }}</h3>
                <ul class="mt-5 flex flex-col gap-3 text-sm">
                    @foreach ($businessHours as $hour)
                        <li class="flex items-baseline justify-between gap-4 border-b border-white/10 pb-2 text-white/70">
                            <span>{{ $hour->day_label }}</span>
                            <span class="text-white">{{ $hour->time_label }}</span>
                        </li>
                    @endforeach
                </ul>
                <p class="text-gold-300 mt-4 text-xs uppercase tracking-[0.16em]">{{ __('site.footer.appointment_only') }}</p>
            </div>
        </div>

        <div class="mt-14 flex flex-col items-center justify-between gap-4 border-t border-white/10 pt-8 sm:flex-row">
            <p class="text-xs text-white/50">{{ __('site.footer.rights', ['year' => now()->year]) }}</p>
            <a href="#top" class="inline-flex items-center gap-2 text-xs uppercase tracking-[0.16em] text-white/60 transition-colors hover:text-white">
                {{ __('site.footer.back_to_top') }}
            </a>
        </div>
    </div>
</footer>
