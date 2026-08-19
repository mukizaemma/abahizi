<!doctype html>
<html class="no-js" lang="{{ app()->getLocale() === 'rw' ? 'rw' : 'en' }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ $setting->company ?? ''}}</title>
    <meta name="description" content="Premium custom handbag manufacturing in Masoro, Rwanda. B-Corp certified CMT factory delivering ethical production for global fashion brands.">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="form-channel-intent-url" content="{{ route('formChannel.intent') }}">


    <!-- Place favicon.ico in the root directory -->
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('storage\images').($setting->logo ?? '')}}">

    <!-- CSS here -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom-animation.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/flaticon.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/swiper-bundle.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/meanmenu.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome-pro.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/spacing.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/theme-custom.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/luxury-demo.css') }}">
    @if(request()->routeIs('home'))
        <link rel="stylesheet" href="{{ asset('assets/css/landing-home.css') }}">
    @endif

    @php
        $theme = \App\Support\ThemeService::fromSetting($setting ?? null);
        $isLandingHome = request()->routeIs('home');
    @endphp

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="{{ $theme['fonts_href'] }}" rel="stylesheet">

    <style>
        :root {
            --brand-primary: {{ $theme['primary'] }};
            --brand-secondary: {{ $theme['secondary'] }};
            --brand-neutral: {{ $theme['neutral'] }};
            --brand-on-primary: {{ $theme['on_primary'] }};
            --bs-primary: {{ $theme['primary'] }};
            --bs-primary-rgb: {{ $theme['primary_rgb'] }};
            --lux-sans: "{{ $theme['body_font'] }}", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            --lux-serif: "{{ $theme['heading_font'] }}", Georgia, "Times New Roman", serif;
        }
        body {
            font-family: var(--lux-sans);
        }
    </style>
</head>

