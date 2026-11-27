<?php
declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="MEGA STORE — moda, elegância e peças selecionadas.">
    <title><?= htmlspecialchars($pageTitle ?? 'MEGA STORE', ENT_QUOTES, 'UTF-8') ?> | MEGA STORE</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=Playfair+Display:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>


<header class="site-header">
    <a class="brand" href="index.php" aria-label="MEGA STORE - página inicial">
        <span class="brand-name">MEGA</span>
        <span class="brand-subtitle">STORE</span>
    </a>

    <button class="mobile-menu-toggle" type="button" aria-label="Abrir menu" aria-expanded="false">
        <span></span><span></span><span></span>
    </button>

    <nav class="main-navigation" aria-label="Menu principal">
        <a href="feminino.php">Feminino</a>
        <a href="masculino.php">Masculino</a>
        <a href="infantil.php">Infantil</a>
        <a href="calcados.php">Calçados</a>
    </nav>

    
    <div class="header-tools">
        <form class="search-form" action="pesquisa.php" method="get" role="search">
            <input type="search" name="q" placeholder="Pesquisar" aria-label="Pesquisar produtos">
            <button type="submit" class="icon-button" aria-label="Pesquisar">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="10.8" cy="10.8" r="6.5"></circle>
                    <path d="m16 16 4.5 4.5"></path>
                </svg>
            </button>
        </form>

        <a class="icon-link cart-icon" href="../carrinho/carrinho.php" aria-label="Carrinho">
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <path d="M3 4h2l2.1 11.1a2 2 0 0 0 2 1.6h8.2a2 2 0 0 0 1.9-1.5L21 8H6"></path>
                <circle cx="10" cy="20" r="1"></circle>
                <circle cx="18" cy="20" r="1"></circle>
            </svg>
            <span class="cart-count">0</span>
        </a>

        <a class="icon-link user-icon" href="perfil.php" aria-label="Minha conta">
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <circle cx="12" cy="8" r="3.5"></circle>
                <path d="M5 21c.5-4 3-6 7-6s6.5 2 7 6"></path>
            </svg>
        </a>

        <a class="create-account-link" href="cadastro.php">Criar conta</a>
    </div>
</header>
