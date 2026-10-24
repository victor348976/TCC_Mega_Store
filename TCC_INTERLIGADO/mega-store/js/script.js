const icons = {
  search: '<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.8"/><path d="m16 16 5 5"/></svg>',
  bag: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 8h16l-1 13H5L4 8Z"/><path d="M9 9V6a3 3 0 0 1 6 0v3"/></svg>',
  heart: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.8 8.8c0 5.5-8.8 10.5-8.8 10.5S3.2 14.3 3.2 8.8A4.3 4.3 0 0 1 12 6.6a4.3 4.3 0 0 1 8.8 2.2Z"/></svg>',
  user: '<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="3.4"/><path d="M4.5 21a7.5 7.5 0 0 1 15 0"/></svg>',
  menu: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 7h18M3 12h18M3 17h18"/></svg>',
};

const routes = [
  ["Feminino", "feminino"],
  ["Masculino", "masculino"],
  ["Infantil", "infantil"],
  ["Calçados", "calcados"],
];

const headerTemplate = `
  <header id="main-header" class="header">
    <div class="header__inner">
      <a class="header__logo" href="#/" aria-label="Mega Store, início">
        <span class="header__logo-text">MEGA</span><span class="header__logo-sub">STORE</span>
      </a>
      <nav class="header__nav" aria-label="Navegação principal">
        <ul class="header__nav-list">
          ${routes.map(([name, slug]) => `<li><a class="header__nav-link" href="#/categoria/${slug}">${name}</a></li>`).join("")}
        </ul>
      </nav>
      <div class="header__icons">
        <button class="header__icon-btn search-toggle" aria-label="Pesquisar" aria-expanded="false">${icons.search}</button>
        <a class="header__icon-btn" href="#/favoritos" aria-label="Favoritos">${icons.heart}</a>
        <a class="header__icon-btn cart-link" href="#/carrinho" aria-label="Carrinho">${icons.bag}<span class="header__cart-count cart-count">0</span></a>
        <a class="header__icon-btn" href="#/conta" aria-label="Minha conta">${icons.user}</a>
        <a class="header__account-link" href="#/cadastro">Criar conta</a>
        <button class="header__hamburger mobile-toggle" aria-label="Abrir menu" aria-expanded="false">${icons.menu}</button>
      </nav>
    </div>
    <nav class="mobile-nav" id="mobile-nav" aria-label="Navegação móvel">
      <ul class="mobile-nav__list">
        ${routes.map(([name, slug]) => `<li><a class="mobile-nav__link" href="#/categoria/${slug}">${name}</a></li>`).join("")}
        <li><a class="mobile-nav__link" href="#/favoritos">Favoritos</a></li>
        <li><a class="mobile-nav__link" href="#/carrinho">Carrinho</a></li>
        <li><a class="mobile-nav__link" href="#/conta">Minha conta</a></li>
        <li><a class="mobile-nav__link" href="#/cadastro">Criar conta</a></li>
      </ul>
    </nav>
    <div class="search-panel">
      <form class="search-inner" role="search">
        <input type="search" placeholder="Pesquisar produtos..." aria-label="Pesquisar produtos">
        <button aria-label="Buscar">${icons.search}</button>
      </form>
      <div class="search-results" aria-live="polite"></div>
    </div>
  </header>`;

const footerTemplate = `
  <footer class="footer">
    <div class="footer__inner">
      <div class="footer__brand">
        <a class="footer__logo" href="#/" aria-label="Mega Store, início"><span class="footer__logo-text">MEGA</span><span class="footer__logo-sub">STORE</span></a>
        <p class="footer__tagline">A Arte da Trama, a Elegância do Vestir.</p>
      </div>
      <div class="footer__links">
        <div class="footer__col"><h4 class="footer__col-title">Navegação</h4><ul>
          <li><a href="#/">Início</a></li>
          ${routes.map(([name, slug]) => `<li><a href="#/categoria/${slug}">${name}</a></li>`).join("")}
          <li><a href="#/favoritos">Favoritos</a></li>
        </ul></div>
        <div class="footer__col"><h4 class="footer__col-title">Atendimento</h4><ul>
          <li><a href="#/ajuda">Perguntas frequentes</a></li><li><a href="#/ajuda">Entregas</a></li>
          <li><a href="#/ajuda">Trocas &amp; devoluções</a></li><li><a href="#/ajuda">Contato</a></li>
        </ul></div>
        <div class="footer__col"><h4 class="footer__col-title">Social</h4><ul>
          <li><a href="https://instagram.com" target="_blank" rel="noreferrer">Instagram</a></li>
          <li><a href="https://pinterest.com" target="_blank" rel="noreferrer">Pinterest</a></li>
          <li><a href="https://linkedin.com" target="_blank" rel="noreferrer">LinkedIn</a></li>
        </ul></div>
      </div>
    </div>
    <div class="footer__bottom"><p>© 2026 Mega Store. Todos os direitos reservados.</p></div>
  </footer>`;

