<?php
include("../geral/conexao.php");
if (empty($_SESSION['id_usuario'])) {
        echo "<a href='../auth/Cadastro_mega_store.php'>POR FAVOR, SE CADASTRE PARA ACESSAR O SITE!</a>";
    } else {
      $id_usuario = $_SESSION['id_usuario'];
$sql = "SELECT p.*, ip.*, ct.*, gen.*, vp.*, c.*
        FROM tb_carrinho c
        INNER JOIN tb_variacao_produto vp ON vp.id_variacao = c.id_variacao
        INNER JOIN tb_produto p ON p.id_produto = vp.id_produto
        LEFT JOIN tb_imagem_produto ip ON ip.id_variacao = vp.id_variacao
        LEFT JOIN tb_categoria ct ON ct.id_categoria = p.id_categoria
        LEFT JOIN tb_genero gen ON gen.id_genero = p.id_genero
        WHERE c.id_usuario = $id_usuario ";

$resultado = mysqli_query($con, $sql);

if (!$resultado) {
    die("Erro na consulta: " . mysqli_error($con));
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta
      name="description"
      content="Revise os produtos do seu carrinho Mega Store."
    />
    <title>Seu carrinho | Mega Store</title>
    <link rel="stylesheet" href="carrinho.css" />
    <script defer src="js/produtos.js"></script>
    <script defer src="js/script.js"></script>
    <script defer src="js/carrinho.js"></script>
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
    </style>
  </head>
  <body data-page="carrinho">
    <div data-site-header></div>
    <main class="container" style="padding-top: 48px">
      <section class="container" id="produtos">
        <div class="catalog-intro">
          <span class="eyebrow">A coleção</span>
          <h1>Seu Carrinho</h1>
        
        <div class='products__grid'>
    <?php
while($x = mysqli_fetch_assoc($resultado)) {
    echo"<form method='get' action='../mega-store/tela_produto.php'>
                    <input type='hidden' name='id_produto' value='".$x['id_produto']."'>
                    <button type='submit' class='button-produto' style='background: none; border: none; padding: 0; cursor: pointer;'>
                    <article class='product-card reveal' id='product-1'>
                <div class='product-card__image-wrapper'>
                    <img src='../produtos/uploads/".$x['caminho_imagem']."' alt='Vestido Noir Éternel' class='product-card__image'>
                    <span class='product-card__badge'>Novo</span>
                </div>
                <div class='product-card__info'>
                    <h3 class='product-card__name'>".$x['nome_produto']."</h3>
                    <p class='product-card__category'>".$x['nome_categoria']."</p>
                    <p class='product-card__price'>R$ ".$x['preco']."</p>
                    <p class='product-card__price'>Quantidade: ".$x['qtd_carrinho']."</p>
                </div>
            </article>
            </button>
                ".$x['caminho_imagem']."
                    </form>
                    ";
                }
             ?>
             </div>
      </section>
    </main>
    <div data-site-footer></div>
  </body>
</html>
<?php
}
?>