<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 450px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 5px; padding: 30px;">
            <div class="modal-body p-0">
                <div class="text-start mb-4">
                    <h2 style="color: #2c4a63; font-weight: 700; margin-bottom: 5px;">Login</h2>
                    <p style="color: #6c757d; font-size: 16px;">Login to your account</p>
                </div>
                <style>
                    .material-floating { position: relative; background: transparent; }
                    .material-floating input.form-control { height: 50px; padding-left: 15px; border-radius: 5px; border: 1px solid #e0e0e0; background-color: #fff; }
                    .material-floating input.form-control:focus { background-color: white; border-color: var(--primary); box-shadow: 0 0 0 4px color-mix(in srgb, var(--primary) 20%, transparent) !important; outline: none; }
                    .material-floating label { position: absolute; top: 50%; left: 15px; transform: translateY(-50%); background: #fff; padding: 0 5px; transition: all 0.2s ease; pointer-events: none; color: #6c757d; border-radius: 4px; z-index: 5; margin: 0; }
                    .material-floating input:focus + label, .material-floating input:not(:placeholder-shown) + label { top: 0; font-size: 0.8rem; color: var(--primary); background: white; }
                </style>
                <form id="ajaxLoginForm">
                    @csrf
                    <div class="mb-3 material-floating">
                        <input type="email" name="email" id="modalEmail" class="form-control" placeholder=" ">
                        <label for="modalEmail">Email</label>
                    </div>

                    <div class="mb-3 position-relative">
                        <div class="material-floating">
                            <input type="password" name="password" id="modal_password" class="form-control"
                                placeholder=" " style="padding-right: 40px;">
                            <label for="modal_password">Password</label>
                        </div>
                        <span class="position-absolute"
                            style="top: 50%; right: 15px; transform: translateY(-50%); cursor: pointer; color: #6c757d; z-index: 10;">
                            <i class="fas fa-eye" id="togglePassword"></i>
                        </span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4 text-start">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="rememberMe" name="remember">
                            <label class="custom-control-label" for="rememberMe" style="color: #6c757d; font-weight: 500; cursor: pointer;">Keep me signed in</label>
                        </div>
                        <a href="{{ route('password.request') }}" class="text-primary"
                            style="font-weight: 500; text-decoration: none;">Forgot Password?</a>
                    </div>

                    <button type="submit" class="btn bg-primary w-100 d-flex justify-content-center align-items-center">
                        <span class="spinner-border spinner-border-sm d-none me-2" role="status" aria-hidden="true" style="margin-right: 8px;"></span>
                        <span class="btn-text">Login</span>
                    </button>

                    <p class="pt-3 text-center">Don't have an account? <a href="{{ route('register') }}">Create free account</a></p>

                    <div class="text-center mt-3 mb-2">
                        <span class="text-muted">OR</span>
                    </div>

                    <div class="row">
                        <div class="col-6 pe-2">
                            <a href="{{ route('social.login', 'google') }}" class="btn btn-outline-danger w-100 d-flex justify-content-center align-items-center" style="height: 45px; border-radius: 5px;">
                                <i class="fab fa-google me-2"></i> Google
                            </a>
                        </div>
                        <div class="col-6 ps-2">
                            <a href="{{ route('social.login', 'facebook') }}" class="btn btn-outline-primary w-100 d-flex justify-content-center align-items-center" style="height: 45px; border-radius: 5px;">
                                <i class="fab fa-facebook-f me-2"></i> Facebook
                            </a>
                        </div>
                    </div>

                    <div id="loginError" class="text-danger mt-3 text-center" style="display:none;"></div>
                </form>
            </div>
        </div>
    </div>
</div>


