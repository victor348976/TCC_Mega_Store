<?php
include("../geral/conexao.php");
if (empty($_SESSION['id_usuario'])) {
        echo "<a href='../auth/Cadastro_mega_store.php'>POR FAVOR, SE CADASTRE PARA ACESSAR O SITE!</a>";
    } else {
      $id_usuario = $_SESSION['id_usuario'];
$sql = "SELECT p.*, ip.*, ct.*, gen.*, vp.*
                    FROM  tb_produto p, 
                          tb_imagem_produto ip, 
                          tb_categoria ct, 
                          tb_genero gen, 
                          tb_variacao_produto vp,
                          tb_carrinho c,
                          tb_usuario u
                    WHERE p.id_produto = vp.id_produto 
                      AND vp.id_variacao = ip.id_variacao 
                      AND gen.id_genero = p.id_genero 
                      AND ct.id_categoria = p.id_categoria
                      AND c.id_variacao = vp.id_variacao
                      AND u.id_usuario = $id_usuario
                      AND c.id_usuario = u.id_usuario
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
      content="Revise os produtos do seu carrinho Mega Store."
    />
    <title>Seu carrinho | Mega Store</title>
    <link rel="stylesheet" href="css/style.css" />
    <script defer src="js/produtos.js"></script>
    <script defer src="js/script.js"></script>
    <script defer src="js/carrinho.js"></script>
  </head>
  <body data-page="carrinho">
    <div data-site-header></div>
    <main class="container" style="padding-top: 48px">
      <h1 class="cart-title">Seu carrinho</h1>
      <div class="cart-layout" id="cart-content"> 
        <?php
        if (mysqli_num_rows($resultado) > 0) {
            while ($row = mysqli_fetch_assoc($resultado)) {
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
            }
        } else {
            echo "<p>Seu carrinho está vazio.</p>";
        }
        ?>  
      </div>
      
    </main>
    <div data-site-footer></div>
  </body>
</html>
<?php
}
?>