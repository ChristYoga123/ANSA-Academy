<header class="main-header main-header-three">
    <nav class="main-menu">
        <div class="main-menu__wrapper">
            <div class="container">
                <div class="main-menu__wrapper-inner">
                    <div class="main-menu__left">
                        <div class="main-menu__logo">
                            <a href="index.html"><img src="assets/images/resources/logo-1.png" alt=""></a>
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
                                    <a href="{{ $menu['url'] }}">{{ $menu['text'] }}</a>

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
                                <a href="#" class="thm-btn">Masuk</a>
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
            <a href="index.html" aria-label="logo image"><img src="assets/images/resources/logo-4.png" width="105"
                    alt="" /></a>
        </div>
        <!-- /.logo-box -->
        <div class="mobile-nav__container"></div>
        <!-- /.mobile-nav__container -->

        <ul class="mobile-nav__contact list-unstyled">
            <li>
                <i class="fa fa-envelope"></i>
                <a href="mailto:needhelp@packageName__.com">needhelp@fistudy.com</a>
            </li>
            <li>
                <i class="fas fa-phone"></i>
                <a href="tel:666-888-0000">666 888 0000</a>
            </li>
        </ul><!-- /.mobile-nav__contact -->
        <div class="mobile-nav__top">
            <div class="mobile-nav__social">
                <a href="#" class="fab fa-twitter"></a>
                <a href="#" class="fab fa-facebook-square"></a>
                <a href="#" class="fab fa-pinterest-p"></a>
                <a href="#" class="fab fa-instagram"></a>
            </div><!-- /.mobile-nav__social -->
        </div><!-- /.mobile-nav__top -->



    </div>
    <!-- /.mobile-nav__content -->
</div>
