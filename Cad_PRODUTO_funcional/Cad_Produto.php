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
        <form method="post" action="Cad_Produto.php" enctype="multipart/form-data">
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
                        echo"<option value=".$x["id_genero"].">".$x["nome_genero"]."</option>";
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
                        echo"<option value=".$x["id_categoria"].">".$x["nome_categoria"]."</option>";
                      }
                      ?>
                    </select>
                  </div>
                 </td>
                </tr>
                <tr>
                 <td align="right" width="25%">Imagem: </td>
                 <td align="left" width="57%">
                  <div name="repsenha">
                    <input type="file" name="imagem" accept="image/*" required>
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
echo $data;

if (isset($_POST["cadastrar"])) {

    $produto   = $_POST["produto"];
    $desc      = $_POST["desc"];
    $preco     = $_POST["preco"];
    $genero    = $_POST["genero"];
    $categoria = $_POST["categoria"];
    $erro = '';

    if ($produto == '') {
        $erro .= "Digite o nome do produto<br>";
    }
    if ($desc == '') {
        $erro .= "Digite a descrição<br>";
    }
    if ($preco == '') {
        $erro .= "Digite um preço<br>";
    }
    if ($genero == '') {
        $erro .= "Selecione o gênero<br>";
    }
    if ($categoria == '') {
        $erro .= "Selecione a categoria<br>";
    }

    if ($erro == '') {

        $sql = "INSERT INTO tb_produto 
        (nome_produto, descricao_produto, preco, id_categoria, id_genero, ativo, data_cadastro) 
        VALUES 
        ('$produto', '$desc', '$preco', '$categoria', '$genero', '1', '$data')";
        mysqli_query($con, $sql);

        $id_produto = mysqli_insert_id($con);

        $imagem = $_FILES['imagem'];
        $nomeOriginal = $imagem['name'];
        $tmp = $imagem['tmp_name'];
        $extensao = pathinfo($nomeOriginal, PATHINFO_EXTENSION);

        $novoNome = uniqid() . "." . $extensao;
        $pasta = "uploads/produtos/";

        if (!is_dir($pasta)) {
            mkdir($pasta, 0777, true);
        }

        move_uploaded_file($tmp, $pasta . $novoNome);

        $sql = "INSERT INTO tb_imagem_produto (id_produto, caminho_imagem)
                VALUES ('$id_produto', '$novoNome')";
        mysqli_query($con, $sql);

        echo "<font color=green size=4>Produto Cadastrado com Sucesso</font>";
    } else {
        echo "<font color=red size=4>$erro</font>";
    }
}
?>

    </center>
</html>