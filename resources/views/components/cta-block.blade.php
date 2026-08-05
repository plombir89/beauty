@props(['block'])

@php($locale = app()->getLocale())

@if ($block)
    <section {{ $attributes->merge(['class' => 'pb-20 sm:pb-28']) }}>
        <div class="mx-auto w-full max-w-7xl px-5 sm:px-8">
            <div class="from-plum-700 to-plum-900 relative overflow-hidden rounded-[2.5rem] bg-gradient-to-br px-7 py-14 text-center sm:px-14 sm:py-20">
                <div aria-hidden="true" class="grain absolute inset-0 opacity-50"></div>
                <div aria-hidden="true" class="bg-gold-400/20 absolute -right-16 -top-24 size-72 rounded-full blur-3xl"></div>

                <div class="relative mx-auto max-w-2xl">
                    <h2 class="text-4xl font-light leading-tight text-white sm:text-5xl">{{ $block->title }}</h2>
                    <p class="mt-5 leading-relaxed text-white/70">{{ $block->text }}</p>

                    <div class="mt-9 flex flex-wrap justify-center gap-3">
                        <a href="{{ route($locale.'.contact') }}" class="btn-gold px-8 py-4 text-base">
                            {{ $block->primary_label ?: __('site.common.book_now') }}
                        </a>
                        <a href="{{ route($locale.'.services.index') }}" class="btn-secondary border-white/25 bg-white/10 px-8 py-4 text-base text-white hover:border-white/50 hover:bg-white/20">
                            {{ $block->secondary_label ?: __('site.common.all_services') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
