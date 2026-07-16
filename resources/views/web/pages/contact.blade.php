@extends('web.layouts.app')
@section('content')
    @include('web.components.breadcrumb', [
        'breadcrumbs' => [
            ['name' => 'Contact Us', 'url' => '']
        ]
    ])
    <div class="container pt-1">
        <div class="row">
            <div class="col-12 text-center">
                <h2 class="font-weight-light">Contact Us</h2>
                <p class="lead text-muted">Have any questions? We would love to hear from you.</p>
            </div>
        </div>
    </div>

    <div class="py-5">
        <div class="container">
            <!-- Top Row: Contact Info Cards -->
            <div class="row mb-5">
                <div class="col-md-4 mb-4 mb-md-0">
                    <div class="theme-shadow transition-all hover-up p-4 text-center h-100" style="border-radius: 12px; background-color: color-mix(in srgb, var(--primary) 4%, white);">
                        <div class="d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px; border-radius: 50%; background-color: color-mix(in srgb, var(--primary) 12%, white);">
                            <i class="fas fa-map-marker-alt fa-2x text-primary"></i>
                        </div>
                        <h5 class="font-weight-semi-bold" style="color: color-mix(in srgb, var(--primary) 60%, #111);">Address</h5>
                        <p class="text-muted mb-0">{{ ($siteSettings->contact_address ?? null) }}</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4 mb-md-0">
                    <div class="theme-shadow transition-all hover-up p-4 text-center h-100" style="border-radius: 12px; background-color: color-mix(in srgb, var(--primary) 4%, white);">
                        <div class="d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px; border-radius: 50%; background-color: color-mix(in srgb, var(--primary) 12%, white);">
                            <i class="fas fa-phone-alt fa-2x text-primary"></i>
                        </div>
                        <h5 class="font-weight-semi-bold" style="color: color-mix(in srgb, var(--primary) 60%, #111);">Phone</h5>
                        <p class="text-muted mb-0">{{ ($siteSettings->contact_phone ?? null) }}</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="theme-shadow transition-all hover-up p-4 text-center h-100" style="border-radius: 12px; background-color: color-mix(in srgb, var(--primary) 4%, white);">
                        <div class="d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px; border-radius: 50%; background-color: color-mix(in srgb, var(--primary) 12%, white);">
                            <i class="fas fa-envelope fa-2x text-primary"></i>
                        </div>
                        <h5 class="font-weight-semi-bold" style="color: color-mix(in srgb, var(--primary) 60%, #111);">Email</h5>
                        <p class="text-muted mb-0">{{ ($siteSettings->contact_email ?? null) }}</p>
                    </div>
                </div>
            </div>

            <!-- Bottom Row: Form and Map -->
            <div class="row align-items-stretch">
                <!-- Left: Form -->
                <div class="col-md-6 mb-5 mb-md-0">
                    <div class="theme-shadow p-5 h-100" style="border-radius: 12px; background-color: color-mix(in srgb, var(--primary) 4%, white);">
                        <h3 class="font-weight-semi-bold mb-4" style="color: color-mix(in srgb, var(--primary) 60%, #111);">Send Us a Message</h3>
                        <form id="contactForm" action="{{ route('contact.submit') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="material-floating">
                                        <input type="text" class="form-control modern-input" name="name" id="name" placeholder=" " value="{{ old('name') }}" required>
                                        <label for="name">Your Name</label>
                                    </div>
                                    @error('name')
                                        <span class="text-danger mt-2 d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="material-floating">
                                        <input type="email" class="form-control modern-input" name="email" id="email" placeholder=" " value="{{ old('email') }}" required>
                                        <label for="email">Your Email</label>
                                    </div>
                                    @error('email')
                                        <span class="text-danger mt-2 d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="material-floating">
                                        <input type="tel" id="phone" name="phone_input" class="form-control modern-input w-100" value="{{ old('phone_input') }}" required>
                                        <label for="phone" class="always-float">Your Phone</label>
                                        <input type="hidden" id="full_phone" name="phone" value="{{ old('phone') }}">
                                    </div>
                                    @error('phone')
                                        <span class="text-danger mt-2 d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="material-floating">
                                        <input type="text" class="form-control modern-input" name="subject" id="subject" placeholder=" " value="{{ old('subject') }}" required>
                                        <label for="subject">Subject</label>
                                    </div>
                                    @error('subject')
                                        <span class="text-danger mt-2 d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="material-floating">
                                    <textarea class="form-control modern-input pt-3" name="message" id="message" placeholder=" " style="height: 150px" required>{{ old('message') }}</textarea>
                                    <label for="message">Message</label>
                                </div>
                                @error('message')
                                    <span class="text-danger mt-2 d-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary theme-shadow transition-all hover-up px-5 py-3" style="border-radius: 12px; font-weight: 600;">Submit Message</button>
                        </form>
                    </div>
                </div>

                <!-- Right: Map -->
                <div class="col-md-6">
                    <div class="theme-shadow overflow-hidden map-wrapper h-100" style="border-radius: 12px; background-color: color-mix(in srgb, var(--primary) 4%, white);">
                        {!! ($siteSettings->google_map ?? null) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link class="styles-cdn" rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.10/build/css/intlTelInput.css">
    <style>
        .map-wrapper {
            position: relative;
            height: 100%;
            min-height: 400px;
        }

        .map-wrapper iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 0;
        }

        .iti {
            width: 100% !important;
            display: block !important;
        }

        .modern-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: color-mix(in srgb, var(--primary) 60%, #111);
            margin-bottom: 8px;
        }

        .modern-input {
            border-radius: 12px !important;
            border: 1px solid rgba(0,0,0,0.1);
            background-color: #f8f9fa;
            transition: all 0.3s ease;
        }
        
        .iti input.modern-input {
            padding-top: 12px;
            padding-bottom: 12px;
            padding-left: 95px !important; /* Ensure flag room */
        }

        .modern-input:focus {
            background-color: white;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px color-mix(in srgb, var(--primary) 20%, transparent) !important;
            outline: none;
        }

        /* Material Floating Labels */
        .material-floating {
            position: relative;
            background: transparent;
        }
        .material-floating input.modern-input {
            height: 50px;
            padding-left: 15px;
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
        .material-floating input:not(:placeholder-shown) + label,
        .material-floating textarea:focus + label,
        .material-floating textarea:not(:placeholder-shown) + label,
        .material-floating label.always-float {
            top: 0;
            font-size: 0.8rem;
            color: var(--primary);
            background: white;
        }
        .material-floating input:focus, .material-floating textarea:focus {
            background-color: white;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.10/build/js/intlTelInput.min.js"></script>
    <script>
        const input = document.querySelector("#phone");
        const hiddenInput = document.querySelector("#full_phone");
        const form = document.querySelector("#contactForm");
        const phoneError = document.querySelector("#phone-error");

        const iti = window.intlTelInput(input, {
            initialCountry: "bd",
            separateDialCode: true,
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.10/build/js/utils.js",
        });

        // ইউজার যখনই ইনপুটে টাইপ করবে বা চেঞ্জ করবে, এরর মেসেজ সাথে সাথে গায়েব হয়ে যাবে
        input.addEventListener('input', function() {
            phoneError.classList.add('d-none');
            input.classList.remove('is-invalid');
        });

        form.addEventListener('submit', function(event) {
            if (iti.isValidNumber()) {
                hiddenInput.value = iti.getNumber();
            } else {
                event.preventDefault();
                phoneError.classList.remove('d-none');
                input.classList.add('is-invalid');
            }
        });
    </script>
@endpush




