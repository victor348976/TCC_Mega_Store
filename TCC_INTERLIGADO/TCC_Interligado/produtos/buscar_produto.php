<?php
include("../geral/conexao.php");
include("../geral/head.php");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos</title>
</head>
<body>
    <center>
        <form method="post" action="buscar_produto.php">
            <table border="0">
                <tr>
                    <td align="right" width="25%">
                        Produtos:
                    </td>
                    <td align="left" width="57%">
                        <div>
                            <select name="id_produto" required>
                                <option value="">
                                    Selecione o produto
                                </option>
                                <?php
                                $sql = "SELECT *
        FROM tb_produto";
                                $r = mysqli_query($con, $sql);
                                while ($x = mysqli_fetch_assoc($r)) {
                                    echo "<option value=" . $x["id_produto"] . ">" . $x["nome_produto"] ."</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td align="center" colspan="2">
                        <br>
                        <input
                            type="submit"
                            value="Buscar"
                            name="buscar">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input
                            type="button"
                            onclick="location.replace('buscar_produto.php');"
                            value="Limpar">
                         <br>
                    </td>
                </tr>
            </table>
        </form>
        <?php

        if (isset($_POST["buscar"])) {

            $id_produto = $_POST["id_produto"];

            $sql = "SELECT *
        FROM tb_produto p,
             tb_variacao_produto vp,
             tb_imagem_produto ip,
             tb_categoria ct,
             tb_genero gen

        WHERE p.id_produto = $id_produto
          AND vp.id_produto = p.id_produto
          AND ip.id_variacao = vp.id_variacao
          AND gen.id_genero = p.id_genero
          AND ct.id_categoria = p.id_categoria

        GROUP BY p.id_produto";

            $r = mysqli_query($con, $sql);

            if (mysqli_num_rows($r) > 0) {

                while ($x = mysqli_fetch_assoc($r)) {

                    echo "
<br><br>
<b>ID Produto:</b> " . $x['id_produto'] . "<br>
<b>Nome:</b> " . $x['nome_produto'] . "<br>
<b>Descrição:</b> " . $x['descricao_produto'] . "<br>
<b>Preço:</b> R$ " . $x['preco'] . "<br>
<b>Categoria:</b> " . $x['nome_categoria'] . "<br>
<b>Gênero:</b> " . $x['nome_genero'] . "<br>
";

                    if ($x['ativo'] == 1) {
                        echo "<b>Ativo:</b> Sim<br>";
                    } else {
                        echo "<b>Ativo:</b> Não<br>";
                    }
                    echo "<b>Data de cadastro:</b> " . $x['data_cadastro'] . "<br><br>";
                    echo "<img src='../produtos/uploads/" . $x['caminho_imagem'] . "'alt='Imagem do produto'width='250'>";
                }
            } else {
                echo "<br><br><font color='red'>Produto não encontrado.</font>";
            }
        }
        ?>
    </center>
</body>
</html>