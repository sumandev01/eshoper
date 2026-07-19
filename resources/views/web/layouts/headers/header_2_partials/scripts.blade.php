<script>
    document.addEventListener('DOMContentLoaded', function() {
        const header = document.getElementById('siteHeader');
        
        // ---------- Sticky Header ----------
        window.addEventListener('scroll', function() {
            if (window.scrollY > 120) {
                header.classList.add('is-stuck');
            } else {
                header.classList.remove('is-stuck');
            }
        });

        // ---------- Mega Menu Hover Intent ----------
        let megaHoverTimeout;
        const triggers = document.querySelectorAll('.martX-header-2 .mega-cat-trigger');
        const subs = document.querySelectorAll('.martX-header-2 .mega-sub');
        
        triggers.forEach(trigger => {
            trigger.addEventListener('mouseenter', function() {
                clearTimeout(megaHoverTimeout);
                
                megaHoverTimeout = setTimeout(() => {
                    // Remove active from all
                    triggers.forEach(t => t.classList.remove('active'));
                    subs.forEach(s => s.style.display = 'none');
                    
                    // Add active to current
                    this.classList.add('active');
                    const targetId = this.getAttribute('data-target');
                    const targetSub = document.getElementById(targetId);
                    if (targetSub) {
                        targetSub.style.display = 'grid';
                    }
                }, 150); // 150ms delay for hover intent
            });
        });

        // ---------- Custom Category Dropdown JS ----------
        const catWrappers = document.querySelectorAll('.custom-category-wrapper');
        
        catWrappers.forEach(wrapper => {
            const catBtn = wrapper.querySelector('.custom-cat-btn');
            const catDropdown = wrapper.querySelector('.custom-cat-dropdown');
            const catOptions = wrapper.querySelectorAll('.custom-cat-option');
            const searchCategoryInput = wrapper.querySelector('input[name="category_id"]');
            const searchCategoryLabel = wrapper.querySelector('span');
            const chev = wrapper.querySelector('.cat-chev');

            if (catBtn && catDropdown) {
                catBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Close others
                    document.querySelectorAll('.custom-cat-dropdown').forEach(d => {
                        if (d !== catDropdown) {
                            d.style.display = 'none';
                            const otherChev = d.parentElement.querySelector('.cat-chev');
                            if(otherChev) otherChev.style.transform = 'rotate(0)';
                        }
                    });

                    const isOpen = catDropdown.style.display === 'block';
                    catDropdown.style.display = isOpen ? 'none' : 'block';
                    if(chev) chev.style.transform = isOpen ? 'rotate(0)' : 'rotate(180deg)';
                });

                catOptions.forEach(option => {
                    option.addEventListener('click', function(e) {
                        e.stopPropagation();
                        catOptions.forEach(opt => opt.classList.remove('is-active'));
                        this.classList.add('is-active');
                        
                        const value = this.getAttribute('data-value');
                        const text = this.innerText;
                        
                        if(searchCategoryInput) {
                            searchCategoryInput.value = value;
                            searchCategoryInput.dispatchEvent(new Event('change')); // Trigger fetchSuggestions
                        }
                        if(searchCategoryLabel) searchCategoryLabel.innerText = text;
                        
                        catDropdown.style.display = 'none';
                        if(chev) chev.style.transform = 'rotate(0)';
                    });
                });

                // Close category dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!catBtn.contains(e.target) && !catDropdown.contains(e.target)) {
                        catDropdown.style.display = 'none';
                        if(chev) chev.style.transform = 'rotate(0)';
                    }
                });
            }
        });

        // ---------- Real-time AJAX Search ----------
        const searchInput = document.querySelector('.martX-header-2 #searchInput');
        const searchCategory = document.querySelector('.martX-header-2 #searchCategoryInput'); // Updated to use hidden input
        const dropdown = document.querySelector('.martX-header-2 .search-results-dropdown');
        const searchBtn = document.querySelector('.martX-header-2 .search-btn');
        let originalSearchBtnHtml = '';
        if (searchBtn) originalSearchBtnHtml = searchBtn.innerHTML;
        let searchTimeout;

        function fetchSuggestions() {
            const query = searchInput.value.trim();
            const categoryId = searchCategory.value;

            if (query.length < 2) {
                dropdown.style.display = 'none';
                return;
            }

            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                // Show loading state
                if (searchBtn) searchBtn.innerHTML = `<svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="spin-anim"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg>`;
                dropdown.innerHTML = `<div class="p-3 text-center text-muted" style="font-size: 14.5px;">Searching...</div>`;
                dropdown.style.display = 'block';

                fetch(`{{ route('search.suggestions') }}?search=${encodeURIComponent(query)}&category_id=${encodeURIComponent(categoryId)}`)
                    .then(res => res.json())
                    .then(data => {
                        dropdown.innerHTML = '';
                        if (data.length > 0) {
                            const siteCurrency = '{{ $siteSettings->currency_symbol ?? "৳" }}';
                            data.forEach(item => {
                                const priceHtml = item.discount > 0 
                                    ? `<span style="color: #000; font-weight: 600; font-size: 13px;">${siteCurrency}${item.discount}</span> <span style="color: #e74c3c; font-size: 12px; text-decoration: line-through; margin-left: 5px;">${siteCurrency}${item.price}</span>`
                                    : `<span style="color: #000; font-weight: 600; font-size: 13px;">${siteCurrency}${item.price}</span>`;
    
                                dropdown.innerHTML += `
                                    <a href="/product/${item.slug}" class="search-suggestion-item" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; text-decoration: none; color: #333; border-bottom: 1px solid #f0f0f0;">
                                        <img src="${item.thumbnail}" alt="${item.name}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 6px; flex-shrink: 0;">
                                        <div>
                                            <p class="mb-0" style="font-size: 14px; font-weight: 500;">${item.name}</p>
                                            <div class="mt-1">${priceHtml}</div>
                                        </div>
                                    </a>
                                `;
                            });
                            dropdown.style.display = 'block';
                        } else {
                            dropdown.innerHTML = `<div class="p-3 text-center text-muted">No products found</div>`;
                            dropdown.style.display = 'block';
                        }
                    })
                    .catch(err => {
                        dropdown.innerHTML = `<div class="p-3 text-center text-muted">Something went wrong</div>`;
                        dropdown.style.display = 'block';
                    })
                    .finally(() => {
                        // Restore original search icon
                        if (searchBtn) searchBtn.innerHTML = originalSearchBtnHtml;
                    });
            }, 300);
        }

        if (searchInput && searchCategory) {
            searchInput.addEventListener('input', fetchSuggestions);
            searchCategory.addEventListener('change', fetchSuggestions);
            
            // Hide dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !dropdown.contains(e.target) && !searchCategory.contains(e.target)) {
                    dropdown.style.display = 'none';
                }
            });
        }

        // ---------- Mobile search overlay ----------
        const openSearch = document.getElementById('openSearch2');
        const closeSearch = document.getElementById('closeSearch2');
        const searchOverlay = document.getElementById('searchOverlay2');
        if(openSearch) {
            openSearch.addEventListener('click', () => {
                searchOverlay.classList.add('is-open');
                document.getElementById('searchMobile2').focus({preventScroll:true});
            });
        }
        if(closeSearch) closeSearch.addEventListener('click', () => searchOverlay.classList.remove('is-open'));

        // ---------- Offcanvas ----------
        const openOffcanvas = document.getElementById('openOffcanvas2');
        const closeOffcanvas = document.getElementById('closeOffcanvas2');
        const offcanvas = document.getElementById('offcanvas2');
        const scrim = document.getElementById('scrim2');

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
        document.querySelectorAll('#accordion2 .accordion-trigger').forEach(function(btn){
            btn.addEventListener('click', function(){
                const panel = btn.nextElementSibling;
                const isOpen = btn.getAttribute('aria-expanded') === 'true';

                document.querySelectorAll('#accordion2 .accordion-trigger').forEach(function(other){
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
                    panel.style.maxHeight = panel.scrollHeight + "px";
                }
            });
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const desktopSearchToggle = document.getElementById("desktopSearchToggle");
        const desktopSearchDropdown = document.querySelector(".desktop-search-dropdown");
        
        if (desktopSearchToggle && desktopSearchDropdown) {
            desktopSearchToggle.addEventListener("click", function(e) {
                e.stopPropagation();
                if (desktopSearchDropdown.style.display === "none") {
                    desktopSearchDropdown.style.display = "block";
                    // Focus and clear input
                    setTimeout(() => {
                        const input = desktopSearchDropdown.querySelector("#searchInput");
                        const resultsDropdown = document.querySelector('.martX-header-2 .search-results-dropdown');
                        if(input) {
                            input.value = ''; // Clear text
                            if (resultsDropdown) resultsDropdown.style.display = 'none'; // Hide previous results
                            input.focus();
                        }
                    }, 50);
                } else {
                    desktopSearchDropdown.style.display = "none";
                }
            });
            
            // Prevent closing when clicking inside the dropdown
            desktopSearchDropdown.addEventListener("click", function(e) {
                e.stopPropagation();
            });
            
            // Close when clicking outside
            document.addEventListener("click", function() {
                desktopSearchDropdown.style.display = "none";
            });
        }
    });
</script>

