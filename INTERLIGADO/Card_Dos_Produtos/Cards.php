<?php
  include("conexao.php");
  include("../INTERLIGADO/principal.php");          
        
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
                      -- A mágica acontece aqui: filtramos apenas a primeira variação encontrada
                      AND vp.id_variacao = (
                         SELECT MIN(id_variacao) 
                         FROM tb_variacao_produto 
                         WHERE id_produto = p.id_produto
                     )
                    GROUP BY p.id_produto;
                    ";
            $r= mysqli_query($con,$sql);
            
                while($x = mysqli_fetch_assoc($r)){
                    echo"Nome:".$x['nome_produto']."<br>";
                    echo"preco:".$x['preco']."<br>";
                    echo"".$x['caminho_imagem']."";
                    echo"imagem:<img src='../uploads/produtos/".$x['caminho_imagem']."' alt='' width='100px'>";
                    //echo"<img src='uploads/produtos/69ba8847e72fc.jpeg' alt='' width='100px' >";
                    echo"<br><br><br>";
                }
        
    ?>
</html>