const categoryContent = {
  feminino: ["Feminino", "Caimentos precisos e texturas escolhidas para expressar sua própria elegância.", "../home/img/ready-to-wear.png"],
  masculino: ["Masculino", "Peças essenciais e detalhes precisos para uma elegância que acompanha seu ritmo.", "../home/img/product-blazer.png"],
  infantil: ["Infantil", "Conforto e liberdade para acompanhar cada momento de descoberta.", "../home/img/product-dress.png"],
  calcados: ["Calçados", "Modelos versáteis, conforto e acabamento para cada passo.", "../home/img/product-blazer.png"],
};

let appMain;
let homeMarkup;
let activeView = "";

function escapeHTML(value) {
  return String(value).replace(/[&<>"']/g, (character) => ({
    "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;",
  })[character]);
}

function formatarPreco(valor) {
  return Number(valor).toLocaleString("pt-BR", { style: "currency", currency: "BRL" });
}

function readStorage(key, fallback = []) {
  try {
    const value = JSON.parse(localStorage.getItem(key) || "null");
    return value === null ? fallback : value;
  } catch {
    return fallback;
  }
}

function writeStorage(key, value) {
  try {
    localStorage.setItem(key, JSON.stringify(value));
    return true;
  } catch {
    return false;
  }
}

function readCart() {
  return readStorage("mega-carrinho");
}

function readFavorites() {
  return readStorage("mega-favoritos");
}

function getProduct(id) {
  return produtos.find((product) => product.id === Number(id));
}

function updateCartCount() {
  const count = readCart().reduce((total, item) => total + Number(item.quantidade || 0), 0);
  document.querySelectorAll(".cart-count").forEach((element) => { element.textContent = count; });
}

function renderProductCard(product) {
  const favorite = readFavorites().includes(product.id);
  return `<article class="product-card">
    <a class="product-image" href="#/produto/${product.id}" aria-label="Ver ${escapeHTML(product.nome)}"><img src="${escapeHTML(product.imagem)}" alt="${escapeHTML(product.nome)}" loading="lazy"></a>
    <button class="product-wish${favorite ? " active" : ""}" type="button" data-wish="${product.id}" aria-label="Adicionar ${escapeHTML(product.nome)} aos favoritos" aria-pressed="${favorite}">${icons.heart}</button>
    <div class="product-info"><span class="product-category">${escapeHTML(product.categoria)}</span>
      <a href="#/produto/${product.id}"><h3 class="product-name">${escapeHTML(product.nome)}</h3></a>
      <span class="product-price">${formatarPreco(product.preco)}</span>
    </div>
  </article>`;
}

function renderProductGrid(products) {
  if (!products.length) return '<p class="empty-products">Nenhum produto encontrado. Experimente outro termo.</p>';
  return products.map(renderProductCard).join("");
}

function renderHome() {
  activeView = "home";
  document.title = "Mega Store | A arte da trama, a elegância do vestir";
  appMain.innerHTML = homeMarkup;
  const grid = document.querySelector("#home-products");
  if (grid) grid.innerHTML = renderProductGrid(produtos.slice(0, 8));
  updateCartCount();
}

