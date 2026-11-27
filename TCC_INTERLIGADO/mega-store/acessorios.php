<?php
include("../geral/conexao.php");

$sql = "SELECT p.*, ip.*, ct.*, gen.*, vp.*
                    FROM  tb_produto p, 
                          tb_imagem_produto ip, 
                          tb_categoria ct, 
                          tb_genero gen, 
                          tb_variacao_produto vp
                    WHERE p.id_produto = vp.id_produto 
                      AND vp.id_variacao = ip.id_variacao 
                      AND gen.id_genero = p.id_genero 
                      AND ct.id_categoria= 5
                      AND ct.id_categoria = p.id_categoria
                      -- filtramos apenas a primeira variação encontrada
                      AND vp.id_variacao = (
                         SELECT MIN(id_variacao) 
                         FROM tb_variacao_produto 
                         WHERE id_produto = p.id_produto
                     )
                    GROUP BY p.id_produto;
                    ";
          

$resultado = mysqli_query($con, $sql);

?>
<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta
      name="description"
      content="Acessórios contemporâneos Mega Store: bolsas, relógios, óculos e detalhes escolhidos."
    />
    <title>Acessórios | Mega Store</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="../geral/css/cabecalho.css" />
    <script defer src="js/produtos.js"></script>
    <script defer src="js/script.js"></script>
  </head>
  <body data-page="acessorios">
    <?php include __DIR__ . '/includes/header.php'; ?>
    <main>
      <section class="hero">
        <img
          src="https://images.unsplash.com/photo-1590739225287-bd31519780c3?auto=format&fit=crop&w=2100&q=90"
          alt="Seleção editorial de acessórios contemporâneos"
        />
        <div class="hero-copy">
          <span class="eyebrow">Mega Store · Os detalhes</span>
          <h1>O toque<br />final</h1>
          <p>Peças discretas, formas marcantes e detalhes que tornam cada look único.</p>
          <a class="button" href="#produtos">Explorar acessórios</a>
        </div>
      </section>
      <section class="container" id="produtos">
        <div class="catalog-intro">
          <span class="eyebrow">A coleção</span>
          <h1>Acessórios</h1>
          <p>
            Complementos escolhidos com intenção para dar expressão aos seus
            momentos cotidianos.
          </p>
          <div class="category-chips">
            <a href="#category-products">Bolsas</a
            ><a href="#category-products">Óculos</a
            ><a href="#category-products">Relógios</a
            ><a href="#category-products">Detalhes</a>
          </div>
        </div>
        <div class="filter-row">
          <span>Seleção de acessórios</span><span id="product-count"></span>
        </div>
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
                </div>
            </article>
            </button>
                    </form>
                    ";
                }
             ?>
             </div>
      </section>
      <section class="section">
        <div class="editorial">
          <img
            src="https://images.unsplash.com/photo-1529139574466-a303027c1d8b?auto=format&fit=crop&w=2000&q=85"
            alt="Detalhes de estilo em editorial contemporâneo"
            loading="lazy"
          />
          <div class="editorial-copy">
            <span class="eyebrow">Expressão & cuidado</span>
            <h2>Menos, mas<br />com presença</h2>
            <p>
              Acessórios pensados para completar a silhueta com equilíbrio,
              elegância e personalidade.
            </p>
            <a href="index.php#destaques" class="text-link">Ver destaques →</a>
          </div>
        </div>
      </section>
    </main>
    <?php
        include('../geral/footer.php');
    ?>
  </body>
</html>
