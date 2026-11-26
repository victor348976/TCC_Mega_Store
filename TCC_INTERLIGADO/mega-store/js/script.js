(() => {
  const headerMount = document.querySelector('[data-site-header]');

  if (headerMount) {
    headerMount.innerHTML = `
      <header class="site-header">
        <div class="header-main">
          <button class="icon-button mobile-toggle" type="button" aria-label="Abrir menu" aria-expanded="false">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 6h18M3 12h18M3 18h18"></path></svg>
          </button>

          <a class="brand" href="index.php" aria-label="Mega Store - página inicial">
            <span>MEGA</span><span>STORE</span>
          </a>

          <nav class="primary-nav" aria-label="Menu principal">
            <a href="feminino.php">Feminino</a>
            <a href="masculino.php">Masculino</a>
            <a href="infantil.php">Infantil</a>
            <a href="calcados.php">Calçados</a>
            <a href="acessorios.php">Acessórios</a>
          </nav>

          <div class="header-tools">
            <button class="icon-button search-toggle" type="button" aria-label="Pesquisar">
              <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.5"></circle><path d="m16 16 4.5 4.5"></path></svg>
            </button>
            <a class="icon-button cart-link" href="carrinho.php" aria-label="Carrinho">
              <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 4h2l2.1 11.1a2 2 0 0 0 2 1.6h8.2a2 2 0 0 0 1.9-1.5L21 8H6"></path><circle cx="10" cy="20" r="1"></circle><circle cx="18" cy="20" r="1"></circle></svg>
              <span class="cart-count">0</span>
            </a>
            <a class="account-link" href="login.php">Entrar</a>
          </div>

          <nav class="mobile-nav" aria-label="Menu mobile">
            <a href="feminino.php">Feminino</a>
            <a href="masculino.php">Masculino</a>
            <a href="infantil.php">Infantil</a>
            <a href="calcados.php">Calçados</a>
            <a href="acessorios.php">Acessórios</a>
            <a href="login.php">Minha conta</a>
          </nav>
        </div>

        <div class="search-panel">
          <form class="search-inner" action="index.php" method="get">
            <input type="search" name="q" placeholder="Pesquisar na Mega Store" aria-label="Pesquisar produtos">
            <button type="submit" aria-label="Enviar pesquisa">Buscar</button>
          </form>
        </div>
      </header>
    `;

    const menuButton = headerMount.querySelector('.mobile-toggle');
    const mobileNav = headerMount.querySelector('.mobile-nav');
    const searchToggle = headerMount.querySelector('.search-toggle');
    const searchPanel = headerMount.querySelector('.search-panel');

    menuButton?.addEventListener('click', () => {
      const open = mobileNav.classList.toggle('open');
      menuButton.setAttribute('aria-expanded', String(open));
    });

    searchToggle?.addEventListener('click', () => {
      searchPanel.classList.toggle('open');
      if (searchPanel.classList.contains('open')) {
        searchPanel.querySelector('input')?.focus();
      }
    });
  }

  const footerMount = document.querySelector('[data-site-footer]');
  if (footerMount) {
    footerMount.innerHTML = `
      <footer class="site-footer">
        <div class="footer-grid">
          <div class="footer-brand-block">
            <a class="footer-brand" href="index.php">MEGA<span>STORE</span></a>
            <p>A arte da moda, a elegância do vestir.</p>
          </div>
          <div class="footer-column">
            <h3>Navegação</h3>
            <a href="index.php">Início</a>
            <a href="feminino.php">Feminino</a>
            <a href="masculino.php">Masculino</a>
            <a href="infantil.php">Infantil</a>
          </div>
          <div class="footer-column">
            <h3>Coleções</h3>
            <a href="calcados.php">Calçados</a>
            <a href="acessorios.php">Acessórios</a>
            <a href="carrinho.php">Carrinho</a>
          </div>
          <div class="footer-column">
            <h3>Atendimento</h3>
            <a href="login.php">Minha conta</a>
            <a href="cadastro.php">Criar conta</a>
          </div>
        </div>
        <div class="footer-bottom">
          <span>&copy; ${new Date().getFullYear()} Mega Store. Todos os direitos reservados.</span>
        </div>
      </footer>
    `;
  }
})();
