@extends('layouts.app')

@section('content')
    @php($locale = app()->getLocale())

    <section class="from-blush-50 to-cream bg-gradient-to-b py-16 pt-24 sm:py-24">
        <div class="mx-auto w-full max-w-7xl px-5 sm:px-8">
            <div class="grid items-center gap-12 lg:grid-cols-[1.05fr_1fr] lg:gap-20">
                <div>
                    <span class="eyebrow inline-flex items-center gap-3">
                        <span aria-hidden="true" class="h-px w-8 bg-gold-400"></span>
                        {{ $content?->eyebrow ?? $page->eyebrow }}
                    </span>

                    <h1 class="mt-5 text-5xl font-light leading-[1.05] sm:text-6xl">{{ $content?->title ?? $page->title }}</h1>

                    <p class="text-plum-700 mt-6 text-xl font-light leading-relaxed">{{ $content?->lead }}</p>
                </div>

                <div class="relative">
                    @if ($content?->image)
                        <img src="{{ asset($content->image) }}" alt="" class="aspect-[4/3] w-full rounded-[2.5rem] object-cover shadow-lift">
                    @endif
                    <div aria-hidden="true" class="border-gold-300/50 absolute -right-5 -top-5 -z-10 hidden size-36 rounded-full border lg:block"></div>
                </div>
            </div>
        </div>
    </section>

    @if ($content)
        <section class="py-16 sm:py-20">
            <div class="mx-auto w-full max-w-3xl px-5 sm:px-8">
                <div class="prose-lg flex flex-col gap-6">
                    <p class="text-ink-soft text-lg leading-relaxed">{{ $content->text }}</p>
                    <p class="text-ink-soft text-lg leading-relaxed">{{ $content->text2 }}</p>
                </div>
            </div>
        </section>
    @endif

    @if ($specialists->isNotEmpty())
        <section class="py-20 sm:py-24">
            <div class="mx-auto w-full max-w-7xl px-5 sm:px-8">
                <div class="mx-auto flex max-w-2xl flex-col items-center gap-4 text-center">
                    <span class="eyebrow inline-flex items-center gap-3"><span aria-hidden="true" class="h-px w-8 bg-gold-400"></span>{{ __('site.about.team_eyebrow') }}</span>
                    <h2 class="text-4xl font-light leading-[1.1] sm:text-5xl">{{ __('site.about.team_title') }}</h2>
                    <p class="text-ink-soft text-base leading-relaxed sm:text-lg">{{ __('site.about.team_subtitle') }}</p>
                </div>

                <div class="mt-14 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($specialists as $specialist)
                        <article class="border-sand-100 shadow-soft hover:shadow-lift flex h-full flex-col overflow-hidden rounded-4xl border bg-white transition-shadow duration-500">
                            @if ($specialist->image)
                                <img src="{{ asset('storage/'.ltrim($specialist->image, '/')) }}" alt="{{ $specialist->name }}" class="aspect-[4/5] w-full object-cover">
                            @else
                                <div class="from-blush-50 to-sand-50 grid aspect-[4/5] w-full place-items-center bg-gradient-to-br">
                                    <span class="font-display text-gold-500/80 text-7xl leading-none">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                </div>
                            @endif

                            <div class="flex flex-1 flex-col p-7">
                                @if ($specialist->title)
                                    <span class="eyebrow text-gold-600">{{ $specialist->title }}</span>
                                @endif

                                <h3 class="mt-4 text-2xl font-medium">{{ $specialist->name }}</h3>

                                @if ($specialist->bio)
                                    <p class="text-ink-soft mt-3 text-sm leading-relaxed">{{ $specialist->bio }}</p>
                                @endif

                                @if ($specialist->services->isNotEmpty())
                                    <div class="mt-6 flex flex-wrap gap-2">
                                        <span class="sr-only">{{ __('site.about.team_services') }}</span>
                                        @foreach ($specialist->services->take(4) as $service)
                                            <span class="border-sand-200 bg-sand-50 text-ink-soft rounded-full border px-3 py-1 text-xs">{{ $service->title }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="bg-sand-50 py-20 sm:py-24">
        <div class="mx-auto w-full max-w-7xl px-5 sm:px-8">
            <div class="mx-auto flex max-w-2xl flex-col items-center gap-4 text-center">
                <h2 class="text-4xl font-light leading-[1.1] sm:text-5xl">{{ __('site.about.values_title') }}</h2>
            </div>

            <div class="mt-12 grid gap-6 md:grid-cols-3">
                @foreach ($values as $value)
                    <article class="border-sand-100 shadow-soft rounded-4xl border bg-white p-8">
                        <span class="bg-plum-50 text-plum-600 grid size-12 place-items-center rounded-2xl">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <h3 class="mt-5 text-2xl font-medium">{{ $value->title }}</h3>
                        <p class="text-ink-soft mt-3 leading-relaxed">{{ $value->text }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <div class="pt-20">
        <x-cta-block :block="$ctaBlock" :studio="$studio" />
    </div>
@endsection