function renderCategory(slug) {
  const [name, description, image] = categoryContent[slug] || [];
  if (!name) return renderNotFound();
  activeView = `categoria/${slug}`;
  document.title = `${name} | Mega Store`;
  const categoryProducts = produtos.filter((product) => product.categoria === name);
  appMain.innerHTML = `<section class="hero"><img src="${image}" alt="Coleção ${name}"/><div class="hero-copy"><span class="eyebrow">Mega Store · Coleção</span><h1>Coleção<br>${name}</h1><p>${description}</p><a class="button" href="#produtos">Explorar coleção</a></div></section>
    <section class="container" id="produtos"><div class="catalog-intro"><span class="eyebrow">A coleção</span><h1>${name}</h1><p>${description}</p></div>
    <div class="filter-row"><span id="product-count">${categoryProducts.length} peças</span><label for="sort-products">Ordenar</label><select id="sort-products"><option value="featured">Em destaque</option><option value="price-asc">Menor preço</option><option value="price-desc">Maior preço</option><option value="name">Nome</option></select></div>
    <div class="product-grid" id="category-products">${renderProductGrid(categoryProducts)}</div></section>`;
  document.querySelector("#sort-products").addEventListener("change", (event) => {
    const sorted = [...categoryProducts];
    if (event.target.value === "price-asc") sorted.sort((a, b) => a.preco - b.preco);
    if (event.target.value === "price-desc") sorted.sort((a, b) => b.preco - a.preco);
    if (event.target.value === "name") sorted.sort((a, b) => a.nome.localeCompare(b.nome, "pt-BR"));
    document.querySelector("#category-products").innerHTML = renderProductGrid(sorted);
  });
}

function renderProduct(product) {
  if (!product) return renderNotFound();
  activeView = `produto/${product.id}`;
  document.title = `${product.nome} | Mega Store`;
  appMain.innerHTML = `<section class="container product-detail" id="product-detail">
    <div class="detail-image"><img src="${escapeHTML(product.imagem)}" alt="${escapeHTML(product.nome)}"></div>
    <div class="detail-copy"><span class="product-category">${escapeHTML(product.categoria)}</span><h1>${escapeHTML(product.nome)}</h1>
      <p class="product-price">${formatarPreco(product.preco)}</p><p class="detail-description">${escapeHTML(product.descricao)}</p>
      <span class="field-label">Selecione o tamanho</span><div class="size-options" id="size-options">${product.tamanhos.map((size, index) => `<button type="button" class="size-option${index === 0 ? " selected" : ""}" data-size="${escapeHTML(size)}" aria-pressed="${index === 0}">${escapeHTML(size)}</button>`).join("")}</div>
      <span class="field-label">Quantidade</span><div class="quantity-control"><button type="button" id="quantity-minus" aria-label="Diminuir">−</button><output id="quantity-value">1</output><button type="button" id="quantity-plus" aria-label="Aumentar">+</button></div>
      <button class="button button-dark" id="add-to-cart" type="button">Adicionar ao carrinho</button><p class="notice" id="product-notice" aria-live="polite"></p>
      <a class="text-link" href="#/categoria/${slugForCategory(product.categoria)}">Continuar comprando</a>
    </div></section>`;
  let quantity = 1;
  const output = document.querySelector("#quantity-value");
  document.querySelector("#quantity-minus").addEventListener("click", () => { quantity = Math.max(1, quantity - 1); output.value = quantity; output.textContent = quantity; });
  document.querySelector("#quantity-plus").addEventListener("click", () => { quantity += 1; output.value = quantity; output.textContent = quantity; });
  document.querySelector("#size-options").addEventListener("click", (event) => {
    const option = event.target.closest("[data-size]");
    if (!option) return;
    document.querySelectorAll("#size-options .size-option").forEach((button) => {
      const selected = button === option;
      button.classList.toggle("selected", selected);
      button.setAttribute("aria-pressed", String(selected));
    });
  });
  document.querySelector("#add-to-cart").addEventListener("click", () => {
    const size = document.querySelector("#size-options .selected")?.dataset.size || "";
    const cart = readCart();
    const existing = cart.find((item) => item.id === product.id && item.tamanho === size);
    if (existing) existing.quantidade += quantity;
    else cart.push({ id: product.id, tamanho: size, quantidade: quantity });
    if (writeStorage("mega-carrinho", cart)) {
      updateCartCount();
      document.querySelector("#product-notice").textContent = "Produto adicionado. Ver carrinho";
      document.querySelector("#product-notice").innerHTML += ' · <a href="#/carrinho">Abrir carrinho</a>';
    } else document.querySelector("#product-notice").textContent = "Não foi possível salvar o carrinho neste navegador.";
  });
}

function slugForCategory(category) {
  return ({ Feminino: "feminino", Masculino: "masculino", Infantil: "infantil", "Calçados": "calcados" })[category] || "";
}

