<?php
$pageTitle = 'Vestido Noir Éternel';
$product = [
    'category' => 'Feminino',
    'name' => 'Vestido Noir Éternel',
    'price' => 'R$ 12.890,00',
    'description' => 'Vestido de linhas elegantes, pensado para ocasiões especiais e feito para atravessar estações.',
    'image' => 'assets/img/vestido-noir.svg',
    'sizes' => ['PP', 'P', 'M', 'G','GG'],
];

?>

<?php
include("../geral/conexao.php");

$id_produto = isset($_GET['id_produto']) ? (int)$_GET['id_produto'] : 0;

$sqlProduto = "SELECT p.*, ct.nome_categoria, gen.nome_genero
               FROM tb_produto p
               LEFT JOIN tb_categoria ct ON ct.id_categoria = p.id_categoria
               LEFT JOIN tb_genero gen ON gen.id_genero = p.id_genero
               WHERE p.id_produto = $id_produto
               LIMIT 1";
$rProduto = mysqli_query($con, $sqlProduto);
$prod = mysqli_fetch_assoc($rProduto);

$variacoes = [];
$imagemInicial = '';

$sqlVar = "SELECT vp.id_variacao, vp.cor, vp.tamanho, vp.estoque, ip.caminho_imagem
           FROM tb_variacao_produto vp
           LEFT JOIN tb_imagem_produto ip ON ip.id_variacao = vp.id_variacao
           WHERE vp.id_produto = $id_produto
           ORDER BY vp.id_variacao";
$rVar = mysqli_query($con, $sqlVar);

while ($v = mysqli_fetch_assoc($rVar)) {
    $cor = $v['cor'];
    $tamanho = $v['tamanho'];
    $estoque = (int)$v['estoque'];
    $imagem = $v['caminho_imagem'] ?? '';
    $id_variacao = (int)$v['id_variacao'];

    if (!isset($variacoes[$cor])) {
        $variacoes[$cor] = [
            'imagem' => '',
            'tamanhos' => []
        ];
    }

    if ($variacoes[$cor]['imagem'] === '' && $imagem !== '') {
        $variacoes[$cor]['imagem'] = $imagem;
        if ($imagemInicial === '') {
            $imagemInicial = $imagem;
        }
    }

    $variacoes[$cor]['tamanhos'][$tamanho] = [
        'estoque' => $estoque,
        'id_variacao' => $id_variacao
    ];
}

$coresDisponiveis = array_keys($variacoes);

if ($imagemInicial === '' && !empty($coresDisponiveis)) {
    $primeiraCor = $coresDisponiveis[0];
    $imagemInicial = $variacoes[$primeiraCor]['imagem'];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../geral/css/cabecalho.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .color-options,
.size-options {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin: 12px 0 20px;
}

.color-option,
.size-btn {
    cursor: pointer;
}

.color-option input {
    display: none;
}

.color-option span,
.tamanho-btn {
    display: inline-block;
    padding: 10px 16px;
    border: 1px solid #ccc;
    background: white;
    cursor: pointer;
}

.color-option input:checked + span,
.tamanho-btn.ativo {
    background: black;
    color: white;
    border-color: black;
}

.tamanho-btn.indisponivel {
    opacity: 0.4;
    cursor: not-allowed;
    text-decoration: line-through;
}

#estoque-disponivel {
    display: block;
    margin-top: 8px;
    color: #777;
}
    .product-card__image-wrapper {
    position: relative;
    overflow: hidden;
    aspect-ratio: 3 / 4;
    background: var(--gray-50);
    margin-bottom: 16px;
}
    .product-card__image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.7s var(--ease-silk), opacity 0.5s ease;
}

.product-card:hover .product-card__image {
    transform: scale(1.04);
}
.category-card__image-wrapper {
    position: absolute;
    inset: 0;
    overflow: hidden;
}

.category-card__image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.8s var(--ease-silk), filter 0.8s var(--ease-silk);
    filter: brightness(0.85);
}

