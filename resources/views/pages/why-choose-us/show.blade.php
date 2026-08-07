@extends('layouts.app')

@section('content')
    @php($locale = app()->getLocale())

    <section class="from-blush-50 to-cream bg-gradient-to-b py-16 pt-24 sm:py-24">
        <div class="mx-auto grid w-full max-w-7xl items-center gap-12 px-5 sm:px-8 lg:grid-cols-[1.05fr_1fr] lg:gap-20">
            <div>
                <a href="{{ route($locale.'.home') }}" class="text-ink-soft hover:text-plum-700 inline-flex text-sm transition-colors">← {{ __('site.nav.home') }}</a>
                <span class="eyebrow mt-8 inline-flex items-center gap-3">
                    <span aria-hidden="true" class="h-px w-8 bg-gold-400"></span>
                    {{ __('site.common.why_choose_us') }}
                </span>
                <h1 class="mt-5 text-5xl font-light leading-[1.05] sm:text-6xl">{{ $item->title }}</h1>
                <p class="text-plum-700 mt-6 text-xl font-light leading-relaxed">{{ $item->summary }}</p>
            </div>
            <div class="relative">
                @if ($item->image)
                    <img src="{{ asset('storage/'.ltrim($item->image, '/')) }}" alt="" class="aspect-[4/3] w-full rounded-[2.5rem] object-cover shadow-lift">
                @endif
                <div aria-hidden="true" class="border-gold-300/50 absolute -right-5 -top-5 -z-10 hidden size-36 rounded-full border lg:block"></div>
            </div>
        </div>
    </section>

    <section class="py-16 sm:py-20">
        <div class="mx-auto grid w-full max-w-7xl gap-10 px-5 sm:px-8 lg:grid-cols-[minmax(0,1fr)_22rem]">
            <article class="border-sand-100 shadow-soft rounded-4xl border bg-white p-6 sm:p-8">
                <div class="blog-rich-content">
                    {{ \App\Support\SiteRichContentRenderer::render($item->body) }}
                </div>
            </article>

            <aside class="border-sand-100 shadow-soft rounded-4xl border bg-white p-6">
                <h2 class="text-xl font-medium">{{ __('site.common.other_reasons') }}</h2>
                <div class="mt-5 grid gap-2">
                    @foreach ($items as $reason)
                        <a href="{{ route($locale.'.why.show', ['itemSlug' => $reason->slug]) }}" class="rounded-2xl px-4 py-3 text-sm font-medium transition-colors {{ $reason->is($item) ? 'bg-plum-600 text-white' : 'bg-sand-50 text-ink-soft hover:bg-plum-50 hover:text-plum-700' }}">
                            {{ $reason->title }}
                        </a>
                    @endforeach
                </div>
            </aside>
        </div>
    </section>
@endsection
