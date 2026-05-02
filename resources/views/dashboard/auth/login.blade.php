<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Purple Admin</title>
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendors/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/assets/css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('dashboard/assets/images/favicon.png') }}" />
</head>
<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth">
                <div class="row flex-grow">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left p-5">
                            <div class="brand-logo">
                                <img src="../../assets/images/logo.svg">
                            </div>
                            <h4>Hello! let's get started</h4>
                            @error('loginError')
                                <span class="text-danger my-2 d-block">{{ $message }}</span>
                            @enderror
                            <h6 class="font-weight-light">Sign in to continue.</h6>
                            <form class="pt-3" method="POST" action="{{ route('admin.login') }}">
                                @csrf
                                <x-input id="email" class="form-control form-control-lg" placeholder="Email" type="email" name="email" :value="old('email')" autofocus />
                                <div style="position: relative;">
                                    <x-input id="password" class="form-control form-control-lg" placeholder="Password" type="password" name="password" />
                                    <i class="mdi mdi-eye-off-outline" id="togglePassword" style="position: absolute; top: 0px; right: 15px; transform: translateY(90%); cursor: pointer;"></i>
                                </div>
                                <div class="mt-3 d-grid gap-2">
                                    <button class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn" type="submit">SIGN IN</button>
                                </div>
                                <div class="my-2 d-flex justify-content-between align-items-center">
                                    <div class="form-check">
                                        <label class="form-check-label text-muted">
                                            <input type="checkbox" name="remember" class="form-check-input"> Keep me signed in </label>
                                    </div>
                                    <a href="#" class="auth-link text-primary">Forgot password?</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- content-wrapper ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <script src="{{ asset('dashboard/assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('dashboard/assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('dashboard/assets/js/misc.js') }}"></script>
    <script src="{{ asset('dashboard/assets/js/settings.js') }}"></script>
    <script src="{{ asset('dashboard/assets/js/todolist.js') }}"></script>
    <script src="{{ asset('dashboard/assets/js/jquery.cookie.js') }}"></script>
    <!-- endinject -->
    <script>
        const password = document.querySelector('#password');
        const togglePassword = document.querySelector('#togglePassword');

        togglePassword.addEventListener('click', function (e) {
            if (password.type === 'password') {
                password.type = 'text';
                togglePassword.classList.remove('mdi-eye-off-outline');
                togglePassword.classList.add('mdi-eye-outline');
            } else {
                password.type = 'password';
                togglePassword.classList.remove('mdi-eye-outline');
                togglePassword.classList.add('mdi-eye-off-outline');
            }
        })
    </script>
</body>

</html>
