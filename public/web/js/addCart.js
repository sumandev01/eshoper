$(document).ready(function () {
    // ---------- INIT VARIANTS ----------
    initProductVariants();

    $(".product-card").each(function () {
        let sizeElement = $(this).find(".shop-size-selector.active");
        if (sizeElement.length > 0 && sizeElement.data("value") !== "") {
            fetchColors(sizeElement);
        }
    });

    // ---------- EVENTS ----------
    $(document).on("click", ".shop-size-selector", function () {
        let card = $(this).closest(".product-card");
        card.find(".shop-size-selector").removeClass("active");
        $(this).addClass("active");
        fetchColors($(this));
    });

    $(document).on("click", ".shop-color-selector", function () {
        let card = $(this).closest(".product-card");
        card.find(".shop-color-selector").removeClass("active");
        $(this).addClass("active");
        updateProductUI($(this));
    });

    $(document).on("click", ".shop-add-to-cart", function (e) {
        e.preventDefault();

        let $btn = $(this);
        if ($btn.hasClass("disabled")) return;

        let card = $btn.closest(".product-card");
        let productId = $btn.data("product-id");
        let sizeId = card.find(".shop-size-selector.active").data("value");
        let colorId = card.find(".shop-color-selector.active").data("value");

        let currentPrice = card
            .find(".variant-price")
            .text()
            .replace(siteCurrency, "")
            .trim();

        if (card.find(".shop-size-selector").length > 0) {
            if (!sizeId || !colorId) {
                showToast(
                    "error",
                    "Please select a size and color before adding to cart.",
                );
                return;
            }
        }

        addToCart(productId, sizeId, colorId, currentPrice);
    });

    // ---------- PASSWORD TOGGLE ----------
    $(document).on("click", "#togglePassword", function () {
        const passwordField = $("#modal_password");
        const type =
            passwordField.attr("type") === "password" ? "text" : "password";
        passwordField.attr("type", type);
        $(this).toggleClass("fa-eye fa-eye-slash");
    });

    // ---------- AJAX LOGIN ----------
    // AJAX Login Logic
    $(document).on("submit", "#ajaxLoginForm", function (e) {
        e.preventDefault();

        let formData = $(this).serialize();
        let submitBtn = $(this).find('button[type="submit"]');
        let btnText = submitBtn.find('.btn-text');
        let spinner = submitBtn.find('.spinner-border');

        submitBtn.prop("disabled", true);
        btnText.text("Logging in...");
        spinner.removeClass('d-none');

        $.ajax({
            url: "/login",
            type: "POST",
            data: formData,
            dataType: "json",
            success: function (response) {
                // Update CSRF token globally to prevent 419 errors on subsequent requests
                if(response.csrf_token) {
                    window.LaravelData.csrf_token = response.csrf_token;
                    $('meta[name="csrf-token"]').attr('content', response.csrf_token);
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': response.csrf_token
                        }
                    });
                    $('input[name="_token"]').val(response.csrf_token);
                }

                // Update Header HTML without reloading
                if(response.header_html) {
                    $('#main-header-wrapper').html(response.header_html);
                }

                $("#loginModal").modal("hide");
                showToast("success", response.message);

                // Reset button state
                submitBtn.prop("disabled", false);
                btnText.text("Login");
                spinner.addClass('d-none');

                if (window.pendingCartData) {
                    const { productId, sizeId, colorId, price } =
                        window.pendingCartData;
                    addToCart(productId, sizeId, colorId, price);
                    window.pendingCartData = null;
                }
            },
            error: function (xhr) {
                submitBtn.prop("disabled", false);
                btnText.text("Login");
                spinner.addClass('d-none');

                let errorMsg = "Login failed. Please try again.";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }

                $("#loginError").text(errorMsg).show();
            },
        });
    });

    //
    $(document).on("click", ".wishlist-btn", function () {
        let $btn = $(this);
        let $icon = $btn.find("i");
        let productId = $btn.data("product-id");

        $.ajax({
            url: window.LaravelData.route_wishlistToggle,
            method: "POST",
            data: {
                product_id: productId,
                _token: window.LaravelData.csrf_token,
            },
            success: function (response) {
                showToast("success", response.message);
                if (response.action === "added") {
                    $icon.css("color", "#e74c3c"); // red
                } else if (response.action === "removed") {
                    $icon.css("color", "#ccc"); // grey
                }
                $(".global-wishlist-count").text(response.wishlistCount);
            },
            error: function (xhr) {
                showToast(
                    "error",
                    "Please login to add this to your wishlist.",
                );
            },
        });
    });
});

// ---------- HELPER FUNCTIONS ----------

function initProductVariants(context = document) {
    $(context)
        .find(".product-card")
        .each(function () {
            let sizeElement = $(this).find(".shop-size-selector.active");
            if (sizeElement.length > 0 && sizeElement.data("value") !== "") {
                fetchColors(sizeElement);
            }
        });
}

