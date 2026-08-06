<?php

test('custom not found page renders in English by default', function (): void {
    $this->get('/en/missing-page')
        ->assertNotFound()
        ->assertSee('lang="en"', false)
        ->assertSee('<meta name="robots" content="none">', false)
        ->assertSeeText('404')
        ->assertSeeText('Page not found')
        ->assertSeeText('Browse services');
});

test('custom not found page renders in Russian for Russian URLs', function (): void {
    $this->get('/ru/net-takoi-stranitsy')
        ->assertNotFound()
        ->assertSee('lang="ru"', false)
        ->assertSeeText('404')
        ->assertSeeText('Страница не найдена')
        ->assertSeeText('Смотреть услуги');
});
