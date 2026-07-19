<!-- TOP UTILITY BAR -->
<div class="topbar" id="topbar">
  <div class="container topbar-inner d-flex align-items-center justify-content-between">
    <nav class="topbar-links">
      @php
          $headerTopMenu = \App\Models\Menu::with(['items' => function ($q) { $q->orderBy('order'); }])->where('location', 'header_top')->first();
      @endphp
      @if ($headerTopMenu)
          @foreach ($headerTopMenu->items as $index => $item)
              @php
                  $linkUrl = '#';
                  if ($item->type == 'custom') { $linkUrl = $item->url; } 
                  elseif ($item->type == 'system') { $linkUrl = $item->reference_id == 'root' ? route('root') : route($item->reference_id); } 
                  elseif ($item->type == 'page') { $page = \App\Models\Page::find($item->reference_id); $linkUrl = $page ? route('page', $page->slug) : '#'; } 
                  elseif ($item->type == 'category') { $cat = \App\Models\Category::find($item->reference_id); $linkUrl = $cat ? route('category.products', $cat->slug) : '#'; }
              @endphp
              <a href="{{ $linkUrl }}">{{ $item->title }}</a>
          @endforeach
      @endif
    </nav>
    <div class="topbar-right">
      <div class="social-icons">
          @if (!empty($siteSettings->social_facebook) && $siteSettings->social_facebook != '#')
              <a href="{{ $siteSettings->social_facebook }}" target="_blank" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
          @endif
          @if (!empty($siteSettings->social_twitter) && $siteSettings->social_twitter != '#')
              <a href="{{ $siteSettings->social_twitter }}" target="_blank" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
          @endif
          @if (!empty($siteSettings->social_linkedin) && $siteSettings->social_linkedin != '#')
              <a href="{{ $siteSettings->social_linkedin }}" target="_blank" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
          @endif
          @if (!empty($siteSettings->social_instagram) && $siteSettings->social_instagram != '#')
              <a href="{{ $siteSettings->social_instagram }}" target="_blank" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
          @endif
          @if (!empty($siteSettings->social_youtube) && $siteSettings->social_youtube != '#')
              <a href="{{ $siteSettings->social_youtube }}" target="_blank" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
          @endif
      </div>
    </div>
  </div>
</div>
