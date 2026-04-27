<!doctype html>
<html class="no-js" lang="zxx">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ $setting->company ?? ''}}</title>
    <meta name="description" content="A Community-based Initiative Dedicated to Transforming Lives through Holistic Empowerment, Leadership Development, and Spiritual Growth">
    <meta name="viewport" content="width=device-width, initial-scale=1">


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

    @php
        $primary = $setting->primary_color ?? '#fad200';
        $secondary = $setting->secondary_color ?? '#2c2c2c';
        $neutral = $setting->neutral_color ?? '#b0b0b0';
        $fontFamily = $setting->font_family ?? 'Poppins';
        $googleFontParam = str_replace(' ', '+', $fontFamily);
    @endphp

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family={{ $googleFontParam }}:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: "{{ $fontFamily }}", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }
        :root {
            --brand-primary: {{ $primary }};
            --brand-secondary: {{ $secondary }};
            --brand-neutral: {{ $neutral }};
        }
    </style>
</head>

<body>

    <!-- preloader -->
    <div id="preloader">
        <div class="preloader">
            <span></span>
            <span></span>
        </div>
    </div>
    <!-- preloader end  -->

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
                                <img src="{{asset('storage\images').($setting->logo ?? '')}}" alt="" width="90px">
                            </a>
                        </div>
                    </div>
                    <div class="col-xl-8 d-none d-xl-block">
                        <div class="tp-header-3__main-menu">
                            <nav class="tp-main-menu-content">
                                <ul>
                                    <li><a href="{{ route('home') }}">Home</a></li>
                                    <li class="has-dropdown">
                                        <a href="{{ route('backgroundDetails') }}">About</a>
                                        <ul class="submenu tp-submenu">
                                            <li><a href="{{ route('backgroundDetails') }}">Background / History</a></li>
                                            <li><a href="{{ route('ourApproach') }}">Our Approach</a></li>
                                            <li><a href="{{ route('ourModel') }}">Our Model</a></li>
                                            <li><a href="{{ route('team') }}">Our Team</a></li>
                                        </ul>
                                    </li>
                                    <li class="has-dropdown">
                                        <a href="{{ route('showPrograms') }}">Our Programs</a>
                                        <ul class="submenu tp-submenu">
                                            @forelse ($ourPrograms as $prog)
                                                <li><a href="{{ route('programShow', ['slug' => $prog->slug]) }}">{{ $prog->title }}</a></li>
                                            @empty
                                                <li><span class="px-3 d-inline-block text-muted small">No programs available</span></li>
                                            @endforelse
                                        </ul>
                                    </li>
                                    <li><a href="{{ route('ourProducts') }}">Our Products</a></li>
                                    <li><a href="{{ route('ourFactory') }}">Our Factory</a></li>
                                    <li><a href="{{ route('impactPage') }}">Impact</a></li>
                                    <li><a href="{{ route('contacts') }}">Contacts</a></li>
                                    <li><a href="{{ route('handoverPage') }}">Handover</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-6 col-md-8 col-5">
                        <div class="tp-header-3__right-box">
                            <div class="tp-header-3__right-action text-end">
                                <ul class="d-flex align-items-center justify-content-end">
                                    {{-- <li>
                                        <div class="tp-header-3__icon-box d-none d-md-block">
                                            <button class="search-open-btn"><i class="flaticon-loupe"></i></button><a href="{{ route('login') }}"><i class="flaticon-user"></i></a>
                                        </div>
                                    </li>                                     --}}
                                    <li>
                                        <div class="tp-header-3__btn d-none d-md-block">
                                            <a class="tp-btn" href="{{ route('getInvolved') }}">Get involved</a>
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
                                <img src="{{ asset('storage/images' . $setting->logo) }}" alt="{{ $setting->company ?? 'Abahizi Rwanda' }}" class="site-footer__logo" height="88" width="auto">
                            @else
                                <span class="site-footer__wordmark h4 text-white mb-0">{{ $setting->company ?? 'Abahizi Rwanda' }}</span>
                            @endif
                        </a>
                        <p class="site-footer__tagline mt-3 mb-0">From your heart to theirs—help us impact lives that truly matter.</p>

                        <ul class="site-footer__contact list-unstyled mt-4 mb-0">
                            @if(!empty($setting->email))
                                <li class="mb-2">
                                    <a href="mailto:{{ $setting->email }}" class="site-footer__contact-link">
                                        <i class="far fa-envelope site-footer__contact-icon" aria-hidden="true"></i>
                                        {{ $setting->email }}
                                    </a>
                                </li>
                            @endif
                            @if(!empty($setting->phone))
                                <li>
                                    <a href="tel:{{ $setting->phone }}" class="site-footer__contact-link">
                                        <i class="far fa-phone site-footer__contact-icon" aria-hidden="true"></i>
                                        {{ $setting->phone }}
                                    </a>
                                </li>
                            @endif
                        </ul>

                        <div class="site-footer__socials mt-3">
                            @if(!empty($setting->facebook))
                                <a href="{{ $setting->facebook }}" class="site-footer__social" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                            @endif
                            @if(!empty($setting->instagram))
                                <a href="{{ $setting->instagram }}" class="site-footer__social" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                            @endif
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-4 site-footer__col">
                        <h3 class="site-footer__heading">Explore</h3>
                        <ul class="site-footer__nav list-unstyled mb-0">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li><a href="{{ route('backgroundDetails') }}">About us</a></li>
                            <li><a href="{{ route('showPrograms') }}">Our programs</a></li>
                            <li><a href="{{ route('ourProducts') }}">Our products</a></li>
                            <li><a href="{{ route('impactPage') }}">Impact</a></li>
                            <li><a href="{{ route('contacts') }}">Contact</a></li>
                            <li><a href="{{ route('handoverPage') }}">Handover</a></li>
                        </ul>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-4 site-footer__col">
                        <h3 class="site-footer__heading">Our programs</h3>
                        <ul class="site-footer__nav site-footer__programs-list list-unstyled mb-0">
                            @forelse ($ourPrograms as $rs)
                                <li><a href="{{ route('project', ['slug' => $rs->slug]) }}">{{ $rs->title }}</a></li>
                            @empty
                                <li class="site-footer__muted">Programs will appear here when published.</li>
                            @endforelse
                        </ul>

                        <div class="site-footer__cta-below-programs pt-3 mt-3">
                            <div class="site-footer__cta-group">
                                <a href="{{ route('requestOrder') }}" class="site-footer__btn site-footer__btn--order">
                                    <i class="fas fa-clipboard-list" aria-hidden="true"></i>
                                    Request an order
                                </a>
                                <a href="{{ route('getInvolved') }}" class="site-footer__btn site-footer__btn--ghost">
                                    Get involved
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
                        <span class="site-footer__copy">&copy; Abahizi Rwanda <span id="footer-year"></span></span>
                        <span class="site-footer__copy-sep d-none d-md-inline">·</span>
                        <span class="site-footer__credit">Site by <a href="https://iremetech.com" target="_blank" rel="noopener noreferrer">Ireme Technologies</a></span>
                    </div>
                    <div class="col-12 col-md-auto text-center text-md-end">
                        <a href="{{ route('ourProducts') }}" class="site-footer__mini-link">Products</a>
                        <span class="site-footer__copy-sep">·</span>
                        <a href="{{ route('requestOrder') }}" class="site-footer__mini-link">Order request</a>
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



</body>

</html>