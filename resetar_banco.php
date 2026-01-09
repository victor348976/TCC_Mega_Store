
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Resetar Banco</title>
</head>
<body>

<h2>RESETAR BANCO DE DADOS</h2>
<h4>Este arquivo serve que para quando terminar de testar as interações com o banco, vc possa deixa-lo "LIMPO", até que comecemos a parte visual</h4>
<h4>Este arquivo, reseta apenas as tabelas'tb_usuario', 'tb_imagem_produto' e 'tb_produto'</h4>
<form action="resetar_banco.php" method="post"
      onsubmit="return confirm('TEM CERTEZA? ISSO APAGA TODOS OS DADOS!');">

    <label>
        <input type="checkbox" name="confirmacao" required>
        Eu entendo que essa ação é irreversível
    </label>

    <br><br>

    <input type="submit" value="RESETAR BANCO">
</form>

</body>
</html>

<?php
include("conexao.php");

if (!isset($_POST['confirmacao'])) {
    die("Ação não autorizada.");
}

$tabelas = [
    'tb_usuario',
    'tb_imagem_produto',
    'tb_produto',
];

mysqli_query($con, "SET FOREIGN_KEY_CHECKS = 0");

foreach ($tabelas as $tabela) {
    mysqli_query($con, "TRUNCATE TABLE $tabela");
}

mysqli_query($con, "SET FOREIGN_KEY_CHECKS = 1");

echo "Banco resetado com sucesso!";

$pasta = "Cad_PRODUTO_funcional/uploads/produtos"; // caminho da pasta

$arquivos = glob($pasta . "/*");

foreach ($arquivos as $arquivo) {
    if (is_file($arquivo)) {
        unlink($arquivo);
    }
}
echo "Pasta de imagens, limpa com sucesso";
?>