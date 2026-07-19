<div class="header-actions">
  @if($user)
  <a href="{{ route('user.dashboard') }}" class="icon-btn text-center">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21c0-3.9-3.6-7-8-7s-8 3.1-8 7"/><circle cx="12" cy="7" r="4"/></svg>
    {{ Str::limit($user->name, 10, '...') }}
  </a>
  @else
  <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#loginModal" class="icon-btn">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21c0-3.9-3.6-7-8-7s-8 3.1-8 7"/><circle cx="12" cy="7" r="4"/></svg>
    Account
  </a>
  @endif
  
  <a href="{{ route('wishlist') }}" class="icon-btn">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m20.8 8.6-8.1 8.6a1 1 0 0 1-1.4 0l-8.1-8.6a4.6 4.6 0 0 1 6.7-6.3l1.1 1.1 1.1-1.1a4.6 4.6 0 0 1 6.7 6.3Z"/></svg>
    Wishlist
    <span class="cart-count global-wishlist-count">{{ $wishlistProducts?->count() ?? count((array) session('wishlist')) }}</span>
  </a>
  
  <div class="cart-wrap">
    <a href="{{ route('cart') }}" class="icon-btn" id="cartTrigger">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1.4"/><circle cx="18" cy="21" r="1.4"/><path d="M2.5 3h2l2.7 12.4a2 2 0 0 0 2 1.6h7.7a2 2 0 0 0 2-1.6L21 7.5H6"/></svg>
      Cart
      <span class="cart-count global-cart-count">{{ $cartProducts?->count() ?? count((array) session('cart')) }}</span>
    </a>

    @include('web.layouts.headers.header_1_partials.minicart_dropdown')
  </div>
</div>
