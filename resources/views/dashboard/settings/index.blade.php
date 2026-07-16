@extends('dashboard.layouts.app')
@section('title', ($siteSettings->site_title ?? null) . ' - ' . 'Settings')
@section('content')
    <style>
        .settings-nav .nav-link {
            color: #495057;
            font-weight: 500;
            border-left: 3px solid transparent;
            transition: all 0.3s ease;
        }
        .settings-nav .nav-link:hover {
            background-color: #f8f9fa;
        }
        .settings-nav .nav-link.active {
            background-color: #f8f9fa;
            color: var(--primary);
            border-left: 3px solid var(--primary);
            border-radius: 0;
        }
    </style>

    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
            <div class="page-title-box">
                <h3 class="mb-0">Settings</h3>
                <p class="text-muted fw-bold mt-2 mb-0">Manage all your settings</p>
            </div>
        </div>

        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- ================= LEFT SIDEBAR TABS ================= -->
                <div class="col-lg-3 mb-4">
                    <div class="card shadow-sm border-0 sticky-top" style="top: 80px; z-index: 10;">
                        <div class="card-body p-0">
                            <div class="nav flex-column nav-pills settings-nav" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                <button class="nav-link active text-start px-4 py-3 border-bottom" id="v-pills-basic-tab" data-bs-toggle="pill" data-bs-target="#v-pills-basic" type="button" role="tab" aria-selected="true">
                                    <i class="mdi mdi-information-outline me-2"></i> Basic Information
                                </button>
                                <button class="nav-link text-start px-4 py-3 border-bottom" id="v-pills-theme-tab" data-bs-toggle="pill" data-bs-target="#v-pills-theme" type="button" role="tab" aria-selected="false">
                                    <i class="mdi mdi-palette me-2"></i> Appearance
                                </button>
                                <button class="nav-link text-start px-4 py-3 border-bottom" id="v-pills-contact-tab" data-bs-toggle="pill" data-bs-target="#v-pills-contact" type="button" role="tab" aria-selected="false">
                                    <i class="mdi mdi-card-account-mail-outline me-2"></i> Contact & Footer
                                </button>
                                <button class="nav-link text-start px-4 py-3 border-bottom" id="v-pills-mail-tab" data-bs-toggle="pill" data-bs-target="#v-pills-mail" type="button" role="tab" aria-selected="false">
                                    <i class="mdi mdi-email-outline me-2"></i> Mail Settings
                                </button>
                                <button class="nav-link text-start px-4 py-3 border-bottom" id="v-pills-social-tab" data-bs-toggle="pill" data-bs-target="#v-pills-social" type="button" role="tab" aria-selected="false">
                                    <i class="mdi mdi-share-variant me-2"></i> Social Media
                                </button>
                                <button class="nav-link text-start px-4 py-3 border-bottom" id="v-pills-tracking-tab" data-bs-toggle="pill" data-bs-target="#v-pills-tracking" type="button" role="tab" aria-selected="false">
                                    <i class="mdi mdi-cog-outline me-2"></i> Tracking & Config
                                </button>
                                <button class="nav-link text-start px-4 py-3" id="v-pills-home-tab" data-bs-toggle="pill" data-bs-target="#v-pills-home" type="button" role="tab" aria-selected="false">
                                    <i class="mdi mdi-home-outline me-2"></i> Home Page Content
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ================= RIGHT CONTENT AREA ================= -->
                <div class="col-lg-9">
                    <div class="tab-content" id="v-pills-tabContent">
                        
                        <!-- TAB: Basic Information -->
                        <div class="tab-pane fade show active" id="v-pills-basic" role="tabpanel" aria-labelledby="v-pills-basic-tab">
                            <div class="card shadow-sm border-0 mb-4">
                                <div class="card-header bg-white py-3 border-bottom">
                                    <h5 class="mb-0 text-primary">Basic Information</h5>
                                </div>
                                <div class="card-body p-4">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold text-muted mb-2">Site Title</label>
                                            <x-input name="site_title" class="fs-6" type="text" :value="$siteSettings->site_title ?? null" :required="false" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold text-muted mb-2">Site Keywords</label>
                                            <x-input name="site_keywords" class="fs-6" type="text" :value="$siteSettings->site_keywords ?? null" :required="false" />
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label class="form-label fw-bold text-muted mb-2">Site Description</label>
                                            <x-input name="site_description" class="fs-6" type="text" :value="$siteSettings->site_description ?? null" :required="false" :rows="3" />
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label fw-bold text-muted mb-2">Logo</label>
                                            <x-media-thumbnail button_label="Select Logo" input_name="site_logo" :existing_image="$logoImage" :existing_id="$logoId" :target_id="'logo'"/>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label fw-bold text-muted mb-2">Mobile Logo</label>
                                            <x-media-thumbnail button_label="Select Mobile Logo" input_name="site_mobile_logo" :existing_image="$mobileLogoImage" :existing_id="$mobileLogoId" :target_id="'mobile_logo'"/>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label fw-bold text-muted mb-2">Favicon</label>
                                            <x-media-thumbnail button_label="Select Favicon" input_name="site_favicon" :existing_image="$faviconImage" :existing_id="$faviconId" :target_id="'favicon'"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TAB: Appearance -->
                        <div class="tab-pane fade" id="v-pills-theme" role="tabpanel" aria-labelledby="v-pills-theme-tab">
                            <div class="card shadow-sm border-0 mb-4">
                                <div class="card-header bg-white py-3 border-bottom">
                                    <h5 class="mb-0 text-primary">Appearance (Theme & Button Colors)</h5>
                                </div>
                                <div class="card-body p-4">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold text-muted mb-2">Primary Color</label>
                                            <div class="d-flex align-items-center gap-2">
                                                <input type="color" class="form-control form-control-color color-sync-picker" style="max-width: 60px;" value="{{ $siteSettings->theme_color_primary ?? '#D19C97' }}">
                                                <input type="text" name="theme_color_primary" class="form-control color-sync-text" value="{{ $siteSettings->theme_color_primary ?? '#D19C97' }}" placeholder="#RRGGBB">
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold text-muted mb-2">Dark Color</label>
                                            <div class="d-flex align-items-center gap-2">
                                                <input type="color" class="form-control form-control-color color-sync-picker" style="max-width: 60px;" value="{{ $siteSettings->theme_color_dark ?? '#1C1C1C' }}">
                                                <input type="text" name="theme_color_dark" class="form-control color-sync-text" value="{{ $siteSettings->theme_color_dark ?? '#1C1C1C' }}" placeholder="#RRGGBB">
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold text-muted mb-2">Button Background Color</label>
                                            <div class="d-flex align-items-center gap-2">
                                                <input type="color" class="form-control form-control-color color-sync-picker" style="max-width: 60px;" value="{{ $siteSettings->theme_button_bg ?? '#D19C97' }}">
                                                <input type="text" name="theme_button_bg" class="form-control color-sync-text" value="{{ $siteSettings->theme_button_bg ?? '#D19C97' }}" placeholder="#RRGGBB">
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold text-muted mb-2">Button Text Color</label>
                                            <div class="d-flex align-items-center gap-2">
                                                <input type="color" class="form-control form-control-color color-sync-picker" style="max-width: 60px;" value="{{ $siteSettings->theme_button_text ?? '#212529' }}">
                                                <input type="text" name="theme_button_text" class="form-control color-sync-text" value="{{ $siteSettings->theme_button_text ?? '#212529' }}" placeholder="#RRGGBB">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TAB: Contact & Footer -->
                        <div class="tab-pane fade" id="v-pills-contact" role="tabpanel" aria-labelledby="v-pills-contact-tab">
                            <div class="card shadow-sm border-0 mb-4">
                                <div class="card-header bg-white py-3 border-bottom">
                                    <h5 class="mb-0 text-primary">Contact & Footer</h5>
                                </div>
                                <div class="card-body p-4">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold text-muted mb-2">Contact Email</label>
                                            <x-input name="contact_email" class="fs-6" type="email" :value="$siteSettings->contact_email ?? null" :required="false" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold text-muted mb-2">Contact Phone</label>
                                            <x-input name="contact_phone" class="fs-6" type="text" :value="$siteSettings->contact_phone ?? null" :required="false" />
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label class="form-label fw-bold text-muted mb-2">Contact Address</label>
                                            <x-input name="contact_address" class="fs-6" type="text" :value="$siteSettings->contact_address ?? null" :required="false" />
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label class="form-label fw-bold text-muted mb-2">Footer Text</label>
                                            <x-input name="footer_text" class="fs-6" type="text" :value="$siteSettings->footer_text ?? null" :required="false" />
                                            <small class="text-danger mt-2 d-block"><b>Note:</b> You can use &copy;, {year} and {site_title} as placeholders.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TAB: Mail Settings -->
                        <div class="tab-pane fade" id="v-pills-mail" role="tabpanel" aria-labelledby="v-pills-mail-tab">
                            <div class="card shadow-sm border-0 mb-4">
                                <div class="card-header bg-white py-3 border-bottom">
                                    <h5 class="mb-0 text-primary">Mail Settings (SMTP)</h5>
                                </div>
                                <div class="card-body p-4">
                                    <div class="mb-4">
                                        <a class="text-primary text-decoration-none fw-bold" data-bs-toggle="collapse" href="#smtpHelpCollapse" role="button" aria-expanded="false" aria-controls="smtpHelpCollapse">
                                            <i class="mdi mdi-information me-1"></i> How to setup SMTP? <i class="mdi mdi-chevron-down ms-1"></i>
                                        </a>
                                        <div class="collapse mt-3" id="smtpHelpCollapse">
                                            <div class="alert alert-info border-0 shadow-sm mb-0 rounded-3" style="background-color: #f8f9fa; border-left: 4px solid var(--primary) !important;">
                                                <p class="small mb-2"><strong>1. For Live Server (Webmail):</strong> Use your cPanel/Hosting email (e.g., <code>support@yourdomain.com</code>). Host: <code>mail.yourdomain.com</code>, Port: <code>465</code>, Password: Your email password.</p>
                                                <p class="small mb-0"><strong>2. For Personal Gmail:</strong> Host: <code>smtp.gmail.com</code>, Port: <code>465</code>. <strong>Important:</strong> You must use a 16-digit <a href="https://support.google.com/accounts/answer/185833" target="_blank" class="text-primary fw-bold text-decoration-underline">Google App Password</a> instead of your regular Gmail password.</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold text-muted mb-2">Mail Host</label>
                                            <x-input name="mail_host" class="fs-6" type="text" :value="$siteSettings->mail_host ?? 'smtp.gmail.com'" :required="false" placeholder="smtp.gmail.com" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold text-muted mb-2">Mail Port</label>
                                            <x-input name="mail_port" class="fs-6" type="number" :value="$siteSettings->mail_port ?? '465'" :required="false" placeholder="465" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold text-muted mb-2">Mail Username</label>
                                            <x-input name="mail_username" class="fs-6" type="text" :value="$siteSettings->mail_username ?? null" :required="false" placeholder="your-email@gmail.com" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold text-muted mb-2">Mail Password / App Password</label>
                                            <x-input name="mail_password" class="fs-6" type="password" :value="$siteSettings->mail_password ?? null" :required="false" placeholder="Secret Password" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold text-muted mb-2">Mail Encryption</label>
                                            <select name="mail_encryption" class="form-select fs-6">
                                                <option value="ssl" {{ ($siteSettings->mail_encryption ?? 'ssl') == 'ssl' ? 'selected' : '' }}>SSL</option>
                                                <option value="tls" {{ ($siteSettings->mail_encryption ?? '') == 'tls' ? 'selected' : '' }}>TLS</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold text-muted mb-2">Mail From Address</label>
                                            <x-input name="mail_from_address" class="fs-6" type="email" :value="$siteSettings->mail_from_address ?? 'noreply@eshopper.com'" :required="false" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TAB: Social Media -->
                        <div class="tab-pane fade" id="v-pills-social" role="tabpanel" aria-labelledby="v-pills-social-tab">
                            <div class="card shadow-sm border-0 mb-4">
                                <div class="card-header bg-white py-3 border-bottom">
                                    <h5 class="mb-0 text-primary">Social Media Links</h5>
                                </div>
                                <div class="card-body p-4">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold text-muted mb-2">Facebook URL</label>
                                            <x-input name="social_facebook" class="fs-6" type="url" :value="$siteSettings->social_facebook ?? null" :required="false" placeholder="https://facebook.com/..." />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold text-muted mb-2">Twitter URL</label>
                                            <x-input name="social_twitter" class="fs-6" type="url" :value="$siteSettings->social_twitter ?? null" :required="false" placeholder="https://twitter.com/..." />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold text-muted mb-2">LinkedIn URL</label>
                                            <x-input name="social_linkedin" class="fs-6" type="url" :value="$siteSettings->social_linkedin ?? null" :required="false" placeholder="https://linkedin.com/..." />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold text-muted mb-2">Instagram URL</label>
                                            <x-input name="social_instagram" class="fs-6" type="url" :value="$siteSettings->social_instagram ?? null" :required="false" placeholder="https://instagram.com/..." />
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label class="form-label fw-bold text-muted mb-2">YouTube URL</label>
                                            <x-input name="social_youtube" class="fs-6" type="url" :value="$siteSettings->social_youtube ?? null" :required="false" placeholder="https://youtube.com/..." />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TAB: Tracking & Config -->
                        <div class="tab-pane fade" id="v-pills-tracking" role="tabpanel" aria-labelledby="v-pills-tracking-tab">
                            <div class="card shadow-sm border-0 mb-4">
                                <div class="card-header bg-white py-3 border-bottom">
                                    <h5 class="mb-0 text-primary">Tracking & Configuration</h5>
                                </div>
                                <div class="card-body p-4">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold text-muted mb-2">Facebook Pixel ID</label>
                                            <x-input name="facebook_pixel" class="fs-6" type="text" :value="$siteSettings->facebook_pixel ?? null" :required="false" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold text-muted mb-2">Google Analytics ID</label>
                                            <x-input name="google_analytics" class="fs-6" type="text" :value="$siteSettings->google_analytics ?? null" :required="false" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold text-muted mb-2">Currency Symbol</label>
                                            <x-input name="currency_symbol" class="fs-6" type="text" :value="$siteSettings->currency_symbol ?? null" :required="false" placeholder="e.g. $, ৳" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold text-muted mb-2">Currency Code</label>
                                            <x-input name="currency_code" class="fs-6" type="text" :value="$siteSettings->currency_code ?? null" :required="false" placeholder="e.g. USD, BDT" />
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label class="form-label fw-bold text-muted mb-2">Google Map (Iframe)</label>
                                            <x-input name="google_map" class="fs-6" type="text" :value="$siteSettings->google_map ?? null" :required="false" />
                                            <small class="text-danger mt-2 d-block"><b>Note:</b> Paste the full <code>&lt;iframe&gt;</code> code here.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TAB: Home Page Content -->
                        <div class="tab-pane fade" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                            <div class="card shadow-sm border-0 mb-4">
                                <div class="card-header bg-white py-3 border-bottom">
                                    <h5 class="mb-0 text-primary">Home Page Content</h5>
                                </div>
                                <div class="card-body p-4">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold text-muted mb-2">Trendy Products Title</label>
                                            <x-input name="home_trending_title" class="fs-6" type="text" :value="$siteSettings->home_trending_title ?? null" :required="false" placeholder="e.g. Trendy Products" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold text-muted mb-2">Just Arrived Title</label>
                                            <x-input name="home_latest_title" class="fs-6" type="text" :value="$siteSettings->home_latest_title ?? null" :required="false" placeholder="e.g. Just Arrived" />
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label class="form-label fw-bold text-muted mb-2">Subscribe Heading</label>
                                            <x-input name="subscribe_heading" class="fs-6" type="text" :value="$siteSettings->subscribe_heading ?? null" :required="false" placeholder="e.g. Stay Updated" />
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label class="form-label fw-bold text-muted mb-2">Subscribe Text</label>
                                            <x-input name="subscribe_text" class="fs-6" type="text" :value="$siteSettings->subscribe_text ?? null" :required="false" :rows="3" placeholder="Description for subscribe section" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Submit Button sticky at the bottom of the content area -->
                        <div class="card shadow-sm border-0 mt-2 mb-5 bg-light">
                            <div class="card-body text-end p-4">
                                <button type="submit" class="btn btn-primary px-5 py-3 rounded-pill fw-bold" style="letter-spacing: 0.5px;">
                                    <i class="mdi mdi-content-save me-1"></i> Save All Settings
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            // Synchronize color pickers and text inputs
            $('.color-sync-picker').on('input', function() {
                $(this).next('.color-sync-text').val($(this).val());
            });

            $('.color-sync-text').on('input', function() {
                var color = $(this).val();
                if (/^#[0-9A-F]{6}$/i.test(color)) {
                    $(this).prev('.color-sync-picker').val(color);
                }
            });
            
            // Keep track of active tab on reload if needed
            var activeTab = localStorage.getItem('activeTab');
            if (activeTab) {
                var tab = new bootstrap.Tab(document.querySelector('#' + activeTab));
                if(tab) tab.show();
            }
            $('button[data-bs-toggle="pill"]').on('shown.bs.tab', function (e) {
                localStorage.setItem('activeTab', e.target.id);
            });
        });
    </script>
@endpush
