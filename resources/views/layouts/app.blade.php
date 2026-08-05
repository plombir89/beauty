<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#fdfaf8">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300..700;1,300..700&family=Manrope:wght@300..700&display=swap" rel="stylesheet">
        @head
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body id="top" class="min-h-screen bg-cream text-ink antialiased">
        <x-site.header :studio="$layoutStudio ?? null" :alternate-urls="$alternateUrls ?? []" />

        <main id="main">
            {{ $slot ?? '' }}
            @yield('content')
        </main>

        <x-site.footer :studio="$layoutStudio ?? null" :social-links="$layoutSocialLinks ?? collect()" :business-hours="$layoutBusinessHours ?? collect()" />

        @livewireScripts
    </body>
</html>
