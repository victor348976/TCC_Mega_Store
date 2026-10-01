const icons = {
  search:
    '<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.8"/><path d="m16 16 5 5"/></svg>',
  bag: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 8h16l-1 13H5L4 8Z"/><path d="M9 9V6a3 3 0 0 1 6 0v3"/></svg>',
  heart:
    '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.8 8.8c0 5.5-8.8 10.5-8.8 10.5S3.2 14.3 3.2 8.8A4.3 4.3 0 0 1 12 6.6a4.3 4.3 0 0 1 8.8 2.2Z"/></svg>',
  user: '<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="3.4"/><path d="M4.5 21a7.5 7.5 0 0 1 15 0"/></svg>',
  menu: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 7h18M3 12h18M3 17h18"/></svg>',
};

const headerTemplate = `
	<div class="announcement">Frete grátis em pedidos acima de R$ 499 · Uma seleção pensada para durar</div>
	<header class="site-header">
		<div class="header-main">
			<button class="icon-button mobile-toggle" aria-label="Abrir menu" aria-expanded="false">${icons.menu}</button>
			<a class="brand" href="index.html" aria-label="Mega Store, início">MEGA<span>STORE</span></a>
			<nav class="primary-nav" aria-label="Navegação principal">
				<a href="feminino.html">Feminino</a>
				<a href="masculino.html">Masculino</a>
				<a href="infantil.html">Infantil</a>
				<a href="calcados.html">Calçados</a>
			</nav>
			<div class="header-tools">
				<button class="icon-button search-toggle" aria-label="Pesquisar">${icons.search}</button>
				<a class="icon-button" href="index.html#favoritos" aria-label="Favoritos">${icons.heart}</a>
				<a class="icon-button cart-link" href="carrinho.html" aria-label="Carrinho">${icons.bag}<span class="cart-count">0</span></a>
				<a class="icon-button" href="login.html" aria-label="Minha conta">${icons.user}</a>
				<a class="account-link" href="cadastro.html">Cadastre-se</a>
			</div>
		</div>
		<nav class="mobile-nav" aria-label="Navegação móvel">
			<a href="feminino.html">Feminino</a>
			<a href="masculino.html">Masculino</a>
			<a href="infantil.html">Infantil</a>
			<a href="calcados.html">Calçados</a>
			<a href="login.html">Minha conta</a>
			<a href="cadastro.html">Cadastre-se</a>
		</nav>
		<div class="search-panel">
			<form class="search-inner" role="search">
				<input type="search" placeholder="Pesquisar produtos..." aria-label="Pesquisar produtos">
				<button aria-label="Buscar">${icons.search}</button>
			</form>
			<div class="search-results" aria-live="polite"></div>
		</div>
	</header>
`;

const footerTemplate = `
	<footer class="site-footer">
		<div class="footer-grid">
			<div class="footer-brand-block">
				<a class="footer-brand" href="index.html">MEGA<span>STORE</span></a>
				<p>A arte da trama, a elegância do vestir.</p>
			</div>
			<div class="footer-column">
				<h3>Navegação</h3>
				<a href="index.html#colecoes">Coleções</a>
				<a href="index.html#tecidos">Tecidos</a>
				<a href="index.html#destaques">Destaques</a>
				<a href="index.html#sobre">Sobre nós</a>
			</div>
			<div class="footer-column">
				<h3>Atendimento</h3>
				<a id="faq" href="index.html#faq">Perguntas frequentes</a>
				<a id="entregas" href="index.html#entregas">Entregas</a>
				<a id="trocas" href="index.html#trocas">Trocas &amp; devoluções</a>
				<a id="contato" href="index.html#contato">Contato</a>
			</div>
			<div class="footer-column">
				<h3>Social</h3>
				<a href="https://instagram.com" target="_blank" rel="noreferrer">Instagram</a>
				<a href="https://pinterest.com" target="_blank" rel="noreferrer">Pinterest</a>
				<a href="https://linkedin.com" target="_blank" rel="noreferrer">LinkedIn</a>
			</div>
		</div>
		<div class="footer-bottom">
			<span>© 2026 Mega Store. Todos os direitos reservados.</span>
			<span>Feito para vestir o tempo.</span>
		</div>
	</footer>
`;

function loadSharedLayout() {
  document.querySelectorAll("[data-site-header]").forEach((element) => {
    element.innerHTML = headerTemplate;
  });

  document.querySelectorAll("[data-site-footer]").forEach((element) => {
    element.innerHTML = footerTemplate;
  });
}

function readCart() {
  try {
    return JSON.parse(localStorage.getItem("mega-carrinho") || "[]");
  } catch {
    return [];
  }
}

function readFavorites() {
  try {
    return JSON.parse(localStorage.getItem("mega-favoritos") || "[]");
  } catch {
    return [];
  }
}

function updateCartCount() {
  const itemCount = readCart().reduce(
    (total, item) => total + item.quantidade,
    0
  );

  document.querySelectorAll(".cart-count").forEach((element) => {
    element.textContent = itemCount;
  });
}

