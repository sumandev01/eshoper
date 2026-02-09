$(document).ready(function () {
    initProductVariants();
    // Load colors based on default size for each product card on page load
    $(".product-card").each(function () {
        let sizeElement = $(this).find(".shop-size-selector");
        if (sizeElement.length > 0 && sizeElement.val() !== "") {
            fetchColors(sizeElement);
        }
    });

    // When size dropdown changes
    $(document).on("change", ".shop-size-selector", function () {
        fetchColors($(this));
    });

    // When color dropdown changes, update price and UI
    $(document).on("change", ".shop-color-selector", function () {
        updateProductUI($(this));
    });

    // Add to cart logic
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
                showToast("error", "Please select a size and color before adding to cart.");
                return;
            }
        }
        addToCart(productId, sizeId, colorId, currentPrice);
    });
});

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

// Function to fetch colors and variant data
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
            // Store variant data inside the product card
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
        error: function (xhr) {
            console.error("Color fetch failed");
        },
    });
}

// Main function to update product UI
function updateProductUI(element) {
    let card = element.closest(".product-card");
    let colorId = element.val();
    let variants = card.data("variants");

    if (!variants || !colorId) return;

    let selected = variants.find((v) => v.id == colorId);
    if (!selected) return;

    let basePrice = parseFloat(selected.price);
    let discountPrice = parseFloat(selected.discount_price);

    // Logic:
    // If discount is 0 or null, or discount price is equal to or greater than base price
    // then show only the base price
    if (discountPrice > 0 && discountPrice < basePrice) {
        // Discount exists
        card.find(".variant-price").text("৳" + discountPrice);
        card.find(".main-price")
            .text("৳" + basePrice)
            .removeClass("d-none");

        // Show save amount box
        card.find(".save-amount-box").removeClass("d-none");
        card.find(".save-amount").text("Save ৳" + (basePrice - discountPrice));
    } else {
        // No discount (0, null, or invalid)
        card.find(".variant-price").text("৳" + basePrice);
        card.find(".main-price").addClass("d-none");

        // Hide save amount box
        card.find(".save-amount-box").addClass("d-none");
    }

    // Stock check
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

// Cart function (will remain the same as before)
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
        },
    });
}
