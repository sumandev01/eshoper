<style>
    :root {
        --ink: #2A241E;
        --ink-soft: #6B6259;
        --paper: #FFFFFF;
        --peach: #FFF4EC;
        --peach-line: #F3E4D6;
        --line: #ECE6DE;
        --brand: var(--primary, #E85D2A);
        --brand-deep: var(--primary-deep, #C64A1E);
        --brand-tint: var(--primary-soft, #FFEDE2);
        --badge: #1E9E6B;
        --shadow-soft: 0 10px 30px -14px rgba(42, 36, 30, 0.18);
        --shadow-pop: 0 20px 50px -12px rgba(42, 36, 30, 0.28);
        --radius: 14px;
        --ease: cubic-bezier(.22, 1, .36, 1);
    }

    /* Prevent conflict with Bootstrap */
    .martX-header-1 {
        font-family: 'Hind Siliguri', 'Poppins', sans-serif;
    }

    .martX-header-1 *,
    .martX-header-1 *::before,
    .martX-header-1 *::after {
        box-sizing: border-box;
    }

    .martX-header-1 ul {
        padding: 0;
        margin: 0;
        list-style: none;
    }

    .martX-header-1 a {
        text-decoration: none;
        color: inherit;
    }

    .martX-header-1 button {
        font-family: inherit;
        border: none;
        background: none;
        cursor: pointer;
        color: inherit;
        padding: 0;
    }

    .martX-header-1 .brandmark {
        font-family: 'Poppins', sans-serif;
    }

    /* ============ WRAPPER ============ */
    .martX-header-1 {
        position: relative;
        background: var(--paper);
        width: 100%;
        z-index: 1050;
    }

    .martX-header-1.is-stuck {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        box-shadow: var(--shadow-soft);
        animation: slideDown 0.35s ease-out forwards;
    }

    @keyframes slideDown {
        from {
            transform: translateY(-100%);
        }

        to {
            transform: translateY(0);
        }
    }

    /* ============ TOP UTILITY BAR ============ */
    .martX-header-1 .topbar {
        background: var(--peach);
        border-bottom: 1px solid var(--peach-line);
        font-size: 13px;
        color: var(--ink-soft);
        max-height: 38px;
        overflow: hidden;
        transition: max-height .35s var(--ease), opacity .3s var(--ease), border-color .3s;
    }

    .martX-header-1.is-stuck .topbar {
        max-height: 0;
        opacity: 0;
        border-color: transparent;
    }

    .martX-header-1 .topbar-inner {
        height: 38px;
    }

    .martX-header-1 .topbar-links {
        display: flex;
        gap: 22px;
    }

    .martX-header-1 .topbar-links a {
        transition: color .2s;
    }

    .martX-header-1 .topbar-links a:hover {
        color: var(--brand-deep);
    }

    .martX-header-1 .topbar-right {
        display: flex;
        align-items: center;
        gap: 18px;
    }

    .martX-header-1 .social-icons {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .martX-header-1 .social-icons svg {
        width: 14px;
        height: 14px;
        opacity: .65;
        transition: opacity .2s;
    }

    .martX-header-1 .social-icons i {
        opacity: .65;
        transition: opacity .2s;
        font-size: 14px;
    }

    .martX-header-1 .social-icons a:hover svg,
    .martX-header-1 .social-icons a:hover i {
        opacity: 1;
    }

    .martX-header-1 .topbar-divider {
        width: 1px;
        height: 14px;
        background: var(--peach-line);
    }

    /* ============ MAIN HEADER ============ */
    .martX-header-1 .main-header {
        transition: box-shadow .3s var(--ease), padding .3s var(--ease);
        background: var(--paper);
    }

    .martX-header-1.is-stuck .main-header {
        box-shadow: var(--shadow-soft);
    }

    .martX-header-1 .main-header-inner {
        padding: 16px 0px;
        display: grid;
        grid-template-columns: auto 1fr auto;
        align-items: center;
        gap: 32px;
        transition: padding .3s var(--ease);
    }

    .martX-header-1.is-stuck .main-header-inner {
        padding-top: 11px;
        padding-bottom: 11px;
    }

    .martX-header-1 .logo {
        display: flex;
        align-items: baseline;
        gap: 2px;
        font-size: 26px;
        font-weight: 700;
        color: var(--ink);
        white-space: nowrap;
    }

    .martX-header-1 .logo span {
        color: var(--brand);
    }

    /* search */
    .martX-header-1 .search-form-wrapper {
        max-width: 560px;
        width: 100%;
        margin: 0 auto;
        position: relative;
    }

    .martX-header-1 .search-form {
        display: flex;
        align-items: center;
        width: 100%;
        border: 1.5px solid var(--line);
        border-radius: 999px;
        padding: 4px 4px 4px 18px;
        background: var(--paper);
        transition: border-color .25s, box-shadow .25s;
    }

    .martX-header-1 .search-form:focus-within {
        border-color: var(--brand);
        box-shadow: 0 0 0 4px var(--brand-tint);
    }

    .martX-header-1 .search-form input {
        flex: 1;
        border: none;
        outline: none;
        font-size: 14.5px;
        font-family: inherit;
        background: transparent;
        color: var(--ink);
        padding: 8px 0;
    }

    .martX-header-1 .search-form input::placeholder {
        color: #B4AA9E;
    }

    .martX-header-1 .search-submit {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: var(--brand);
        color: #fff;
        flex-shrink: 0;
        transition: background .2s;
    }

    .martX-header-1 .search-submit:hover {
        background: var(--brand-deep);
    }

    .martX-header-1 .search-submit svg {
        width: 16px;
        height: 16px;
    }

    #search-suggestions {
        position: absolute;
        top: calc(100% + 4px);
        left: 0;
        width: 100%;
        background: var(--paper);
        z-index: 1000;
        box-shadow: var(--shadow-pop);
        border-radius: 14px;
        overflow: hidden;
        display: none;
        border: 1px solid var(--line);
    }

    /* right icon cluster */
    .martX-header-1 .header-actions {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .martX-header-1 .icon-btn {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 2px;
        padding: 8px 12px;
        border-radius: 10px;
        font-size: 11.5px;
        color: var(--ink-soft);
        transition: background .2s, color .2s;
    }

    .martX-header-1 .icon-btn:hover {
        background: var(--peach);
        color: var(--ink);
    }

    .martX-header-1 .icon-btn svg {
        width: 21px;
        height: 21px;
        stroke: currentColor;
    }

    .martX-header-1 .cart-count {
        position: absolute;
        top: 2px;
        right: 6px;
        background: var(--brand);
        color: #fff;
        font-size: 10px;
        font-weight: 700;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transform: scale(1);
    }

    .martX-header-1 .cart-count.bump {
        animation: bump .38s var(--ease);
    }

    @keyframes bump {
        0% {
            transform: scale(1);
        }

        35% {
            transform: scale(1.5);
        }

        65% {
            transform: scale(.85);
        }

        100% {
            transform: scale(1);
        }
    }

    /* ============ NAV ROW ============ */
    .martX-header-1 .nav-row {
        background: var(--paper);
        border-bottom: 1px solid var(--line);
        border-top: 1px solid var(--line);
    }

    .martX-header-1 .nav-inner {
        padding: 0px;
        display: flex;
        align-items: center;
        gap: 6px;
        position: relative;
    }

    .martX-header-1 .nav-inner>li {
        /* position removed to allow mega menu to align to container */
    }

    .martX-header-1 .nav-link {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 15px 16px;
        font-size: 16px;
        font-weight: 600;
        color: var(--ink);
        position: relative;
    }

    .martX-header-1 .nav-link::after {
        content: '';
        position: absolute;
        left: 16px;
        right: 16px;
        bottom: 9px;
        height: 2px;
        background: var(--brand);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform .28s var(--ease);
    }

    .martX-header-1 .nav-link:hover::after,
    .martX-header-1 .nav-link.is-open::after {
        transform: scaleX(1);
    }

    .martX-header-1 .nav-link:hover,
    .martX-header-1 .nav-link.is-open {
        color: var(--brand-deep);
    }

    .martX-header-1 .chev {
        width: 11px;
        height: 11px;
        transition: transform .25s var(--ease);
    }

    .martX-header-1 .has-mega:hover .chev {
        transform: rotate(180deg);
    }

    .martX-header-1 .nav-link.categories {
        color: var(--paper);
        background: var(--brand);
    }

    .martX-header-1 .nav-link.categories::after {
        display: none;
    }

    .martX-header-1 .nav-link.categories:hover {
        color: var(--paper);
    }

    /* ============ MEGA MENU ============ */
    .martX-header-1 .mega {
        position: absolute;
        left: 0;
        top: 100%;
        width: 1024px;
        max-width: 100%;
        background: var(--paper);
        border: 1px solid var(--line);
        border-radius: 0 16px 16px 16px;
        box-shadow: var(--shadow-pop);
        padding: 26px;
        display: grid;
        grid-template-columns: auto 1fr 250px;
        gap: 30px;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-8px);
        transition: opacity .22s var(--ease), transform .22s var(--ease), visibility .22s;
        z-index: 50;
    }

    .martX-header-1 .has-mega:hover .mega,
    .martX-header-1 .has-mega:focus-within .mega {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .martX-header-1 .mega-col-title {
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .04em;
        color: var(--ink-soft);
        text-transform: uppercase;
        margin-bottom: 12px;
    }

    .martX-header-1 .mega-cats li a {
        display: block;
        padding: 8px 10px;
        margin: 0 -10px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        color: var(--ink);
        transition: background .18s, color .18s;
    }

    .martX-header-1 .mega-cats li a:hover {
        background: var(--peach);
        color: var(--brand-deep);
    }

    .martX-header-1 .mega-cats li a.is-active {
        color: var(--brand-deep);
        background: var(--peach);
    }

    .martX-header-1 .mega-sub {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 6px 20px;
    }

    .martX-header-1 .mega-sub a {
        display: block;
        padding: 6px 0;
        font-size: 13.5px;
        color: var(--ink-soft);
        transition: color .18s, transform .18s;
    }

    .martX-header-1 .mega-sub a:hover {
        color: var(--brand-deep);
        transform: translateX(3px);
    }

    .martX-header-1 .mega-promo {
        border-radius: 14px;
        overflow: hidden;
        background: linear-gradient(155deg, var(--brand) 0%, var(--brand-deep) 100%);
        color: #fff;
        padding: 20px 18px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 230px;
        position: relative;
    }

    .martX-header-1 .mega-promo::before {
        content: '';
        position: absolute;
        right: -30px;
        top: -30px;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: rgba(255, 255, 255, .12);
    }

    .martX-header-1 .offerBtn i {
        margin-left: 6px;
        font-size: 12px;
        transition: margin-left .2s;
        
    }

    .martX-header-1 .offerBtn:hover i {
        margin-left: 15px;
        transition: margin-left .2s;
    }

    .martX-header-1 .live-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .03em;
        background: rgba(255, 255, 255, .18);
        padding: 4px 10px;
        border-radius: 999px;
        width: fit-content;
    }

    .martX-header-1 .live-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #fff;
        animation: pulse 1.4s infinite;
    }

    /* ============ MINI CART ============ */
    .martX-header-1 .cart-wrap {
        position: relative;
    }

    .martX-header-1 .minicart {
        position: absolute;
        right: 0;
        top: 100%;
        width: 340px;
        background: var(--paper);
        border: 1px solid var(--line);
        border-radius: 16px;
        box-shadow: var(--shadow-pop);
        padding: 18px;
        opacity: 0;
        visibility: hidden;
        transform: translateY(10px);
        transition: opacity .22s var(--ease), transform .22s var(--ease), visibility .22s;
        z-index: 60;
    }
    
    .martX-header-1 .minicart::before {
        content: '';
        position: absolute;
        top: -20px;
        left: 0;
        right: 0;
        height: 20px;
        background: transparent;
    }

    .martX-header-1 .cart-wrap:hover .minicart,
    .martX-header-1 .cart-wrap:focus-within .minicart {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .martX-header-1 .minicart-title {
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .martX-header-1 .minicart-title span {
        font-size: 12px;
        color: var(--ink-soft);
        font-weight: 600;
    }

    .martX-header-1 .cart-item {
        display: flex;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid var(--line);
        text-align: left;
    }

    .martX-header-1 .cart-item:last-of-type {
        border-bottom: none;
    }

    .martX-header-1 .cart-item img {
        width: 52px;
        height: 52px;
        border-radius: 10px;
        object-fit: cover;
        background: var(--peach);
    }

    .martX-header-1 .cart-item-info {
        flex: 1;
        min-width: 0;
    }

    .martX-header-1 .cart-item-info p {
        margin: 0 0 4px;
        font-size: 13.5px;
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .martX-header-1 .cart-item-info .meta {
        font-size: 12px;
        color: var(--ink-soft);
    }

    .martX-header-1 .cart-item-price {
        font-size: 13.5px;
        font-weight: 700;
        color: var(--brand-deep);
        white-space: nowrap;
    }

    .martX-header-1 .minicart-foot {
        margin-top: 14px;
        padding-top: 14px;
        border-top: 1px dashed var(--line);
    }

    .martX-header-1 .minicart-subtotal {
        display: flex;
        justify-content: space-between;
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 12px;
    }

    .martX-header-1 .btn-checkout {
        display: block;
        width: 100%;
        text-align: center;
        padding: 12px;
        border-radius: 10px;
        background: var(--brand);
        color: #fff;
        font-weight: 700;
        font-size: 14px;
        transition: background .2s;
    }

    .martX-header-1 .btn-checkout:hover {
        background: var(--brand-deep);
        color: #fff;
    }

    /* ============ SEARCH OVERLAY (mobile) ============ */
    .martX-header-1 .search-overlay {
        position: fixed;
        inset: 0;
        background: var(--paper);
        z-index: 2000;
        display: flex;
        flex-direction: column;
        padding: 18px;
        transform: translateY(-100%);
        transition: transform .3s var(--ease);
    }

    .martX-header-1 .search-overlay.is-open {
        transform: translateY(0);
    }

    .martX-header-1 .search-overlay-top {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .martX-header-1 .search-overlay-top .search-form {
        flex: 1;
        display: flex;
        align-items: center;
        border: 1.5px solid var(--line);
        border-radius: 999px;
        padding: 4px 4px 4px 18px;
    }

    .martX-header-1 .search-overlay-top .search-form input {
        flex: 1;
        border: none;
        outline: none;
        background: transparent;
        padding: 8px 0;
        font-size: 14.5px;
    }

    .martX-header-1 .close-overlay {
        padding: 8px;
        color: var(--ink-soft);
    }

    .martX-header-1 .close-overlay svg {
        width: 22px;
        height: 22px;
    }

    /* ============ OFFCANVAS ============ */
    .martX-header-1 .offcanvas-scrim {
        position: fixed;
        inset: 0;
        background: rgba(42, 36, 30, .45);
        opacity: 0;
        visibility: hidden;
        transition: opacity .3s var(--ease), visibility .3s;
        z-index: 2990;
    }

    .martX-header-1 .offcanvas-scrim.is-open {
        opacity: 1;
        visibility: visible;
    }

    .martX-header-1 .martX-header-1-offcanvas {
        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;
        width: 82%;
        max-width: 340px;
        background: var(--paper);
        z-index: 3000;
        transform: translateX(-100%);
        transition: transform .32s var(--ease);
        display: flex;
        flex-direction: column;
        box-shadow: var(--shadow-pop);
    }

    .martX-header-1 .martX-header-1-offcanvas.is-open {
        transform: translateX(0);
    }

    .martX-header-1 .offcanvas-head {
        background: var(--peach);
        padding: 20px 18px;
        display: flex;
        align-items: center;
        gap: 12px;
        border-bottom: 1px solid var(--peach-line);
    }

    .martX-header-1 .avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: var(--brand);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-family: 'Poppins', sans-serif;
        flex-shrink: 0;
    }

    .martX-header-1 .offcanvas-head p {
        margin: 0;
        font-size: 13.5px;
        font-weight: 700;
    }

    .martX-header-1 .offcanvas-head span {
        font-size: 12px;
        color: var(--ink-soft);
    }

    .martX-header-1 .offcanvas-close {
        margin-left: auto;
        padding: 6px;
        color: var(--ink-soft);
    }

    .martX-header-1 .offcanvas-close svg {
        width: 20px;
        height: 20px;
    }

    .martX-header-1 .offcanvas-body {
        flex: 1;
        overflow-y: auto;
        padding: 8px 6px;
    }

    .martX-header-1 .oc-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 13px 14px;
        font-size: 14.5px;
        font-weight: 600;
        border-radius: 10px;
    }

    .martX-header-1 .oc-link:hover {
        background: var(--peach);
    }

    .martX-header-1 .oc-link svg {
        width: 18px;
        height: 18px;
        color: var(--brand);
    }

    .martX-header-1 .accordion-item {
        border-bottom: 1px solid var(--line);
        border-top: none;
        border-left: none;
        border-right: none;
        border-radius: 0;
    }

    .martX-header-1 .accordion-trigger,
    .martX-header-1 .accordion-link {
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
    }

    .martX-header-1 .accordion-trigger .arrow {
        width: 9px;
        height: 9px;
        border-right: 2px solid var(--ink-soft);
        border-bottom: 2px solid var(--ink-soft);
        transform: rotate(-45deg);
        transition: transform .25s var(--ease);
    }

    .martX-header-1 .accordion-trigger[aria-expanded="true"] .arrow {
        transform: rotate(45deg);
    }

    .martX-header-1 .accordion-panel {
        max-height: 0;
        overflow: hidden;
        transition: max-height .3s var(--ease);
    }

    .martX-header-1 .accordion-panel a {
        display: block;
        padding: 9px 14px 9px 30px;
        font-size: 13.5px;
        color: var(--ink-soft);
        text-align: left;
    }

    .martX-header-1 .accordion-panel a:hover {
        color: var(--brand-deep);
    }

    /* ============ RESPONSIVE ============ */
    @media (max-width:1024px) {
        .martX-header-1 .main-header-inner {
            grid-template-columns: auto 1fr auto;
            gap: 18px;
        }

        .martX-header-1 .search-form-wrapper {
            max-width: 340px;
        }
    }

    @media (max-width:860px) {
        .martX-header-1 .nav-row {
            display: none;
        }

        .martX-header-1 .main-header-inner {
            display: none;
        }

        .martX-header-1 .mobile-bar {
            border-bottom: 1px solid var(--line);
        }

        .martX-header-1 .hamburger {
            padding: 6px;
            color: var(--ink);
        }

        .martX-header-1 .hamburger svg {
            width: 23px;
            height: 23px;
        }

        .martX-header-1 .mobile-bar .logo {
            font-size: 21px;
            flex: 1;
            justify-content: flex-start;
        }

        .martX-header-1 .mobile-bar .header-actions {
            gap: 0;
        }

        .martX-header-1 .mobile-bar .icon-btn {
            padding: 6px 9px;
        }

        .martX-header-1 .mobile-bar .icon-btn .icon-label {
            display: none;
        }
    }
</style>
