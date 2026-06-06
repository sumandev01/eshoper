@extends('web.layouts.app')
@section('content')
    <section class="hero-section text-center">
        <div class="container">
            <h1 class="display-4">Contact Us</h1>
            <p class="lead text-muted">Have any questions? We would love to hear from you.</p>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-7 mb-5 mb-md-0">
                    <h3 class="font-weight-light mb-4">Send Us a Message</h3>
                    <form id="contactForm" action="{{ route('contactRequest') }}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="name">Your Name</label>
                                <input type="text" class="form-control" name="name" id="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <span class="text-danger mt-2 d-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="email">Your Email</label>
                                <input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <span class="text-danger mt-2 d-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-md-12">
                                <label for="phone" class="d-block">Your Phone</label>
                                <input type="tel" id="phone" name="phone_input" class="form-control w-100" value="{{ old('phone_input') }}" required>
                                <input type="hidden" id="full_phone" name="phone" value="{{ old('phone') }}">
                                
                                @error('phone')
                                    <span class="text-danger mt-2 d-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-md-12 mt-2">
                                <label for="subject">Subject</label>
                                <input type="text" class="form-control" name="subject" id="subject" value="{{ old('subject') }}" required>
                                @error('subject')
                                    <span class="text-danger mt-2 d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea class="form-control" name="message" id="message" rows="5" required>{{ old('message') }}</textarea>
                            @error('message')
                                <span class="text-danger mt-2 d-block">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary px-4">Submit</button>
                    </form>
                </div>

                <div class="col-md-5">
                    <h3 class="font-weight-light mb-4">Our Store</h3>

                    <div class="d-flex mb-3">
                        <div class="contact-info-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <h5>Address</h5>
                            <p class="text-muted">{{ $siteSettings->contact_address }}</p>
                        </div>
                    </div>

                    <div class="d-flex mb-3">
                        <div class="contact-info-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div>
                            <h5>Phone</h5>
                            <p class="text-muted">{{ $siteSettings->contact_phone }}</p>
                        </div>
                    </div>

                    <div class="d-flex mb-4">
                        <div class="contact-info-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>
                            <h5>Email</h5>
                            <p class="text-muted">{{ $siteSettings->contact_email }}</p>
                        </div>
                    </div>

                    <h4 class="font-weight-light mb-3">Find Us on Map</h4>
                    <div class="map-container rounded shadow-sm">
                        {!! $siteSettings->google_map !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <link class="styles-cdn" rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.10/build/css/intlTelInput.css">
    <style>
        .hero-section {
            background-color: #f8f9fa;
            padding: 80px 0;
        }

        .contact-info-icon {
            width: 40px;
            height: 40px;
            line-height: 40px;
            text-align: center;
            background-color: #007bff;
            color: white;
            border-radius: 50%;
            margin-right: 15px;
        }

        .iti {
            width: 100% !important;
            display: block !important;
        }

        .map-container {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
        }

        .map-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 0;
        }
    </style>
@endpush

@push('script')
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