function renderFavorites() {
  activeView = "favoritos";
  document.title = "Favoritos | Mega Store";
  const favorites = readFavorites().map(getProduct).filter(Boolean);
  appMain.innerHTML = `<section class="container section"><div class="catalog-intro"><span class="eyebrow">Sua seleção</span><h1>Favoritos</h1><p>Peças que você guardou para rever.</p></div><div class="product-grid">${favorites.length ? renderProductGrid(favorites) : '<div class="empty-state"><h2>Sua lista está vazia</h2><p>Toque no coração de uma peça para guardá-la aqui.</p><a class="button button-dark" href="#/">Explorar coleção</a></div>'}</div></section>`;
}

function renderSearch(query) {
  activeView = "busca";
  const term = query.trim().toLocaleLowerCase("pt-BR");
  const results = term ? produtos.filter((product) => `${product.nome} ${product.categoria} ${product.descricao}`.toLocaleLowerCase("pt-BR").includes(term)) : [];
  document.title = `Busca${term ? `: ${query}` : ""} | Mega Store`;
  appMain.innerHTML = `<section class="container section"><div class="catalog-intro"><span class="eyebrow">Busca</span><h1>Resultados</h1><p>${term ? `${results.length} resultado(s) para “${escapeHTML(query)}”` : "Digite um termo no campo de busca para encontrar produtos."}</p></div><div class="product-grid">${term ? renderProductGrid(results) : ""}</div></section>`;
}

function renderCart() {
  activeView = "carrinho";
  document.title = "Carrinho | Mega Store";
  const items = readCart().filter((item) => getProduct(item.id));
  if (!items.length) {
    appMain.innerHTML = `<section class="container" style="padding-top:48px"><h1 class="cart-title">Seu carrinho</h1><div class="empty-state"><h2>Seu carrinho está vazio</h2><p>Descubra peças para acompanhar sua rotina.</p><a class="button button-dark" href="#/">Explorar coleção</a></div></section>`;
    updateCartCount();
    return;
  }
  const subtotal = items.reduce((sum, item) => sum + getProduct(item.id).preco * item.quantidade, 0);
  const lines = items.map((item) => {
    const product = getProduct(item.id);
    return `<article class="cart-line" data-id="${product.id}" data-size="${escapeHTML(item.tamanho || "")}">
      <a href="#/produto/${product.id}"><img src="${escapeHTML(product.imagem)}" alt="${escapeHTML(product.nome)}"></a>
      <div><a href="#/produto/${product.id}"><h2>${escapeHTML(product.nome)}</h2></a><p>${escapeHTML(product.categoria)}${item.tamanho ? ` · Tamanho ${escapeHTML(item.tamanho)}` : ""}</p>
        <div class="quantity-control"><button type="button" data-cart-action="minus" aria-label="Diminuir">−</button><output>${item.quantidade}</output><button type="button" data-cart-action="plus" aria-label="Aumentar">+</button></div>
        <button class="remove-item" type="button" data-cart-action="remove">Remover</button></div><span class="cart-line-price">${formatarPreco(product.preco * item.quantidade)}</span></article>`;
  }).join("");
  appMain.innerHTML = `<section class="container" style="padding-top:48px"><h1 class="cart-title">Seu carrinho</h1><div class="cart-layout"><div class="cart-items">${lines}</div><aside class="summary"><h2>Resumo do pedido</h2><div class="summary-line"><span>Subtotal</span><span>${formatarPreco(subtotal)}</span></div><div class="summary-line"><span>Entrega</span><span>${subtotal >= 499 ? "Grátis" : "A calcular"}</span></div><div class="summary-line summary-total"><span>Total estimado</span><span>${formatarPreco(subtotal)}</span></div><p class="notice">Checkout demonstrativo: nenhum pagamento será realizado.</p><button class="button button-dark" id="checkout-button" type="button">Confirmar pedido de demonstração</button><p class="notice" id="checkout-notice" aria-live="polite"></p></aside></div></section>`;
}

