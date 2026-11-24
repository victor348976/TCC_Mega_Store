<?php
declare(strict_types=1);

$pageTitle = 'Vestido Noir Éternel';
$product = [
    'category' => 'Feminino',
    'name' => 'Vestido Noir Éternel',
    'price' => 'R$ 12.890,00',
    'description' => 'Vestido de linhas elegantes, pensado para ocasiões especiais e feito para atravessar estações.',
    'image' => 'assets/img/vestido-noir.svg',
    'sizes' => ['PP', 'P', 'M', 'G'],
];

require __DIR__ . '/includes/header.php';
?>

<main class="product-page">
    <section class="product-gallery" aria-label="Imagens do produto">
        <div class="product-image">
            <img
                src="<?= htmlspecialchars($product['image'], ENT_QUOTES, 'UTF-8') ?>"
                alt="Vestido longo preto da coleção MEGA STORE"
            >
        </div>
    </section>

    <section class="product-details">
        <span class="eyebrow"><?= htmlspecialchars($product['category'], ENT_QUOTES, 'UTF-8') ?></span>

        <h1><?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?></h1>

        <p class="product-price"><?= htmlspecialchars($product['price'], ENT_QUOTES, 'UTF-8') ?></p>

        <p class="product-description">
            <?= htmlspecialchars($product['description'], ENT_QUOTES, 'UTF-8') ?>
        </p>

        <div class="size-section">
            <h2 class="field-title">Selecione o tamanho</h2>

            <div class="size-options">
                <?php foreach ($product['sizes'] as $index => $size): ?>
                    <label class="size-option">
                        <input
                            type="radio"
                            name="size"
                            value="<?= htmlspecialchars($size, ENT_QUOTES, 'UTF-8') ?>"
                            <?= $index === 3 ? 'checked' : '' ?>
                        >
                        <span><?= htmlspecialchars($size, ENT_QUOTES, 'UTF-8') ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="quantity-section">
            <label class="field-title" for="quantity">Quantidade</label>
            <input id="quantity" class="quantity-input" type="number" min="1" max="10" value="1">
        </div>

        <a class="button button-dark add-to-cart" href="carrinho.php">
            Adicionar ao carrinho
        </a>

        <a class="continue-link" href="index.php">Continuar comprando</a>
    </section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
