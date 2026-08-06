@extends('layouts.app')

@section('content')
    @php($locale = app()->getLocale())

    <section class="from-blush-50 to-cream bg-gradient-to-b py-16 pt-24 sm:py-24">
        <div class="mx-auto w-full max-w-4xl px-5 sm:px-8">
            <a href="{{ route($locale.'.blog.index') }}" class="text-ink-soft hover:text-plum-700 inline-flex text-sm transition-colors">← {{ __('site.common.back_to_blog') }}</a>
            <span class="eyebrow mt-8 inline-flex items-center gap-3">
                <span aria-hidden="true" class="h-px w-8 bg-gold-400"></span>
                {{ $post->category?->title }}
            </span>
            <h1 class="mt-5 text-5xl font-light leading-[1.05] sm:text-6xl">{{ $post->title }}</h1>
            <p class="text-plum-700 mt-6 text-xl font-light leading-relaxed">{{ $post->excerpt }}</p>
            @if ($post->published_at)
                <p class="text-ink-faint mt-4 text-sm">{{ $post->published_at->format('F j, Y') }}</p>
            @endif
        </div>
    </section>

    @if ($post->image)
        <div class="bg-cream">
            <div class="mx-auto w-full max-w-5xl px-5 sm:px-8">
                <img src="{{ asset('storage/'.ltrim($post->image, '/')) }}" alt="" class="aspect-[16/9] w-full rounded-[2.5rem] object-cover shadow-lift">
            </div>
        </div>
    @endif

    <section class="py-16 sm:py-20">
        <div class="mx-auto grid w-full max-w-7xl gap-10 px-5 sm:px-8 lg:grid-cols-[minmax(0,1fr)_22rem]">
            <article class="border-sand-100 shadow-soft rounded-4xl border bg-white p-6 sm:p-8">
                <div class="blog-rich-content">
                    {{ \App\Support\BlogRichContentRenderer::render($post->body) }}
                </div>
            </article>

            @if ($recentPosts->isNotEmpty())
                <aside class="border-sand-100 shadow-soft rounded-4xl border bg-white p-6">
                    <h2 class="text-xl font-medium">{{ __('site.common.recent_posts') }}</h2>
                    <div class="mt-5 grid gap-4">
                        @foreach ($recentPosts as $recentPost)
                            <a href="{{ route($locale.'.blog.show', ['postSlug' => $recentPost->slug]) }}" class="block rounded-2xl bg-sand-50 p-4 transition-colors hover:bg-plum-50">
                                <p class="text-sm font-semibold text-plum-900">{{ $recentPost->title }}</p>
                                <p class="text-ink-faint mt-1 text-xs">{{ $recentPost->category?->title }}</p>
                            </a>
                        @endforeach
                    </div>
                </aside>
            @endif
        </div>
    </section>
@endsection
