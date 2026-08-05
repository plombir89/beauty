@props(['service'])

@php
    $locale = app()->getLocale();
    $url = route($locale.'.services.show', ['serviceSlug' => $service->slug]);
@endphp

<article {{ $attributes->merge(['class' => 'h-full']) }}>
    <div class="border-sand-100 shadow-soft hover:shadow-lift group relative flex h-full flex-col overflow-hidden rounded-4xl border bg-white transition-all duration-500 hover:-translate-y-1">
        <div class="relative overflow-hidden">
            @if ($service->image)
                <img src="{{ asset($service->image) }}" alt="" class="aspect-[4/3] w-full object-cover transition-transform duration-700 group-hover:scale-[1.06]">
            @endif
            <span class="text-plum-700 absolute left-4 top-4 rounded-full bg-white/90 px-3 py-1 text-[0.65rem] font-semibold uppercase tracking-[0.14em] backdrop-blur">
                {{ $service->category?->title }}
            </span>
        </div>

        <div class="flex flex-1 flex-col p-6">
            <h3 class="text-xl font-medium leading-snug">
                <a href="{{ $url }}" class="after:absolute after:inset-0 hover:underline hover:decoration-gold-400 hover:underline-offset-4">
                    {{ $service->title }}
                </a>
            </h3>

            <p class="text-ink-soft mt-3 line-clamp-3 text-sm leading-relaxed">{{ $service->summary }}</p>

            @if ($service->duration)
                <p class="text-ink-faint mt-3 inline-flex items-center gap-2 text-xs">{{ $service->duration }}</p>
            @endif

            <div class="border-sand-100 mt-auto flex items-end justify-between gap-4 border-t pt-5">
                <p class="font-display text-plum-900 text-lg leading-tight whitespace-pre-line">{{ $service->display_price }}</p>
                <span class="text-gold-600 group-hover:text-plum-700 inline-flex shrink-0 items-center gap-1.5 text-xs font-semibold uppercase tracking-[0.12em] transition-colors">
                    {{ __('site.common.read_more') }}
                </span>
            </div>
        </div>
    </div>
</article>
