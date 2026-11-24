<?php
  include("../geral/conexao.php");
  include("../geral/head.php");
  //include("../INTERLIGADO/principal.php");          
        
            $sql = "SELECT p.*, ip.*, ct.*, gen.*, vp.*
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
                    GROUP BY p.id_produto;
                    ";
            $r= mysqli_query($con,$sql);
            
                
        
    ?>
    <style>
.container-produtos {
    display: grid;
    grid-template-columns: repeat(5, 1fr); /* 5 por linha */
    gap: 15px;
    padding: 20px;
}

.card-produto {
    border: 1px solid #ccc;
    border-radius: 10px;
    padding: 10px;
    text-align: center;
    background: #fff;
    transition: 0.3s;
}

.card-produto:hover {
    transform: scale(1.05);
}

.card-produto img {
    width: 100%;
    height: 120px;
    object-fit: cover;
    border-radius: 8px;
}

.card-produto h3 {
    font-size: 16px;
    margin: 10px 0 5px;
}

.card-produto .preco {
    color: green;
    font-weight: bold;
}

/* Responsivo */
@media (max-width: 1000px) {
    .container-produtos {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 600px) {
    .container-produtos {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>

<?php
echo "<div class='container-produtos'>";

while($x = mysqli_fetch_assoc($r)){
    echo "
    <form method='get' action='Card_venda_produto.php'>
    <input type='hidden' name='id_produto' value='".$x['id_produto']."'>
    <button type='submit'>
    <div class='card-produto'>
        <img src='../produtos/uploads/".$x['caminho_imagem']."' alt='Imagem do produto'>

        <h3>".$x['nome_produto']."</h3>

        <p class='preco'>R$ ".$x['preco']."</p>
    </div>
    </button>
    </form>
    ";
}

echo "</div>";

?>

</html>