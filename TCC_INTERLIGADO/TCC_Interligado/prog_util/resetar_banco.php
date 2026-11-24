<?php
  require_once __DIR__ . '/../geral/auth.php';
  require_admin();
  include("../geral/head.php");

  if (empty($_SESSION['reset_token'])) {
      $_SESSION['reset_token'] = bin2hex(random_bytes(32));
  }

  $resultadoReset = '';
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $tokenEnviado = $_POST['token'] ?? '';
      if (!is_string($tokenEnviado) || !hash_equals($_SESSION['reset_token'], $tokenEnviado)) {
          http_response_code(400);
          exit('Solicitação inválida.');
      }

      if (!isset($_POST['confirmacao'])) {
          http_response_code(400);
          exit('Confirme a operação para continuar.');
      }

      if (!mysqli_query($con, 'SET FOREIGN_KEY_CHECKS = 0')) {
          error_log('Falha ao preparar reset do banco Mega Store: ' . mysqli_error($con));
          http_response_code(500);
          exit('Não foi possível iniciar o reset do banco.');
      }

      $falhaReset = false;
      foreach (['tb_usuario', 'tb_imagem_produto', 'tb_produto', 'tb_variacao_produto'] as $tabela) {
          if (!mysqli_query($con, "TRUNCATE TABLE $tabela")) {
              error_log('Falha ao resetar ' . $tabela . ': ' . mysqli_error($con));
              $falhaReset = true;
              break;
          }
      }
      if (!mysqli_query($con, 'SET FOREIGN_KEY_CHECKS = 1')) {
          error_log('Falha ao reativar chaves estrangeiras do banco Mega Store: ' . mysqli_error($con));
          $falhaReset = true;
      }
      if ($falhaReset) {
          http_response_code(500);
          exit('O reset do banco não foi concluído. Consulte o log do servidor.');
      }

      $arquivos = glob(__DIR__ . '/../produtos/uploads/*');
      foreach ($arquivos as $arquivo) {
          if (is_file($arquivo) && !unlink($arquivo)) {
              http_response_code(500);
              exit('O banco foi resetado, mas não foi possível remover todas as imagens.');
          }
      }

      $_SESSION['reset_token'] = bin2hex(random_bytes(32));
      $resultadoReset = 'Banco e imagens resetados com sucesso.';
  }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Resetar Banco</title>
</head>
<body>

<h2>RESETAR BANCO DE DADOS</h2>
<h4>Este arquivo serve que para quando terminar de testar as interações com o banco, vc possa deixa-lo "LIMPO", até que comecemos a parte visual</h4>
<h4>Este arquivo, reseta apenas as tabelas'tb_usuario', 'tb_imagem_produto', 'tb_variacao_produto' 'tb_produto'</h4>
<form action="resetar_banco.php" method="post"
      onsubmit="return confirm('TEM CERTEZA? ISSO APAGA TODOS OS DADOS!');">

    <input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION['reset_token'], ENT_QUOTES, 'UTF-8') ?>">
    <label>
        <input type="checkbox" name="confirmacao" required>
        Eu entendo que essa ação é irreversível
    </label>

    <br><br>

    <input type="submit" value="RESETAR BANCO">
</form>
<?php if ($resultadoReset !== ''): ?>
    <p role="status"><?= htmlspecialchars($resultadoReset, ENT_QUOTES, 'UTF-8') ?></p>
<?php endif; ?>

</body>
</html>