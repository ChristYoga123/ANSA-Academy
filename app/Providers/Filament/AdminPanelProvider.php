<?php

namespace App\Providers\Filament;

use App\Filament\Admin\Pages\PromoPage;
use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Pages\Dashboard;
use Filament\Navigation\MenuItem;
use Filament\Support\Colors\Color;
use Filament\Navigation\NavigationItem;
use Filament\Navigation\NavigationGroup;
use Filament\Http\Middleware\Authenticate;
use Filament\Navigation\NavigationBuilder;
use App\Filament\Admin\Pages\WebResourcePage;
use App\Filament\Admin\Resources\EventResource;
use App\Filament\Admin\Resources\LombaResource;
use Illuminate\Session\Middleware\StartSession;
use App\Filament\Admin\Resources\MenteeResource;
use App\Filament\Admin\Resources\MentorResource;
use Illuminate\Cookie\Middleware\EncryptCookies;
use App\Filament\Admin\Resources\ArtikelResource;
use App\Filament\Admin\Resources\KelasAnsaResource;
use App\Filament\Admin\Resources\MentoringResource;
use App\Filament\Admin\Resources\TransaksiResource;
use App\Filament\Admin\Resources\LokerMentorResource;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use FilipFonal\FilamentLogManager\FilamentLogManager;
use GeoSot\FilamentEnvEditor\FilamentEnvEditorPlugin;
use Illuminate\Routing\Middleware\SubstituteBindings;
use App\Filament\Admin\Resources\ProofreadingResource;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Filament\Admin\Resources\ProdukDigitalResource;
use Filament\Http\Middleware\DisableBladeIconComponents;
use App\Filament\Admin\Resources\ProgramKategoriResource;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use App\Filament\Admin\Resources\LokerMentorBidangResource;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Joaopaulolndev\FilamentEditProfile\Pages\EditProfilePage;
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
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
                    )
                    ->canAccess(fn() => auth()->user()->can('page_EditProfilePage')),
                FilamentEnvEditorPlugin::make()
                    ->hideKeys('APP_KEY', 'BCRYPT_ROUNDS')
                    ->authorize(
                        // fn () => auth()->user()->isAdmin()
                    ),
                FilamentLogManager::make(),
                FilamentShieldPlugin::make(),
            ])
            // topbar
            ->userMenuItems([
                'profile' => MenuItem::make()
                    ->label(fn() => "Edit Profile")
                    ->url(fn (): string => EditProfilePage::getUrl())
                    ->icon('heroicon-m-cog-6-tooth')
                    ->visible(function (): bool {
                        return auth()->user()->can('page_EditProfilePage');
                    }),
            ])
            // sidebar
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                return $builder->groups([
                    NavigationGroup::make('')
                        ->items([
                            ...Dashboard::getNavigationItems(),
                            ...ArtikelResource::getNavigationItems(),
                            ...LombaResource::getNavigationItems(),
                            ...ProdukDigitalResource::getNavigationItems(),
                            ...EventResource::getNavigationItems(),
                            ...PromoPage::getNavigationItems(),
                            ...TransaksiResource::getNavigationItems(),
                            // ...PageResource::getNavigationItems(),
                            // ...CategoryResource::getNavigationItems(),
                            // ...HomePageSettings::getNavigationItems(),
                        ]),
                    NavigationGroup::make('Karir')
                        ->items([
                            ...LokerMentorBidangResource::getNavigationItems(),
                            ...LokerMentorResource::getNavigationItems(),
                            // ...PageResource::getNavigationItems(),
                            // ...CategoryResource::getNavigationItems(),
                            // ...HomePageSettings::getNavigationItems(),
                        ]),
                    NavigationGroup::make('Program')
                        ->items([
                            ...MentoringResource::getNavigationItems(),
                            ...KelasAnsaResource::getNavigationItems(),
                            ...ProofreadingResource::getNavigationItems(),
                            // ...PageResource::getNavigationItems(),
                            // ...CategoryResource::getNavigationItems(),
                            // ...HomePageSettings::getNavigationItems(),
                        ]),
                    NavigationGroup::make('Master Data')
                        ->items([
                            ...MentorResource::getNavigationItems(),
                            ...MenteeResource::getNavigationItems(),
                            ...ProgramKategoriResource::getNavigationItems(),
                            ...WebResourcePage::getNavigationItems(),
                        ]),
                    NavigationGroup::make('Settings')
                        ->items([
                            NavigationItem::make('Roles & Permissions')
                                ->icon('heroicon-s-shield-check')
                                ->visible(fn() => auth()->user()->can('view_role') && auth()->user()->can('view_any_role'))
                                ->url(fn() => route('filament.admin.resources.shield.roles.index'))
                                ->isActiveWhen(fn() => request()->routeIs('filament.admin.resources.shield.roles.*')),
                            NavigationItem::make('Environment Editor')
                                ->icon('heroicon-s-cog')
                                ->url(fn() => route('filament.admin.pages.env-editor'))
                                ->visible(fn() => auth()->user()->can('page_ViewEnv'))
                                ->isActiveWhen(fn() => request()->routeIs('filament.admin.pages.env-editor')),
                            NavigationItem::make('Logs')
                                ->icon('heroicon-s-newspaper')
                                ->url(fn() => route('filament.admin.pages.logs'))
                                ->visible(fn() => auth()->user()->can('page_Logs'))
                                ->isActiveWhen(fn() => request()->routeIs('filament.admin.pages.logs')),
                        ]),

                ]);
            })
            ;
    }
}
