<header class="main-header main-header-three">
    <nav class="main-menu">
        <div class="main-menu__wrapper">
            <div class="container">
                <div class="main-menu__wrapper-inner">
                    <div class="main-menu__left">
                        <div class="main-menu__logo">
                            <a href="{{ route('index') }}"><img src="{{ $webResource->getFirstMediaUrl('logo-website') }}"
                                    alt="logo" width="100px"></a>
                        </div>
                    </div>
                    <div class="main-menu__main-menu-box">
                        <a href="#" class="mobile-nav__toggler"><i class="fa fa-bars"></i></a>
                        <ul class="main-menu__list">
                            @foreach ($menus as $menu)
                                @php
                                    $isActive = request()->is(trim($menu['url'], '/') . '*');
                                    $hasSubmenu = isset($menu['sub']);
                                @endphp

                                <li class="{{ $hasSubmenu ? 'dropdown' : '' }} {{ $isActive ? 'current' : '' }}">
                                    <a href="{{ !isset($menu['sub']) ? $menu['url'] : '#' }}">{{ $menu['text'] }}</a>

                                    @if ($hasSubmenu)
                                        <ul class="shadow-box">
                                            @foreach ($menu['sub'] as $submenu)
                                                @php
                                                    $isSubActive = request()->is(trim($submenu['url'], '/') . '*');
                                                @endphp
                                                <li class="{{ $isSubActive ? 'current' : '' }}">
                                                    <a href="{{ $submenu['url'] }}">{{ $submenu['text'] }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="main-menu__right">
                        <div class="main-menu__btn-boxes">
                            <div class="main-menu__btn-box-2">
                                @auth
                                    <a href="
                                    {{ Auth::user()->hasRole(['super_admin', 'mentor']) ? route('filament.mentor.pages.dashboard') : route('filament.mentee.pages.dashboard') }}
                                    "
                                        class="thm-btn">Hi, {{ Auth::user()->name }}</a>
                                @endauth

                                @guest
                                    <a href="{{ route('filament.mentee.auth.login') }}" class="thm-btn">Login</a>
                                @endguest
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>

<div class="stricky-header stricked-menu main-menu">
    <div class="sticky-header__content"></div><!-- /.sticky-header__content -->
</div><!-- /.stricky-header -->

<div class="mobile-nav__wrapper">
    <div class="mobile-nav__overlay mobile-nav__toggler"></div>
    <!-- /.mobile-nav__overlay -->
    <div class="mobile-nav__content">
        <span class="mobile-nav__close mobile-nav__toggler"><i class="fa fa-times"></i></span>

        <div class="logo-box">
            <a href="{{ route('index') }}" aria-label="logo image"><img
                    src="{{ $webResource->getFirstMediaUrl('logo-website') }}" alt="Logo" width="100px" /></a>
        </div>
        <!-- /.logo-box -->
        <div class="mobile-nav__container"></div>
        <!-- /.mobile-nav__container -->



    </div>
    <!-- /.mobile-nav__content -->
</div>
