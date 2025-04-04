<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Pages\Dashboard;
use Filament\Navigation\MenuItem;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\Auth;
use Filament\Navigation\NavigationGroup;
use App\Filament\Mentee\Pages\ProfitPage;
use Filament\Http\Middleware\Authenticate;
use Filament\Navigation\NavigationBuilder;
use App\Filament\Mentee\Widgets\CalendarWidget;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use App\Filament\Mentee\Pages\Auth\CustomRegister;
use App\Filament\Mentee\Resources\TransaksiResource;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use App\Filament\Mentee\Resources\KelasAnsaMenteeResource;
use App\Filament\Mentee\Resources\MentoringMenteeResource;
use App\Filament\Mentee\Resources\TestimoniMentorResource;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Saade\FilamentFullCalendar\FilamentFullCalendarPlugin;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use App\Filament\Mentee\Resources\ProofreadingMenteeResource;
use Joaopaulolndev\FilamentEditProfile\Pages\EditProfilePage;
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;

class MenteePanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('mentee')
            ->path('mentee')
            ->login()
            ->registration(CustomRegister::class)
            ->passwordReset()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Mentee/Resources'), for: 'App\\Filament\\Mentee\\Resources')
            ->discoverPages(in: app_path('Filament/Mentee/Pages'), for: 'App\\Filament\\Mentee\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Mentee/Widgets'), for: 'App\\Filament\\Mentee\\Widgets')
            ->widgets([
                CalendarWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                FilamentEditProfilePlugin::make()
                    ->shouldRegisterNavigation(false)
                    ->shouldShowAvatarForm(
                        value: true,
                        directory: 'avatars', // image will be stored in 'storage/app/public/avatars
                        rules: 'mimes:jpeg,png,jpg|max:1024' //only accept jpeg and png files with a maximum size of 1MB
                    ),
                FilamentFullCalendarPlugin::make()
                    ->locale('id')
            ])
            ->userMenuItems([
                MenuItem::make()
                    ->label(fn() => "Edit Profile")
                    ->url(fn (): string => EditProfilePage::getUrl())
                    ->icon('heroicon-m-cog-6-tooth'),
                MenuItem::make()
                    ->label(fn() => "Code : " . Auth::user()->referral_code)
                    ->icon('heroicon-m-link'),
            ])
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                return $builder->groups([
                    NavigationGroup::make('')
                        ->items([
                            ...Dashboard::getNavigationItems(),
                            ...TransaksiResource::getNavigationItems(),
                            ...ProfitPage::getNavigationItems(),
                            // ...PageResource::getNavigationItems(),
                            // ...CategoryResource::getNavigationItems(),
                            // ...HomePageSettings::getNavigationItems(),
                        ]),
                    NavigationGroup::make('Program Terdaftar')
                        ->items([
                            ...MentoringMenteeResource::getNavigationItems(),
                            ...KelasAnsaMenteeResource::getNavigationItems(),
                            ...ProofreadingMenteeResource::getNavigationItems(),
                            ...TestimoniMentorResource::getNavigationItems(),
                        ]),
                ]);
            });
    }
}
