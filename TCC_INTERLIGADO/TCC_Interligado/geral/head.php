<?php
require_once __DIR__ . '/auth.php';
$usuarioNav = current_user();
$perfilNav = $usuarioNav ? '../usuario/perfil.php' : '../auth/Cadastro_mega_store.php';
?>
<link rel="stylesheet" href="../home/Tl_princip.css">
<header class="header">
    <div class="header__inner">
        <a href="../home/Tl_principal.php" class="header__logo">
            <span class="header__logo-text">MEGA</span>
            <span class="header__logo-sub">STORE</span>
        </a>
        <nav class="header__nav" aria-label="Navegação principal">
            <ul class="header__nav-list">
                <li><a class="header__nav-link" href="../home/Tl_principal.php">Início</a></li>
                <li><a class="header__nav-link" href="../produtos/Card_produtos.php">Produtos</a></li>
                <li><a class="header__nav-link" href="../produtos/buscar_produto.php">Buscar</a></li>
                <?php if ($usuarioNav && (int) $usuarioNav['tipo'] === 1): ?>
                    <li><a class="header__nav-link" href="../admin/index.php">Administração</a></li>
                <?php endif; ?>
                <li><a class="header__nav-link" href="<?= htmlspecialchars($perfilNav, ENT_QUOTES, 'UTF-8') ?>"><?= $usuarioNav ? 'Meu perfil' : 'Entrar' ?></a></li>
            </ul>
        </nav>
    </div>
</header>