.category-card:hover .category-card__image {
    transform: scale(1.05);
    filter: brightness(0.7);
}
    .product-page {
    display: grid;
    grid-template-columns: 1fr 1fr;
    align-items: start;
    gap: 40px;
    width: 100%;
    max-width: 1150px;
    margin: 50px auto;
    padding: 0 20px;
    box-sizing: border-box;
}

.product-gallery {
    width: 100%;
    display: flex;
    justify-content: center;
}

.product-card__image-wrapper {
    position: relative;
    width: 100%;
    max-width: 520px;
    aspect-ratio: 3 / 4;
    overflow: hidden;
    margin: 0;
}

.product-details {
    width: 100%;
    max-width: 520px;
    justify-self: center;
}

.category-card__image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

@media (max-width: 768px) {
    .product-page {
        grid-template-columns: 1fr;
        gap: 30px;
        margin: 30px auto;
    }

    .product-gallery,
    .product-details {
        max-width: 100%;
    }

    .product-card__image-wrapper {
        max-width: 100%;
    }
}
    </style>
      
</head>
<body>
<?php include('includes/header.php'); ?>

<main class="product-page">
    <section class="product-gallery" aria-label="Imagens do produto">
        <div class="product-card__image-wrapper">
    <img
        id="imagem-produto"
        src="../produtos/uploads/<?= htmlspecialchars($imagemInicial, ENT_QUOTES, 'UTF-8') ?>"
        alt="Imagem do produto"
        class='category-card__image'
    >
</div>
    </section>

    <section class="product-details">
        <span class="eyebrow"><?= htmlspecialchars($prod['nome_categoria'], ENT_QUOTES, 'UTF-8') ?></span>

        <h1><?= htmlspecialchars($prod['nome_produto'], ENT_QUOTES, 'UTF-8') ?></h1>

        <p class="product-price">R$ <?= htmlspecialchars($prod['preco'], ENT_QUOTES, 'UTF-8') ?></p>

        <p class="product-description">
            <?= htmlspecialchars($prod['descricao_produto'], ENT_QUOTES, 'UTF-8') ?>
        </p>

        <form method="POST" action="">
    <div class="color-section">
        <h2 class="field-title">Selecione a cor</h2>

        <div class="color-options">
            <?php foreach ($variacoes as $cor => $dados): ?>
                <label class="color-option">
                    <input
                        type="radio"
                        name="cor"
                        value="<?= htmlspecialchars($cor, ENT_QUOTES, 'UTF-8') ?>"
                        data-img="../produtos/uploads/<?= htmlspecialchars($dados['imagem'], ENT_QUOTES, 'UTF-8') ?>"
                    >
                    <span><?= htmlspecialchars($cor, ENT_QUOTES, 'UTF-8') ?></span>
                </label>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="size-section">
        <h2 class="field-title">Selecione o tamanho</h2>
        <div class="size-options" id="lista-tamanhos"></div>
    </div>

    <div class="quantity-section" id="area-quantidade" style="display: none;">
        <label class="field-title" for="quantidade">Quantidade</label>

        <input
            id="quantidade"
            name="quantidade"
            class="quantity-input"
            type="number"
            min="1"
            value="1"
            required
        >

        <small id="estoque-disponivel"></small>
    </div>

    <input type="hidden" name="id_variacao" id="id_variacao">

    <button type="submit" class="button button-dark add-to-cart" name="comprar">
        Adicionar ao carrinho
    </button>
</form>
    </section>
