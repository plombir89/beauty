@extends('layouts.app')

@section('content')
    @php
        $locale = app()->getLocale();
        $activeCategoryKey = $activeCategoryKey ?? '';
        $query = $query ?? '';
        $totalServicesCount = $allServices->count();
    @endphp

    <section class="from-blush-50 to-cream bg-gradient-to-b pb-10 pt-14 sm:pt-20">
        <div class="mx-auto w-full max-w-7xl px-5 sm:px-8">
            <div class="max-w-3xl">
                <span class="eyebrow inline-flex items-center gap-3">
                    <span aria-hidden="true" class="h-px w-8 bg-gold-400"></span>
                    {{ $page->eyebrow }}
                </span>
                <h1 class="mt-5 text-5xl font-light leading-[1.05] sm:text-6xl">{{ $page->title }}</h1>
                <p class="text-ink-soft mt-5 text-lg leading-relaxed">{{ $page->subtitle }}</p>
            </div>
        </div>
    </section>

    <div class="mx-auto w-full max-w-7xl px-5 sm:px-8">
        <form method="GET" action="{{ route($locale.'.services.index') }}" class="border-sand-100 bg-cream/85 sticky top-[4.5rem] z-30 -mx-5 border-b px-5 py-4 backdrop-blur-xl sm:-mx-8 sm:px-8">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <ul class="-mx-1 flex snap-x gap-2 overflow-x-auto px-1 pb-1 [scrollbar-width:none] lg:flex-wrap lg:overflow-visible [&::-webkit-scrollbar]:hidden" aria-label="{{ $page->title }}">
                    <li class="shrink-0 snap-start">
                        <a href="{{ route($locale.'.services.index', $query ? ['q' => $query] : []) }}" aria-pressed="{{ $activeCategoryKey === '' ? 'true' : 'false' }}" class="flex shrink-0 items-center gap-2 rounded-full border px-4 py-2 text-sm whitespace-nowrap transition-all duration-300 {{ $activeCategoryKey === '' ? 'border-plum-600 bg-plum-600 text-white shadow-sm' : 'border-sand-200 bg-white text-ink-soft hover:border-plum-400 hover:text-plum-700' }}">
                            {{ __('site.services.all') }}
                            <span class="text-[0.65rem] tabular-nums {{ $activeCategoryKey === '' ? 'text-white/60' : 'text-ink-faint' }}">{{ $totalServicesCount }}</span>
                        </a>
                    </li>

                    @foreach ($categories as $category)
                        @php($isActive = $activeCategoryKey === $category->key)
                        <li class="shrink-0 snap-start">
                            <a href="{{ route($locale.'.services.index', array_filter(['category' => $category->key, 'q' => $query])) }}" aria-pressed="{{ $isActive ? 'true' : 'false' }}" class="flex shrink-0 items-center gap-2 rounded-full border px-4 py-2 text-sm whitespace-nowrap transition-all duration-300 {{ $isActive ? 'border-plum-600 bg-plum-600 text-white shadow-sm' : 'border-sand-200 bg-white text-ink-soft hover:border-plum-400 hover:text-plum-700' }}">
                                {{ $category->title }}
                                <span class="text-[0.65rem] tabular-nums {{ $isActive ? 'text-white/60' : 'text-ink-faint' }}">{{ $allServices->where('service_category_id', $category->id)->count() }}</span>
                            </a>
                        </li>
                    @endforeach

                    <li class="shrink-0 snap-start">
                        <a href="#waxing" class="border-gold-300 text-gold-600 hover:bg-gold-500 flex shrink-0 items-center rounded-full border px-4 py-2 text-sm whitespace-nowrap transition-all duration-300 hover:text-white">
                            {{ __('site.services.waxing_tab') }}
                        </a>
                    </li>
                </ul>

                <div class="relative lg:w-72">
                    @if ($activeCategoryKey)
                        <input type="hidden" name="category" value="{{ $activeCategoryKey }}">
                    @endif
                    <span aria-hidden="true" class="text-ink-faint pointer-events-none absolute left-4 top-1/2 size-4 -translate-y-1/2">⌕</span>
                    <input type="search" name="q" value="{{ $query }}" placeholder="{{ __('site.services.search_placeholder') }}" aria-label="{{ __('site.services.search_label') }}" class="border-sand-200 text-ink placeholder:text-ink-faint hover:border-sand-300 focus:border-plum-400 w-full rounded-full border bg-white py-2.5 pl-11 pr-10 text-sm transition-colors focus:outline-none">
                    @if ($query)
                        <a href="{{ route($locale.'.services.index', array_filter(['category' => $activeCategoryKey])) }}" aria-label="{{ __('site.services.clear_filters') }}" class="text-ink-faint hover:text-plum-700 absolute right-3 top-1/2 grid size-6 -translate-y-1/2 place-items-center rounded-full transition-colors">×</a>
                    @endif
                </div>
            </div>
        </form>

        <p aria-live="polite" class="text-ink-faint pt-6 text-sm">
            {{ trans_choice('site.services.results', $services->count(), ['count' => $services->count()]) }}
        </p>

        @if ($services->isNotEmpty())
            <ul class="grid gap-6 py-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($services as $service)
                    <li class="flex">
                        <div class="w-full">
                            <x-service-card :service="$service" />
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="border-sand-200 my-10 flex flex-col items-center rounded-4xl border border-dashed px-6 py-20 text-center">
                <span class="text-gold-400 text-3xl">⌕</span>
                <h2 class="mt-5 text-2xl font-medium">{{ __('site.services.empty_title') }}</h2>
                <p class="text-ink-soft mt-2 max-w-sm text-sm">{{ __('site.services.empty_text') }}</p>
                <a href="{{ route($locale.'.services.index') }}" class="btn-secondary mt-6">{{ __('site.services.clear_filters') }}</a>
            </div>
        @endif
    </div>

    @if ($waxingGroups->isNotEmpty())
        <section id="waxing" class="bg-sand-50 py-20 sm:py-28">
            <div class="mx-auto w-full max-w-7xl px-5 sm:px-8">
                <div class="mx-auto flex max-w-2xl flex-col items-center gap-4 text-center">
                    <span class="eyebrow inline-flex items-center gap-3">
                        <span aria-hidden="true" class="h-px w-8 bg-gold-400"></span>
                        {{ __('site.waxing.eyebrow') }}
                    </span>
                    <h2 class="text-4xl font-light leading-[1.1] sm:text-5xl">{{ __('site.waxing.title') }}</h2>
                    <p class="text-ink-soft text-base leading-relaxed sm:text-lg">{{ __('site.waxing.note') }}</p>
                </div>

                <div class="mt-14 grid gap-6 lg:grid-cols-3">
                    @foreach ($waxingGroups as $group)
                        <article class="border-sand-100 shadow-soft rounded-4xl border bg-white p-7">
                            <h3 class="text-plum-900 text-xl font-medium tracking-wide">{{ $group->title }}</h3>
                            <span aria-hidden="true" class="bg-gold-400 mt-4 block h-px w-10"></span>

                            <ul class="mt-5 flex flex-col">
                                @foreach ($group->items as $item)
                                    <li class="border-sand-100 flex items-baseline justify-between gap-4 border-b py-2.5 last:border-b-0">
                                        <span class="text-ink-soft text-sm">{{ $item->title }}</span>
                                        <span class="text-plum-900 text-sm font-semibold tabular-nums">{{ $item->display_price }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </article>
                    @endforeach
                </div>

                <div class="border-gold-300/40 from-blush-50 mt-8 rounded-4xl border bg-gradient-to-br to-white p-8 sm:p-10">
                    <h3 class="inline-flex items-center gap-3 text-2xl font-medium">{{ __('site.waxing.benefits_title') }}</h3>
                    <p class="text-ink-soft mt-4 max-w-3xl leading-relaxed">{{ __('site.waxing.benefits_text') }}</p>
                </div>
            </div>
        </section>
    @endif
@endsection
