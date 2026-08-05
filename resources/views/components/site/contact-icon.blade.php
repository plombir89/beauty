@props(['name'])

@php($icon = strtolower((string) $name))

@switch($icon)
    @case('mail')
        <svg {{ $attributes->class('size-4') }} viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false">
            <path d="M4.75 6.75h14.5v10.5H4.75z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
            <path d="m5.5 7.5 6.5 5 6.5-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        @break

    @case('map')
        <svg {{ $attributes->class('size-4') }} viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false">
            <path d="M12 21s6-5.2 6-11a6 6 0 1 0-12 0c0 5.8 6 11 6 11Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
            <circle cx="12" cy="10" r="2.25" stroke="currentColor" stroke-width="1.8" />
        </svg>
        @break

    @case('phone')
    @default
        <svg {{ $attributes->class('size-4') }} viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false">
            <path d="M7.4 4.75h2.45l1.25 4.05-1.75 1.1a9.6 9.6 0 0 0 4.75 4.75l1.1-1.75 4.05 1.25v2.45c0 1.1-.9 2-2 2C10.7 18.6 5.4 13.3 5.4 6.75c0-1.1.9-2 2-2Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
@endswitch
