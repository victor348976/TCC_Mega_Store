<?php
include("includes/header.php");

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
                      AND gen.id_genero = 2
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
    <meta name="description" content="Explore a coleção feminina Mega Store." />
    <title>Feminino | Mega Store</title>
    <link rel="stylesheet" href="css/style.css" />
    <script defer src="js/produtos.js"></script>
    <script defer src="js/script.js"></script>
  </head>
  <body data-page="feminino">
    <div data-site-header></div>
    <main>
      <section class="hero">
        <img
          src="https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=2100&q=90"
          alt="Editorial feminino com silhuetas contemporâneas"
        />
        <div class="hero-copy">
          <span class="eyebrow">Mega Store · Coleção</span>
          <h1>Sessão<br />feminina</h1>
          <p>Design, atitude e a sofisticação que é só sua.</p>
          <a class="button" href="#produtos">Explorar sessão</a>
        </div>
      </section>
      <section class="container" id="produtos">
        <div class="catalog-intro">
          <span class="eyebrow">A coleção</span>
          <h1>Feminino</h1>
          <p>
            Caimentos precisos e texturas escolhidas para expressar sua própria
            elegância.
          </p>
        </div>
        <div class="filter-row">
          <span>Seleção feminina</span><span id="product-count"></span>
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
            src="https://images.unsplash.com/photo-1539109136881-3be0616acf4b?auto=format&fit=crop&w=2000&q=85"
            alt="Retrato editorial de moda feminina"
            loading="lazy"
          />
          <div class="editorial-copy">
            <span class="eyebrow">Forma & expressão</span>
            <h2>Uma presença<br />que é sua</h2>
            <p>
              Peças que valorizam a individualidade com linhas leves, matérias
              marcantes e atenção ao movimento.
            </p>
            <a href="index.php#destaques" class="text-link"
              >Descobrir destaques →</a
            >
          </div>
        </div>
      </section>
    </main>
    <div data-site-footer></div>
  </body>
</html>
