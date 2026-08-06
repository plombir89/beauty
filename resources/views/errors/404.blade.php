@php
    use App\Support\LocalizedRoutes;
    use Laravel\Head\Facades\Head;

    $locale = LocalizedRoutes::locale(request()->segment(1));

    app()->setLocale($locale);

    $alternateUrls = [
        'en' => route('en.home'),
        'ru' => route('ru.home'),
    ];

    view()->share('alternateUrls', $alternateUrls);

    Head::title(__('site.not_found.title'))
        ->description(__('site.not_found.text'))
        ->canonical(url()->current())
        ->hiddenFromRobots();
@endphp

@extends('layouts.app')

@section('content')
    <section class="from-blush-50 via-cream to-sand-50 relative overflow-hidden bg-gradient-to-b py-24 sm:py-32">
        <div class="mx-auto flex w-full max-w-7xl flex-col items-center px-5 text-center sm:px-8">
            <span class="font-display text-gold-400 text-8xl leading-none sm:text-9xl">404</span>

            <h1 class="mt-6 max-w-2xl text-4xl font-light leading-[1.08] sm:text-5xl">
                {{ __('site.not_found.title') }}
            </h1>

            <p class="text-ink-soft mt-5 max-w-md leading-relaxed">
                {{ __('site.not_found.text') }}
            </p>

            <div class="mt-9 flex flex-wrap justify-center gap-3">
                <a href="{{ route($locale.'.home') }}" class="btn-primary px-8 py-4 text-base">
                    {{ __('site.not_found.home') }}
                </a>
                <a href="{{ route($locale.'.services.index') }}" class="btn-secondary px-8 py-4 text-base">
                    {{ __('site.not_found.services') }}
                </a>
            </div>
        </div>
    </section>
@endsection