<body @class(['landing-home' => $isLandingHome ?? false])>

    <!-- back-to-top-start  -->
    <button class="scroll-top scroll-to-target" data-target="html">
        <i class="far fa-angle-double-up"></i>
    </button>
    <!-- back-to-top-end  -->

        <!-- tp-offcanvus-area-start -->
    <div class="tpoffcanvas-area">
        <div class="tpoffcanvas">
            <div class="tpoffcanvas__close-btn">
                <button class="close-btn"><i class="fal fa-times"></i></button>
            </div>
            <div class="tpoffcanvas__logo">
                <a href="{{ route('home') }}">
                    <img src="{{asset('storage\images').($setting->logo ?? '')}}" alt="" width="120px">
                </a>
            </div>
            <div class="tpoffcanvas__title">
                
            </div>
            <div class="tp-main-menu-mobile d-xl-none"></div>
            {{-- <div class="tpoffcanvas__contact-info">
                <div class="tpoffcanvas__contact-title">
                    <h5>Contact us</h5>
                </div>
                <ul>
                    <li>
                    <i class="fa-light fa-location-dot"></i>
                    <a  target="_blank">{{ $setting->address ?? '' }}</a>
                    </li>
                    <li>
                    <i class="fas fa-envelope"></i>
                    <a href="mailto:{{ $setting->email ?? '' }}">{{ $setting->email ?? '' }}</a>
                    </li>
                    <li>
                    <i class="fal fa-phone-alt"></i>
                    <a href="tel:{{ $setting->phone ?? '' }}">{{ $setting->phone ?? '' }}</a>
                    </li>
                </ul>
            </div>
            
            <div class="tpoffcanvas__social">
                <div class="row align-items-center">
                    <div class="col-12 mt-5">
                        <div class="tp-copyright__socials text-center text-sm-start">
                            <a href="{{ $setting->facebook ?? '' }}" class="btn btn-secondary" target="_blank"><i class="fab fa-facebook-f"></i></a>
                            <a href="{{ $setting->instagram ?? '' }}" class="btn btn-secondary" target="_blank"><i class="fab fa-instagram"></i></a>
                            <a href="{{ $setting->twitter ?? '' }}" class="btn btn-secondary" target="_blank"><i class="fab fa-twitter"></i></a>
                            <a href="{{ $setting->youtube ?? '' }}" class="btn btn-secondary" target="_blank"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
        </div>
        
    <div class="body-overlay"></div>
    <!-- tp-offcanvus-area-end -->

    <header class="tp-header-height">
        
        <!-- header-area-start -->
        <div id="header-sticky" class="tp-header-3__area">
            <div class="container">
                <div class="row align-items-center site-header-row">
                    <div class="col-xl-2 col-lg-6 col-md-4 col-7">
                        <div class="tp-header-3__logo">
                            <a href="{{route('home')}}">
                                <img src="{{asset('storage\images').($setting->logo ?? '')}}" alt="{{ $setting->company ?? 'Abahizi CBC' }}" class="site-header__logo-img" width="auto" height="72">
                            </a>
                        </div>
                    </div>
                    <div class="col-xl-8 d-none d-xl-block">
                        <div class="tp-header-3__main-menu">
                            <nav class="tp-main-menu-content">
                                <ul>
                                    <li><a href="{{ route('home') }}">{{ __('site.nav.home') }}</a></li>
                                    <li class="has-dropdown">
                                        <a href="{{ route('ourMission') }}">{{ __('site.nav.about_us') }}</a>
                                        <ul class="submenu tp-submenu">
                                            <li><a href="{{ route('ourMission') }}">{{ __('site.nav.mission') }}</a></li>
                                            <li><a href="{{ route('whatWeDo') }}">{{ __('site.nav.what_we_do') }}</a></li>
                                            <li><a href="{{ route('team') }}">{{ __('site.nav.team') }}</a></li>
                                            <li><a href="{{ route('testimonials') }}">{{ __('site.nav.testimonials') }}</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="{{ route('ourFactory') }}">{{ __('site.nav.factory') }}</a></li>
                                    @if(($setting->show_products_page ?? true))
                                        <li><a href="{{ route('ourProducts') }}">{{ __('site.nav.products') }}</a></li>
                                    @endif
                                    <li class="has-dropdown">
                                        <a href="{{ route('impactPage') }}">{{ __('site.nav.impact') }}</a>
                                        <ul class="submenu tp-submenu">
                                            <li><a href="{{ route('impactEmployeeEmpowerment') }}">{{ __('site.nav.employee_empowerment') }}</a></li>
                                            <li><a href="{{ route('impactCommunity') }}">{{ __('site.nav.community') }}</a></li>
                                            <li><a href="{{ route('impactReports') }}">{{ __('site.nav.social_impact_reports') }}</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="{{ route('posts') }}">{{ __('site.nav.updates') }}</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-6 col-md-8 col-5">
                        <div class="tp-header-3__right-box">
                            <div class="tp-header-3__right-action text-end">
                                <ul class="d-flex align-items-center justify-content-end">
                                    <li>
                                        <div class="tp-header-3__btn d-none d-md-block">
                                            <a class="tp-btn tp-btn--lux" href="{{ route('contacts') }}">{{ __('site.nav.inquiry') }}</a>
                                        </div>
                                    </li>  
                                    <li>
                                        <div class="tp-header-3__bar d-xl-none">
                                            <button class="tp-menu-bar"><i class="fa-solid fa-bars-staggered"></i></button>
                                        </div>
                                    </li>                                  
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- header-area-end -->
    </header>

    <main>
        
        @yield('content')
    </main>

    <footer class="site-footer" role="contentinfo">
        <div class="site-footer__upper">
            <div class="container py-5 py-lg-5">
                <div class="row g-4 g-lg-4 g-xl-5 align-items-start justify-content-between site-footer__grid">
                    <div class="col-12 col-lg-4 site-footer__col">
                        <a href="{{ route('home') }}" class="site-footer__logo-link d-inline-block">
                            @if(!empty($setting->logo))
                                <img src="{{ asset('storage/images' . $setting->logo) }}" alt="{{ $setting->company ?? 'Abahizi CBC' }}" class="site-footer__logo" height="88" width="auto">
                            @else
                                <span class="site-footer__wordmark h4 text-white mb-0">{{ $setting->company ?? 'Abahizi CBC' }}</span>
                            @endif
                        </a>
                        <p class="site-footer__tagline mt-3 mb-0">{{ __('site.footer.tagline') }}</p>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-4 site-footer__col">
                        <h3 class="site-footer__heading">Explore</h3>
                        <ul class="site-footer__nav list-unstyled mb-0">
                            <li><a href="{{ route('home') }}">{{ __('site.nav.home') }}</a></li>
                            <li><a href="{{ route('ourMission') }}">{{ __('site.nav.mission') }}</a></li>
                            <li><a href="{{ route('ourFactory') }}">{{ __('site.nav.factory') }}</a></li>
                            <li><a href="{{ route('impactPage') }}">{{ __('site.nav.impact') }}</a></li>
                            <li><a href="{{ route('posts') }}">{{ __('site.nav.updates') }}</a></li>
                            <li><a href="{{ route('impactReports') }}">{{ __('site.nav.social_impact_reports') }}</a></li>
                        </ul>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-4 site-footer__col site-footer__col--touch">
                        <h3 class="site-footer__heading">Get in touch</h3>
                        <ul class="site-footer__contact list-unstyled mb-0">
                            @if(!empty($setting->phone))
                                <li class="mb-2">
                                    <a href="tel:{{ $setting->phone }}" class="site-footer__contact-link">
                                        <i class="far fa-phone site-footer__contact-icon" aria-hidden="true"></i>
                                        {{ $setting->phone }}
                                    </a>
                                </li>
                            @endif
                            @if(!empty($setting->email))
                                <li class="mb-2">
                                    <a href="mailto:{{ $setting->email }}" class="site-footer__contact-link">
                                        <i class="far fa-envelope site-footer__contact-icon" aria-hidden="true"></i>
                                        {{ $setting->email }}
                                    </a>
                                </li>
                            @endif
                            @if(!empty($setting->address))
                                <li>
                                    <span class="site-footer__contact-link site-footer__contact-link--static">
                                        <i class="far fa-location-dot site-footer__contact-icon" aria-hidden="true"></i>
                                        {{ $setting->address }}
                                    </span>
                                </li>
                            @endif
                        </ul>

                        @if(!empty($setting->facebook) || !empty($setting->instagram))
                            <div class="site-footer__socials mt-3">
                                @if(!empty($setting->facebook))
                                    <a href="{{ $setting->facebook }}" class="site-footer__social" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                                @endif
                                @if(!empty($setting->instagram))
                                    <a href="{{ $setting->instagram }}" class="site-footer__social" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                                @endif
                            </div>
                        @endif

                        <div class="site-footer__cta-block">
                            <div class="site-footer__cta-group">
                                <a href="{{ route('contacts') }}" class="site-footer__btn site-footer__btn--order tp-btn--lux">
                                    <i class="fas fa-envelope" aria-hidden="true"></i>
                                    {{ __('site.nav.inquiry') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="site-footer__lower">
            <div class="container py-3 py-md-4">
                <div class="row align-items-center justify-content-between g-2">
                    <div class="col-12 col-md-auto text-center text-md-start">
                        <span class="site-footer__copy">&copy; Abahizi CBC <span id="footer-year"></span></span>
                        <span class="site-footer__copy-sep d-none d-md-inline">·</span>
                        <span class="site-footer__credit">Site by <a href="https://iremetech.com" target="_blank" rel="noopener noreferrer">Ireme Technologies</a></span>
                    </div>
                    <div class="col-12 col-md-auto text-center text-md-end">
                        <a href="{{ route('ourProducts') }}" class="site-footer__mini-link">Products</a>
                    </div>
                </div>
            </div>
        </div>
        <script>
            document.getElementById('footer-year').textContent = new Date().getFullYear();
        </script>
    </footer>

    @php
        $whatsappChatNumber = preg_replace('/\D+/', '', $setting->phone ?? $setting->phone1 ?? '');
    @endphp
    @if($whatsappChatNumber !== '')
        <a href="https://wa.me/{{ $whatsappChatNumber }}" class="site-float-whatsapp" target="_blank" rel="noopener noreferrer" title="Chat on WhatsApp" aria-label="Chat on WhatsApp">
            <i class="fab fa-whatsapp" aria-hidden="true"></i>
        </a>
    @endif

    <!-- JS here -->
    <script src="{{ asset('assets/js/jquery.js') }}"></script>
    <script src="{{ asset('assets/js/waypoints.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/slick.js') }}"></script>
    <script src="{{ asset('assets/js/magnific-popup.js') }}"></script>
    <script src="{{ asset('assets/js/purecounter.js') }}"></script>
    <script src="{{ asset('assets/js/wow.js') }}"></script>
    <script src="{{ asset('assets/js/nice-select.js') }}"></script>
    <script src="{{ asset('assets/js/swiper-bundle.js') }}"></script>
    <script src="{{ asset('assets/js/isotope-pkgd.js') }}"></script>
    <script src="{{ asset('assets/js/imagesloaded-pkgd.js') }}"></script>
    <script src="{{ asset('assets/js/ajax-form.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/site-form-channels.js') }}"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
    <script src="{{ asset('assets/js/luxury-site.js') }}"></script>
    @if($isLandingHome ?? false)
        <script src="{{ asset('assets/js/landing-home.js') }}"></script>
    @endif

</body>

</html>