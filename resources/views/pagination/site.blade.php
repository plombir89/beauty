@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('site.pagination.navigation') }}" class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-ink-soft text-sm">
            {{ __('site.pagination.showing', ['first' => $paginator->firstItem(), 'last' => $paginator->lastItem(), 'total' => $paginator->total()]) }}
        </p>

        <ul class="flex items-center gap-2">
            @if ($paginator->onFirstPage())
                <li>
                    <span class="border-sand-200 text-ink-faint grid size-10 cursor-not-allowed place-items-center rounded-full border bg-white/70">
                        <span class="sr-only">{{ __('site.pagination.previous') }}</span>
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false">
                            <path d="m14.5 6-6 6 6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="border-sand-200 text-plum-700 hover:border-plum-400 hover:bg-plum-600 grid size-10 place-items-center rounded-full border bg-white transition-all duration-300 hover:-translate-y-0.5 hover:text-white">
                        <span class="sr-only">{{ __('site.pagination.previous') }}</span>
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false">
                            <path d="m14.5 6-6 6 6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>
                </li>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li>
                        <span class="text-ink-faint grid size-10 place-items-center text-sm">{{ $element }}</span>
                    </li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <li>
                            @if ($page === $paginator->currentPage())
                                <span aria-current="page" class="bg-plum-600 grid size-10 place-items-center rounded-full text-sm font-semibold text-white shadow-sm">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" aria-label="{{ __('site.pagination.page', ['page' => $page]) }}" class="border-sand-200 text-ink-soft hover:border-plum-400 hover:bg-plum-600 grid size-10 place-items-center rounded-full border bg-white text-sm font-medium transition-all duration-300 hover:-translate-y-0.5 hover:text-white">{{ $page }}</a>
                            @endif
                        </li>
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="border-sand-200 text-plum-700 hover:border-plum-400 hover:bg-plum-600 grid size-10 place-items-center rounded-full border bg-white transition-all duration-300 hover:-translate-y-0.5 hover:text-white">
                        <span class="sr-only">{{ __('site.pagination.next') }}</span>
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false">
                            <path d="m9.5 6 6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>
                </li>
            @else
                <li>
                    <span class="border-sand-200 text-ink-faint grid size-10 cursor-not-allowed place-items-center rounded-full border bg-white/70">
                        <span class="sr-only">{{ __('site.pagination.next') }}</span>
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false">
                            <path d="m9.5 6 6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
