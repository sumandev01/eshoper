<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>EShopper - Bootstrap Shop Template</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">
    <!-- Favicon -->
    <link href="{{ asset('web/img/favicon.ico') }}" rel="icon">
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
    </style>
</head>
<body>
    <!-- Topbar Start -->
    @include('web.layouts.partial.header')
    <!-- Topbar End -->
    <!-- Main Content Start -->
    @yield('content')
    <!-- Main Content End -->
    <!-- Footer Start -->
    @include('web.layouts.partial.footer')
    <!-- Footer End -->
    <!-- Login Modal Start for use add to cart -->
    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 450px;">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 5px; padding: 30px;">
                <div class="modal-body p-0">
                    <div class="text-left mb-4">
                        <h3 style="color: #2c4a63; font-weight: 700; margin-bottom: 5px;">Login</h2>
                        <p style="color: #6c757d; font-size: 16px;">Sign into your pages account</p>
                    </div>

                    <form id="ajaxLoginForm">
                        @csrf
                        <div class="form-group mb-4">
                            <label style="color: #6c757d; font-weight: 500;">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Your email here.."
                                style="height: 55px; border: 1px solid #e0e0e0; border-radius: 5px; background: #fff;">
                        </div>

                        <div class="form-group mb-2">
                            <label style="color: #6c757d; font-weight: 500;">Password</label>
                            <div class="position-relative">
                                <input type="password" name="password" id="modal_password" class="form-control"
                                    placeholder="Your password here.."
                                    style="height: 55px; border: 1px solid #e0e0e0; border-radius: 5px; background: #fff;">
                                <span class="position-absolute"
                                    style="top: 18px; right: 15px; cursor: pointer; color: #6c757d;">
                                    <i class="fas fa-eye" id="togglePassword"></i>
                                </span>
                            </div>
                        </div>

                        <div class="text-left mb-4">
                            <a href="#" style="color: #8cc63f; font-weight: 500; text-decoration: none;">Forgot
                                Password?</a>
                        </div>

                        <button type="submit" class="btn w-100"
                            style="background-color: #8cc63f; color: white; height: 55px; font-size: 18px; font-weight: 500; border-radius: 3px;">
                            Login
                        </button>

                        <p class="pt-3 text-center">Don't have an account? <a href="{{ route('register') }}">Create free
                                    account</a></p>

                        <div id="loginError" class="text-danger mt-3 text-center" style="display:none;"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
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
            csrf_token: "{{ csrf_token() }}"
        };
    </script>
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
