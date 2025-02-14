let cart = [];

//* selectors
const selectors = {
  products: document.querySelector(".products"),
  cartBtn: document.querySelector(".cart-btn"),
  cartQty: document.querySelector(".cart-qty"),
  cartClose: document.querySelector(".cart-close"),
  cart: document.querySelector(".cart"),
  cartOverlay: document.querySelector(".cart-overlay"),
  cartClear: document.querySelector(".cart-clear"),
  cartBody: document.querySelector(".cart-body"),
  cartTotal: document.querySelector(".cart-total"),
  checkoutBtn: document.querySelector(".checkout"),
  hamburger: document.querySelector(".hamburger"),
  navMenu: document.querySelector(".nav-menu"),
};

//* event listeners
const setupListeners = () => {
  document.addEventListener("DOMContentLoaded", initStore);

  if (selectors.products) {
    selectors.products.addEventListener("click", addToCart);
  }
  if (selectors.checkoutBtn) {
    selectors.checkoutBtn.addEventListener("click", goToCheckout);
  }
  if (selectors.cartBtn) {
    selectors.cartBtn.addEventListener("click", showCart);
  }
  if (selectors.cartOverlay) {
    selectors.cartOverlay.addEventListener("click", hideCart);
  }
  if (selectors.cartClose) {
    selectors.cartClose.addEventListener("click", hideCart);
  }
  if (selectors.cartBody) {
    selectors.cartBody.addEventListener("click", updateCart);
  }
  if (selectors.cartClear) {
    selectors.cartClear.addEventListener("click", clearCart);
  }

  if (selectors.hamburger && selectors.navMenu) {
    selectors.hamburger.addEventListener("click", toggleMenu);
  }
};

//* event handlers
const initStore = () => {
  loadCart();
  renderCart();
};

const showCart = () => {
  selectors.cart.classList.add("show");
  selectors.cartOverlay.classList.add("show");
};

const hideCart = () => {
  selectors.cart.classList.remove("show");
  selectors.cartOverlay.classList.remove("show");
};

const clearCart = () => {
  cart = [];
  saveCart();
  renderCart();
  setTimeout(hideCart, 500);
};

const goToCheckout = () => {
  localStorage.setItem("cart-data", JSON.stringify(cart));
  clearCart();
  window.location.href = "checkout.php";
};

const addToCart = (e) => {
  if (e.target.hasAttribute("data-id")) {
    const id = parseInt(e.target.dataset.id);
    const productElement = e.target.closest(".product");
    const title = productElement.querySelector("h3").textContent;
    const price = parseFloat(
      productElement.querySelector("p").textContent.replace("Rp", "")
    );
    const image = productElement.querySelector("img").src;

    const inCart = cart.find((x) => x.id === id);

    if (inCart) {
      Swal.fire({
        icon: "warning",
        title: "Sorry!",
        text: "Item is already in cart.",
        showConfirmButton: false,
        timer: 2000,
      });
      return;
    }

    cart.push({ id, title, price, image, qty: 1 });
    saveCart();
    renderCart();
    showCart();
  }
};

const removeFromCart = (id) => {
  cart = cart.filter((x) => x.id !== id);

  // if the last item is remove, close the cart
  cart.length === 0 && setTimeout(hideCart, 500);

  renderCart();
};

const increaseQty = (id) => {
  const item = cart.find((x) => x.id === id);
  if (!item) return;

  item.qty++;
};

const decreaseQty = (id) => {
  const item = cart.find((x) => x.id === id);
  if (!item) return;

  item.qty--;

  if (item.qty === 0) removeFromCart(id);
};

const updateCart = (e) => {
  if (e.target.hasAttribute("data-btn")) {
    const cartItem = e.target.closest(".cart-item");
    const id = parseInt(cartItem.dataset.id);
    const btn = e.target.dataset.btn;

    btn === "incr" && increaseQty(id);
    btn === "decr" && decreaseQty(id);

    saveCart();
    renderCart();
  }
};

const saveCart = () => {
  localStorage.setItem("online-store", JSON.stringify(cart));
};

const loadCart = () => {
  cart = JSON.parse(localStorage.getItem("online-store")) || [];
};

//* render functions

const renderCart = () => {
  // show cart qty in navbar
  const cartQty = cart.reduce((sum, item) => {
    return sum + item.qty;
  }, 0);

  selectors.cartQty.textContent = cartQty;
  selectors.cartQty.classList.toggle("visible", cartQty);

  // show cart total
  selectors.cartTotal.textContent = calculateTotal().format();

  // show empty cart
  if (cart.length === 0) {
    selectors.cartBody.innerHTML =
      '<div class="cart-empty">Your cart is empty.</div>';
    return;
  }

  // show cart items
  selectors.cartBody.innerHTML = cart
    .map(({ id, title, price, image, qty }) => {
      const amount = price * qty;

      return `
        <div class="cart-item" data-id="${id}">
          <img src="${image}" alt="${title}" />
          <div class="cart-item-detail">
            <h3>${title}</h3>
            <h5>${price.format()}</h5>
            <div class="cart-item-amount">
              <i class="bi bi-dash-lg" data-btn="decr"></i>
              <span class="qty">${qty}</span>
              <i class="bi bi-plus-lg" data-btn="incr"></i>

              <span class="cart-item-price">
                ${amount.format()}
              </span>
            </div>
          </div>
        </div>`;
    })
    .join("");
};

//* helper functions

const calculateTotal = () => {
  return cart
    .map(({ price, qty }) => price * qty)
    .reduce((sum, number) => sum + number, 0);
};

Number.prototype.format = function () {
  return this.toLocaleString("id-ID", {
    style: "currency",
    currency: "IDR",
  });
};
const toggleMenu = () => {
  selectors.hamburger.classList.toggle("active");
  selectors.navMenu.classList.toggle("show");
  if (selectors.navMenu.classList.contains("show")) {
    selectors.cartBtn.style.display = "none";
  } else {
    selectors.cartBtn.style.display = "block";
  }
  hideCart();
};

setupListeners();
