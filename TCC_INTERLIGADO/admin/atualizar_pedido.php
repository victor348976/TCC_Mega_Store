<?php
include "../geral/conexao.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: gerenciar_pedidos.php?erro=1');
    exit;
}

$idVenda = (int) ($_POST['id_venda'] ?? 0);
$status = trim($_POST['status_venda'] ?? 'Pendente');

if ($idVenda <= 0) {
    header('Location: gerenciar_pedidos.php?erro=1');
    exit;
}

$sql = "UPDATE tb_venda SET status_venda = ? WHERE id_venda = ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, 'si', $status, $idVenda);

if (mysqli_stmt_execute($stmt)) {
    header('Location: gerenciar_pedidos.php?sucesso=1');
    exit;
}

header('Location: gerenciar_pedidos.php?erro=1');
exit;
