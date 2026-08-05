<?php

namespace App\Providers;

use App\Models\BusinessHour;
use App\Models\SocialLink;
use App\Models\StudioProfile;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Laravel\Head\Enums\OgType;
use Laravel\Head\Enums\TwitterCard;
use Laravel\Head\Facades\Head;
use Laravel\Head\HeadBuilder;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();

        View::composer('layouts.app', function ($view): void {
            $view->with([
                'layoutStudio' => StudioProfile::active()->first(),
                'layoutSocialLinks' => SocialLink::active()->ordered()->get(),
                'layoutBusinessHours' => BusinessHour::active()->ordered()->get(),
            ]);
        });
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        Head::defaults(fn (HeadBuilder $head) => $head
            ->title('Elegant Beauty Studio', suffix: ' - Elegant Beauty Studio')
            ->description('Skin, brows and body care in Lakewood, WA.')
            ->canonical(forceHttps: false)
            ->og(siteName: 'Elegant Beauty Studio', type: OgType::Website)
            ->twitter(card: TwitterCard::SummaryWithLargeImage)
            ->favicon('/img/logo.png', type: 'image/png')
            ->appleTouchIcon('/img/logo.png')
            ->searchableByRobots());

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
