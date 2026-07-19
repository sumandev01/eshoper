    <div class="minicart" id="minicart">
      <div class="minicart-title">Your Cart <span class="minicart-counter">{{ $cartProducts?->count() ?? 0 }} Items</span></div>

      @if($cartProducts && $cartProducts->count() > 0)
          <div style="max-height: 250px; overflow-y: auto;">
              @php $subtotal = 0; @endphp
              @foreach($cartProducts as $cartItem)
              @php $subtotal += ($cartItem->cart_price * $cartItem->quantity); @endphp
              <div class="cart-item">
                  <img src="{{ $cartItem->product->thumbnail ?? asset('default.webp') }}" alt="">
                  <div class="cart-item-info">
                      <p>{{ $cartItem->product->name ?? 'Product' }}</p>
                      <span class="meta">Qty: {{ $cartItem->quantity }}</span>
                  </div>
                  <span class="cart-item-price">{{ get_setting('currency_symbol') }}{{ formatBDT($cartItem->cart_price) }}</span>
              </div>
              @endforeach
          </div>
          <div class="minicart-foot">
          <div class="minicart-subtotal"><span>Subtotal</span><span>{{ get_setting('currency_symbol') }}{{ formatBDT($subtotal) }}</span></div>
          <a href="{{ route('checkout.index') }}" class="btn-checkout">Proceed to Checkout</a>
          </div>
      @else
          <div style="padding: 30px 0; text-align: center; color: var(--ink-soft);">
              Your cart is empty
          </div>
      @endif
    </div>
