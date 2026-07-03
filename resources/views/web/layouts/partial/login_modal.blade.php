<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 450px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 5px; padding: 30px;">
            <div class="modal-body p-0">
                <div class="text-left mb-4">
                    <h2 style="color: #2c4a63; font-weight: 700; margin-bottom: 5px;">Login</h2>
                    <p style="color: #6c757d; font-size: 16px;">Sign into your pages account</p>
                </div>

                <form id="ajaxLoginForm">
                    @csrf
                    <div class="form-group mb-4">
                        <input type="email" name="email" class="form-control" placeholder="Your email here.."
                            style="height: 55px; border: 1px solid #e0e0e0; border-radius: 5px; background: #fff;">
                    </div>

                    <div class="form-group mb-2">
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

                    <div class="d-flex justify-content-between align-items-center mb-4 text-left">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="rememberMe" name="remember">
                            <label class="custom-control-label" for="rememberMe" style="color: #6c757d; font-weight: 500; cursor: pointer;">Keep me signed in</label>
                        </div>
                        <a href="#" class="text-primary"
                            style="font-weight: 500; text-decoration: none;">Forgot Password?</a>
                    </div>

                    <button type="submit" class="btn bg-primary w-100 d-flex justify-content-center align-items-center">
                        <span class="spinner-border spinner-border-sm d-none mr-2" role="status" aria-hidden="true" style="margin-right: 8px;"></span>
                        <span class="btn-text">Login</span>
                    </button>

                    <p class="pt-3 text-center">Don't have an account? <a href="{{ route('register') }}">Create free account</a></p>

                    <div id="loginError" class="text-danger mt-3 text-center" style="display:none;"></div>
                </form>
            </div>
        </div>
    </div>
</div>
