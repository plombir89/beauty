@props(['block', 'studio' => null])

@php
    $locale = app()->getLocale();
    $studio ??= $layoutStudio ?? null;
    $phone = $studio?->phone;
    $phoneHref = $studio?->phone_href;

    if (! $phoneHref && $phone) {
        $phoneHref = 'tel:'.preg_replace('/[^0-9+]/', '', $phone);
    }

    $secondaryLabel = $block?->secondary_label;

    if ($phone && $secondaryLabel) {
        $secondaryLabel = str_replace(':phone', $phone, $secondaryLabel);
    }
@endphp

@if ($block && $block->text)
    <section {{ $attributes->merge(['class' => 'pb-20 sm:pb-28']) }}>
        <div class="mx-auto w-full max-w-7xl px-5 sm:px-8">
            <div class="from-plum-700 to-plum-900 relative overflow-hidden rounded-[2.5rem] bg-gradient-to-br px-7 py-14 text-center sm:px-14 sm:py-20">
                <div aria-hidden="true" class="grain absolute inset-0 opacity-50"></div>
                <div aria-hidden="true" class="bg-gold-400/20 absolute -right-16 -top-24 size-72 rounded-full blur-3xl"></div>

                <div class="relative mx-auto max-w-3xl">
                    <p class="text-3xl font-light leading-tight text-white sm:text-4xl lg:text-5xl">{{ $block->text }}</p>

                    <div class="mt-9 flex flex-wrap justify-center gap-3">
                        <a href="{{ route($locale.'.contact') }}" class="btn-gold inline-flex items-center gap-2 px-8 py-4 text-base">
                            <span>{{ $block->primary_label ?: __('site.common.book_now') }}</span>
                            <span aria-hidden="true">→</span>
                        </a>
                        @if ($phoneHref)
                            <a href="{{ $phoneHref }}" class="btn-secondary inline-flex items-center gap-2 border-white/25 bg-white/10 px-8 py-4 text-base text-white hover:border-white/50 hover:bg-white/20">
                                <x-site.contact-icon name="phone" class="size-4" />
                                <span>{{ $secondaryLabel ?: $phone }}</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
