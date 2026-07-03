@extends('dashboard.layouts.app')
@section('title', ($siteSettings->site_title ?? null) . ' - ' . 'Settings')
@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header py-4">
                <div class="page-title-box">
                    <h3 class="mb-0">Settings</h3>
                    <p class="text-muted fw-bold mt-2 mb-0">Manage all your settings</p>
                </div>
            </div>
            <div class="card-body py-3 px-md-4 px-2">

                <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="container mt-4">
                        <div class="row align-items-center mb-4 gy-3">
                            <div class="col-sm-3 col-10 text-start">
                                <label class="form-label mb-0">Site Title</label>
                            </div>
                            <div class="col-auto px-0 fs-5 fw-bold">:</div>
                            <div class="col-md-8">
                                <x-input name="site_title" class="fs-6" type="text" :value="$siteSettings->site_title ?? null"
                                    :required="false" />
                            </div>
                        </div>

                        <div class="row align-items-center mb-4 gy-3">
                            <div class="col-md-3 col-10 text-start">
                                <label class="form-label mb-0">Logo</label>
                            </div>
                            <div class="col-auto px-0 fs-5 fw-bold">:</div>
                            <div class="col-md-8">
                                <x-media-thumbnail button_label="Select Logo" input_name="site_logo" :existing_image="$siteLogo"
                                    :target_id="'logo'" />
                            </div>
                        </div>
                        <div class="row align-items-center mb-4 gy-3">
                            <div class="col-md-3 col-10 text-start">
                                <label class="form-label mb-0">Favicon</label>
                            </div>
                            <div class="col-auto px-0 fs-5 fw-bold">:</div>
                            <div class="col-md-8">
                                <x-media-thumbnail button_label="Select Favicon" input_name="site_favicon" :existing_image="$siteFavicon"
                                    :target_id="'favicon'" />
                            </div>
                        </div>

                        <div class="row align-items-center mb-4 gy-3">
                            <div class="col-md-3 col-10 text-start">
                                <label class="form-label mb-0">Site Description</label>
                            </div>
                            <div class="col-auto px-0 fs-5 fw-bold">:</div>
                            <div class="col-md-8">
                                <x-input name="site_description" class="fs-6" type="text" :value="$siteSettings->site_description ?? null"
                                    :required="false" :rows="3" />
                            </div>
                        </div>

                        <div class="row align-items-center mb-4 gy-3">
                            <div class="col-md-3 col-10 text-start">
                                <label class="form-label mb-0">Site Keywords</label>
                            </div>
                            <div class="col-auto px-0 fs-5 fw-bold">:</div>
                            <div class="col-md-8">
                                <x-input name="site_keywords" class="fs-6" type="text" :value="$siteSettings->site_keywords ?? null"
                                    :required="false" />
                            </div>
                        </div>

                        <div class="row align-items-center mb-4 gy-3">
                            <div class="col-md-3 col-10 text-start">
                                <label class="form-label mb-0">Contact Email</label>
                            </div>
                            <div class="col-auto px-0 fs-5 fw-bold">:</div>
                            <div class="col-md-8">
                                <x-input name="contact_email" class="fs-6" type="email" :value="$siteSettings->contact_email ?? null"
                                    :required="false" />
                            </div>
                        </div>

                        <div class="row align-items-center mb-4 gy-3">
                            <div class="col-md-3 col-10 text-start">
                                <label class="form-label mb-0">Contact Phone</label>
                            </div>
                            <div class="col-auto px-0 fs-5 fw-bold">:</div>
                            <div class="col-md-8">
                                <x-input name="contact_phone" class="fs-6" type="text" :value="$siteSettings->contact_phone ?? null"
                                    :required="false" />
                            </div>
                        </div>

                        <div class="row align-items-center mb-4 gy-3">
                            <div class="col-md-3 col-10 text-start">
                                <label class="form-label mb-0">Contact Address</label>
                            </div>
                            <div class="col-auto px-0 fs-5 fw-bold">:</div>
                            <div class="col-md-8">
                                <x-input name="contact_address" class="fs-6" type="text" :value="$siteSettings->contact_address ?? null"
                                    :required="false" />
                            </div>
                        </div>


                        <div class="row align-items-center gy-3">
                            <div class="col-md-3 col-10 text-start">
                                <label class="form-label mb-0">Footer Text</label>
                            </div>
                            <div class="col-auto px-0 fs-5 fw-bold">:</div>
                            <div class="col-md-8">
                                <x-input name="footer_text" class="fs-6" type="text" :value="$siteSettings->footer_text ?? null"
                                    :required="false" />
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-8 offset-md-3">
                                <span class="text-danger pt-2 ps-md-3 ps-0 d-block"><b>Note: </b>You can use copyright-
                                    &copy; and {year} and {site_title} as placeholders</span>
                            </div>
                        </div>

                        <div class="row align-items-center gy-3">
                            <div class="col-md-3 col-10 text-start">
                                <label class="form-label mb-0">Facebook Pixel ID</label>
                            </div>
                            <div class="col-auto px-0 fs-5 fw-bold">:</div>
                            <div class="col-md-8">
                                <x-input name="facebook_pixel" class="fs-6" type="text" :value="$siteSettings->facebook_pixel ?? null"
                                    :required="false" />
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-8 offset-md-3">
                                <span class="text-danger pt-2 ps-md-3 ps-0 d-block"><b>Note: </b>You can use facebook pixel
                                    Id as placeholders</span>
                            </div>
                        </div>

                        <div class="row align-items-center gy-3">
                            <div class="col-md-3 col-10 text-start">
                                <label class="form-label mb-0">Google Analytics ID</label>
                            </div>
                            <div class="col-auto px-0 fs-5 fw-bold">:</div>
                            <div class="col-md-8">
                                <x-input name="google_analytics" class="fs-6" type="text" :value="$siteSettings->google_analytics ?? null"
                                    :required="false" />
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-8 offset-md-3">
                                <span class="text-danger pt-2 ps-md-3 ps-0 d-block"><b>Note: </b>You can use google
                                    analytics Id as placeholders</span>
                            </div>
                        </div>

                        <div class="row align-items-center gy-3">
                            <div class="col-md-3 col-10 text-start">
                                <label class="form-label mb-0">Currency Symbol</label>
                            </div>
                            <div class="col-auto px-0 fs-5 fw-bold">:</div>
                            <div class="col-md-8">
                                <x-input name="currency_symbol" class="fs-6" type="text" :value="$siteSettings->currency_symbol ?? null"
                                    :required="false" />
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-8 offset-md-3">
                                <span class="text-danger pt-2 ps-md-3 ps-0 d-block"><b>Note: </b>You can use currency
                                    symbol as placeholders</span>
                            </div>
                        </div>

                        <div class="row align-items-center mb-4 gy-3">
                            <div class="col-md-3 col-10 text-start">
                                <label class="form-label mb-0">Currency Code</label>
                            </div>
                            <div class="col-auto px-0 fs-5 fw-bold">:</div>
                            <div class="col-md-8">
                                <x-input name="currency_code" class="fs-6" type="text" :value="$siteSettings->currency_code ?? null"
                                    :required="false" />
                            </div>
                        </div>

                        <div class="row align-items-center gy-3">
                            <div class="col-md-3 col-10 text-start">
                                <label class="form-label mb-0">Google Map</label>
                            </div>
                            <div class="col-auto px-0 fs-5 fw-bold">:</div>
                            <div class="col-md-8">
                                <x-input name="google_map" class="fs-6" type="text" :value="$siteSettings->google_map ?? null"
                                    :required="false" />
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-8 offset-md-3">
                                <span class="text-danger pt-2 ps-md-3 ps-0 d-block">
                                    <b>Note:</b> Go to Google Maps, share your location, select "Embed a map", and paste the
                                    full <code>&lt;iframe&gt;</code> code here.
                                </span>
                            </div>
                        </div>
                        
                        <!-- Social Media Links -->
                        <h4 class="mt-5 mb-3 border-bottom pb-2">Social Media Links</h4>
                        
                        <div class="row align-items-center mb-4 gy-3">
                            <div class="col-md-3 col-10 text-start">
                                <label class="form-label mb-0">Facebook URL</label>
                            </div>
                            <div class="col-auto px-0 fs-5 fw-bold">:</div>
                            <div class="col-md-8">
                                <x-input name="social_facebook" class="fs-6" type="url" :value="$siteSettings->social_facebook ?? null" :required="false" placeholder="https://facebook.com/..." />
                            </div>
                        </div>

                        <div class="row align-items-center mb-4 gy-3">
                            <div class="col-md-3 col-10 text-start">
                                <label class="form-label mb-0">Twitter URL</label>
                            </div>
                            <div class="col-auto px-0 fs-5 fw-bold">:</div>
                            <div class="col-md-8">
                                <x-input name="social_twitter" class="fs-6" type="url" :value="$siteSettings->social_twitter ?? null" :required="false" placeholder="https://twitter.com/..." />
                            </div>
                        </div>

                        <div class="row align-items-center mb-4 gy-3">
                            <div class="col-md-3 col-10 text-start">
                                <label class="form-label mb-0">LinkedIn URL</label>
                            </div>
                            <div class="col-auto px-0 fs-5 fw-bold">:</div>
                            <div class="col-md-8">
                                <x-input name="social_linkedin" class="fs-6" type="url" :value="$siteSettings->social_linkedin ?? null" :required="false" placeholder="https://linkedin.com/in/..." />
                            </div>
                        </div>

                        <div class="row align-items-center mb-4 gy-3">
                            <div class="col-md-3 col-10 text-start">
                                <label class="form-label mb-0">Instagram URL</label>
                            </div>
                            <div class="col-auto px-0 fs-5 fw-bold">:</div>
                            <div class="col-md-8">
                                <x-input name="social_instagram" class="fs-6" type="url" :value="$siteSettings->social_instagram ?? null" :required="false" placeholder="https://instagram.com/..." />
                            </div>
                        </div>

                        <div class="row align-items-center mb-4 gy-3">
                            <div class="col-md-3 col-10 text-start">
                                <label class="form-label mb-0">YouTube URL</label>
                            </div>
                            <div class="col-auto px-0 fs-5 fw-bold">:</div>
                            <div class="col-md-8">
                                <x-input name="social_youtube" class="fs-6" type="url" :value="$siteSettings->social_youtube ?? null" :required="false" placeholder="https://youtube.com/..." />
                            </div>
                        </div>
                        
                        <!-- Theme Colors -->
                        <h4 class="mt-5 mb-3 border-bottom pb-2">Theme Colors</h4>
                        
                        <div class="row align-items-center mb-4 gy-3">
                            <div class="col-md-3 col-10 text-start">
                                <label class="form-label mb-0">Primary Color</label>
                            </div>
                            <div class="col-auto px-0 fs-5 fw-bold">:</div>
                            <div class="col-md-8">
                                <input type="color" name="theme_color_primary" class="form-control form-control-color w-100" style="max-width: 150px;" value="{{ $siteSettings->theme_color_primary ?? '#D19C97' }}" title="Choose primary color">
                            </div>
                        </div>

                        <div class="row align-items-center mb-4 gy-3">
                            <div class="col-md-3 col-10 text-start">
                                <label class="form-label mb-0">Primary Hover Color</label>
                            </div>
                            <div class="col-auto px-0 fs-5 fw-bold">:</div>
                            <div class="col-md-8">
                                <input type="color" name="theme_color_primary_hover" class="form-control form-control-color w-100" style="max-width: 150px;" value="{{ $siteSettings->theme_color_primary_hover ?? '#c17a74' }}" title="Choose primary hover color">
                            </div>
                        </div>

                        <div class="row align-items-center mb-4 gy-3">
                            <div class="col-md-3 col-10 text-start">
                                <label class="form-label mb-0">Secondary Color</label>
                            </div>
                            <div class="col-auto px-0 fs-5 fw-bold">:</div>
                            <div class="col-md-8">
                                <input type="color" name="theme_color_secondary" class="form-control form-control-color w-100" style="max-width: 150px;" value="{{ $siteSettings->theme_color_secondary ?? '#EDF1FF' }}" title="Choose secondary color">
                            </div>
                        </div>

                        <div class="row align-items-center mb-4 gy-3">
                            <div class="col-md-3 col-10 text-start">
                                <label class="form-label mb-0">Dark Color</label>
                            </div>
                            <div class="col-auto px-0 fs-5 fw-bold">:</div>
                            <div class="col-md-8">
                                <input type="color" name="theme_color_dark" class="form-control form-control-color w-100" style="max-width: 150px;" value="{{ $siteSettings->theme_color_dark ?? '#1C1C1C' }}" title="Choose dark color">
                            </div>
                        </div>

                        <!-- Button Colors -->
                        <h4 class="mt-5 mb-3 border-bottom pb-2">Button Colors</h4>
                        
                        <div class="row align-items-center mb-4 gy-3">
                            <div class="col-md-3 col-10 text-start">
                                <label class="form-label mb-0">Button Background Color</label>
                            </div>
                            <div class="col-auto px-0 fs-5 fw-bold">:</div>
                            <div class="col-md-8">
                                <input type="color" name="theme_button_bg" class="form-control form-control-color w-100" style="max-width: 150px;" value="{{ $siteSettings->theme_button_bg ?? '#D19C97' }}" title="Choose button background color">
                            </div>
                        </div>

                        <div class="row align-items-center mb-4 gy-3">
                            <div class="col-md-3 col-10 text-start">
                                <label class="form-label mb-0">Button Text Color</label>
                            </div>
                            <div class="col-auto px-0 fs-5 fw-bold">:</div>
                            <div class="col-md-8">
                                <input type="color" name="theme_button_text" class="form-control form-control-color w-100" style="max-width: 150px;" value="{{ $siteSettings->theme_button_text ?? '#212529' }}" title="Choose button text color">
                            </div>
                        </div>

                        <div class="row align-items-center mb-4 gy-3">
                            <div class="col-md-3 col-10 text-start">
                                <label class="form-label mb-0">Button Hover Background Color</label>
                            </div>
                            <div class="col-auto px-0 fs-5 fw-bold">:</div>
                            <div class="col-md-8">
                                <input type="color" name="theme_button_hover_bg" class="form-control form-control-color w-100" style="max-width: 150px;" value="{{ $siteSettings->theme_button_hover_bg ?? '#c17a74' }}" title="Choose button hover background color">
                            </div>
                        </div>

                        <div class="row align-items-center mb-4 gy-3">
                            <div class="col-md-3 col-10 text-start">
                                <label class="form-label mb-0">Button Hover Text Color</label>
                            </div>
                            <div class="col-auto px-0 fs-5 fw-bold">:</div>
                            <div class="col-md-8">
                                <input type="color" name="theme_button_hover_text" class="form-control form-control-color w-100" style="max-width: 150px;" value="{{ $siteSettings->theme_button_hover_text ?? '#ffffff' }}" title="Choose button hover text color">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary px-4 py-3 mb-2 mt-3">
                            <i class="mdi mdi-content-save me-1"></i> Save Settings
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
@push('styles')
    <style>
        .form-group {
            margin-bottom: 0 !important;
            width: 100%;
            margin-left: 10px;
        }

        [id^="media-preview-"] {
            padding: 0 !important;
            margin-bottom: 10px !important;
        }

        .preview-image-wrapper {
            padding-left: 0 !important;
            margin-bottom: 0 !important;
        }

        .imagePreviewSingle {
            margin-bottom: 0 !important;
        }

        @media (max-width: 767px) {
            .form-group {
                margin-left: 0 !important;
            }
        }
    </style>
@endpush
