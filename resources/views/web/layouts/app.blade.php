<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <!-- SEO Meta Tags -->
    <title>@yield('title', ($siteSettings->site_title ?? null))</title>
    <meta name="description" content="@yield('meta_description', ($siteSettings->site_description ?? null))">
    <meta name="robots" content="index, follow">
    <meta name="keywords" content="@yield('meta_keywords', ($siteSettings->site_keywords ?? null))">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph -->
    <meta property="og:title" content="@yield('og_title')">
    <meta property="og:description" content="@yield('og_description')">
    <meta property="og:image" content="@yield('og_image')">
    <meta property="og:url" content="@yield('og_url')">
    <meta property="og:type" content="@yield('og_type', 'website')">
    @yield('extra_meta')



    <script>
        const siteCurrency = "{{ ($siteSettings->currency_symbol ?? null) ?? '৳' }}";
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
        })(window, document, 'script', 'dataLayer', '{{ ($siteSettings->google_analytics ?? null) }}');
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
        fbq('init', '{{ ($siteSettings->facebook_pixel ?? null) }}');
        fbq('track', 'PageView');
    </script>
    <!-- End Facebook Pixel Code -->


    <!-- Favicon -->
    @if(!empty($siteSettings->site_favicon) && !str_contains($siteSettings->site_favicon, 'default.webp'))
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
    <link href="{{ asset('web/css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    @stack('styles')
    <style>
        /* Dynamic Theme Colors */
        :root {
            --primary: {{ $siteSettings->theme_color_primary ?? '#D19C97' }};
            --primary-dark: {{ $siteSettings->theme_color_primary_hover ?? '#c17a74' }};
            --secondary: {{ $siteSettings->theme_color_secondary ?? '#EDF1FF' }};
            --dark: {{ $siteSettings->theme_color_dark ?? '#1C1C1C' }};
            
            --btn-bg: {{ $siteSettings->theme_button_bg ?? '#D19C97' }};
            --btn-text: {{ $siteSettings->theme_button_text ?? '#212529' }};
            --btn-hover-bg: {{ $siteSettings->theme_button_hover_bg ?? '#c17a74' }};
            --btn-hover-text: {{ $siteSettings->theme_button_hover_text ?? '#ffffff' }};
        }

        a.btn-primary, button.btn-primary, .btn-primary {
            background-color: var(--btn-bg) !important;
            border-color: var(--btn-bg) !important;
            color: var(--btn-text) !important;
        }

        a.btn-primary:hover, button.btn-primary:hover, .btn-primary:hover,
        a.btn-primary:focus, button.btn-primary:focus, .btn-primary:focus,
        a.btn-primary:active, button.btn-primary:active, .btn-primary:active,
        a.btn-primary.active, button.btn-primary.active, .btn-primary.active {
            background-color: var(--btn-hover-bg) !important;
            border-color: var(--btn-hover-bg) !important;
            color: var(--btn-hover-text) !important;
        }
        
        .btn-primary i, .btn-primary span {
            color: inherit !important;
        }

        .navbarShadow {
            box-shadow: 0px 15px 10px -15px rgba(0, 0, 0, 0.15);
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
            border-top: 4px solid #D19C97;
            /* Primary color */
            border-radius: 50%;
            animation: spin 1s linear infinite;
            z-index: 10;
            /* High z-index to stay on top */
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
        @include('web.layouts.partial.header')
    </div>
    <!-- Topbar End -->
    <!-- Main Content Start -->
    @yield('content')
    <!-- Main Content End -->
    <!-- Footer Start -->
    @include('web.layouts.partial.footer')
    <!-- Footer End -->
    <!-- Login Modal Start for use add to cart -->
    @include('web.layouts.partial.login_modal')
    <!-- Login Modal End -->
    <!-- Back to Top -->
    <a href="#" class="btn btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a>
    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('web/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('web/lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="mail/jqBootstrapValidation.min.js"></script>
    <script src="mail/contact.js"></script>
    <!-- Template Javascript -->
    <script src="{{ asset('web/js/main.js') }}"></script>
    <script src="{{ asset('dashboard/assets/js/sweetalert2.all.min.js') }}"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script src="{{ asset('web/js/addCart.js') }}"></script>
    <script>
        window.LaravelData = {
            route_getColorBySize: "{{ route('getColorBySize') }}",
            route_addToCart: "{{ route('addToCart') }}",
            route_wishlistToggle: "{{ route('wishlist.toggle') }}",
            csrf_token: "{{ csrf_token() }}"
        };
    </script>
    <!-- Facebook Pixel Code -->
    <noscript>
        <img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id={{ ($siteSettings->facebook_pixel ?? null) }}&ev=PageView&noscript=1" />
    </noscript>
    <!-- End Facebook Pixel Code -->
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ ($siteSettings->google_analytics ?? null) }}"
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
    @stack('script')
</body>

</html>

