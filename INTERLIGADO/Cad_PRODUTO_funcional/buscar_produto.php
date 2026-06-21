<?php
  include("conexao.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos</title>
</head>
<body>
    <center>
        <form method="post" action="buscar_produto.php" enctype="multipart/form-data">
            <table border="0">
                <tr>
                 <td align="right" width="25%">Produtos: </td>
                 <td align="left" width="57%">
                  <div name="repsenha">
                    <select name="id_produto" required>
                      <option value="">Selecione o produto</option>
                      <?php
                      $sql="SELECT *
                            FROM   tb_produto";
                      $r= mysqli_query($con,$sql);
                      while($x = mysqli_fetch_assoc($r)){
                        echo"<option value=".$x["id_produto"].">".$x["nome_produto"]."</option>";
                      }
                      ?>
                    </select>
                  </div>
                 </td>
                </tr>
                <tr>
                 <td align="center" colspan="2">
                  <br>
                  <input type="submit" value="Buscar" name="buscar">
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <input type="button" onclick="location.replace('buscar_produto.php');" value="Limpar"><br>
                 </td>
                </tr>
            </table> 
        </form>
    </body>
    <?php                
        if (isset($_POST["buscar"])) {
            $id_produto = $_POST["id_produto"];
            $sql = "SELECT *
                    FROM tb_produto p, tb_imagem_produto ip, tb_categoria ct, tb_genero gen
                    WHERE p.id_produto = $id_produto AND
                          ip.id_produto = $id_produto AND
                          gen.id_genero = p.id_genero AND
                          ct.id_categoria = p.id_categoria";
            $r= mysqli_query($con,$sql);
                while($x = mysqli_fetch_assoc($r)){
                    echo"Id_produto:".$x['id_produto']."<br>";
                    echo"Nome:".$x['nome_produto']."<br>";
                    echo"descricao:".$x['descricao_produto']."<br>";
                    echo"preco:".$x['preco']."<br>";
                    echo"categoria:".$x['nome_categoria']."<br>";
                    echo"genero:".$x['nome_genero']."<br>";
                    if($x['ativo']==1){
                        echo"ativo:Sim<br>";
                    }else{
                        echo"ativo:Não<br>";
                    }
                    echo"data de cadastro:".$x['data_cadastro']."<br>";
                    echo"imagem:<img src='uploads/produtos/".$x['caminho_imagem']."' alt=''>";
                    //echo"<img src='uploads/produtos/6961580e5ef8c.png' alt=''>";
                    break;
                }
        }
    ?>
</html>