function renderAuth(kind) {
  activeView = kind;
  const register = kind === "cadastro";
  document.title = `${register ? "Criar conta" : "Entrar"} | Mega Store`;
  const user = readStorage("mega-sessao", null);
  if (kind === "conta" && user) {
    appMain.innerHTML = `<section class="auth-wrap"><section class="auth-panel"><span class="eyebrow auth-eyebrow">Minha conta</span><h1>Olá, ${escapeHTML(user.nome)}</h1><p>Sessão de demonstração ativa neste navegador.</p><button class="button button-dark" type="button" id="logout-button">Sair da conta</button><p class="auth-alternative"><a href="#/">Voltar à loja</a></p></section></section>`;
    return;
  }
  const isRegister = register;
  appMain.innerHTML = `<section class="auth-wrap"><section class="auth-panel"><span class="eyebrow auth-eyebrow">${isRegister ? "Faça parte" : "Bem-vinda(o) de volta"}</span><h1>${isRegister ? "Criar conta" : "Entrar"}</h1><p>${isRegister ? "Crie seu perfil de demonstração neste navegador." : "Acesse seu perfil de demonstração."}</p>
    <form id="auth-form" data-auth-kind="${isRegister ? "register" : "login"}">${isRegister ? `<div class="form-field"><label for="auth-name">Nome</label><input id="auth-name" name="nome" autocomplete="name" required></div>` : ""}
      <div class="form-field"><label for="auth-email">E-mail</label><input id="auth-email" name="email" type="email" autocomplete="email" required></div>
      <div class="form-field"><label for="auth-password">Senha</label><input id="auth-password" name="senha" type="password" minlength="8" autocomplete="${isRegister ? "new-password" : "current-password"}" required></div>
      ${isRegister ? `<div class="form-field"><label for="auth-confirm">Confirmar senha</label><input id="auth-confirm" name="confirmacao" type="password" minlength="8" required></div>` : ""}
      <button class="button button-dark" type="submit">${isRegister ? "Criar conta" : "Entrar"}</button><p class="form-message" id="auth-message" aria-live="polite"></p></form>
      <p class="auth-alternative">${isRegister ? `Já possui conta? <a href="#/login">Entrar</a>` : `Ainda não possui conta? <a href="#/cadastro">Cadastre-se</a>`}</p>
      <p class="notice">Demonstração local: seus dados não são enviados a um servidor e não substituem uma conta segura da loja.</p></section></section>`;
}

function renderHelp() {
  activeView = "ajuda";
  document.title = "Atendimento | Mega Store";
  appMain.innerHTML = `<section class="container section"><div class="catalog-intro"><span class="eyebrow">Estamos aqui</span><h1>Atendimento</h1><p>Esta versão é uma demonstração local da loja.</p></div><div class="summary"><h2>Entregas e pedidos</h2><p>O carrinho calcula o total e permite concluir um pedido de demonstração. Nenhum pagamento ou envio real é processado.</p><h2>Trocas e devoluções</h2><p>Para usar a loja em produção, conecte o fluxo de pedidos a um backend e configure suas políticas de atendimento.</p><h2>Contato</h2><p>Configure aqui os canais oficiais de atendimento da Mega Store.</p></div></section>`;
}

function renderOrder(orderId) {
  activeView = "pedido";
  const order = readStorage("mega-ultimo-pedido", null);
  document.title = "Pedido | Mega Store";
  appMain.innerHTML = `<section class="container section"><div class="empty-state"><span class="eyebrow">Pedido de demonstração</span><h2>Pedido ${escapeHTML(orderId)} registrado neste navegador</h2><p>${order ? `Total demonstrativo: ${formatarPreco(order.total)}. ` : ""}Nenhum pagamento ou envio foi realizado.</p><a class="button button-dark" href="#/">Voltar à loja</a></div></section>`;
}

function renderNotFound() {
  activeView = "nao-encontrado";
  document.title = "Página não encontrada | Mega Store";
  appMain.innerHTML = `<section class="container section"><div class="empty-state"><h2>Não encontramos esta tela</h2><a class="button button-dark" href="#/">Voltar ao início</a></div></section>`;
}

function parseRoute() {
  const hash = window.location.hash;
  if (!hash.startsWith("#/")) return { type: "home" };
  const [path, query = ""] = hash.slice(2).split("?");
  const parts = path.split("/").filter(Boolean);
  const type = parts[0] || "home";
  return { type, value: parts[1], query: new URLSearchParams(query) };
}

