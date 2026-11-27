
<?php
include("../geral/conexao.php");

$erro = '';

if (empty($_SESSION['id_usuario'])) {
    header("Location: ../auth/Cadastro_mega_store.php?modo=login");
    exit;
}

$id_usuario = (int)$_SESSION['id_usuario'];

// PROCESSAMENTO DAS QUANTIDADES E DA COMPRA
if (isset($_POST['atualizar']) || isset($_POST['comprar'])) {

    $quantidades = $_POST['quantidade'] ?? [];
    $selecionados = $_POST['selecionados'] ?? [];

    if (isset($_POST['comprar']) && empty($selecionados)) {
        $erro = "Selecione pelo menos um produto para comprar.";
    } else {

        mysqli_begin_transaction($con);

        try {

            foreach ($quantidades as $id_variacao => $nova_quantidade) {

                $id_variacao = (int)$id_variacao;
                $nova_quantidade = (int)$nova_quantidade;

                if ($id_variacao <= 0 || $nova_quantidade <= 0) {
                    throw new Exception("Quantidade ou variação inválida.");
                }

                // Busca a quantidade atual no carrinho
                $sqlAtual = "SELECT qtd_carrinho
                             FROM tb_carrinho
                             WHERE id_variacao = $id_variacao
                             AND id_usuario = $id_usuario
                             LIMIT 1";

                $rAtual = mysqli_query($con, $sqlAtual);

                if (!$rAtual || mysqli_num_rows($rAtual) == 0) {
                    throw new Exception("Produto não encontrado no carrinho.");
                }

                $atual = mysqli_fetch_assoc($rAtual);
                $quantidade_atual = (int)$atual['qtd_carrinho'];

                $diferenca = $nova_quantidade - $quantidade_atual;

                // Se aumentou a quantidade, desconta o estoque adicional
                if ($diferenca > 0) {

                    $sqlEstoque = "UPDATE tb_variacao_produto
                                   SET estoque = estoque - $diferenca
                                   WHERE id_variacao = $id_variacao
                                   AND estoque >= $diferenca";

                    $rEstoque = mysqli_query($con, $sqlEstoque);

                    if (!$rEstoque || mysqli_affected_rows($con) == 0) {
                        throw new Exception("Estoque insuficiente para aumentar a quantidade.");
                    }
                }

                // Se diminuiu, devolve a diferença ao estoque
                elseif ($diferenca < 0) {

                    $devolver = abs($diferenca);

                    $sqlEstoque = "UPDATE tb_variacao_produto
                                   SET estoque = estoque + $devolver
                                   WHERE id_variacao = $id_variacao";

                    if (!mysqli_query($con, $sqlEstoque)) {
                        throw new Exception(mysqli_error($con));
                    }
                }

                // Atualiza a quantidade no carrinho
                $sqlUpdate = "UPDATE tb_carrinho
                              SET qtd_carrinho = $nova_quantidade
                              WHERE id_variacao = $id_variacao
                              AND id_usuario = $id_usuario";

                if (!mysqli_query($con, $sqlUpdate)) {
                    throw new Exception(mysqli_error($con));
                }
            }

            mysqli_commit($con);

            // Se clicou em comprar, encaminha os produtos selecionados
            if (isset($_POST['comprar'])) {

                $selecionados = array_map('intval', $selecionados);
                $selecionados = array_filter($selecionados);

                $itens = http_build_query([
                    'itens' => array_values($selecionados)
                ]);

                header("Location: finalizar_compra.php?$itens");
                exit;
            }

            // Se clicou em atualizar, recarrega o carrinho
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;

        } catch (Exception $e) {

            mysqli_rollback($con);
            $erro = $e->getMessage();
        }
    }
}

// BUSCA OS PRODUTOS DO CARRINHO
$sql = "SELECT
            p.id_produto,
            p.nome_produto,
            p.preco,
            ct.nome_categoria,
            gen.nome_genero,
            vp.id_variacao,
            vp.cor,
            vp.tamanho,
            vp.estoque,
            c.qtd_carrinho,
            (
                SELECT ip.caminho_imagem
                FROM tb_imagem_produto ip
                WHERE ip.id_variacao = vp.id_variacao
                LIMIT 1
            ) AS imagem_variacao
        FROM tb_carrinho c
        INNER JOIN tb_variacao_produto vp
            ON vp.id_variacao = c.id_variacao
        INNER JOIN tb_produto p
            ON p.id_produto = vp.id_produto
        LEFT JOIN tb_categoria ct
            ON ct.id_categoria = p.id_categoria
        LEFT JOIN tb_genero gen
            ON gen.id_genero = p.id_genero
        WHERE c.id_usuario = $id_usuario";

$resultado = mysqli_query($con, $sql);

if (!$resultado) {
    die("Erro na consulta: " . mysqli_error($con));
}

$produtos = [];