</main>
<?php 
    if (isset($_POST["comprar"])) {
    $id_variacao = $_POST['id_variacao'];
    $quantidade = $_POST['quantidade'];

    if (empty($_SESSION['id_usuario'])) {
        echo "<a href='../auth/Cadastro_mega_store.php'>POR FAVOR, SE CADASTRE PARA ACESSAR O SITE!</a>";
    } else {

        $id_usuario = $_SESSION['id_usuario'];

        $sql_carrinho = "INSERT INTO tb_carrinho
                (id_variacao,id_usuario,qtd_carrinho)
                VALUES
                ('$id_variacao','$id_usuario','$quantidade')";
                $r= mysqli_query($con,$sql_carrinho);
        //$sql_estoque = "UPDATE tb_variacao_produto
        //       SET estoque = estoque - $quantidade
        //        WHERE id_variacao = $id_variacao
        //        AND id_produto = $id_produto
        //        AND estoque >= $quantidade";        
        //$r= mysqli_query($con,$sql_estoque);
            
    }
    }
    ?>
<?php require __DIR__ . '/includes/footer.php'; ?>

<script>
const variacoes = <?= json_encode($variacoes, JSON_UNESCAPED_UNICODE) ?>;

let corSelecionada = null;
let tamanhoSelecionado = null;

function selecionarCor(el) {
    corSelecionada = el.value;
    tamanhoSelecionado = null;

    document.getElementById("id_variacao").value = "";

    const novaImagem = el.getAttribute("data-img");

    if (novaImagem) {
        document.getElementById("imagem-produto").src = novaImagem;
    }

    renderTamanhos(corSelecionada);
}

function renderTamanhos(cor) {
    const container = document.getElementById("lista-tamanhos");
    const areaQuantidade = document.getElementById("area-quantidade");

    areaQuantidade.style.display = "none";
    container.innerHTML = "";

    if (!cor || !variacoes[cor]) {
        return;
    }

    const tamanhos = variacoes[cor].tamanhos;

    for (const [tamanho, dados] of Object.entries(tamanhos)) {
        const disponivel = dados.estoque > 0;

        const botao = document.createElement("button");

        botao.type = "button";
        botao.className = "tamanho-btn";

        botao.textContent = tamanho;

        if (disponivel) {
            botao.classList.add("disponivel");

            botao.addEventListener("click", function() {
                selecionarTamanho(this, dados);
            });
        } else {
            botao.classList.add("indisponivel");
            botao.disabled = true;
        }

        container.appendChild(botao);
    }
}

function selecionarTamanho(el, dados) {
    document.querySelectorAll(".tamanho-btn").forEach(botao => {
        botao.classList.remove("ativo");
    });

    el.classList.add("ativo");

    tamanhoSelecionado = el.textContent;

    document.getElementById("id_variacao").value = dados.id_variacao;

    const campoQuantidade = document.getElementById("quantidade");
    const areaQuantidade = document.getElementById("area-quantidade");
    const textoEstoque = document.getElementById("estoque-disponivel");

    campoQuantidade.value = 1;
    campoQuantidade.min = 1;
    campoQuantidade.max = dados.estoque;

    textoEstoque.textContent = dados.estoque + " unidade(s) disponível(is)";

    areaQuantidade.style.display = "block";
}

document.addEventListener("DOMContentLoaded", function() {

    document.querySelectorAll('input[name="cor"]').forEach(radio => {
        radio.addEventListener("change", function() {
            selecionarCor(this);
        });
    });

    const primeiraCor = document.querySelector('input[name="cor"]');

    if (primeiraCor) {
        primeiraCor.checked = true;
        selecionarCor(primeiraCor);
    }

    document.querySelector("form").addEventListener("submit", function(event) {
        const idVariacao = document.getElementById("id_variacao").value;
        const quantidade = parseInt(document.getElementById("quantidade").value);
        const estoque = parseInt(document.getElementById("quantidade").max);

        if (!corSelecionada || !tamanhoSelecionado || !idVariacao) {
            event.preventDefault();
            alert("Selecione uma cor e um tamanho disponível!");
            return;
        }

        if (isNaN(quantidade) || quantidade < 1 || quantidade > estoque) {
            event.preventDefault();
            alert("Selecione uma quantidade válida!");
        }
    });
});
</script>
</body>
</html>