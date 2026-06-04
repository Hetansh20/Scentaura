document.addEventListener("DOMContentLoaded", function () {
  // Get all elements
  const decreaseButtons = document.querySelectorAll(".decrease");
  const increaseButtons = document.querySelectorAll(".increase");
  const removeButtons = document.querySelectorAll(".remove-item");
  const saveButtons = document.querySelectorAll(".save-for-later");
  const quantityInputs = document.querySelectorAll(".item-quantity input");
  const promoButton = document.querySelector(".promo-code button");
  const checkoutButton = document.querySelector(".checkout-btn");
  const continueShoppingButton = document.querySelector(".continue-shopping");
  const cartCount = document.getElementById("cart-count");

  // Handle decrease quantity
  decreaseButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const input = this.nextElementSibling;
      let value = parseInt(input.value);
      if (value > 1) {
        input.value = value - 1;
        updateCartSummary();
      }
    });
  });

  // Handle increase quantity
  increaseButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const input = this.previousElementSibling;
      let value = parseInt(input.value);
      if (value < 10) {
        input.value = value + 1;
        updateCartSummary();
      }
    });
  });

  // Handle quantity input changes
  quantityInputs.forEach((input) => {
    input.addEventListener("change", function () {
      // Ensure the value is between 1 and 10
      if (this.value < 1) this.value = 1;
      if (this.value > 10) this.value = 10;
      updateCartSummary();
    });
  });

  // Handle remove item
  removeButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const item = this.closest(".cart-item");
      item.classList.add("fade-out");

      // Add animation effect
      setTimeout(() => {
        item.remove();
        updateCartCount();
        updateCartSummary();

        // Check if cart is empty
        if (document.querySelectorAll(".cart-item").length === 0) {
          showEmptyCart();
        }
      }, 300);
    });
  });

  // Handle save for later
  saveButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const icon = this.querySelector("i");

      // Toggle heart icon
      if (icon.classList.contains("fa-regular")) {
        icon.classList.remove("fa-regular");
        icon.classList.add("fa-solid");
        icon.style.color = "#ff6b6b";
      } else {
        icon.classList.remove("fa-solid");
        icon.classList.add("fa-regular");
        icon.style.color = "";
      }
    });
  });

  // Handle apply promo code
  promoButton.addEventListener("click", function () {
    const promoInput = document.querySelector(".promo-code input");

    if (promoInput.value.trim() !== "") {
      // Simple validation - in real site you'd check against a database
      if (promoInput.value.toLowerCase() === "welcome10") {
        // Apply 10% discount
        alert("Promo code applied! 10% discount added.");
        updateCartSummary(0.1); // 10% discount
      } else {
        alert("Invalid promo code. Please try again.");
      }
    } else {
      alert("Please enter a promo code.");
    }
  });

  // Handle checkout
  checkoutButton.addEventListener("click", function () {
    alert("Proceeding to checkout...");
    // In a real site, this would redirect to checkout page
  });

  // Handle continue shopping
  continueShoppingButton.addEventListener("click", function () {
    window.location.href = "index.php";
  });

  // Back to top functionality
  window.addEventListener("scroll", function () {
    const backToTopButton = document.getElementById("backToTop");

    if (window.scrollY > 300) {
      backToTopButton.classList.add("show");
    } else {
      backToTopButton.classList.remove("show");
    }
  });

  // Add to cart functionality for "You may also like" section
  const addToCartButtons = document.querySelectorAll(".also-like-item button");

  addToCartButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const product = this.closest(".also-like-item");
      const productName = product.querySelector("h3").textContent;

      alert(`${productName} added to your cart!`);

      // Update cart count
      let count = parseInt(cartCount.textContent);
      cartCount.textContent = count + 1;

      // Add animation effect to cart icon
      cartCount.style.transform = "scale(1.5)";
      setTimeout(() => {
        cartCount.style.transform = "scale(1)";
      }, 300);
    });
  });

  // Helper functions
  function updateCartSummary(discount = 0) {
    const items = document.querySelectorAll(".cart-item");
    let subtotal = 0;
    let itemCount = 0;

    // Calculate subtotal
    items.forEach((item) => {
      const price = parseFloat(
        item.querySelector(".item-price").textContent.replace("$", "")
      );
      const quantity = parseInt(
        item.querySelector(".item-quantity input").value
      );
      subtotal += price * quantity;
      itemCount += quantity;
    });

    // Apply discount if any
    const discountAmount = subtotal * discount;
    const shipping = 12.99;
    const tax = subtotal * 0.08; // Assuming 8% tax
    const total = subtotal - discountAmount + shipping + tax;

    // Update summary display
    document.querySelectorAll(
      ".summary-row"
    )[0].innerHTML = `<span>Subtotal (${itemCount} items)</span><span>$${subtotal.toFixed(
      2
    )}</span>`;

    // Add discount row if applicable
    if (discount > 0) {
      // Check if discount row exists
      const discountRow = document.querySelector(".discount-row");
      if (discountRow) {
        discountRow.innerHTML = `<span>Discount</span><span>-$${discountAmount.toFixed(
          2
        )}</span>`;
      } else {
        // Create discount row
        const newDiscountRow = document.createElement("div");
        newDiscountRow.className = "summary-row discount-row";
        newDiscountRow.innerHTML = `<span>Discount</span><span>-$${discountAmount.toFixed(
          2
        )}</span>`;

        // Insert after subtotal
        const subtotalRow = document.querySelectorAll(".summary-row")[0];
        subtotalRow.parentNode.insertBefore(
          newDiscountRow,
          subtotalRow.nextSibling
        );
      }
    }

    document.querySelectorAll(
      ".summary-row"
    )[1].innerHTML = `<span>Shipping</span><span>$${shipping.toFixed(
      2
    )}</span>`;
    document.querySelectorAll(
      ".summary-row"
    )[2].innerHTML = `<span>Tax</span><span>$${tax.toFixed(2)}</span>`;
    document.querySelector(
      ".summary-total"
    ).innerHTML = `<span>Total</span><span>$${total.toFixed(2)}</span>`;
  }

  function updateCartCount() {
    const items = document.querySelectorAll(".cart-item");
    cartCount.textContent = items.length;
  }

  function showEmptyCart() {
    const cartContainer = document.querySelector(".cart-container");

    // Create empty cart message
    const emptyCartHTML = `
        <div class="empty-cart">
            <i class="fa-solid fa-cart-shopping"></i>
            <h2>Your Cart is Empty</h2>
            <p>Looks like you haven't added any items to your cart yet.</p>
            <button onclick="window.location.href='index.php'" class="continue-shopping-btn">Continue Shopping</button>
        </div>
    `;

    // Replace cart content with empty cart message
    cartContainer.innerHTML = emptyCartHTML;

    // Hide the order summary section
    const orderSummary = document.querySelector(".order-summary");
    if (orderSummary) {
      orderSummary.style.display = "none";
    }
  }

  // Initialize cart summary on page load
  updateCartSummary();

  // Back to top button click handler
  document.getElementById("backToTop").addEventListener("click", function () {
    window.scrollTo({
      top: 0,
      behavior: "smooth",
    });
  });
});
