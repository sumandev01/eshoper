<script>
(function(){
  // ---------- Mega Menu Hover Interaction ----------
  let megaHoverTimeout;
  document.querySelectorAll('.mega-cat-trigger').forEach(trigger => {
      trigger.addEventListener('mouseenter', function(e) {
          clearTimeout(megaHoverTimeout);
          megaHoverTimeout = setTimeout(() => {
              document.querySelectorAll('.mega-cat-trigger').forEach(t => t.classList.remove('is-active'));
              this.classList.add('is-active');
              document.querySelectorAll('.mega-sub').forEach(sub => sub.style.display = 'none');
              const targetId = this.getAttribute('data-target');
              const targetSub = document.getElementById(targetId);
              if (targetSub) targetSub.style.display = 'grid';
          }, 400);
      });
      trigger.addEventListener('mouseleave', function(e) {
          clearTimeout(megaHoverTimeout);
      });
  });
  
  const firstTrigger = document.querySelector('.mega-cat-trigger');
  if(firstTrigger) firstTrigger.classList.add('is-active');

  // ---------- Sticky header ----------
  const header = document.getElementById('siteHeader');
  let lastY = window.scrollY;
  
  // Create a placeholder to prevent content jump when header becomes fixed
  const placeholder = document.createElement('div');
  placeholder.style.display = 'none';
  header.parentNode.insertBefore(placeholder, header);

  function onScroll(){
    const y = window.scrollY;
    if(y > 120){
      if (!header.classList.contains('is-stuck')) {
          placeholder.style.height = header.offsetHeight + 'px';
          placeholder.style.display = 'block';
          header.classList.add('is-stuck');
      }
    } else {
      if (header.classList.contains('is-stuck')) {
          header.classList.remove('is-stuck');
          placeholder.style.display = 'none';
      }
    }
    lastY = y;
  }
  window.addEventListener('scroll', onScroll, {passive:true});

  // ---------- Mobile search overlay ----------
  const openSearch = document.getElementById('openSearch');
  const closeSearch = document.getElementById('closeSearch');
  const searchOverlay = document.getElementById('searchOverlay');
  if(openSearch) {
      openSearch.addEventListener('click', () => {
        searchOverlay.classList.add('is-open');
        document.getElementById('searchMobile').focus({preventScroll:true});
      });
  }
  if(closeSearch) closeSearch.addEventListener('click', () => searchOverlay.classList.remove('is-open'));

  // ---------- Offcanvas ----------
  const openOffcanvas = document.getElementById('openOffcanvas');
  const closeOffcanvas = document.getElementById('closeOffcanvas');
  const offcanvas = document.getElementById('offcanvas');
  const scrim = document.getElementById('scrim');

  function openOC(){
    if(!offcanvas) return;
    offcanvas.classList.add('is-open');
    scrim.classList.add('is-open');
    offcanvas.setAttribute('aria-hidden','false');
    document.body.style.overflow='hidden';
  }
  function closeOC(){
    if(!offcanvas) return;
    offcanvas.classList.remove('is-open');
    scrim.classList.remove('is-open');
    offcanvas.setAttribute('aria-hidden','true');
    document.body.style.overflow='';
  }
  if(openOffcanvas) openOffcanvas.addEventListener('click', openOC);
  if(closeOffcanvas) closeOffcanvas.addEventListener('click', closeOC);
  if(scrim) scrim.addEventListener('click', closeOC);

  // ---------- Accordion ----------
  document.querySelectorAll('.accordion-trigger').forEach(function(btn){
    btn.addEventListener('click', function(){
      const panel = btn.nextElementSibling;
      const isOpen = btn.getAttribute('aria-expanded') === 'true';

      document.querySelectorAll('.accordion-trigger').forEach(function(other){
        if(other !== btn){
          other.setAttribute('aria-expanded','false');
          other.nextElementSibling.style.maxHeight = null;
        }
      });

      if(isOpen){
        btn.setAttribute('aria-expanded','false');
        panel.style.maxHeight = null;
      } else {
        btn.setAttribute('aria-expanded','true');
        panel.style.maxHeight = panel.scrollHeight + 'px';
      }
    });
  });

  document.addEventListener('keydown', function(e){
    if(e.key === 'Escape'){
      closeOC();
      if(searchOverlay) searchOverlay.classList.remove('is-open');
    }
  });

  // ---------- Search Suggestions (AJAX) ----------
  const siteCurrency = "{{ get_setting('currency_symbol', '$') }}";
  let searchTimeout;

  const searchInputs = document.querySelectorAll('#header-search, #searchMobile');
  const suggestionsBox = document.getElementById('search-suggestions');

  searchInputs.forEach(input => {
      input.addEventListener('keyup', function() {
          let search = this.value.trim();
          clearTimeout(searchTimeout);

          if (search.length < 2) {
              if (suggestionsBox) suggestionsBox.style.display = 'none';
              return;
          }

          if (suggestionsBox) {
              suggestionsBox.innerHTML = '<div style="padding: 15px; text-align: center; color: #666;"><i class="fa fa-spinner fa-spin" style="margin-right: 5px;"></i> Searching...</div>';
              suggestionsBox.style.display = 'block';
          }

          searchTimeout = setTimeout(function() {
              fetch("{{ route('search.suggestions') }}?search=" + encodeURIComponent(search), {
                  headers: { 'X-Requested-With': 'XMLHttpRequest' }
              })
              .then(res => res.json())
              .then(products => {
                  if (!suggestionsBox) return;
                  
                  if (products.length === 0) {
                      suggestionsBox.innerHTML = '<div style="padding: 15px; text-align: center; color: #666;">No products found for "'+search+'"</div>';
                      return;
                  }

                  let html = '';
                  products.forEach(function(product) {
                      html += `
                          <a href="/product/${product.slug}" style="
                              display: flex; align-items: center; gap: 12px; padding: 10px 14px; text-decoration: none;
                              color: #333; border-bottom: 1px solid #f0f0f0; transition: background 0.2s;
                          " onmouseover="this.style.background='#f8f8f8'" onmouseout="this.style.background='white'">
                              <div style="width: 40px; height: 40px; flex-shrink: 0; border-radius: 6px; overflow: hidden; border: 1px solid #eee;">
                                  <img src="${product.thumbnail}" style="width: 100%; height: 100%; object-fit: cover;" alt="${product.name}">
                              </div>
                              <div style="flex: 1; min-width: 0;">
                                  <div style="font-size: 14px; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${product.name}</div>
                                  <div style="margin-top: 3px;">
                                      ${product.discount > 0 ? `
                                          <span style="color: #000; font-weight: 600; font-size: 13px;">${siteCurrency}${product.discount}</span>
                                          <span style="color: #e74c3c; font-size: 12px; text-decoration: line-through; margin-left: 5px;">${siteCurrency}${product.price}</span>
                                      ` : `
                                          <span style="color: #000; font-weight: 600; font-size: 13px;">${siteCurrency}${product.price}</span>
                                      `}
                                  </div>
                              </div>
                          </a>
                      `;
                  });

                  html += `
                      <a href="{{ route('shop') }}?search=${encodeURIComponent(search)}" style="
                          display: block; padding: 10px 14px; text-align: center; color: #fff; font-size: 13px; font-weight: 600;
                          text-decoration: none; background: var(--brand);
                      " onmouseover="this.style.background='var(--brand-deep)'" onmouseout="this.style.background='var(--brand)'">
                          View all results for "${search}" <i class="fa fa-arrow-right" style="margin-left: 6px; font-size: 12px;"></i>
                      </a>
                  `;

                  suggestionsBox.innerHTML = html;
              })
              .catch(err => {
                  if (suggestionsBox) suggestionsBox.style.display = 'none';
              });
          }, 300);
      });
  });

  document.addEventListener('click', function(e) {
      if (!e.target.closest('.search-form-wrapper') && suggestionsBox) {
          suggestionsBox.style.display = 'none';
      }
  });
  
  // Global Cart Sync for Mobile
  const cartCountEl = document.getElementById('cartCount');
  if (cartCountEl) {
      const observer = new MutationObserver(function(mutations) {
          const newCount = cartCountEl.innerText;
          document.querySelectorAll('.global-cart-count').forEach(el => {
              if(el !== cartCountEl) el.innerText = newCount;
          });
          const miniTitle = document.querySelector('.minicart-counter');
          if(miniTitle) miniTitle.innerText = newCount + " Items";
      });
      observer.observe(cartCountEl, { childList: true, characterData: true, subtree: true });
  }

})();
</script>
