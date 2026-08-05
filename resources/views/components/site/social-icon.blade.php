@props(['name'])

@php($icon = strtolower((string) $name))

@switch($icon)
    @case('facebook')
        <svg {{ $attributes->class('size-4 fill-current') }} viewBox="0 0 24 24" aria-hidden="true" focusable="false">
            <path d="M14.5 8.2h2.4V4.3h-2.8c-3 0-4.9 1.9-4.9 5v2.1H6.5v3.8h2.7V22h4.1v-6.8h2.8l.5-3.8h-3.3V9.7c0-1 .4-1.5 1.2-1.5Z" />
        </svg>
        @break

    @case('youtube')
        <svg {{ $attributes->class('size-4 fill-current') }} viewBox="0 0 24 24" aria-hidden="true" focusable="false">
            <path fill-rule="evenodd" d="M21.6 7.1a3.1 3.1 0 0 0-2.2-2.2C17.5 4.4 12 4.4 12 4.4s-5.5 0-7.4.5a3.1 3.1 0 0 0-2.2 2.2A32 32 0 0 0 2 12a32 32 0 0 0 .4 4.9 3.1 3.1 0 0 0 2.2 2.2c1.9.5 7.4.5 7.4.5s5.5 0 7.4-.5a3.1 3.1 0 0 0 2.2-2.2A32 32 0 0 0 22 12a32 32 0 0 0-.4-4.9ZM10.2 15.5v-7L16 12l-5.8 3.5Z" clip-rule="evenodd" />
        </svg>
        @break

    @case('tiktok')
        <svg {{ $attributes->class('size-4 fill-current') }} viewBox="0 0 24 24" aria-hidden="true" focusable="false">
            <path d="M15.4 3c.4 2.7 1.9 4.3 4.6 4.5v3.6c-1.6.1-3-.4-4.5-1.3v5.8c0 4.8-5.2 7.9-9.4 5.5-2.7-1.5-3.6-5.4-1.8-8 1.5-2.3 4.2-3.3 6.9-2.6v3.8l-.9-.2c-1.2-.2-2.3.5-2.7 1.5-.4 1.1.1 2.4 1.2 3 1.2.7 2.8.2 3.4-1.1.2-.4.2-.9.2-1.4V3h3Z" />
        </svg>
        @break

    @case('instagram')
    @default
        <svg {{ $attributes->class('size-4') }} viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false">
            <rect width="16" height="16" x="4" y="4" rx="5" stroke="currentColor" stroke-width="1.8" />
            <circle cx="12" cy="12" r="3.4" stroke="currentColor" stroke-width="1.8" />
            <circle cx="17" cy="7" r="1.2" fill="currentColor" />
        </svg>
@endswitch