function renderProductCard(product) {
  return `
		<article class="product-card">
			<a class="product-image" href="produto.html?id=${product.id}">
				<img src="${product.imagem}" alt="${product.nome}" loading="lazy">
			</a>
			<button class="product-wish" type="button" data-wish="${
        product.id
      }" aria-label="Adicionar ${product.nome} aos favoritos">
				${icons.heart}
			</button>
			<div class="product-info">
				<span class="product-category">${product.categoria}</span>
				<a href="produto.html?id=${product.id}"><h3 class="product-name">${
    product.nome
  }</h3></a>
				<span class="product-price">${formatarPreco(product.preco)}</span>
			</div>
		</article>
	`;
}

function renderProductGrid(selector, productList) {
  const grid = document.querySelector(selector);
  if (!grid) return;

  const count = document.querySelector("#product-count");
  if (count) {
    const unit = productList.length === 1 ? "peça" : "peças";
    count.textContent = `${productList.length} ${unit}`;
  }

  if (productList.length === 0) {
    grid.innerHTML = '<p class="empty-products">Nenhum produto disponível.</p>';
    return;
  }

  grid.innerHTML = productList.map(renderProductCard).join("");
  updateFavoriteButtons();
}

function updateFavoriteButtons() {
  const favoriteIds = readFavorites();

  document.querySelectorAll("[data-wish]").forEach((button) => {
    const isFavorite = favoriteIds.includes(Number(button.dataset.wish));
    button.classList.toggle("active", isFavorite);
    button.setAttribute("aria-pressed", String(isFavorite));
  });
}

function renderSearchResults(term, resultsContainer) {
  const normalizedTerm = term.trim().toLocaleLowerCase("pt-BR");
  const matches = normalizedTerm
    ? produtos
        .filter((product) => {
          const searchableText = `${product.nome} ${product.categoria}`;
          return searchableText
            .toLocaleLowerCase("pt-BR")
            .includes(normalizedTerm);
        })
        .slice(0, 8)
    : [];

  resultsContainer.innerHTML = matches
    .map(
      (product) => `
		<a class="search-result" href="produto.html?id=${product.id}">
			<img src="${product.imagem}" alt="">
			<span>${product.nome}<small>${formatarPreco(product.preco)}</small></span>
		</a>
	`
    )
    .join("");
}

function setupSearch() {
  const toggle = document.querySelector(".search-toggle");
  const panel = document.querySelector(".search-panel");
  if (!toggle || !panel) return;

  const input = panel.querySelector("input");
  const results = panel.querySelector(".search-results");

  toggle.addEventListener("click", () => {
    panel.classList.toggle("open");
    if (panel.classList.contains("open")) input.focus();
  });

  panel.querySelector("form").addEventListener("submit", (event) => {
    event.preventDefault();
  });

  input.addEventListener("input", () =>
    renderSearchResults(input.value, results)
  );

  document.addEventListener("keydown", (event) => {
    if (event.key === "Escape") panel.classList.remove("open");
  });
}

function setupMobileMenu() {
  const toggle = document.querySelector(".mobile-toggle");
  const menu = document.querySelector(".mobile-nav");
  if (!toggle || !menu) return;

  toggle.addEventListener("click", () => {
    const isOpen = menu.classList.toggle("open");
    toggle.setAttribute("aria-expanded", String(isOpen));
    document.body.classList.toggle("no-scroll", isOpen);
  });
}

function setupFavorites() {
  document.addEventListener("click", (event) => {
    const button = event.target.closest("[data-wish]");
    if (!button) return;

    const productId = Number(button.dataset.wish);
    const favoriteIds = readFavorites();
    const nextFavorites = favoriteIds.includes(productId)
      ? favoriteIds.filter((id) => id !== productId)
      : [...favoriteIds, productId];

    localStorage.setItem("mega-favoritos", JSON.stringify(nextFavorites));
    updateFavoriteButtons();
  });
}

function productsForPage(page) {
  const categories = {
    feminino: "Feminino",
    masculino: "Masculino",
    infantil: "Infantil",
    calcados: "Calçados",
  };

  return produtos.filter((product) => product.categoria === categories[page]);
}

function renderHomeProducts() {
  const isFavoritesPage = window.location.hash === "#favoritos";

  if (!isFavoritesPage) {
    renderProductGrid("#home-products", produtos.slice(0, 8));
    return;
  }

  document.querySelector("#featured-title").textContent = "Favoritos";
  document.querySelector("#featured-description").textContent =
    "Peças que você guardou para rever.";

  const favoriteIds = readFavorites();
  const favoriteProducts = produtos.filter((product) =>
    favoriteIds.includes(product.id)
  );
  renderProductGrid("#home-products", favoriteProducts);
}

function initializePage() {
  loadSharedLayout();
  localStorage.removeItem("mega-carrinho");
  localStorage.removeItem("mega-favoritos");
  updateCartCount();
  setupSearch();
  setupMobileMenu();
  setupFavorites();

  const page = document.body.dataset.page;
  if (page === "home") renderHomeProducts();
  if (["feminino", "masculino", "infantil", "calcados"].includes(page)) {
    renderProductGrid("#category-products", productsForPage(page));
  }
}

document.addEventListener("DOMContentLoaded", initializePage);
