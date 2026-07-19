<a href="{{ route('root') }}" class="logo brandmark">
  @if (!empty($siteSettings->site_logo))
      <img src="{{ $siteSettings->site_logo }}" style="max-height: 65px; max-width: 220px; object-fit: contain;" alt="Logo">
  @else
      martX<span>.</span>
  @endif
</a>

<div class="search-form-wrapper">
    <form action="{{ route('shop') }}" method="GET" class="search-form" role="search">
      <label for="header-search" class="visually-hidden">Search Products</label>
      <input id="header-search" name="search" type="text" placeholder="Search for products, brands or categories..." autocomplete="off">
      <button type="submit" class="search-submit" aria-label="Search">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
      </button>
    </form>
    <div id="search-suggestions"></div>
</div>
