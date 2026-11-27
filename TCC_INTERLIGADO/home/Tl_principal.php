<!DOCTYPE html>
<html lang='pt-BR'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta name='description' content='Mega Store — Alta costura, tecidos finos e roupas exclusivas. Descubra a arte da trama e a elegância do vestir.'>
    <title>Mega Store | Alta Costura & Tecidos Finos</title>

    <!-- Google Fonts -->
    <link rel='preconnect' href='https://fonts.googleapis.com'>
    <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
    <link href='https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Inter:wght@200;300;400;500;600&display=swap' rel='stylesheet'>

    <link rel='stylesheet' href='Tl_princip.css'>
    <link rel='stylesheet' href='../geral/css/cabecalho.css'>
    
</head>
<body>
    <?php
  include('../geral/conexao.php');
  //include('../INTERLIGADO/principal.php');          
        
            $sql = 'SELECT p.*, ip.*, ct.*, gen.*, vp.*
                    FROM  tb_produto p, 
                          tb_imagem_produto ip, 
                          tb_categoria ct, 
                          tb_genero gen, 
                          tb_variacao_produto vp
                    WHERE p.id_produto = vp.id_produto 
                      AND vp.id_variacao = ip.id_variacao 
                      AND gen.id_genero = p.id_genero 
                      AND ct.id_categoria = p.id_categoria
                      -- filtramos apenas a primeira variação encontrada
                      AND vp.id_variacao = (
                         SELECT MIN(id_variacao) 
                         FROM tb_variacao_produto 
                         WHERE id_produto = p.id_produto
                     )
                    GROUP BY p.id_produto
                    LIMIT 15;
                    ';
            $r= mysqli_query($con,$sql); 
    ?>

    <!-- ═══════════════ HEADER / NAVBAR ═══════════════ -->
    <?php include('../mega-store/includes/header.php'); ?>
        

    
    <section class='hero' id='hero'>
        <div class='hero__image-wrapper'>
            <img src='img/hero.png' alt='Mega Store — Alta costura e elegância' class='hero__image' id='hero-image'>
            <div class='hero__overlay'></div>
        </div>
        <div class='hero__content reveal'>
            <p class='hero__subtitle'>Alta Costura & Tecidos Finos</p>
            <h1 class='hero__title'>A Arte da Trama,<br>a Elegância do Vestir</h1>
            <div class='hero__divider'></div>
            <a href='../mega-store' class='hero__cta' id='hero-cta'>Explorar Coleção</a>
        </div>
    </section>

   
    <section class='categories' id='colecoes'>
        <div class='categories__grid'>
            <!-- Card 1: Tecidos Nobres -->
            <a href='#tecidos' class='category-card reveal' id='card-tecidos'>
                <div class='category-card__image-wrapper'>
                    <img src='img/tecidos-nobres.png' alt='Tecidos nobres — seda, cashmere e linho' class='category-card__image'>
                </div>
                <div class='category-card__content'>
                    <span class='category-card__label'>Descubra</span>
                    <h2 class='category-card__title'>Tecidos Nobres</h2>
                    <p class='category-card__desc'>Seda italiana, cashmere mongol e linho belga — matérias-primas selecionadas com rigor artesanal.</p>
                    <span class='category-card__link'>Ver Tecidos &rarr;</span>
                </div>
            </a>

            <!-- Card 2: Ready-to-Wear -->
            <a href='#destaques' class='category-card reveal' id='card-rtw'>
                <div class='category-card__image-wrapper'>
                    <img src='img/ready-to-wear.png' alt='Coleções Ready-to-Wear' class='category-card__image'>
                </div>
                <div class='category-card__content'>
                    <span class='category-card__label'>Explore</span>
                    <h2 class='category-card__title'>Coleções Ready-to-Wear</h2>
                    <p class='category-card__desc'>Peças exclusivas que transitam do ateliê à vida. Alfaiataria impecável e design contemporâneo.</p>
                    <span class='category-card__link'>Ver Coleção &rarr;</span>
                </div>
            </a>
        </div>
    </section>

    <div class='marquee' aria-hidden='true'>
        <div class='marquee__track'>
            <span>SEDA &nbsp;◆&nbsp; CASHMERE &nbsp;◆&nbsp; LINHO &nbsp;◆&nbsp; ALGODÃO EGÍPCIO &nbsp;◆&nbsp; LÃ MERINO &nbsp;◆&nbsp; ALTA COSTURA &nbsp;◆&nbsp; EXCLUSIVIDADE &nbsp;◆&nbsp; ELEGÂNCIA &nbsp;◆&nbsp;</span>
            <span>SEDA &nbsp;◆&nbsp; CASHMERE &nbsp;◆&nbsp; LINHO &nbsp;◆&nbsp; ALGODÃO EGÍPCIO &nbsp;◆&nbsp; LÃ MERINO &nbsp;◆&nbsp; ALTA COSTURA &nbsp;◆&nbsp; EXCLUSIVIDADE &nbsp;◆&nbsp; ELEGÂNCIA &nbsp;◆&nbsp;</span>
        </div>
    </div>

  
    <section class='products' id='destaques'>
        <div class='products__header reveal'>
            <span class='products__label'>Curadoria</span>
            <h2 class='products__title'>Destaques da Coleção</h2>
            <p class='products__subtitle'>Peças selecionadas que definem a estação.</p>
        </div>

        <div class='products__grid'>
            
            <!-- Product 7 -->
             <?php
                while($x = mysqli_fetch_assoc($r)){
                    echo "
                    <form method='get' action='Card_venda_produto.php'>
                    <input type='hidden' name='id_produto' value='".$x['id_produto']."'>
                    <button type='submit'>
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

    <!-- ═══════════════ ABOUT / STORY ═══════════════ -->
    <section class='story' id='sobre'>
        <div class='story__inner'>
            <div class='story__text reveal'>
                <span class='story__label'>Nossa Essência</span>
                <h2 class='story__title'>Tradição, Artesania<br>& Inovação</h2>
                <p class='story__desc'>Desde 1987, a Mega Store cultiva a arte de vestir com excelência. Cada peça é um encontro entre a tradição da alfaiataria europeia e a ousadia do design contemporâneo. Nossos tecidos são selecionados nas melhores tecelagens do mundo — de Como a Kyoto — para oferecer uma experiência sensorial única.</p>
                <a href="../SOBRENOS/sobre_nos.php" '#contato' class='story__cta'>Conheça Nossa História &rarr;</a>
            </div>
            <div class='story__visual reveal'>
                <div class='story__image-frame'>
                    <img src='img/product-dress.png' alt='Ateliê Mega Store' class='story__image'>
                </div>
                <div class='story__accent-line'></div>
            </div>
        </div>
    </section>

    <!-- ═══════════════ NEWSLETTER ═══════════════ -->
    <section class='newsletter' id='contato'>
        <div class='newsletter__inner reveal'>
            <span class='newsletter__label'>Exclusividade</span>
            <h2 class='newsletter__title'>Receba Primeiro</h2>
            <p class='newsletter__desc'>Inscreva-se para acesso antecipado a novas coleções, tecidos exclusivos e convites para eventos privados.</p>
            <form class='newsletter__form' id='newsletter-form'>
                <div class='newsletter__input-wrapper'>
                    <input type='email' class='newsletter__input' id='newsletter-email' placeholder='seu@email.com' required aria-label='Seu e-mail'>
                    <button type='submit' class='newsletter__btn' id='newsletter-submit'>Assinar</button>
                </div>
                <p class='newsletter__feedback' id='newsletter-feedback'></p>
            </form>
        </div>
    </section>

    <?php
        include('../geral/footer.php');
    ?>

    <script src='Tl_princip.js'>
        
    </script>
</body>
</html>