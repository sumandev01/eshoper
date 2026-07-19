<style>
    /* Header 2 - Floating Capsule Theme */
    .martX-header-2 {
        position: relative;
        width: 100%;
        z-index: 1040;
        background: #fff;
        transition: all 0.3s ease;
        padding-bottom: 10px;
    }

    .martX-header-2 .header-capsule {
        background: rgba(255, 255, 255, 0.75) !important;
        backdrop-filter: blur(25px);
        -webkit-backdrop-filter: blur(25px);
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.06), inset 0 1px 0 rgba(255,255,255,1) !important;
        border: 1px solid rgba(255, 255, 255, 0.4);
        border-radius: 999px;
        transition: all 0.3s ease;
    }

    /* Sticky State */
    .martX-header-2.is-stuck {
        position: fixed;
        top: 0;
        left: 0;
        background: transparent;
        padding-bottom: 0;
        animation: slideDown 0.4s ease-out forwards;
        z-index: 1040;
    }
    .martX-header-2.is-stuck .header-capsule {
        background: rgba(255, 255, 255, 0.9) !important;
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.08) !important;
        border: 1px solid rgba(255, 255, 255, 0.6);
    }

    @keyframes slideDown {
        from { transform: translateY(-100%); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    /* Navigation Links */
    .martX-header-2 .nav-link {
        font-weight: 500;
        font-size: 14.5px;
        color: var(--ink);
        text-decoration: none;
        transition: all 0.25s ease;
        padding: 8px 18px !important;
        border-radius: 99px;
    }
    .martX-header-2 .nav-link:hover {
        background: rgba(0,0,0,0.03);
        color: var(--primary);
    }

    /* Search Form */
    .martX-header-2 .search-form-wrapper {
        max-width: 500px;
    }
    .martX-header-2 .search-form {
        background: rgba(0,0,0,0.025);
        border: 1px solid rgba(0,0,0,0.04) !important;
        transition: all 0.3s ease;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.01);
    }
    .martX-header-2 .search-form:focus-within {
        background: #fff;
        border-color: rgba(242, 92, 39, 0.3) !important;
        box-shadow: 0 0 0 4px rgba(242, 92, 39, 0.08), inset 0 2px 4px rgba(0,0,0,0.01);
    }
    .martX-header-2 .search-input:focus {
        box-shadow: none;
    }
    
    /* Action Buttons */
    .martX-header-2 .action-btn {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(0,0,0,0.03);
        color: var(--ink);
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .martX-header-2 .action-btn:hover {
        background: #fff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        transform: translateY(-2px);
        color: var(--primary) !important;
    }
    .martX-header-2 .badge {
        font-weight: 600;
        padding: 0.25em 0.5em;
        line-height: 1;
        border: 2px solid #fff;
    }

    /* Mega Menu (Reusing some styles from header 1 but scoped to header 2) */
    .martX-header-2 .has-mega {
        position: static;
    }
    .martX-header-2 .mega {
        position: absolute;
        left: 0;
        top: 100%;
        max-width: 100vw;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        display: flex;
        opacity: 0;
        visibility: hidden;
        transform: translateY(15px);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        z-index: 999;
        overflow: hidden;
        margin-top: 20px;
        border: 1px solid var(--line);
    }
    /* Invisible hover bridge connected directly to the Categories button */
    .martX-header-2 .nav-link.categories {
        position: relative;
    }
    .martX-header-2 .nav-link.categories::after {
        content: '';
        position: absolute;
        top: 100%; /* Start exactly at bottom of the button */
        left: 0;
        width: 100%; /* Only as wide as the button */
        height: 35px; /* Stretch down to cover the gap to the mega menu */
        background: transparent;
    }
    .martX-header-2 .has-mega:hover .mega {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }
    .martX-header-2 .mega-cats a:hover,
    .martX-header-2 .mega-cats a.active {
        color: var(--primary) !important;
        transform: translateX(4px);
    }
    .martX-header-2 .mega-sub a:hover {
        color: var(--primary) !important;
    }

    /* Search Results */
    .martX-header-2 .search-results-dropdown {
        max-height: 400px;
        overflow-y: auto;
    }
    .martX-header-2 .search-suggestion-item {
        padding: 10px 15px;
        display: flex;
        align-items: center;
        gap: 15px;
        text-decoration: none;
        color: var(--ink);
        border-bottom: 1px solid var(--line);
        transition: background 0.2s;
    }
    .martX-header-2 .search-suggestion-item:hover {
        background: var(--paper-soft);
    }
    .martX-header-2 .search-suggestion-item img {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 6px;
    }

    /* Mobile adjustments */
    @media (max-width: 991px) {
        .martX-header-2 .header-capsule {
            border-radius: 0;
            margin-top: 0 !important;
            padding-top: 8px !important;
            padding-bottom: 8px !important;
        }
        .martX-header-2 .container {
            padding: 0;
            margin-top: 0 !important;
        }
    }
    
    @media (max-width: 575px) {
        .martX-header-2 .action-btn {
            width: 36px;
            height: 36px;
        }
        .martX-header-2 .header-actions svg {
            width: 18px;
            height: 18px;
        }
        .martX-header-2 .hamburger svg {
            width: 22px;
            height: 22px;
        }
    }
    .search-overlay {
        position: fixed;
        inset: 0;
        background: #fff;
        z-index: 2000;
        display: flex;
        flex-direction: column;
        padding: 18px;
        transform: translateY(-100%);
        transition: transform .3s ease;
    }

    .search-overlay.is-open {
        transform: translateY(0);
    }

    .search-overlay-top {
        display: flex;
        flex-direction: column;
        align-items: stretch;
        gap: 16px;
    }

    .search-overlay-top .search-form {
        flex: 1;
        display: flex;
        align-items: center;
        background: #fff;
        border: 1.5px solid var(--primary);
        border-radius: 999px;
        padding: 5px 5px 5px 10px;
        box-shadow: 0 2px 10px rgba(242,92,39,0.1);
    }

    /* Custom Category Dropdown Styles */
    .custom-cat-option {
        padding: 8px 16px;
        font-size: 13.5px;
        color: var(--ink);
        cursor: pointer;
        transition: background 0.2s, color 0.2s;
    }
    .custom-cat-option:hover {
        background: rgba(242, 92, 39, 0.08); /* Soft primary color background */
        color: var(--primary);
    }
    .custom-cat-option.is-active {
        color: var(--primary);
        font-weight: 600;
        background: rgba(242, 92, 39, 0.05);
    }
    .custom-cat-dropdown::-webkit-scrollbar {
        width: 6px;
    }
    .custom-cat-dropdown::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 10px;
    }

    .search-overlay-top .search-form input {
        flex: 1;
        border: none;
        outline: none;
        background: transparent;
        padding: 8px 0;
        font-size: 14.5px;
        width: 100%;
        min-width: 0;
    }

    .search-overlay-top .search-submit {
        background: var(--primary);
        color: #fff;
        border: none;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-left: 8px;
        box-shadow: 0 4px 10px rgba(242,92,39,0.25);
    }
    
    .search-overlay-top .search-submit svg {
        width: 16px;
        height: 16px;
    }
    .close-overlay {
        padding: 8px;
        color: var(--ink-soft);
        background: transparent;
        border: none;
    }

    .close-overlay svg {
        width: 22px;
        height: 22px;
    }

    /* ============ OFFCANVAS (Bottom Sheet & Glassmorphism) ============ */
    .offcanvas-scrim {
        position: fixed;
        inset: 0;
        background: rgba(42, 36, 30, .45);
        opacity: 0;
        visibility: hidden;
        transition: opacity .3s ease, visibility .3s;
        z-index: 2990;
    }

    .offcanvas-scrim.is-open {
        opacity: 1;
        visibility: visible;
    }

    .martX-header-2-offcanvas {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        width: 100%;
        max-width: 100%;
        max-height: 85vh;
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        z-index: 3000;
        transform: translateY(100%);
        transition: transform .35s cubic-bezier(0.2, 0.8, 0.2, 1);
        display: flex;
        flex-direction: column;
        border-radius: 24px 24px 0 0;
        box-shadow: 0 -10px 40px rgba(0, 0, 0, 0.1);
        border-top: 1px solid rgba(255,255,255,0.6);
    }

    .martX-header-2-offcanvas.is-open {
        transform: translateY(0);
    }

    .offcanvas-head {
        background: transparent;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        border-bottom: 1px solid rgba(0,0,0,0.06);
    }

    .bottom-sheet-handle {
        width: 36px;
        height: 5px;
        background: rgba(0,0,0,0.15);
        border-radius: 10px;
        margin: 0 auto 16px;
    }

    .avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: var(--primary);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-family: "Poppins", sans-serif;
        flex-shrink: 0;
        box-shadow: 0 4px 10px rgba(242,92,39,0.25);
    }

    .offcanvas-head p {
        margin: 0;
        font-size: 14px;
        font-weight: 700;
        color: var(--ink);
    }

    .offcanvas-head span {
        font-size: 12.5px;
        color: var(--ink-soft);
    }

    .offcanvas-close {
        padding: 8px;
        color: var(--ink-soft);
        background: transparent;
        border: none;
        transition: all 0.2s;
    }
    .offcanvas-close:hover {
        background: rgba(0,0,0,0.08) !important;
        color: var(--ink);
    }

    .offcanvas-close svg {
        width: 20px;
        height: 20px;
    }

    .offcanvas-body {
        flex: 1;
        overflow-y: auto;
        padding: 8px 6px;
    }

    .oc-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 13px 14px;
        font-size: 14.5px;
        font-weight: 600;
        border-radius: 10px;
        text-decoration: none;
    }

    .oc-link:hover {
        background: var(--peach);
    }

    .oc-link svg {
        width: 18px;
        height: 18px;
        color: var(--primary);
    }

    .accordion-item {
        border: 1px solid rgba(0,0,0,0.06);
        border-radius: 12px;
        margin-bottom: 10px;
        background: #fff;
        overflow: hidden;
    }

    .accordion-trigger,
    .accordion-link {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 13px 14px;
        font-size: 14.5px;
        font-weight: 600;
        text-align: left;
        color: var(--ink);
        text-decoration: none;
        background: none;
        border: none;
    }

    .accordion-trigger .arrow-icon {
        color: var(--ink-soft);
        font-size: 13px;
        transition: transform .3s ease;
    }

    .accordion-trigger[aria-expanded="true"] .arrow-icon {
        transform: rotate(180deg);
    }

    .accordion-panel {
        max-height: 0;
        overflow: hidden;
        transition: max-height .3s ease;
    }

    .subcat-panel {
        background: rgba(0,0,0,0.02);
        border-top: 1px solid rgba(0,0,0,0.04);
    }

    .accordion-panel a {
        display: block;
        padding: 9px 14px 9px 30px;
        font-size: 13.5px;
        color: var(--ink-soft);
        text-align: left;
        text-decoration: none;
    }

    .accordion-panel a:hover {
        color: var(--primary);
    }
    /* Search Suggestions Hover */
    .search-suggestion-item {
        transition: background 0.2s;
    }
    .search-suggestion-item:hover {
        background: rgba(0,0,0,0.02);
    }
    .search-suggestion-item p {
        transition: color 0.2s ease-in-out;
    }
    .search-suggestion-item:hover p {
        color: var(--primary) !important;
    }

    /* Spinner Animation */
    @keyframes spin-anim {
        100% { transform: rotate(360deg); }
    }
    .spin-anim {
        animation: spin-anim 1s linear infinite;
    }
</style>
