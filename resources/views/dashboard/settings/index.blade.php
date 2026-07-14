@extends('dashboard.layouts.app')
@section('title', ($siteSettings->site_title ?? null) . ' - ' . 'Settings')
@section('content')
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
                <!-- ================= LEFT COLUMN ================= -->
                <div class="col-lg-6">

                    <!-- Basic Information Card -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 text-primary"><i class="mdi mdi-information-outline me-2"></i>Basic Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center mb-4 gy-3">
                                <div class="col-md-4 col-10 text-start"><label class="form-label mb-0">Site Title</label>
                                </div>
                                <div class="col-auto px-0 fs-5 fw-bold">:</div>
                                <div class="col-md-7"><x-input name="site_title" class="fs-6" type="text"
                                        :value="$siteSettings->site_title ?? null" :required="false" /></div>
                            </div>

                            <div class="row align-items-center mb-4 gy-3">
                                <div class="col-md-4 col-10 text-start"><label class="form-label mb-0">Logo</label></div>
                                <div class="col-auto px-0 fs-5 fw-bold">:</div>
                                <div class="col-md-7">
                                    <x-media-thumbnail button_label="Select Logo" input_name="site_logo" :existing_image="$logoImage" :existing_id="$logoId" :target_id="'logo'"/>
                                </div>
                            </div>
                            <div class="row align-items-center mb-4 gy-3">
                                <div class="col-md-4 col-10 text-start"><label class="form-label mb-0">Mobile Logo</label>
                                </div>
                                <div class="col-auto px-0 fs-5 fw-bold">:</div>
                                <div class="col-md-7"><x-media-thumbnail button_label="Select Mobile Logo"
                                        input_name="site_mobile_logo" :existing_image="$mobileLogoImage" :existing_id="$mobileLogoId" :target_id="'mobile_logo'"/></div>
                            </div>

                            <div class="row align-items-center mb-4 gy-3">
                                <div class="col-md-4 col-10 text-start"><label class="form-label mb-0">Favicon</label></div>
                                <div class="col-auto px-0 fs-5 fw-bold">:</div>
                                <div class="col-md-7"><x-media-thumbnail button_label="Select Favicon"
                                        input_name="site_favicon" :existing_image="$faviconImage" :existing_id="$faviconId" :target_id="'favicon'"/></div>
                            </div>

                            <div class="row align-items-center mb-4 gy-3">
                                <div class="col-md-4 col-10 text-start"><label class="form-label mb-0">Site
                                        Description</label></div>
                                <div class="col-auto px-0 fs-5 fw-bold">:</div>
                                <div class="col-md-7"><x-input name="site_description" class="fs-6" type="text"
                                        :value="$siteSettings->site_description ?? null" :required="false" :rows="3" /></div>
                            </div>

                            <div class="row align-items-center mb-4 gy-3">
                                <div class="col-md-4 col-10 text-start"><label class="form-label mb-0">Site Keywords</label>
                                </div>
                                <div class="col-auto px-0 fs-5 fw-bold">:</div>
                                <div class="col-md-7"><x-input name="site_keywords" class="fs-6" type="text"
                                        :value="$siteSettings->site_keywords ?? null" :required="false" /></div>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media Links Card -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 text-primary"><i class="mdi mdi-share-variant me-2"></i>Social Media Links</h5>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center mb-4 gy-3">
                                <div class="col-md-4 col-10 text-start"><label class="form-label mb-0">Facebook URL</label>
                                </div>
                                <div class="col-auto px-0 fs-5 fw-bold">:</div>
                                <div class="col-md-7"><x-input name="social_facebook" class="fs-6" type="url"
                                        :value="$siteSettings->social_facebook ?? null" :required="false" placeholder="https://facebook.com/..." />
                                </div>
                            </div>

                            <div class="row align-items-center mb-4 gy-3">
                                <div class="col-md-4 col-10 text-start"><label class="form-label mb-0">Twitter URL</label>
                                </div>
                                <div class="col-auto px-0 fs-5 fw-bold">:</div>
                                <div class="col-md-7"><x-input name="social_twitter" class="fs-6" type="url"
                                        :value="$siteSettings->social_twitter ?? null" :required="false" placeholder="https://twitter.com/..." /></div>
                            </div>

                            <div class="row align-items-center mb-4 gy-3">
                                <div class="col-md-4 col-10 text-start"><label class="form-label mb-0">LinkedIn URL</label>
                                </div>
                                <div class="col-auto px-0 fs-5 fw-bold">:</div>
                                <div class="col-md-7"><x-input name="social_linkedin" class="fs-6" type="url"
                                        :value="$siteSettings->social_linkedin ?? null" :required="false" placeholder="https://linkedin.com/in/..." />
                                </div>
                            </div>

                            <div class="row align-items-center mb-4 gy-3">
                                <div class="col-md-4 col-10 text-start"><label class="form-label mb-0">Instagram URL</label>
                                </div>
                                <div class="col-auto px-0 fs-5 fw-bold">:</div>
                                <div class="col-md-7"><x-input name="social_instagram" class="fs-6" type="url"
                                        :value="$siteSettings->social_instagram ?? null" :required="false" placeholder="https://instagram.com/..." />
                                </div>
                            </div>

                            <div class="row align-items-center mb-4 gy-3">
                                <div class="col-md-4 col-10 text-start"><label class="form-label mb-0">YouTube URL</label>
                                </div>
                                <div class="col-auto px-0 fs-5 fw-bold">:</div>
                                <div class="col-md-7"><x-input name="social_youtube" class="fs-6" type="url"
                                        :value="$siteSettings->social_youtube ?? null" :required="false" placeholder="https://youtube.com/..." />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Theme Colors Card -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 text-primary"><i class="mdi mdi-palette me-2"></i>Theme & Button Colors</h5>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center mb-4 gy-3">
                                <div class="col-md-4 col-10 text-start"><label class="form-label mb-0">Primary
                                        Color</label></div>
                                <div class="col-auto px-0 fs-5 fw-bold">:</div>
                                <div class="col-md-7">
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="color" class="form-control form-control-color color-sync-picker"
                                            style="max-width: 60px;"
                                            value="{{ $siteSettings->theme_color_primary ?? '#D19C97' }}"
                                            title="Choose primary color">
                                        <input type="text" name="theme_color_primary"
                                            class="form-control color-sync-text" style="max-width: 120px;"
                                            value="{{ $siteSettings->theme_color_primary ?? '#D19C97' }}"
                                            placeholder="#RRGGBB">
                                    </div>
                                </div>
                            </div>

                            <div class="row align-items-center mb-4 gy-3">
                                <div class="col-md-4 col-10 text-start"><label class="form-label mb-0">Dark Color</label>
                                </div>
                                <div class="col-auto px-0 fs-5 fw-bold">:</div>
                                <div class="col-md-7">
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="color" class="form-control form-control-color color-sync-picker"
                                            style="max-width: 60px;"
                                            value="{{ $siteSettings->theme_color_dark ?? '#1C1C1C' }}"
                                            title="Choose dark color">
                                        <input type="text" name="theme_color_dark"
                                            class="form-control color-sync-text" style="max-width: 120px;"
                                            value="{{ $siteSettings->theme_color_dark ?? '#1C1C1C' }}"
                                            placeholder="#RRGGBB">
                                    </div>
                                </div>
                            </div>

                            <div class="row align-items-center mb-4 gy-3">
                                <div class="col-md-4 col-10 text-start"><label class="form-label mb-0">Button Bg
                                        Color</label></div>
                                <div class="col-auto px-0 fs-5 fw-bold">:</div>
                                <div class="col-md-7">
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="color" class="form-control form-control-color color-sync-picker"
                                            style="max-width: 60px;"
                                            value="{{ $siteSettings->theme_button_bg ?? '#D19C97' }}"
                                            title="Choose button background color">
                                        <input type="text" name="theme_button_bg" class="form-control color-sync-text"
                                            style="max-width: 120px;"
                                            value="{{ $siteSettings->theme_button_bg ?? '#D19C97' }}"
                                            placeholder="#RRGGBB">
                                    </div>
                                </div>
                            </div>

                            <div class="row align-items-center mb-4 gy-3">
                                <div class="col-md-4 col-10 text-start"><label class="form-label mb-0">Button Text
                                        Color</label></div>
                                <div class="col-auto px-0 fs-5 fw-bold">:</div>
                                <div class="col-md-7">
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="color" class="form-control form-control-color color-sync-picker"
                                            style="max-width: 60px;"
                                            value="{{ $siteSettings->theme_button_text ?? '#212529' }}"
                                            title="Choose button text color">
                                        <input type="text" name="theme_button_text"
                                            class="form-control color-sync-text" style="max-width: 120px;"
                                            value="{{ $siteSettings->theme_button_text ?? '#212529' }}"
                                            placeholder="#RRGGBB">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ================= RIGHT COLUMN ================= -->
                <div class="col-lg-6">

                    <!-- Contact & Footer Card -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 text-primary"><i class="mdi mdi-card-account-mail-outline me-2"></i>Contact &
                                Footer</h5>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center mb-4 gy-3">
                                <div class="col-md-4 col-10 text-start"><label class="form-label mb-0">Contact
                                        Email</label></div>
                                <div class="col-auto px-0 fs-5 fw-bold">:</div>
                                <div class="col-md-7"><x-input name="contact_email" class="fs-6" type="email"
                                        :value="$siteSettings->contact_email ?? null" :required="false" /></div>
                            </div>

                            <div class="row align-items-center mb-4 gy-3">
                                <div class="col-md-4 col-10 text-start"><label class="form-label mb-0">Contact
                                        Phone</label></div>
                                <div class="col-auto px-0 fs-5 fw-bold">:</div>
                                <div class="col-md-7"><x-input name="contact_phone" class="fs-6" type="text"
                                        :value="$siteSettings->contact_phone ?? null" :required="false" /></div>
                            </div>

                            <div class="row align-items-center mb-4 gy-3">
                                <div class="col-md-4 col-10 text-start"><label class="form-label mb-0">Contact
                                        Address</label></div>
                                <div class="col-auto px-0 fs-5 fw-bold">:</div>
                                <div class="col-md-7"><x-input name="contact_address" class="fs-6" type="text"
                                        :value="$siteSettings->contact_address ?? null" :required="false" /></div>
                            </div>

                            <div class="row align-items-center gy-3">
                                <div class="col-md-4 col-10 text-start"><label class="form-label mb-0">Footer Text</label>
                                </div>
                                <div class="col-auto px-0 fs-5 fw-bold">:</div>
                                <div class="col-md-7"><x-input name="footer_text" class="fs-6" type="text"
                                        :value="$siteSettings->footer_text ?? null" :required="false" /></div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-7 offset-md-5">
                                    <span class="text-danger small pt-2 d-block"><b>Note: </b>You can use &copy; and {year}
                                        and {site_title} as placeholders</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tracking & Configuration Card -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 text-primary"><i class="mdi mdi-cog-outline me-2"></i>Tracking & Configuration
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center gy-3">
                                <div class="col-md-4 col-10 text-start"><label class="form-label mb-0">Facebook Pixel
                                        ID</label></div>
                                <div class="col-auto px-0 fs-5 fw-bold">:</div>
                                <div class="col-md-7"><x-input name="facebook_pixel" class="fs-6" type="text"
                                        :value="$siteSettings->facebook_pixel ?? null" :required="false" /></div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-7 offset-md-5"><span class="text-danger small pt-2 d-block"><b>Note:
                                        </b>Use facebook pixel ID</span></div>
                            </div>

                            <div class="row align-items-center gy-3">
                                <div class="col-md-4 col-10 text-start"><label class="form-label mb-0">Analytics
                                        ID</label></div>
                                <div class="col-auto px-0 fs-5 fw-bold">:</div>
                                <div class="col-md-7"><x-input name="google_analytics" class="fs-6" type="text"
                                        :value="$siteSettings->google_analytics ?? null" :required="false" /></div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-7 offset-md-5"><span class="text-danger small pt-2 d-block"><b>Note:
                                        </b>Use google analytics Id</span></div>
                            </div>

                            <div class="row align-items-center gy-3">
                                <div class="col-md-4 col-10 text-start"><label class="form-label mb-0">Currency
                                        Symbol</label></div>
                                <div class="col-auto px-0 fs-5 fw-bold">:</div>
                                <div class="col-md-7"><x-input name="currency_symbol" class="fs-6" type="text"
                                        :value="$siteSettings->currency_symbol ?? null" :required="false" /></div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-7 offset-md-5"><span class="text-danger small pt-2 d-block"><b>Note:
                                        </b>e.g. $, ৳</span></div>
                            </div>

                            <div class="row align-items-center mb-4 gy-3">
                                <div class="col-md-4 col-10 text-start"><label class="form-label mb-0">Currency
                                        Code</label></div>
                                <div class="col-auto px-0 fs-5 fw-bold">:</div>
                                <div class="col-md-7"><x-input name="currency_code" class="fs-6" type="text"
                                        :value="$siteSettings->currency_code ?? null" :required="false" /></div>
                            </div>

                            <div class="row align-items-center gy-3">
                                <div class="col-md-4 col-10 text-start"><label class="form-label mb-0">Google Map</label>
                                </div>
                                <div class="col-auto px-0 fs-5 fw-bold">:</div>
                                <div class="col-md-7"><x-input name="google_map" class="fs-6" type="text"
                                        :value="$siteSettings->google_map ?? null" :required="false" /></div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-7 offset-md-5">
                                    <span class="text-danger small pt-2 d-block"><b>Note:</b> Paste the full
                                        <code>&lt;iframe&gt;</code> code here.</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Home Page Content Card -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 text-primary"><i class="mdi mdi-home-outline me-2"></i>Home Page Content</h5>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center mb-4 gy-3">
                                <div class="col-md-4 col-10 text-start"><label class="form-label mb-0">Trendy Products
                                        Title</label></div>
                                <div class="col-auto px-0 fs-5 fw-bold">:</div>
                                <div class="col-md-7"><x-input name="home_trending_title" class="fs-6" type="text"
                                        :value="$siteSettings->home_trending_title ?? null" :required="false" placeholder="e.g. Trendy Products" /></div>
                            </div>

                            <div class="row align-items-center mb-4 gy-3">
                                <div class="col-md-4 col-10 text-start"><label class="form-label mb-0">Just Arrived
                                        Title</label></div>
                                <div class="col-auto px-0 fs-5 fw-bold">:</div>
                                <div class="col-md-7"><x-input name="home_latest_title" class="fs-6" type="text"
                                        :value="$siteSettings->home_latest_title ?? null" :required="false" placeholder="e.g. Just Arrived" /></div>
                            </div>

                            <div class="row align-items-center mb-4 gy-3">
                                <div class="col-md-4 col-10 text-start"><label class="form-label mb-0">Subscribe
                                        Heading</label></div>
                                <div class="col-auto px-0 fs-5 fw-bold">:</div>
                                <div class="col-md-7"><x-input name="subscribe_heading" class="fs-6" type="text"
                                        :value="$siteSettings->subscribe_heading ?? null" :required="false" placeholder="e.g. Stay Updated" /></div>
                            </div>

                            <div class="row align-items-center mb-4 gy-3">
                                <div class="col-md-4 col-10 text-start"><label class="form-label mb-0">Subscribe
                                        Text</label></div>
                                <div class="col-auto px-0 fs-5 fw-bold">:</div>
                                <div class="col-md-7"><x-input name="subscribe_text" class="fs-6" type="text"
                                        :value="$siteSettings->subscribe_text ?? null" :required="false" :rows="3"
                                        placeholder="Description for subscribe section" /></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Submit Button Card -->
            <div class="card shadow-sm border-0 mb-5">
                <div class="card-body text-end">
                    <button type="submit" class="btn btn-primary px-5 py-3">
                        <i class="mdi mdi-content-save me-1"></i> Save All Settings
                    </button>
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
                var colorValue = $(this).val();
                $(this).siblings('.color-sync-text').val(colorValue);
            });

            $('.color-sync-text').on('input', function() {
                var colorValue = $(this).val();
                $(this).siblings('.color-sync-picker').val(colorValue);
            });
        });
    </script>
@endpush
