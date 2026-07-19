<nav class="nav-row">
    <div class="container">
        <ul class="nav-inner mx-auto">
            <li class="has-mega">
                <button class="nav-link categories">
                    <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round">
                        <path d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    All Categories
                    <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round">
                        <path d="m6 9 6 6 6-6" />
                    </svg>
                </button>

                <!-- MEGA MENU -->
                <div class="mega">
                    <div class="mega-cats-wrapper">
                        <p class="mega-col-title" style="margin-bottom: 12px;">Categories</p>
                        <div style="display: flex; gap: 30px;">
                            @foreach ($categories->take(20)->chunk(10) as $chunk)
                                <ul class="mega-cats" style="min-width: 180px;">
                                    @foreach ($chunk as $category)
                                        <li>
                                            <a href="{{ route('category.products', $category?->slug) }}"
                                                class="mega-cat-trigger" data-target="mega-sub-{{ $category->id }}">
                                                {{ $category->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endforeach
                        </div>
                    </div>

                    <div class="mega-sub-container">
                        <p class="mega-col-title">Sub-Categories</p>
                        @foreach ($categories->take(20) as $index => $category)
                            <div class="mega-sub" id="mega-sub-{{ $category->id }}"
                                style="display: {{ $index == 0 ? 'grid' : 'none' }};">
                                @foreach ($category->subCategories as $subCat)
                                    <a
                                        href="{{ route('subcategory.products', $subCat?->slug) }}">{{ $subCat->name }}</a>
                                @endforeach
                                @if ($category->subCategories->count() == 0)
                                    <span style="color: var(--ink-soft); font-size: 13px;">No sub-categories</span>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="mega-promo">
                        <span class="live-badge"><span class="live-dot"></span> Flash Sale Live</span>
                        @if ($megaOffer1)
                            <div>
                                <h4>{{ $megaOffer1?->subtitle }}</h4>
                                <p>{{ $megaOffer1?->title }}</p>
                            </div>
                            <a href="{{ $megaOffer1?->link }}" class="offerBtn" style="color: #fff;">Shop Now <i
                                    class="fa fa-arrow-right"></i></a>
                        @else
                            <div>
                                <h4>{{ $megaOffer2?->subtitle }}</h4>
                                <p>{{ $megaOffer2?->title }}</p>
                            </div>
                            <a href="{{ $megaOffer2?->link }}" class="offerBtn" style="color: #fff;">Shop Now <i
                                    class="fa fa-arrow-right"></i></a>
                        @endif
                    </div>
                </div>
            </li>

            @php
                $headerMenu = \App\Models\Menu::with([
                    'items' => function ($q) {
                        $q->orderBy('order');
                    },
                ])
                    ->where('location', 'header_main')
                    ->first();
            @endphp
            @if ($headerMenu)
                @foreach ($headerMenu->items as $item)
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
                            $linkUrl = $cat ? route('category.products', $cat->slug) : '#';
                        }
                    @endphp
                    <li><a href="{{ $linkUrl }}" class="nav-link">{{ $item->title }}</a></li>
                @endforeach
            @endif
        </ul>
    </div>
</nav>