while ($x = mysqli_fetch_assoc($resultado)) {
    $produtos[] = $x;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seu carrinho | Mega Store</title>
    <link rel="stylesheet" href="carrinho.css">
    <link rel="stylesheet" href="../geral/css/cabecalho.css">
    <script defer src="js/produtos.js"></script>
    <script defer src="js/script.js"></script>

    <style>
        * {
            box-sizing: border-box;
        }

        .cart-container {
            max-width: 1250px;
            margin: auto;
            padding: 48px 24px;
        }

        .cart-layout {
            display: grid;
            grid-template-columns: minmax(0, 1.7fr) minmax(280px, 0.8fr);
            gap: 32px;
            align-items: start;
            margin-top: 35px;
        }

        .cart-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .cart-card {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 16px;
            border: 1px solid #e5e5e5;
            background: white;
            position: relative;
        }

        .cart-card__image {
            width: 115px;
            height: 145px;
            object-fit: cover;
            flex-shrink: 0;
            background: #f4f4f4;
        }

        .cart-card__info {
            flex: 1;
            min-width: 0;
        }

        .cart-card__name {
            font-size: 17px;
            margin: 0 0 8px;
        }

        .cart-card__details {
            color: #777;
            font-size: 13px;
            line-height: 1.8;
        }

        .cart-card__price {
            font-weight: 600;
            margin-top: 10px;
        }

        .cart-card__quantity {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 12px;
        }

        .cart-card__quantity input {
            width: 65px;
            padding: 8px;
            border: 1px solid #ccc;
        }

        .cart-card__select {
            width: 19px;
            height: 19px;
            cursor: pointer;
            flex-shrink: 0;
        }

        .cart-summary {
            border: 1px solid #e5e5e5;
            padding: 25px;
            background: #fafafa;
            position: sticky;
            top: 25px;
        }

        .cart-summary h2 {
            font-size: 20px;
            margin: 0 0 25px;
        }

        .summary-line {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            margin: 15px 0;
            font-size: 14px;
        }

        .summary-total {
            border-top: 1px solid #ddd;
            padding-top: 18px;
            margin-top: 20px;
            font-size: 18px;
            font-weight: 600;
        }

        .cart-button {
            display: block;
            width: 100%;
            padding: 14px;
            margin-top: 15px;
            border: 1px solid #111;
            background: #111;
            color: white;
            cursor: pointer;
            text-align: center;
            font-size: 13px;
            text-decoration: none;
        }

        .cart-button--outline {
            background: transparent;
            color: #111;
        }

        .cart-button:hover {
            opacity: .8;
        }

        .cart-select-all {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 18px;
            font-size: 13px;
        }

        .cart-error {
            padding: 12px;
            margin: 20px 0;
            background: #fce8e8;
            color: #a00000;
        }

        .cart-empty {
            padding: 45px 20px;
            text-align: center;
            border: 1px solid #eee;
        }

        @media (max-width: 800px) {
            .cart-layout {
                grid-template-columns: 1fr;
            }

            .cart-summary {
                position: static;
            }
        }

        @media (max-width: 500px) {
            .cart-card {
                gap: 12px;
                padding: 10px;
            }

            .cart-card__image {
                width: 85px;
                height: 115px;
            }

            .cart-card__name {
                font-size: 14px;
            }

            .cart-card__details {
                font-size: 11px;
            }
        }
    </style>
</head>

<body data-page="carrinho">

<?php include __DIR__ . '/../mega-store/includes/header.php'; ?>

<main class="cart-container">

    <span class="eyebrow">A coleção</span>
    <h1>Seu carrinho</h1>
    <p>Confira suas peças antes de finalizar a compra.</p>

    <?php if ($erro != ''): ?>
        <div class="cart-error">
            <?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <?php if (count($produtos) > 0): ?>

    <form method="POST" id="cartForm">

        <div class="cart-layout">

            <!-- LISTA DE PRODUTOS -->
            <section>

                <label class="cart-select-all">
                    <input type="checkbox" id="selecionarTodos" checked>
                    Selecionar todos os produtos
                </label>

                <div class="cart-list">

                    <?php foreach ($produtos as $x):

                        $idVariacao = (int)$x['id_variacao'];
                        $preco = (float)$x['preco'];
                        $quantidade = (int)$x['qtd_carrinho'];
                        $subtotal = $preco * $quantidade;

                        $imagem = !empty($x['imagem_variacao'])
                            ? "../produtos/uploads/" . $x['imagem_variacao']
                            : "";

                    ?>

                    <article class="cart-card">

                        <input
                            type="checkbox"
                            class="cart-card__select selecionar-produto"
                            name="selecionados[]"
                            value="<?= $idVariacao ?>"
                            checked
                            aria-label="Selecionar <?= htmlspecialchars($x['nome_produto'], ENT_QUOTES, 'UTF-8') ?>"
                        >

                        <?php if ($imagem != ''): ?>
                            <img
                                src="<?= htmlspecialchars($imagem, ENT_QUOTES, 'UTF-8') ?>"
                                alt="<?= htmlspecialchars($x['nome_produto'], ENT_QUOTES, 'UTF-8') ?>"
                                class="cart-card__image"
                            >
                        <?php else: ?>
                            <div class="cart-card__image"></div>
                        <?php endif; ?>

                        <div class="cart-card__info">

                            <h3 class="cart-card__name">
                                <?= htmlspecialchars($x['nome_produto'], ENT_QUOTES, 'UTF-8') ?>
                            </h3>

                            <div class="cart-card__details">
                                Categoria: <?= htmlspecialchars($x['nome_categoria'] ?? '', ENT_QUOTES, 'UTF-8') ?><br>
                                Gênero: <?= htmlspecialchars($x['nome_genero'] ?? '', ENT_QUOTES, 'UTF-8') ?><br>
                                Cor: <?= htmlspecialchars($x['cor'], ENT_QUOTES, 'UTF-8') ?><br>
                                Tamanho: <?= htmlspecialchars($x['tamanho'], ENT_QUOTES, 'UTF-8') ?>
                            </div>

                            <p class="cart-card__price">
                                R$ <?= number_format($preco, 2, ',', '.') ?>
                            </p>

                            <div class="cart-card__quantity">
                                <label for="qtd<?= $idVariacao ?>">Quantidade:</label>

                                <input
                                    type="number"
                                    id="qtd<?= $idVariacao ?>"
                                    name="quantidade[<?= $idVariacao ?>]"
                                    class="quantidade-produto"
                                    min="1"
                                    value="<?= $quantidade ?>"
                                >
                            </div>

                            <p>
                                Subtotal:
                                <strong class="subtotal-produto"
                                    data-preco="<?= $preco ?>"
                                >
                                    R$ <?= number_format($subtotal, 2, ',', '.') ?>
                                </strong>
                            </p>

                        </div>

                    </article>

                    <?php endforeach; ?>

                </div>

                <button
                    type="submit"
                    name="atualizar"
                    class="cart-button cart-button--outline"
                >
                    Atualizar quantidades
                </button>

            </section>

            <!-- RESUMO DA COMPRA -->
            <aside class="cart-summary">

                <h2>Resumo do pedido</h2>

                <div class="summary-line">
                    <span>Produtos selecionados</span>
                    <strong id="totalItens">0</strong>
                </div>

                <div class="summary-line">
                    <span>Subtotal</span>
                    <strong id="subtotalGeral">R$ 0,00</strong>
                </div>

                <div class="summary-line summary-total">
                    <span>Total</span>
                    <strong id="totalGeral">R$ 0,00</strong>
                </div>

                <button
                    type="submit"
                    name="comprar"
                    class="cart-button"
                >
                    Fazer compra
                </button>

            </aside>

        </div>

    </form>

    <?php else: ?>

        <div class="cart-empty">
            <h2>Seu carrinho está vazio.</h2>
            <p>Explore nossa coleção e encontre sua próxima peça favorita.</p>
            <a href="../mega-store/index.php" class="cart-button">
                Explorar coleção
            </a>
        </div>

    <?php endif; ?>

</main>

<div data-site-footer></div>

<script>
const selecionarTodos = document.getElementById('selecionarTodos');
const seletores = document.querySelectorAll('.selecionar-produto');
const quantidades = document.querySelectorAll('.quantidade-produto');

function formatarMoeda(valor) {
    return valor.toLocaleString('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    });
}

function atualizarResumo() {

    let total = 0;
    let quantidadeItens = 0;

    document.querySelectorAll('.cart-card').forEach(card => {

        const checkbox = card.querySelector('.selecionar-produto');
        const campoQuantidade = card.querySelector('.quantidade-produto');
        const subtotalElemento = card.querySelector('.subtotal-produto');

        const preco = parseFloat(subtotalElemento.dataset.preco);
        const quantidade = Math.max(1, parseInt(campoQuantidade.value) || 1);
        const subtotal = preco * quantidade;

        subtotalElemento.textContent = formatarMoeda(subtotal);

        if (checkbox.checked) {
            total += subtotal;
            quantidadeItens += quantidade;
        }
    });

    document.getElementById('totalItens').textContent = quantidadeItens;
    document.getElementById('subtotalGeral').textContent = formatarMoeda(total);
    document.getElementById('totalGeral').textContent = formatarMoeda(total);

    selecionarTodos.checked =
        seletores.length > 0 &&
        [...seletores].every(c => c.checked);

    selecionarTodos.indeterminate =
        [...seletores].some(c => c.checked) &&
        ![...seletores].every(c => c.checked);
}

selecionarTodos?.addEventListener('change', function() {
    seletores.forEach(c => {
        c.checked = this.checked;
    });

    atualizarResumo();
});

seletores.forEach(c => {
    c.addEventListener('change', atualizarResumo);
});

quantidades.forEach(c => {
    c.addEventListener('input', atualizarResumo);
});

atualizarResumo();
</script>

</body>
</html>
