function readCartItems() {
  try {
    return JSON.parse(localStorage.getItem("mega-carrinho") || "[]");
  } catch {
    return [];
  }
}

function saveCartItems(items) {
  localStorage.setItem("mega-carrinho", JSON.stringify(items));
}

function renderCartLine(item) {
  const product = obterProduto(item.id);
  if (!product) return "";

  const sizeLabel = item.tamanho ? ` · Tamanho ${item.tamanho}` : "";

  return `
		<article class="cart-line" data-id="${product.id}" data-size="${
    item.tamanho || ""
  }">
			<a href="produto.html?id=${product.id}">
				<img src="${product.imagem}" alt="${product.nome}">
			</a>
			<div>
				<a href="produto.html?id=${product.id}"><h2>${product.nome}</h2></a>
				<p>${product.categoria}${sizeLabel}</p>
				<div class="quantity-control">
					<button data-cart-action="minus" aria-label="Diminuir quantidade">−</button>
					<output>${item.quantidade}</output>
					<button data-cart-action="plus" aria-label="Aumentar quantidade">+</button>
				</div>
				<button class="remove-item" data-cart-action="remove">Remover</button>
			</div>
			<span class="cart-line-price">${formatarPreco(
        product.preco * item.quantidade
      )}</span>
		</article>
	`;
}

function calculateSubtotal(items) {
  return items.reduce((subtotal, item) => {
    const product = obterProduto(item.id);
    return subtotal + (product ? product.preco * item.quantidade : 0);
  }, 0);
}

function renderCartSummary(subtotal) {
  return `
		<aside class="summary">
			<h2>Resumo do pedido</h2>
			<div class="summary-line"><span>Subtotal</span><span>${formatarPreco(
        subtotal
      )}</span></div>
			<div class="summary-line"><span>Entrega</span><span>A calcular</span></div>
			<div class="summary-line summary-total"><span>Total</span><span>${formatarPreco(
        subtotal
      )}</span></div>
			<button class="button button-dark" id="checkout-button">Finalizar pedido</button>
			<p class="notice" id="checkout-notice" aria-live="polite"></p>
		</aside>
	`;
}

function renderCart() {
  const container = document.querySelector("#cart-content");
  if (!container) return;

  const items = readCartItems();
  if (items.length === 0) {
    container.innerHTML = `
			<div class="empty-state">
				<h2>Seu carrinho está vazio</h2>
				<p>Descubra peças para acompanhar sua rotina.</p>
				<a class="button button-dark" href="index.html">Explorar coleção</a>
			</div>
		`;
    return;
  }

  const itemMarkup = items.map(renderCartLine).join("");
  const subtotal = calculateSubtotal(items);

  container.innerHTML = `
		<div class="cart-items">${itemMarkup}</div>
		${renderCartSummary(subtotal)}
	`;
}

function updateCartItem(button) {
  const line = button.closest(".cart-line");
  const productId = Number(line.dataset.id);
  const size = line.dataset.size;
  const action = button.dataset.cartAction;
  const items = readCartItems();
  const itemIndex = items.findIndex((item) => {
    return item.id === productId && (item.tamanho || "") === size;
  });

  if (itemIndex === -1) return;

  if (action === "remove") {
    items.splice(itemIndex, 1);
  } else if (action === "plus") {
    items[itemIndex].quantidade += 1;
  } else if (action === "minus") {
    items[itemIndex].quantidade = Math.max(1, items[itemIndex].quantidade - 1);
  }

  saveCartItems(items);
  renderCart();
  updateCartCount();
}

function handleCartClick(event) {
  const actionButton = event.target.closest("[data-cart-action]");
  if (actionButton) {
    updateCartItem(actionButton);
    return;
  }

  if (event.target.id === "checkout-button") {
    document.querySelector("#checkout-notice").textContent =
      "O checkout demonstrativo está pronto para integração com um meio de pagamento.";
  }
}

document.addEventListener("click", handleCartClick);
document.addEventListener("DOMContentLoaded", renderCart);
