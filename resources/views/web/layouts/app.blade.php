<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <!-- SEO Meta Tags -->
    <title>@yield('title', $siteSettings->site_title ?? null)</title>
    <meta name="description" content="@yield('meta_description', $siteSettings->site_description ?? null)">
    <meta name="robots" content="index, follow">
    <meta name="keywords" content="@yield('meta_keywords', $siteSettings->site_keywords ?? null)">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph -->
    <meta property="og:title" content="@yield('og_title')">
    <meta property="og:description" content="@yield('og_description')">
    <meta property="og:image" content="@yield('og_image')">
    <meta property="og:url" content="@yield('og_url')">
    <meta property="og:type" content="@yield('og_type', 'website')">
    @yield('extra_meta')



    <script>
        const siteCurrency = "{{ $siteSettings->currency_symbol ?? null ?? '৳' }}";
    </script>
    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', '{{ $siteSettings->google_analytics ?? null }}');
    </script>
    <!-- End Google Tag Manager -->

    <!-- Facebook Pixel Code -->
    <script>
        ! function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '{{ $siteSettings->facebook_pixel ?? null }}');
        fbq('track', 'PageView');
    </script>
    <!-- End Facebook Pixel Code -->


    <!-- Favicon -->
    @if (!empty($siteSettings->site_favicon) && !str_contains($siteSettings->site_favicon, 'default.webp'))
        <link rel="icon" href="{{ $siteSettings->site_favicon }}">
    @endif
    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <!-- Libraries Stylesheet -->
    <link href="{{ asset('web/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <!-- sweetalert2 -->
    <link rel="stylesheet" href="{{ asset('dashboard/assets/css/sweetalert2.min.css') }}">
    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('web/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('web/css/main.style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="{{ asset('dashboard/assets/css/dataTables.min.css') }}">
    @stack('styles')
    @php
        function hexToRgb($hex) {
            $hex = str_replace('#', '', $hex);
            if (strlen($hex) == 3) {
                $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
            }
            if (strlen($hex) != 6) return [0, 0, 0];
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
            return [$r, $g, $b];
        }
        
        function mixColors($rgb1, $rgb2, $weight) {
            $w1 = $weight / 100;
            $w2 = 1 - $w1;
            $r = round($rgb1[0] * $w1 + $rgb2[0] * $w2);
            $g = round($rgb1[1] * $w1 + $rgb2[1] * $w2);
            $b = round($rgb1[2] * $w1 + $rgb2[2] * $w2);
            return "rgb($r, $g, $b)";
        }

        $primaryHex = $siteSettings->theme_color_primary ?? '#D19C97';
        $btnBgHex = $siteSettings->theme_button_bg ?? '#D19C97';
        $primaryRgb = hexToRgb($primaryHex);
        $btnBgRgb = hexToRgb($btnBgHex);

        $primaryDark = mixColors($primaryRgb, [0,0,0], 85);
        $primarySoft = "rgba({$primaryRgb[0]}, {$primaryRgb[1]}, {$primaryRgb[2]}, 0.1)";
        $primaryShadowHover = "rgba({$primaryRgb[0]}, {$primaryRgb[1]}, {$primaryRgb[2]}, 0.15)";
        $secondaryColor = mixColors($primaryRgb, [255,255,255], 10);
        $btnHoverBg = mixColors($btnBgRgb, [0,0,0], 85);
        $borderColor = mixColors($primaryRgb, [255,255,255], 30);
    @endphp
    <style>
        /* Dynamic Theme Colors */
        :root {
            /* User Defined Colors */
            --primary: {{ $primaryHex }};
            --dark: {{ $siteSettings->theme_color_dark ?? '#1C1C1C' }};
            
            --btn-bg: {{ $btnBgHex }};
            --btn-text: {{ $siteSettings->theme_button_text ?? '#212529' }};

            /* PHP Calculated Colors (Cross-Browser Compatible) */
            --primary-dark: {{ $primaryDark }};
            --primary-soft: {{ $primarySoft }};
            --secondary: {{ $secondaryColor }};
            
            --btn-hover-bg: {{ $btnHoverBg }};
            --btn-hover-text: var(--btn-text);

            --border-color: {{ $borderColor }};
        }
        
        .theme-shadow {
            border: none !important;
            box-shadow: 0 4px 15px {{ $primarySoft }} !important;
            transition: box-shadow 0.3s ease, transform 0.3s ease !important;
        }

        .theme-shadow:hover {
            box-shadow: 0 8px 25px {{ $primaryShadowHover }} !important;
            transform: translateY(-2px) !important;
        }


        .bg-secondary {
            background-color: var(--secondary) !important;
        }

        .text-secondary {
            color: var(--secondary) !important;
        }

        .bg-primary {
            background-color: var(--primary) !important;
        }

        .text-primary {
            color: var(--primary) !important;
        }

        a.btn-primary,
        button.btn-primary,
        .btn-primary {
            background-color: var(--btn-bg) !important;
            border-color: var(--btn-bg) !important;
            color: var(--btn-text) !important;
        }

        a.btn-primary:hover,
        button.btn-primary:hover,
        .btn-primary:hover,
        a.btn-primary:focus,
        button.btn-primary:focus,
        .btn-primary:focus,
        a.btn-primary:active,
        button.btn-primary:active,
        .btn-primary:active,
        a.btn-primary.active,
        button.btn-primary.active,
        .btn-primary.active {
            background-color: var(--btn-hover-bg) !important;
            border-color: var(--btn-hover-bg) !important;
            color: var(--btn-hover-text) !important;
        }

        .border,
        .border-top,
        .border-bottom,
        .border-start,
        .border-end {
            border-color: var(--border-color) !important;
        }

        .form-control,
        .form-control:focus,
        .form-control:active {
            border-color: var(--border-color) !important;
        }

        .btn-primary i,
        .btn-primary span {
            color: inherit !important;
        }

        a.text-dark:hover,
        a.text-dark:focus {
            color: var(--primary) !important;
            text-decoration: none !important;
        }

        .navbarShadow {
            box-shadow: 0px 15px 15px -10px color-mix(in srgb, var(--primary) 15%, transparent);
        }

        .custom-navbar-bg {
            position: relative;
            background-color: color-mix(in srgb, var(--primary) 15%, white);
            z-index: 999;
        }
        
        .modern-category-btn {
            border-top-left-radius: 12px !important;
            border-top-right-radius: 12px !important;
            border-bottom-left-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
        }
        .modern-category-menu {
            border: none !important;
            border-bottom-left-radius: 12px;
            border-bottom-right-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            background-color: color-mix(in srgb, var(--primary) 15%, white);
        }
        .modern-category-menu .dropdown-menu {
            background-color: color-mix(in srgb, var(--primary) 15%, white);
        }
        .modern-category-menu .nav-item {
            border-bottom: 1px solid rgba(0,0,0,0.03) !important;
            transition: all 0.3s ease;
        }
        .modern-category-menu .nav-item:last-child {
            border-bottom: none !important;
        }
        .modern-category-menu .nav-item:hover,
        .modern-category-menu .nav-item.dropdown:hover {
            background-color: color-mix(in srgb, var(--primary) 12%, white) !important;
        }
        .modern-category-menu .nav-link, 
        .modern-category-menu .custom-sub-link {
            transition: all 0.3s ease;
        }
        .modern-category-menu .nav-item:hover > .nav-link,
        .modern-category-menu .custom-sub-link:hover {
            color: var(--primary) !important;
            padding-left: 35px !important;
            background: transparent !important;
        }
        .modern-category-menu .nav-item.dropdown .dropdown-menu .custom-sub-link:hover {
            background-color: color-mix(in srgb, var(--primary) 12%, white) !important;
        }
        .modern-category-menu .nav-link.active,
        .modern-category-menu .custom-sub-link.active,
        .modern-category-menu .nav-item.dropdown .nav-link.active {
            background-color: color-mix(in srgb, var(--primary) 12%, white) !important;
            color: var(--primary) !important;
        }

        /* Custom Navbar Links */
        .navbar-light .navbar-nav .nav-link {
            color: color-mix(in srgb, var(--primary) 60%, #111) !important;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .navbar-light .navbar-nav .nav-link:hover,
        .navbar-light .navbar-nav .nav-link.active {
            color: var(--primary) !important;
        }

        /* Custom Global Dropdowns */
        .dropdown-menu:not(.modern-category-menu .dropdown-menu) {
            border-radius: 12px !important;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05) !important;
            border: none !important;
            padding: 10px 0;
            overflow: hidden;
            background: white;
        }
        .dropdown-menu:not(.modern-category-menu .dropdown-menu) .dropdown-item {
            transition: all 0.3s ease;
            color: color-mix(in srgb, var(--primary) 60%, #111);
            font-weight: 500;
            padding: 8px 20px;
        }
        .dropdown-menu:not(.modern-category-menu .dropdown-menu) .dropdown-item:hover {
            background-color: color-mix(in srgb, var(--primary) 12%, white) !important;
            color: var(--primary) !important;
            padding-left: 25px;
        }

        /* Custom Checkboxes */
        input[type="checkbox"] {
            accent-color: var(--primary);
        }
        .custom-control-input:checked ~ .custom-control-label::before {
            border-color: var(--primary) !important;
            background-color: var(--primary) !important;
        }

        /* Custom Pagination */
        .pagination .page-item.active .page-link {
            background-color: var(--primary) !important;
            border-color: var(--primary) !important;
            color: white !important;
            box-shadow: 0 5px 15px color-mix(in srgb, var(--primary) 30%, transparent);
        }
        .pagination .page-link {
            color: color-mix(in srgb, var(--primary) 60%, #111);
            transition: all 0.3s ease;
            border-radius: 8px;
            margin: 0 3px;
            border: 1px solid rgba(0,0,0,0.05);
        }
        .pagination .page-link:hover {
            background-color: color-mix(in srgb, var(--primary) 12%, white) !important;
            color: var(--primary) !important;
            border-color: transparent;
        }
        
        .modern-slider {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        .modern-slider .carousel-inner {
            border-radius: 12px;
        }

        .wpo-breadcumb-wrap ol li {
            display: inline-block;
            color: #233d50;
            position: relative;
            font-family: 'Muli', sans-serif;
            font-size: 18px;
            line-height: 30px;
            padding: 0 20px 0 5px;
        }

        .wpo-breadcumb-wrap ol li::after {
            content: "\f105";
            position: absolute;
            right: 0px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 12px;
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
        }

        .wpo-breadcumb-wrap ol li:last-child::after {
            display: none;
        }

        button:focus {
            box-shadow: none ! important;
        }

        .wishlist-btn i.active {
            color: #e74c3c !important;
        }

        .wishlist-btn i {
            color: #ccc;
        }

        .my-custom-toast {
            width: auto !important;
            min-width: 250px;
            max-width: 350px;
            padding: 10px 20px !important;
        }

        .my-custom-toast .swal2-title {
            white-space: nowrap !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            margin: 0 !important;
            font-size: 14px !important;
        }

        .my-custom-toast {
            display: flex !important;
            align-items: center !important;
        }

        /* Optimized Images */
        .optimized-image {
            aspect-ratio: 1/1;
            object-fit: contain;
            width: 100%;
            height: auto;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
            position: relative;
            z-index: 2;
        }

        .img-wrapper {
            position: relative;
            overflow: hidden;
            background-color: #f1f1f1;
            aspect-ratio: 1/1;
        }

        .img-spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--primary);
            /* Primary color */
            border-radius: 50%;
            animation: spin 1s linear infinite;
            z-index: 10;
            /* High z-index to stay on top */
        }

        .cat-item .cat-img img,
        .product-item .product-img img {
            transition: .5s !important;
        }

        @keyframes spin {
            0% {
                transform: translate(-50%, -50%) rotate(0deg);
            }

            100% {
                transform: translate(-50%, -50%) rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <!-- Topbar Start -->
    <div id="main-header-wrapper">
        @include('web.layouts.partials.header')
    </div>
    <!-- Topbar End -->
    <!-- Main Content Start -->
    @yield('content')
    <!-- Main Content End -->
    <!-- Footer Start -->
    @include('web.layouts.partials.footer')
    <!-- Footer End -->
    <!-- Login Modal Start for use add to cart -->
    @include('web.layouts.partials.login_modal')
    <!-- Login Modal End -->
    <!-- Back to Top -->
    <a href="#" class="btn btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a>
    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="{{ asset('web/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('web/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('web/lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="mail/jqBootstrapValidation.min.js"></script>
    <script src="mail/contact.js"></script>
    <!-- Template Javascript -->
    <script src="{{ asset('web/js/main.js') }}"></script>
    <script src="{{ asset('dashboard/assets/js/sweetalert2.all.min.js') }}"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script src="{{ asset('web/js/addCart.js') }}"></script>
    <script src="{{ asset('dashboard/assets/js/dataTables.min.js') }}"></script>
    <script>
        window.LaravelData = {
            route_getColorBySize: "{{ route('product.color.by.size') }}",
            route_addToCart: "{{ route('cart.add') }}",
            route_wishlistToggle: "{{ route('wishlist.toggle') }}",
            csrf_token: "{{ csrf_token() }}"
        };
    </script>
    <!-- Facebook Pixel Code -->
    <noscript>
        <img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id={{ $siteSettings->facebook_pixel ?? null }}&ev=PageView&noscript=1" />
    </noscript>
    <!-- End Facebook Pixel Code -->
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $siteSettings->google_analytics ?? null }}"
            height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <script>
        // GLOBAL TOAST FUNCTION
        function showToast(type, message) {
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                customClass: {
                    popup: 'my-custom-toast'
                },
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });

            Toast.fire({
                icon: type,
                title: message
            });
        }
    </script>
    @if (session('success'))
        <script>
            showToast('success', "{{ session('success') }}");
        </script>
    @endif

    @if (session('error'))
        <script>
            showToast('error', "{{ session('error') }}");
        </script>
    @endif

    @if ($errors->any())
        <script>
            showToast('error', "{{ $errors->first() }}");
        </script>
    @endif
    <script>
        // AJAX Cron System for Background Tasks (No cPanel required)
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                fetch("{{ route('background.tasks') }}").catch(error => console.error('Cron error:', error));
            }, 5000); // Wait 5 seconds so it doesn't impact page load
        });
    </script>

    @stack('scripts')
</body>

</html>