function renderRoute() {
  if (!window.location.hash.startsWith("#/")) {
    const anchor = window.location.hash.slice(1);
    if (activeView !== "home" && !activeView.startsWith("categoria/")) {
      renderHome();
    }
    document.body.classList.toggle("header-over-hero", activeView === "home" || activeView.startsWith("categoria/"));
    if (anchor) requestAnimationFrame(() => document.getElementById(decodeURIComponent(anchor))?.scrollIntoView({ behavior: "smooth", block: "start" }));
    updateCartCount();
    return;
  }
  const route = parseRoute();
  if (route.type === "categoria") renderCategory(route.value);
  else if (route.type === "produto") renderProduct(getProduct(route.value));
  else if (route.type === "favoritos") renderFavorites();
  else if (route.type === "busca") renderSearch(route.query.get("q") || "");
  else if (route.type === "carrinho") renderCart();
  else if (["login", "cadastro", "conta"].includes(route.type)) renderAuth(route.type);
  else if (route.type === "ajuda") renderHelp();
  else if (route.type === "pedido") renderOrder(route.value || "");
  else if (route.type === "home") { document.title = "Mega Store | A arte da trama, a elegância do vestir"; renderHome(); }
  else renderNotFound();
  document.body.classList.toggle("header-over-hero", activeView === "home" || activeView.startsWith("categoria/"));
  updateCartCount();
  document.querySelector(".mobile-nav")?.classList.remove("is-open");
  document.body.classList.remove("no-scroll");
  const toggle = document.querySelector(".mobile-toggle");
  if (toggle) {
    toggle.classList.remove("is-active");
    toggle.setAttribute("aria-expanded", "false");
  }
}

function renderSearchResults(term, container) {
  const normalized = term.trim().toLocaleLowerCase("pt-BR");
  const matches = normalized ? produtos.filter((product) => `${product.nome} ${product.categoria}`.toLocaleLowerCase("pt-BR").includes(normalized)).slice(0, 8) : [];
  container.innerHTML = matches.map((product) => `<a class="search-result" href="#/produto/${product.id}"><img src="${escapeHTML(product.imagem)}" alt=""><span>${escapeHTML(product.nome)}<small>${formatarPreco(product.preco)}</small></span></a>`).join("");
}

function setupHeader() {
  const toggle = document.querySelector(".search-toggle");
  const panel = document.querySelector(".search-panel");
  const form = panel.querySelector("form");
  const input = form.querySelector("input");
  toggle.addEventListener("click", () => {
    const open = panel.classList.toggle("open");
    toggle.setAttribute("aria-expanded", String(open));
    if (open) input.focus();
  });
  input.addEventListener("input", () => renderSearchResults(input.value, panel.querySelector(".search-results")));
  form.addEventListener("submit", (event) => {
    event.preventDefault();
    window.location.hash = `#/busca?q=${encodeURIComponent(input.value.trim())}`;
    panel.classList.remove("open");
    toggle.setAttribute("aria-expanded", "false");
  });
  document.querySelector(".mobile-toggle").addEventListener("click", (event) => {
    const menu = document.querySelector(".mobile-nav");
    const open = menu.classList.toggle("is-open");
    event.currentTarget.classList.toggle("is-active", open);
    event.currentTarget.setAttribute("aria-expanded", String(open));
    document.body.classList.toggle("no-scroll", open);
  });
  document.querySelectorAll(".mobile-nav__link").forEach((link) => {
    link.addEventListener("click", () => {
      document.querySelector(".mobile-nav").classList.remove("is-open");
      document.querySelector(".mobile-toggle").classList.remove("is-active");
      document.querySelector(".mobile-toggle").setAttribute("aria-expanded", "false");
      document.body.classList.remove("no-scroll");
    });
  });
  document.addEventListener("keydown", (event) => {
    if (event.key === "Escape") { panel.classList.remove("open"); toggle.setAttribute("aria-expanded", "false"); }
  });
}

