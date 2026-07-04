<div class="container-fluid bg-secondary text-dark mt-5 pt-5">
    <div class="row px-xl-5 pt-5">
        <div class="col-lg-4 col-md-12 mb-5 pr-3 pr-xl-5">
            <a href="{{ route('root') }}" class="text-decoration-none">
                <h1 class="mb-4 display-5 font-weight-semi-bold">
                    <img src="{{ ($siteSettings->site_logo ?? null) }}" class="img-fluid" style="width: auto; max-height: 80px;"
                        alt="Logo" loading="lazy">
                </h1>
            </a>
            <p>{!! ($siteSettings->site_description ?? null) !!}</p>
            <p class="mb-2"><i
                    class="fa fa-map-marker-alt text-primary mr-3"></i>{{ ($siteSettings->contact_address ?? null) }}</p>
            <p class="mb-2"><i class="fa fa-envelope text-primary mr-3"></i>{{ ($siteSettings->contact_email ?? null) }}</p>
            <p class="mb-0"><i class="fa fa-phone-alt text-primary mr-3"></i>{{ ($siteSettings->contact_phone ?? null) }}</p>
            
            <div class="d-flex mt-4">
                @if(!empty($siteSettings->social_facebook) && $siteSettings->social_facebook != '#')
                    <a class="text-dark mb-2" href="{{ $siteSettings->social_facebook }}" target="_blank"><i class="fab fa-facebook-f text-primary mr-3 fs-5"></i></a>
                @endif
                @if(!empty($siteSettings->social_twitter) && $siteSettings->social_twitter != '#')
                    <a class="text-dark mb-2" href="{{ $siteSettings->social_twitter }}" target="_blank"><i class="fab fa-twitter text-primary mr-3 fs-5"></i></a>
                @endif
                @if(!empty($siteSettings->social_linkedin) && $siteSettings->social_linkedin != '#')
                    <a class="text-dark mb-2" href="{{ $siteSettings->social_linkedin }}" target="_blank"><i class="fab fa-linkedin-in text-primary mr-3 fs-5"></i></a>
                @endif
                @if(!empty($siteSettings->social_instagram) && $siteSettings->social_instagram != '#')
                    <a class="text-dark mb-2" href="{{ $siteSettings->social_instagram }}" target="_blank"><i class="fab fa-instagram text-primary mr-3 fs-5"></i></a>
                @endif
                @if(!empty($siteSettings->social_youtube) && $siteSettings->social_youtube != '#')
                    <a class="text-dark mb-2" href="{{ $siteSettings->social_youtube }}" target="_blank"><i class="fab fa-youtube text-primary mr-3 fs-5"></i></a>
                @endif
            </div>
        </div>
        <div class="col-lg-8 col-md-12">
            <div class="row">
                <div class="col-md-4 mb-5">
                    <h5 class="font-weight-bold text-dark mb-4">Company</h5>
                    <div class="d-flex flex-column justify-content-start">
                        @php
                            $companyMenu = \App\Models\Menu::with(['items' => function($q) { $q->orderBy('order'); }])->where('location', 'footer_company')->first();
                        @endphp
                        @if($companyMenu)
                            @foreach($companyMenu->items as $item)
                                @php
                                    $linkUrl = '#';
                                    if ($item->type == 'custom') {
                                        $linkUrl = $item->url;
                                    } elseif ($item->type == 'system') {
                                        $linkUrl = $item->reference_id == 'root' ? route('root') : route($item->reference_id);
                                    } elseif ($item->type == 'page') {
                                        $page = \App\Models\Page::find($item->reference_id);
                                        $linkUrl = $page ? route('page', $page->slug) : '#';
                                    } elseif ($item->type == 'category') {
                                        $cat = \App\Models\Category::find($item->reference_id);
                                        $linkUrl = $cat ? route('categoryProducts', $cat->slug) : '#';
                                    }
                                @endphp
                                <a class="text-dark mb-2" href="{{ $linkUrl }}"><i class="fa fa-angle-right mr-2"></i>{{ $item->title }}</a>
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="col-md-4 mb-5">
                    <h5 class="font-weight-bold text-dark mb-4">Support</h5>
                    <div class="d-flex flex-column justify-content-start">
                        @php
                            $supportMenu = \App\Models\Menu::with(['items' => function($q) { $q->orderBy('order'); }])->where('location', 'footer_support')->first();
                        @endphp
                        @if($supportMenu)
                            @foreach($supportMenu->items as $item)
                                @php
                                    $linkUrl = '#';
                                    if ($item->type == 'custom') {
                                        $linkUrl = $item->url;
                                    } elseif ($item->type == 'system') {
                                        $linkUrl = $item->reference_id == 'root' ? route('root') : route($item->reference_id);
                                    } elseif ($item->type == 'page') {
                                        $page = \App\Models\Page::find($item->reference_id);
                                        $linkUrl = $page ? route('page', $page->slug) : '#';
                                    } elseif ($item->type == 'category') {
                                        $cat = \App\Models\Category::find($item->reference_id);
                                        $linkUrl = $cat ? route('categoryProducts', $cat->slug) : '#';
                                    }
                                @endphp
                                <a class="text-dark mb-2" href="{{ $linkUrl }}"><i class="fa fa-angle-right mr-2"></i>{{ $item->title }}</a>
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="col-md-4 mb-5">
                    <h5 class="font-weight-bold text-dark mb-4">Newsletter</h5>
                    <form action="">
                        <div class="form-group">
                            <input type="text" class="form-control border-0 py-4" placeholder="Your Name"
                                required="required" />
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control border-0 py-4" placeholder="Your Email"
                                required="required" />
                        </div>
                        <div>
                            <button class="btn btn-primary btn-block border-0 py-3" type="submit">Subscribe
                                Now</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row border-top border-light mx-xl-5 py-4">
        <div class="col-md-6 px-xl-0">
            <p class="mb-md-0 text-center text-md-left text-dark">
                {!! str_replace(
                    ['{year}', '{site_title}', '&copy;'],
                    [date('Y'), e(($siteSettings->site_title ?? null)), '©'],
                    ($siteSettings->footer_text ?? null),
                ) !!}
            </p>
        </div>
        <div class="col-md-6 px-xl-0 text-center text-md-right">
            <img class="img-fluid" src="{{ asset('web/img/payments.png') }}" alt="" loading="lazy">
        </div>
    </div>
</div>
<!-- Footer End -->


