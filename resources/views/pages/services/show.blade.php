@extends('layouts.app')

@section('content')
    @php($locale = app()->getLocale())

    <div class="from-blush-50 to-cream bg-gradient-to-b pb-20 pt-8">
        <div class="mx-auto w-full max-w-7xl px-5 sm:px-8">
            <a href="{{ route($locale.'.services.index') }}" class="text-ink-soft hover:text-plum-700 inline-flex items-center gap-2 text-sm transition-colors">
                ← {{ __('site.common.back_to_services') }}
            </a>

            <div class="mt-8 grid gap-10 lg:grid-cols-[minmax(0,1fr)_minmax(0,1.05fr)] lg:gap-16">
                <div>
                    @if ($service->image)
                        <img src="{{ asset($service->image) }}" alt="{{ $service->title }}" class="aspect-[4/5] w-full rounded-[2.5rem] object-cover shadow-lift lg:sticky lg:top-28">
                    @endif
                </div>

                <div>
                    <span class="border-gold-300/60 text-gold-600 inline-flex rounded-full border bg-white/70 px-3.5 py-1.5 text-[0.65rem] font-semibold uppercase tracking-[0.18em]">
                        {{ $service->category?->title }}
                    </span>

                    <h1 class="mt-5 text-4xl font-light leading-[1.08] sm:text-5xl">{{ $service->title }}</h1>

                    <p class="text-plum-700 mt-5 text-lg font-light leading-relaxed">{{ $service->summary }}</p>

                    <div class="border-sand-200 mt-8 flex flex-wrap items-end gap-x-10 gap-y-5 border-y py-6">
                        <div>
                            <p class="eyebrow">{{ __('site.common.price') }}</p>
                            <p class="font-display text-plum-900 mt-1.5 text-2xl leading-snug whitespace-pre-line">{{ $service->display_price }}</p>
                        </div>

                        @if ($service->duration)
                            <div>
                                <p class="eyebrow">{{ __('site.common.duration') }}</p>
                                <p class="text-ink mt-1.5 inline-flex items-center gap-2 text-sm">{{ $service->duration }}</p>
                            </div>
                        @endif

                        @if ($service->skin_type)
                            <div>
                                <p class="eyebrow">{{ __('site.common.best_for') }}</p>
                                <p class="text-ink mt-1.5 text-sm">{{ $service->skin_type }}</p>
                            </div>
                        @endif
                    </div>

                    @if ($service->details)
                        <div class="service-rich-content mt-7">
                            {{ \App\Support\SiteRichContentRenderer::render($service->details) }}
                        </div>
                    @endif

                    <h2 class="mt-9 inline-flex items-center gap-3 text-2xl font-medium">{{ __('site.common.benefits') }}</h2>

                    <ul class="mt-5 flex flex-col gap-3">
                        @foreach ((array) $service->benefits as $benefit)
                            <li class="flex gap-3">
                                <span class="text-gold-500 mt-0.5 shrink-0">✓</span>
                                <span class="text-ink-soft leading-relaxed">{{ $benefit }}</span>
                            </li>
                        @endforeach
                    </ul>

                    @if ($service->note)
                        <p class="border-gold-300/50 bg-blush-50 text-ink-soft mt-8 flex gap-3 rounded-3xl border px-5 py-4 text-sm leading-relaxed">
                            <span class="text-gold-500 mt-0.5 shrink-0">i</span>
                            <span>
                                <strong class="text-plum-900 mr-1 font-semibold">{{ __('site.common.good_to_know') }}:</strong>
                                {{ $service->note }}
                            </span>
                        </p>
                    @endif

                    <div class="mt-9 flex flex-wrap items-center gap-4">
                        <a href="{{ route($locale.'.contact', ['service' => $service->slug]) }}" class="btn-primary px-8 py-4 text-base">
                            {{ __('site.detail.book_this') }}
                        </a>
                        <p class="text-ink-faint max-w-52 text-xs leading-snug">{{ __('site.detail.price_note') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($relatedServices->isNotEmpty())
        <section class="bg-sand-50 py-20">
            <div class="mx-auto w-full max-w-7xl px-5 sm:px-8">
                <h2 class="text-3xl font-light sm:text-4xl">{{ __('site.detail.related') }}</h2>
                <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($relatedServices as $relatedService)
                        <x-service-card :service="$relatedService" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
