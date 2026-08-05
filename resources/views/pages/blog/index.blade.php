@extends('layouts.app')

@section('content')
    @php
        $locale = app()->getLocale();
        $activeCategory = $activeCategory ?? null;
    @endphp

    <section class="from-blush-50 to-cream bg-gradient-to-b pb-10 pt-14 sm:pt-20">
        <div class="mx-auto w-full max-w-7xl px-5 sm:px-8">
            <div class="max-w-3xl">
                <span class="eyebrow inline-flex items-center gap-3">
                    <span aria-hidden="true" class="h-px w-8 bg-gold-400"></span>
                    {{ __('site.nav.blog') }}
                </span>
                <h1 class="mt-5 text-5xl font-light leading-[1.05] sm:text-6xl">{{ $activeCategory?->title ?? $page->title }}</h1>
                <p class="text-ink-soft mt-5 text-lg leading-relaxed">{{ $activeCategory?->description ?? $page->subtitle }}</p>
            </div>
        </div>
    </section>

    <section class="border-sand-100 bg-cream/85 sticky top-[4.5rem] z-30 border-b py-4 backdrop-blur-xl">
        <div class="mx-auto flex w-full max-w-7xl gap-2 overflow-x-auto px-5 sm:px-8">
            <a href="{{ route($locale.'.blog.index') }}" class="shrink-0 rounded-full border px-4 py-2 text-sm whitespace-nowrap transition-all duration-300 {{ $activeCategory ? 'border-sand-200 bg-white text-ink-soft hover:border-plum-400 hover:text-plum-700' : 'border-plum-600 bg-plum-600 text-white shadow-sm' }}">
                {{ __('site.blog.all') }}
            </a>
            @foreach ($categories as $category)
                <a href="{{ route($locale.'.blog.category', ['categorySlug' => $category->slug]) }}" class="shrink-0 rounded-full border px-4 py-2 text-sm whitespace-nowrap transition-all duration-300 {{ $activeCategory?->is($category) ? 'border-plum-600 bg-plum-600 text-white shadow-sm' : 'border-sand-200 bg-white text-ink-soft hover:border-plum-400 hover:text-plum-700' }}">
                    {{ $category->title }}
                </a>
            @endforeach
        </div>
    </section>

    <section class="py-20 sm:py-28">
        <div class="mx-auto w-full max-w-7xl px-5 sm:px-8">
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($posts as $post)
                    <article class="border-sand-100 shadow-soft hover:shadow-lift group relative flex h-full flex-col overflow-hidden rounded-4xl border bg-white transition-all duration-500 hover:-translate-y-1">
                        <a href="{{ route($locale.'.blog.show', ['postSlug' => $post->slug]) }}" class="block">
                            @if ($post->image)
                                <img src="{{ asset($post->image) }}" alt="" class="aspect-[4/3] w-full object-cover transition-transform duration-700 group-hover:scale-[1.06]">
                            @endif
                            <div class="flex flex-1 flex-col p-6">
                                <p class="text-gold-600 text-xs font-semibold uppercase tracking-[0.16em]">{{ $post->category?->title }}</p>
                                <h2 class="mt-3 text-2xl font-medium leading-snug">{{ $post->title }}</h2>
                                <p class="text-ink-soft mt-3 text-sm leading-relaxed">{{ $post->excerpt }}</p>
                                <span class="text-gold-600 group-hover:text-plum-700 mt-6 inline-flex text-xs font-semibold uppercase tracking-[0.12em] transition-colors">{{ __('site.common.read_more') }}</span>
                            </div>
                        </a>
                    </article>
                @endforeach
            </div>

            <div class="mt-10">
                {{ $posts->links() }}
            </div>
        </div>
    </section>
@endsection