function fetchColors(element) {
    let card = element.closest(".product-card");
    let sizeId = element.data("value");
    let colorContainer = card.find(".shop-color-container");
    let variants = card.data("variants");
    let requestedColorId = card.data("active-color");

    if (!variants || !sizeId) return;

    // Local filtering - No AJAX
    let availableVariants = variants.filter((v) => v.size_id == sizeId);
    let html = '';

    if (availableVariants.length > 0) {
        availableVariants.forEach(function (variant, index) {
            let activeClass = "";
            if (requestedColorId && availableVariants.some(v => v.color_id == requestedColorId)) {
                activeClass = (variant.color_id == requestedColorId) ? "active" : "";
            } else {
                activeClass = index === 0 ? "active" : "";
            }
            let colorCode = variant.color_code || '#000';
            html += `<div><span class="color shop-color-selector ${activeClass}" data-value="${variant.color_id}" style="background-color: ${colorCode}; cursor: pointer;" title="${variant.color_name}"></span></div>`;
        });

        colorContainer.html(html);
        updateProductUI(colorContainer.find('.shop-color-selector.active'));
    } else {
        colorContainer.html('');
    }
}

function updateProductUI(element) {
    let card = element.closest(".product-card");
    let colorId = element.data("value");
    let sizeId = card.find(".shop-size-selector.active").data("value");
    let variants = card.data("variants");

    if (!variants || !colorId || !sizeId) return;

    let selected = variants.find((v) => v.color_id == colorId && v.size_id == sizeId);
    if (!selected) return;

    // --- IMAGE UPDATE LOGIC ---
    let imgEl = card.find(".product-main-image");
    if (imgEl.length > 0) {
        // Save the original fallback image on first interaction
        if (!imgEl.data("original-src")) {
            imgEl.data("original-src", imgEl.attr("src"));
        }
        
        // Swap to variant image, fallback to original if null
        let newSrc = selected.image ? selected.image : imgEl.data("original-src");
        
        if (imgEl.attr("src") !== newSrc) {
            imgEl.css("opacity", "0.6").css("transition", "opacity 0.2s");
            imgEl.attr("src", newSrc).on('load', function() {
                $(this).css("opacity", "1");
            });
        }
    }

    // --- PRICE & DISCOUNT LOGIC ---
    let basePrice = parseFloat(selected.price) || 0;
    let discountPrice = parseFloat(selected.discount_price) || 0;
    
    let saveBox = card.find(".save-amount-box");
    let saveAmountText = card.find(".save-amount");

    if (discountPrice > 0 && discountPrice < basePrice) {
        card.find(".variant-price").text(
            siteCurrency + discountPrice.toLocaleString("en-IN")
        );
        card.find(".main-price")
            .text(siteCurrency + basePrice.toLocaleString("en-IN"))
            .removeClass("d-none");

        saveBox.removeClass("d-none").addClass("d-block");
        let percentage = Math.round(((basePrice - discountPrice) / basePrice) * 100);
        saveAmountText.text("-" + percentage + "%");
    } else {
        card.find(".variant-price").text(siteCurrency + basePrice.toLocaleString('en-IN'));
        card.find(".main-price").addClass("d-none");
        
        saveBox.removeClass("d-block").addClass("d-none");
    }

    // --- STOCK & BUTTON LOGIC ---
    let addToCartBtn = card.find(".shop-add-to-cart");
    if (selected.stock <= 0) {
        addToCartBtn
            .addClass("disabled")
            .html('<i class="fas fa-shopping-cart me-2"></i> Out of Stock')
            .attr("title", "Out of Stock")
            .css("pointer-events", "none");
    } else {
        addToCartBtn
            .removeClass("disabled")
            .html('<i class="fas fa-shopping-cart me-2"></i> Add to Cart')
            .removeAttr("title")
            .css("pointer-events", "auto");
    }
}

function addToCart(productId, sizeId, colorId, price) {
    $.ajax({
        url: window.LaravelData.route_addToCart,
        type: "POST",
        data: {
            productId: productId,
            sizeId: sizeId,
            colorId: colorId,
            price: price,
            quantity: 1,
            _token: window.LaravelData.csrf_token,
        },
        success: function (response) {
            showToast("success", response.message);
            $(".global-cart-count").text(response.cartCount);
            
            // Fetch and update the mini-cart dropdown globally
            $.get(window.LaravelData.route_minicart, function(html) {
                $('#minicart').replaceWith(html);
            });
        },
        error: function (xhr) {
            if (xhr.status === 401) {
                window.pendingCartData = {
                    productId: productId,
                    sizeId: sizeId,
                    colorId: colorId,
                    price: price
                };
                $("#loginModal").modal("show");
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                showToast("error", xhr.responseJSON.message);
            } else {
                showToast("error", "Failed to add to cart.");
            }
            console.error("Add to cart failed");
        },
    });
}
