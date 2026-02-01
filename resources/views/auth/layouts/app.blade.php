<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="wpOceans">

    <link rel="shortcut icon" type="image/png" href="{{ asset('auth/images/favicon.png') }}">

    <title>EShopper - Bootstrap Shop Template</title>
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
