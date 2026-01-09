<?php
  include("conexao.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produto</title>
</head>
<body>
    <center>
        <form method="get" action="Cad_Produto.php">
            <table border="0">
                <tr>
                    <td align="center" colspan="2">
                      <h2><strong>Cadastro Produto</strong></h2>
                    </td>
                </tr>
                <tr>
                    <td align="right" width="25%">Nome:
                    </td>
                    <td align="left" width="57%">
                      <div name='user'>
                        <input type="text" value="" name="produto"
                        placeholder="Digite o nome do Produto" required>
                      </div>
                    </td>
                </tr>
                <tr>
                    <td align="right" width="25%">Descrição:
                    </td>
                    <td align="left" width="57%">
                      <div name='user'>
                        <input type="text" value="" name="desc"
                        placeholder="Digite a descrição do produto" required>
                      </div>
                    </td>
                </tr>
                <tr>
                    <td align="right" width="25%">preço:
                    </td>
                    <td align="left" width="57%">
                      <div name='user'>R$
                        <input type="number" value="" name="preco" step="0.01" min="0"
                        placeholder="Digite o preco do produto" required>
                      </div>
                    </td>
                </tr>
                <tr>
                 <td align="right" width="25%">Genero: </td>
                 <td align="left" width="57%">
                  <div name="senha">
                    <select name="genero" required>
                      <option value="">Selecione o gênero</option>
                      <?php
                      $sql="SELECT *
                            FROM   tb_genero";
                      $r= mysqli_query($con,$sql);
                      while($x = mysqli_fetch_assoc($r)){
                        echo"<option value=".$x["id_genero"].">".$x["nome"]."</option>";
                      }
                      ?>
                    </select>
                  </div>
                 </td>
                </tr>
                <tr>
                 <td align="right" width="25%">Categoria: </td>
                 <td align="left" width="57%">
                  <div name="repsenha">
                    <select name="categoria" required>
                      <option value="">Selecione a categoria</option>
                      <?php
                      $sql="SELECT *
                            FROM   tb_categoria";
                      $r= mysqli_query($con,$sql);
                      while($x = mysqli_fetch_assoc($r)){
                        echo"<option value=".$x["id_categoria"].">".$x["nome"]."</option>";
                      }
                      ?>
                    </select>
                  </div>
                 </td>
                </tr>
                <tr>
                 <td align="center" colspan="2">
                  <br>
                  <input type="submit" value="Cadastrar" name="cadastrar">
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <input type="button" onclick="location.replace('Cad_user.php');" value="Limpar"><br>
                 </td>
                </tr>
            </table>
        </form>
    </body>
    <?php
    $data = date('Y/m/d');
    echo "$data";
     if(isset($_GET["cadastrar"])){
       $produto=$_GET["produto"];
       $desc=$_GET["desc"];
       $preco=$_GET["preco"];
       $genero=$_GET["genero"];
       $categoria=$_GET["categoria"];
       $erro='';
       if($produto==''){//verifica se o nome do produto não é vazio
         $erro.="Digite o nome do produto<br>";
       }
       if($desc==''){//verifica se a descricao não é vazio
         $erro.="Digite seu Email<br>";
       }
       if($preco==''){//verifica se o preco não é vazio
         $erro.="Digite um preco<br>";
       }
       if($genero==''){//verifica se o genero não é vazia
         $erro.="Selecione o genero do produto<br>";
       } 
       if($categoria==''){//verifica se a rcategoria  não é vazia
         $erro.="Selecione a categoria do produto<br>";
       }
       //variaveis para verificar se o preco tem virgula
       $precoP = str_replace(",", ".", $preco);
       if($erro==''){
        $sql = "INSERT INTO tb_produto (nome, descricao, preco, id_categoria, id_genero, ativo, data_cadastro) VALUES ('$produto', '$desc', '$preco', '$categoria', '$genero', '1', '$data')";
         $r= mysqli_query($con,$sql);
         echo"<font color=green size=4>Produto Cadastrado com Sucesso</font>";
       }
     else{
       echo"<font color=red size=4>$erro</font>";
     }
     }
    ?>
    </center>
</html>