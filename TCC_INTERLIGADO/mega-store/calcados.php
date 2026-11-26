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
      content="Calçados contemporâneos para acompanhar todos os caminhos."
    />
    <title>Calçados | Mega Store</title>
    <link rel="stylesheet" href="css/style.css" />
    <script defer src="js/produtos.js"></script>
    <script defer src="js/script.js"></script>
  </head>
  <body data-page="calcados">
    <div data-site-header></div>
    <main>
      <section class="hero">
        <img
          src="https://images.unsplash.com/photo-1543163521-1bf539c55dd2?auto=format&fit=crop&w=2100&q=90"
          alt="Calçados de design contemporâneo"
        />
        <div class="hero-copy">
          <span class="eyebrow">Mega Store · Passo a passo</span>
          <h1>O caminho<br />é seu</h1>
          <p>Formas essenciais, conforto pensado e acabamento para durar.</p>
          <a class="button" href="#produtos">Explorar calçados</a>
        </div>
      </section>
      <section class="container" id="produtos">
        <div class="catalog-intro">
          <span class="eyebrow">A coleção</span>
          <h1>Calçados</h1>
          <p>
            Modelos versáteis para acompanhar seu dia, da primeira hora até o
            último compromisso.
          </p>
        </div>
        <div class="filter-row">
          <span>Seleção de calçados</span><span id="product-count"></span>
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
      <section class="section">
        <div class="editorial">
          <img
            src="https://images.unsplash.com/photo-1495555961986-6d4c1ecb7be3?auto=format&fit=crop&w=2000&q=85"
            alt="Detalhes de calçados em composição editorial"
            loading="lazy"
          />
          <div class="editorial-copy">
            <span class="eyebrow">Conforto & intenção</span>
            <h2>Feitos para<br />acompanhar</h2>
            <p>
              Design descomplicado e conforto em cada passo, com a atenção aos
              detalhes que faz diferença.
            </p>
            <a href="index.php" class="text-link">Ver destaques →</a>
          </div>
        </div>
      </section>
    </main>
    <div data-site-footer></div>
  </body>
</html>
