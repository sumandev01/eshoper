<style>
    .footer-link {
        color: rgba(255, 255, 255, 0.7);
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .footer-link:hover {
        color: var(--primary) !important;
    }
</style>
<div class="mt-5 pt-lg-5 pt-1" style="background-color: #111827; color: #f9fafb;">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-12 mb-4 pe-3 pe-xl-5">
                <a href="{{ route('root') }}" class="text-decoration-none">
                    <h1 class="mb-4 display-5 font-weight-semi-bold">
                        <img src="{{ !empty($siteSettings->site_footer_logo) ? $siteSettings->site_footer_logo : $siteSettings->site_logo ?? null }}"
                            class="img-fluid" style="width: auto; max-height: 80px;" alt="Logo" loading="lazy">
                    </h1>
                </a>
                <p>{!! $siteSettings->site_description ?? null !!}</p>
                <p class="mb-2"><i
                        class="fa fa-map-marker-alt text-primary me-3"></i>{{ $siteSettings->contact_address ?? null }}
                </p>
                <p class="mb-2"><i
                        class="fa fa-envelope text-primary me-3"></i>{{ $siteSettings->contact_email ?? null }}</p>
                <p class="mb-0"><i
                        class="fa fa-phone-alt text-primary me-3"></i>{{ $siteSettings->contact_phone ?? null }}</p>

                <div class="d-flex mt-4">
                    @if (!empty($siteSettings->social_facebook) && $siteSettings->social_facebook != '#')
                        <a class="text-light mb-2" href="{{ $siteSettings->social_facebook }}" target="_blank"><i
                                class="fab fa-facebook-f text-primary me-3 fs-5"></i></a>
                    @endif
                    @if (!empty($siteSettings->social_twitter) && $siteSettings->social_twitter != '#')
                        <a class="text-light mb-2" href="{{ $siteSettings->social_twitter }}" target="_blank"><i
                                class="fab fa-twitter text-primary me-3 fs-5"></i></a>
                    @endif
                    @if (!empty($siteSettings->social_linkedin) && $siteSettings->social_linkedin != '#')
                        <a class="text-light mb-2" href="{{ $siteSettings->social_linkedin }}" target="_blank"><i
                                class="fab fa-linkedin-in text-primary me-3 fs-5"></i></a>
                    @endif
                    @if (!empty($siteSettings->social_instagram) && $siteSettings->social_instagram != '#')
                        <a class="text-light mb-2" href="{{ $siteSettings->social_instagram }}" target="_blank"><i
                                class="fab fa-instagram text-primary me-3 fs-5"></i></a>
                    @endif
                    @if (!empty($siteSettings->social_youtube) && $siteSettings->social_youtube != '#')
                        <a class="text-light mb-2" href="{{ $siteSettings->social_youtube }}" target="_blank"><i
                                class="fab fa-youtube text-primary me-3 fs-5"></i></a>
                    @endif
                </div>
            </div>
            <div class="col-lg-8 col-md-12">
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <h5 class="fw-bold text-white mb-4">Company</h5>
                        <div class="">
                            @php
                                $companyMenu = \App\Models\Menu::with([
                                    'items' => function ($q) {
                                        $q->orderBy('order');
                                    },
                                ])
                                    ->where('location', 'footer_company')
                                    ->first();
                            @endphp
                            @if ($companyMenu)
                                @foreach ($companyMenu->items as $item)
                                    @php
                                        $linkUrl = '#';
                                        if ($item->type == 'custom') {
                                            $linkUrl = $item->url;
                                        } elseif ($item->type == 'system') {
                                            $linkUrl =
                                                $item->reference_id == 'root'
                                                    ? route('root')
                                                    : route($item->reference_id);
                                        } elseif ($item->type == 'page') {
                                            $page = \App\Models\Page::find($item->reference_id);
                                            $linkUrl = $page ? route('page', $page->slug) : '#';
                                        } elseif ($item->type == 'category') {
                                            $cat = \App\Models\Category::find($item->reference_id);
                                            $linkUrl = $cat ? route('category.products', $cat->slug) : '#';
                                        }
                                    @endphp
                                    <li class="nav-item list-unstyled {{ !$loop->last ? 'mb-2' : '' }}">
                                        <a class="footer-link mb-2" href="{{ $linkUrl }}"><i
                                                class="fa fa-angle-right me-2"></i>{{ $item->title }}</a>
                                    </li>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <h5 class="fw-bold text-white mb-4">Support</h5>
                        <div class="">
                            @php
                                $supportMenu = \App\Models\Menu::with([
                                    'items' => function ($q) {
                                        $q->orderBy('order');
                                    },
                                ])
                                    ->where('location', 'footer_support')
                                    ->first();
                            @endphp
                            @if ($supportMenu)
                                @foreach ($supportMenu->items as $item)
                                    @php
                                        $linkUrl = '#';
                                        if ($item->type == 'custom') {
                                            $linkUrl = $item->url;
                                        } elseif ($item->type == 'system') {
                                            $linkUrl =
                                                $item->reference_id == 'root'
                                                    ? route('root')
                                                    : route($item->reference_id);
                                        } elseif ($item->type == 'page') {
                                            $page = \App\Models\Page::find($item->reference_id);
                                            $linkUrl = $page ? route('page', $page->slug) : '#';
                                        } elseif ($item->type == 'category') {
                                            $cat = \App\Models\Category::find($item->reference_id);
                                            $linkUrl = $cat ? route('category.products', $cat->slug) : '#';
                                        }
                                    @endphp
                                    <li class="nav-item list-unstyled {{ !$loop->last ? 'mb-2' : '' }}">
                                        <a class="footer-link mb-2" href="{{ $linkUrl }}"><i
                                                class="fa fa-angle-right me-2"></i>{{ $item->title }}</a>
                                    </li>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <h5 class="fw-bold text-white mb-4">Newsletter</h5>
                        <form action="{{ route('subscribe') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <input type="email" name="email" class="form-control border-0 py-2"
                                    placeholder="Your Email" required="required" />
                                @error('email')
                                    <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                                @enderror
                                @if (session('success'))
                                    <span class="text-success small mt-1 d-block">{{ session('success') }}</span>
                                @endif
                            </div>
                            <div>
                                <button class="btn btn-primary btn-block border-0 py-2 w-100" type="submit">Subscribe
                                    Now</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="border-top" style="border-color: rgba(255,255,255,0.1) !important;">
        <div class="container py-4">
            <div class="row">
                <div class="col-md-6 px-xl-0">
                    <p class="mb-md-0 text-center text-md-start text-light">
                        {!! str_replace(
                            ['{year}', '{site_title}', '&copy;'],
                            [date('Y'), e($siteSettings->site_title ?? null), '©'],
                            $siteSettings->footer_text ?? null,
                        ) !!}
                    </p>
                </div>
                <div class="col-md-6 px-xl-0 text-center text-md-end">
                    @if (isset($paymentMethods) && $paymentMethods->count() > 0)
                        @foreach ($paymentMethods as $paymentMethod)
                            @if ($paymentMethod->media)
                                <img class="img-fluid me-2" src="{{ Storage::url($paymentMethod->media->src) }}"
                                    alt="{{ $paymentMethod->name }}" style="max-height: 30px;" loading="lazy">
                            @endif
                        @endforeach
                    @else
                        <img class="img-fluid" src="{{ asset('web/img/payments.png') }}" alt="Payments" loading="lazy">
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Footer End -->
