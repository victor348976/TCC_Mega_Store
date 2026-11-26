 <header id='main-header' class='header'>
        <div class='header__inner'>
            <!-- Logo -->
            <a href='#' class='header__logo' id='logo'>
                <span class='header__logo-text'>MEGA</span>
                <span class='header__logo-sub'>STORE</span>
            </a>

            <!-- Nav Links -->
            <nav class='header__nav' id='main-nav' aria-label='Navegação principal'>
                <ul class='header__nav-list'>
                    <li><a href='../mega-store' class='header__nav-link'>Coleções</a></li>
                    <li><a href='#tecidos' class='header__nav-link'>Tecidos</a></li>
                    <li><a href='#sobre' class='header__nav-link'>Sobre</a></li>
                    <li><a href='#contato' class='header__nav-link'>Contato</a></li>
                </ul>
            </nav>

            <!-- Icons -->
            <div class='header__icons'>
                <button class='header__icon-btn' id='search-btn' aria-label='Buscar'>
                    <!-- Search SVG -->
                    <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'>
                        <circle cx='11' cy='11' r='8'></circle>
                        <line x1='21' y1='21' x2='16.65' y2='16.65'></line>
                    </svg> 
                </button>
                
                <button class='header__icon-btn' id='perfil-btn' aria-label='Perfil'>
                    <!-- Cart SVG -->
                    <a href='../mega-store/carrinho.html'><svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'>
                        <path d='M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z'></path>
                        <line x1='3' y1='6' x2='21' y2='6'></line>
                        <path d='M16 10a4 4 0 0 1-8 0'></path>
                    </svg></a>
                    <span class='header__cart-count' id='cart-count'>0</span>
                </button>
                    <button class='header__icon-btn' id='cart-btn' aria-label='Carrinho'>
                    <a href='> @PERFIL </a>
                    </button>
                     <button class='header__icon-btn' id='cart-btn' aria-label='Carrinho'>
                    <a href='../auth/Cadastro_mega_store.php'> LOGIN </a>
                    </button>
                <!-- Mobile Menu Toggle -->
                <button class='header__hamburger' id='hamburger-btn' aria-label='Abrir menu'>d
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </div>
        <!-- Mobile Nav Overlay -->
        <div class='mobile-nav' id='mobile-nav'>
            <ul class='mobile-nav__list'>
                <li><a href='../mega-store' class='mobile-nav__link'>Coleções</a></li>
                <li><a href='#tecidos' class='mobile-nav__link'>Tecidos</a></li>
                <li><a href='#sobre' class='mobile-nav__link'>Sobre</a></li>
                <li><a href='#contato' class='mobile-nav__link'>Contato</a></li>
            </ul>
        </div>
    </header>