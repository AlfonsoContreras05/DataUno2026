const navToggle = document.querySelector('.nav-toggle');
const navMenu = document.querySelector('[data-nav-menu]');

if (navToggle && navMenu) {
  navToggle.addEventListener('click', () => {
    const isOpen = navMenu.classList.toggle('open');
    navToggle.setAttribute('aria-expanded', String(isOpen));
  });
}

const revealItems = document.querySelectorAll('.reveal');
const observer = new IntersectionObserver((entries) => {
  entries.forEach((entry) => {
    if (entry.isIntersecting) {
      entry.target.classList.add('visible');
      observer.unobserve(entry.target);
    }
  });
}, { threshold: 0.12 });

revealItems.forEach((item) => observer.observe(item));

// Filtros de catálogo: funcionan para servicios antiguos y productos nuevos.
const filterButtons = document.querySelectorAll('.filter-btn');
const catalogCards = document.querySelectorAll('[data-category]');
const productSearch = document.querySelector('[data-product-search]');
let activeCategory = 'todos';

function applyCatalogFilters() {
  const searchValue = productSearch ? productSearch.value.trim().toLowerCase() : '';

  catalogCards.forEach((card) => {
    const categoryMatch = activeCategory === 'todos' || card.dataset.category === activeCategory;
    const searchText = card.dataset.search || card.textContent.toLowerCase();
    const searchMatch = !searchValue || searchText.includes(searchValue);
    card.classList.toggle('hidden', !(categoryMatch && searchMatch));
  });
}

filterButtons.forEach((button) => {
  button.addEventListener('click', () => {
    activeCategory = button.dataset.filter;
    filterButtons.forEach((btn) => btn.classList.remove('active'));
    button.classList.add('active');
    applyCatalogFilters();
  });
});

if (productSearch) {
  productSearch.addEventListener('input', applyCatalogFilters);
}

// Carrito DataUno: cotización de productos por WhatsApp.
const CART_KEY = 'datauno_product_cart_v1';
const cartDrawer = document.querySelector('[data-cart-drawer]');
const cartOverlay = document.querySelector('[data-cart-overlay]');
const cartItemsEl = document.querySelector('[data-cart-items]');
const cartEmptyEl = document.querySelector('[data-cart-empty]');
const cartCountEls = document.querySelectorAll('[data-cart-count]');
const cartSendEl = document.querySelector('[data-cart-send]');
const cartOpenBtns = document.querySelectorAll('[data-cart-open]');
const cartCloseBtns = document.querySelectorAll('[data-cart-close]');
const cartClearBtn = document.querySelector('[data-cart-clear]');
const addCartBtns = document.querySelectorAll('[data-add-cart]');

let cart = [];

function loadCart() {
  try {
    cart = JSON.parse(localStorage.getItem(CART_KEY)) || [];
  } catch (error) {
    cart = [];
  }
}

function saveCart() {
  localStorage.setItem(CART_KEY, JSON.stringify(cart));
}

function getCartTotalQty() {
  return cart.reduce((sum, item) => sum + item.qty, 0);
}

function openCart() {
  if (!cartDrawer || !cartOverlay) return;
  cartDrawer.classList.add('open');
  cartDrawer.setAttribute('aria-hidden', 'false');
  cartOverlay.hidden = false;
  requestAnimationFrame(() => cartOverlay.classList.add('open'));
}

function closeCart() {
  if (!cartDrawer || !cartOverlay) return;
  cartDrawer.classList.remove('open');
  cartDrawer.setAttribute('aria-hidden', 'true');
  cartOverlay.classList.remove('open');
  setTimeout(() => {
    cartOverlay.hidden = true;
  }, 180);
}

function buildWhatsAppMessage() {
  const lines = [
    'Hola DataUno, quiero cotizar estos productos:',
    '',
    ...cart.map((item, index) => `${index + 1}. ${item.name} x${item.qty} (${item.category}) - ${item.price}`),
    '',
    'Por favor indíquenme disponibilidad, compatibilidad e instalación si corresponde.'
  ];

  return lines.join('\n');
}

function updateCartSendLink() {
  if (!cartSendEl || !cartDrawer) return;

  if (!cart.length) {
    cartSendEl.href = '#';
    cartSendEl.classList.add('disabled');
    return;
  }

  const whatsappBase = cartDrawer.dataset.whatsapp || 'https://wa.me/56994392133';
  cartSendEl.href = `${whatsappBase}?text=${encodeURIComponent(buildWhatsAppMessage())}`;
  cartSendEl.classList.remove('disabled');
}

function renderCart() {
  if (!cartItemsEl) return;

  cartCountEls.forEach((el) => {
    el.textContent = String(getCartTotalQty());
  });

  if (!cart.length) {
    cartItemsEl.innerHTML = '';
    if (cartEmptyEl) cartEmptyEl.hidden = false;
    updateCartSendLink();
    return;
  }

  if (cartEmptyEl) cartEmptyEl.hidden = true;

  cartItemsEl.innerHTML = cart.map((item) => `
    <article class="cart-item" data-cart-id="${item.id}">
      <div>
        <strong>${item.name}</strong>
        <span>${item.category} · ${item.price}</span>
      </div>
      <div class="cart-qty">
        <button type="button" data-cart-dec="${item.id}" aria-label="Restar unidad">−</button>
        <b>${item.qty}</b>
        <button type="button" data-cart-inc="${item.id}" aria-label="Sumar unidad">+</button>
      </div>
      <button class="cart-remove" type="button" data-cart-remove="${item.id}">Quitar</button>
    </article>
  `).join('');

  updateCartSendLink();
}

function addToCart(product) {
  const existing = cart.find((item) => item.id === product.id);
  if (existing) {
    existing.qty += 1;
  } else {
    cart.push({ ...product, qty: 1 });
  }
  saveCart();
  renderCart();
  openCart();
}

function changeQty(id, delta) {
  const item = cart.find((product) => product.id === id);
  if (!item) return;

  item.qty += delta;
  if (item.qty <= 0) {
    cart = cart.filter((product) => product.id !== id);
  }

  saveCart();
  renderCart();
}

addCartBtns.forEach((button) => {
  button.addEventListener('click', () => {
    addToCart({
      id: button.dataset.id,
      name: button.dataset.name,
      category: button.dataset.category,
      price: button.dataset.price,
    });
  });
});

if (cartItemsEl) {
  cartItemsEl.addEventListener('click', (event) => {
    const target = event.target;
    if (!(target instanceof HTMLElement)) return;

    const incId = target.dataset.cartInc;
    const decId = target.dataset.cartDec;
    const removeId = target.dataset.cartRemove;

    if (incId) changeQty(incId, 1);
    if (decId) changeQty(decId, -1);
    if (removeId) {
      cart = cart.filter((item) => item.id !== removeId);
      saveCart();
      renderCart();
    }
  });
}

cartOpenBtns.forEach((button) => button.addEventListener('click', openCart));
cartCloseBtns.forEach((button) => button.addEventListener('click', closeCart));
if (cartOverlay) cartOverlay.addEventListener('click', closeCart);

if (cartClearBtn) {
  cartClearBtn.addEventListener('click', () => {
    cart = [];
    saveCart();
    renderCart();
  });
}

if (cartSendEl) {
  cartSendEl.addEventListener('click', (event) => {
    if (!cart.length) event.preventDefault();
  });
}

loadCart();
renderCart();
