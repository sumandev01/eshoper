$(document).ready(function () {
    // ---------- INIT VARIANTS ----------
    initProductVariants();

    $(".product-card").each(function () {
        let sizeElement = $(this).find(".shop-size-selector");
        if (sizeElement.length > 0 && sizeElement.val() !== "") {
            fetchColors(sizeElement);
        }
    });

    // ---------- EVENTS ----------
    $(document).on("change", ".shop-size-selector", function () {
        fetchColors($(this));
    });

    $(document).on("change", ".shop-color-selector", function () {
        updateProductUI($(this));
    });

    $(document).on("click", ".shop-add-to-cart", function (e) {
        e.preventDefault();

        let card = $(this).closest(".product-card");
        let productId = $(this).data("product-id");
        let sizeId = card.find(".shop-size-selector").val();
        let colorId = card.find(".shop-color-selector").val();

        let currentPrice = card
            .find(".variant-price")
            .text()
            .replace("৳", "")
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

        submitBtn.prop("disabled", true).text("Checking...");

        $.ajax({
            url: "/login",
            type: "POST",
            data: formData,
            dataType: "json",
            success: function (response) {
                $("#loginModal").modal("hide");
                showToast("success", response.message);

                if (window.pendingCartData) {
                    const { productId, sizeId, colorId, price } =
                        window.pendingCartData;
                    addToCart(productId, sizeId, colorId, price);
                    window.pendingCartData = null;
                }

                setTimeout(function () {
                    location.reload();
                }, 1000);
            },
            error: function (xhr) {
                submitBtn.prop("disabled", false).text("Login");

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
                $("#wishlistCount").text(response.wishlistCount);
            },
            error: function (xhr) {
                showToast("error", "Please login to add this to your wishlist.");
            },
        });
    });
});

// ---------- HELPER FUNCTIONS ----------

function initProductVariants(context = document) {
    $(context)
        .find(".product-card")
        .each(function () {
            let sizeElement = $(this).find(".shop-size-selector");
            if (sizeElement.length > 0 && sizeElement.val() !== "") {
                fetchColors(sizeElement);
            }
        });
}

function fetchColors(element) {
    let card = element.closest(".product-card");
    let productId = card.attr("data-product-id");
    let sizeId = element.val();
    let colorDropdown = card.find(".shop-color-selector");

    if (!productId || !sizeId) return;

    $.ajax({
        url: window.LaravelData.route_getColorBySize,
        type: "POST",
        data: {
            productId: productId,
            sizeId: sizeId,
            _token: window.LaravelData.csrf_token,
        },
        success: function (response) {
            card.data("variants", response.colors);

            let options = '<option value="" disabled>Color</option>';

            if (response.colors && response.colors.length > 0) {
                response.colors.forEach(function (color, index) {
                    let selected = index === 0 ? "selected" : "";
                    options += `<option value="${color.id}" ${selected}>${color.name}</option>`;
                });

                colorDropdown
                    .html(options)
                    .prop("disabled", false)
                    .trigger("change");
            } else {
                colorDropdown
                    .html('<option value="">N/A</option>')
                    .prop("disabled", true)
                    .trigger("change");
            }
        },
        error: function () {
            console.error("Color fetch failed");
        },
    });
}

function updateProductUI(element) {
    let card = element.closest(".product-card");
    let colorId = element.val();
    let variants = card.data("variants");

    if (!variants || !colorId) return;

    let selected = variants.find((v) => v.id == colorId);
    if (!selected) return;

    let basePrice = parseFloat(selected.price);
    let discountPrice = parseFloat(selected.discount_price);

    if (discountPrice > 0 && discountPrice < basePrice) {
        card.find(".variant-price").text("৳" + discountPrice);
        card.find(".main-price")
            .text("৳" + basePrice)
            .removeClass("d-none");

        card.find(".save-amount-box").removeClass("d-none");
        card.find(".save-amount").text("Save ৳" + (basePrice - discountPrice));
    } else {
        card.find(".variant-price").text("৳" + basePrice);
        card.find(".main-price").addClass("d-none");
        card.find(".save-amount-box").addClass("d-none");
    }

    if (selected.stock <= 0) {
        card.find(".shop-add-to-cart")
            .addClass("disabled")
            .text("Out of Stock");
    } else {
        card.find(".shop-add-to-cart")
            .removeClass("disabled")
            .html(
                '<i class="fas fa-shopping-cart text-primary mr-1"></i>Add To Cart',
            );
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
            $("#cartCount").text(response.cartCount);
        },
        error: function (xhr) {
            if (xhr.status === 401) {
                $("#loginModal").modal("show");
            }
            console.error("Add to cart failed");
        },
    });
}
