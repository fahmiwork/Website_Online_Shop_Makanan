// * selectors
const selectors = {
  checkoutItems: document.querySelector(".checkout-items"),
  checkoutTotal: document.querySelector(".checkout-total"),
  placeOrderBtn: document.querySelector("#placeOrderBtn"),
};

// * load cart from localStorage
const loadCart = () => {
  return JSON.parse(localStorage.getItem("cart-data")) || [];
};

// * save cart to localStorage
const saveCart = (cart) => {
  localStorage.setItem("cart-data", JSON.stringify(cart));
};

// * render checkout items
const renderCheckout = () => {
  const cart = loadCart();

  if (cart.length === 0) {
    selectors.checkoutItems.innerHTML =
      '<div class="empty-cart">Your cart is empty.</div>';
    selectors.checkoutTotal.textContent = "IDR 0";
    return;
  }

  // render items
  selectors.checkoutItems.innerHTML = cart
    .map(({ id, title, price, qty, image }) => {
      const total = price * qty;
      return `
        <div class="checkout-item" data-id="${id}">
          <h3>${title}</h3>
          <div class="checkout-detail">
          <img src="${image}" alt="${title}" />
          <p>${price.format()} x ${qty} = ${total.format()}</p>
          </div>
          <div class="checkout-actions">
            <button class="btn-decrease" data-btn="decr">-</button>
            <span class="qty">${qty}</span>
            <button class="btn-increase" data-btn="incr">+</button>
            <button class="btn-remove" data-btn="remove">Remove</button>
          </div>
        </div>
      `;
    })
    .join("");

  // calculate total
  const total = cart.reduce((sum, { price, qty }) => sum + price * qty, 0);
  selectors.checkoutTotal.textContent = total.format();
};

// * update item quantity
const updateItemQty = (id, action) => {
  let cart = loadCart();
  const item = cart.find((item) => item.id === id);

  if (!item) return;

  if (action === "incr") {
    item.qty++;
  } else if (action === "decr") {
    item.qty--;
    if (item.qty === 0) cart = cart.filter((item) => item.id !== id);
  }

  saveCart(cart);
  renderCheckout();
};

// * remove item
const removeItem = (id) => {
  let cart = loadCart();
  cart = cart.filter((item) => item.id !== id);
  saveCart(cart);
  renderCheckout();
};

// * handle item actions
const handleItemActions = (e) => {
  const button = e.target;
  const action = button.dataset.btn;

  if (!action) return;

  const itemElement = button.closest(".checkout-item");
  const id = parseInt(itemElement.dataset.id);

  if (action === "incr" || action === "decr") {
    updateItemQty(id, action);
  } else if (action === "remove") {
    removeItem(id);
  }
};

// * handle place order
const placeOrder = async () => {
  const cart = loadCart();
  const total = cart.reduce((sum, { price, qty }) => sum + price * qty, 0);

  if (cart.length === 0) {
    alert("Your cart is empty!");
    return;
  }

  const formData = new FormData(document.querySelector("form"));
  const customerDetails = {
    first_name: formData.get("first_name"),
    notes: formData.get("notes"),
    email: formData.get("email"),
    phone: formData.get("phone"),
    address: formData.get("address"),
    id_user: formData.get("id_user"),
  };

  try {
    const response = await fetch(
      "http://localhost/pemesanan_makanan/php/placeOrder.php",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          items: cart,
          total,
          customer: customerDetails,
        }),
      }
    );

    const result = await response.json();

    if (!response.ok) {
      throw new Error(result.message || "Failed to place order, koneksi gagal");
    }

    // Redirect to Midtrans payment page
    window.snap.pay(result.snapToken, {
      onSuccess: function (result) {
        Swal.fire({
          icon: "success",
          title: "BERHASIL",
          text: "yes",
          showConfirmButton: true,
          timer: 2000,
        }).then(() => {
          localStorage.removeItem("cart-data");
          window.location.href = "pesanan.php"; // Arahkan ke halaman checkout
        });
      },
      onPending: function (result) {
        Swal.fire({
          icon: "warning",
          title: "PENDING",
          text: "Waiting for your payment!",
          showConfirmButton: true,
          timer: 2000,
        }).then(() => {
          localStorage.removeItem("cart-data");
          window.location.href = "pesanan.php"; // Arahkan ke halaman
        });
      },
      onError: function (result) {
        Swal.fire({
          icon: "warning",
          title: "PENDING",
          text: "Payment failed!",
          showConfirmButton: true,
          timer: 2000,
        }).then(() => {
          localStorage.removeItem("cart-data");
          window.location.href = "checkout.php"; // Arahkan ke halaman checkout
        });
      },
      onClose: function () {
        Swal.fire({
          icon: "warning",
          title: "PENDING",
          text: "You closed the popup without finishing the payment",
          showConfirmButton: true,
          timer: 2000,
        }).then(() => {
          localStorage.removeItem("cart-data");
          window.location.href = "pesanan.php"; // Arahkan ke halaman checkout
        });
      },
    });
  } catch (error) {
    alert("Failed to place order: " + error.message);
  }
};

// * helper to format currency
Number.prototype.format = function () {
  return this.toLocaleString("id-ID", {
    style: "currency",
    currency: "IDR",
  });
};

// * initialize
document.addEventListener("DOMContentLoaded", () => {
  if (
    selectors.checkoutItems &&
    selectors.checkoutTotal &&
    selectors.placeOrderBtn
  ) {
    renderCheckout();
    selectors.checkoutItems.addEventListener("click", handleItemActions);
    selectors.placeOrderBtn.addEventListener("click", placeOrder);
  } else {
    console.error("One or more selectors are missing in the DOM.");
  }
});

document.addEventListener("DOMContentLoaded", () => {
  renderCheckout();
  selectors.checkoutItems.addEventListener("click", handleItemActions);

  // Tambahkan event listener untuk form submission
  const form = document.querySelector("form");
  form.addEventListener("submit", async (e) => {
    e.preventDefault(); // Hentikan perilaku default form submission
    await placeOrder(); // Jalankan fungsi placeOrder
  });
});

document.addEventListener("DOMContentLoaded", () => {
  const hamburger = document.querySelector(".hamburger");
  const cartBtn = document.querySelector(".cart-btn"); // Ambil tombol cart
  const cart = document.querySelector(".cart");
  const navMenu = document.querySelector(".nav-menu");

  hamburger.addEventListener("click", () => {
    // Toggle kelas 'active' untuk animasi hamburger
    hamburger.classList.toggle("active");

    // Toggle kelas 'show' untuk menampilkan atau menyembunyikan menu
    navMenu.classList.toggle("show");

    // Jika hamburger menu aktif, sembunyikan tombol cart
    if (navMenu.classList.contains("show")) {
      cartBtn.style.display = "none"; // Sembunyikan cart
    } else {
      cartBtn.style.display = "block"; // Tampilkan cart kembali
    }

    // Tutup cart jika terbuka
    cart.classList.remove("show");
  });
});
