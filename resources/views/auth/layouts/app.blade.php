<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="wpOceans">

    <link rel="shortcut icon" type="image/png" href="{{ $siteSettings->site_favicon ? asset($siteSettings->site_favicon) : asset('auth/images/favicon.png') }}">

    <title>@hasSection('title')@yield('title') - @endif{{ $siteSettings->site_title ?? 'EShopper' }}</title>
    <link href="{{ asset('auth/css/themify-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('auth/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('auth/css/flaticon_ecommerce.css') }}" rel="stylesheet">
    <link href="{{ asset('auth/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('auth/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('auth/css/owl.carousel.css') }}" rel="stylesheet">
    <link href="{{ asset('auth/css/owl.theme.css') }}" rel="stylesheet">
    <link href="{{ asset('auth/css/slick.css') }}" rel="stylesheet">
    <link href="{{ asset('auth/css/slick-theme.css') }}" rel="stylesheet">
    <link href="{{ asset('auth/css/swiper.min.css') }}" rel="stylesheet">
    <link href="{{ asset('auth/css/owl.transitions.css') }}" rel="stylesheet">
    <link href="{{ asset('auth/css/jquery.fancybox.css') }}" rel="stylesheet">
    <link href="{{ asset('auth/css/odometer-theme-default.css') }}" rel="stylesheet">
    <!-- sweetalert2 -->
    <link rel="stylesheet" href="{{ asset('dashboard/assets/css/sweetalert2.min.css') }}">
    <link href="{{ asset('auth/css/style.css') }}" rel="stylesheet">
    <style>
        .form-style input {
            margin-bottom: 0 !important;
        }
    </style>
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
            --primary: {{ $primaryHex }};
            --dark: {{ $siteSettings->theme_color_dark ?? '#1C1C1C' }};
            --btn-bg: {{ $btnBgHex }};
            --btn-text: {{ $siteSettings->theme_button_text ?? '#212529' }};

            --primary-dark: {{ $primaryDark }};
            --primary-soft: {{ $primarySoft }};
            --secondary: {{ $secondaryColor }};
            
            --btn-hover-bg: {{ $btnHoverBg }};
            --btn-hover-text: var(--btn-text);

            --border-color: {{ $borderColor }};
        }

        /* Override Bootstrap's default primary text color with the theme's primary color */
        .text-primary {
            color: var(--primary) !important;
        }


        body {
            background-color: var(--primary-soft);
        }

        .auth-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 10px 40px var(--primaryShadowHover);
            padding: 40px;
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
        }

        .auth-card .form-control {
            border: 1px solid var(--border-color);
            border-radius: 6px;
            padding: 12px 15px;
            height: auto;
            transition: all 0.3s ease;
        }

        .auth-card .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem var(--primary-soft);
        }

        .auth-card .btn-primary {
            background-color: var(--btn-bg) !important;
            border-color: var(--btn-bg) !important;
            color: var(--btn-text) !important;
            border-radius: 6px;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .auth-card .btn-primary:hover {
            background-color: var(--btn-hover-bg) !important;
            border-color: var(--btn-hover-bg) !important;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .auth-card h2 {
            color: var(--dark);
            font-weight: 700;
        }
        
        .auth-card p {
            color: #6c757d;
        }

        /* Material Floating Labels */
        .material-floating {
            position: relative;
            background: transparent;
        }
        .material-floating input.form-control {
            height: 50px;
            padding-left: 15px;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            background-color: #f8f9fa;
        }
        .material-floating input.form-control:focus {
            background-color: white;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px var(--primary-soft) !important;
        }
        .material-floating label {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            background: #f8f9fa;
            padding: 0 5px;
            transition: all 0.2s ease;
            pointer-events: none;
            color: #6c757d;
            border-radius: 4px;
            z-index: 5;
        }
        .material-floating input:focus + label,
        .material-floating input:not(:placeholder-shown) + label {
            top: 0;
            font-size: 0.8rem;
            color: var(--primary);
            background: white;
        }

        /* Custom Checkbox for this template */
        input[type="checkbox"]:checked + label::before,
        .form-check-input:checked {
            background-color: var(--primary) !important;
            border-color: var(--primary) !important;
        }
        .form-check-input:focus {
            box-shadow: 0 0 0 0.25rem var(--primary-soft);
            border-color: var(--primary) !important;
        }
        .form-check-label {
            cursor: pointer;
            margin-left: 0.5rem;
            user-select: none;
        }
    </style>
    @stack('style')
</head>

<body>

    <!-- start page-wrapper -->
    <div class="page-wrapper">

        @yield('content')

    </div>
    <!-- end of page-wrapper -->

    <!-- All JavaScript files
    ================================================== -->
    <script src="{{ asset('auth/js/jquery.min.js') }}"></script>
    <script src="{{ asset('auth/js/bootstrap.bundle.min.js') }}"></script>
    <!-- Plugins for this template -->
    <script src="{{ asset('auth/js/modernizr.custom.js') }}"></script>
    <script src="{{ asset('auth/js/jquery.dlmenu.js') }}"></script>
    <script src="{{ asset('auth/js/jquery-plugin-collection.js') }}"></script>
    <!-- Custom script for this template -->
    <script src="{{ asset('auth/js/script.js') }}"></script>
    <!-- sweetalert2 -->
    <script src="{{ asset('dashboard/assets/js/sweetalert2.all.min.js') }}"></script>
    <script>
        // GLOBAL TOAST FUNCTION
        function showToast(type, message) {
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
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