function setupGlobalActions() {
  document.addEventListener("click", (event) => {
    const favoriteButton = event.target.closest("[data-wish]");
    if (favoriteButton) {
      event.preventDefault();
      const id = Number(favoriteButton.dataset.wish);
      const favorites = readFavorites();
      const updated = favorites.includes(id) ? favorites.filter((favoriteId) => favoriteId !== id) : [...favorites, id];
      writeStorage("mega-favoritos", updated);
      favoriteButton.classList.toggle("active", updated.includes(id));
      favoriteButton.setAttribute("aria-pressed", String(updated.includes(id)));
      return;
    }
    const cartButton = event.target.closest("[data-cart-action]");
    if (cartButton) {
      const line = cartButton.closest(".cart-line");
      const id = Number(line.dataset.id);
      const size = line.dataset.size || "";
      const cart = readCart();
      const index = cart.findIndex((item) => item.id === id && (item.tamanho || "") === size);
      if (index < 0) return;
      if (cartButton.dataset.cartAction === "remove") cart.splice(index, 1);
      if (cartButton.dataset.cartAction === "plus") cart[index].quantidade += 1;
      if (cartButton.dataset.cartAction === "minus") cart[index].quantidade = Math.max(1, cart[index].quantidade - 1);
      writeStorage("mega-carrinho", cart);
      renderCart();
      updateCartCount();
      return;
    }
    if (event.target.closest("#checkout-button")) {
      const cart = readCart();
      const total = cart.reduce((sum, item) => sum + (getProduct(item.id)?.preco || 0) * item.quantidade, 0);
      const orderId = `DEMO-${Date.now().toString().slice(-8)}`;
      writeStorage("mega-ultimo-pedido", { id: orderId, total, createdAt: new Date().toISOString() });
      writeStorage("mega-carrinho", []);
      window.location.hash = `#/pedido/${orderId}`;
      return;
    }
    if (event.target.closest("#logout-button")) {
      localStorage.removeItem("mega-sessao");
      window.location.hash = "#/";
    }
  });

  document.addEventListener("submit", async (event) => {
    const form = event.target.closest("#auth-form");
    if (!form) return;
    event.preventDefault();
    const message = form.querySelector(".form-message");
    const data = new FormData(form);
    const email = String(data.get("email")).trim().toLocaleLowerCase("pt-BR");
    const password = String(data.get("senha"));
    if (!window.crypto?.subtle) {
      message.textContent = "Para criar ou acessar a conta de demonstração, abra a loja pelo localhost (XAMPP) ou HTTPS.";
      return;
    }
    const passwordHash = await crypto.subtle.digest("SHA-256", new TextEncoder().encode(password)).then((buffer) => Array.from(new Uint8Array(buffer), (byte) => byte.toString(16).padStart(2, "0")).join(""));
    const accounts = readStorage("mega-contas", []);
    if (form.dataset.authKind === "register") {
      const name = String(data.get("nome")).trim();
      if (password !== data.get("confirmacao")) { message.textContent = "As senhas não coincidem."; return; }
      if (accounts.some((account) => account.email === email)) { message.textContent = "Este e-mail já está cadastrado neste navegador."; return; }
      accounts.push({ nome: name, email, passwordHash });
      if (!writeStorage("mega-contas", accounts)) { message.textContent = "Não foi possível salvar a conta neste navegador."; return; }
      writeStorage("mega-sessao", { nome: name, email });
      window.location.hash = "#/conta";
      return;
    }
    const account = accounts.find((entry) => entry.email === email && entry.passwordHash === passwordHash);
    if (!account) { message.textContent = "E-mail ou senha inválidos nesta demonstração."; return; }
    writeStorage("mega-sessao", { nome: account.nome, email: account.email });
    window.location.hash = "#/conta";
  });
}

function initializeApp() {
  const page = document.body.dataset.page;
  const legacyRoutes = { feminino: "categoria/feminino", masculino: "categoria/masculino", infantil: "categoria/infantil", calcados: "categoria/calcados", carrinho: "carrinho", login: "login", cadastro: "cadastro" };
  if (page && page !== "home" && legacyRoutes[page]) {
    window.location.replace(`index.html#/${legacyRoutes[page]}`);
    return;
  }
  appMain = document.querySelector("#app-main");
  if (!appMain) return;
  document.querySelectorAll("[data-site-header]").forEach((element) => { element.innerHTML = headerTemplate; });
  document.querySelectorAll("[data-site-footer]").forEach((element) => { element.innerHTML = footerTemplate; });
  homeMarkup = appMain.innerHTML;
  setupHeader();
  setupGlobalActions();
  window.addEventListener("hashchange", renderRoute);
  renderRoute();
}

document.addEventListener("DOMContentLoaded", initializeApp);