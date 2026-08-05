@extends('layouts.app')

@section('content')
    <section class="from-blush-50 to-cream bg-gradient-to-b pb-10 pt-14 sm:pt-20">
        <div class="mx-auto w-full max-w-7xl px-5 sm:px-8">
            <div class="max-w-3xl">
                <span class="eyebrow inline-flex items-center gap-3">
                    <span aria-hidden="true" class="h-px w-8 bg-gold-400"></span>
                    {{ $page->eyebrow }}
                </span>
                <h1 class="mt-5 text-5xl font-light leading-[1.05] sm:text-6xl">{{ $page->title }}</h1>
                <p class="text-ink-soft mt-5 text-lg leading-relaxed">{{ $page->subtitle }}</p>
            </div>
        </div>
    </section>

    <section class="pb-20">
        <div class="mx-auto w-full max-w-7xl px-5 sm:px-8">
            <div class="grid gap-8 lg:grid-cols-[1.15fr_1fr] lg:gap-12">
                <div class="flex flex-col gap-8">
                    <livewire:booking-form :initial-service-id="$initialServiceId" />

                    @if ($depositSetting)
                        @php
                            $amount = '$'.number_format((float) $depositSetting->amount, 0);
                            $methods = implode(', ', $depositSetting->offsite_payment_methods ?? []);
                        @endphp
                        <div class="border-sand-100 shadow-soft rounded-4xl border bg-white p-6 sm:p-8">
                            <span class="eyebrow inline-flex items-center gap-2">{{ $depositSetting->eyebrow }}</span>

                            <h2 class="mt-4 text-2xl font-medium">{{ str_replace(':amount', $amount, $depositSetting->title) }}</h2>

                            <p class="text-ink-soft mt-3 text-sm leading-relaxed">{{ str_replace(':methods', $methods, $depositSetting->text) }}</p>

                            <div class="border-sand-200 bg-sand-50 text-ink-soft mt-6 rounded-2xl border px-4 py-3 text-sm">
                                {{ __('site.common.payment_methods') }}: {{ implode(', ', $depositSetting->payment_methods ?? []) }}
                            </div>

                            <p class="text-ink-faint mt-5 text-xs leading-relaxed">{{ $depositSetting->note }}</p>
                        </div>
                    @endif
                </div>

                <div class="flex flex-col gap-6">
                    @if ($studio)
                        <div class="border-sand-100 shadow-soft rounded-4xl border bg-white p-6 sm:p-8">
                            <h2 class="text-2xl font-medium">{{ __('site.contact.info_title') }}</h2>

                            <ul class="mt-6 flex flex-col gap-5 text-sm">
                                <li>
                                    <a href="{{ $studio->phone_href }}" class="group flex items-center gap-4 transition-colors">
                                        <span class="bg-blush-50 text-plum-600 group-hover:bg-plum-600 grid size-11 shrink-0 place-items-center rounded-2xl transition-colors group-hover:text-white">{{ __('site.common.phone_short') }}</span>
                                        <span>
                                            <span class="text-ink-faint block text-xs">{{ __('site.contact.call_us') }}</span>
                                            <span class="text-ink group-hover:text-plum-700 font-medium">{{ $studio->phone }}</span>
                                        </span>
                                    </a>
                                </li>

                                <li>
                                    <a href="mailto:{{ $studio->email }}" class="group flex items-center gap-4 transition-colors">
                                        <span class="bg-blush-50 text-plum-600 group-hover:bg-plum-600 grid size-11 shrink-0 place-items-center rounded-2xl transition-colors group-hover:text-white">@</span>
                                        <span class="min-w-0">
                                            <span class="text-ink-faint block text-xs">{{ __('site.contact.write_us') }}</span>
                                            <span class="text-ink group-hover:text-plum-700 font-medium break-all">{{ $studio->email }}</span>
                                        </span>
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ $studio->maps_url }}" target="_blank" rel="noopener" class="group flex items-start gap-4 transition-colors">
                                        <span class="bg-blush-50 text-plum-600 group-hover:bg-plum-600 grid size-11 shrink-0 place-items-center rounded-2xl transition-colors group-hover:text-white">⌖</span>
                                        <span>
                                            <span class="text-ink-faint block text-xs">{{ __('site.contact.get_directions') }}</span>
                                            <span class="text-ink group-hover:text-plum-700 font-medium">{{ $studio->address }}</span>
                                        </span>
                                    </a>
                                </li>
                            </ul>

                            @if ($businessHours->isNotEmpty())
                                <div class="border-sand-100 mt-7 border-t pt-6">
                                    <h3 class="eyebrow">{{ __('site.common.hours') }}</h3>
                                    <ul class="mt-4 flex flex-col gap-2.5 text-sm">
                                        @foreach ($businessHours as $hour)
                                            <li class="text-ink-soft flex items-baseline justify-between gap-4">
                                                <span>{{ $hour->day_label }}</span>
                                                <span class="text-ink font-medium">{{ $hour->time_label }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <p class="text-gold-600 mt-4 text-xs font-semibold uppercase tracking-[0.14em]">{{ __('site.footer.appointment_only') }}</p>
                                </div>
                            @endif

                            @if ($socialLinks->isNotEmpty())
                                <div class="border-sand-100 mt-7 border-t pt-6">
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($socialLinks as $socialLink)
                                            <a href="{{ $socialLink->href }}" aria-label="{{ $socialLink->label }}" class="bg-sand-100 text-plum-700 hover:bg-plum-600 grid size-10 place-items-center rounded-full transition-all duration-300 hover:-translate-y-0.5 hover:text-white" target="_blank" rel="noopener">
                                                <x-site.social-icon :name="$socialLink->key" />
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="border-sand-100 shadow-soft bg-sand-50 relative isolate overflow-hidden rounded-4xl border">
                            <div class="text-ink-soft absolute inset-0 -z-10 grid place-items-center gap-2 p-6 text-center text-sm">{{ $studio->address }}</div>
                            <iframe src="{{ $studio->map_embed_url }}" title="{{ $studio->name }}" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="aspect-[4/3] w-full border-0 sm:aspect-[16/10]"></iframe>
                        </div>

                        <a href="{{ $studio->maps_url }}" target="_blank" rel="noopener" class="text-plum-700 hover:text-plum-600 inline-flex items-center gap-2 self-start text-sm font-medium transition-colors">{{ __('site.contact.get_directions') }}</a>
                    @endif
                </div>
            </div>
        </div>
    </section>

    @if ($policy)
        <section class="bg-sand-50 py-20">
            <div class="mx-auto w-full max-w-7xl px-5 sm:px-8">
                <div>
                    <h2 class="text-3xl font-light sm:text-4xl">{{ $policy->title }}</h2>
                    <p class="text-ink-soft mt-4 max-w-3xl leading-relaxed">{{ $policy->intro }}</p>
                </div>

                <div class="mt-10 grid gap-6 md:grid-cols-3">
                    @foreach ($policy->items as $item)
                        <article class="border-sand-100 shadow-soft rounded-4xl border bg-white p-7">
                            <span class="bg-blush-50 text-plum-600 grid size-11 place-items-center rounded-2xl">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            <h3 class="mt-5 text-xl font-medium">{{ $item->title }}</h3>
                            <p class="text-ink-soft mt-3 text-sm leading-relaxed">{{ $item->text }}</p>
                        </article>
                    @endforeach
                </div>

                <div class="border-gold-300/50 mt-8 flex flex-col gap-3 rounded-4xl border border-dashed bg-white/60 px-7 py-6 sm:flex-row sm:items-center sm:justify-between">
                    @if ($policy->kids_note)
                        <p class="text-plum-900 inline-flex items-center gap-3 font-medium">{{ $policy->kids_note }}</p>
                    @endif
                    @if ($policy->thanks_note)
                        <p class="text-ink-soft text-sm italic">{{ $policy->thanks_note }}</p>
                    @endif
                </div>
            </div>
        </section>
    @endif
@endsection
