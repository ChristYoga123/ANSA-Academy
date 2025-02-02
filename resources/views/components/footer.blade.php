<footer class="site-footer-two">
    <div class="site-footer-two__shape-1 img-bounce"></div>
    <div class="site-footer-two__shape-2 float-bob-y"></div>
    <div class="site-footer-two__star text-rotate-box">
        <img src="{{ asset('assets/images/shapes/site-footer-star.png') }}" alt="">
    </div>
    <div class="site-footer-two__top">
        <div class="site-footer-two__main-content">
            <div class="container">
                <div class="site-footer-two__main-content-inner">
                    {{-- <div class="site-footer-two__star rotate-me">
                        <img src="{{ asset('assets/images/shapes/site-footer-two-star.png') }}" alt="logo">
                    </div> --}}
                    <div class="row">

                        <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="100ms">
                            <div class="footer-widget-two__about">
                                <div class="footer-widget-two__about-logo">
                                    <a href="{{ route('index') }}"><img
                                            src="{{ $webResource->getFirstMediaUrl('logo-website') }}" alt="logo"
                                            width="200px"></a>
                                </div>
                                <div class="site-footer-two__social">
                                    <a href="{{ $webResource->media_sosial['linkedin'] }}"><span
                                            class="fab fa-linkedin-in"></span></a>
                                    <a href="{{ $webResource->media_sosial['instagram'] }}"><span
                                            class="fab fa-instagram"></span></a>
                                    <a href="{{ $webResource->media_sosial['youtube'] }}"><span
                                            class="fab fa-youtube"></span></a>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="200ms">
                            <div class="footer-widget-two__quick-links">
                                <h4 class="footer-widget-two__title">Link</h4>
                                <ul class="footer-widget-two__quick-links-list list-unstyled">
                                    @foreach (config('menus') as $menu)
                                        <li><a href="{{ $menu['url'] }}"> <span class="icon-plus"></span>
                                                {{ $menu['text'] }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="300ms">
                            <div class="footer-widget-two__support">
                                <h4 class="footer-widget-two__title">Support</h4>
                                <ul class="footer-widget-two__quick-links-list list-unstyled">
                                    <li><a href="{{ route('karir.index') }}"> <span class="icon-plus"></span>
                                            Karir</a>
                                    </li>
                                    <li><a href="#"> <span class="icon-plus"></span> Privacy &
                                            Policy</a></li>
                                    <li><a href="#"> <span class="icon-plus"></span>
                                            Term & Condition</a></li>
                                    <li>
                                        <a href="#"> <span class="icon-plus"></span> Refund
                                            Policy</a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-xl-2 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="400ms">
                            <div class="footer-widget-two__courses">
                                <h4 class="footer-widget-two__title">Program Kami</h4>
                                <ul class="footer-widget-two__quick-links-list list-unstyled">
                                    <li><a href="{{ route('mentoring.index') }}"> <span class="icon-plus"></span>
                                            Mentoring</a>
                                    </li>
                                    <li><a href="{{ route('kelas-ansa.index') }}"> <span class="icon-plus"></span>
                                            Kelas ANSA</a></li>
                                    <li><a href="{{ route('proofreading.index') }}"> <span class="icon-plus"></span>
                                            Proofreading</a></li>
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="site-footer__bottom">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="site-footer__bottom-inner">
                        <div class="site-footer__copyright">
                            <p class="site-footer__copyright-text">Copyright © {{ date('Y') }} <a
                                    href="route('index')">ANSA Academy</a>. All
                                Rights Reserved</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
