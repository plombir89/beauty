<?php

use App\Models\User;
use App\Models\WhyChooseUsItem;

test('why choose item edit page opens when body still uses legacy paragraphs', function (): void {
    $user = User::factory()->create();
    $item = WhyChooseUsItem::query()->create([
        'key' => 'legacy-body',
        'slug' => ['en' => 'legacy-body', 'ru' => 'legacy-body-ru'],
        'title' => ['en' => 'Legacy body', 'ru' => 'Legacy body RU'],
        'summary' => ['en' => 'Legacy summary', 'ru' => 'Legacy summary RU'],
        'body' => [
            'en' => ['First paragraph', 'Second paragraph'],
            'ru' => ['Первый абзац', 'Второй абзац'],
        ],
        'sort_order' => 1,
        'is_active' => true,
    ]);

    $this->actingAs($user)
        ->get(route('filament.admin.resources.why-choose-us-items.edit', ['record' => $item]))
        ->assertOk();
});
