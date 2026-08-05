@extends('layouts.app')

@section('content')
    @php
        $locale = app()->getLocale();
        $heroStats = is_array($hero?->stats) ? $hero->stats : [];
    @endphp

    <section class="relative overflow-hidden pt-20">
        <div aria-hidden="true" class="from-blush-50 via-cream to-sand-50 absolute inset-0 -z-10 bg-gradient-to-br"></div>
        <div aria-hidden="true" class="bg-blush-200/40 absolute -right-32 -top-40 -z-10 size-[34rem] rounded-full blur-3xl"></div>
        <div aria-hidden="true" class="bg-gold-300/25 absolute -bottom-48 -left-40 -z-10 size-[30rem] rounded-full blur-3xl"></div>

        <div class="mx-auto w-full max-w-7xl px-5 py-16 sm:px-8 sm:py-24 lg:py-28">
            <div class="grid items-center gap-14 lg:grid-cols-[1.05fr_1fr] lg:gap-20">
                <div>
                    <span class="border-gold-300/60 text-gold-600 inline-flex items-center gap-2 rounded-full border bg-white/60 px-4 py-1.5 text-xs font-semibold uppercase tracking-[0.2em] backdrop-blur">
                        {{ $hero?->eyebrow }}
                    </span>

                    <h1 class="mt-6 text-5xl font-light leading-[1.02] sm:text-6xl lg:text-7xl">
                        Elegant
                        <span class="text-gold-500 font-normal italic"> Beauty </span>
                        Studio
                    </h1>

                    <p class="text-plum-700 mt-6 max-w-xl text-lg font-light leading-relaxed sm:text-xl">
                        {{ $hero?->lead ?? $page->subtitle }}
                    </p>

                    <p class="text-ink-soft mt-4 max-w-xl leading-relaxed">
                        {{ $hero?->text }}
                    </p>

                    <div class="mt-9 flex flex-wrap gap-3">
                        <a href="{{ route($locale.'.contact') }}" class="btn-primary px-8 py-4 text-base">
                            {{ $hero?->primary_label ?: __('site.common.book_now') }}
                        </a>
                        <a href="{{ route($locale.'.services.index') }}" class="btn-secondary px-8 py-4 text-base">
                            {{ $hero?->secondary_label ?: __('site.common.all_services') }}
                        </a>
                    </div>

                    @if ($heroStats !== [])
                        <dl class="border-sand-200 mt-12 grid max-w-lg grid-cols-3 gap-4 border-t pt-8">
                            @foreach ($heroStats as $stat)
                                <div>
                                    <dt class="sr-only">{{ $stat['label'] ?? '' }}</dt>
                                    <dd>
                                        <span class="font-display text-plum-900 mt-2 block text-3xl leading-none">{{ $stat['value'] ?? '' }}</span>
                                        <span class="text-ink-soft mt-1.5 block text-xs leading-snug">{{ $stat['label'] ?? '' }}</span>
                                    </dd>
                                </div>
                            @endforeach
                        </dl>
                    @endif
                </div>

                <div class="relative">
                    @if ($hero?->image)
                        <img src="{{ asset($hero->image) }}" alt="{{ $hero->title }}" class="aspect-[4/5] w-full rounded-[3rem] object-cover shadow-lift sm:aspect-[5/6]">
                    @endif

                    <div class="border-sand-100 shadow-soft absolute -bottom-6 -left-4 hidden w-56 rounded-3xl border bg-white/95 p-5 backdrop-blur sm:block lg:-left-10">
                        <p class="eyebrow">{{ __('site.common.certificates') }}</p>
                        <p class="text-ink-soft mt-2 text-sm leading-snug">{{ __('site.home.certificates_subtitle') }}</p>
                    </div>

                    <div aria-hidden="true" class="border-gold-300/50 absolute -right-5 -top-5 -z-10 hidden size-40 rounded-full border lg:block"></div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 sm:py-28">
        <div class="mx-auto w-full max-w-7xl px-5 sm:px-8">
            <div class="mx-auto flex max-w-2xl flex-col items-center gap-4 text-center">
                <span class="eyebrow inline-flex items-center gap-3"><span aria-hidden="true" class="h-px w-8 bg-gold-400"></span>{{ __('site.common.expertise') }}</span>
                <h2 class="text-4xl font-light leading-[1.1] sm:text-5xl">{{ __('site.home.expertise_title') }}</h2>
                <p class="text-ink-soft text-base leading-relaxed sm:text-lg">{{ $page->subtitle }}</p>
            </div>

            <div class="mt-14 grid gap-6 lg:grid-cols-3">
                @foreach ($expertisePillars as $pillar)
                    <article class="border-sand-100 shadow-soft hover:shadow-lift flex h-full flex-col overflow-hidden rounded-4xl border bg-white transition-shadow duration-500">
                        @if ($pillar->image)
                            <img src="{{ asset($pillar->image) }}" alt="" class="aspect-[16/10] w-full object-cover">
                        @endif

                        <div class="flex flex-1 flex-col p-7">
                            <span class="bg-plum-50 text-plum-600 grid size-11 place-items-center rounded-2xl">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>

                            <h3 class="mt-5 text-2xl font-medium">{{ $pillar->title }}</h3>
                            <p class="text-ink-soft mt-3 text-sm leading-relaxed">{{ $pillar->intro }}</p>

                            <ul class="mt-5 flex flex-col gap-2.5">
                                @foreach ((array) $pillar->items as $item)
                                    <li class="flex gap-3 text-sm leading-snug">
                                        <span class="text-gold-500 mt-0.5 shrink-0">✓</span>
                                        <span class="text-ink-soft">{{ $item }}</span>
                                    </li>
                                @endforeach
                            </ul>

                            @if ($pillar->note)
                                <p class="border-sand-100 text-ink-faint mt-auto border-t pt-5 text-xs italic">{{ $pillar->note }}</p>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-sand-50 py-20 sm:py-28">
        <div class="mx-auto w-full max-w-7xl px-5 sm:px-8">
            <div class="mx-auto flex max-w-2xl flex-col items-center gap-4 text-center">
                <span class="eyebrow inline-flex items-center gap-3"><span aria-hidden="true" class="h-px w-8 bg-gold-400"></span>{{ __('site.common.why_choose_us') }}</span>
                <h2 class="text-4xl font-light leading-[1.1] sm:text-5xl">{{ __('site.common.why_choose_us') }}</h2>
            </div>

            <div class="mt-14 grid gap-8 md:grid-cols-3">
                @foreach ($whyChooseUsItems as $item)
                    <article class="group">
                        <div class="relative">
                            @if ($item->image)
                                <img src="{{ asset($item->image) }}" alt="" class="aspect-square w-full rounded-4xl object-cover shadow-soft transition-transform duration-700 group-hover:scale-[1.04]">
                            @endif
                            <span class="font-display text-gold-500/90 absolute -left-2 -top-4 text-6xl leading-none">
                                0{{ $loop->iteration }}
                            </span>
                        </div>

                        <h3 class="mt-6 text-2xl font-medium">{{ $item->title }}</h3>
                        <p class="text-ink-soft mt-3 text-sm leading-relaxed">{{ $item->summary }}</p>
                        <a href="{{ route($locale.'.why.show', ['itemSlug' => $item->slug]) }}" class="text-gold-600 hover:text-plum-700 mt-4 inline-flex text-xs font-semibold uppercase tracking-[0.12em] transition-colors">
                            {{ __('site.common.read_more') }}
                        </a>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section id="top-services" class="py-20 sm:py-28">
        <div class="mx-auto w-full max-w-7xl px-5 sm:px-8">
            <div class="mx-auto flex max-w-2xl flex-col items-center gap-4 text-center">
                <span class="eyebrow inline-flex items-center gap-3"><span aria-hidden="true" class="h-px w-8 bg-gold-400"></span>{{ __('site.common.top_services') }}</span>
                <h2 class="text-4xl font-light leading-[1.1] sm:text-5xl">{{ __('site.common.top_services') }}</h2>
                <p class="text-ink-soft text-base leading-relaxed sm:text-lg">{{ __('site.home.top_services_subtitle') }}</p>
            </div>

            <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($topServices as $service)
                    <x-service-card :service="$service" />
                @endforeach
            </div>

            <div class="mt-12 flex justify-center">
                <a href="{{ route($locale.'.services.index') }}" class="btn-secondary px-8 py-4 text-base">
                    {{ __('site.common.all_services') }}
                </a>
            </div>
        </div>
    </section>

    @if ($aboutTeaser)
        <section class="bg-sand-50 py-20 sm:py-28">
            <div class="mx-auto grid w-full max-w-7xl items-center gap-12 px-5 sm:px-8 lg:grid-cols-[1fr_1.05fr] lg:gap-20">
                @if ($aboutTeaser->image)
                    <img src="{{ asset($aboutTeaser->image) }}" alt="" class="aspect-[4/3] w-full rounded-[2.5rem] object-cover shadow-lift">
                @endif
                <div>
                    <span class="eyebrow inline-flex items-center gap-3"><span aria-hidden="true" class="h-px w-8 bg-gold-400"></span>{{ $aboutTeaser->eyebrow }}</span>
                    <h2 class="mt-5 text-4xl font-light leading-[1.1] sm:text-5xl">{{ $aboutTeaser->title }}</h2>
                    <p class="text-ink-soft mt-5 leading-relaxed">{{ $aboutTeaser->text }}</p>
                    <a href="{{ route($locale.'.about') }}" class="btn-primary mt-8">
                        {{ $aboutTeaser->cta_label ?: __('site.common.learn_more') }}
                    </a>
                </div>
            </div>
        </section>
    @endif

    <section class="py-20 sm:py-28">
        <div class="mx-auto w-full max-w-7xl px-5 sm:px-8">
            <div class="mx-auto flex max-w-2xl flex-col items-center gap-4 text-center">
                <span class="eyebrow inline-flex items-center gap-3"><span aria-hidden="true" class="h-px w-8 bg-gold-400"></span>{{ __('site.common.certificates') }}</span>
                <h2 class="text-4xl font-light leading-[1.1] sm:text-5xl">{{ __('site.common.certificates') }}</h2>
            </div>

            <ul class="mt-14 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
                @foreach ($certificates as $certificate)
                    <li>
                        <a href="{{ asset($certificate->image) }}" target="_blank" rel="noopener" aria-label="{{ $certificate->alt }}" class="group shadow-soft hover:shadow-lift focus-visible:outline-plum-600 block w-full overflow-hidden rounded-3xl bg-white transition-all duration-500 hover:-translate-y-1">
                            <span class="relative block">
                                <img src="{{ asset($certificate->image) }}" alt="" class="aspect-[3/4] w-full object-cover transition-transform duration-700 group-hover:scale-105">
                                <span class="bg-plum-900/0 group-hover:bg-plum-900/30 absolute inset-0 grid place-items-center transition-colors duration-300">
                                    <span class="text-white opacity-0 transition-opacity duration-300 group-hover:opacity-100">↗</span>
                                </span>
                            </span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    <x-cta-block :block="$ctaBlock" />
@endsection
